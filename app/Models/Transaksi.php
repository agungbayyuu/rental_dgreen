<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{

    public const HARGA_HELM = 5000;
        
    protected $fillable = [
        'nama_customer',
        'no_whatsapp',
        'motor_id',
        'tanggal_sewa',
        'tanggal_kembali',
        'lokasi_antar',
        'lokasi_ambil',
        'harga',
        'helm',
        'jasa_antar',
        'catatan',
        'periode',
        'status',
    ];

    protected $casts = [
        'tanggal_sewa' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'helm' => 'integer',
        'jasa_antar' => 'integer',
    ];

    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }

    public function getBiayaHelmAttribute(): int
    {
        return (int) ($this->helm ?? 0) * self::HARGA_HELM;
    }

    public function getTotalBayarAttribute(): int
    {
        return (int) ($this->harga ?? 0)
            + (int) ($this->jasa_antar ?? 0)
            + $this->biaya_helm;
    }
}