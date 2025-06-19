<?php

use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::post('/guests', [App\Http\Controllers\WelcomeController::class, 'store'])->name('guests.store');
Route::post('/clear-cookie', [App\Http\Controllers\WelcomeController::class, 'clearCookie'])->name('clear.cookie');
Route::get('tour', [App\Http\Controllers\WelcomeController::class, 'tour_index'])->name('tour');
Route::get('/tour/data', [App\Http\Controllers\WelcomeController::class, 'getData'])->name('tour.data');
Route::post('/ai-chat/send', [App\Http\Controllers\AIChatController::class, 'send'])->name('ai.chat.send');
Route::get('/test-openai', function () {
    try {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello']
            ],
            'max_tokens' => 50,
        ]);

        return response()->json([
            'success' => true,
            'response' => $response->choices[0]->message->content
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::post('/save-chat', [App\Http\Controllers\ChatController::class, 'saveChat']);