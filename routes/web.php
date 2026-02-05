<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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

Route::get('/login', function () {
    return view('Auth.login');
});

Route::get('/dashboard', [AdminController::class, 'index']);
