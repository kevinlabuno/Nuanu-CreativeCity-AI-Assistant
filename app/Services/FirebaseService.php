<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseService
{
    protected $firebase;

    public function __construct()
    {
        $serviceAccount = config('firebase.credentials');
        
        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.database_uri'));
    }

    public function firestore()
    {
        return $this->firebase->createFirestore()
            ->database();
    }
}