<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $fillable = [
        'nama_customer',
        'no_whatsapp',
        'motor_id',
        'tanggal_sewa',
        'tanggal_kembali',
        'lokasi_antar',
        'lokasi_ambil',
        'harga',
        'status',
    ];

    protected $casts = [
        'tanggal_sewa' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }
}