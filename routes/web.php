       <?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ProjectDocumentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ProjectRequirementController;
use App\Http\Controllers\Admin\DeveloperManagementController;
use App\Http\Controllers\TeamController;
use App\Models\Project;
use App\Models\PricingCategory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Public site + auth + client + admin document actions
|--------------------------------------------------------------------------
*/

// Public landing
Route::get('/', function () {
    $categories = PricingCategory::with(['items' => function ($query) {
        $query->where('active', true)->orderBy('sort');
    }])
    ->where('active', true)
    ->orderBy('sort')
    ->get();

    return view('home', compact('categories'));
})->name('home');

// Dashboard routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/services', [DashboardController::class, 'services'])->name('dashboard.services');
    Route::get('/dashboard/transactions', [DashboardController::class, 'transactions'])->name('dashboard.transactions');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    
    // Client Projects Management
    Route::get('/dashboard/projects', [DashboardController::class, 'projects'])->name('dashboard.projects');
    Route::get('/dashboard/projects/create', [DashboardController::class, 'createProject'])->name('dashboard.projects.create');
    Route::post('/dashboard/projects', [DashboardController::class, 'storeProject'])->name('dashboard.projects.store');
    Route::get('/dashboard/projects/{project}', [DashboardController::class, 'showProject'])->name('dashboard.projects.show');
    Route::get('/dashboard/projects/{project}/edit', [DashboardController::class, 'editProject'])->name('dashboard.projects.edit');
    Route::put('/dashboard/projects/{project}', [DashboardController::class, 'updateProject'])->name('dashboard.projects.update');
    Route::delete('/dashboard/projects/{project}', [DashboardController::class, 'destroyProject'])->name('dashboard.projects.destroy');
    
    // Client Progress Tracking Routes
    Route::get('/progress', [App\Http\Controllers\Client\ProgressController::class, 'index'])->name('progress.index');
    Route::get('/projects/{project}/progress', [App\Http\Controllers\Client\ProgressController::class, 'show'])->name('progress.show');
    Route::get('/projects/{project}/files/{file}/download', [App\Http\Controllers\Client\ProgressController::class, 'downloadFile'])->name('progress.download');
});

// Add missing profile.edit route to fix navigation dropdown
Route::get('/profile', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('profile.edit');

// Payment routes
Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::get('/payment/return/{purchase}', [PaymentController::class, 'paymentReturn'])->name('payment.return');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Tiny health check
Route::get('/__proj_test', function () {
    try {
        $c = Project::count();
    } catch (\Exception $e) {
        return "DB ERROR: " . $e->getMessage();
    }
    return "Projects: " . $c;
});

// Debug: return last N lines of laravel log (token-protected via ADMIN_DEBUG_TOKEN or ?token=)
Route::get('/__debug/logs', function (Request $request) {
    $token = env('ADMIN_DEBUG_TOKEN') ?: $request->query('token');
    $provided = $request->header('X-Debug-Token') ?? $request->query('token');
    if (!$token || $provided !== $token) {
        return response('Forbidden', 403);
    }

    $lines = (int) $request->query('lines', 200);
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) {
        return response()->json(['ok' => false, 'reason' => 'log_not_found']);
    }

    // Read last N lines efficiently
    $fp = fopen($path, 'r');
    $buffer = '';
    $chunk = 4096;
    $pos = -1;
    $lineCount = 0;
    $data = '';
    fseek($fp, 0, SEEK_END);
    $filesize = ftell($fp);
    while ($lineCount < $lines && $filesize > 0) {
        $readSize = min($chunk, $filesize);
        fseek($fp, $filesize - $readSize);
        $data = fread($fp, $readSize) . $data;
        $filesize -= $readSize;
        $lineCount = substr_count($data, "\n");
        if ($filesize <= 0) break;
    }
    fclose($fp);

    $all = explode("\n", trim($data));
    $last = array_slice($all, -$lines);
    return response()->json(['ok' => true, 'lines' => $last]);
})->name('debug.logs');

// Session test route
Route::get('/__session_test', function () {
    session(['test_key' => 'session_works']);
    return 'Session set. Token: ' . csrf_token() . ' | Session ID: ' . session()->getId();
});

