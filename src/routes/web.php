<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApplicationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('admin.login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisteredController::class, 'create'])->name('admin.register');
    Route::post('register', [RegisteredController::class, 'store']);

    Route::middleware('auth:admin')->group(function () {
        Route::get('/attendance/list', [AdminAttendanceController::class, 'getList'])->name('admin.attendance.list');
        Route::get('/attendance/list/previous', [AdminAttendanceController::class, 'getPreviousDate']);
        Route::get('/attendance/list/later', [AdminAttendanceController::class, 'getLaterDate']);
        Route::get('/attendance/{id}', [AdminAttendanceController::class, 'getDetail']);
        Route::post('/attendance/{id}', [AdminAttendanceController::class, 'update']);
        Route::get('/staff/list', [AdminAttendanceController::class, 'getStaffList']);
        /*Route::get('/stamp_correction_request/list', [AdminAttendanceController::class, 'getApplicationList']);*/
        Route::post('/logout', [LoginController::class, 'destroy']);
    });
});

Route::middleware('auth:admin')->group(function(){
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [AdminAttendanceController::class, 'getApproval']);
});

Route::middleware(['auth:web', 'verified'])->group(function(){
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('guest.attendance.index');
    Route::post('/work/start', [AttendanceController::class, 'startWork']);
    Route::post('/work/end', [AttendanceController::class, 'endWork']);
    Route::post('/break/in', [AttendanceController::class, 'takeBreak']);
    Route::post('/break/out', [AttendanceController::class, 'leaveBreak']);
    Route::get('/attendance/list', [AttendanceController::class, 'getList']);
    Route::post('/attendance/list', [AttendanceController::class, 'getPreviousMonthList']);
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'getDetail']);
    Route::post('/attendance/detail/{id}', [AttendanceController::class, 'update']);
    /*Route::get('/stamp_correction_request/list', [AttendanceController::class, 'getApplicationList']);*/
});

Route::get('/stamp_correction_request/list', [ApplicationController::class, 'getApplicationList']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');