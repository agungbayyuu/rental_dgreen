<?php

namespace App\Filament\Widgets;

use App\Models\Motor;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $totalMotor = Motor::count();

        $totalTransaksiBulanIni = Transaksi::whereMonth('tanggal_sewa', $bulanIni)
            ->whereYear('tanggal_sewa', $tahunIni)
            ->count();

        return [
            Stat::make('Total Motor', $totalMotor)
                ->description('Jumlah armada terdaftar')
                ->icon('heroicon-o-truck')
                ->color('success'),

            Stat::make('Transaksi Bulan Ini', $totalTransaksiBulanIni)
                ->description(now()->translatedFormat('F Y'))
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary'),
        ];
    }
}