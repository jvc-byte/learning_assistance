<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Chatbot\ChatController;
use App\Http\Controllers\Chatbot\AIAssistantController;

// AI Assistant Routes - Fixed middleware configuration
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/ai/lesson/{lessonId}/question', [AIAssistantController::class, 'processQuestion'])
        ->name('ai.processQuestion');
    
    Route::get('/ai/test', [AIAssistantController::class, 'testApi'])
        ->name('ai.testApi');
});

// Alternative API routes if you need them (without CSRF protection)
Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::post('/api/ai/lesson/{lessonId}/question', [AIAssistantController::class, 'processQuestion'])
        ->name('api.ai.processQuestion');
});

Route::get('chatbot', function () {
    return Inertia::render('Chatbot');
})->middleware(['auth', 'verified'])->name('chatbot');

Route::post('/chat/response', [ChatController::class, 'message'])
    ->middleware(['auth'])
    ->name('chat.response');