<?php

namespace App\Filament\Resources\Services;

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
                //
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
