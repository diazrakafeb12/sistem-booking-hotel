<x-app-layout>
    @section('title', 'Booking Kamar')

    {{-- Hero Banner --}}
    <div style="background:linear-gradient(135deg,#0d2137 0%,#2e86de 100%); border-radius:16px; padding:40px 48px; margin-bottom:28px; color:#fff; position:relative; overflow:hidden">
        <div style="position:absolute; top:-40px; right:-40px; width:200px; height:200px; background:rgba(255,255,255,0.05); border-radius:50%"></div>
        <div style="position:absolute; bottom:-60px; right:80px; width:150px; height:150px; background:rgba(255,255,255,0.04); border-radius:50%"></div>
        <div style="font-size:13px; color:#b8d4e8; font-weight:600; letter-spacing:2px; text-transform:uppercase; margin-bottom:10px">
            Hotel Booking System
        </div>
        <div style="font-size:32px; font-weight:800; margin-bottom:10px; line-height:1.2">
            Temukan Kamar Impian Anda 🏨
        </div>
        <div style="font-size:15px; color:#b8d4e8; margin-bottom:24px; max-width:500px">
            Nikmati pengalaman menginap terbaik dengan fasilitas premium dan pelayanan profesional kami.
        </div>
        <a href="{{ route('booking.create') }}"
           style="background:#fff; color:#0d2137; padding:12px 28px; border-radius:10px; font-weight:700; font-size:14px; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all 0.2s">
            📋 Buat Booking Sekarang
        </a>
    </div>

    {{-- Info Stats --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px">
        <div style="background:#fff; border-radius:12px; padding:20px 24px; box-shadow:0 2px 12px rgba(0,0,0,0.06); text-align:center">
            <div style="font-size:36px; margin-bottom:8px">🛏️</div>
            <div style="font-size:28px; font-weight:800; color:#0d2137">{{ $kamarTersedia }}</div>
            <div style="font-size:13px; color:#888; margin-top:4px">Kamar Tersedia</div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:20px 24px; box-shadow:0 2px 12px rgba(0,0,0,0.06); text-align:center">
            <div style="font-size:36px; margin-bottom:8px">⭐</div>
            <div style="font-size:28px; font-weight:800; color:#0d2137">{{ $totalTipe }}</div>
            <div style="font-size:13px; color:#888; margin-top:4px">Tipe Kamar</div>
        </div>
        <div style="background:#fff; border-radius:12px; padding:20px 24px; box-shadow:0 2px 12px rgba(0,0,0,0.06); text-align:center">
            <div style="font-size:36px; margin-bottom:8px">✅</div>
            <div style="font-size:28px; font-weight:800; color:#0d2137">{{ $totalBooking }}</div>
            <div style="font-size:13px; color:#888; margin-top:4px">Total Booking</div>
        </div>
    </div>

    {{-- Daftar Tipe Kamar --}}
    <div style="margin-bottom:28px">
        <div style="font-size:18px; font-weight:700; color:#0d2137; margin-bottom:16px">
            🏷️ Tipe Kamar Kami
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:16px">
            @foreach($tipeKamar as $tipe)
            <div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #eef2f7; transition:transform 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)'"
                 onmouseout="this.style.transform='translateY(0)'">
                {{-- Header tipe --}}
                <div style="background:linear-gradient(135deg,#0d2137,#2e86de); padding:20px; color:#fff">
                    <div style="font-size:20px; margin-bottom:4px">
                        @if($loop->index == 0) 🏅
                        @elseif($loop->index == 1) 💎
                        @elseif($loop->index == 2) 👑
                        @else ✨
                        @endif
                    </div>
                    <div style="font-weight:700; font-size:16px">{{ $tipe->nama_tipe }}</div>
                    <div style="font-size:11px; color:#b8d4e8; margin-top:2px">{{ $tipe->kamar->count() }} kamar tersedia</div>
                </div>
                <div style="padding:16px">
                    <div style="font-size:22px; font-weight:800; color:#27ae60; margin-bottom:8px">
                        Rp {{ number_format($tipe->harga_per_malam, 0, ',', '.') }}
                        <span style="font-size:13px; font-weight:400; color:#888">/malam</span>
                    </div>
                    <div style="font-size:13px; color:#888; margin-bottom:16px; min-height:36px">
                        {{ $tipe->deskripsi ?? 'Kamar nyaman dengan fasilitas lengkap.' }}
                    </div>
                    <a href="{{ route('booking.create') }}"
                       style="display:block; text-align:center; background:#2e86de; color:#fff; padding:10px; border-radius:8px; font-weight:600; font-size:13px; text-decoration:none">
                        Pesan Sekarang →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Kamar Tersedia --}}
    <div>
        <div style="font-size:18px; font-weight:700; color:#0d2137; margin-bottom:16px">
            🛏️ Kamar yang Tersedia Hari Ini
        </div>
        @if($kamar->isEmpty())
            <div style="background:#fff; border-radius:12px; padding:40px; text-align:center; color:#aaa; box-shadow:0 2px 12px rgba(0,0,0,0.06)">
                Semua kamar sedang terisi. Silakan cek kembali nanti.
            </div>
        @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:16px">
            @foreach($kamar as $k)
            <div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #eef2f7">
                @if($k->foto)
                    <img src="{{ Storage::url($k->foto) }}" style="width:100%; height:160px; object-fit:cover">
                @else
                    <div style="width:100%; height:160px; background:linear-gradient(135deg,#eef4fb,#d0e8f7); display:flex; align-items:center; justify-content:center; font-size:48px">
                        🛏️
                    </div>
                @endif
                <div style="padding:16px">
                    <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:8px">
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#0d2137">Kamar {{ $k->nomor_kamar }}</div>
                            <div style="font-size:12px; color:#888">{{ $k->tipeKamar->nama_tipe ?? '-' }} • Lantai {{ $k->lantai ?? '-' }}</div>
                        </div>
                        <span style="background:#d4edda; color:#155724; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700">
                            ✅ Tersedia
                        </span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px">
                        <div style="font-weight:800; color:#27ae60; font-size:16px">
                            Rp {{ number_format($k->tipeKamar->harga_per_malam ?? 0, 0, ',', '.') }}
                            <span style="font-size:11px; font-weight:400; color:#888">/malam</span>
                        </div>
                        <a href="{{ route('booking.create') }}"
                           style="background:#0d2137; color:#fff; padding:7px 16px; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none">
                            Pesan →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</x-app-layout>