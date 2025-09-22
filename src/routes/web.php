<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredController;
use App\Http\Controllers\AttendanceController;


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
        Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.list');
        Route::post('/logout', [LoginController::class, 'destroy']);
    });
});

Route::middleware(['auth:web', 'verified'])->group(function(){
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('guest.attendance.index');
    Route::post('/work/start', [AttendanceController::class, 'startWork']);
    Route::post('/work/end', [AttendanceController::class, 'endWork']);
    Route::post('/break/in', [AttendanceController::class, 'takeBreak']);
    Route::post('/break/out', [AttendanceController::class, 'leaveBreak']);
    Route::get('/attendance/list', [AttendanceController::class, 'getList']);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');