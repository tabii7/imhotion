<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReportResource\Pages;
use App\Models\ProjectReport;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ReportResource extends Resource
{
    protected static ?string $model = ProjectReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Business';
    
    protected static ?string $navigationLabel = 'Reports';
    
    protected static ?string $modelLabel = 'Report';
    
    protected static ?string $pluralModelLabel = 'Reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Report Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Report Name'),
                        
                        Textarea::make('description')
                            ->rows(3)
                            ->label('Description'),
                        
                        Select::make('type')
                            ->options([
                                'project_status' => 'Project Status Report',
                                'team_performance' => 'Team Performance Report',
                                'time_log_summary' => 'Time Log Summary',
                                'budget_analysis' => 'Budget Analysis',
                                'milestone_tracking' => 'Milestone Tracking',
                            ])
                            ->required()
                            ->label('Report Type'),
                        
                        Select::make('created_by')
                            ->label('Created By')
                            ->options(User::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->label('Created By'),
                    ])->columns(2),
                
                Section::make('Report Data')
                    ->schema([
                        DateTimePicker::make('generated_at')
                            ->label('Generated At'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                TextColumn::make('name')
                    ->label('Report Name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'project_status' => 'blue',
                        'team_performance' => 'green',
                        'time_log_summary' => 'yellow',
                        'budget_analysis' => 'purple',
                        'milestone_tracking' => 'orange',
                    })
                    ->sortable(),
                
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('generated_at')
                    ->label('Generated At')
                    ->dateTime()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'project_status' => 'Project Status Report',
                        'team_performance' => 'Team Performance Report',
                        'time_log_summary' => 'Time Log Summary',
                        'budget_analysis' => 'Budget Analysis',
                        'milestone_tracking' => 'Milestone Tracking',
                    ]),
                
                SelectFilter::make('created_by')
                    ->label('Created By')
                    ->options(User::all()->pluck('name', 'id')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                
                Action::make('generate')
                    ->label('Generate Report')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (ProjectReport $record): void {
                        $record->update(['generated_at' => now()]);
                    }),
                
                Action::make('export')
                    ->label('Export')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->url(fn (ProjectReport $record): string => route('admin.reports.export', $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'view' => Pages\ViewReport::route('/{record}'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
