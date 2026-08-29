<?php

namespace App\Filament\Resources\Transaksis;

use App\Filament\Resources\Transaksis\Pages\CreateTransaksi;
use App\Filament\Resources\Transaksis\Pages\EditTransaksi;
use App\Filament\Resources\Transaksis\Pages\ListTransaksis;
use App\Filament\Resources\Transaksis\Schemas\TransaksiForm;
use App\Filament\Resources\Transaksis\Tables\TransaksisTable;
use App\Models\Transaksi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
// use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Actions\Action;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
    return $schema
            ->schema([
                        TextInput::make('nama_customer')
                            ->label('Nama Customer')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('no_whatsapp')
                            ->label('No. WhatsApp')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('08xxxxxxxxxx'),

                        Select::make('motor_id')
                            ->label('Motor')
                            ->relationship(
                                name: 'motor',
                                titleAttribute: 'nomor_polisi',
                                modifyQueryUsing: function ($query, $record) {
                                    return $query->where('status', 'Tersedia')
                                        ->when($record, fn ($q) => $q->orWhere('id', $record->motor_id));
                                }
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nomor_polisi} - {$record->motor}")
                            ->searchable()
                            ->preload()
                            ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'Dibooking' => 'Dibooking',
                            'Berjalan'  => 'Berjalan',
                            'Selesai'   => 'Selesai',
                            'Batal'     => 'Batal',
                        ])
                        ->default('Dibooking')
                        ->required(),

                    DateTimePicker::make('tanggal_sewa')
                        ->label('Tanggal Sewa')
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y H:i')
                        ->seconds(false),

                    DateTimePicker::make('tanggal_kembali')
                        ->label('Tanggal Kembali')
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y H:i')
                        ->seconds(false)
                        ->afterOrEqual('tanggal_sewa'),

                    TextInput::make('harga')
                        ->label('Harga Sewa')
                        ->numeric()
                        ->prefix('Rp')
                        ->minValue(0)
                        // ->required()
                        ->placeholder('150000'),

                    TextInput::make('lokasi_antar')
                        ->label('Lokasi Antar')
                        ->maxLength(255)
                        ->placeholder('Kosongkan jika ambil sendiri'),

                    TextInput::make('lokasi_ambil')
                        ->label('Lokasi Ambil')
                        ->maxLength(255)
                        ->placeholder('Kosongkan jika kembali sendiri'),
                    Textarea::make('pesan_whatsapp')
                        ->label('Pesan untuk WhatsApp')
                        ->rows(12)
                        ->dehydrated(false) // tidak disimpan ke database, hanya tampilan sementara
                        ->default(fn ($record) => $record ? self::generateWhatsappMessage($record) : null)
                        ->extraInputAttributes(['id' => 'pesan-whatsapp-field'])
                        ->columnSpanFull()
                        ->hintAction(
                            Action::make('generate')
                                ->label('Generate Ulang')
                                ->icon('heroicon-o-arrow-path')
                                ->action(function ($set, $record) {
                                    if ($record) {
                                        $set('pesan_whatsapp', self::generateWhatsappMessage($record->fresh()));
                                    }
                                })
                        ),
                    Placeholder::make('aksi_pesan')
                        ->label('')
                        ->content(function ($record) {
                            if (! $record) return null;

                            $nomor = preg_replace('/^0/', '62', preg_replace('/\D/', '', $record->no_whatsapp));

                            return new HtmlString(<<<HTML
                                <div class="flex gap-3" x-data="{
                                    copyText() {
                                        const text = document.getElementById('pesan-whatsapp-field').value;
                                        navigator.clipboard.writeText(text);
                                        alert('Pesan berhasil disalin!');
                                    },
                                    sendWa() {
                                        const text = document.getElementById('pesan-whatsapp-field').value;
                                        window.open('https://wa.me/{$nomor}?text=' + encodeURIComponent(text), '_blank');
                                    }
                                }">
                                    <button type="button" @click="copyText()"
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium">
                                        📋 Copy Pesan
                                    </button>
                                    <button type="button" @click="sendWa()"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">
                                        💬 Kirim ke WhatsApp
                                    </button>
                                </div>
                            HTML);
                        })
                        ->columnSpanFull(),
            ]);
        }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama_customer')
                    ->label('Nama Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_whatsapp')
                    ->label('No. WhatsApp')
                    ->searchable(),

                TextColumn::make('motor.nomor_polisi')
                    ->label('Motor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_sewa')
                    ->label('Tgl Sewa')
                    ->date('d M Y H:i')
                    ->sortable(),

                TextColumn::make('tanggal_kembali')
                    ->label('Tgl Kembali')
                    ->date('d M Y H:i')
                    ->sortable(),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->toggleable(),

                // TextColumn::make('lokasi_ambil')
                //     ->label('Lokasi Ambil')
                //     ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dibooking' => 'warning',
                        'Berjalan'  => 'info',
                        'Selesai'   => 'success',
                        'Batal'     => 'danger',
                        default     => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
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
            'index' => ListTransaksis::route('/'),
            'create' => CreateTransaksi::route('/create'),
            'edit' => EditTransaksi::route('/{record}/edit'),
        ];
    }
    public static function generateWhatsappMessage(\App\Models\Transaksi $record): string
    {
        $mulai   = $record->tanggal_sewa;
        $selesai = $record->tanggal_kembali;
        $durasiHari = max(1, $mulai->diffInDays($selesai));

        $pesan  = "📄 SP: Sewa Motor Harian\n";
        $pesan .= "Nama : {$record->nama_customer}\n";
        $pesan .= "Motor: {$record->motor->motor}\n";
        $pesan .= "Plat : {$record->motor->nomor_polisi}\n";
        $pesan .= "📅 Periode Sewa: " . $mulai->translatedFormat('d') . " - " . $selesai->translatedFormat('d F Y H:i') . " wib = {$durasiHari} hari\n";
        $pesan .= "💰 Biaya Sewa: Rp " . number_format($record->harga, 0, ',', '.') . "\n";

        if ($record->lokasi_antar) {
            $pesan .= "📍 Lokasi Antar: {$record->lokasi_antar}\n";
        }
        if ($record->lokasi_ambil) {
            $pesan .= "📍 Lokasi Ambil: {$record->lokasi_ambil}\n";
        }

        $pesan .= "Status pembayaran : \n"; // sengaja kosong, diisi manual oleh admin
        $pesan .= "🔁 Transfer ke:\n";
        $pesan .= "BRI: Qris\n";
        $pesan .= "Setelah transfer, jangan lupa kabari ya.\n";
        $pesan .= "Terima kasih! 🙏";

        return $pesan;
    }
}
