<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investasi Motor — D'Green Rental</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Work Sans', sans-serif; }
        .font-display { font-family: 'Fraunces', serif; }
    </style>
</head>
<body class="bg-[#F5EFE1] text-[#16231C]">

    {{-- ===== HEADER ===== --}}
    <header class="bg-[#1F4430] text-[#F5EFE1]">
        <div class="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="D'Green Rental" class="w-[52px] h-[52px] rounded-full">
                <div>
                    <p class="font-display font-semibold text-lg leading-tight">D'Green Rental</p>
                    <p class="text-xs text-[#D4B24C] tracking-wide">Sewa Motor Murah & Mudah</p>
                </div>
            </a>
            <a href="https://wa.me/628156298698" target="_blank" rel="noopener"
               class="hidden sm:inline-block bg-[#D4B24C] text-[#1F4430] font-semibold px-5 py-2 rounded-full hover:bg-[#e0c264] transition">
                Hubungi Kami
            </a>
        </div>
    </header>

    {{-- ===== HERO ===== --}}
    <section class="bg-[#1F4430] text-[#F5EFE1]">
        <div class="max-w-6xl mx-auto px-6 py-16 md:py-20">
            <p class="text-[#D4B24C] text-sm tracking-[0.2em] uppercase mb-3">Peluang Kerjasama</p>
            <h1 class="font-display font-semibold text-4xl md:text-5xl leading-tight mb-5 max-w-2xl">
                Investasi Motor D'Green Rental
            </h1>
            <p class="text-[#D8CFB8] max-w-xl leading-relaxed">
                Usaha rental motor di Yogyakarta masih sangat terbuka peluangnya. Kami membutuhkan sekitar
                20 unit armada tambahan untuk memenuhi permintaan konsumen yang terus bertambah — dan kami
                mengajak Anda untuk terlibat di dalamnya.
            </p>
        </div>
    </section>

    {{-- ===== KENAPA SEKARANG ===== --}}
    <section class="max-w-6xl mx-auto px-6 py-14">
        <div class="grid md:grid-cols-2 gap-10 items-start">
            <div>
                <h2 class="font-display font-semibold text-2xl md:text-3xl mb-4">Kenapa saat ini?</h2>
                <p class="text-[#4A5A50] leading-relaxed mb-4">
                    D'Green Rental menganut sistem mudah, pasti, dan tanggung jawab dalam setiap kerjasama.
                    Kami memahami bahwa dalam bisnis selalu ada risiko — namun kepercayaan itu kami jaga
                    lewat kejujuran dan komitmen sejak awal.
                </p>
                <p class="text-[#4A5A50] leading-relaxed">
                    Dari data sewa yang berjalan, permintaan rata-rata mencapai 5–10 unit setiap hari,
                    bahkan kami cukup sering menolak calon penyewa karena keterbatasan armada.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-[#E4DCC8] p-6">
                <p class="text-sm text-[#4A5A50] mb-1">Permintaan harian rata-rata</p>
                <p class="font-display font-semibold text-4xl text-[#1F4430] mb-4">5–10 unit</p>
                <div class="h-px bg-[#E4DCC8] mb-4"></div>
                <p class="text-sm text-[#4A5A50] mb-1">Kebutuhan armada tambahan</p>
                <p class="font-display font-semibold text-4xl text-[#1F4430]">20 unit</p>
            </div>
        </div>
    </section>

    {{-- ===== MODAL INVESTASI ===== --}}
    <section class="bg-white border-y border-[#E4DCC8]">
        <div class="max-w-6xl mx-auto px-6 py-14">
            <h2 class="font-display font-semibold text-2xl md:text-3xl mb-6">Modal investasi</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="border-l-2 border-[#D4B24C] pl-5">
                    <p class="text-[#4A5A50] leading-relaxed">
                        Modal disesuaikan dengan jenis kendaraan yang ingin diinvestasikan.
                    </p>
                </div>
                <div class="border-l-2 border-[#D4B24C] pl-5">
                    <p class="text-[#4A5A50] leading-relaxed">
                        Contoh: Honda Scoopy sekitar Rp13.000.000, atau Honda Vario Techno sekitar Rp16.000.000.
                    </p>
                </div>
                <div class="border-l-2 border-[#D4B24C] pl-5">
                    <p class="text-[#4A5A50] leading-relaxed">
                        Pembelian motor bisa atas nama D'Green Rental, atau tetap atas nama Anda sebagai pemilik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== KEUNGGULAN ===== --}}
    <section class="max-w-6xl mx-auto px-6 py-14">
        <h2 class="font-display font-semibold text-2xl md:text-3xl mb-8">Keunggulan berinvestasi bersama kami</h2>
        <div class="grid sm:grid-cols-2 gap-x-10 gap-y-6">
            <div class="flex gap-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#3F7D4E] mt-2.5 shrink-0"></span>
                <p class="text-[#16231C]">Sistem kerjasama mudah dan tidak bergantung pada performa penjualan kami.</p>
            </div>
            <div class="flex gap-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#3F7D4E] mt-2.5 shrink-0"></span>
                <p class="text-[#16231C]">Modal investasi mulai dari Rp15.000.000.</p>
            </div>
            <div class="flex gap-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#3F7D4E] mt-2.5 shrink-0"></span>
                <p class="text-[#16231C]">Estimasi keuntungan sekitar 100% dalam kontrak 2 tahun.</p>
            </div>
            <div class="flex gap-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#3F7D4E] mt-2.5 shrink-0"></span>
                <p class="text-[#16231C]">Biaya perawatan dan ganti oli sepenuhnya ditanggung D'Green Rental.</p>
            </div>
            <div class="flex gap-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#3F7D4E] mt-2.5 shrink-0"></span>
                <p class="text-[#16231C]">Risiko kehilangan atau pencurian motor menjadi tanggung jawab kami.</p>
            </div>
            <div class="flex gap-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#3F7D4E] mt-2.5 shrink-0"></span>
                <p class="text-[#16231C]">Perjanjian investor dan pengelola di atas materai.</p>
            </div>
        </div>
    </section>

    {{-- ===== SISTEM KERJASAMA ===== --}}
    <section class="bg-[#1F4430] text-[#F5EFE1]">
        <div class="max-w-6xl mx-auto px-6 py-14">
            <h2 class="font-display font-semibold text-2xl md:text-3xl mb-8">Sistem kerjasama</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <p class="font-display text-3xl text-[#D4B24C] mb-2">1–2</p>
                    <p class="text-[#D8CFB8] leading-relaxed">
                        Tahun masa kontrak sewa motor, dengan penghasilan bulanan yang pasti sesuai jenis motor.
                    </p>
                </div>
                <div>
                    <p class="font-display text-3xl text-[#D4B24C] mb-2">Baru</p>
                    <p class="text-[#D8CFB8] leading-relaxed">
                        Motor minimal dalam kondisi 1 tahun pemakaian, agar biaya perawatan tetap terkendali.
                    </p>
                </div>
                <div>
                    <p class="font-display text-3xl text-[#D4B24C] mb-2">Gratis</p>
                    <p class="text-[#D8CFB8] leading-relaxed">
                        Biaya perawatan dan servis bulanan ditanggung penuh oleh D'Green Rental.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== PERHITUNGAN INVESTASI ===== --}}
    <section class="max-w-6xl mx-auto px-6 py-14">
        <h2 class="font-display font-semibold text-2xl md:text-3xl mb-2">Perhitungan investasi</h2>
        <p class="text-[#4A5A50] mb-8">Simulasi komisi bulanan berdasarkan kontrak 24 bulan.</p>

        <div class="overflow-x-auto rounded-2xl border border-[#E4DCC8]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#1F4430] text-[#F5EFE1] text-sm">
                        <th class="px-5 py-4 font-semibold">Modal (harga motor)</th>
                        <th class="px-5 py-4 font-semibold">Komisi / bulan</th>
                        <th class="px-5 py-4 font-semibold">Masa kontrak</th>
                        <th class="px-5 py-4 font-semibold">Total 24 bulan</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-sm">
                    <tr class="border-t border-[#E4DCC8]">
                        <td class="px-5 py-4">Rp15.000.000 – Rp20.000.000</td>
                        <td class="px-5 py-4 font-semibold text-[#1F4430]">Rp500.000</td>
                        <td class="px-5 py-4 text-[#4A5A50]">2 tahun, dapat diperpanjang</td>
                        <td class="px-5 py-4 font-semibold text-[#1F4430]">Rp12.000.000</td>
                    </tr>
                    <tr class="border-t border-[#E4DCC8]">
                        <td class="px-5 py-4">Rp21.000.000 – Rp25.000.000</td>
                        <td class="px-5 py-4 font-semibold text-[#1F4430]">Rp600.000</td>
                        <td class="px-5 py-4 text-[#4A5A50]">2 tahun, dapat diperpanjang</td>
                        <td class="px-5 py-4 font-semibold text-[#1F4430]">Rp14.400.000</td>
                    </tr>
                    <tr class="border-t border-[#E4DCC8]">
                        <td class="px-5 py-4">Rp26.000.000 – Rp30.000.000</td>
                        <td class="px-5 py-4 font-semibold text-[#1F4430]">Rp700.000</td>
                        <td class="px-5 py-4 text-[#4A5A50]">2 tahun, dapat diperpanjang</td>
                        <td class="px-5 py-4 font-semibold text-[#1F4430]">Rp16.800.000</td>
                    </tr>
                    <tr class="border-t border-[#E4DCC8]">
                        <td class="px-5 py-4">Rp31.000.000 – Rp40.000.000</td>
                        <td class="px-5 py-4 font-semibold text-[#1F4430]">Rp800.000</td>
                        <td class="px-5 py-4 text-[#4A5A50]">2 tahun, dapat diperpanjang</td>
                        <td class="px-5 py-4 font-semibold text-[#1F4430]">Rp19.200.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-xs text-[#8A9086] mt-3">Angka bersifat estimasi dan dapat disesuaikan lebih lanjut sesuai kesepakatan.</p>
    </section>

    {{-- ===== CTA / KONTAK ===== --}}
    <section class="bg-white border-t border-[#E4DCC8]">
        <div class="max-w-6xl mx-auto px-6 py-16">
            <div class="bg-[#F5EFE1] rounded-2xl p-8 md:p-10 grid md:grid-cols-[1fr_auto] gap-8 items-center">
                <div>
                    <h2 class="font-display font-semibold text-2xl md:text-3xl mb-3">Tertarik berinvestasi?</h2>
                    <p class="text-[#4A5A50] leading-relaxed max-w-lg">
                        Kami memahami Anda membutuhkan data dan informasi yang jelas mengenai pengelola dan
                        penanggung jawab sebelum memutuskan. Hubungi kami, dan tim D'Green Rental akan
                        segera mengirimkan data lengkapnya.
                    </p>
                </div>
                <a href="https://wa.me/628156298698?text=Halo%2C%20saya%20tertarik%20investasi%20motor%20di%20D%27Green%20Rental"
                   target="_blank" rel="noopener"
                   class="inline-flex items-center justify-center gap-2 bg-[#1F4430] text-white font-semibold px-6 py-3.5 rounded-full hover:bg-[#16321f] transition whitespace-nowrap">
                    Hubungi via WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-[#1F4430] text-[#D8CFB8]">
        <div class="max-w-6xl mx-auto px-6 py-10 text-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} D'Green Rental. Sewa Motor Murah & Mudah.</p>
            <div class="flex items-center gap-4">
                <a href="https://wa.me/628156298698" target="_blank" rel="noopener" class="hover:text-[#D4B24C] transition">WhatsApp</a>
                <a href="https://instagram.com/dgreenrental" target="_blank" rel="noopener" class="hover:text-[#D4B24C] transition">Instagram</a>
            </div>
        </div>
    </footer>

</body>
</html>