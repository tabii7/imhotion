<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard as FilamentDashboard;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\MenuItem;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // FILAMENT COMPLETELY DISABLED - Using custom admin pages only
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->homeUrl('/admin/dashboard')
            ->login() // Re-enabled but will redirect to custom login
            ->authGuard('web')
            ->brandName('Imhotion Admin')
            ->brandLogo(asset('images/imhotion.jpg'))
            ->favicon(asset('images/favicon.ico'))
            ->colors([
                'primary' => '#3B82F6',
                'gray' => '#6B7280',
                'success' => '#10B981',
                'warning' => '#F59E0B',
                'danger' => '#EF4444',
                'info' => '#06B6D4',
            ])
            ->font('Inter')
            ->navigationGroups([])
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->pages([])
            ->resources([])
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                SubstituteBindings::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
