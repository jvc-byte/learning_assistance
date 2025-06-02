<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Chatbot\ChatController;

Route::get('chatbot', function () {
    return Inertia::render('Chatbot');
})->middleware(['auth', 'verified'])->name('chatbot');

Route::post('/chat/response', [ChatController::class, 'message'])
    ->middleware(['auth'])
    ->name('chat.response');