// Pricing front-end
use App\Http\Controllers\PricingController;
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');

// Admin-only: document maintenance used by the row-details modal
Route::name('filament.admin.')
    ->prefix('admin')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::post('/projects/{project}/documents/upload', [ProjectDocumentController::class, 'upload'])
            ->name('projects.documents.upload');

        Route::get('/projects/{project}/documents/{document}/download', [ProjectDocumentController::class, 'download'])
            ->name('projects.documents.download');

        Route::post('/projects/{project}/documents/{document}/rename', [ProjectDocumentController::class, 'rename'])
            ->name('projects.documents.rename');

        Route::post('/projects/{project}/documents/{document}/hide', [ProjectDocumentController::class, 'hide'])
            ->name('projects.documents.hide');

        Route::get('/__proj_test2', function () {
            return "Admin doc routes OK: " . Project::count();
        })->name('proj_test2');
    });

/*
|--------------------------------------------------------------------------
| Custom login
|--------------------------------------------------------------------------
| GET  /login  -> renders simple-login if exists, else Breeze auth.login
| POST /login  -> authenticates then redirects to /client
*/
/*
// Route::middleware('guest')->group(function () {
//     Route::get('/login', function () {
//         if (view()->exists('auth.simple-login')) {
//             return view('auth.simple-login');
//         }
//         if (view()->exists('auth.login')) {
//             return view('auth.login');
//         }
//         abort(500, 'Login view missing (auth.simple-login or auth.login).');
//     })->name('login');

//     // Redirect legacy/mistyped /login/admin to the admin login page
//     Route::get('/login/admin', function () {
//         return redirect('/admin/login');
//     });

//     Route::post('/login', function (Request $request) {
//         $creds = $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//         ]);
//         if (Auth::attempt($creds, $request->boolean('remember'))) {
//             $request->session()->regenerate();
//             return redirect()->intended('/client');
//         }
//         return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
//     })->name('login.post');
// });
*/

