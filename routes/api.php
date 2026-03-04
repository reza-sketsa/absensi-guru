<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;


Route::apiResource('student', StudentController::class);
Route::apiResource('subject', SubjectController::class);
Route::apiResource('classroom', ClassroomController::class);
Route::apiResource('teacher', TeacherController::class);
Route::apiResource('schedule', ScheduleController::class);
Route::apiResource('attendances', AttendanceController::class);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
