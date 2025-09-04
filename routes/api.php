<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Cart API routes for seamless functionality
Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'addToCart'])->name('api.cart.add');
    Route::post('/update-qty', [CartController::class, 'updateCartQty'])->name('api.cart.update-qty');
    Route::post('/remove', [CartController::class, 'removeFromCart'])->name('api.cart.remove');
    Route::get('/get', [CartController::class, 'getCart'])->name('api.cart.get');
});
