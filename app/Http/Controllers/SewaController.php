<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Transaksi;
use App\Http\Requests\StoreSewaRequest;


class SewaController extends Controller
{
    public function create()
    {
        $motors = Motor::where('status', 'Tersedia')->get();
        $selectedMotorId = request('motor_id'); // ambil dari query string ?motor_id=3

        return view('sewa', compact('motors', 'selectedMotorId'));
    }

    public function store(StoreSewaRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'Dibooking'; // paksa status default, jangan dari input user

        Transaksi::create($data);

        return redirect()
            ->route('sewa.create')
            ->with('success', 'Pengajuan sewa berhasil dikirim! Kami akan menghubungi Anda via WhatsApp.');
    }
}