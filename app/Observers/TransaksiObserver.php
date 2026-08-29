<?php

namespace App\Observers;

use App\Models\Transaksi;
use App\Models\Motor;

class TransaksiObserver
{
    public function created(Transaksi $transaksi): void
    {
        $this->syncMotorStatus($transaksi);
    }

    public function updated(Transaksi $transaksi): void
    {
        // Hanya proses kalau status atau motor_id berubah, biar tidak boros query
        if ($transaksi->isDirty('status') || $transaksi->isDirty('motor_id')) {
            $this->syncMotorStatus($transaksi);

            // Kalau motor diganti, motor lama juga perlu dicek ulang statusnya
            if ($transaksi->isDirty('motor_id')) {
                $motorLamaId = $transaksi->getOriginal('motor_id');
                $this->refreshMotorStatus($motorLamaId);
            }
        }
    }

    public function deleted(Transaksi $transaksi): void
    {
        $this->refreshMotorStatus($transaksi->motor_id);
    }

    protected function syncMotorStatus(Transaksi $transaksi): void
    {
        $motor = $transaksi->motor;

        if (! $motor) {
            return;
        }

        $motor->update([
            'status' => $transaksi->status === 'Berjalan'
                ? 'Disewa'
                : 'Tersedia',
        ]);
    }

    protected function refreshMotorStatus(?int $motorId): void
    {
        if (! $motorId) {
            return;
        }

        $motor = Motor::find($motorId);

        if (! $motor) {
            return;
        }

        // Cek apakah motor ini masih punya transaksi berstatus "Berjalan"
        $masihBerjalan = Transaksi::where('motor_id', $motorId)
            ->where('status', 'Berjalan')
            ->exists();

        $motor->update([
            'status' => $masihBerjalan ? 'Disewa' : 'Tersedia',
        ]);
    }
}