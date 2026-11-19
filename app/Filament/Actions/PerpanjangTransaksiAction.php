<?php

namespace App\Filament\Actions;

use App\Models\Transaksi;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class PerpanjangTransaksiAction
{
    public static function make(): Action
    {
        return Action::make('perpanjang')
            ->label('Perpanjang')
            ->icon('heroicon-o-arrow-path-rounded-square')
            ->color('warning')
            ->visible(fn (Transaksi $record) => $record->status === 'Berjalan')
            ->modalHeading(fn (Transaksi $record) => "Perpanjang sewa: {$record->nama_customer}")
            ->modalDescription('Transaksi lama akan diselesaikan dan transaksi baru dibuat otomatis.')
            ->modalSubmitActionLabel('Buat Perpanjangan')
            ->fillForm(fn (Transaksi $record) => [
                'tanggal_sewa'    => $record->tanggal_kembali,
                'tanggal_kembali' => $record->tanggal_kembali->copy()->addDay(),
                'helm'            => $record->helm,
            ])
            ->schema([
                DateTimePicker::make('tanggal_sewa')
                    ->label('Mulai Perpanjangan')
                    ->required()
                    ->native(false)
                    ->seconds(false)
                    ->displayFormat('d/m/Y H:i'),

                DateTimePicker::make('tanggal_kembali')
                    ->label('Kembali (Baru)')
                    ->required()
                    ->native(false)
                    ->seconds(false)
                    ->displayFormat('d/m/Y H:i')
                    ->afterOrEqual('tanggal_sewa'),

                TextInput::make('harga')
                    ->label('Harga Sewa Perpanjangan')
                    ->numeric()
                    ->prefix('Rp')
                    ->datalist(['35000', '40000', '45000', '65000']),

                TextInput::make('helm')
                    ->label('Jumlah Helm')
                    ->numeric()
                    ->minValue(0),

                TextInput::make('jasa_antar')
                    ->label('Jasa Antar (jika ada)')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0),
            ])
            ->action(function (array $data, Transaksi $record, Action $action) {
                // Cek bentrok dengan booking lain di motor yang sama
                $bentrok = Transaksi::where('motor_id', $record->motor_id)
                    ->where('id', '!=', $record->id)
                    ->whereIn('status', ['Dibooking', 'Berjalan'])
                    ->where('tanggal_sewa', '<', $data['tanggal_kembali'])
                    ->where('tanggal_kembali', '>', $data['tanggal_sewa'])
                    ->exists();

                if ($bentrok) {
                    Notification::make()
                        ->title('Motor sudah dibooking pada rentang tersebut')
                        ->body('Perpanjangan tidak bisa dibuat.')
                        ->danger()
                        ->send();

                    $action->halt();
                }

                DB::transaction(function () use ($data, $record) {
                    // 1. Buat transaksi baru (data customer disalin)
                    Transaksi::create([
                        'perpanjangan_dari_id' => $record->id,
                        'nama_customer'   => $record->nama_customer,
                        'no_whatsapp'     => $record->no_whatsapp,
                        'motor_id'        => $record->motor_id,
                        'tanggal_sewa'    => $data['tanggal_sewa'],
                        'tanggal_kembali' => $data['tanggal_kembali'],
                        'harga'           => $data['harga'] ?? null,
                        'helm'            => $data['helm'] ?? null,
                        'jasa_antar'      => $data['jasa_antar'] ?? null,
                        'lokasi_ambil'    => $record->lokasi_ambil,
                        'periode'         => $record->periode,
                        'catatan'         => "Perpanjangan dari transaksi #{$record->id}",
                        'status'          => 'Berjalan',
                    ]);

                    // 2. Selesaikan transaksi lama
                    $record->update(['status' => 'Selesai']);
                });

                Notification::make()
                    ->title('Perpanjangan berhasil dibuat')
                    ->success()
                    ->send();
            });
    }
}