{{-- Laporan booking: filter tanggal, filter status, export print --}}

<x-app-layout>
    @section('title', 'Laporan Booking')

    {{-- Filter --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3>🔍 Filter Laporan</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.booking') }}">
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
                            <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="checkin"   {{ request('status') == 'checkin'   ? 'selected' : '' }}>Check-In</option>
                            <option value="checkout"  {{ request('status') == 'checkout'  ? 'selected' : '' }}>Check-Out</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:8px">
                        <button type="submit" class="btn btn-primary">🔍 Filter</button>
                        <a href="{{ route('laporan.booking') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px">
        <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border-left:4px solid #2e86de">
            <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase">Total Booking</div>
            <div style="font-size:32px; font-weight:800; color:#0d2137">{{ $totalBooking }}</div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border-left:4px solid #27ae60">
            <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase">Total Pendapatan</div>
            <div style="font-size:24px; font-weight:800; color:#0d2137">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border-left:4px solid #f39c12">
            <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase">Rata-rata per Booking</div>
            <div style="font-size:24px; font-weight:800; color:#0d2137">
                Rp {{ $totalBooking > 0 ? number_format($totalPendapatan / $totalBooking, 0, ',', '.') : 0 }}
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header">
            <h3>📋 Data Booking</h3>
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
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Malam</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $i => $b)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong style="color:#2e86de">{{ $b->kode_booking }}</strong></td>
                        <td>{{ $b->tamu->nama_lengkap ?? '-' }}</td>
                        <td>{{ $b->kamar->nomor_kamar ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($b->tgl_checkin)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($b->tgl_checkout)->format('d/m/Y') }}</td>
                        <td style="text-align:center">{{ $b->jumlah_malam }}</td>
                        <td style="color:#27ae60; font-weight:700">Rp {{ number_format($b->total_biaya, 0, ',', '.') }}</td>
                        <td>
                            @if($b->status == 'confirmed')
                                <span class="badge badge-info">Confirmed</span>
                            @elseif($b->status == 'checkin')
                                <span class="badge badge-success">Check-In</span>
                            @elseif($b->status == 'checkout')
                                <span class="badge badge-dark">Check-Out</span>
                            @elseif($b->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center; padding:40px; color:#aaa">
                            Tidak ada data booking.
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