<x-app-layout>
    @section('title', 'Dashboard')

    {{-- Greeting Customer --}}
    <div style="background:linear-gradient(135deg,#0a1628 0%,#1a3a5c 60%,#2e86de 100%); border-radius:14px; padding:28px 32px; margin-bottom:24px; color:#fff; position:relative; overflow:hidden">
        <div style="position:absolute; right:-20px; top:-20px; width:160px; height:160px; background:rgba(255,255,255,0.04); border-radius:50%"></div>
        <div style="font-size:11px; color:#7fb3d3; font-weight:600; letter-spacing:2px; text-transform:uppercase; margin-bottom:8px">
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
        <div style="font-size:24px; font-weight:800; margin-bottom:6px">
            Halo, {{ Auth::user()->name }}! 👋
        </div>
        <div style="font-size:13px; color:#b8d4e8; margin-bottom:20px">
            Selamat datang di Hotel Booking System. Temukan dan pesan kamar terbaik untuk Anda.
        </div>
        <a href="{{ route('booking.create') }}"
           style="background:#fff; color:#0d2137; padding:11px 24px; border-radius:10px; font-weight:700; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:8px">
            📋 Buat Booking Sekarang →
        </a>
    </div>

    {{-- Stats --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px">
        <div style="background:#fff; border-radius:12px; padding:20px 22px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #2e86de; display:flex; align-items:center; gap:14px">
            <div style="width:48px; height:48px; background:#eef4fb; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0">📋</div>
            <div>
                <div style="font-size:10px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px">Total Booking</div>
                <div style="font-size:32px; font-weight:800; color:#0d2137; line-height:1.1">{{ $data['total_booking'] }}</div>
                <div style="font-size:11px; color:#2e86de; margin-top:2px">semua reservasi Anda</div>
            </div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:20px 22px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #27ae60; display:flex; align-items:center; gap:14px">
            <div style="width:48px; height:48px; background:#e8f8f1; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0">🏨</div>
            <div>
                <div style="font-size:10px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px">Booking Aktif</div>
                <div style="font-size:32px; font-weight:800; color:#0d2137; line-height:1.1">{{ $data['booking_aktif'] }}</div>
                <div style="font-size:11px; color:#27ae60; margin-top:2px">confirmed & check-in</div>
            </div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:20px 22px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #f39c12; display:flex; align-items:center; gap:14px">
            <div style="width:48px; height:48px; background:#fef9e7; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0">💳</div>
            <div>
                <div style="font-size:10px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px">Total Pengeluaran</div>
                <div style="font-size:18px; font-weight:800; color:#0d2137; line-height:1.2">
                    Rp {{ number_format($data['total_bayar'], 0, ',', '.') }}
                </div>
                <div style="font-size:11px; color:#f39c12; margin-top:2px">pembayaran lunas</div>
            </div>
        </div>
    </div>

    {{-- Riwayat Booking --}}
    <div class="card">
        <div class="card-header">
            <h3>📋 Riwayat Booking Saya</h3>
            <a href="{{ route('booking.create') }}" class="btn btn-primary btn-sm">+ Buat Booking</a>
        </div>

        @if($data['booking_saya']->isEmpty())
        <div style="text-align:center; padding:60px; color:#ccc">
            <div style="font-size:48px; margin-bottom:12px">🏨</div>
            <div style="font-size:15px; font-weight:600; color:#aaa; margin-bottom:8px">Belum ada booking</div>
            <div style="font-size:13px; color:#ccc; margin-bottom:20px">Mulai pesan kamar impian Anda sekarang!</div>
            <a href="{{ route('booking.create') }}" class="btn btn-primary">📋 Buat Booking Pertama</a>
        </div>
        @else
        <div style="padding:0">
            <table style="font-size:12px">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Kamar</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Malam</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['booking_saya'] as $b)
                    <tr>
                        <td>
                            <span style="font-family:monospace; font-size:11px; color:#2e86de; font-weight:700; background:#eef4fb; padding:3px 8px; border-radius:6px">
                                {{ $b->kode_booking }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight:600; color:#0d2137">{{ $b->kamar->nomor_kamar ?? '-' }}</div>
                            <div style="font-size:10px; color:#aaa">{{ $b->kamar->tipeKamar->nama_tipe ?? '-' }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($b->tgl_checkin)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($b->tgl_checkout)->format('d M Y') }}</td>
                        <td style="text-align:center">
                            <span style="background:#f0f4f8; padding:2px 8px; border-radius:20px; font-size:11px">
                                {{ $b->jumlah_malam }}🌙
                            </span>
                        </td>
                        <td style="font-weight:700; color:#27ae60">
                            Rp {{ number_format($b->total_biaya, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($b->pembayaran)
                                @if($b->pembayaran->status == 'lunas')
                                    <span class="badge badge-success">✅ Lunas</span>
                                @elseif($b->pembayaran->status == 'dp')
                                    <span class="badge badge-warning">⚠️ DP</span>
                                @else
                                    <span class="badge badge-danger">❌ Belum</span>
                                @endif
                            @else
                                <span class="badge badge-danger">❌ Belum</span>
                            @endif
                        </td>
                        <td>
                            @if($b->status == 'confirmed')
                                <span class="badge badge-info">✅ Confirmed</span>
                            @elseif($b->status == 'checkin')
                                <span class="badge badge-success">🏨 Check-In</span>
                            @elseif($b->status == 'checkout')
                                <span class="badge badge-dark">🚪 Check-Out</span>
                            @elseif($b->status == 'cancelled')
                                <span class="badge badge-danger">❌ Cancelled</span>
                            @else
                                <span class="badge badge-warning">⏳ Pending</span>
                            @endif
                        </td>
                        <td style="text-align:center; white-space:nowrap">
                            <a href="{{ route('booking.show', $b->id_booking) }}"
                               class="btn btn-primary btn-sm">👁️ Detail</a>
                            @if($b->pembayaran && $b->pembayaran->status == 'dp' && $b->status != 'cancelled')
                                <a href="{{ route('pembayaran.create', ['booking_id' => $b->id_booking, 'pelunasan' => 1]) }}"
                                   class="btn btn-sm" style="background:#e67e22; color:#fff">
                                    💰 Lunasi
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</x-app-layout>