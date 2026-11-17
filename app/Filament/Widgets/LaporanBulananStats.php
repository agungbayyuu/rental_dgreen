<?php

namespace App\Filament\Widgets;

use App\Support\LaporanBulanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\Reactive;

class LaporanBulananStats extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $pollingInterval = null;

    #[Reactive]
    public ?array $filters = [];

    protected function getStats(): array
    {
        $bulan = (int) ($this->filters['bulan'] ?? now()->month);
        $tahun = (int) ($this->filters['tahun'] ?? now()->year);

        $d  = LaporanBulanan::hitung($bulan, $tahun);
        $rp = fn (int $n) => ($n < 0 ? '-' : '') . 'Rp ' . number_format(abs($n), 0, ',', '.');
        $periode = $d['awal']->translatedFormat('F Y');

        return [
            Stat::make('Pemasukan (Transaksi)', $rp($d['pemasukan']))
                ->description($d['jumlahTransaksi'] . ' transaksi, ' . $periode)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Pengeluaran (Servis)', $rp($d['pengeluaran']))
                ->description($d['jumlahServis'] . ' servis, ' . $periode)
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Selisih (Laba Bersih)', $rp($d['selisih']))
                ->description($d['selisih'] >= 0 ? 'Surplus' : 'Defisit')
                ->descriptionIcon($d['selisih'] >= 0 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->color($d['selisih'] >= 0 ? 'success' : 'danger'),
        ];
    }
}