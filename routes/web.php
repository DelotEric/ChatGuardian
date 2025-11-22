<?php

use App\Http\Controllers\CatController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FeedingPointController;
use App\Http\Controllers\FosterFamilyController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('dashboard');
Route::view('/login', 'auth.login')->name('login');

Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
Route::post('/volunteers', [VolunteerController::class, 'store'])->name('volunteers.store');

Route::get('/cats', [CatController::class, 'index'])->name('cats.index');
Route::post('/cats', [CatController::class, 'store'])->name('cats.store');

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
