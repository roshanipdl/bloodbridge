<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GooglePlacesService;

class Donor extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'donors';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'blood_type',
        'contact',
        'address',
        'place_name',
        'city',
        'latitude',
        'longitude',
        'is_available',
        'health_status',
        'last_donation_date',
        'user_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'is_available' => 'boolean',
        'last_donation_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

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
                'address' => $placeDetails['formatted_address']
            ]);
        }

        return $this;
    }
}
