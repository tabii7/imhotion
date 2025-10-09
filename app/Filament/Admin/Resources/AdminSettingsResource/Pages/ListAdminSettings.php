<?php

namespace App\Filament\Admin\Resources\AdminSettingsResource\Pages;

use App\Filament\Admin\Resources\AdminSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminSettings extends ListRecords
{
    protected static string $resource = AdminSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
