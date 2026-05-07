<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::post('/login', [DashboardController::class, 'authenticate'])->middleware('guest')->name('login');
Route::view('/login', 'login')->middleware('guest');
Route::delete('/logout', [DashboardController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/booking', [TreatmentController::class, 'booking'])->name('booking.store')->middleware('guest');
Route::view('/booking', 'booking')->middleware('guest');
Route::get('/history', [TreatmentController::class, 'history'])->middleware('guest');
Route::get('/treatment-print', [TreatmentController::class, 'print'])->name('treatments.print')->middleware('guest');

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/index', [DashboardController::class, 'index'])->name('index');
    Route::put('/treatments/{treatment:code}/doctor-action', [TreatmentController::class, 'doctorAction'])->name('treatments.doctor-action');
    Route::put('/treatments/{treatment:code}/apoteker-action', [TreatmentController::class, 'apotekerAction'])->name('treatments.apoteker-action');
    Route::resource('treatments', TreatmentController::class);
    Route::middleware('role:Admin')->group(function () {
        Route::resource('doctors', DoctorController::class);
        Route::resource('rooms', RoomController::class)->except('show');
    });
});
