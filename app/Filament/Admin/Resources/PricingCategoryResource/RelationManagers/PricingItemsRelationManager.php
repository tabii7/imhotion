<?php

namespace App\Filament\Admin\Resources\PricingCategoryResource\RelationManagers;

use App\Models\PricingItem;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Resources\Table;

class PricingItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('price')->numeric()->required(),
                Forms\Components\Select::make('price_unit')
                    ->options([
                        'per_day' => 'per day',
                        'per_hour' => 'per hour',
                        'per_year' => 'per year',
                        'per_project' => 'per project',
                    ])
                    ->default('per_day'),
                Forms\Components\TextInput::make('duration_years')->label('Valid for (years)')->numeric()->nullable(),
                Forms\Components\TextInput::make('discount_percent')->numeric()->nullable(),
                Forms\Components\Checkbox::make('has_gift_box')->label('Gift Box'),
                Forms\Components\Checkbox::make('has_project_files')->label('Project Files'),
                Forms\Components\Checkbox::make('has_weekends_included')->label('Weekends Included'),
                Forms\Components\Textarea::make('note')->rows(2),
                Forms\Components\Toggle::make('active')->default(true),
                Forms\Components\TextInput::make('sort')->numeric()->default(0),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->limit(30),
                    Tables\Columns\TextColumn::make('price')->formatStateUsing(fn (?float $state) => '€' . number_format($state, 2)),
                Tables\Columns\TextColumn::make('price_unit')->label('Unit'),
                Tables\Columns\IconColumn::make('has_gift_box')->boolean()->label('Gift'),
                Tables\Columns\IconColumn::make('has_project_files')->boolean()->label('Files'),
                Tables\Columns\IconColumn::make('has_weekends_included')->boolean()->label('Weekends'),
                Tables\Columns\TextColumn::make('discount_percent')->label('Discount'),
                Tables\Columns\BooleanColumn::make('active'),
                Tables\Columns\TextColumn::make('sort'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('sort');
    }
}
