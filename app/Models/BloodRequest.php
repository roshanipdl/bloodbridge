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
        'units_required',
        'urgency_level',
        'hospital_name',
        'additional_info',
        'notes',
        'status',
        'recipient_id',
        'latitude',
        'longitude',
        'required_by_date',
        'donor_id',
        'fulfill_date',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'required_by_date' => 'date',
        'fulfill_date' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7'
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class, 'recipient_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCustomLabelAttribute()
    {
        return "{$this->id} - {$this->recipient_name} ( {$this->recipient->blood_group} )";
    }
}
