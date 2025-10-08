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
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->homeUrl('/admin/dashboard')
            ->login()
            ->authGuard('web')
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Profile')
                    ->url(fn (): string => route('filament.admin.resources.users.edit', auth()->id()))
                    ->icon('heroicon-o-user'),
                'logout' => MenuItem::make()
                    ->label('Logout')
                    ->url(fn (): string => route('filament.admin.auth.logout'))
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->openUrlInNewTab(false),
            ])
            ->brandName('Imhotion Admin')
            ->brandLogo(asset('images/imhotion.jpg'))
            ->favicon(asset('images/favicon.ico'))
            ->colors([
                'primary' => '#6366F1',
                'gray' => '#6B7280',
                'success' => '#10B981',
                'warning' => '#F59E0B',
                'danger' => '#EF4444',
                'info' => '#06B6D4',
            ])
            ->font('Inter')
            ->navigationGroups([
                NavigationGroup::make('Management')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible(),
                NavigationGroup::make('Team & Projects')
                    ->icon('heroicon-o-user-group')
                    ->collapsible(),
                NavigationGroup::make('Business')
                    ->icon('heroicon-o-currency-dollar')
                    ->collapsible(),
                NavigationGroup::make('System')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible(),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])

            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])

            ->resources([
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\ProjectResource::class,
                \App\Filament\Resources\TeamResource::class,
                \App\Filament\Resources\PricingCategoryResource::class,
                \App\Filament\Resources\ReportResource::class,
                \App\Filament\Resources\SettingResource::class,
            ])

            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->widgets([
                \App\Filament\Admin\Widgets\KpisWidget::class,
                \App\Filament\Admin\Widgets\ProjectOverviewWidget::class,
                \App\Filament\Admin\Widgets\RecentActivityWidget::class,
            ])

            // Admin-scoped debug routes
            ->routes(function () {
                // existing test
                Route::get('/__proj_test2', function () {
                    if (!auth()->check()) return redirect('/admin/login');
                    try {
                        $c = \App\Models\Project::count();
                        return response("Admin panel OK. Projects = {$c}", 200);
                    } catch (\Throwable $e) {
                        return response('Admin panel error: ' . $e->getMessage(), 500);
                    }
                })->name('filament.admin.__proj_test2');

                // NEW: check if 'status' column exists and show a sample row
                Route::get('/__status_check', function () {
                    if (!auth()->check()) return redirect('/admin/login');

                    $has = Schema::hasColumn('projects', 'status');
                    $sample = Project::select('id','title','status')->orderBy('id','desc')->first();

                    return response()->json([
                        'has_status_column' => $has,
                        'sample' => $sample,
                    ]);
                })->name('filament.admin.__status_check');

                // Test logout functionality
                Route::get('/__test_logout', function () {
                    if (!auth()->check()) return redirect('/admin/login');
                    
                    Auth::logout();
                    request()->session()->invalidate();
                    request()->session()->regenerateToken();
                    
                    return redirect('/admin/login')->with('success', 'Logout successful');
                })->name('filament.admin.__test_logout');

                // Test dropdown functionality
                Route::get('/__test_dropdown', function () {
                    if (!auth()->check()) return redirect('/admin/login');
                    
                    return response()->json([
                        'user' => auth()->user()->name,
                        'dropdown_working' => true,
                        'logout_route' => route('filament.admin.auth.logout'),
                        'profile_route' => route('filament.admin.resources.users.edit', auth()->id()),
                    ]);
                })->name('filament.admin.__test_dropdown');
            })

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
