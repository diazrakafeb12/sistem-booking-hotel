<x-app-layout>
    @section('title', 'Tipe Kamar')

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px">
        <div>
            <h2 style="font-size:20px; font-weight:800; color:#0d2137; margin-bottom:4px">🏷️ Daftar Tipe Kamar</h2>
            <div style="font-size:13px; color:#888">Kelola semua tipe kamar hotel</div>
        </div>
        @if(in_array(Auth::user()->role, ['ceo', 'admin']))
        <a href="{{ route('tipe-kamar.create') }}" class="btn btn-primary">+ Tambah Tipe Kamar</a>
        @endif
    </div>

    @if($tipeKamar->isEmpty())
        <div style="text-align:center; padding:60px; background:#fff; border-radius:12px; color:#aaa">
            <div style="font-size:40px; margin-bottom:12px">🏷️</div>
            Belum ada data tipe kamar.
        </div>
    @else
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px">
        @foreach($tipeKamar as $i => $tipe)
        <div style="background:#fff; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,0.07); overflow:hidden; display:flex; flex-direction:column; transition:transform 0.2s"
             onmouseover="this.style.transform='translateY(-4px)'"
             onmouseout="this.style.transform='translateY(0)'">

            {{-- Header Card --}}
            <div style="background:linear-gradient(135deg, #0d2137 0%, #1a5276 60%, #2e86de 100%); padding:24px; position:relative; overflow:hidden">
                <div style="position:absolute; right:-20px; top:-20px; width:100px; height:100px; background:rgba(255,255,255,0.05); border-radius:50%"></div>
                <div style="position:absolute; right:20px; bottom:-30px; width:80px; height:80px; background:rgba(255,255,255,0.05); border-radius:50%"></div>
                <div style="font-size:11px; color:#7fb3d3; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; margin-bottom:6px">Tipe #{{ $i + 1 }}</div>
                <div style="font-size:22px; font-weight:800; color:#fff; margin-bottom:4px">{{ $tipe->nama_tipe }}</div>
                <div style="font-size:20px; font-weight:800; color:#4fc3f7">
                    Rp {{ number_format($tipe->harga_per_malam, 0, ',', '.') }}
                    <span style="font-size:12px; font-weight:400; color:#90caf9">/malam</span>
                </div>
            </div>

            {{-- Body --}}
            <div style="padding:20px; flex:1; display:flex; flex-direction:column; gap:16px">

                {{-- Deskripsi --}}
                <div>
                    <div style="font-size:11px; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px">Deskripsi</div>
                    <div style="font-size:13px; color:#555; line-height:1.7; background:#f8f9fa; padding:12px; border-radius:8px; min-height:60px">
                        {{ $tipe->deskripsi ?? 'Tidak ada deskripsi.' }}
                    </div>
                </div>

                {{-- Jumlah Kamar --}}
                <div style="display:flex; align-items:center; gap:10px; padding:12px; background:#eef4fb; border-radius:8px">
                    <span style="font-size:20px">🛏️</span>
                    <div>
                        <div style="font-size:11px; color:#888">Total Kamar Tipe Ini</div>
                        <div style="font-size:15px; font-weight:700; color:#0d2137">
                            {{ $tipe->kamar->count() ?? 0 }} kamar
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aksi --}}
            @if(in_array(Auth::user()->role, ['ceo', 'admin']))
            <div style="padding:14px 20px; border-top:1px solid #f0f4f8; display:flex; gap:8px">
                <a href="{{ route('tipe-kamar.edit', $tipe->id_tipe) }}"
                   class="btn btn-warning btn-sm" style="flex:1; justify-content:center">
                    ✏️ Edit
                </a>
                <form action="{{ route('tipe-kamar.destroy', $tipe->id_tipe) }}" method="POST" style="flex:1"
                      onsubmit="return confirm('Yakin ingin menghapus tipe kamar ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" style="width:100%; justify-content:center">🗑️ Hapus</button>
                </form>
            </div>
            @endif

        </div>
        @endforeach
    </div>
    @endif

</x-app-layout>