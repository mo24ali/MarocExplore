<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IterinaryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\StayController;
use App\Http\Controllers\DishController;

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

        // Places
        Route::get('/destinations/{destination}/places', [PlaceController::class, 'index']);
        Route::post('/destinations/{destination}/places', [PlaceController::class, 'store']);
        Route::get('/destinations/{destination}/places/{place}', [PlaceController::class, 'show']);
        Route::put('/destinations/{destination}/places/{place}', [PlaceController::class, 'update']);
        Route::delete('/destinations/{destination}/places/{place}', [PlaceController::class, 'destroy']);

        // Stays
        Route::get('/destinations/{destination}/stays', [StayController::class, 'index']);
        Route::post('/destinations/{destination}/stays', [StayController::class, 'store']);
        Route::get('/destinations/{destination}/stays/{stay}', [StayController::class, 'show']);
        Route::put('/destinations/{destination}/stays/{stay}', [StayController::class, 'update']);
        Route::delete('/destinations/{destination}/stays/{stay}', [StayController::class, 'destroy']);

        // Dishes
        Route::get('/destinations/{destination}/dishes', [DishController::class, 'index']);
        Route::post('/destinations/{destination}/dishes', [DishController::class, 'store']);
        Route::get('/destinations/{destination}/dishes/{dish}', [DishController::class, 'show']);
        Route::put('/destinations/{destination}/dishes/{dish}', [DishController::class, 'update']);
        Route::delete('/destinations/{destination}/dishes/{dish}', [DishController::class, 'destroy']);
    });

});

