<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IterinaryController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/itineraries', [IterinaryController::class, 'index']);
    Route::get('/itineraries/popular', [IterinaryController::class, 'popular']);
    Route::get('/itineraries/search', [IterinaryController::class, 'search']);
    Route::get('/itineraries/stats', [IterinaryController::class, 'stats']);
    Route::get('/itineraries/{iterinary}', [IterinaryController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);

        Route::post('/itineraries', [IterinaryController::class, 'store']);
        Route::put('/itineraries/{iterinary}', [IterinaryController::class, 'update']);
        Route::delete('/itineraries/{iterinary}', [IterinaryController::class, 'destroy']);
        Route::post('/itineraries/{iterinary}/wishlist', [IterinaryController::class, 'addToWishlist']);
        Route::delete('/itineraries/{iterinary}/wishlist', [IterinaryController::class, 'removeFromWishlist']);

        Route::post('/itineraries/{iterinary}/destinations', [IterinaryController::class, 'addDestination']);
        Route::put('/itineraries/{iterinary}/destinations/{destination}', [IterinaryController::class, 'updateDestination']);
        Route::delete('/itineraries/{iterinary}/destinations/{destination}', [IterinaryController::class, 'removeDestination']);
        
    });

});
