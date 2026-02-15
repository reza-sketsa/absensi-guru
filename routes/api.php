<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;

// Route::get('/student', [StudentController::class, 'index']);
// Route::get('student/{id}', [StudentController::class, 'show']);
// Route::post('/student', [StudentController::class, 'store']);
// Route::put('/student/{id}', [StudentController::class, 'update']);
// Route::delete('/student/{id}', [StudentController::class, 'destroy']);

Route::apiResource('student', StudentController::class);
Route::apiResource('subject', SubjectController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
