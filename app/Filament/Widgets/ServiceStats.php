<?php
// app/Filament/Widgets/ServiceStats.php

namespace App\Filament\Widgets;

use App\Support\MotorServiceMonitor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServiceStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected ?string $pollingInterval = null;

    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        $data = MotorServiceMonitor::all();

        return [
            Stat::make('Terlambat servis', $data->where('status', 'terlambat')->count())
                ->description('Segera ganti oli')->color('danger')
                ->descriptionIcon('heroicon-m-exclamation-triangle'),

            Stat::make('Jatuh tempo ≤ 7 hari', $data->where('status', 'segera')->count())
                ->description('Siapkan jadwal')->color('warning')
                ->descriptionIcon('heroicon-m-clock'),

            Stat::make('Perlu ganti oli gardan', $data->whereIn('status', ['terlambat', 'segera'])->where('butuh_gardan', true)->count())
                ->description('Pada servis berikutnya')->color('info')
                ->descriptionIcon('heroicon-m-cog-6-tooth'),

            Stat::make('Aman', $data->where('status', 'aman')->count())
                ->description('Belum perlu servis')->color('success')
                ->descriptionIcon('heroicon-m-check-circle'),
        ];
    }
}