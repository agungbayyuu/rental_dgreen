<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LaporanBulananStats;
use App\Support\LaporanBulanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class LaporanBulananPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Laporan Bulanan';
    protected static ?string $title = 'Laporan Bulanan';
    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected string $view = 'filament.pages.laporan-bulanan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $bulan = collect(range(1, 12))->mapWithKeys(
            fn ($m) => [$m => now()->setDay(1)->setMonth($m)->translatedFormat('F')]
        )->all();

        $tahun = collect(range(now()->year - 3, now()->year))
            ->mapWithKeys(fn ($y) => [$y => (string) $y])->all();

        return $schema
            ->schema([
                Select::make('bulan')->label('Bulan')->options($bulan)->live()->required(),
                Select::make('tahun')->label('Tahun')->options($tahun)->live()->required(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    protected function getHeaderWidgets(): array
    {
        return [LaporanBulananStats::class];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 3;
    }

    public function getWidgetData(): array
    {
        return ['filters' => $this->data];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('unduh_pdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $d = LaporanBulanan::hitung(
                        (int) ($this->data['bulan'] ?? now()->month),
                        (int) ($this->data['tahun'] ?? now()->year),
                    );

                    $pdf = Pdf::loadView('pdf.laporan-bulanan', ['d' => $d])
                        ->setPaper('a4', 'portrait');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'laporan-bulanan-' . $d['awal']->format('Y-m') . '.pdf'
                    );
                }),
        ];
    }
}