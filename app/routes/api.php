<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientOrdersController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/results', [PatientOrdersController::class, 'index'])->name('patient.orders');
});

