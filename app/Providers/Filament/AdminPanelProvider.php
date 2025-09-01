<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard as FilamentDashboard;
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

            ->pages([
                FilamentDashboard::class,
            ])

            ->resources([
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\ProjectResource::class,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')

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
            })

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
