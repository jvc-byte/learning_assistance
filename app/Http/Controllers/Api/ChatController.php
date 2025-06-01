<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function message(Request $request)
    {
        $message = $request->input('message');
        
        if (empty($message)) {
            return response()->json(['error' => 'Message cannot be empty', 'success' => false], 400);
        }

        try {
            $apiKey = env('GOOGLE_AI_API_KEY');
            
            if (empty($apiKey)) {
                return response()->json(['error' => 'API key not configured', 'success' => false], 500);
            }

            // Use Gemini 3.4B model
            $response = Http::timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/gemma-3-4b-it:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $message
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topP' => 0.8,
                    'topK' => 40,
                    'candidateCount' => 1,
                    'maxOutputTokens' => 1024
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated';
                
                return response()->json(['response' => trim($text)]);
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'API request failed';
                
                return response()->json(['error' => 'API Error: ' . $errorMessage], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Chat API Error: ' . $e->getMessage());
            
            return response()->json(['error' => 'Failed to generate response: ' . $e->getMessage()], 500);
        }
    }
}