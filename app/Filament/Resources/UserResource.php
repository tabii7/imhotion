<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Users';

    protected static ?int $navigationSort = 10;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'full_name', 'email', 'phone', 'city', 'country'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) User::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Account')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('Full name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name')
                            ->label('Username / Short name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'client' => 'Client',
                            ])
                            ->required()
                            ->default('client')
                            ->native(false),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Hash::make($state) : null)
                            ->required(fn (string $context) => $context === 'create')
                            ->rule('confirmed')
                            ->helperText('Leave empty to keep current password'),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->required(fn (string $context) => $context === 'create')
                            ->label('Confirm password'),
                    ])->columns(2),

                Forms\Components\Section::make('Contact')
                    ->schema([
                        Forms\Components\TextInput::make('address')->maxLength(255),
                        Forms\Components\TextInput::make('postal_code')->label('Postal code')->maxLength(20),
                        Forms\Components\TextInput::make('city')->maxLength(255),
                        Forms\Components\TextInput::make('country')->maxLength(60),
                        Forms\Components\TextInput::make('phone')->tel()->maxLength(40),
                    ])->columns(2),

                Forms\Components\Section::make('Other')
                    ->schema([
                        Forms\Components\TextInput::make('balance_days')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->suffix('days'),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email verified at')
                            ->seconds(false)
                            ->helperText('Set a date/time to mark the email as verified'),
                    ])->columns(2),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_name')->label('Full name')->searchable()->sortable(),
                TextColumn::make('email')->copyable()->copyMessage('Email copied')->searchable()->sortable(),
                TextColumn::make('role')->badge()->sortable(),
                TextColumn::make('phone')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                TextColumn::make('country')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                TextColumn::make('balance_days')->label('Balance')->sortable(),
                TextColumn::make('email_verified_at')
                    ->dateTime('Y-m-d H:i')
                    ->label('Verified')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime('Y-m-d H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime('Y-m-d H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('role')->options([
                    'admin' => 'Admin',
                    'client' => 'Client',
                ]),
        TernaryFilter::make('verified')
                    ->label('Email verified')
                    ->nullable()
                    ->trueLabel('Verified')
                    ->falseLabel('Unverified')
                    ->queries(
            true: fn (\Illuminate\Database\Eloquent\Builder $q) => $q->whereNotNull('email_verified_at'),
            false: fn (\Illuminate\Database\Eloquent\Builder $q) => $q->whereNull('email_verified_at'),
            blank: fn (\Illuminate\Database\Eloquent\Builder $q) => $q
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('No users yet')
            ->emptyStateDescription('Create the first user to get started.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here later if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
