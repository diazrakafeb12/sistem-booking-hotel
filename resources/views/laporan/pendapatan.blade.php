<x-app-layout>
    @section('title', 'Laporan Pendapatan')

    {{-- Filter Bulan --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3>🔍 Filter Periode</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.pendapatan') }}">
                <div style="display:grid; grid-template-columns:1fr 1fr auto; gap:16px; align-items:end; max-width:500px">
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-control">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-control">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">🔍 Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px">
        <div style="background:linear-gradient(135deg,#0d2137,#2e86de); border-radius:12px; padding:24px; color:#fff; box-shadow:0 2px 12px rgba(0,0,0,0.1)">
            <div style="font-size:11px; color:#b8d4e8; font-weight:700; text-transform:uppercase">Total Pendapatan</div>
            <div style="font-size:26px; font-weight:800; margin-top:6px">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div style="font-size:12px; color:#b8d4e8; margin-top:4px">
                {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
            </div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border-left:4px solid #27ae60">
            <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase">Jumlah Transaksi</div>
            <div style="font-size:32px; font-weight:800; color:#0d2137">{{ $pembayaran->count() }}</div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border-left:4px solid #f39c12">
            <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase">Rata-rata per Transaksi</div>
            <div style="font-size:22px; font-weight:800; color:#0d2137">
                Rp {{ $pembayaran->count() > 0 ? number_format($totalPendapatan / $pembayaran->count(), 0, ',', '.') : 0 }}
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header">
            <h3>💰 Rincian Pendapatan</h3>
            <button onclick="window.print()" class="btn btn-primary btn-sm">🖨️ Print</button>
        </div>
        <div style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Bayar</th>
                        <th>Kode Booking</th>
                        <th>Tamu</th>
                        <th>Kamar</th>
                        <th>Malam</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->format('d/m/Y') }}</td>
                        <td><strong style="color:#2e86de">{{ $p->booking->kode_booking ?? '-' }}</strong></td>
                        <td>{{ $p->booking->tamu->nama_lengkap ?? '-' }}</td>
                        <td>{{ $p->booking->kamar->nomor_kamar ?? '-' }}</td>
                        <td style="text-align:center">{{ $p->booking->jumlah_malam ?? '-' }}</td>
                        <td style="color:#27ae60; font-weight:700">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:#aaa">
                            Tidak ada data pendapatan pada periode ini.
                        </td>
                    </tr>
                    @endforelse

                    @if($pembayaran->count() > 0)
                    <tr style="background:#f0f4f8; font-weight:700">
                        <td colspan="6" style="text-align:right; padding:14px 16px">TOTAL</td>
                        <td style="color:#27ae60; font-size:15px">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <style>
    @media print {
        .sidebar, .topbar, .card-header .btn, form { display: none !important; }
        .main-content { margin-left: 0 !important; }
    }
    </style>
</x-app-layout>