<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAssistantController extends Controller
{
    /**
     * Test the Google AI API connection.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testApi()
    {
        try {
            $result = $this->callGoogleAIModel('This is a test message to verify API connectivity.');
            
            return response()->json([
                'success' => true,
                'message' => 'Google AI API connection successful',
                'response' => $result
            ], 200);
        } catch (\Exception $e) {
            Log::error('AI Assistant Test API Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Google AI API connection failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process a question using the Google AI model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lessonId
     * @return \Illuminate\Http\JsonResponse
     */
    public function processQuestion(Request $request, $lessonId)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'question' => 'required|string|max:1000',
            ]);

            // Get the authenticated user
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'error' => 'Unauthorized: User not authenticated'
                ], 401);
            }

            // Find the lesson
            $lesson = Lesson::findOrFail($lessonId);
            $question = $validated['question'];

            Log::info('AI Assistant Request Received', [
                'user_id' => $user->id,
                'lesson_id' => $lessonId,
                'question' => $question
            ]);

            $context = "Lesson Title: {$lesson->title}\n\nLesson Content: {$lesson->content}";
            
            $prompt = "You are an educational assistant named Flora helping a student understand a lesson. " .
                     "Based on the following lesson content, please answer the student's question.\n\n" .
                     "$context\n\n" .
                     "Student's Question: $question\n\n" .
                     "Please provide a helpful, educational response that explains concepts clearly. " .
                     "If the question is not directly addressed in the lesson content, use your knowledge to provide relevant information, " .
                     "but make it clear what comes from the lesson and what is additional information.";

            Log::info('Generated Prompt', [
                'prompt_length' => strlen($prompt)
            ]);

            $aiResponse = $this->callGoogleAIModel($prompt);
            
            $relatedLessons = $this->findRelatedLessons($lesson->id, $question);
            
            // Fixed: Return 'response' instead of 'answer' to match frontend expectation
            return response()->json([
                'response' => $aiResponse,
                'related_lessons' => $relatedLessons
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('AI Assistant Validation Error', [
                'exception' => $e,
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Lesson not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('AI Assistant Process Question Error', [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to process your question. Please try again later.',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Call the Google AI Gemma model.
     *
     * @param  string  $prompt
     * @return string  The AI's response text.
     * @throws \Exception If API call fails or response is invalid.
     */
    private function callGoogleAIModel($prompt)
    {
        $apiKey = config('services.google_ai.api_key') ?? env('GOOGLE_AI_API_KEY');
        
        if (empty($apiKey)) {
            Log::error('Google AI API key not configured.');
            throw new \Exception('API key not configured for Google AI.');
        }

        // Fixed: Use correct API URL with API key parameter
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;
        
        Log::info('Sending request to Google AI API.', [
            'url_base' => "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent",
            'has_api_key' => !empty($apiKey)
        ]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->timeout(60)
            ->post($apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topP' => 0.8,
                    'topK' => 40,
                    'candidateCount' => 1,
                    'maxOutputTokens' => 1024 
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Google AI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Google AI API returned error: " . $response->status() . " - " . $response->body());
            }

            $result = $response->json();
            
            // Check for blocked content
            if (isset($result['candidates'][0]['finishReason']) && 
                $result['candidates'][0]['finishReason'] === 'SAFETY') {
                throw new \Exception("Content was blocked by safety filters");
            }
            
            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                Log::error('Invalid Google AI API response format', ['response' => $result]);
                throw new \Exception("Invalid response format from Google AI API");
            }

            return $result['candidates'][0]['content']['parts'][0]['text'];
        } catch (\Exception $e) {
            Log::error('Google AI API Call Error', [
                'exception' => $e,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Find lessons related to the question.
     *
     * @param  int  $currentLessonId
     * @param  string  $question
     * @return array
     */
    private function findRelatedLessons($currentLessonId, $question)
    {
        $keywords = $this->extractKeywords($question);
        
        if (empty($keywords)) {
            return [];
        }
        
        $relatedLessons = Lesson::where('id', '!=', $currentLessonId)
            ->where(function($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    if (strlen($keyword) > 3) { 
                        $query->orWhere('title', 'like', "%{$keyword}%")
                              ->orWhere('content', 'like', "%{$keyword}%");
                    }
                }
            })
            ->limit(3)
            ->get(['id', 'title']);
            
        return $relatedLessons->toArray();
    }
    
    /**
     * Extract keywords from a question.
     *
     * @param  string  $text
     * @return array
     */
    private function extractKeywords($text)
    {
        $stopWords = [
            'the', 'and', 'or', 'a', 'an', 'in', 'on', 'at', 'to', 'for', 'with', 'by', 
            'about', 'like', 'through', 'over', 'before', 'between', 'after', 'since', 
            'without', 'under', 'within', 'along', 'following', 'across', 'behind', 
            'beyond', 'plus', 'except', 'but', 'up', 'out', 'around', 'down', 'off', 
            'above', 'near', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 
            'has', 'had', 'do', 'does', 'did', 'will', 'would', 'shall', 'should', 
            'may', 'might', 'must', 'can', 'could', 'what', 'where', 'when', 'why', 
            'how', 'which', 'who', 'whom', 'whose'
        ];
        
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        $words = explode(' ', $text);
        $keywords = array_diff($words, $stopWords);
        
        return array_values(array_filter($keywords, function($word) {
            return strlen(trim($word)) > 2;
        }));
    }
}