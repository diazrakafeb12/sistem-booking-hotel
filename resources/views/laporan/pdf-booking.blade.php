<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d2137; padding-bottom: 10px; }
        .header h2 { color: #0d2137; margin: 0; font-size: 16px; }
        .header p  { margin: 4px 0; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th { background: #0d2137; color: #fff; padding: 8px 6px; text-align: left; font-size: 10px; }
        tbody td { padding: 7px 6px; border-bottom: 1px solid #eee; }
        tbody tr:nth-child(even) { background: #f8faff; }
        .badge-lunas   { color: #155724; background: #d4edda; padding: 2px 6px; border-radius: 4px; }
        .badge-dp      { color: #856404; background: #fff3cd; padding: 2px 6px; border-radius: 4px; }
        .badge-belum   { color: #721c24; background: #f8d7da; padding: 2px 6px; border-radius: 4px; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #888; }
        .total-row { background: #0d2137 !important; color: #fff; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>🏨 HOTEL BOOKING SYSTEM</h2>
        <p>Laporan Data Booking</p>
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Nama Tamu</th>
                <th>Kamar</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Malam</th>
                <th>Total Biaya</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $i => $b)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $b->kode_booking }}</strong></td>
                <td>{{ $b->tamu->nama_lengkap ?? '-' }}</td>
                <td>{{ $b->kamar->nomor_kamar ?? '-' }} ({{ $b->kamar->tipeKamar->nama_tipe ?? '-' }})</td>
                <td>{{ \Carbon\Carbon::parse($b->tgl_checkin)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tgl_checkout)->format('d/m/Y') }}</td>
                <td style="text-align:center">{{ $b->jumlah_malam }}</td>
                <td>Rp {{ number_format($b->total_biaya, 0, ',', '.') }}</td>
                <td>{{ ucfirst($b->status) }}</td>
                <td>
                    @if($b->pembayaran)
                        <span class="badge-{{ $b->pembayaran->status }}">
                            {{ ucfirst($b->pembayaran->status) }}
                        </span>
                    @else
                        <span class="badge-belum">Belum</span>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="7" style="text-align:right; padding: 8px 6px">TOTAL PENDAPATAN</td>
                <td colspan="3" style="padding: 8px 6px">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Hotel Booking System — Laporan dibuat otomatis oleh sistem
    </div>
</body>
</html>