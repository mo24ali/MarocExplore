<?php

use App\Http\Controllers\IterinaryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/itineraries/create', [IterinaryController::class, 'create'])->name('itineraries.create');
    Route::post('/iterinary/create', [IterinaryController::class, 'create'])->name('iterinary.create');
    Route::post('/iterinary/store', [IterinaryController::class, 'store'])->name('iterinary.store');
});

require __DIR__.'/auth.php';
