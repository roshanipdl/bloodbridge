<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'blood_type_needed',
        'units_required',
        'urgency_level',
        'notes',
        'status',
        'fulfill_date',
        'donor_id'
    ];

    protected $casts = [
        'fulfill_date' => 'datetime',
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
}
