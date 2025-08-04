<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'total_donations',
        'contact',
        'latitude',
        'longitude',
        'is_available',
        'health_status',
        'last_donation_date',
        'user_id',
        'donation_history',
        'health_notes',
        'next_eligible_donation_date',
        'medical_conditions',
        'last_health_check_date',
        'donations_in_last_2_years'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'is_available' => 'boolean',
        'last_donation_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'next_eligible_donation_date' => 'date',
        'last_health_check_date' => 'date'
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
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function donationHistory() {
        return $this->hasMany(DonationHistory::class);
    }

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


}
