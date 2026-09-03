<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'Green Rental — Sewa Motor Murah & Mudah</title>
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
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="D'Green Rental" class="w-[52px] h-[52px] rounded-full">
                <div>
                    <p class="font-display font-semibold text-lg leading-tight">D'Green Rental</p>
                    <p class="text-xs text-[#D4B24C] tracking-wide">Sewa Motor Murah & Mudah</p>
                </div>
            </div>
            <a href="{{ route('sewa.create') }}"
               class="hidden sm:inline-block bg-[#D4B24C] text-[#1F4430] font-semibold px-5 py-2 rounded-full hover:bg-[#e0c264] transition">
                Sewa Sekarang
            </a>
        </div>
    </header>

    {{-- ===== HERO ===== --}}
    <section class="bg-[#1F4430] text-[#F5EFE1] relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 py-16 md:py-24 grid md:grid-cols-2 gap-10 items-center relative z-10">
            <div>
                <p class="text-[#D4B24C] text-sm tracking-[0.2em] uppercase mb-3">Rental Motor Harian dan Bulanan</p>
                <h1 class="font-display font-semibold text-4xl md:text-5xl leading-tight mb-5">
                    Rental Motor<br>Murah dan Mudah
                </h1>
                <p class="text-[#D8CFB8] mb-8 max-w-md">
                    Armada terawat, proses cepat tanpa ribet serta melayani jasa antar jemput unit. Cek ketersediaan motor
                    secara langsung, lalu ajukan sewa hanya dalam hitungan menit.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="#armada"
                       class="bg-[#D4B24C] text-[#1F4430] font-semibold px-6 py-3 rounded-full hover:bg-[#e0c264] transition">
                        Lihat Armada
                    </a>
                    <a href="{{ route('sewa.create') }}"
                       class="border border-[#D4B24C] text-[#D4B24C] font-semibold px-6 py-3 rounded-full hover:bg-[#D4B24C]/10 transition">
                        Ajukan Sewa
                    </a>
                </div>
            </div>

                        {{-- signature: leaf-vein motif --}}
            <div class="flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="D'Green Rental" class="w-64 h-64 md:w-72 md:h-72 rounded-full">
            </div>
        </div>
    </section>

    {{-- ===== STATUS RINGKAS ===== --}}
{{-- ===== STATUS RINGKAS ===== --}}
    <section class="max-w-6xl mx-auto px-6 pt-6 mb-4">
        <div class="flex flex-wrap gap-6 text-sm text-[#4A5A50]">
            <p><span class="inline-block w-2.5 h-2.5 rounded-full bg-[#3F7D4E] mr-2"></span>
                <span class="font-semibold text-[#16231C]">{{ $tersedia }}</span> motor tersedia</p>
            <p><span class="inline-block w-2.5 h-2.5 rounded-full bg-[#B6553C] mr-2"></span>
                <span class="font-semibold text-[#16231C]">{{ $total - $tersedia }}</span> sedang tidak tersedia</p>
        </div>
    </section>

    {{-- ===== ARMADA MOTOR ===== --}}
    <section id="armada" class="max-w-6xl mx-auto px-6 py-10">
        <h2 class="font-display font-semibold text-2xl md:text-3xl mb-2">Armada Kami</h2>
        <p class="text-[#4A5A50] mb-8">Status diperbarui langsung dari sistem — apa yang kamu lihat, itu yang tersedia.</p>

        @if ($motors->isEmpty())
            <p class="text-[#4A5A50]">Belum ada data motor.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($motors as $motor)
                    @php $isTersedia = $motor->status === 'Tersedia'; @endphp
                    <div class="bg-white rounded-2xl border border-[#E4DCC8] overflow-hidden hover:shadow-lg transition">
                            <div class="bg-[#1F4430] h-48 flex items-center justify-center relative overflow-hidden">
                                @if ($motor->foto)
                                    <img src="{{ asset('storage/' . $motor->foto) }}"
                                        alt="{{ $motor->motor }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <svg viewBox="0 0 64 40" class="w-20 h-20 text-[#D4B24C]" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="14" cy="32" r="6"/>
                                        <circle cx="50" cy="32" r="6"/>
                                        <path d="M14 32 L26 18 H38 L44 32 M26 18 L22 10 H16 M38 18 L44 12 H50" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @endif
                                <span class="absolute top-3 right-3 flex items-center gap-1.5 bg-white/95 rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $isTersedia ? 'text-[#3F7D4E]' : 'text-[#B6553C]' }}">
                                    <span class="w-2 h-2 rounded-full {{ $isTersedia ? 'bg-[#3F7D4E]' : 'bg-[#B6553C]' }}"></span>
                                    {{ $motor->status }}
                                </span>
                            </div>
                        <div class="p-5">
                            <h3 class="font-display font-semibold text-lg">{{ $motor->motor }}</h3>
                            <div class="flex items-center justify-end">
                                @if ($isTersedia)
                                    <a href="{{ route('sewa.create', ['motor_id' => $motor->id]) }}"
                                    class="text-sm font-semibold text-[#1F4430] border border-[#1F4430] rounded-full px-4 py-1.5 hover:bg-[#1F4430] hover:text-white transition">
                                        Sewa
                                    </a>
                                @else
                                    <span class="text-sm text-[#B6A88A]">Tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

        {{-- ===== LOKASI ===== --}}
    <section id="lokasi" class="max-w-6xl mx-auto px-6 py-10">
        <h2 class="font-display font-semibold text-2xl md:text-3xl mb-2">Lokasi Kami</h2>
        <p class="text-[#4A5A50] mb-8">Datang langsung ke lokasi kami untuk melihat armada atau melakukan pengambilan unit.</p>

        <div class="grid md:grid-cols-2 gap-6 items-stretch">
            <div class="rounded-2xl overflow-hidden border border-[#E4DCC8] h-72 md:h-full min-h-[280px]">
                <iframe
                    src="https://www.google.com/maps?q=Gg.+Tj.+2,+Juwangen,+Purwomartani,+Kalasan,+Sleman,+Yogyakarta+55571&output=embed"
                    class="w-full h-full border-0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <div class="bg-white rounded-2xl border border-[#E4DCC8] p-6 flex flex-col justify-center gap-4">
                <div class="flex items-start gap-3">
                    <svg viewBox="0 0 24 24" class="w-6 h-6 text-[#3F7D4E] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 21s-7-7.2-7-12a7 7 0 1 1 14 0c0 4.8-7 12-7 12z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                    <p class="text-[#4A5A50] text-sm leading-relaxed">
                        Gg. Tj. 2, Juwangen, Purwomartani, Kec. Kalasan, Sleman, Cupuwatu I,
                        Purwomartani, Kec. Kalasan, Yogyakarta, Daerah Istimewa Yogyakarta 55571
                    </p>
                </div>

                <a href="https://maps.app.goo.gl/3xRcgWwW99WtqvQG7?g_st=ic" target="_blank" rel="noopener"
                   class="inline-flex items-center justify-center gap-2 bg-[#1F4430] text-white font-semibold px-5 py-3 rounded-full hover:bg-[#16321f] transition w-fit">
                    Buka di Google Maps
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-[#1F4430] text-[#D8CFB8] mt-10">
    <div class="max-w-6xl mx-auto px-6 py-10 text-sm flex flex-col md:flex-row justify-between items-center gap-4">
        <p>&copy; {{ date('Y') }} D'Green Rental. Sewa Motor Murah & Mudah.</p>

        <div class="flex items-center gap-4">
            <a href="https://wa.me/628156298698" target="_blank" rel="noopener"
               class="flex items-center gap-2 hover:text-[#D4B24C] transition">
                <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12.004 2C6.486 2 2 6.486 2 12.004c0 1.99.583 3.86 1.6 5.43L2 22l4.72-1.57a9.94 9.94 0 0 0 5.284 1.518h.004c5.518 0 10.004-4.486 10.004-10.004S17.522 2 12.004 2zm0 18.19a8.17 8.17 0 0 1-4.166-1.144l-.299-.178-3.03 1.007 1.026-2.955-.194-.303a8.14 8.14 0 0 1-1.257-4.363c0-4.514 3.674-8.188 8.192-8.188 4.517 0 8.19 3.674 8.19 8.19 0 4.517-3.673 8.19-8.19 8.19z"/>
                </svg>
                <span>WhatsApp</span>
            </a>

            <a href="https://instagram.com/dgreenrental" target="_blank" rel="noopener"
               class="flex items-center gap-2 hover:text-[#D4B24C] transition">
                <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="18" height="18" rx="5"/>
                    <circle cx="12" cy="12" r="4"/>
                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                </svg>
                <span>Instagram</span>
            </a>
        </div>
    </div>
</footer>

</body>
</html>