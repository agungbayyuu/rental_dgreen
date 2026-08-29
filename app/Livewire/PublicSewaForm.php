<?php

namespace App\Livewire;

use App\Models\Transaksi;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema; // <-- ganti dari Filament\Forms\Form
use Filament\Notifications\Notification;
use Livewire\Component;


class PublicSewaForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema // <-- ganti signature
    {
        return $schema
            ->components([ // <-- ganti dari ->schema([...]) menjadi ->components([...])
                TextInput::make('nama_customer')
                    ->label('Nama Lengkap')
                    ->required(),

                TextInput::make('no_whatsapp')
                    ->label('No. WhatsApp')
                    ->tel()
                    ->required(),

                Select::make('motor_id')
                    ->label('Pilih Motor')
                    ->relationship(
                        name: 'motor',
                        titleAttribute: 'nomor_polisi',
                        modifyQueryUsing: fn ($query) => $query->where('status', 'Tersedia')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nomor_polisi} - {$record->merk} {$record->tipe}")
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('tanggal_sewa')->required()->minDate(now()),
                DatePicker::make('tanggal_kembali')->required()->afterOrEqual('tanggal_sewa'),

                TextInput::make('lokasi_antar'),
                TextInput::make('lokasi_ambil'),
            ])
            ->statePath('data')
            ->model(Transaksi::class);
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $data['status'] = 'Dibooking';

        Transaksi::create($data);

        Notification::make()
            ->title('Pengajuan sewa berhasil dikirim!')
            ->success()
            ->send();

        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.public-sewa-form');
    }
}