<?php

namespace App\Filament\Resources\Services;

use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Service';

       public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('motor_id')
                ->label('Motor')
                ->relationship(name: 'motor', titleAttribute: 'nomor_polisi')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nomor_polisi} - {$record->motor}")
                ->searchable()
                ->preload()
                ->required(),

            DatePicker::make('tanggal_service')
                ->label('Tanggal Service')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y'),

            TextInput::make('biaya_service')
                ->label('Biaya Service')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Select::make('servis_oli')
                ->label('Servis Oli')
                ->options([
                    'Oli Mesin' => 'Oli Mesin',
                    'Oli Mesin & Gardan' => 'Oli Mesin & Gardan',
                    'Tidak Ganti Oli' => 'Tidak Ganti Oli',
                ])
                ->native(false),

            Textarea::make('catatan')
                ->label('Catatan')
                ->rows(2)
                ->columnSpanFull(),
        ]);
    }

      public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('motor'))
            ->columns([
                TextColumn::make('motor.motor')
                    ->label('Nama Motor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('motor.nomor_polisi')
                    ->label('Plat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_service')
                    ->label('Tgl Service')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('biaya_service')
                    ->label('Biaya Service')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('servis_oli')
                    ->label('Servis Oli')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'Oli Mesin' => 'warning',
                        'Oli Mesin & Gardan' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('tanggal_service', 'desc')
            ->filters([
    Filter::make('periode')
        ->label('Periode')
        ->schema([
            Select::make('bulan')
                ->label('Bulan')
                ->options([
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ])
                ->native(false)
                ->placeholder('Semua Bulan')
                ->default(now()->month),

            Select::make('tahun')
                ->label('Tahun')
                ->options(
                    collect(range(now()->year, now()->year - 5))
                        ->mapWithKeys(fn ($year) => [$year => $year])
                        ->all()
                )
                ->native(false)
                ->default(now()->year),
        ])
        ->columns(2)
        ->query(function (Builder $query, array $data): Builder {
            return $query
                ->when(
                    $data['tahun'] ?? null,
                    fn (Builder $q, $tahun) => $q->whereYear('tanggal_service', $tahun)
                )
                ->when(
                    $data['bulan'] ?? null,
                    fn (Builder $q, $bulan) => $q->whereMonth('tanggal_service', $bulan)
                );
        })
        ->indicateUsing(function (array $data): array {
            $indicators = [];

            if ($data['bulan'] ?? null) {
                $namaBulan = Carbon::create()->month((int) $data['bulan'])->locale('id')->translatedFormat('F');
                $indicators[] = 'Bulan: ' . $namaBulan;
            }

            if ($data['tahun'] ?? null) {
                $indicators[] = 'Tahun: ' . $data['tahun'];
            }

            return $indicators;
        }),
]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
