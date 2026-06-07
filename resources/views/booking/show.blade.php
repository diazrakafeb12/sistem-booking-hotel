<x-app-layout>
    @section('title', 'Detail Booking')

    <div style="max-width:800px">
        <div class="card">
            <div class="card-header">
                <h3>📋 Detail Booking — <span style="color:#2e86de">{{ $booking->kode_booking }}</span></h3>
                <a href="{{ route('booking.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
            <div class="card-body">

                {{-- Status Banner --}}
                @php
                    $bannerBg    = '#d1ecf1';
                    $bannerColor = '#0c5460';
                    $bannerIcon  = '✅';
                    if ($booking->status == 'checkin')   { $bannerBg = '#d4edda'; $bannerColor = '#155724'; $bannerIcon = '🏨'; }
                    if ($booking->status == 'checkout')  { $bannerBg = '#e2e3e5'; $bannerColor = '#383d41'; $bannerIcon = '🚪'; }
                    if ($booking->status == 'cancelled') { $bannerBg = '#f8d7da'; $bannerColor = '#721c24'; $bannerIcon = '❌'; }
                    if ($booking->status == 'pending')   { $bannerBg = '#fff3cd'; $bannerColor = '#856404'; $bannerIcon = '⏳'; }
                @endphp
                <div style="text-align:center; padding:20px; border-radius:12px; margin-bottom:24px;
                            background:{{ $bannerBg }}; color:{{ $bannerColor }}">
                    <div style="font-size:32px; margin-bottom:6px">{{ $bannerIcon }}</div>
                    <div style="font-weight:800; font-size:18px; text-transform:uppercase; letter-spacing:2px">
                        {{ $booking->status }}
                    </div>
                    <div style="font-size:12px; margin-top:4px; opacity:0.8">
                        Kode Booking: {{ $booking->kode_booking }}
                    </div>
                </div>

                {{-- Info Grid --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px">

                    {{-- Info Tamu --}}
                    <div style="background:#f8faff; border-radius:10px; padding:16px; border:1px solid #e8eef7">
                        <div style="font-size:10px; color:#888; text-transform:uppercase; font-weight:700; letter-spacing:1px; margin-bottom:10px">
                            👤 Info Tamu
                        </div>
                        <div style="font-weight:700; font-size:15px; color:#0d2137">{{ $booking->tamu->nama_lengkap }}</div>
                        <div style="color:#888; font-size:13px; margin-top:4px">📱 {{ $booking->tamu->no_hp ?? '-' }}</div>
                        <div style="color:#888; font-size:13px">✉️ {{ $booking->tamu->email ?? '-' }}</div>
                    </div>

                    {{-- Info Kamar --}}
                    <div style="background:#f8faff; border-radius:10px; padding:16px; border:1px solid #e8eef7">
                        <div style="font-size:10px; color:#888; text-transform:uppercase; font-weight:700; letter-spacing:1px; margin-bottom:10px">
                            🛏️ Info Kamar
                        </div>
                        <div style="font-weight:700; font-size:15px; color:#0d2137">Kamar {{ $booking->kamar->nomor_kamar }}</div>
                        <div style="color:#888; font-size:13px; margin-top:4px">{{ $booking->kamar->tipeKamar->nama_tipe ?? '-' }}</div>
                        <div style="color:#27ae60; font-weight:700; font-size:14px; margin-top:4px">
                            Rp {{ number_format($booking->kamar->tipeKamar->harga_per_malam ?? 0, 0, ',', '.') }}/malam
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div style="background:#f8faff; border-radius:10px; padding:16px; border:1px solid #e8eef7">
                        <div style="font-size:10px; color:#888; text-transform:uppercase; font-weight:700; letter-spacing:1px; margin-bottom:10px">
                            📅 Tanggal Menginap
                        </div>
                        <div style="font-size:13px; margin-bottom:6px">
                            Check-In: <strong>{{ \Carbon\Carbon::parse($booking->tgl_checkin)->format('d M Y') }}</strong>
                        </div>
                        <div style="font-size:13px; margin-bottom:8px">
                            Check-Out: <strong>{{ \Carbon\Carbon::parse($booking->tgl_checkout)->format('d M Y') }}</strong>
                        </div>
                        <div style="background:#2e86de; color:#fff; border-radius:6px; padding:4px 10px; display:inline-block; font-size:12px; font-weight:700">
                            {{ $booking->jumlah_malam }} malam
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    <div style="background:#f8faff; border-radius:10px; padding:16px; border:1px solid #e8eef7">
                        <div style="font-size:10px; color:#888; text-transform:uppercase; font-weight:700; letter-spacing:1px; margin-bottom:10px">
                            💳 Pembayaran
                        </div>
                        <div style="font-size:11px; color:#888; margin-bottom:2px">Total Tagihan</div>
                        <div style="font-size:22px; font-weight:800; color:#0d2137; margin-bottom:8px">
                            Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}
                        </div>

                        @if(!$booking->pembayaran)
                            {{-- Belum ada pembayaran sama sekali --}}
                            <span style="background:#f8d7da; color:#721c24; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700">
                                ❌ Belum Bayar
                            </span>

                        @elseif($booking->pembayaran->status == 'dp')
                            {{-- Sudah DP, belum lunas --}}
                            @php $sisa = $booking->total_biaya - $booking->pembayaran->jumlah_bayar; @endphp
                            <span style="background:#fff3cd; color:#856404; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700">
                                ⚠️ DP / Uang Muka
                            </span>
                            <div style="margin-top:10px; background:#fff3cd; border-radius:8px; padding:10px; font-size:13px">
                                <div style="display:flex; justify-content:space-between; margin-bottom:4px">
                                    <span style="color:#856404">Sudah Dibayar (DP):</span>
                                    <strong>Rp {{ number_format($booking->pembayaran->jumlah_bayar, 0, ',', '.') }}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; border-top:1px solid #f0d080; padding-top:6px; margin-top:4px">
                                    <span style="color:#e74c3c; font-weight:700">Sisa Tagihan:</span>
                                    <strong style="color:#e74c3c; font-size:15px">Rp {{ number_format($sisa, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                        @elseif($booking->pembayaran->status == 'lunas')
                            {{-- Lunas --}}
                            <span style="background:#d4edda; color:#155724; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700">
                                ✅ Lunas
                            </span>
                            <div style="font-size:12px; color:#888; margin-top:6px">
                                via {{ strtoupper($booking->pembayaran->metode) }} •
                                {{ \Carbon\Carbon::parse($booking->pembayaran->tgl_bayar)->format('d M Y, H:i') }}
                            </div>

                        @else
                            <span style="background:#f8d7da; color:#721c24; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700">
                                ❌ Belum Lunas
                            </span>
                        @endif
                    </div>

                </div>

                {{-- Catatan --}}
                @if($booking->catatan)
                <div style="background:#fffbea; border-radius:8px; padding:14px; margin-bottom:20px; font-size:13px; color:#555; border:1px solid #f0e080">
                    📝 <strong>Catatan:</strong> {{ $booking->catatan }}
                </div>
                @endif

                {{-- Action Buttons --}}
<div style="display:flex; gap:10px; flex-wrap:wrap; padding-top:16px; border-top:1px solid #eef2f7">

    {{-- Check-In --}}
    @if($booking->status == 'confirmed')
        <form action="{{ route('booking.checkin', $booking->id_booking) }}" method="POST">
            @csrf @method('PATCH')
            <button class="btn btn-success">🏨 Konfirmasi Check-In</button>
        </form>
    @endif

    {{-- Check-Out --}}
    @if($booking->status == 'checkin')
        @if($booking->pembayaran && $booking->pembayaran->status == 'lunas')
            <form action="{{ route('booking.checkout', $booking->id_booking) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-warning">🚪 Konfirmasi Check-Out</button>
            </form>
        @else
            <div style="background:#fff3cd; border-radius:10px; padding:14px 18px; font-size:13px; color:#856404; border:1px solid #f0d080; width:100%">
                <div style="font-weight:700; margin-bottom:6px">⚠️ Check-Out tidak bisa dilakukan!</div>
                <div style="font-size:12px; margin-bottom:10px">
                    Tamu harus menyelesaikan pembayaran terlebih dahulu sebelum check-out.
                    @if($booking->pembayaran && $booking->pembayaran->status == 'dp')
                        @php $sisa = $booking->total_biaya - $booking->pembayaran->jumlah_bayar; @endphp
                        Sisa tagihan: <strong style="color:#e74c3c">Rp {{ number_format($sisa, 0, ',', '.') }}</strong>
                    @endif
                </div>
                @if($booking->pembayaran && $booking->pembayaran->status == 'dp')
                    <a href="{{ route('pembayaran.create', ['booking_id' => $booking->id_booking, 'pelunasan' => 1]) }}"
                       class="btn btn-sm" style="background:#e67e22; color:#fff">
                        💰 Proses Pelunasan Sekarang
                    </a>
                @elseif(!$booking->pembayaran)
                    <a href="{{ route('pembayaran.create', ['booking_id' => $booking->id_booking]) }}"
                       class="btn btn-primary btn-sm">
                        💳 Input Pembayaran
                    </a>
                @endif
            </div>
        @endif
    @endif

    {{-- Input Pembayaran jika belum ada --}}
    @if(!$booking->pembayaran && $booking->status != 'cancelled' && $booking->status != 'checkin')
        <a href="{{ route('pembayaran.create', ['booking_id' => $booking->id_booking]) }}"
           class="btn btn-primary">💳 Input Pembayaran</a>
    @endif

    {{-- Pelunasan jika masih DP --}}
    @if($booking->pembayaran && $booking->pembayaran->status == 'dp' && $booking->status != 'cancelled' && $booking->status != 'checkin')
        <a href="{{ route('pembayaran.create', ['booking_id' => $booking->id_booking, 'pelunasan' => 1]) }}"
           class="btn" style="background:#e67e22; color:#fff">
            💰 Bayar Sisa / Pelunasan
        </a>
    @endif

    {{-- Invoice --}}
    @if($booking->pembayaran)
        <a href="{{ route('pembayaran.show', $booking->pembayaran->id_pembayaran) }}"
           class="btn btn-secondary">🧾 Lihat Invoice</a>
    @endif

</div>

            </div>
        </div>
    </div>
</x-app-layout>