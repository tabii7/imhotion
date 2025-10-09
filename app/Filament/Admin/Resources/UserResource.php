<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $modelLabel = 'User';
    protected static ?string $pluralModelLabel = 'Users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'client' => 'Client',
                                'developer' => 'Developer',
                                'administrator' => 'Administrator',
                            ])
                            ->required()
                            ->default('client')
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Profile Information')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(40)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('address')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('postal_code')
                            ->maxLength(20)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Select::make('country')
                            ->options([
                                'NL' => 'Netherlands',
                                'BE' => 'Belgium',
                                'DE' => 'Germany',
                                'FR' => 'France',
                                'UK' => 'United Kingdom',
                                'US' => 'United States',
                            ])
                            ->searchable()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Account Settings')
                    ->schema([
                        Forms\Components\TextInput::make('balance_days')
                            ->numeric()
                            ->default(0)
                            ->label('Available Days')
                            ->helperText('Number of days available for projects')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_available')
                            ->label('Available for Work')
                            ->default(true)
                            ->columnSpan(1),
                        Forms\Components\Select::make('specialization_id')
                            ->relationship('specialization', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-user.jpg')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'administrator',
                        'success' => 'developer',
                        'primary' => 'client',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance_days')
                    ->label('Available Days')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 20 => 'success',
                        $state >= 10 => 'warning',
                        $state > 0 => 'info',
                        default => 'danger',
                    }),
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean()
                    ->label('Available')
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialization.name')
                    ->label('Specialization')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'client' => 'Client',
                        'developer' => 'Developer',
                        'administrator' => 'Administrator',
                    ]),
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Available for Work'),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->nullable(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Created from'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Created until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('make_available')
                        ->label('Mark as Available')
                        ->icon('heroicon-o-check-circle')
                        ->action(function ($records) {
                            $records->each->update(['is_available' => true]);
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('make_unavailable')
                        ->label('Mark as Unavailable')
                        ->icon('heroicon-o-x-circle')
                        ->action(function ($records) {
                            $records->each->update(['is_available' => false]);
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
