<?php

namespace App\Http\Controllers;

use App\Models\Motor;

class HomeController extends Controller
{
    public function index()
    {
        $motors = Motor::orderByRaw("CASE WHEN status = 'Tersedia' THEN 0 ELSE 1 END")
            ->orderBy('motor')
            ->get();

        $tersedia = $motors->where('status', 'Tersedia')->count();
        $total    = $motors->count();

        return view('home', compact('motors', 'tersedia', 'total'));
    }
}