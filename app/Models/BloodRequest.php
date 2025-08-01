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
        'recipient_name',
        'blood_group',
        'units_required',
        'urgency_level',
        'status',
        'recipient_id',
        'latitude',
        'longitude',
        'notes'
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


}
