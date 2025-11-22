<?php

use App\Http\Controllers\Api\CatApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\FeedingPointApiController;
use App\Http\Controllers\Api\ReminderApiController;
use App\Http\Middleware\ApiTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(ApiTokenMiddleware::class)->group(function () {
    Route::get('/dashboard', [DashboardApiController::class, 'summary']);
    Route::get('/cats', [CatApiController::class, 'index']);
    Route::get('/cats/{cat}', [CatApiController::class, 'show']);
    Route::get('/feeding-points', [FeedingPointApiController::class, 'index']);
    Route::get('/reminders', [ReminderApiController::class, 'index']);
});
