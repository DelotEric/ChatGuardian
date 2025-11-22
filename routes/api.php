<?php

use App\Http\Controllers\Api\CatApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\DonorApiController;
use App\Http\Controllers\Api\FeedingPointApiController;
use App\Http\Controllers\Api\FosterFamilyApiController;
use App\Http\Controllers\Api\ReminderApiController;
use App\Http\Controllers\Api\StockApiController;
use App\Http\Controllers\Api\VolunteerApiController;
use App\Http\Middleware\ApiTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(ApiTokenMiddleware::class)->group(function () {
    Route::get('/dashboard', [DashboardApiController::class, 'summary']);
    Route::get('/cats', [CatApiController::class, 'index']);
    Route::get('/cats/{cat}', [CatApiController::class, 'show']);
    Route::get('/donations', [DonationApiController::class, 'index']);
    Route::get('/donations/{donation}', [DonationApiController::class, 'show']);
    Route::get('/donors', [DonorApiController::class, 'index']);
    Route::get('/donors/{donor}', [DonorApiController::class, 'show']);
    Route::get('/feeding-points', [FeedingPointApiController::class, 'index']);
    Route::get('/foster-families', [FosterFamilyApiController::class, 'index']);
    Route::get('/reminders', [ReminderApiController::class, 'index']);
    Route::get('/stocks', [StockApiController::class, 'index']);
    Route::get('/volunteers', [VolunteerApiController::class, 'index']);
});
