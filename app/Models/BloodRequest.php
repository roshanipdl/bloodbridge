<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GooglePlacesService;

class BloodRequest extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'recipient_name',
        'blood_group',
        'units_required',
        'hospital_name',
        'hospital_address',
        'place_name',
        'city',
        'contact_number',
        'urgency_level',
        'additional_info',
        'status',
        'request_date',
        'fulfill_date',
        'donor_id',
        'recipient_id',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'request_date' => 'date',
        'fulfill_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class, 'recipient_id');
    }

    /**
     * Get the potential donors for this blood request with their matching scores.
     */
    public function potentialDonors()
    {
        return $this->belongsToMany(Donor::class, 'blood_request_donor')
            ->withPivot('score', 'notified')
            ->withTimestamps();
    }

    /**
     * Get the top matching donors for this blood request.
     */
    public function getTopMatchingDonors($limit = 5)
    {
        return $this->potentialDonors()
            ->orderByPivot('score', 'desc')
            ->limit($limit)
            ->get();
    }

    public function updateLocationFromPlaceId($placeId)
    {
        $placesService = app(GooglePlacesService::class);
        $placeDetails = $placesService->getPlaceDetails($placeId);

        if ($placeDetails) {
            $this->update([
                'place_name' => $placeDetails['place_name'],
                'city' => $placeDetails['city'],
                'latitude' => $placeDetails['latitude'],
                'longitude' => $placeDetails['longitude'],
                'hospital_address' => $placeDetails['formatted_address']
            ]);
        }

        return $this;
    }
}
