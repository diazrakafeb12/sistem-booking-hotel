<x-app-layout>
    @section('title', 'Tambah Tamu')

    <div style="max-width:700px">
        <div class="card">
            <div class="card-header">
                <h3>👤 Tambah Tamu Baru</h3>
                <a href="{{ route('tamu.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('tamu.store') }}" method="POST">
                    @csrf

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                        <div class="form-group" style="grid-column: span 2">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                   class="form-control" placeholder="Masukkan nama lengkap tamu">
                            @error('nama_lengkap')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">NIK (opsional)</label>
                            <input type="text" name="nik" value="{{ old('nik') }}"
                                   class="form-control" placeholder="16 digit NIK KTP"
                                   maxlength="20">
                            @error('nik')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. HP (opsional)</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                   class="form-control" placeholder="Contoh: 08123456789">
                            @error('no_hp')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group" style="grid-column: span 2">
                            <label class="form-label">Email (opsional)</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="Contoh: tamu@email.com">
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group" style="grid-column: span 2">
                            <label class="form-label">Alamat (opsional)</label>
                            <textarea name="alamat" rows="3" class="form-control"
                                      placeholder="Masukkan alamat lengkap tamu">{{ old('alamat') }}</textarea>
                            @error('alamat')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:flex; gap:10px; margin-top:8px">
                        <button type="submit" class="btn btn-primary">💾 Simpan</button>
                        <a href="{{ route('tamu.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>