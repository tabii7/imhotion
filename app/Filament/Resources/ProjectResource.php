<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Team & Projects';
    protected static ?string $navigationLabel = 'Projects';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Project Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Project Name'),

                        Select::make('user_id')
                            ->label('Client')
                            ->options(User::where('role', 'client')->pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Select::make('assigned_developer_id')
                            ->label('Assigned Developer')
                            ->options(User::where('role', 'developer')->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select a developer'),

                        Select::make('assigned_administrator_id')
                            ->label('Assigned Administrator')
                            ->options(User::whereIn('role', ['admin', 'administrator'])->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select an administrator'),

                        Textarea::make('topic')
                            ->required()
                            ->rows(4)
                            ->label('Description'),

                        Select::make('status')
                            ->options([
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'on_hold' => 'On Hold',
                                'finalized' => 'Finalized',
                                'canceled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('in_progress'),

                        Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                            ])
                            ->default('medium'),

                        DatePicker::make('start_date')
                            ->label('Start Date'),

                        DatePicker::make('end_date')
                            ->label('End Date'),

                        TextInput::make('total_days')
                            ->numeric()
                            ->label('Total Days')
                            ->default(0)
                            ->required(),

                        TextInput::make('estimated_hours')
                            ->numeric()
                            ->step(0.5)
                            ->label('Estimated Hours')
                            ->default(0)
                            ->required()
                            ->helperText('Enter the estimated hours for this project'),

                        Textarea::make('notes')
                            ->rows(3)
                            ->label('Notes'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Project $record): string => $record->topic ?? 'No description'),

                TextColumn::make('user.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('assignedDeveloper.name')
                    ->label('Developer')
                    ->sortable()
                    ->placeholder('Unassigned')
                    ->color(fn ($state): string => $state ? 'success' : 'danger'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'finalized' => 'success',
                        'on_hold' => 'warning',
                        'canceled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('progress')
                    ->label('Progress %')
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? $state . '%' : '0%')
                    ->color(fn ($state): string => match (true) {
                        $state >= 100 => 'success',
                        $state >= 75 => 'info',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('estimated_hours')
                    ->label('Est. Hours')
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => $state ? $state . 'h' : 'Not set')
                    ->color('info'),

                TextColumn::make('documents_count')
                    ->label('Documents')
                    ->counts('documents')
                    ->sortable()
                    ->color('info'),

                TextColumn::make('activities_count')
                    ->label('Activities')
                    ->counts('activities')
                    ->sortable()
                    ->color('primary'),

                TextColumn::make('delivery_date')
                    ->label('Deadline')
                    ->date()
                    ->sortable()
                    ->color(fn ($state): string => $state && $state < now() ? 'danger' : 'gray'),

                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'on_hold' => 'On Hold',
                        'finalized' => 'Finalized',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('assigned_developer_id')
                    ->label('Developer')
                    ->relationship('assignedDeveloper', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('priority')
                    ->options([
                        'high' => 'High',
                        'medium' => 'Medium',
                        'low' => 'Low',
                    ]),

                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue Projects')
                    ->query(fn (Builder $query): Builder => $query->where('delivery_date', '<', now())
                        ->whereNotIn('status', ['completed', 'cancelled', 'finalized'])),

                Tables\Filters\Filter::make('has_documents')
                    ->label('Has Documents')
                    ->query(fn (Builder $query): Builder => $query->has('documents')),

                Tables\Filters\Filter::make('no_developer')
                    ->label('Unassigned Projects')
                    ->query(fn (Builder $query): Builder => $query->whereNull('assigned_developer_id')),
            ])
            ->actions([
                ViewAction::make()
                    ->label('View Details')
                    ->url(fn (Project $record): string => route('admin.custom-projects.show', $record)),
                EditAction::make(),
                Action::make('assign')
                    ->label('Quick Assign')
                    ->icon('heroicon-o-user-plus')
                    ->color('info')
                    ->form([
                        Select::make('assigned_developer_id')
                            ->label('Assign Developer')
                            ->options(User::where('role', 'developer')->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select a developer'),
                        Select::make('assigned_administrator_id')
                            ->label('Assign Administrator')
                            ->options(User::whereIn('role', ['admin', 'administrator'])->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select an administrator'),
                    ])
                    ->action(function (Project $record, array $data): void {
                        $record->update($data);
                    }),
                Action::make('view_documents')
                    ->label('Documents')
                    ->icon('heroicon-o-document')
                    ->color('info')
                    ->url(fn (Project $record): string => route('admin.custom-projects.show', $record) . '#documents'),
                Action::make('view_activities')
                    ->label('Activities')
                    ->icon('heroicon-o-clock')
                    ->color('primary')
                    ->url(fn (Project $record): string => route('admin.custom-projects.show', $record) . '#activities'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assign_developer')
                        ->label('Assign Developer')
                        ->icon('heroicon-o-user-plus')
                        ->color('info')
                        ->form([
                            Select::make('assigned_developer_id')
                                ->label('Assign Developer')
                                ->options(User::where('role', 'developer')->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (array $data, $records): void {
                            $records->each(fn (Project $record) => $record->update($data));
                        }),
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'on_hold' => 'On Hold',
                                    'finalized' => 'Finalized',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records): void {
                            $records->each(fn (Project $record) => $record->update($data));
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}