<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LessonController;

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
Route::resource('lessons', LessonController::class);



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/chatbot.php';
require __DIR__.'/role_permission.php';
require __DIR__.'/user_permission.php';



