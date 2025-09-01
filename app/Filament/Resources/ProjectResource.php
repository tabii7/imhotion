<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Projects';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Project')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Project')
                        ->required()
                        ->maxLength(255),

                    Select::make('user_id')
                        ->label('Client')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending'     => 'Pending',
                            'in_progress' => 'In progress',
                            'completed'   => 'Completed',
                            'cancelled'   => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),

                    DatePicker::make('due_date')
                        ->label('Due date')
                        ->native(false)
                        ->displayFormat('d-m-Y')
                        ->nullable(),

                    DatePicker::make('completed_at')
                        ->label('Completed at')
                        ->native(false)
                        ->displayFormat('d-m-Y')
                        ->nullable(),

                    Textarea::make('pending_note')
                        ->label('Pending note')
                        ->rows(2)
                        ->columnSpanFull(),

                    Textarea::make('cancel_reason')
                        ->label('Cancel reason')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Project::query()
                ->with('user')
                ->withCount('documents')
            )
            ->defaultSort('id', 'desc')

            // Row click opens the popup (NOT Edit)
            ->recordUrl(null)
            ->recordAction('view')

            ->columns([
                TextColumn::make('display_code')
                    ->label('ID')
                    ->state(fn (Project $record) => now()->format('y') . (string) $record->id)
                    ->tooltip(fn (Project $record) => 'Internal ID: ' . $record->id)
                    ->copyable()
                    ->grow(false)
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Project')
                    ->extraAttributes(['class' => 'underline cursor-pointer'])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Client')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'pending'     => 'Pending',
                        'in_progress' => 'In progress',
                        'completed'   => 'Completed',
                        'cancelled'   => 'Cancelled',
                        default       => ucfirst((string) $state),
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'pending'     => 'gray',
                        'in_progress' => 'warning',
                        'completed'   => 'success',
                        'cancelled'   => 'danger',
                        default       => 'gray',
                    })
                    ->grow(false),

                TextColumn::make('status_info')
                    ->label('When / Note')
                    ->state(function (Project $record): string {
                        $fmt = fn ($val) => $val
                            ? ($val instanceof Carbon ? $val : Carbon::parse($val))->format('d-m-y')
                            : null;

                        $due  = $fmt($record->due_date ?? null);
                        $done = $fmt($record->completed_at ?? null);

                        return match ($record->status) {
                            'in_progress', 'pending' => $due
                                ? 'Delivery on ' . $due
                                : (filled($record->pending_note) ? (string) $record->pending_note : '—'),
                            'completed'   => $done ? 'Completed ' . $done : 'Completed —',
                            'cancelled'   => filled($record->cancel_reason) ? (string) $record->cancel_reason : 'Cancelled',
                            default       => $due ? 'Delivery on ' . $due : '—',
                        };
                    })
                    ->wrap(),

                // Docs count (white square, BLACK number; 0 by default)
                TextColumn::make('documents_count')
                    ->label('Docs')
                    ->sortable()
                    ->alignCenter()
                    ->getStateUsing(fn (Project $record) => (int) ($record->documents_count ?? 0))
                    ->formatStateUsing(function ($state) {
                        $count = is_numeric($state) ? (int) $state : 0;
                        return '<span style="display:inline-flex;align-items:center;justify-content:center;width:1.5rem;height:1.5rem;border-radius:0.25rem;background:#ffffff;color:#000000;border:1px solid #e5e7eb;font-size:0.75rem;font-weight:700;">'
                            . $count .
                            '</span>';
                    })
                    ->html(),
            ])

            ->filters([])

            ->actions([
                // Row-click action -> details + upload
                Action::make('view')
                    ->label('')
                    ->modalHeading(fn (Project $record) => 'Project: ' . $record->title)
                    ->modalWidth('3xl')
                    ->modalContent(fn (Project $record) => view('filament/projects/row-details', [
                        'record' => $record->load('user', 'documents'),
                    ]))
                    ->form([
                        FileUpload::make('new_documents')
                            ->label('Add documents')
                            ->multiple()
                            ->downloadable()
                            ->openable()
                            ->storeFiles(false)
                            ->helperText('Any file type. You can select multiple files.'),
                    ])
                    ->modalSubmitActionLabel('Upload')
                    ->action(function (Project $record, array $data): void {
                        if (!empty($data['new_documents']) && is_array($data['new_documents'])) {
                            foreach ($data['new_documents'] as $file) {
                                $original    = $file->getClientOriginalName(); // e.g. "specs.pdf"
                                $baseName    = pathinfo($original, PATHINFO_FILENAME); // "specs"
                                $dir         = "project-docs/{$record->id}";
                                // store with a unique physical filename to avoid overwrites, keep original filename in DB:
                                $storedName  = now()->format('YmdHis') . '_' . $original;
                                $path        = Storage::disk('public')->putFileAs($dir, $file, $storedName);

                                // DB row — NOTE: 'filename' is required by your schema
                                $record->documents()->create([
                                    'name'     => $baseName,             // display name (default = filename without extension)
                                    'filename' => $original,             // original filename
                                    'path'     => $path,                 // stored path
                                    'size'     => $file->getSize(),      // bytes
                                ]);
                            }
                        }

                        // Refresh count
                        $record->loadCount('documents');
                    }),

                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit'   => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
