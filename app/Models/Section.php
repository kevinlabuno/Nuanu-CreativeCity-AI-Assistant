<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Exception\Database\DatabaseNotFound;
use Illuminate\Support\Facades\Log;

class Section extends Model
{
    protected $fillable = [
        'title',
        'concept',
        'description',
        'image',
        'background_image',
        'open_hours'
    ];

    public static function getNextSection($currentSectionId = null)
    {
        try {
            $firebase = (new Factory)
                ->withServiceAccount(config('firebase.credentials'))
                ->withDatabaseUri(config('firebase.database_uri'))
                ->createDatabase();

            $sections = $firebase->getReference('data')->getValue();
            if (!$sections) {
                Log::error('No sections found in Firebase database');
                return null;
            }

            $sectionIds = array_keys($sections);
            
            if (!$currentSectionId) {
                return $sections[$sectionIds[0]];
            }

            $currentIndex = array_search($currentSectionId, $sectionIds);
            
            if ($currentIndex === false || $currentIndex >= count($sectionIds) - 1) {
                return $sections[$sectionIds[0]]; // Return to first section
            }

            return $sections[$sectionIds[$currentIndex + 1]];
        } catch (DatabaseNotFound $e) {
            Log::error('Firebase database not found: ' . $e->getMessage());
            Log::error('Database URL: ' . config('firebase.database_url'));
            return null;
        } catch (\Exception $e) {
            Log::error('Firebase error: ' . $e->getMessage());
            return null;
        }
    }
} 