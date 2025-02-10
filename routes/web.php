<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('events.index');
});

Route::get('/events/search', [EventController::class, 'search'])->name('events.search');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::resource('events', EventController::class)->except(['index', 'show']);
    });


    Route::prefix('reservations')->group(function () {
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])
        ->name('reservations.destroy');
        Route::get('/my-reservations', [ReservationController::class, 'myReservations'])
            ->name('reservations.my');
        Route::get('/', [ReservationController::class, 'index'])
            ->name('reservations.index');
        Route::post('/', [ReservationController::class, 'store'])
            ->name('reservations.store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])
            ->name('reservations.show');

    });

        

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::middleware('auth')->group(function () {
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    });
});

require __DIR__.'/auth.php';