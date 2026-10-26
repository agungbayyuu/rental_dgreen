<?php

namespace App\Filament\Resources\Motors\Widgets; // sesuaikan dengan hasil generate

use App\Models\Motor;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MotorStats extends StatsOverviewWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalMotor = Motor::count();

        // Motor yang sedang disewa = punya transaksi berstatus "Berjalan"
        $disewa = Transaksi::where('status', 'Berjalan')
            ->distinct('motor_id')
            ->count('motor_id');

        $tersedia = max(0, $totalMotor - $disewa);

        return [
            Stat::make('Jumlah Motor', $totalMotor)
                ->description('Total armada terdaftar')
                ->icon('heroicon-o-truck')
                ->color('primary'),

            Stat::make('Motor Tersedia', $tersedia)
                ->description('Siap disewakan')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Motor Disewa', $disewa)
                ->description('Sedang berjalan')
                ->icon('heroicon-o-key')
                ->color('warning'),
        ];
    }
}