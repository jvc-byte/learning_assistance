<?php

use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});

Route::get('chatbot', function () {
    return Inertia::render('Chatbot');
})->middleware(['auth', 'verified'])->name('chatbot');

Route::post('/chat/response', [ChatController::class, 'message'])
    ->middleware(['auth'])
    ->name('chat.response');


// Route:get('lessons', function () {
//     return Inertia::render('Lessons');
// })->middleware(['auth', 'verified'])->name('lessons');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
