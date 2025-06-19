<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Services\AirtableService;

class ChatController extends Controller
{
    public function saveChat(Request $request, FirebaseService $firebaseService, AirtableService $airtableService)
    {
        $request->validate([
            'guest_id' => 'required|string',
            'fullname' => 'required|string',
            'type' => 'required|string|in:question,response',
            'content' => 'required|string',
            'location' => 'required|string',
            'timestamp' => 'required|string'
        ]);

        // Save to Firebase
        $db = $firebaseService->firestore();
        $docId = $request->fullname . '_' . $request->guest_id;
        $chatRef = $db->collection('chats')
            ->document($docId)
            ->collection('chats')
            ->newDocument();
        
        $chatData = [
            'type' => $request->type,
            'content' => $request->content,
            'section' => $request->location,
            'timestamp' => $request->timestamp,
            'fullname' => $request->fullname,
            'created_at' => now()
        ];

        $chatRef->set($chatData);

        // Save to Airtable (only for questions if you want)
        if ($request->type === 'question') {
            try {
                $airtableService->createChatRecord([
                    'fullname' => $request->fullname,
                    'content' => $request->content,
                    'location' => $request->location,
                    'type' => $request->type
                ]);
            } catch (\Exception $e) {
                // Log error but don't break the flow
                logger()->error('Airtable chat save failed', [
                    'error' => $e->getMessage(),
                    'data' => $request->all()
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}