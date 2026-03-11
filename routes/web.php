<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'API-only backend', 'status' => 'ok'], 200);
});
