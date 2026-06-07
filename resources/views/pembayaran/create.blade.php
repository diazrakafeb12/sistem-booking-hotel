<x-app-layout>
    @section('title', 'Input Pembayaran')

    <div style="max-width:700px">
        <div class="card">
            <div class="card-header">
                <h3>💳 {{ $isPelunasan ? 'Pelunasan Pembayaran' : 'Input Pembayaran' }}</h3>
                <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('pembayaran.store') }}" method="POST">
                    @csrf

                    {{-- Pilih Booking (tampil hanya jika tidak dari detail booking) --}}
                    @if(!$booking)
                    <div class="form-group">
    <label class="form-label">Pilih Booking</label>
    <select name="id_booking" class="form-control" required id="bookingSelect">
        <option value="">-- Pilih Kode Booking --</option>
        @foreach($bookings as $b)
            <option value="{{ $b->id_booking }}"
                    data-total="{{ $b->sisa_tagihan }}"
                    data-sudah="{{ $b->sudah_bayar }}"
                    {{ old('id_booking') == $b->id_booking ? 'selected' : '' }}>
                {{ $b->kode_booking }} - {{ $b->tamu->nama_lengkap }}
                @if($b->sudah_bayar > 0)
                    (Sisa: Rp {{ number_format($b->sisa_tagihan, 0, ',', '.') }})
                @else
                    (Rp {{ number_format($b->total_biaya, 0, ',', '.') }})
                @endif
            </option>
        @endforeach
    </select>
    @error('id_booking')<div class="form-error">{{ $message }}</div>@enderror
</div>

{{-- Info total --}}
<div id="infoBooking" style="display:none; background:#eef4fb; border-radius:8px; padding:14px; margin-bottom:16px; font-size:13px">
    <div style="display:flex; justify-content:space-between">
        <span>Yang harus dibayar:</span>
        <strong id="totalTagihan" style="color:#0d2137; font-size:16px"></strong>
    </div>
    <div id="infoDp" style="display:none; color:#e67e22; font-size:12px; margin-top:4px">
        ⚠️ Sudah bayar DP: <strong id="sudahBayar"></strong>
    </div>
</div>

                    @else
                    {{-- Jika dari detail booking, id_booking hidden --}}
                    <input type="hidden" name="id_booking" value="{{ $booking->id_booking }}">

                    {{-- Info booking --}}
                    <div style="background:#eef4fb; border-radius:10px; padding:16px; margin-bottom:20px">
                        <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase; margin-bottom:8px">Info Booking</div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; font-size:13px">
                            <div>Kode: <strong style="color:#2e86de">{{ $booking->kode_booking }}</strong></div>
                            <div>Tamu: <strong>{{ $booking->tamu->nama_lengkap }}</strong></div>
                            <div>Kamar: <strong>Kamar {{ $booking->kamar->nomor_kamar }}</strong></div>
                            <div>Menginap: <strong>{{ $booking->jumlah_malam }} malam</strong></div>
                        </div>
                        <div style="margin-top:10px; padding-top:10px; border-top:1px solid #d0e4f7">
                            <div style="display:flex; justify-content:space-between; align-items:center">
                                <span style="font-size:13px; color:#555">Total Tagihan:</span>
                                <strong style="font-size:18px; color:#0d2137">Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</strong>
                            </div>
                            @if($isPelunasan && $booking->pembayaran)
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:4px">
                                <span style="font-size:13px; color:#555">Sudah Dibayar (DP):</span>
                                <strong style="font-size:14px; color:#27ae60">Rp {{ number_format($booking->pembayaran->jumlah_bayar, 0, ',', '.') }}</strong>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:4px; padding-top:6px; border-top:1px dashed #aaa">
                                <span style="font-size:13px; color:#e74c3c; font-weight:700">Sisa Tagihan:</span>
                                <strong style="font-size:16px; color:#e74c3c">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Jumlah Bayar --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                        <div class="form-group">
                            <label class="form-label">Jumlah Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" id="jumlahBayar"
                                   value="{{ old('jumlah_bayar', $sisaTagihan > 0 ? $sisaTagihan : ($booking->total_biaya ?? '')) }}"
                                   class="form-control" placeholder="Masukkan jumlah bayar" required>
                            @if($isPelunasan)
                                <div style="font-size:12px; color:#e67e22; margin-top:4px">
                                    ⚠️ Sisa: <strong>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong>
                                </div>
                            @endif
                            @error('jumlah_bayar')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode" class="form-control" required>
                                <option value="cash"     {{ old('metode') == 'cash'     ? 'selected' : '' }}>💵 Cash</option>
                                <option value="transfer" {{ old('metode') == 'transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                                <option value="kartu"    {{ old('metode') == 'kartu'    ? 'selected' : '' }}>💳 Kartu Debit/Kredit</option>
                            </select>
                            @error('metode')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Pembayaran</label>
                        <select name="status" class="form-control" required>
                            @if($isPelunasan)
                                {{-- Pelunasan hanya bisa lunas --}}
                                <option value="lunas" selected>✅ Lunas</option>
                            @else
                                <option value="lunas"       {{ old('status') == 'lunas'       ? 'selected' : '' }}>✅ Lunas</option>
                                <option value="dp"          {{ old('status') == 'dp'          ? 'selected' : '' }}>⚠️ DP / Uang Muka</option>
                                <option value="belum_lunas" {{ old('status') == 'belum_lunas' ? 'selected' : '' }}>❌ Belum Lunas</option>
                            @endif
                        </select>
                        @error('status')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan (opsional)</label>
                        <textarea name="keterangan" rows="2" class="form-control"
                                  placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div style="display:flex; gap:10px">
                        <button type="submit" class="btn btn-primary">💾 Simpan Pembayaran</button>
                        <a href="{{ $booking ? route('booking.show', $booking->id_booking) : route('pembayaran.index') }}"
                           class="btn btn-secondary">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
    @if(!$booking)
    const bookingSelect = document.getElementById('bookingSelect');

    function updateInfo() {
        const opt   = bookingSelect?.selectedOptions[0];
        const total = opt?.dataset.total;
        const info  = document.getElementById('infoBooking');
        if (total && parseFloat(total) > 0) {
            document.getElementById('totalTagihan').textContent =
                'Rp ' + parseFloat(total).toLocaleString('id-ID');
            document.getElementById('jumlahBayar').value = total;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    }

    bookingSelect?.addEventListener('change', updateInfo);
    window.addEventListener('load', updateInfo);
    @endif
    </script>
</x-app-layout>