<?php

use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Book Management API Routes (protected by Passport + rate limit)
Route::middleware(['auth:api', 'throttle:60,1'])
    ->apiResource('books', BookController::class);
