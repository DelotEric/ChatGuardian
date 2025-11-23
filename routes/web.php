<?php

use App\Http\Controllers\CatController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FeedingPointController;
use App\Http\Controllers\FosterFamilyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

// Routes publiques (authentification)
require __DIR__.'/auth.php';

// Routes protégées par authentification
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bénévoles
    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    Route::post('/volunteers', [VolunteerController::class, 'store'])->name('volunteers.store');

    // Chats
    Route::get('/cats', [CatController::class, 'index'])->name('cats.index');
    Route::post('/cats', [CatController::class, 'store'])->name('cats.store');

    // Familles d'accueil
    Route::get('/foster-families', [FosterFamilyController::class, 'index'])->name('foster-families.index');
    Route::post('/foster-families', [FosterFamilyController::class, 'store'])->name('foster-families.store');

    // Dons
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
    Route::post('/donors', [DonationController::class, 'createDonor'])->name('donors.store');

    // Points de nourrissage
    Route::get('/feeding-points', [FeedingPointController::class, 'index'])->name('feeding-points.index');
    Route::post('/feeding-points', [FeedingPointController::class, 'store'])->name('feeding-points.store');

    // Utilisateurs (réservé aux admins)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
