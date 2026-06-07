<x-app-layout>
    @section('title', 'Laporan Pembayaran')

    {{-- Filter --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3>🔍 Filter Laporan</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.pembayaran') }}">
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:16px; align-items:end">
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Tanggal Dari</label>
                        <input type="date" name="tgl_dari" value="{{ request('tgl_dari') }}" class="form-control">
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Tanggal Sampai</label>
                        <input type="date" name="tgl_sampai" value="{{ request('tgl_sampai') }}" class="form-control">
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="lunas"       {{ request('status') == 'lunas'       ? 'selected' : '' }}>Lunas</option>
                            <option value="dp"          {{ request('status') == 'dp'          ? 'selected' : '' }}>DP</option>
                            <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:8px">
                        <button type="submit" class="btn btn-primary">🔍 Filter</button>
                        <a href="{{ route('laporan.pembayaran') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px">
        <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border-left:4px solid #27ae60">
            <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase">Total Lunas</div>
            <div style="font-size:22px; font-weight:800; color:#27ae60">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border-left:4px solid #f39c12">
            <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase">Total DP</div>
            <div style="font-size:22px; font-weight:800; color:#f39c12">Rp {{ number_format($totalDP, 0, ',', '.') }}</div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border-left:4px solid #e74c3c">
            <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase">Total Belum Lunas</div>
            <div style="font-size:22px; font-weight:800; color:#e74c3c">Rp {{ number_format($totalBelum, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header">
            <h3>💳 Data Pembayaran</h3>
            <button onclick="window.print()" class="btn btn-primary btn-sm">🖨️ Print</button>
        </div>
        <div style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Tamu</th>
                        <th>Kamar</th>
                        <th>Jumlah Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tgl Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong style="color:#2e86de">{{ $p->booking->kode_booking ?? '-' }}</strong></td>
                        <td>{{ $p->booking->tamu->nama_lengkap ?? '-' }}</td>
                        <td>{{ $p->booking->kamar->nomor_kamar ?? '-' }}</td>
                        <td style="color:#27ae60; font-weight:700">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                        <td>{{ strtoupper($p->metode) }}</td>
                        <td>
                            @if($p->status == 'lunas')
                                <span class="badge badge-success">✅ Lunas</span>
                            @elseif($p->status == 'dp')
                                <span class="badge badge-warning">⚠️ DP</span>
                            @else
                                <span class="badge badge-danger">❌ Belum Lunas</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:#aaa">
                            Tidak ada data pembayaran.
                        </td>
                    </tr>
                    @endforelse
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