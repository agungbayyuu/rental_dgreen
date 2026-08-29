<div>
    <form wire:submit="submit">
        {{ $this->form }}

        <button type="submit" class="mt-4 px-4 py-2 bg-orange-500 text-white rounded">
            Kirim Pengajuan
        </button>
    </form>

    <x-filament-actions::modals />
</div>