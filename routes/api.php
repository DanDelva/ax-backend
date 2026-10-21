<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SpecialtyController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ExcuseController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ScheduleController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes using Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Current logged in user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // API CRUDs (The logic on roles is handled internally via Policies/Controllers)
    Route::apiResource('specialties', SpecialtyController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('enrollments', EnrollmentController::class);
    Route::apiResource('grades', GradeController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('excuses', ExcuseController::class);
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('schedules', ScheduleController::class);
});