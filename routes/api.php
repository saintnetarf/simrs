<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/introspect', [AuthController::class, 'introspect']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/refresh-token', [AuthController::class, 'refreshToken']);
});
