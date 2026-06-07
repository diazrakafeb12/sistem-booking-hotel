<x-app-layout>
    @section('title', 'Edit Tamu')

    <div style="max-width:700px">
        <div class="card">
            <div class="card-header">
                <h3>✏️ Edit Data Tamu</h3>
                <a href="{{ route('tamu.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('tamu.update', $tamu->id_tamu) }}" method="POST">
                    @csrf @method('PUT')

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                        <div class="form-group" style="grid-column: span 2">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap"
                                   value="{{ old('nama_lengkap', $tamu->nama_lengkap) }}"
                                   class="form-control">
                            @error('nama_lengkap')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik"
                                   value="{{ old('nik', $tamu->nik) }}"
                                   class="form-control" maxlength="20">
                            @error('nik')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp"
                                   value="{{ old('no_hp', $tamu->no_hp) }}"
                                   class="form-control">
                            @error('no_hp')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group" style="grid-column: span 2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $tamu->email) }}"
                                   class="form-control">
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group" style="grid-column: span 2">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" rows="3" class="form-control">{{ old('alamat', $tamu->alamat) }}</textarea>
                            @error('alamat')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:flex; gap:10px; margin-top:8px">
                        <button type="submit" class="btn btn-warning">💾 Update</button>
                        <a href="{{ route('tamu.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>