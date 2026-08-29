<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    protected $fillable = [
        'id_motor',
        'nomor_polisi',
        'motor',
        'status',
        'foto'
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'harga_sewa_harian' => 'decimal:2',
        ];
    }
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}