<?php

use App\Http\Controllers\ApprovalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingHIstoryController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RoomBookingsController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!s
|
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

// Semua route yang butuh login
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/earnings-overview', [DashboardController::class, 'earningsOverview'])->name('dashboard.earningsOverview');

    // Route khusus untuk halaman history booking
    Route::get('/booking-history', [BookingHIstoryController::class, 'index'])->name('booking.history');
     // Route untuk halaman index booking
    Route::get('booking', [RoomBookingsController::class, 'index'])->name('booking.index');

    // Route untuk menampilkan form create booking
    Route::get('booking/create', [RoomBookingsController::class, 'create'])->name('booking.create');

    // Route untuk menyimpan data booking
    Route::post('booking', [RoomBookingsController::class, 'store'])->name('booking.store');

    // Route untuk menampilkan detail booking tertentu
    Route::get('booking/{id}', [RoomBookingsController::class, 'show'])->name('booking.show');

    // Route untuk menampilkan form edit booking
    Route::get('booking/{id}/edit', [RoomBookingsController::class, 'edit'])->name('booking.edit');

    // Route untuk mengupdate data booking
    Route::put('booking/{id}', [RoomBookingsController::class, 'update'])->name('booking.update');

    Route::get('/profile/settings', [ProfilesController::class, 'index'])->name('profile.index');
    Route::put('/profile/{id}', [ProfilesController::class, 'update'])->name('profile.update');



    Route::middleware(['auth', 'admin'])->group(function () {
        Route::resource('floor', FloorController::class);
        Route::resource('users', UserController::class);
        // Route untuk menghapus data booking
        Route::delete('booking/{id}', [RoomBookingsController::class, 'destroy'])->name('booking.destroy');
        Route::resource('room', RoomController::class);
        Route::get('/rooms/{id}', [RoomController::class, 'show']);
        Route::patch('/room/{id}', [RoomController::class, 'update'])->name('room.update');
        Route::get('/room/{floor}/map', [RoomController::class, 'showFloorMap'])->name('floor.map');
        Route::post('/room/update-position', [RoomController::class, 'updatePosition'])->name('room.updatePosition');
        Route::post('/room/store', [RoomController::class, 'store'])->name('room.store');
        Route::delete('/room/{id}', [RoomController::class, 'destroy'])->name('room.destroy');
        Route::resource('approval', ApprovalController::class);
    
    });
});