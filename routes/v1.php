<?php
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\DoctorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Get user
Route::middleware(['auth:sanctum', 'ability:*'])->get('/v1/user', function (Request $request) {
    return $request->user();
});

// Doctors
Route::middleware(['auth:sanctum', 'ability:*'])->prefix('v1')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
    Route::post('/doctors', [DoctorController::class, 'store']);
    Route::put('/doctors/{doctor}', [DoctorController::class, 'update']);
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);
});
