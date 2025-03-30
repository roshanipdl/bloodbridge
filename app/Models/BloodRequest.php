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
        'hospital_name',
        'hospital_address',
        'contact_number',
        'urgency_level',
        'additional_info',
        'status',
        'request_date',
        'fulfill_date',
        'donor_id',
        'recipient_id',
    
    ];

    protected $casts = [
        'fulfill_date' => 'date',
        'request_date' => 'date',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}


