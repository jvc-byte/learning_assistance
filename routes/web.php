<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('lesson', function () {
    return Inertia::render('Lesson');
})->middleware(['auth', 'verified'])->name('lesson');

Route::resource('users', UserController::class);
Route::resource('roles', RoleController::class);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/chatbot.php';

