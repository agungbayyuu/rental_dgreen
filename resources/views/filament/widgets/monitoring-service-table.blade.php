{{-- resources/views/filament/widgets/monitoring-service-table.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section heading="Monitoring Servis Motor" description="Oli mesin tiap 2 bulan · Oli gardan tiap 3x ganti oli mesin">
        <x-slot name="headerEnd">
            <x-filament::button size="sm" :color="$filter === 'perlu' ? 'primary' : 'gray'" wire:click="$set('filter','perlu')">Perlu servis</x-filament::button>
            <x-filament::button size="sm" :color="$filter === 'semua' ? 'primary' : 'gray'" wire:click="$set('filter','semua')">Semua motor</x-filament::button>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 border-b dark:border-white/10">
                    <tr>
                        <th class="py-2 pr-4">Motor</th>
                        <th class="py-2 pr-4">Plat</th>
                        <th class="py-2 pr-4">Servis terakhir</th>
                        <th class="py-2 pr-4">Jatuh tempo</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2">Yang perlu diganti</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-white/10">
                @forelse ($rows as $r)
                    @php
                        $badge = match ($r['status']) {
                            'terlambat' => ['danger',  'Terlambat ' . abs($r['sisa_hari']) . ' hari'],
                            'segera'    => ['warning', $r['sisa_hari'] === 0 ? 'Hari ini' : $r['sisa_hari'] . ' hari lagi'],
                            'aman'      => ['success', $r['sisa_hari'] . ' hari lagi'],
                            default     => ['gray',    'Belum pernah servis'],
                        };
                    @endphp
                    <tr>
                        <td class="py-3 pr-4 font-medium">{{ $r['motor']->nama_motor }}</td>
                        <td class="py-3 pr-4">{{ $r['motor']->plat }}</td>
                        <td class="py-3 pr-4">{{ $r['terakhir']?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td class="py-3 pr-4">{{ $r['jatuh_tempo']?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td class="py-3 pr-4">
                            <x-filament::badge :color="$badge[0]">{{ $badge[1] }}</x-filament::badge>
                        </td>
                        <td class="py-3">
                            <div class="flex gap-1 flex-wrap">
                                <x-filament::badge color="primary">Oli Mesin</x-filament::badge>
                                @if ($r['butuh_gardan'])
                                    <x-filament::badge color="info">+ Oli Gardan</x-filament::badge>
                                @else
                                    <span class="text-xs text-gray-400 self-center">gardan {{ $r['urutan_ke'] }}/3</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-gray-500">Semua motor aman 🎉</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>