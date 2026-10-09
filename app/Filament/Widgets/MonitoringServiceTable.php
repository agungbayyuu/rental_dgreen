<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Services\ServiceResource; // sesuaikan namespace
use App\Models\Motor;
use App\Support\MotorServiceMonitor;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class MonitoringServiceTable extends BaseWidget
{
    protected static ?string $heading = 'Monitoring Servis Motor';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Motor::query()
                    ->with('services')
                    ->withMax('services as terakhir_service', 'tanggal_service')
            )
            ->columns([
                TextColumn::make('nomor_polisi')
                    ->label('Plat')
                    ->searchable(),

                TextColumn::make('motor')
                    ->label('Motor')
                    ->searchable(),

                TextColumn::make('terakhir_service')
                    ->label('Servis terakhir')
                    ->date('d M Y')
                    ->placeholder('Belum pernah')
                    ->sortable(),

                TextColumn::make('jatuh_tempo')
                    ->label('Jatuh tempo')
                    ->state(fn (Motor $r) => MotorServiceMonitor::analyze($r)['jatuh_tempo'])
                    ->date('d M Y')
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(function (Motor $r) {
                        $a = MotorServiceMonitor::analyze($r);

                        return match ($a['status']) {
                            'terlambat' => 'Terlambat ' . abs($a['sisa_hari']) . ' hari',
                            'segera'    => $a['sisa_hari'] === 0 ? 'Hari ini' : $a['sisa_hari'] . ' hari lagi',
                            'aman'      => $a['sisa_hari'] . ' hari lagi',
                            default     => 'Belum pernah servis',
                        };
                    })
                    ->color(fn (Motor $r) => match (MotorServiceMonitor::analyze($r)['status']) {
                        'terlambat' => 'danger',
                        'segera'    => 'warning',
                        'aman'      => 'success',
                        default     => 'gray',
                    }),

                TextColumn::make('perlu_diganti')
                    ->label('Yang perlu diganti')
                    ->badge()
                    ->state(function (Motor $r) {
                        $a = MotorServiceMonitor::analyze($r);

                        return $a['butuh_gardan']
                            ? ['Oli Mesin', 'Oli Gardan']
                            : ['Oli Mesin'];
                    })
                    ->color(fn (string $state) => $state === 'Oli Gardan' ? 'info' : 'primary'),
            ])
            ->filters([
                Filter::make('perlu_servis')
                    ->label('Perlu servis (terlambat / ≤ 7 hari / belum pernah)')
                    ->default()
                    ->query(fn (Builder $query) => $query->whereDoesntHave(
                        'services',
                        fn (Builder $s) => $s->whereDate(
                            'tanggal_service',
                            '>',
                            now()->addDays(MotorServiceMonitor::HARI_PERINGATAN)
                                ->subMonthsNoOverflow(MotorServiceMonitor::INTERVAL_BULAN)
                                ->toDateString()
                        )
                    )),
            ])
            ->recordActions([
                Action::make('catat')
                    ->label('Catat servis')
                    ->icon('heroicon-m-plus')
                    ->size('xs')
                    ->url(fn (Motor $r) => ServiceResource::getUrl('create', ['motor_id' => $r->id])),
            ])
            ->defaultSort('terakhir_service', 'asc') // servis paling lama di atas
            ->paginated(false);
    }
}