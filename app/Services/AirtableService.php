<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AirtableService
{
    protected $apiKey;
    protected $baseId;

    public function __construct()
    {
        $this->apiKey = 'patR4neHUt1pv3wCS.62dd595fe8c34a3a35fd9859041ec26c487b66e583a73d4613bb94df5aa1ffc1';
        $this->baseId = 'appR3m8XuIxr2oshv';
    }

    // For guest records
    public function createGuestRecord($documentId, $name)
    {
        return $this->createRecord('tblvHve03FrzJb5rO', [
            'records' => [
                [
                    'fields' => [
                        'DocumentID' => $documentId,
                        'FullName' => $name,
                        'Notes' => 'Nuanu-AI'
                    ]
                ]
            ]
        ]);
    }

    // For chat records
    public function createChatRecord($data)
    {
        return $this->createRecord('tblLwiM1opuryIq4P', [
            'records' => [
                [
                    'fields' => [
                        'FullName' => $data['fullname'],
                        'Content' => $data['content'],
                        'Section' => $data['location'],
                        'Type' => $data['type']
                    ]
                ]
            ]
        ]);
    }

    // Generic record creation
    protected function createRecord($tableId, $payload)
    {
        $url = "https://api.airtable.com/v0/{$this->baseId}/{$tableId}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        if ($response->failed()) {
            logger()->error('Airtable API Error', [
                'status' => $response->status(),
                'response' => $response->body(),
                'payload' => $payload
            ]);
            throw new \Exception('Airtable error: ' . $response->body());
        }

        return $response->json();
    }
}