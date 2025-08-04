<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationHistory extends Model
{
    protected $fillable = [
        'donor_id',
        'blood_request_id', 
        'donation_request_id',
        'donation_date',
        'blood_group',
        'notes'
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function donationRequest()
    {
        return $this->belongsTo(DonationRequest::class);
    }
}
