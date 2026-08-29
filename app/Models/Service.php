<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'motor_id',
        'tanggal_service',
        'biaya_service',
        'servis_oli',
        'catatan',
    ];

    protected $casts = [
        'tanggal_service' => 'date',
    ];

    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }
}