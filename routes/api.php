<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IterinaryController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/iterinaries', [IterinaryController::class, 'index']);
    Route::get('/iterinaries/popular', [IterinaryController::class, 'popular']);
    Route::get('/iterinaries/search', [IterinaryController::class, 'search']);
    Route::match(['get', 'post'], '/iterinaries/stats', [IterinaryController::class, 'stats']);
    Route::get('/iterinaries/{iterinary}', [IterinaryController::class, 'show']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);

        Route::post('/iterinaries', [IterinaryController::class, 'store']);
        Route::put('/iterinaries/{iterinary}', [IterinaryController::class, 'update']);
        Route::delete('/iterinaries/{iterinary}', [IterinaryController::class, 'destroy']);
        Route::post('/iterinaries/{iterinary}/destinations', [IterinaryController::class, 'addDestination']);
        Route::put('/iterinaries/{iterinary}/destinations/{destination}', [IterinaryController::class, 'updateDestination']);
        Route::delete('/iterinaries/{iterinary}/destinations/{destination}', [IterinaryController::class, 'removeDestination']);
    });

});
