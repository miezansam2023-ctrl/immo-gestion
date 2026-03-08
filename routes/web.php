<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\SettingsController;
use Faker\Calculator\Ean;
use Illuminate\Support\Facades\Route;

// Page d'accueil (tu pourras la personnaliser plus tard)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Toutes les routes protégées par l'authentification
Route::middleware(['auth', 'verified'])->group(function () {


    // Le Dashboard utilise maintenant le Controller pour envoyer les stats à la vue
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes des modules métiers (Resources)
    Route::resource('biens', BienController::class);
    Route::resource('locataires', LocataireController::class);
    Route::resource('contrats', ContratController::class);
    Route::resource('paiements', PaiementController::class);
    Route::resource('incidents', IncidentController::class);
    
    // Routes du profil utilisateur (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/contrats', [ContratController::class, 'index'])->name('contrats.index');
    Route::get('/contrats/nouveau', [ContratController::class, 'create'])->name('contrats.create');
    Route::post('/contrats', [ContratController::class, 'store'])->name('contrats.store');

    Route::get('/contrats/{contrat}/pdf', [App\Http\Controllers\ContratController::class, 'generatePDF'])->name('contrats.pdf');

    // Paiement
    Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
    // Route pour générer le PDF de la quittance
    Route::get('/paiements/{paiement}/quittance', [App\Http\Controllers\PaiementController::class, 'generateQuittance'])->name('paiements.quittance');
});


Route::controller(SettingsController::class)->group(function () {
    Route::get('/settings', 'index')->name('settings.index');
    Route::put('/settings/profile', 'updateProfile')->name('settings.profile.update');
    Route::put('/settings/password', 'updatePassword')->name('settings.password.update');
});
require __DIR__ . '/auth.php';
