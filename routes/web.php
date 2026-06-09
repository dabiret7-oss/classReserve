<?php

use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\MatiereController;
use App\Http\Controllers\Admin\UserValidationController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\CahierController as AdminCahierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Professeur\ReservationController;
use App\Http\Controllers\Professeur\CahierController as ProfCahierController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profil/infos', [ProfilController::class, 'updateInfos'])->name('profil.infos');
    Route::patch('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/calendrier', [AdminReservationController::class, 'calendrier'])->name('calendrier');

    // Utilisateurs
    Route::get('/users', [UserValidationController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/validate', [UserValidationController::class, 'validateUser'])->name('users.validate');
    Route::patch('/users/{user}/reject', [UserValidationController::class, 'rejectUser'])->name('users.reject');
    Route::patch('/users/{user}/toggle-desactive', [UserValidationController::class, 'toggleDesactive'])->name('users.toggle-desactive');
    Route::delete('/users/{user}', [UserValidationController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/restore', [UserValidationController::class, 'restore'])->name('users.restore');
    
    // Salles
    Route::get('/salles', [SalleController::class, 'index'])->name('salles.index');
    Route::get('/salles/create', [SalleController::class, 'create'])->name('salles.create');
    Route::post('/salles', [SalleController::class, 'store'])->name('salles.store');
    Route::patch('/salles/{salle}/toggle', [SalleController::class, 'toggleStatut'])->name('salles.toggle');

    // Matières
    Route::get('/matieres', [MatiereController::class, 'index'])->name('matieres.index');
    Route::post('/matieres', [MatiereController::class, 'store'])->name('matieres.store');
    Route::delete('/matieres/{matiere}', [MatiereController::class, 'destroy'])->name('matieres.destroy');

    // Classes
    Route::get('/classes', [ClasseController::class, 'index'])->name('classes.index');
    Route::post('/classes', [ClasseController::class, 'store'])->name('classes.store');
    Route::delete('/classes/{classe}', [ClasseController::class, 'destroy'])->name('classes.destroy');

    // Réservations
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [AdminReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [AdminReservationController::class, 'store'])->name('reservations.store');
    Route::patch('/reservations/{reservation}/valider', [AdminReservationController::class, 'valider'])->name('reservations.valider');
    Route::patch('/reservations/{reservation}/rejeter', [AdminReservationController::class, 'rejeter'])->name('reservations.rejeter');

    // Cahiers de texte
    Route::get('/cahiers', [AdminCahierController::class, 'index'])->name('cahiers.index');
    Route::get('/cahiers/create', [AdminCahierController::class, 'create'])->name('cahiers.create');
    Route::post('/cahiers', [AdminCahierController::class, 'store'])->name('cahiers.store');
    Route::get('/cahiers/acces', [AdminCahierController::class, 'acces'])->name('cahiers.acces');
    Route::patch('/cahiers/acces/{acces}/valider', [AdminCahierController::class, 'validerAcces'])->name('cahiers.acces.valider');
    Route::patch('/cahiers/acces/{acces}/rejeter', [AdminCahierController::class, 'rejeterAcces'])->name('cahiers.acces.rejeter');
    Route::get('/cahiers/{cahier}', [AdminCahierController::class, 'show'])->name('cahiers.show');
});

Route::prefix('professeur')->name('professeur.')->middleware(['auth', 'professeur'])->group(function () {
    Route::get('/dashboard', fn() => view('professeur.dashboard'))->name('dashboard');
    Route::get('/calendrier', [ReservationController::class, 'calendrier'])->name('calendrier');

    // Réservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{reservation}/annuler', [ReservationController::class, 'annuler'])->name('reservations.annuler');

    // Cahiers de texte
    Route::get('/cahiers', [ProfCahierController::class, 'index'])->name('cahiers.index');
    Route::post('/cahiers/{cahier}/acces', [ProfCahierController::class, 'demanderAcces'])->name('cahiers.acces');
    Route::get('/cahiers/{cahier}', [ProfCahierController::class, 'show'])->name('cahiers.show');
    Route::post('/cahiers/{cahier}/seances', [ProfCahierController::class, 'storeSeance'])->name('cahiers.seances.store');

    // Notifications
    Route::get('/notifications/lire', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.lire');
});