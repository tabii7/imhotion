<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Models\User;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Grid;

class TeamMemberResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Team';

    protected static ?string $navigationLabel = 'Team Members';

    protected static ?string $modelLabel = 'Team Member';

    protected static ?string $pluralModelLabel = 'Team Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Team Member Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Full Name'),
                        
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email Address'),
                        
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->label('Phone Number'),
                        
                        Textarea::make('bio')
                            ->rows(3)
                            ->label('Bio/Description'),
                        
                        Select::make('specialization_id')
                            ->label('Specialization')
                            ->relationship('specialization', 'name')
                            ->searchable()
                            ->preload(),
                        
                        Select::make('is_available')
                            ->options([
                                true => 'Available',
                                false => 'Busy',
                            ])
                            ->required()
                            ->default(true)
                            ->label('Availability'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'developer'))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('specialization.name')
                    ->label('Specialization')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('is_available')
                    ->label('Status')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Available' : 'Busy')
                    ->sortable(),
                
                TextColumn::make('projects_count')
                    ->counts('assignedProjects')
                    ->label('Active Projects')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_available')
                    ->options([
                        true => 'Available',
                        false => 'Busy',
                    ])
                    ->label('Availability'),
                
                SelectFilter::make('specialization_id')
                    ->label('Specialization')
                    ->relationship('specialization', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('assign_project')
                    ->label('Assign Project')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->form([
                        Select::make('project_id')
                            ->label('Select Project')
                            ->options(Project::whereNull('assigned_developer_id')->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function (User $record, array $data): void {
                        $project = Project::find($data['project_id']);
                        if ($project) {
                            $project->update(['assigned_developer_id' => $record->id]);
                        }
                    })
                    ->visible(fn (User $record): bool => $record->is_available),
                
                Action::make('view_projects')
                    ->label('View Projects')
                    ->icon('heroicon-o-folder')
                    ->color('info')
                    ->url(fn (User $record): string => route('filament.admin.resources.projects.index', ['tableFilters' => ['assigned_developer_id' => ['value' => $record->id]]]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListTeamMembers::route('/'),
            'view' => Pages\ViewTeamMember::route('/{record}'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}