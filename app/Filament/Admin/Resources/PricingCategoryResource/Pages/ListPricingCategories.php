<?php

namespace App\Filament\Admin\Resources\PricingCategoryResource\Pages;

use App\Filament\Admin\Resources\PricingCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPricingCategories extends ListRecords
{
    protected static string $resource = PricingCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

