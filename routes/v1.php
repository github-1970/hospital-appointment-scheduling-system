<?php

use App\Http\Controllers\V1\AppointmentController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\DoctorController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



  // Auth
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// User (current, find, all)
Route::middleware(['auth:sanctum', 'ability:*'])->get('/user', fn (Request $request) => $request->user());
Route::middleware(['auth:sanctum', 'ability:*'])->get('/user/{user}', fn (User $user) => $user);
Route::middleware(['auth:sanctum', 'ability:*'])->get('/users', fn () => User::all());

// Doctors
Route::middleware(['auth:sanctum', 'ability:*'])->prefix('doctors')->group(function () {
  Route::get('/', [DoctorController::class, 'index']);
  Route::post('/', [DoctorController::class, 'store']);
  Route::get('/{doctor}', [DoctorController::class, 'show']);
  Route::put('/{doctor}', [DoctorController::class, 'update']);
  Route::delete('/{doctor}', [DoctorController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'ability:use-appointment'])->prefix('appointments')->group(function () {
  Route::get('/', [AppointmentController::class, 'index']);
  Route::post('/', [AppointmentController::class, 'store']);
  Route::get('/{appointment}', [AppointmentController::class, 'show']);
  Route::put('/{appointment}', [AppointmentController::class, 'update']);
  Route::delete('/{appointment}', [AppointmentController::class, 'destroy']);
});
