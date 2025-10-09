<?php

namespace App\Filament\Admin\Resources\AdminSettingsResource\Pages;

use App\Filament\Admin\Resources\AdminSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdminSettings extends ViewRecord
{
    protected static string $resource = AdminSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
