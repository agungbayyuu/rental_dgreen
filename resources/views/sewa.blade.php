<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Form Sewa Motor</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6">

        <div class="flex justify-center mb-2">
            <img src="{{ asset('images/logo.png') }}" alt="D'Green Rental" class="w-[150px] h-[150px] rounded-full">
        </div>

        <h1 class="text-2xl font-bold mb-6 text-center">Form Sewa Motor D'Green Rental</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-300 rounded">
            <p class="font-semibold text-yellow-800 mb-1">Perhatian:</p>
            <p class="text-sm text-yellow-800">
                Setiap pelanggaran hukum & penyalahgunaan kendaraan rental dengan alasan apapun
                (dipinjamkan, dijaminkan, digadaikan, dijual putus atau gagal bayar) akan kami
                proses secara HUKUM & akan kami share di sosmed.
            </p>
        </div>

        <form method="POST" action="{{ route('sewa.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama_customer" value="{{ old('nama_customer') }}"
                    class="w-full border rounded px-3 py-2 @error('nama_customer') border-red-500 @enderror">
                @error('nama_customer')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                    class="w-full border rounded px-3 py-2 @error('no_whatsapp') border-red-500 @enderror">
                @error('no_whatsapp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Pilih Motor <span class="text-red-500">*</span></label>
                <select name="motor_id" class="w-full border rounded px-3 py-2 @error('motor_id') border-red-500 @enderror">
                    <option value="">-- Pilih Motor --</option>
                    @foreach ($motors as $motor)
                        <option value="{{ $motor->id }}"
                            @selected(old('motor_id', $selectedMotorId) == $motor->id)>
                            {{ $motor->nomor_polisi }} - {{ $motor->motor }}
                        </option>
                    @endforeach
                </select>
                @error('motor_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Sewa <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="tanggal_sewa" value="{{ old('tanggal_sewa') }}"
                        class="w-full border rounded px-3 py-2 @error('tanggal_sewa') border-red-500 @enderror">
                    @error('tanggal_sewa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Kembali <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}"
                        class="w-full border rounded px-3 py-2 @error('tanggal_kembali') border-red-500 @enderror">
                    @error('tanggal_kembali')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Lokasi Antar</label>
                <input type="text" name="lokasi_antar" value="{{ old('lokasi_antar') }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Lokasi Ambil</label>
                <input type="text" name="lokasi_ambil" value="{{ old('lokasi_ambil') }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded font-medium">
                Kirim Pengajuan
            </button>
        </form>
    </div>
</body>
</html>