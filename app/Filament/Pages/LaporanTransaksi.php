<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LaporanStats;
use App\Models\Motor;
use App\Models\Transaksi;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LaporanTransaksi extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Transaksi';

    protected static ?string $title = 'Laporan Transaksi';

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected string $view = 'filament.pages.laporan-transaksi';

    // State untuk filter
    public ?string $dari_tanggal = null;

    public ?string $sampai_tanggal = null;

    public ?int $motor_id = null;

    public ?string $status = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('dari_tanggal')
                ->label('Dari Tanggal')
                ->native(false)
                ->live(),

            DatePicker::make('sampai_tanggal')
                ->label('Sampai Tanggal')
                ->native(false)
                ->live(),

            Select::make('motor_id')
                ->label('Motor')
                ->options(Motor::pluck('motor', 'id'))
                ->searchable()
                ->live(),

            Select::make('status')
                ->label('Status Transaksi')
                ->options([
                    'Dibooking' => 'Dibooking',
                    'Berjalan' => 'Berjalan',
                    'Selesai' => 'Selesai',
                    'Batal' => 'Batal',
                ])
                ->live(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema($this->getFormSchema())
            ->columns(4)
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getFilteredQuery())  // <-- dibungkus closure
            ->columns([
                TextColumn::make('nama_customer')
                    ->label('Customer'),

                TextColumn::make('motor.motor')
                    ->label('Motor'),

                TextColumn::make('tanggal_sewa')
                    ->label('Tgl Sewa')
                    ->date('d M Y'),

                TextColumn::make('tanggal_kembali')
                    ->label('Tgl Kembali')
                    ->date('d M Y'),

                TextColumn::make('harga')
                    ->label('Harga Sewa')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('jasa_antar')
                    ->label('Jasa Antar')
                    ->money('IDR', locale: 'id')
                    ->placeholder('-'),

                TextColumn::make('helm')
                    ->label('Helm')
                    ->suffix(' pcs')
                    ->placeholder('-'),

                TextColumn::make('biaya_helm')
                    ->label('Biaya Helm')
                    ->state(fn ($record) => $record->biaya_helm)
                    ->money('IDR', locale: 'id'),

                TextColumn::make('total_bayar')
                    ->label('Total')
                    ->state(fn ($record) => $record->total_bayar)
                    ->money('IDR', locale: 'id')
                    ->weight('bold'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dibooking' => 'warning',
                        'Berjalan' => 'info',
                        'Selesai' => 'success',
                        'Batal' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('harga')
                    ->label('Harga')
                    // ->state(fn ($record) => $this->hitungTotalHarga($record))
                    ->money('IDR'),
            ])
            ->defaultSort('tanggal_sewa', 'desc');
    }

    protected function getFilteredQuery(): Builder
    {
        $data = $this->data ?? [];

        return Transaksi::query()
            ->when(
                $data['dari_tanggal'] ?? null,
                fn (Builder $query, $date) => $query->whereDate('tanggal_sewa', '>=', $date),
            )
            ->when(
                $data['sampai_tanggal'] ?? null,
                fn (Builder $query, $date) => $query->whereDate('tanggal_sewa', '<=', $date),
            )
            ->when(
                $data['motor_id'] ?? null,
                fn (Builder $query, $motorId) => $query->where('motor_id', $motorId),
            )
            ->when(
                $data['status'] ?? null,
                fn (Builder $query, $status) => $query->where('status', $status),
            );
    }

    protected function hitungTotalHarga($record): int
    {
        // Pastikan relasi motor ter-load
        $motor = $record->motor;

        if (! $motor) {
            return 0;
        }

        $jumlahHari = Carbon::parse($record->tanggal_sewa)
            ->diffInDays(Carbon::parse($record->tanggal_kembali)) + 1;

        $hargaHarian = (int) $motor->harga_sewa_harian;

        return $jumlahHari * $hargaHarian;
    }

    // public function getTotalKeseluruhan(): int
    // {
    //     return $this->getFilteredQuery()->sum('harga');
    // }

    public function getJumlahTransaksi(): int
    {
        return $this->getFilteredQuery()->count();
    }
    public function getTotalKeseluruhan(): int
    {
        return $this->getTotalHargaSewa()
            + $this->getTotalJasaAntar()
            + $this->getTotalBiayaHelm();
    }

    public function getTotalHargaSewa(): int
    {
        return (int) $this->getFilteredQuery()->sum('harga');
    }

    public function getTotalJasaAntar(): int
    {
        return (int) $this->getFilteredQuery()->sum('jasa_antar');
    }

    public function getTotalBiayaHelm(): int
    {
        return (int) $this->getFilteredQuery()->sum('helm') * Transaksi::HARGA_HELM;
    }

    protected function getHeaderWidgets(): array
{
    return [
        LaporanStats::class,
    ];
}

public function getHeaderWidgetsColumns(): int|array
{
    return 4;
}

public function getWidgetData(): array
{
    return [
        'filters' => $this->data,
    ];
}
}
