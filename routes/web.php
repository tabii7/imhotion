<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ProjectDocumentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
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

// Dashboard route with cart functionality (accept optional section path segment)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::post('/dashboard/add-to-cart', [DashboardController::class, 'addToCart'])->middleware('auth')->name('dashboard.add-to-cart');
Route::post('/dashboard/update-cart-qty', [DashboardController::class, 'updateCartQty'])->middleware('auth')->name('dashboard.update-cart-qty');
Route::post('/dashboard/remove-from-cart', [DashboardController::class, 'removeFromCart'])->middleware('auth')->name('dashboard.remove-from-cart');

// Separate dashboard section routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/services', [DashboardController::class, 'services'])->name('dashboard.services');
    Route::get('/dashboard/transactions', [DashboardController::class, 'transactions'])->name('dashboard.transactions');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
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
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        if (view()->exists('auth.simple-login')) {
            return view('auth.simple-login');
        }
        if (view()->exists('auth.login')) {
            return view('auth.login');
        }
        abort(500, 'Login view missing (auth.simple-login or auth.login).');
    })->name('login');

    // Redirect legacy/mistyped /login/admin to the admin login page
    Route::get('/login/admin', function () {
        return redirect('/admin/login');
    });

    Route::post('/login', function (Request $request) {
        $creds = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($creds, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/client');
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    })->name('login.post');
});

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
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout.post');

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
