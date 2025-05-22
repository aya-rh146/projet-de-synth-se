<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\proprietaire\AccueilProprietaireController;
use App\Http\Controllers\locataire\AccueilLocataireController;
use App\Http\Controllers\proprietaire\annonceproprietaireController;
use App\Http\Controllers\locataire\AnnonceLocataireController;
use Illuminate\Support\Facades\Route;

// auth
Route::get('/', [AuthController::class, 'visitor'])->name('visitor');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password.show');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Routes du propriétaire - CORRECTION DES NOMS DE ROUTES
Route::prefix('proprietaire')->name('proprietaire.')->middleware('auth')->group(function () {
    Route::get('/accueilproprietaire', [AccueilProprietaireController::class, 'index'])->name('accueilproprietaire');
    
    // Correction de la route pour les annonces du propriétaire
    Route::get('/annonces', [annonceproprietaireController::class, 'index'])->name('annoncesproprietaire.index');
    
    // Autres routes pour les annonces du propriétaire
    Route::post('/annonces', [annonceproprietaireController::class, 'store'])->name('annoncesproprietaire.store');
    Route::get('/annonces/{id}/edit', [annonceproprietaireController::class, 'edit'])->name('modifierannonceproprietaire');
    Route::put('/annonces/{id}', [annonceproprietaireController::class, 'update'])->name('annoncesproprietaire.update');
    Route::delete('/annonces/{id}', [annonceproprietaireController::class, 'destroy'])->name('annoncesproprietaire.destroy');
});

// Routes du locataire
Route::prefix('locataire')->name('locataire.')->middleware('auth')->group(function () {
    Route::get('/accueillocataire', [AccueilLocataireController::class, 'index'])->name('accueillocataire');
    
    // Routes pour les annonces du locataire
    Route::get('/annonceslocataire', [AnnonceLocataireController::class, 'index'])->name('annonceslocataire.index');
    Route::post('/annonceslocataire', [AnnonceLocataireController::class, 'store'])->name('annoncelocataire.store');
    Route::delete('/annonceslocataire/{id}', [AnnonceLocataireController::class, 'destroy'])->name('annonceslocataire.destroy');
    Route::get('/annonceslocataire/{id}/edit', [AnnonceLocataireController::class, 'edit'])->name('editannonce');
    Route::put('/annonceslocataire/{id}', [AnnonceLocataireController::class, 'update'])->name('modifierannoncelocataire');
});

// Route pour l'admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('admin.dashboard');