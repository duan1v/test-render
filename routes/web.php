<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('employee', [
    \App\Http\Controllers\EmployeeController::class,
    'index'
])->middleware(['auth', 'verified'])->name('employee');

Route::get('migrate', [
    \App\Http\Controllers\MigrateController::class,
    'index'
])->middleware(['auth', 'verified'])->name('migrate');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
