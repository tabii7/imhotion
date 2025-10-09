<?php

namespace App\Filament\Admin\Resources\AdminSettingsResource\Pages;

use App\Filament\Admin\Resources\AdminSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminSettings extends EditRecord
{
    protected static string $resource = AdminSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
