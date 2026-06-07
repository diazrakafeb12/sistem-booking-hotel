<x-app-layout>
    @section('title', 'Edit Kamar')

    <div style="max-width:700px">
        <div class="card">
            <div class="card-header">
                <h3>✏️ Edit Kamar {{ $kamar->nomor_kamar }}</h3>
                <a href="{{ route('kamar.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('kamar.update', $kamar->id_kamar) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                        <div class="form-group">
                            <label class="form-label">Nomor Kamar</label>
                            <input type="text" name="nomor_kamar"
                                   value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}"
                                   class="form-control">
                            @error('nomor_kamar')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tipe Kamar</label>
                            <select name="id_tipe" class="form-control">
                                @foreach($tipeKamar as $tipe)
                                    <option value="{{ $tipe->id_tipe }}"
                                        {{ old('id_tipe', $kamar->id_tipe) == $tipe->id_tipe ? 'selected' : '' }}>
                                        {{ $tipe->nama_tipe }} - Rp {{ number_format($tipe->harga_per_malam, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tipe')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Lantai</label>
                            <input type="number" name="lantai"
                                   value="{{ old('lantai', $kamar->lantai) }}"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kapasitas (orang)</label>
                            <input type="number" name="kapasitas"
                                   value="{{ old('kapasitas', $kamar->kapasitas) }}"
                                   class="form-control" min="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Kamar</label>
                        <select name="status" class="form-control">
                            <option value="tersedia"    {{ old('status', $kamar->status) == 'tersedia'    ? 'selected' : '' }}>✅ Tersedia</option>
                            <option value="terisi"      {{ old('status', $kamar->status) == 'terisi'      ? 'selected' : '' }}>🔴 Terisi</option>
                            <option value="maintenance" {{ old('status', $kamar->status) == 'maintenance' ? 'selected' : '' }}>⚠️ Maintenance</option>
                        </select>
                    </div>

                    {{-- Foto 1, 2, 3 --}}
                    <div style="margin-bottom:8px; font-size:13px; font-weight:700; color:#0d2137">📷 Foto Kamar (maks. 3 foto)</div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:20px">

                        @foreach(['foto' => 'Foto Utama', 'foto_2' => 'Foto 2', 'foto_3' => 'Foto 3'] as $field => $label)
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" style="font-size:12px">{{ $label }}</label>
                            <label for="{{ $field }}" style="display:block; cursor:pointer">
                                <div id="preview-box-{{ $field }}"
                                     style="width:100%; height:120px; border:2px dashed #dde3ed; border-radius:10px; overflow:hidden; background:#f8f9fa; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:6px; transition:border-color 0.2s; position:relative"
                                     onmouseover="this.style.borderColor='#2e86de'"
                                     onmouseout="this.style.borderColor='#dde3ed'">

                                    {{-- Foto existing --}}
                                    @if($kamar->$field)
                                        <img id="preview-img-{{ $field }}"
                                             src="{{ Storage::url($kamar->$field) }}"
                                             style="width:100%; height:100%; object-fit:cover; border-radius:8px">
                                        <div id="preview-icon-{{ $field }}" style="display:none; font-size:24px">📷</div>
                                        <div id="preview-text-{{ $field }}" style="display:none; font-size:11px; color:#aaa">Klik untuk ganti</div>
                                    @else
                                        <img id="preview-img-{{ $field }}" src="" style="display:none; width:100%; height:100%; object-fit:cover; border-radius:8px">
                                        <span id="preview-icon-{{ $field }}" style="font-size:24px">📷</span>
                                        <span id="preview-text-{{ $field }}" style="font-size:11px; color:#aaa">Klik untuk pilih</span>
                                    @endif

                                    {{-- Overlay ganti --}}
                                    @if($kamar->$field)
                                    <div style="position:absolute; inset:0; background:rgba(0,0,0,0.35); display:flex; align-items:center; justify-content:center; border-radius:8px; opacity:0; transition:opacity 0.2s"
                                         onmouseover="this.style.opacity='1'"
                                         onmouseout="this.style.opacity='0'">
                                        <span style="color:#fff; font-size:12px; font-weight:700">🔄 Ganti Foto</span>
                                    </div>
                                    @endif
                                </div>
                                <input type="file" id="{{ $field }}" name="{{ $field }}" accept="image/*"
                                       style="display:none" onchange="previewFoto(this, '{{ $field }}')">
                            </label>
                            @if($kamar->$field)
                                <div style="font-size:11px; color:#aaa; margin-top:4px; text-align:center">Upload baru untuk mengganti</div>
                            @endif
                            @error($field)<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        @endforeach

                    </div>

                    <div style="display:flex; gap:10px; margin-top:8px">
                        <button type="submit" class="btn btn-warning">💾 Update</button>
                        <a href="{{ route('kamar.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function previewFoto(input, field) {
        const img  = document.getElementById(`preview-img-${field}`);
        const icon = document.getElementById(`preview-icon-${field}`);
        const text = document.getElementById(`preview-text-${field}`);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.style.display = 'block';
                if (icon) icon.style.display = 'none';
                if (text) text.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</x-app-layout>