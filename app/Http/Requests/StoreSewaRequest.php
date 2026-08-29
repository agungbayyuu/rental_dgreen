<?php

namespace App\Http\Requests;

use App\Models\Transaksi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator; 

class StoreSewaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public form, semua boleh isi
    }

    public function rules(): array
    {
        return [
            'nama_customer'   => ['required', 'string', 'max:255'],
            'no_whatsapp'     => ['required', 'string', 'max:20'],
            'motor_id'        => ['required', 'exists:motors,id'],
            'tanggal_sewa'    => ['required', 'date', 'after_or_equal:today'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:tanggal_sewa'],
            'lokasi_antar'    => ['nullable', 'string', 'max:255'],
            'lokasi_ambil'    => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_customer.required'   => 'Nama lengkap wajib diisi.',
            'no_whatsapp.required'     => 'No. WhatsApp wajib diisi.',
            'motor_id.required'        => 'Silakan pilih motor.',
            'motor_id.exists'          => 'Motor yang dipilih tidak valid.',
            'tanggal_sewa.after_or_equal'    => 'Tanggal sewa tidak boleh sebelum hari ini.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal sewa.',
        ];
    }
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $motorId    = $this->input('motor_id');
            $tanggalSewa    = $this->input('tanggal_sewa');
            $tanggalKembali = $this->input('tanggal_kembali');

            if (! $motorId || ! $tanggalSewa || ! $tanggalKembali) {
                return; // biarkan rule required di atas yang handle
            }

            $bentrok = Transaksi::where('motor_id', $motorId)
                ->whereIn('status', ['Dibooking', 'Berjalan'])
                ->where('tanggal_sewa', '<', $tanggalKembali)
                ->where('tanggal_kembali', '>', $tanggalSewa)
                ->exists();

            if ($bentrok) {
                $validator->errors()->add(
                    'motor_id',
                    'Motor ini sudah dibooking pada rentang waktu tersebut. Silakan pilih motor lain atau ubah tanggal.'
                );
            }
        });
    }
}