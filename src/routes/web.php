<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/attendance', [AttendanceController::class, 'index']);

/*
Route::get('/admin/login', [LoginController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'store']);
Route::get('/admin/attendance/list', [AdminAttendanceController::class, 'index'])->middleware('auth:admin');
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

Route::middleware(['auth:web', 'verified'])->get('/attendance', function () {
    return view('attendance');
})->name('attendance');