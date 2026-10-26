<?php
// app/Support/MotorServiceMonitor.php

namespace App\Support;

use App\Models\Motor;
use Illuminate\Support\Collection;

class MotorServiceMonitor
{
    public const INTERVAL_BULAN  = 2;  // oli mesin tiap 2 bulan
    public const GARDAN_SETIAP   = 3;  // gardan tiap 3x ganti oli mesin
    public const HARI_PERINGATAN = 7;  // status "segera" jika <= 7 hari

    public static function analyze(Motor $motor): array
    {
        $oliMesin = $motor->services
            ->filter(fn ($s) => str_contains(strtolower($s->servis_oli), 'mesin'))
            ->sortBy([['tanggal_service', 'asc'], ['id', 'asc']])
            ->values();

        $terakhir = $oliMesin->last();

        if (! $terakhir) {
            return [
                'motor'          => $motor,
                'status'         => 'belum',
                'terakhir'       => null,
                'jatuh_tempo'    => null,
                'sisa_hari'      => null,
                'butuh_gardan'   => false,
                'urutan_ke'      => 0,
            ];
        }

        // Servis gardan terakhir
        $gardanTerakhir = $oliMesin
            ->filter(fn ($s) => str_contains(strtolower($s->servis_oli), 'gardan'))
            ->last();

        // Jumlah servis oli mesin sejak gardan terakhir (tidak termasuk servis gardan itu sendiri)
        $sejakGardan = $gardanTerakhir
            ? $oliMesin->filter(fn ($s) => $s->tanggal_service->gt($gardanTerakhir->tanggal_service)
                || ($s->tanggal_service->eq($gardanTerakhir->tanggal_service) && $s->id > $gardanTerakhir->id))->count()
            : $oliMesin->count();

        // Servis berikutnya adalah ke-(sejakGardan + 1); gardan jika sudah 2x mesin saja
        $butuhGardan = $sejakGardan >= (self::GARDAN_SETIAP - 1);

        $jatuhTempo = $terakhir->tanggal_service->copy()->addMonthsNoOverflow(self::INTERVAL_BULAN);
        $sisaHari   = (int) now()->startOfDay()->diffInDays($jatuhTempo->startOfDay(), false);

        $status = match (true) {
            $sisaHari < 0                           => 'terlambat',
            $sisaHari <= self::HARI_PERINGATAN      => 'segera',
            default                                 => 'aman',
        };

        return [
            'motor'        => $motor,
            'status'       => $status,
            'terakhir'     => $terakhir->tanggal_service,
            'jatuh_tempo'  => $jatuhTempo,
            'sisa_hari'    => $sisaHari,
            'butuh_gardan' => $butuhGardan,
            'urutan_ke'    => $sejakGardan + 1, // posisi dalam siklus gardan (1..3)
        ];
    }

    /** Semua motor, diurutkan dari yang paling mendesak. */
    public static function all(): Collection
    {
        $prioritas = ['terlambat' => 0, 'segera' => 1, 'belum' => 2, 'aman' => 3];

        return Motor::with('services')->get()
            ->map(fn ($m) => self::analyze($m))
            ->sortBy([
                fn ($a, $b) => $prioritas[$a['status']] <=> $prioritas[$b['status']],
                fn ($a, $b) => ($a['sisa_hari'] ?? PHP_INT_MAX) <=> ($b['sisa_hari'] ?? PHP_INT_MAX),
            ])
            ->values();
    }
}