<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = -1;
    
    protected static string $view = 'filament.pages.dashboard';
    
    public function getTitle(): string
    {
        return 'Admin Dashboard';
    }
    
    public function getHeading(): string
    {
        return 'Welcome to Imhotion Admin';
    }
    
    public function getSubheading(): string
    {
        return 'Manage your projects, teams, and users from this central dashboard';
    }
}
