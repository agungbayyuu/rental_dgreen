<?php

namespace App\Support;

use App\Models\Service;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanBulanan
{
    public const KOLOM_BIAYA = 'biaya'; // sesuaikan dengan kolom biaya di tabel services

    public static function hitung(int $bulan, int $tahun): array
    {
        $awal  = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhir = $awal->copy()->endOfMonth();

        $transaksi = Transaksi::query()
            ->with('motor')
            ->where('status', '!=', 'Batal')
            ->whereBetween('tanggal_sewa', [$awal, $akhir])
            ->orderBy('tanggal_sewa')
            ->get();

        $servis = Service::query()
            ->with('motor')
            ->whereBetween('tanggal_service', [$awal->toDateString(), $akhir->toDateString()])
            ->orderBy('tanggal_service')
            ->get();

        $hargaSewa  = (int) $transaksi->sum('harga');
        $jasaAntar  = (int) $transaksi->sum('jasa_antar');
        $biayaHelm  = (int) $transaksi->sum('helm') * Transaksi::HARGA_HELM;

        $pemasukan   = $hargaSewa + $jasaAntar + $biayaHelm;
        $pengeluaran = (int) $servis->sum('biaya_service');

        return [
            'awal'            => $awal,
            'transaksi'       => $transaksi,
            'servis'          => $servis,
            'jumlahTransaksi' => $transaksi->count(),
            'jumlahServis'    => $servis->count(),
            'hargaSewa'       => $hargaSewa,
            'jasaAntar'       => $jasaAntar,
            'biayaHelm'       => $biayaHelm,
            'pemasukan'       => $pemasukan,
            'pengeluaran'     => $pengeluaran,
            'selisih'         => $pemasukan - $pengeluaran,
        ];
    }
}