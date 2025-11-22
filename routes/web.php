<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FeedingPointController;
use App\Http\Controllers\FosterFamilyController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');

    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    Route::post('/volunteers', [VolunteerController::class, 'store'])->name('volunteers.store');

    Route::get('/cats', [CatController::class, 'index'])->name('cats.index');
    Route::post('/cats', [CatController::class, 'store'])->name('cats.store');
    Route::patch('/cats/{cat}', [CatController::class, 'update'])->name('cats.update');
    Route::get('/cats/{cat}', [CatController::class, 'show'])->name('cats.show');
    Route::post('/cats/{cat}/photos', [CatController::class, 'storePhotos'])->name('cats.photos.store');
    Route::delete('/cats/{cat}/photos/{photo}', [CatController::class, 'destroyPhoto'])->name('cats.photos.destroy');
    Route::post('/cats/{cat}/stays', [CatController::class, 'storeStay'])->name('cats.stays.store');
    Route::post('/cats/{cat}/stays/{stay}/close', [CatController::class, 'closeStay'])->name('cats.stays.close');

    Route::get('/foster-families', [FosterFamilyController::class, 'index'])->name('foster-families.index');
    Route::post('/foster-families', [FosterFamilyController::class, 'store'])->name('foster-families.store');
    Route::get('/foster-families/{family}/contract', [PdfController::class, 'fosterContract'])->name('foster-families.contract');

    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
    Route::post('/donors', [DonationController::class, 'createDonor'])->name('donors.store');
    Route::get('/donations/export', [DonationController::class, 'exportCsv'])->name('donations.export');
    Route::get('/donations/{donation}/receipt', [PdfController::class, 'donationReceipt'])->name('donations.receipt');

    Route::get('/feeding-points', [FeedingPointController::class, 'index'])->name('feeding-points.index');
    Route::post('/feeding-points', [FeedingPointController::class, 'store'])->name('feeding-points.store');
});
