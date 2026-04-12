<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\AdminController;
use Faker\Calculator\Ean;
use Illuminate\Support\Facades\Route;

// Sécurisation contre l'accès direct au fichier .htaccess
Route::get('.htaccess', fn() => abort(403));


// Page d'accueil 
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Routes d'administration, protégées par les middlewares 'auth' et 'admin'

// ─── ROUTES ADMIN ──────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Liste des utilisateurs (gestionnaires + admins, sans l'admin principal id=1)
    Route::get('/utilisateurs', [AdminController::class, 'utilisateurs'])
        ->name('utilisateurs');

    // Voir les détails d'un utilisateur
    Route::get('/utilisateurs/{user}', [AdminController::class, 'showUtilisateur'])
        ->name('utilisateurs.show');

    // Activer / Désactiver un compte utilisateur
    Route::patch('/utilisateurs/{user}/toggle', [AdminController::class, 'toggleActif'])
        ->name('utilisateurs.toggle');

    // Changer le rôle (gestionnaire ↔ admin)
    Route::patch('/utilisateurs/{user}/change-role', [AdminController::class, 'changeRole'])
        ->name('utilisateurs.changeRole');

    // Supprimer un compte utilisateur 
    Route::delete('/utilisateurs/{user}', [AdminController::class, 'destroyUtilisateur'])
        ->name('utilisateurs.destroy');

    // Stats globales
    Route::get('/stats', [AdminController::class, 'stats'])->name('stats');
});


// Toutes les routes protégées par l'authentification
Route::middleware(['auth', 'verified', 'update.last.login'])->group(function () {

    // Le Dashboard utilise le Controller pour envoyer les stats à la vue
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

    // Routes pour les contrats
    Route::get('/contrats', [ContratController::class, 'index'])->name('contrats.index');
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
