<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/absen', function () {
    return view('Guru.absen');
});

Route::get('/nilai', function () {
    return view('Guru.nilai');
});

Route::get('/siswa', function () {
    return view('Siswa.data');
});

Route::get('/dashboard', [AdminController::class, 'index'])->middleware('auth');
