<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ProjectDocumentController;
use App\Http\Controllers\ClientController;
use App\Models\Project;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Public site + auth + client + admin document actions
|--------------------------------------------------------------------------
*/

// Public landing
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Tiny health check
Route::get('/__proj_test', function () {
    try {
        $c = Project::count();
        return "Projects OK. Count = {$c}";
    } catch (\Throwable $e) {
        return 'Project model error: ' . $e->getMessage();
    }
});

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
})->middleware('auth')->name('logout.get');
