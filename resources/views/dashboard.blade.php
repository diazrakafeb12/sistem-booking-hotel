{{-- Dashboard admin/ceo: stats cards, grafik pendapatan, donut chart --}}

<x-app-layout>
    @section('title', 'Dashboard')

    {{-- Greeting --}}
    <div style="background:linear-gradient(135deg,#0a1628 0%,#1a3a5c 60%,#2e86de 100%); border-radius:14px; padding:24px 28px; margin-bottom:24px; display:flex; align-items:center; justify-content:space-between; color:#fff; position:relative; overflow:hidden">
        <div style="position:absolute; right:-30px; top:-30px; width:180px; height:180px; background:rgba(255,255,255,0.04); border-radius:50%"></div>
        <div style="position:absolute; right:80px; bottom:-50px; width:120px; height:120px; background:rgba(255,255,255,0.03); border-radius:50%"></div>
        <div>
            <div style="font-size:11px; color:#7fb3d3; font-weight:600; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
            <div style="font-size:22px; font-weight:800; margin-bottom:4px">
                Selamat Datang, {{ Auth::user()->name }}! 👋
            </div>
            <div style="font-size:13px; color:#b8d4e8">
                Role: <strong style="color:#fff">{{ ucfirst(Auth::user()->role) }}</strong> —
                Berikut ringkasan sistem hotel Anda.
            </div>
        </div>
        <div style="text-align:right; flex-shrink:0">
            <div style="font-size:11px; color:#7fb3d3; margin-bottom:4px">Total Pendapatan</div>
            <div style="font-size:32px; font-weight:800; line-height:1">
                Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}
            </div>
            <div style="font-size:11px; color:#7fb3d3; margin-top:2px">akumulasi semua waktu</div>
        </div>
    </div>

    {{-- 5 Stats Cards --}}
    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:24px">

        <div style="background:#fff; border-radius:12px; padding:18px 16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #2e86de; text-align:center">
            <div style="font-size:28px; margin-bottom:6px">🛏️</div>
            <div style="font-size:28px; font-weight:800; color:#0d2137; line-height:1">{{ $data['total_kamar'] }}</div>
            <div style="font-size:10px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-top:6px">Jumlah Kamar</div>
            <div style="margin-top:8px; font-size:11px; color:#555">
                <span style="color:#27ae60">{{ $data['kamar_tersedia'] }} tersedia</span> ·
                <span style="color:#e74c3c">{{ $data['kamar_terisi'] }} terisi</span>
            </div>
        </div>

        <div style="background:#fff; border-radius:12px; padding:18px 16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #27ae60; text-align:center">
            <div style="font-size:28px; margin-bottom:6px">💰</div>
            <div style="font-size:18px; font-weight:800; color:#0d2137; line-height:1.2">
                Rp {{ number_format($data['pendapatan_bulan'], 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-top:6px">Pendapatan Bulan Ini</div>
            <div style="margin-top:8px; font-size:11px; color:#27ae60">
                {{ now()->translatedFormat('F Y') }}
            </div>
        </div>

        <div style="background:#fff; border-radius:12px; padding:18px 16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #f39c12; text-align:center">
            <div style="font-size:28px; margin-bottom:6px">⏳</div>
            <div style="font-size:28px; font-weight:800; color:#0d2137; line-height:1">{{ $data['booking_pending'] }}</div>
            <div style="font-size:10px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-top:6px">Menunggu Konfirmasi</div>
            <div style="margin-top:8px; font-size:11px; color:#f39c12">booking belum check-in</div>
        </div>

        <div style="background:#fff; border-radius:12px; padding:18px 16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #e74c3c; text-align:center">
            <div style="font-size:28px; margin-bottom:6px">🔧</div>
            <div style="font-size:28px; font-weight:800; color:#0d2137; line-height:1">{{ $data['kamar_maintenance'] }}</div>
            <div style="font-size:10px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-top:6px">Maintenance</div>
            <div style="margin-top:8px; font-size:11px; color:#e74c3c">kamar sedang diperbaiki</div>
        </div>

        <div style="background:#fff; border-radius:12px; padding:18px 16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #8e44ad; text-align:center">
            <div style="font-size:28px; margin-bottom:6px">💎</div>
            <div style="font-size:18px; font-weight:800; color:#0d2137; line-height:1.2">
                Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-top:6px">Total Pendapatan</div>
            <div style="margin-top:8px; font-size:11px; color:#8e44ad">semua waktu</div>
        </div>

    </div>

    {{-- Grafik + Tabel --}}
    <div style="display:grid; grid-template-columns:3fr 2fr; gap:20px; margin-bottom:20px">

        {{-- Grafik Pendapatan --}}
        <div class="card">
            <div class="card-header">
                <h3>📈 Pendapatan 6 Bulan Terakhir</h3>
            </div>
            <div class="card-body">
                <canvas id="grafikPendapatan" height="200"></canvas>
            </div>
        </div>

        {{-- Grafik Donut Status Booking --}}
        <div class="card">
            <div class="card-header">
                <h3>📊 Status Booking</h3>
            </div>
            <div class="card-body" style="display:flex; flex-direction:column; align-items:center">
                <canvas id="grafikStatus" style="max-width:200px; max-height:200px"></canvas>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:16px; width:100%">
                    <div style="display:flex; align-items:center; gap:6px; font-size:12px">
                        <div style="width:12px; height:12px; background:#2e86de; border-radius:3px"></div>
                        <span>Confirmed: <strong>{{ $data['grafik_status']['confirmed'] }}</strong></span>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px; font-size:12px">
                        <div style="width:12px; height:12px; background:#27ae60; border-radius:3px"></div>
                        <span>Check-In: <strong>{{ $data['grafik_status']['checkin'] }}</strong></span>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px; font-size:12px">
                        <div style="width:12px; height:12px; background:#95a5a6; border-radius:3px"></div>
                        <span>Check-Out: <strong>{{ $data['grafik_status']['checkout'] }}</strong></span>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px; font-size:12px">
                        <div style="width:12px; height:12px; background:#e74c3c; border-radius:3px"></div>
                        <span>Cancelled: <strong>{{ $data['grafik_status']['cancelled'] }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Booking Terbaru + Status Kamar --}}
    <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px">

        <div class="card">
            <div class="card-header">
                <h3>📋 Booking Terbaru</h3>
                <a href="{{ route('tamu.index') }}" class="btn btn-primary btn-sm">Lihat Semua</a>
            </div>
            <div style="padding:0">
                <table style="font-size:12px">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tamu</th>
                            <th>Kamar</th>
                            <th>Check-In</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['booking_terbaru'] as $b)
                        <tr>
                            <td><strong style="color:#2e86de; font-size:11px">{{ $b->kode_booking }}</strong></td>
                            <td>{{ $b->tamu->nama_lengkap ?? '-' }}</td>
                            <td>{{ $b->kamar->nomor_kamar ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($b->tgl_checkin)->format('d/m/Y') }}</td>
                            <td>
                                @if($b->status == 'confirmed')
                                    <span class="badge badge-info">Confirmed</span>
                                @elseif($b->status == 'checkin')
                                    <span class="badge badge-success">Check-In</span>
                                @elseif($b->status == 'checkout')
                                    <span class="badge badge-dark">Check-Out</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px; color:#aaa">
                                Belum ada data booking.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Status Kamar --}}
        <div class="card">
            <div class="card-header"><h3>🛏️ Status Kamar</h3></div>
            <div class="card-body">
                @php $total = $data['total_kamar']; @endphp
                <div style="display:flex; gap:4px; border-radius:8px; overflow:hidden; height:12px; margin-bottom:16px">
                    <div style="background:#27ae60; width:{{ $total > 0 ? ($data['kamar_tersedia']/$total*100) : 0 }}%"></div>
                    <div style="background:#e74c3c; width:{{ $total > 0 ? ($data['kamar_terisi']/$total*100) : 0 }}%"></div>
                    <div style="background:#f39c12; width:{{ $total > 0 ? ($data['kamar_maintenance']/$total*100) : 0 }}%"></div>
                </div>
                @foreach([['Tersedia', $data['kamar_tersedia'], '#27ae60'], ['Terisi', $data['kamar_terisi'], '#e74c3c'], ['Maintenance', $data['kamar_maintenance'], '#f39c12']] as [$label, $val, $color])
                <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #f5f7fa">
                    <div style="display:flex; align-items:center; gap:8px">
                        <div style="width:10px; height:10px; background:{{ $color }}; border-radius:3px"></div>
                        <span style="font-size:12px; color:#555">{{ $label }}</span>
                    </div>
                    <div>
                        <strong style="color:{{ $color }}; font-size:14px">{{ $val }}</strong>
                        <span style="font-size:11px; color:#aaa"> ({{ $total > 0 ? round($val/$total*100) : 0 }}%)</span>
                    </div>
                </div>
                @endforeach
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:10px">
                    <span style="font-size:12px; font-weight:600; color:#0d2137">Total</span>
                    <strong style="font-size:16px; color:#0d2137">{{ $total }}</strong>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Grafik Pendapatan Bar
    const ctx1 = document.getElementById('grafikPendapatan').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($data['grafik_pendapatan']->pluck('bulan')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($data['grafik_pendapatan']->pluck('total')) !!},
                backgroundColor: [
                    'rgba(46,134,222,0.7)',
                    'rgba(46,134,222,0.7)',
                    'rgba(46,134,222,0.7)',
                    'rgba(46,134,222,0.7)',
                    'rgba(46,134,222,0.7)',
                    'rgba(46,134,222,0.9)',
                ],
                borderColor: '#2e86de',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + (val/1000000).toFixed(1) + 'jt',
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });

    // Grafik Donut Status Booking
    const ctx2 = document.getElementById('grafikStatus').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Confirmed', 'Check-In', 'Check-Out', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $data['grafik_status']['confirmed'] }},
                    {{ $data['grafik_status']['checkin'] }},
                    {{ $data['grafik_status']['checkout'] }},
                    {{ $data['grafik_status']['cancelled'] }},
                ],
                backgroundColor: ['#2e86de', '#27ae60', '#95a5a6', '#e74c3c'],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.label + ': ' + ctx.raw + ' booking'
                    }
                }
            }
        }
    });
    </script>
</x-app-layout>