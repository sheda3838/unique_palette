<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ArtworkController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes Protection & Unauthorized Handling
|--------------------------------------------------------------------------
| 1. Authentication: All protected routes use Laravel Sanctum (auth:sanctum).
| 2. Security (SQL Injection Prevention):
|    - Eloquent ORM: Uses PDO parameter binding automatically to prevent SQLi.
|    - Query Builder: Also uses PDO binding for all where clauses and inputs.
|    - Input Validation: All API inputs are strictly validated before processing.
| 3. Global Exception Handling: Handled in bootstrap/app.php to ensure no 
|    database details or raw SQL errors are ever leaked in JSON responses.
|
*/

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/artworks', [ArtworkController::class, 'index']);
Route::get('/artworks/{artwork}', [ArtworkController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/verify-email', [AuthController::class, 'verifyEmail']);

    // User Profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/photo', [UserController::class, 'updateProfilePic']);

    /*
    |--------------------------------------------------------------------------
    | Artist Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('artist')->group(function () {
        Route::post('/artworks', [ArtworkController::class, 'store']);
        Route::put('/artworks/{artwork}', [ArtworkController::class, 'update']);
        Route::delete('/artworks/{artwork}', [ArtworkController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Buyer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('buyer')->group(function () {
        // Cart
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart', [CartController::class, 'store']);
        Route::delete('/cart/{id}', [CartController::class, 'destroy']); // id is artwork_id

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::get('/admin/orders', [AdminController::class, 'orders']);
        Route::post('/admin/artworks/{artwork}/approve', [AdminController::class, 'approveArtwork']);
        Route::post('/admin/artworks/{artwork}/reject', [AdminController::class, 'rejectArtwork']);
    });
});
