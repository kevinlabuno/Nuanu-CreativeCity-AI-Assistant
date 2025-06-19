<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class AIChatController extends Controller
{
    public function send(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'message' => 'required|string|max:1000',
                'context' => 'nullable|string'
            ]);

            $message = $request->input('message');
            $context = json_decode($request->input('context', '{}'), true);
            $currentLocation = $context['currentSection']['title'] ?? 'this location';

            if (!config('openai.api_key')) {
                throw new \Exception('OpenAI API key is not configured');
            }

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are a helpful AI assistant for Nuanu City Tour. You are currently at {$currentLocation}. Provide accurate and friendly information about the tour locations and attractions."
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            return response()->json([
                'success' => true,
                'message' => $response->choices[0]->message->content
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input: ' . $e->getMessage()
            ], 422);
        } catch (\OpenAI\Exceptions\ErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => 'OpenAI API error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            \Log::error('AI Chat Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }
}