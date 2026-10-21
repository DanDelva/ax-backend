<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\SpecialtyController;
use App\Http\Controllers\Web\SubjectController;
use App\Http\Controllers\Web\EnrollmentController;
use App\Http\Controllers\Web\GradeController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ExcuseController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\ScheduleController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('panel.login.submit');

// Protected
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('panel.logout');
    Route::get('/', [DashboardController::class, 'index'])->name('panel.dashboard');
    Route::get('/mi-horario', [ScheduleController::class, 'timetable'])->name('panel.schedule.timetable');

    Route::resource('users', UserController::class)->names('panel.users');
    Route::resource('specialties', SpecialtyController::class)->names('panel.specialties');
    Route::resource('subjects', SubjectController::class)->names('panel.subjects');
    Route::resource('enrollments', EnrollmentController::class)->names('panel.enrollments');
    Route::resource('grades', GradeController::class)->names('panel.grades');
    Route::resource('payments', PaymentController::class)->names('panel.payments');
    Route::resource('excuses', ExcuseController::class)->names('panel.excuses')->parameters(['excuses' => 'excuse']);
    Route::get('/attendances/quick', [AttendanceController::class, 'quick'])->name('panel.attendances.quick');
    Route::post('/attendances/quick', [AttendanceController::class, 'quickStore'])->name('panel.attendances.quick.store');
    Route::resource('attendances', AttendanceController::class)->names('panel.attendances');
    Route::resource('schedules', ScheduleController::class)->names('panel.schedules');
});
