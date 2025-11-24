<?php

use App\Http\Controllers\CatController;
use App\Http\Controllers\CatStayController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\AdopterController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\MedicalCareController;
use App\Http\Controllers\FeedingPointController;
use App\Http\Controllers\FosterFamilyController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

// Routes publiques (authentification)
require __DIR__ . '/auth.php';

// Public Routes (Site Vitrine)
Route::prefix('site')->group(function () {
    Route::get('/', [App\Http\Controllers\PublicController::class, 'home'])->name('public.home');
    Route::get('/chats-a-l-adoption', [App\Http\Controllers\PublicController::class, 'cats'])->name('public.cats');
    Route::get('/chat/{cat}', [App\Http\Controllers\PublicController::class, 'showCat'])->name('public.cats.show');
    Route::get('/adopter/{cat?}', [App\Http\Controllers\PublicController::class, 'apply'])->name('public.apply');
    Route::post('/adopter', [App\Http\Controllers\PublicController::class, 'submitApplication'])->name('public.submit');
});

// Routes protégées par authentification
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bénévoles
    // Bénévoles
    Route::resource('volunteers', VolunteerController::class);

    // Chats
    Route::resource('cats', CatController::class);
    Route::get('cats/{cat}/medical-history', [CatController::class, 'medicalHistory'])->name('cats.medical-history');
    Route::get('cats/{cat}/health-record', [CatController::class, 'generateHealthRecord'])->name('cats.health-record');
    Route::get('cats/{cat}/health-record/download', [CatController::class, 'downloadHealthRecord'])->name('cats.health-record.download');
    Route::post('cats/{cat}/weight-records', [App\Http\Controllers\WeightRecordController::class, 'store'])->name('cats.weight-records.store');
    Route::put('cats/{cat}/weight-records/{weightRecord}', [App\Http\Controllers\WeightRecordController::class, 'update'])->name('cats.weight-records.update');
    Route::delete('cats/{cat}/weight-records/{weightRecord}', [App\Http\Controllers\WeightRecordController::class, 'destroy'])->name('cats.weight-records.destroy');



    // Familles d'accueil
    Route::resource('foster-families', FosterFamilyController::class);

    // Dons
    Route::resource('donations', DonationController::class);
    Route::get('donations/{donation}/receipt', [DonationController::class, 'generateReceipt'])->name('donations.receipt');
    Route::get('donations/{donation}/receipt/download', [DonationController::class, 'downloadReceipt'])->name('donations.receipt.download');
    Route::post('donations/{donation}/receipt/email', [DonationController::class, 'emailReceipt'])->name('donations.receipt.email');
    Route::post('/donors/create', [DonorController::class, 'store'])->name('donors.store.full');

    // Séjours
    Route::resource('cat-stays', CatStayController::class);

    // Donateurs
    Route::resource('donors', DonorController::class);

    // Adoptants
    Route::resource('adopters', AdopterController::class);

    // Partenaires
    Route::resource('partners', PartnerController::class);

    // Soins médicaux
    Route::resource('medical-cares', MedicalCareController::class);
    Route::post('medical-cares/{medical_care}/send-alert', [MedicalCareController::class, 'sendAlert'])->name('medical-cares.send-alert');

    // Inventaire
    Route::resource('inventory-items', App\Http\Controllers\InventoryItemController::class);
    Route::resource('inventory-movements', App\Http\Controllers\InventoryMovementController::class);

    // Adhérents
    Route::resource('members', MemberController::class);
    Route::resource('memberships', MembershipController::class);
    Route::get('memberships/{membership}/receipt', [MembershipController::class, 'generateReceipt'])->name('memberships.receipt');
    Route::get('memberships/{membership}/receipt/download', [MembershipController::class, 'downloadReceipt'])->name('memberships.receipt.download');
    Route::post('memberships/{membership}/receipt/email', [MembershipController::class, 'emailReceipt'])->name('memberships.receipt.email');

    // Points de nourrissage
    Route::resource('feeding-points', FeedingPointController::class);

    // Actualités & Événements
    Route::resource('news', App\Http\Controllers\NewsController::class);
    Route::resource('events', App\Http\Controllers\EventController::class);

    // Messagerie
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/sent', [App\Http\Controllers\MessageController::class, 'sent'])->name('messages.sent');
    Route::get('/messages/create', [App\Http\Controllers\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/{message}/reply', [App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');

    // Aide
    Route::get('/help', [HelpController::class, 'index'])->name('help');

    // Utilisateurs (réservé aux admins)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
