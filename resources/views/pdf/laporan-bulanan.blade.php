<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 18px; margin: 0; }
        h2 { font-size: 13px; margin: 18px 0 6px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; }
        th { background: #f3f3f3; text-align: left; }
        .right { text-align: right; }
        .total td { font-weight: bold; background: #fafafa; }
        .plus { color: #15803d; }
        .minus { color: #b91c1c; }
    </style>
</head>
<body>
    <h1>D'Green Rental</h1>
    <div class="muted">Laporan Bulanan | {{ $d['awal']->translatedFormat('F Y') }}</div>

    <h2>Ringkasan</h2>
    <table>
        <tr><td>Pemasukan (harga sewa)</td><td class="right">Rp {{ number_format($d['hargaSewa'], 0, ',', '.') }}</td></tr>
        <tr><td>Pemasukan (jasa antar)</td><td class="right">Rp {{ number_format($d['jasaAntar'], 0, ',', '.') }}</td></tr>
        <tr><td>Pemasukan (sewa helm)</td><td class="right">Rp {{ number_format($d['biayaHelm'], 0, ',', '.') }}</td></tr>
        <tr class="total"><td>Total Pemasukan ({{ $d['jumlahTransaksi'] }} transaksi)</td><td class="right plus">Rp {{ number_format($d['pemasukan'], 0, ',', '.') }}</td></tr>
        <tr class="total"><td>Total Pengeluaran Servis ({{ $d['jumlahServis'] }} servis)</td><td class="right minus">Rp {{ number_format($d['pengeluaran'], 0, ',', '.') }}</td></tr>
        <tr class="total">
            <td>Selisih (Laba Bersih)</td>
            <td class="right {{ $d['selisih'] >= 0 ? 'plus' : 'minus' }}">
                {{ $d['selisih'] < 0 ? '-' : '' }}Rp {{ number_format(abs($d['selisih']), 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <h2>Detail Pemasukan</h2>
    <table>
        <tr><th>Tgl Sewa</th><th>Customer</th><th>Motor</th><th class="right">Total</th></tr>
        @forelse ($d['transaksi'] as $t)
            <tr>
                <td>{{ $t->tanggal_sewa->format('d/m/Y') }}</td>
                <td>{{ $t->nama_customer }}</td>
                <td>{{ $t->motor?->motor }}</td>
                <td class="right">{{ number_format($t->total_bayar, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Tidak ada transaksi.</td></tr>
        @endforelse
    </table>

    <h2>Detail Pengeluaran Servis</h2>
    <table>
        <tr><th>Tgl Servis</th><th>Motor</th><th class="right">Biaya</th></tr>
        @forelse ($d['servis'] as $s)
            <tr>
                <td>{{ \Carbon\Carbon::parse($s->tanggal_service)->format('d/m/Y') }}</td>
                <td>{{ $s->motor?->motor }}</td>
                <td class="right">{{ number_format($s->biaya_service ?? 0, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="muted">Tidak ada servis.</td></tr>
        @endforelse
    </table>

    <p class="muted" style="margin-top:16px;">Dicetak {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
</body>
</html>