<?php

use App\Http\Controllers\Admin\UserValidationController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Professeur\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/users', [UserValidationController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/validate', [UserValidationController::class, 'validateUser'])->name('users.validate');
    Route::patch('/users/{user}/reject', [UserValidationController::class, 'rejectUser'])->name('users.reject');

    Route::get('/salles', [SalleController::class, 'index'])->name('salles.index');
    Route::get('/salles/create', [SalleController::class, 'create'])->name('salles.create');
    Route::post('/salles', [SalleController::class, 'store'])->name('salles.store');
    Route::patch('/salles/{salle}/toggle', [SalleController::class, 'toggleStatut'])->name('salles.toggle');

    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}/valider', [AdminReservationController::class, 'valider'])->name('reservations.valider');
    Route::patch('/reservations/{reservation}/rejeter', [AdminReservationController::class, 'rejeter'])->name('reservations.rejeter');
});

Route::prefix('professeur')->name('professeur.')->middleware(['auth', 'professeur'])->group(function () {
    Route::get('/dashboard', fn() => view('professeur.dashboard'))->name('dashboard');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
});