<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Reactive;

class LaporanStats extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false; // tidak tampil di Dashboard

    protected ?string $pollingInterval = null;

    #[Reactive]
    public ?array $filters = [];

    protected function query(): Builder
    {
        $f = $this->filters ?? [];

        return Transaksi::query()
            ->when($f['dari_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_sewa', '>=', $v))
            ->when($f['sampai_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_sewa', '<=', $v))
            ->when($f['motor_id'] ?? null, fn ($q, $v) => $q->where('motor_id', $v))
            ->when($f['status'] ?? null, fn ($q, $v) => $q->where('status', $v));
    }

    protected function getStats(): array
    {
        $jumlah     = (clone $this->query())->count();
        $hargaSewa  = (int) (clone $this->query())->sum('harga');
        $jasaAntar  = (int) (clone $this->query())->sum('jasa_antar');
        $jumlahHelm = (int) (clone $this->query())->sum('helm');
        $biayaHelm  = $jumlahHelm * Transaksi::HARGA_HELM;

        $total = $hargaSewa + $jasaAntar + $biayaHelm;
        $rp    = fn (int $n) => 'Rp ' . number_format($n, 0, ',', '.');

        return [
            Stat::make('Total Transaksi', $jumlah)
                ->description('Sesuai filter')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Total Pendapatan', $rp($total))
                ->description('Sewa + jasa antar + helm')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Total Jasa Antar', $rp($jasaAntar))
                ->description('Pengantaran motor')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('Total Sewa Helm', $rp($biayaHelm))
                ->description($jumlahHelm . ' pcs')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('warning'),
        ];
    }
}