<?php

namespace App\Filament\Admin\Resources;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdminSettingsResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $modelLabel = 'Setting';
    protected static ?string $pluralModelLabel = 'Settings';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Settings')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique identifier for this setting'),
                        Forms\Components\Textarea::make('value')
                            ->required()
                            ->rows(3)
                            ->helperText('Setting value (can be JSON for complex data)'),
                        Forms\Components\TextInput::make('description')
                            ->maxLength(500)
                            ->helperText('Description of what this setting does'),
                        Forms\Components\Select::make('type')
                            ->options([
                                'string' => 'Text',
                                'number' => 'Number',
                                'boolean' => 'True/False',
                                'json' => 'JSON Data',
                                'email' => 'Email',
                                'url' => 'URL',
                            ])
                            ->required()
                            ->default('string'),
                        Forms\Components\Toggle::make('is_public')
                            ->label('Public Setting')
                            ->helperText('Can be accessed by frontend')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'string',
                        'success' => 'number',
                        'warning' => 'boolean',
                        'info' => 'json',
                        'danger' => 'email',
                        'gray' => 'url',
                    ]),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean()
                    ->label('Public'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'string' => 'Text',
                        'number' => 'Number',
                        'boolean' => 'True/False',
                        'json' => 'JSON Data',
                        'email' => 'Email',
                        'url' => 'URL',
                    ]),
                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('Public Settings'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('key');
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
            'index' => \App\Filament\Admin\Resources\AdminSettingsResource\Pages\ListAdminSettings::route('/'),
            'create' => \App\Filament\Admin\Resources\AdminSettingsResource\Pages\CreateAdminSettings::route('/create'),
            'view' => \App\Filament\Admin\Resources\AdminSettingsResource\Pages\ViewAdminSettings::route('/{record}'),
            'edit' => \App\Filament\Admin\Resources\AdminSettingsResource\Pages\EditAdminSettings::route('/{record}/edit'),
        ];
    }
}
