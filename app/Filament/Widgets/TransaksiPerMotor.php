<?php

namespace App\Filament\Widgets;

use App\Models\Motor;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TransaksiPerMotor extends BaseWidget
{
    protected static ?string $heading = 'Transaksi per Motor Bulan Ini';

    public function table(Table $table): Table
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        return $table
            ->query(
                Motor::query()
                    ->withCount(['transaksis' => function (Builder $query) use ($bulanIni, $tahunIni) {
                        $query->whereMonth('tanggal_sewa', $bulanIni)
                              ->whereYear('tanggal_sewa', $tahunIni);
                    }])
            )
            ->columns([
                Tables\Columns\TextColumn::make('nomor_polisi')
                    ->label('Plat'),

                Tables\Columns\TextColumn::make('motor')
                    ->label('Motor'),

                Tables\Columns\TextColumn::make('transaksis_count')
                    ->label('Total Transaksi')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state) => $state > 0 ? 'success' : 'gray'),
            ])
            ->defaultSort('transaksis_count', 'desc')
            ->paginated(false);
    }
}