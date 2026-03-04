<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Auth ──────────────────────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me',      [AuthController::class, 'me']);
    });

    // ── Future resource routes go here ────────────────
    // Route::apiResource('products', ProductController::class);
    // Route::apiResource('orders',   OrderController::class);
});
