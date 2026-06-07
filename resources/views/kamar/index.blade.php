<x-app-layout>
    @section('title', 'Data Kamar')

    <div style="background:#fff; border-radius:12px; padding:18px 24px; box-shadow:0 2px 12px rgba(0,0,0,0.06); margin-bottom:24px; display:flex; align-items:center; justify-content:space-between">
        <h3 style="font-size:16px; font-weight:700; color:#0d2137">🛏️ Daftar Kamar</h3>
        @if(in_array(Auth::user()->role, ['ceo', 'admin']))
        <a href="{{ route('kamar.create') }}" class="btn btn-primary">+ Tambah Kamar</a>
        @endif
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('kamar.index') }}" style="background:#fff; border-radius:12px; padding:18px 24px; box-shadow:0 2px 12px rgba(0,0,0,0.06); margin-bottom:24px; display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap">
        <div style="flex:1; min-width:200px">
            <label style="font-size:12px; font-weight:700; color:#555; display:block; margin-bottom:6px">Tipe Kamar</label>
            <select name="tipe" class="form-control" style="padding:9px 14px">
                <option value="">— Semua Tipe —</option>
                @foreach($tipeKamar as $t)
                    <option value="{{ $t->id_tipe }}" {{ request('tipe') == $t->id_tipe ? 'selected' : '' }}>
                        {{ $t->nama_tipe }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="flex:1; min-width:200px">
            <label style="font-size:12px; font-weight:700; color:#555; display:block; margin-bottom:6px">Status</label>
            <select name="status" class="form-control" style="padding:9px 14px">
                <option value="">— Semua Status —</option>
                <option value="tersedia"    {{ request('status') == 'tersedia'    ? 'selected' : '' }}>✅ Tersedia</option>
                <option value="terisi"      {{ request('status') == 'terisi'      ? 'selected' : '' }}>🔴 Terisi</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>⚠️ Maintenance</option>
            </select>
        </div>
        <div style="display:flex; gap:8px">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('kamar.index') }}" class="btn btn-secondary">↺ Reset</a>
        </div>
    </form>

    @if(request('tipe') || request('status'))
    <div style="margin-bottom:16px; font-size:13px; color:#555">
        Menampilkan <strong>{{ $kamar->count() }}</strong> kamar
        @if(request('tipe'))
            tipe <strong>{{ $tipeKamar->firstWhere('id_tipe', request('tipe'))->nama_tipe ?? '' }}</strong>
        @endif
        @if(request('status'))
            status <strong>{{ request('status') }}</strong>
        @endif
    </div>
    @endif

    @if($kamar->isEmpty())
        <div style="text-align:center; padding:60px; background:#fff; border-radius:12px; color:#aaa">
            <div style="font-size:40px; margin-bottom:12px">🔍</div>
            Tidak ada kamar yang sesuai filter.
        </div>
    @else
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px">
        @foreach($kamar as $k)
        @php
            $fotos = collect([$k->foto, $k->foto_2, $k->foto_3])->filter()->values();
        @endphp

        <div style="background:#fff; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,0.07); overflow:hidden; display:flex; flex-direction:column; transition:transform 0.2s"
             onmouseover="this.style.transform='translateY(-4px)'"
             onmouseout="this.style.transform='translateY(0)'">

            {{-- Slider Foto --}}
            <div style="position:relative; height:200px; background:#eef2f7; overflow:hidden" id="slider-{{ $k->id_kamar }}">
                @if($fotos->count() > 0)
                    @foreach($fotos as $fi => $foto)
                    <div class="slide-{{ $k->id_kamar }}" style="position:absolute; inset:0; transition:opacity 0.4s; opacity:{{ $fi === 0 ? '1' : '0' }}; pointer-events:{{ $fi === 0 ? 'auto' : 'none' }}">
                        <img src="{{ Storage::url($foto) }}" style="width:100%; height:100%; object-fit:cover">
                    </div>
                    @endforeach

                    @if($fotos->count() > 1)
                    {{-- Tombol Prev --}}
                    <button onclick="slideKamar({{ $k->id_kamar }}, -1, {{ $fotos->count() }})"
                        style="position:absolute; left:8px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.45); color:#fff; border:none; border-radius:50%; width:30px; height:30px; font-size:14px; cursor:pointer; z-index:10">‹</button>
                    {{-- Tombol Next --}}
                    <button onclick="slideKamar({{ $k->id_kamar }}, 1, {{ $fotos->count() }})"
                        style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.45); color:#fff; border:none; border-radius:50%; width:30px; height:30px; font-size:14px; cursor:pointer; z-index:10">›</button>
                    {{-- Dots --}}
                    <div style="position:absolute; bottom:8px; left:50%; transform:translateX(-50%); display:flex; gap:5px; z-index:10">
                        @foreach($fotos as $fi => $foto)
                        <div class="dot-{{ $k->id_kamar }}" style="width:7px; height:7px; border-radius:50%; background:{{ $fi === 0 ? '#fff' : 'rgba(255,255,255,0.5)' }}; transition:background 0.3s"></div>
                        @endforeach
                    </div>
                    @endif
                @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:8px">
                        <span style="font-size:40px">🛏️</span>
                        <span style="font-size:12px; color:#aaa">Tidak ada foto</span>
                    </div>
                @endif

                {{-- Badge Status --}}
                <div style="position:absolute; top:12px; right:12px; z-index:10">
                    @if($k->status == 'tersedia')
                        <span style="background:#27ae60; color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700">✅ Tersedia</span>
                    @elseif($k->status == 'terisi')
                        <span style="background:#e74c3c; color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700">🔴 Terisi</span>
                    @else
                        <span style="background:#f39c12; color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700">⚠️ Maintenance</span>
                    @endif
                </div>
                {{-- Badge Tipe --}}
                <div style="position:absolute; top:12px; left:12px; z-index:10">
                    <span style="background:rgba(13,33,55,0.75); color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600">
                        {{ $k->tipeKamar->nama_tipe ?? '-' }}
                    </span>
                </div>
            </div>

            {{-- Info --}}
            <div style="padding:16px 18px; flex:1; display:flex; flex-direction:column; gap:10px">
                <div style="display:flex; justify-content:space-between; align-items:center">
                    <div style="font-size:18px; font-weight:800; color:#0d2137">{{ $k->nomor_kamar }}</div>
                    <div style="font-size:15px; font-weight:700; color:#27ae60">
                        Rp {{ number_format($k->tipeKamar->harga_per_malam ?? 0, 0, ',', '.') }}
                        <span style="font-size:11px; color:#888; font-weight:400">/malam</span>
                    </div>
                </div>
                <div style="display:flex; gap:16px">
                    <div style="font-size:12px; color:#666">🏢 Lantai {{ $k->lantai ?? '-' }}</div>
                    <div style="font-size:12px; color:#666">👥 {{ $k->kapasitas }} orang</div>
                </div>
                @if($k->tipeKamar->deskripsi ?? false)
                <div style="font-size:12px; color:#888; line-height:1.5; border-top:1px solid #f0f4f8; padding-top:10px">
                    {{ Str::limit($k->tipeKamar->deskripsi, 80) }}
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div style="padding:12px 18px; border-top:1px solid #f0f4f8; display:flex; gap:8px">
                {{-- Tombol Detail --}}
                <button onclick="openModal({{ $k->id_kamar }})"
                    class="btn btn-secondary btn-sm" style="flex:1; justify-content:center">
                    🔍 Detail
                </button>

                @if(in_array(Auth::user()->role, ['ceo', 'admin']))
                <a href="{{ route('kamar.edit', $k->id_kamar) }}" class="btn btn-warning btn-sm" style="flex:1; justify-content:center">
                    ✏️ Edit
                </a>
                <form action="{{ route('kamar.destroy', $k->id_kamar) }}" method="POST" style="flex:1"
                      onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" style="width:100%; justify-content:center">🗑️ Hapus</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Modal Detail --}}
        <div id="modal-{{ $k->id_kamar }}"
             style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:1000; align-items:center; justify-content:center; padding:20px">
            <div style="background:#fff; border-radius:16px; width:100%; max-width:600px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.3)">

                {{-- Slider Modal --}}
                <div style="position:relative; height:280px; background:#eef2f7; border-radius:16px 16px 0 0; overflow:hidden" id="modal-slider-{{ $k->id_kamar }}">
                    @if($fotos->count() > 0)
                        @foreach($fotos as $fi => $foto)
                        <div class="modal-slide-{{ $k->id_kamar }}" style="position:absolute; inset:0; transition:opacity 0.4s; opacity:{{ $fi === 0 ? '1' : '0' }}; pointer-events:{{ $fi === 0 ? 'auto' : 'none' }}">
                            <img src="{{ Storage::url($foto) }}" style="width:100%; height:100%; object-fit:cover">
                        </div>
                        @endforeach

                        @if($fotos->count() > 1)
                        <button onclick="slideModal({{ $k->id_kamar }}, -1, {{ $fotos->count() }})"
                            style="position:absolute; left:12px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.5); color:#fff; border:none; border-radius:50%; width:36px; height:36px; font-size:18px; cursor:pointer; z-index:10">‹</button>
                        <button onclick="slideModal({{ $k->id_kamar }}, 1, {{ $fotos->count() }})"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.5); color:#fff; border:none; border-radius:50%; width:36px; height:36px; font-size:18px; cursor:pointer; z-index:10">›</button>
                        <div style="position:absolute; bottom:10px; left:50%; transform:translateX(-50%); display:flex; gap:6px; z-index:10">
                            @foreach($fotos as $fi => $foto)
                            <div class="modal-dot-{{ $k->id_kamar }}" style="width:8px; height:8px; border-radius:50%; background:{{ $fi === 0 ? '#fff' : 'rgba(255,255,255,0.5)' }}; transition:background 0.3s"></div>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:8px">
                            <span style="font-size:50px">🛏️</span>
                            <span style="font-size:13px; color:#aaa">Tidak ada foto</span>
                        </div>
                    @endif

                    {{-- Tombol Tutup --}}
                    <button onclick="closeModal({{ $k->id_kamar }})"
                        style="position:absolute; top:12px; right:12px; background:rgba(0,0,0,0.5); color:#fff; border:none; border-radius:50%; width:32px; height:32px; font-size:16px; cursor:pointer; z-index:20">✕</button>
                </div>

                {{-- Konten Detail --}}
                <div style="padding:24px">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px">
                        <div>
                            <div style="font-size:22px; font-weight:800; color:#0d2137">Kamar {{ $k->nomor_kamar }}</div>
                            <div style="font-size:13px; color:#888; margin-top:2px">{{ $k->tipeKamar->nama_tipe ?? '-' }}</div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-size:20px; font-weight:800; color:#27ae60">
                                Rp {{ number_format($k->tipeKamar->harga_per_malam ?? 0, 0, ',', '.') }}
                            </div>
                            <div style="font-size:11px; color:#888">per malam</div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div style="margin-bottom:16px">
                        @if($k->status == 'tersedia')
                            <span style="background:#d4edda; color:#155724; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700">✅ Tersedia</span>
                        @elseif($k->status == 'terisi')
                            <span style="background:#f8d7da; color:#721c24; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700">🔴 Terisi</span>
                        @else
                            <span style="background:#fff3cd; color:#856404; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700">⚠️ Maintenance</span>
                        @endif
                    </div>

                    {{-- Info Grid --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px">
                        <div style="background:#f8f9fa; border-radius:10px; padding:14px; text-align:center">
                            <div style="font-size:22px; margin-bottom:4px">🏢</div>
                            <div style="font-size:11px; color:#888">Lantai</div>
                            <div style="font-size:15px; font-weight:700; color:#0d2137">{{ $k->lantai ?? '-' }}</div>
                        </div>
                        <div style="background:#f8f9fa; border-radius:10px; padding:14px; text-align:center">
                            <div style="font-size:22px; margin-bottom:4px">👥</div>
                            <div style="font-size:11px; color:#888">Kapasitas</div>
                            <div style="font-size:15px; font-weight:700; color:#0d2137">{{ $k->kapasitas }} orang</div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    @if($k->tipeKamar->deskripsi ?? false)
                    <div style="margin-bottom:20px">
                        <div style="font-size:13px; font-weight:700; color:#0d2137; margin-bottom:8px">📋 Deskripsi</div>
                        <div style="font-size:13px; color:#555; line-height:1.7; background:#f8f9fa; padding:14px; border-radius:10px">
                            {{ $k->tipeKamar->deskripsi }}
                        </div>
                    </div>
                    @endif

                    {{-- Tombol Booking --}}
                    @if($k->status == 'tersedia')
                    <a href="{{ route('booking.create') }}"
                        style="display:block; text-align:center; background:#2e86de; color:#fff; padding:12px; border-radius:8px; font-size:14px; font-weight:700; text-decoration:none">
                        📋 Booking Kamar Ini
                    </a>
                    @else
                    <div style="text-align:center; background:#f0f4f8; color:#aaa; padding:12px; border-radius:8px; font-size:14px; font-weight:600">
                        Kamar tidak tersedia saat ini
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <script>
        const slideIndex = {};
        const modalSlideIndex = {};

        function slideKamar(id, dir, total) {
            if (slideIndex[id] === undefined) slideIndex[id] = 0;
            slideIndex[id] = (slideIndex[id] + dir + total) % total;
            updateSlider(id, total);
        }

        function updateSlider(id, total) {
            const slides = document.querySelectorAll(`.slide-${id}`);
            const dots   = document.querySelectorAll(`.dot-${id}`);
            slides.forEach((s, i) => {
                s.style.opacity = i === slideIndex[id] ? '1' : '0';
                s.style.pointerEvents = i === slideIndex[id] ? 'auto' : 'none';
            });
            dots.forEach((d, i) => {
                d.style.background = i === slideIndex[id] ? '#fff' : 'rgba(255,255,255,0.5)';
            });
        }

        function slideModal(id, dir, total) {
            if (modalSlideIndex[id] === undefined) modalSlideIndex[id] = 0;
            modalSlideIndex[id] = (modalSlideIndex[id] + dir + total) % total;
            updateModalSlider(id, total);
        }

        function updateModalSlider(id, total) {
            const slides = document.querySelectorAll(`.modal-slide-${id}`);
            const dots   = document.querySelectorAll(`.modal-dot-${id}`);
            slides.forEach((s, i) => {
                s.style.opacity = i === modalSlideIndex[id] ? '1' : '0';
                s.style.pointerEvents = i === modalSlideIndex[id] ? 'auto' : 'none';
            });
            dots.forEach((d, i) => {
                d.style.background = i === modalSlideIndex[id] ? '#fff' : 'rgba(255,255,255,0.5)';
            });
        }

        function openModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Tutup modal klik luar
        document.querySelectorAll('[id^="modal-"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>

</x-app-layout>