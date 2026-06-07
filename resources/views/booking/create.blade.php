<x-app-layout>
    @section('title', 'Buat Booking')

    <div style="max-width:800px">
        <div class="card">
            <div class="card-header">
                <h3>📋 Form Booking Kamar</h3>
                <a href="{{ route('booking.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('booking.store') }}" method="POST" id="formBooking">
                    @csrf

                    {{-- STEP 1 --}}
                    <div style="background:#eef4fb; border-radius:10px; padding:20px; margin-bottom:24px">
                        <div style="font-size:13px; font-weight:700; color:#0d2137; margin-bottom:14px">
                            📅 LANGKAH 1 — Pilih Tanggal Menginap
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr auto; gap:16px; align-items:end">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tanggal Check-In</label>
                                <input type="date" name="tgl_checkin" id="checkin"
                                       class="form-control" min="{{ date('Y-m-d') }}"
                                       value="{{ old('tgl_checkin') }}" required>
                                @error('tgl_checkin')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tanggal Check-Out</label>
                                <input type="date" name="tgl_checkout" id="checkout"
                                       class="form-control"
                                       value="{{ old('tgl_checkout') }}" required>
                                @error('tgl_checkout')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <button type="button" id="btnCekKamar" class="btn btn-primary"
                                    onclick="cekKamar()" disabled>
                                🔍 Cek Kamar
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div id="stepKamar" style="display:none; margin-bottom:24px">
                        <div style="font-size:13px; font-weight:700; color:#0d2137; margin-bottom:14px">
                            🛏️ LANGKAH 2 — Pilih Kamar
                        </div>
                        <div id="loadingKamar" style="text-align:center; padding:20px; display:none">
                            <span style="color:#888">⏳ Mengecek ketersediaan kamar...</span>
                        </div>
                        <div id="daftarKamar" style="display:grid; grid-template-columns:repeat(2,1fr); gap:16px"></div>
                        <input type="hidden" name="id_kamar" id="id_kamar" value="{{ old('id_kamar') }}">
                        @error('id_kamar')
                            <div class="form-error" style="margin-top:8px">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STEP 3 --}}
                    <div id="stepDetail" style="display:none">
                        <div style="font-size:13px; font-weight:700; color:#0d2137; margin-bottom:14px">
                            👤 LANGKAH 3 — Detail Tamu & Pembayaran
                        </div>

                        {{-- Info Kamar Terpilih --}}
                        <div id="infoKamarTerpilih" style="background:#d4edda; border-radius:10px; padding:16px; margin-bottom:16px; display:none">
                            <div style="font-weight:700; color:#155724; font-size:14px" id="namaKamarTerpilih"></div>
                            <div style="font-size:13px; color:#1e8449; margin-top:4px" id="hargaKamarTerpilih"></div>
                        </div>

                        {{-- Data Tamu --}}
                        <div style="background:#f8faff; border-radius:10px; padding:18px; margin-bottom:16px; border:1px solid #e8eef7">
                            <div style="font-size:12px; font-weight:700; color:#2e86de; text-transform:uppercase; letter-spacing:1px; margin-bottom:14px">
                                👤 Data Tamu
                            </div>

                            @if(Auth::user()->role === 'customer')
                                <div style="background:#eef4fb; border-radius:8px; padding:10px 14px; margin-bottom:14px; font-size:12px; color:#0c5460; border:1px solid #b8d4e8">
                                    ℹ️ Email diambil otomatis dari akun Anda: <strong>{{ Auth::user()->email }}</strong>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                                    <div class="form-group" style="grid-column:span 2; margin:0">
                                        <label class="form-label">Nama Lengkap <span style="color:#e74c3c">*</span></label>
                                        <input type="text" name="nama_tamu" class="form-control"
                                               placeholder="Masukkan nama lengkap Anda"
                                               value="{{ old('nama_tamu') }}" required>
                                        @error('nama_tamu')<div class="form-error">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group" style="margin:0">
                                        <label class="form-label">No. HP <span style="color:#e74c3c">*</span></label>
                                        <input type="text" name="no_hp" class="form-control"
                                               placeholder="08xxxxxxxxxx"
                                               value="{{ old('no_hp') }}" required>
                                        @error('no_hp')<div class="form-error">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group" style="margin:0">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ Auth::user()->email }}" readonly
                                               style="background:#f0f4f8; color:#888; cursor:not-allowed">
                                    </div>
                                </div>
                            @else
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                                    <div class="form-group" style="grid-column:span 2; margin:0">
                                        <label class="form-label">Nama Lengkap <span style="color:#e74c3c">*</span></label>
                                        <input type="text" name="nama_tamu" class="form-control"
                                               placeholder="Masukkan nama lengkap tamu"
                                               value="{{ old('nama_tamu') }}" required>
                                        @error('nama_tamu')<div class="form-error">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group" style="margin:0">
                                        <label class="form-label">No. HP <span style="color:#e74c3c">*</span></label>
                                        <input type="text" name="no_hp" class="form-control"
                                               placeholder="08xxxxxxxxxx"
                                               value="{{ old('no_hp') }}" required>
                                        @error('no_hp')<div class="form-error">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group" style="margin:0">
                                        <label class="form-label">Email <span style="color:#aaa; font-weight:400">(opsional)</span></label>
                                        <input type="email" name="email" class="form-control"
                                               placeholder="email@contoh.com"
                                               value="{{ old('email') }}">
                                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Estimasi Biaya --}}
                        <div style="background:#eef4fb; border-radius:10px; padding:16px; margin-bottom:16px">
                            <div style="font-size:12px; font-weight:700; color:#2e86de; text-transform:uppercase; letter-spacing:1px; margin-bottom:10px">
                                💰 Estimasi Biaya
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center">
                                <div style="font-size:13px; color:#555">
                                    <span id="infoMalam">0 malam</span> ×
                                    <span id="infoHarga">Rp 0</span>
                                </div>
                                <div>
                                    <span style="font-size:13px; color:#555; font-weight:600">Total: </span>
                                    <span id="totalBiaya" style="font-size:22px; font-weight:800; color:#0d2137">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        {{-- Pembayaran --}}
                        <div style="background:#fff8f0; border-radius:10px; padding:18px; margin-bottom:16px; border:1px solid #f5d5a8">
                            <div style="font-size:12px; font-weight:700; color:#e67e22; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px">
                                💳 Pembayaran
                            </div>
                            <div style="font-size:12px; color:#e67e22; margin-bottom:14px">
                                ⚠️ Tamu wajib melakukan pembayaran minimal DP untuk mengkonfirmasi booking.
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                                <div class="form-group" style="margin:0">
                                    <label class="form-label">Jumlah Bayar (Rp) <span style="color:#e74c3c">*</span></label>
                                    <input type="number" name="jumlah_bayar" id="jumlahBayar"
                                           class="form-control" placeholder="Masukkan jumlah bayar"
                                           value="{{ old('jumlah_bayar') }}" required min="1">
                                    <div style="font-size:11px; color:#888; margin-top:4px">
                                        Min. DP: <strong id="minDP">-</strong>
                                    </div>
                                    @error('jumlah_bayar')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group" style="margin:0">
                                    <label class="form-label">Metode Pembayaran <span style="color:#e74c3c">*</span></label>
                                    <select name="metode_bayar" class="form-control" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="cash"     {{ old('metode_bayar') == 'cash'     ? 'selected' : '' }}>💵 Cash</option>
                                        <option value="transfer" {{ old('metode_bayar') == 'transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                                        <option value="kartu"    {{ old('metode_bayar') == 'kartu'    ? 'selected' : '' }}>💳 Kartu Debit/Kredit</option>
                                    </select>
                                    @error('metode_bayar')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:14px; margin-bottom:0">
                                <label class="form-label">Status Pembayaran <span style="color:#e74c3c">*</span></label>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:6px">
                                    <label id="optLunas" style="border:2px solid #dde3ed; border-radius:10px; padding:12px 14px; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; gap:10px">
                                        <input type="radio" name="status_bayar" value="lunas"
                                               {{ old('status_bayar') == 'lunas' ? 'checked' : '' }}
                                               onchange="pilihStatusBayar('lunas')" style="accent-color:#27ae60">
                                        <div>
                                            <div style="font-weight:700; font-size:13px; color:#155724">✅ Lunas</div>
                                            <div style="font-size:11px; color:#888">Bayar penuh sekarang</div>
                                        </div>
                                    </label>
                                    <label id="optDP" style="border:2px solid #dde3ed; border-radius:10px; padding:12px 14px; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; gap:10px">
                                        <input type="radio" name="status_bayar" value="dp"
                                               {{ old('status_bayar') == 'dp' ? 'checked' : '' }}
                                               onchange="pilihStatusBayar('dp')" style="accent-color:#f39c12">
                                        <div>
                                            <div style="font-weight:700; font-size:13px; color:#856404">⚠️ DP / Uang Muka</div>
                                            <div style="font-size:11px; color:#888">Bayar sebagian dulu</div>
                                        </div>
                                    </label>
                                </div>
                                @error('status_bayar')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="form-group">
                            <label class="form-label">Catatan <span style="color:#aaa; font-weight:400">(opsional)</span></label>
                            <textarea name="catatan" rows="2" class="form-control"
                                      placeholder="Permintaan khusus, dll.">{{ old('catatan') }}</textarea>
                        </div>

                        <div style="display:flex; gap:10px">
                            <button type="submit" class="btn btn-primary">💾 Simpan Booking & Pembayaran</button>
                            <a href="{{ route('booking.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
    let hargaKamarDipilih = 0;

    document.getElementById('checkin').addEventListener('change', function() {
        const checkout = document.getElementById('checkout');
        checkout.min = this.value;
        if (checkout.value && checkout.value <= this.value) checkout.value = '';
        toggleBtnCek();
        resetKamar();
    });

    document.getElementById('checkout').addEventListener('change', function() {
        toggleBtnCek();
        resetKamar();
    });

    function toggleBtnCek() {
        const ci = document.getElementById('checkin').value;
        const co = document.getElementById('checkout').value;
        document.getElementById('btnCekKamar').disabled = !(ci && co && co > ci);
    }

    function resetKamar() {
        document.getElementById('stepKamar').style.display  = 'none';
        document.getElementById('stepDetail').style.display = 'none';
        document.getElementById('id_kamar').value = '';
        hargaKamarDipilih = 0;
    }

    function getMalam() {
        const ci = new Date(document.getElementById('checkin').value);
        const co = new Date(document.getElementById('checkout').value);
        return Math.max(0, (co - ci) / 86400000);
    }

    function cekKamar() {
        const checkin  = document.getElementById('checkin').value;
        const checkout = document.getElementById('checkout').value;

        document.getElementById('stepKamar').style.display    = 'block';
        document.getElementById('loadingKamar').style.display = 'block';
        document.getElementById('daftarKamar').innerHTML      = '';
        document.getElementById('stepDetail').style.display   = 'none';

        fetch('{{ route("booking.cek") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ tgl_checkin: checkin, tgl_checkout: checkout })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('loadingKamar').style.display = 'none';
            tampilkanKamar(data);
        })
        .catch(() => {
            document.getElementById('loadingKamar').style.display = 'none';
            document.getElementById('daftarKamar').innerHTML =
                '<p style="color:red; padding:10px">Gagal mengecek ketersediaan kamar.</p>';
        });
    }

    function tampilkanKamar(kamarList) {
        const container = document.getElementById('daftarKamar');
        container.innerHTML = '';

        if (kamarList.length === 0) {
            container.innerHTML = '<p style="color:#aaa; text-align:center; padding:20px">Tidak ada kamar ditemukan.</p>';
            return;
        }

        kamarList.forEach(k => {
            const card = document.createElement('div');
            card.id = 'kamar-' + k.id_kamar;
            card.style.cssText = `
                border: 2px solid ${k.tersedia ? '#dde3ed' : '#f5c6cb'};
                border-radius: 12px; padding: 16px;
                cursor: ${k.tersedia ? 'pointer' : 'not-allowed'};
                background: ${k.tersedia ? '#fff' : '#fff5f5'};
                opacity: ${k.tersedia ? '1' : '0.7'};
                transition: all 0.2s; position: relative;
            `;
            card.innerHTML = `
                ${k.foto ? `<img src="${k.foto}" style="width:100%; height:120px; object-fit:cover; border-radius:8px; margin-bottom:10px">` : ''}
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:6px">
                    <div>
                        <div style="font-weight:700; font-size:15px; color:#0d2137">Kamar ${k.nomor_kamar}</div>
                        <div style="font-size:12px; color:#888">${k.tipe} • Lantai ${k.lantai ?? '-'} • ${k.kapasitas} orang</div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-weight:800; color:#27ae60; font-size:14px">${k.harga_format}</div>
                        <div style="font-size:11px; color:#aaa">/malam</div>
                    </div>
                </div>
                ${k.tersedia
                    ? `<div style="background:#d4edda; color:#155724; border-radius:6px; padding:5px 10px; font-size:12px; font-weight:700; text-align:center; margin-top:8px">✅ TERSEDIA</div>`
                    : `<div style="background:#f8d7da; color:#721c24; border-radius:6px; padding:5px 10px; font-size:12px; font-weight:700; text-align:center; margin-top:8px">❌ TIDAK TERSEDIA</div>`
                }
            `;
            if (k.tersedia) {
                card.onclick = () => pilihKamar(k);
                card.addEventListener('mouseenter', () => {
                    if (document.getElementById('id_kamar').value != k.id_kamar) {
                        card.style.borderColor = '#2e86de';
                        card.style.background  = '#f7faff';
                    }
                });
                card.addEventListener('mouseleave', () => {
                    if (document.getElementById('id_kamar').value != k.id_kamar) {
                        card.style.borderColor = '#dde3ed';
                        card.style.background  = '#fff';
                    }
                });
            }
            container.appendChild(card);
        });
    }

    function pilihKamar(k) {
        document.querySelectorAll('#daftarKamar > div').forEach(c => {
            c.style.borderColor = '#dde3ed';
            c.style.background  = '#fff';
        });
        const card = document.getElementById('kamar-' + k.id_kamar);
        card.style.borderColor = '#2e86de';
        card.style.background  = '#eef4fb';

        document.getElementById('id_kamar').value = k.id_kamar;
        hargaKamarDipilih = k.harga;

        document.getElementById('namaKamarTerpilih').textContent  = `🛏️ Kamar ${k.nomor_kamar} — ${k.tipe}`;
        document.getElementById('hargaKamarTerpilih').textContent = `${k.harga_format} / malam`;
        document.getElementById('infoKamarTerpilih').style.display = 'block';

        hitungTotal();

        const total = hargaKamarDipilih * getMalam();
        const minDP = Math.ceil(total * 0.1);
        document.getElementById('minDP').textContent      = 'Rp ' + minDP.toLocaleString('id-ID');
        document.getElementById('jumlahBayar').min        = minDP;

        document.getElementById('stepDetail').style.display = 'block';
        document.getElementById('stepDetail').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hitungTotal() {
        const malam = getMalam();
        const total = malam * hargaKamarDipilih;
        document.getElementById('infoMalam').textContent  = malam + ' malam';
        document.getElementById('infoHarga').textContent  = 'Rp ' + hargaKamarDipilih.toLocaleString('id-ID');
        document.getElementById('totalBiaya').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function pilihStatusBayar(val) {
        document.getElementById('optLunas').style.borderColor = val == 'lunas' ? '#27ae60' : '#dde3ed';
        document.getElementById('optLunas').style.background  = val == 'lunas' ? '#f0faf4' : '#fff';
        document.getElementById('optDP').style.borderColor    = val == 'dp'    ? '#f39c12' : '#dde3ed';
        document.getElementById('optDP').style.background     = val == 'dp'    ? '#fef9e7' : '#fff';

        const total = hargaKamarDipilih * getMalam();
        if (val == 'lunas') {
            document.getElementById('jumlahBayar').value    = total;
            document.getElementById('jumlahBayar').readOnly = true;
        } else {
            document.getElementById('jumlahBayar').value       = '';
            document.getElementById('jumlahBayar').readOnly    = false;
            document.getElementById('jumlahBayar').placeholder = 'Min. 10% dari total';
        }
    }
    </script>
</x-app-layout>