/*
|--------------------------------------------------------------------------
| Client dashboard
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/client', [ClientController::class, 'index'])
    ->name('client');

/*
|--------------------------------------------------------------------------
| Easy logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

// Temporary debug endpoint (token-protected) to inspect admin auth & resource availability
Route::get('/admin/__debug_pricing', function (Request $request) {
    $token = env('ADMIN_DEBUG_TOKEN', null) ?: $request->query('token');
    $provided = $request->header('X-Debug-Token') ?? $request->query('token');
    if (!$token || $provided !== $token) {
        return response()->json(['ok' => false, 'reason' => 'missing_or_invalid_token'], 403);
    }

    return response()->json([
        'ok' => true,
        'authenticated' => auth()->check(),
        'user' => auth()->user() ? auth()->user()->only(['id','email','role']) : null,
        'pricing_resource_exists' => class_exists(\App\Filament\Admin\Resources\PricingCategoryResource::class),
    ]);
})->name('admin.debug_pricing');

// API routes for project files
Route::middleware(['auth'])->group(function () {
    Route::get('/api/projects/{project}/files', function (Project $project) {
        // Ensure user can access this project
        if ($project->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $files = $project->documents->map(function ($doc) {
            $extension = pathinfo($doc->filename, PATHINFO_EXTENSION);
            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            $isVideo = in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mov']);

            return [
                'id' => $doc->id,
                'name' => $doc->name,
                'filename' => $doc->filename,
                'size' => $doc->size,
                'url' => asset('storage/' . $doc->path),
                'thumbnail_url' => $isImage ? asset('storage/' . $doc->path) : null,
                'poster_url' => $isVideo ? asset('storage/video-poster.jpg') : null, // You can generate actual video posters later
            ];
        });

        return response()->json($files);
    });

    Route::get('/projects/{project}/download-all', function (Project $project) {
        // Ensure user can access this project
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }

        // For now, redirect to first file or show a message
        // You can implement actual ZIP generation later
        $firstDoc = $project->documents->first();
        if ($firstDoc) {
            return redirect(asset('storage/' . $firstDoc->path));
        }

        return redirect()->back()->with('message', 'No files available for download');
    });
});

// Include Laravel Breeze auth routes
require __DIR__.'/auth.php';

// Social login (Google) via Laravel Socialite
use App\Http\Controllers\Auth\SocialAuthController;

Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
});

// Enhanced Service-Based Platform Routes

// Administrator Routes
Route::middleware(['auth'])->prefix('administrator')->name('administrator.')->group(function () {
    Route::get('/dashboard', [AdministratorController::class, 'dashboard'])->name('dashboard');
    Route::get('/projects', [AdministratorController::class, 'projects'])->name('projects');
    Route::get('/projects/{project}', [AdministratorController::class, 'showProject'])->name('projects.show');
    Route::post('/projects/{project}/assign-developer', [AdministratorController::class, 'assignDeveloper'])->name('projects.assign-developer');
    Route::post('/projects/{project}/update-status', [AdministratorController::class, 'updateProjectStatus'])->name('projects.update-status');
    Route::get('/project-documents/{document}/download', [AdministratorController::class, 'downloadDocument'])->name('project-documents.download');
    Route::post('/requirements/{requirement}/review', [AdministratorController::class, 'reviewRequirement'])->name('requirements.review');
    Route::get('/developers', [AdministratorController::class, 'developers'])->name('developers');
    Route::get('/reports', [AdministratorController::class, 'reports'])->name('reports');
    
    // Progress Tracking Routes
    Route::get('/progress', [App\Http\Controllers\Administrator\ProgressController::class, 'index'])->name('progress.index');
    Route::get('/projects/{project}/progress', [App\Http\Controllers\Administrator\ProgressController::class, 'show'])->name('progress.show');
    Route::get('/projects/{project}/files/{file}/download', [App\Http\Controllers\Administrator\ProgressController::class, 'downloadFile'])->name('progress.download');
    Route::get('/projects/{project}/progress/export', [App\Http\Controllers\Administrator\ProgressController::class, 'exportProgress'])->name('progress.export');
});

// Developer Routes
Route::middleware(['auth'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');
    Route::get('/projects', [DeveloperController::class, 'projects'])->name('projects');
    Route::get('/projects/{project}', [DeveloperController::class, 'showProject'])->name('projects.show');
    Route::post('/projects/{project}/update-status', [DeveloperController::class, 'updateProjectStatus'])->name('projects.update-status');
    Route::post('/projects/{project}/log-time', [DeveloperController::class, 'logTime'])->name('projects.log-time');
    Route::post('/projects/{project}/upload-document', [DeveloperController::class, 'uploadDocument'])->name('projects.upload-document');
    Route::get('/project-documents/{document}/download', [DeveloperController::class, 'downloadDocument'])->name('project-documents.download');
    Route::get('/time-logs', [DeveloperController::class, 'timeLogs'])->name('time-logs');
    Route::post('/update-availability', [DeveloperController::class, 'updateAvailability'])->name('update-availability');
    Route::get('/profile', [DeveloperController::class, 'profile'])->name('profile');
    Route::post('/profile', [DeveloperController::class, 'updateProfile'])->name('profile.update');
    
    // Progress Tracking Routes
    Route::get('/projects/{project}/progress', [App\Http\Controllers\Developer\ProgressController::class, 'index'])->name('progress.index');
    Route::get('/projects/{project}/progress/create', [App\Http\Controllers\Developer\ProgressController::class, 'create'])->name('progress.create');
    Route::post('/projects/{project}/progress', [App\Http\Controllers\Developer\ProgressController::class, 'store'])->name('progress.store');
    Route::get('/projects/{project}/progress/{progress}', [App\Http\Controllers\Developer\ProgressController::class, 'show'])->name('progress.show');
    Route::get('/projects/{project}/progress/{progress}/edit', [App\Http\Controllers\Developer\ProgressController::class, 'edit'])->name('progress.edit');
    Route::put('/projects/{project}/progress/{progress}', [App\Http\Controllers\Developer\ProgressController::class, 'update'])->name('progress.update');
    Route::delete('/projects/{project}/progress/{progress}', [App\Http\Controllers\Developer\ProgressController::class, 'destroy'])->name('progress.destroy');
    Route::post('/projects/{project}/progress/upload', [App\Http\Controllers\Developer\ProgressController::class, 'uploadFile'])->name('progress.upload');
});

// Project Requirements Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/projects/{project}/requirements', [ProjectRequirementController::class, 'store'])->name('requirements.store');
    Route::get('/requirements/{requirement}', [ProjectRequirementController::class, 'show'])->name('requirements.show');
    Route::put('/requirements/{requirement}', [ProjectRequirementController::class, 'update'])->name('requirements.update');
    Route::delete('/requirements/{requirement}', [ProjectRequirementController::class, 'destroy'])->name('requirements.destroy');
});

// Team Routes (Public)
Route::prefix('team')->name('team.')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('index');
    Route::get('/register', [TeamController::class, 'showRegistration'])->name('register');
    Route::post('/register', [TeamController::class, 'register'])->name('register.store');
    Route::get('/search', [TeamController::class, 'search'])->name('search');
    // Moved to end of file to avoid conflicts with auth routes
});

// Admin Login Routes (outside auth middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', function () {
        return view('auth.login', ['admin' => true]);
    })->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])->name('login.post');
});

// Admin Developer Management Routes
Route::middleware([\App\Http\Middleware\AdminAuth::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/developers', [DeveloperManagementController::class, 'index'])->name('developers.index');
    Route::get('/developers/create', [DeveloperManagementController::class, 'create'])->name('developers.create');
    Route::post('/developers', [DeveloperManagementController::class, 'store'])->name('developers.store');
    Route::get('/developers/{developer}', [DeveloperManagementController::class, 'show'])->name('developers.show');
    Route::get('/developers/{developer}/edit', [DeveloperManagementController::class, 'edit'])->name('developers.edit');
    Route::put('/developers/{developer}', [DeveloperManagementController::class, 'update'])->name('developers.update');
    Route::delete('/developers/{developer}', [DeveloperManagementController::class, 'destroy'])->name('developers.destroy');
    Route::post('/developers/{developer}/toggle-availability', [DeveloperManagementController::class, 'toggleAvailability'])->name('developers.toggle-availability');
});

// Enhanced Administration Routes
Route::middleware([\App\Http\Middleware\AdminAuth::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\ReportsController::class, 'dashboard'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Projects Management
    Route::get('/projects', [App\Http\Controllers\Admin\ProjectController::class, 'index'])->name('projects');
    Route::get('/projects/create', [App\Http\Controllers\Admin\ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [App\Http\Controllers\Admin\ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [App\Http\Controllers\Admin\ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [App\Http\Controllers\Admin\ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [App\Http\Controllers\Admin\ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [App\Http\Controllers\Admin\ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/documents/{document}/download', [App\Http\Controllers\Admin\ProjectController::class, 'downloadDocument'])->name('projects.download');
    Route::get('/projects/{project}/assign', [App\Http\Controllers\Admin\ProjectController::class, 'assignForm'])->name('projects.assign');
    Route::put('/projects/{project}/assign', [App\Http\Controllers\Admin\ProjectController::class, 'assignDeveloper'])->name('projects.assign.store');
    
    // Reports Management
    Route::get('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/create', [App\Http\Controllers\Admin\ReportsController::class, 'create'])->name('reports.create');
    Route::post('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [App\Http\Controllers\Admin\ReportsController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/edit', [App\Http\Controllers\Admin\ReportsController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [App\Http\Controllers\Admin\ReportsController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [App\Http\Controllers\Admin\ReportsController::class, 'destroy'])->name('reports.destroy');
    Route::post('/reports/{report}/generate', [App\Http\Controllers\Admin\ReportsController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/{report}/export', [App\Http\Controllers\Admin\ReportsController::class, 'export'])->name('reports.export');
    
    // Settings Management
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    
    // Pricing Management
    Route::get('/pricing', function () {
        return view('admin.pricing');
    })->name('pricing');
});


// Catch-all route for specializations (temporarily disabled to fix auth routes)
// Route::get('/{specialization}', [TeamController::class, 'show'])
//     ->where('specialization', '^(?!login|register|logout|dashboard|admin|team|developer|administrator|client|api|payment|auth|forgot-password|reset-password|verify-email|confirm-password|email|livewire|storage|up|__debug|__proj|__session|pricing|requirements|projects|developers|purchases|settings|users|pricing-categories).*')
//     ->name('specialization');
