<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\CatAdoptionController;
use App\Http\Controllers\CatVetRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\FeedingPointController;
use App\Http\Controllers\FosterFamilyController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StockItemController;
use App\Http\Controllers\UserController;
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
    Route::get('/search', SearchController::class)->name('search');

    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    Route::post('/volunteers', [VolunteerController::class, 'store'])->name('volunteers.store');
    Route::patch('/volunteers/{volunteer}', [VolunteerController::class, 'update'])->name('volunteers.update');
    Route::delete('/volunteers/{volunteer}', [VolunteerController::class, 'destroy'])->name('volunteers.destroy');

    Route::get('/cats', [CatController::class, 'index'])->name('cats.index');
    Route::post('/cats', [CatController::class, 'store'])->name('cats.store');
    Route::patch('/cats/{cat}', [CatController::class, 'update'])->name('cats.update');
    Route::get('/cats/{cat}', [CatController::class, 'show'])->name('cats.show');
    Route::post('/cats/{cat}/photos', [CatController::class, 'storePhotos'])->name('cats.photos.store');
    Route::delete('/cats/{cat}/photos/{photo}', [CatController::class, 'destroyPhoto'])->name('cats.photos.destroy');
    Route::post('/cats/{cat}/stays', [CatController::class, 'storeStay'])->name('cats.stays.store');
    Route::post('/cats/{cat}/stays/{stay}/close', [CatController::class, 'closeStay'])->name('cats.stays.close');
    Route::post('/cats/{cat}/adoptions', [CatAdoptionController::class, 'store'])->name('cats.adoptions.store');
    Route::patch('/cats/{cat}/adoptions/{adoption}', [CatAdoptionController::class, 'update'])->name('cats.adoptions.update');
    Route::delete('/cats/{cat}/adoptions/{adoption}', [CatAdoptionController::class, 'destroy'])->name('cats.adoptions.destroy');
    Route::post('/cats/{cat}/vet-records', [CatVetRecordController::class, 'store'])->name('cats.vet-records.store');
    Route::patch('/cats/{cat}/vet-records/{vetRecord}', [CatVetRecordController::class, 'update'])->name('cats.vet-records.update');
    Route::delete('/cats/{cat}/vet-records/{vetRecord}', [CatVetRecordController::class, 'destroy'])->name('cats.vet-records.destroy');
    Route::get('/cats/{cat}/vet-records/export', [CatVetRecordController::class, 'export'])->name('cats.vet-records.export');

    Route::get('/foster-families', [FosterFamilyController::class, 'index'])->name('foster-families.index');
    Route::post('/foster-families', [FosterFamilyController::class, 'store'])->name('foster-families.store');
    Route::patch('/foster-families/{family}', [FosterFamilyController::class, 'update'])->name('foster-families.update');
    Route::delete('/foster-families/{family}', [FosterFamilyController::class, 'destroy'])->name('foster-families.destroy');
    Route::get('/foster-families/{family}/contract', [PdfController::class, 'fosterContract'])->name('foster-families.contract');
    Route::get('/cats/{cat}/adoptions/{adoption}/contract', [PdfController::class, 'adoptionContract'])->name('cats.adoptions.contract');

Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
Route::patch('/donations/{donation}', [DonationController::class, 'update'])->name('donations.update');
Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy');
Route::get('/donations/export', [DonationController::class, 'exportCsv'])->name('donations.export');
Route::post('/donations/{donation}/send-receipt', [DonationController::class, 'sendReceipt'])->name('donations.sendReceipt');
Route::get('/donations/{donation}/receipt', [PdfController::class, 'donationReceipt'])->name('donations.receipt');

    Route::get('/donors', [DonorController::class, 'index'])->name('donors.index');
    Route::post('/donors', [DonorController::class, 'store'])->name('donors.store');
    Route::patch('/donors/{donor}', [DonorController::class, 'update'])->name('donors.update');
    Route::delete('/donors/{donor}', [DonorController::class, 'destroy'])->name('donors.destroy');
    Route::get('/donors/export', [DonorController::class, 'exportCsv'])->name('donors.export');

    Route::get('/feeding-points', [FeedingPointController::class, 'index'])->name('feeding-points.index');
    Route::post('/feeding-points', [FeedingPointController::class, 'store'])->name('feeding-points.store');
    Route::patch('/feeding-points/{feedingPoint}', [FeedingPointController::class, 'update'])->name('feeding-points.update');
    Route::delete('/feeding-points/{feedingPoint}', [FeedingPointController::class, 'destroy'])->name('feeding-points.destroy');

    Route::get('/stocks', [StockItemController::class, 'index'])->name('stocks.index');
    Route::post('/stocks', [StockItemController::class, 'store'])->name('stocks.store');
    Route::patch('/stocks/{stockItem}', [StockItemController::class, 'update'])->name('stocks.update');
    Route::delete('/stocks/{stockItem}', [StockItemController::class, 'destroy'])->name('stocks.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/settings/organization', [OrganizationController::class, 'edit'])->name('settings.organization');
    Route::patch('/settings/organization', [OrganizationController::class, 'update'])->name('settings.organization.update');
});
