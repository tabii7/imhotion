<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingCategoryResource\Pages;
use App\Filament\Resources\PricingCategoryResource\RelationManagers\PricingItemsRelationManager;
use App\Models\PricingCategory;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class PricingCategoryResource extends Resource
{
    protected static ?string $model = PricingCategory::class;
    // Use an existing heroicon name to avoid SvgNotFound errors
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?string $navigationLabel = 'Pricing';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255),
                Forms\Components\Textarea::make('description')->rows(3),
                Forms\Components\Toggle::make('active')->label('Active')->default(true),
                Forms\Components\TextInput::make('sort')->numeric()->default(0),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('active')->label('Active'),
                Tables\Columns\TextColumn::make('items_count')->counts('items')->label('Items'),
                Tables\Columns\TextColumn::make('sort')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('sort');
    }

    public static function getRelations(): array
    {
        return [
            PricingItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricingCategories::route('/'),
            'create' => Pages\CreatePricingCategory::route('/create'),
            'edit' => Pages\EditPricingCategory::route('/{record}/edit'),
        ];
    }
}
