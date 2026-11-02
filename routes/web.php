<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

// ── Home ──────────────────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('venues.index'))->name('home');

// ── Venues (public) ───────────────────────────────────────────────────────────
Route::prefix('venues')->name('venues.')->group(function () {
    Route::get('/',                           [VenueController::class, 'index'])->name('index');
    Route::get('/{slug}',                     [VenueController::class, 'show'])->name('show');
    Route::post('/{slug}/check-availability', [VenueController::class, 'checkAvailability'])->name('check-availability');
});

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Bookings (auth required) ──────────────────────────────────────────────────
Route::prefix('bookings')->name('bookings.')->middleware('auth')->group(function () {
    Route::get('/dashboard',               [BookingController::class, 'dashboard'])->name('dashboard');
    Route::get('/{reference}/confirmation',[BookingController::class, 'confirmation'])->name('confirmation');
    Route::get('/{reference}',             [BookingController::class, 'show'])->name('show');
    Route::post('/{reference}/cancel',     [BookingController::class, 'cancel'])->name('cancel');
});

// ── Venue booking (auth required) ─────────────────────────────────────────────
Route::prefix('venues')->name('venues.')->middleware('auth')->group(function () {
    Route::get('/{slug}/book',  [BookingController::class, 'create'])->name('book');
    Route::post('/{slug}/book', [BookingController::class, 'store'])->name('book.store');
});

// ── Profile / Account (auth required) ─────────────────────────────────────────
Route::prefix('account')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/',                [ProfileController::class, 'show'])->name('show');
    Route::put('/update',          [ProfileController::class, 'update'])->name('update');
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('password');
    Route::delete('/delete',       [ProfileController::class, 'deleteAccount'])->name('delete');
});
