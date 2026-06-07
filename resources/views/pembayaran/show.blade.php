<x-app-layout>
    @section('title', 'Invoice Pembayaran')

    <div style="max-width:700px">
        <div class="card">
            <div class="card-header">
                <h3>🧾 Invoice Pembayaran</h3>
                <div style="display:flex; gap:8px">
                    <button onclick="window.print()" class="btn btn-primary btn-sm">🖨️ Print</button>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
                </div>
            </div>
            <div class="card-body" id="invoice">

                {{-- Header Invoice --}}
                <div style="text-align:center; margin-bottom:24px; padding-bottom:16px; border-bottom:2px solid #0d2137">
                    <div style="font-size:28px; margin-bottom:4px">🏨</div>
                    <div style="font-size:20px; font-weight:800; color:#0d2137">HOTEL BOOKING SYSTEM</div>
                    <div style="font-size:13px; color:#888">Jl. Contoh No. 123, Kota Anda</div>
                    <div style="margin-top:10px; font-size:16px; font-weight:700; color:#2e86de">
                        INVOICE PEMBAYARAN
                    </div>
                </div>

                {{-- Info Invoice --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:20px; font-size:13px">
                    <div>
                        <span style="color:#888">No. Invoice:</span>
                        <strong>#INV-{{ str_pad($pembayaran->id_pembayaran, 5, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div>
                        <span style="color:#888">Tgl Bayar:</span>
                        <strong>{{ \Carbon\Carbon::parse($pembayaran->tgl_bayar)->format('d M Y, H:i') }}</strong>
                    </div>
                    <div>
                        <span style="color:#888">Kode Booking:</span>
                        <strong>{{ $pembayaran->booking->kode_booking }}</strong>
                    </div>
                    <div>
                        <span style="color:#888">Metode:</span>
                        <strong>{{ strtoupper($pembayaran->metode) }}</strong>
                    </div>
                </div>

                {{-- Detail Tamu & Kamar --}}
                <table style="margin-bottom:20px; font-size:13px">
                    <tr style="background:transparent; border:none">
                        <td style="padding:6px 0; color:#888; border:none; width:140px">Nama Tamu</td>
                        <td style="padding:6px 0; border:none">: <strong>{{ $pembayaran->booking->tamu->nama_lengkap }}</strong></td>
                    </tr>
                    <tr style="background:transparent; border:none">
                        <td style="padding:6px 0; color:#888; border:none">No. Kamar</td>
                        <td style="padding:6px 0; border:none">: <strong>{{ $pembayaran->booking->kamar->nomor_kamar }}</strong> ({{ $pembayaran->booking->kamar->tipeKamar->nama_tipe ?? '-' }})</td>
                    </tr>
                    <tr style="background:transparent; border:none">
                        <td style="padding:6px 0; color:#888; border:none">Check-In</td>
                        <td style="padding:6px 0; border:none">: <strong>{{ \Carbon\Carbon::parse($pembayaran->booking->tgl_checkin)->format('d M Y') }}</strong></td>
                    </tr>
                    <tr style="background:transparent; border:none">
                        <td style="padding:6px 0; color:#888; border:none">Check-Out</td>
                        <td style="padding:6px 0; border:none">: <strong>{{ \Carbon\Carbon::parse($pembayaran->booking->tgl_checkout)->format('d M Y') }}</strong></td>
                    </tr>
                    <tr style="background:transparent; border:none">
                        <td style="padding:6px 0; color:#888; border:none">Jumlah Malam</td>
                        <td style="padding:6px 0; border:none">: <strong>{{ $pembayaran->booking->jumlah_malam }} malam</strong></td>
                    </tr>
                </table>

                {{-- Rincian Biaya --}}
                <table style="margin-bottom:20px">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th style="text-align:right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Sewa Kamar {{ $pembayaran->booking->kamar->nomor_kamar }}
                                ({{ $pembayaran->booking->jumlah_malam }} malam ×
                                Rp {{ number_format($pembayaran->booking->kamar->tipeKamar->harga_per_malam ?? 0, 0, ',', '.') }})
                            </td>
                            <td style="text-align:right; font-weight:700">
                                Rp {{ number_format($pembayaran->booking->total_biaya, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Total --}}
                <div style="background:#0d2137; border-radius:8px; padding:16px; display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
                    <div style="color:#b8d4e8; font-size:14px; font-weight:600">TOTAL PEMBAYARAN</div>
                    <div style="color:#fff; font-size:24px; font-weight:800">
                        Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                    </div>
                </div>

                {{-- Status --}}
                <div style="text-align:center; margin-bottom:20px">
                    @if($pembayaran->status == 'lunas')
                        <span style="background:#d4edda; color:#155724; padding:8px 24px; border-radius:20px; font-weight:700; font-size:14px">✅ LUNAS</span>
                    @elseif($pembayaran->status == 'dp')
                        <span style="background:#fff3cd; color:#856404; padding:8px 24px; border-radius:20px; font-weight:700; font-size:14px">⚠️ DP / UANG MUKA</span>
                    @else
                        <span style="background:#f8d7da; color:#721c24; padding:8px 24px; border-radius:20px; font-weight:700; font-size:14px">❌ BELUM LUNAS</span>
                    @endif
                </div>

                @if($pembayaran->keterangan)
                <div style="font-size:12px; color:#888; text-align:center; font-style:italic">
                    Keterangan: {{ $pembayaran->keterangan }}
                </div>
                @endif

                <div style="text-align:center; margin-top:20px; padding-top:16px; border-top:1px solid #eee; font-size:12px; color:#aaa">
                    Terima kasih telah menginap di hotel kami 🏨
                </div>

            </div>
        </div>
    </div>

    <style>
    @media print {
        .sidebar, .topbar, .btn, form { display: none !important; }
        .main-content { margin-left: 0 !important; }
        .card { box-shadow: none !important; }
    }
    </style>
</x-app-layout>