<x-filament-panels::page>

    {{-- Form Filter --}}
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>

    {{-- Ringkasan Total --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-filament::section>
            <div class="text-sm text-gray-500">Jumlah Transaksi Servis</div>
            <div class="text-2xl font-bold">
                {{ $this->getJumlahServis() }}
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500">Total Biaya Servis</div>
            <div class="text-2xl font-bold text-success-600">
                Rp {{ number_format($this->getTotalBiaya(), 0, ',', '.') }}
            </div>
        </x-filament::section>
    </div>

    {{-- Tabel Detail --}}
    {{ $this->table }}

</x-filament-panels::page>