<?php

namespace App\Filament\Pages;

use App\Models\Motor;
use App\Models\Service;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LaporanServis extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Laporan Servis';

    protected static ?string $title = 'Laporan Servis';

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected string $view = 'filament.pages.laporan-servis';

    // State untuk filter (statePath('data'))
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
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema($this->getFormSchema())
            ->columns(3)
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getFilteredQuery())
            ->columns([
                TextColumn::make('motor.motor')
                    ->label('Motor'),

                TextColumn::make('tanggal_service')
                    ->label('Tgl Servis')
                    ->date('d M Y')
                    ->sortable(),

                IconColumn::make('servis_oli')
                    ->label('Ganti Oli')
                    ->boolean(),

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->catatan),

                TextColumn::make('biaya_service')
                    ->label('Biaya Servis')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->defaultSort('tanggal_service', 'desc');
    }

    protected function getFilteredQuery(): Builder
    {
        $data = $this->data ?? [];

        return Service::query()
            ->with('motor')
            ->when(
                $data['dari_tanggal'] ?? null,
                fn (Builder $query, $date) => $query->whereDate('tanggal_service', '>=', $date),
            )
            ->when(
                $data['sampai_tanggal'] ?? null,
                fn (Builder $query, $date) => $query->whereDate('tanggal_service', '<=', $date),
            )
            ->when(
                $data['motor_id'] ?? null,
                fn (Builder $query, $motorId) => $query->where('motor_id', $motorId),
            );
    }

    public function getTotalBiaya(): int
    {
        return $this->getFilteredQuery()->sum('biaya_service');
    }

    public function getJumlahServis(): int
    {
        return $this->getFilteredQuery()->count();
    }
}