<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GooglePlacesService
{
    protected $apiKey;
    protected $baseUrl = 'https://maps.googleapis.com/maps/api/place';

    public function __construct()
    {
        $this->apiKey = config('services.google.places_api_key');
    }

    public function getPlaceDetails($placeId)
    {
        try {
            $response = Http::get("{$this->baseUrl}/details/json", [
                'place_id' => $placeId,
                'key' => $this->apiKey,
                'fields' => 'name,formatted_address,geometry,address_components'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'OK') {
                    $result = $data['result'];
                    
                    // Extract city from address components
                    $city = '';
                    foreach ($result['address_components'] as $component) {
                        if (in_array('locality', $component['types'])) {
                            $city = $component['long_name'];
                            break;
                        }
                    }

                    return [
                        'place_name' => $result['name'],
                        'city' => $city,
                        'latitude' => $result['geometry']['location']['lat'],
                        'longitude' => $result['geometry']['location']['lng'],
                        'formatted_address' => $result['formatted_address']
                    ];
                }
            }

            Log::error('Google Places API error', [
                'response' => $response->json(),
                'place_id' => $placeId
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Google Places API exception', [
                'message' => $e->getMessage(),
                'place_id' => $placeId
            ]);

            return null;
        }
    }
} 