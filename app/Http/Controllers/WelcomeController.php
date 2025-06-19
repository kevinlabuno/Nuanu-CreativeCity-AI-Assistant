<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Services\AirtableService;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request, FirebaseService $firebaseService, AirtableService $airtableService)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
        ]);
        
        $db = $firebaseService->firestore();
        $datetime = Carbon::now()->format('YmdHis');
        $nameSlug = Str::slug($request->input('fullname'));
        $randomString = Str::lower(Str::random(5));
        $customId = sprintf('guest_%s_%s_%s', $datetime, $nameSlug, $randomString);
    
        $db->collection('guests')
            ->document($customId)
            ->set([
                'fullname' => $request->input('fullname'),
                'created_at' => now(),
            ]);
        
        $airtableService->createGuestRecord($customId, $request->input('fullname'));
    
        $cookie = Cookie::make(
            'guest_id',
            $customId,
            60 * 24 * 30,
            '/',        
            null,       
            false,      
            false,      
            false,      
            'Lax'       
        );
    
        return redirect()->route('tour')
            ->with([
                'success' => 'Name saved successfully!',
                'document_id' => $customId,
                'show_transition' => true
            ])
            ->withCookie($cookie);
    }

    public function clearCookie()
    {
        $cookie = Cookie::forget('guest_id');
        return redirect('/')->withCookie($cookie);
    }

    public function tour_index()
    {
        $firebase = new FirebaseService();
        $firestore = $firebase->firestore();
        $guestId = request()->cookie('guest_id');
        $guestName = 'there';
        if ($guestId) {
            $guestDoc = $firestore->collection('guests')->document($guestId)->snapshot();
            if ($guestDoc->exists()) {
                $guestData = $guestDoc->data();
                $guestName = $guestData['fullname'] ?? 'there';
            }
        }
        $documents = $firestore->collection('data')->documents();
        $dataList = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();
                $dataList[] = $data;
            }
        }
        return view('general-card', [
            'initialData' => $dataList[0] ?? [],
            'allData' => $dataList,
            'currentIndex' => 0,
            'totalItems' => count($dataList),
            'backgroundImage' => isset($dataList[0]['background_image']) ? 
                                '/assets/img/'.$dataList[0]['background_image'] : 
                                '/assets/img/default_background.webp',
            'guestName' => $guestName
        ]);
    }
    
    public function getData(Request $request)
    {
        $index = $request->input('index', 0);
        $firebase = new FirebaseService();
        $firestore = $firebase->firestore();
        
        $documents = $firestore->collection('data')->documents();
        
        $dataList = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();
                $dataList[] = $data;
            }
        }
        
        $index = $index % count($dataList);
        $nextIndex = ($index + 1) % count($dataList);
        
        return response()->json([
            'data' => $dataList[$index] ?? [],
            'background_image' => '/assets/img/'.($dataList[$index]['background_image'] ?? 'default_background.webp'),
            'nextIndex' => $nextIndex,
            'currentIndex' => $index,
            'totalItems' => count($dataList)
        ]);
    }
}
