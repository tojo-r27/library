<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API root welcome
Route::get('/', fn () => response()->json(['message' => 'welcome']));
Route::get('/welcome', fn () => response()->json(['message' => 'welcome']));

// Public Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Book Management API Routes (protected by Sanctum + rate limit)
// Protected routes
Route::middleware(['auth:sanctum', 'throttle:60,1'])
    ->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'throttle:60,1'])
    ->apiResource('books', BookController::class);
