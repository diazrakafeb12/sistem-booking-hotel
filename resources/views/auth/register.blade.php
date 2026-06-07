<x-guest-layout>
    <div style="text-align:center; margin-bottom:24px">
        <div style="font-size:32px; margin-bottom:8px">🏨</div>
        <h2 style="font-size:20px; font-weight:800; color:#0d2137">Buat Akun Customer</h2>
        <p style="font-size:13px; color:#888; margin-top:4px">Daftar untuk mulai memesan kamar hotel</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama Lengkap --}}
        <div style="margin-bottom:16px">
            <label style="font-size:12px; font-weight:700; color:#334155; display:block; margin-bottom:6px">
                👤 Nama Lengkap
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                style="width:100%; padding:10px 14px; border:1.5px solid #dde3ed; border-radius:8px; font-size:13.5px; color:#334155; outline:none; box-sizing:border-box"
                placeholder="Masukkan nama lengkap Anda">
            @error('name')
                <div style="color:#e74c3c; font-size:12px; margin-top:4px">{{ $message }}</div>
            @enderror
        </div>

        {{-- Username --}}
        <div style="margin-bottom:16px">
            <label style="font-size:12px; font-weight:700; color:#334155; display:block; margin-bottom:6px">
                🪪 Username
            </label>
            <input type="text" name="username" value="{{ old('username') }}" required
                style="width:100%; padding:10px 14px; border:1.5px solid #dde3ed; border-radius:8px; font-size:13.5px; color:#334155; outline:none; box-sizing:border-box"
                placeholder="Buat username unik Anda">
            @error('username')
                <div style="color:#e74c3c; font-size:12px; margin-top:4px">{{ $message }}</div>
            @enderror
        </div>

        {{-- No. Telepon --}}
        <div style="margin-bottom:16px">
            <label style="font-size:12px; font-weight:700; color:#334155; display:block; margin-bottom:6px">
                📞 No. Telepon
            </label>
            <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                style="width:100%; padding:10px 14px; border:1.5px solid #dde3ed; border-radius:8px; font-size:13.5px; color:#334155; outline:none; box-sizing:border-box"
                placeholder="Contoh: 081234567890">
            @error('no_telepon')
                <div style="color:#e74c3c; font-size:12px; margin-top:4px">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div style="margin-bottom:16px">
            <label style="font-size:12px; font-weight:700; color:#334155; display:block; margin-bottom:6px">
                📧 Email
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required
                style="width:100%; padding:10px 14px; border:1.5px solid #dde3ed; border-radius:8px; font-size:13.5px; color:#334155; outline:none; box-sizing:border-box"
                placeholder="email@contoh.com">
            @error('email')
                <div style="color:#e74c3c; font-size:12px; margin-top:4px">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div style="margin-bottom:16px">
            <label style="font-size:12px; font-weight:700; color:#334155; display:block; margin-bottom:6px">
                🔒 Password
            </label>
            <input type="password" name="password" required autocomplete="new-password"
                style="width:100%; padding:10px 14px; border:1.5px solid #dde3ed; border-radius:8px; font-size:13.5px; color:#334155; outline:none; box-sizing:border-box"
                placeholder="Minimal 8 karakter">
            @error('password')
                <div style="color:#e74c3c; font-size:12px; margin-top:4px">{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div style="margin-bottom:24px">
            <label style="font-size:12px; font-weight:700; color:#334155; display:block; margin-bottom:6px">
                🔒 Konfirmasi Password
            </label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                style="width:100%; padding:10px 14px; border:1.5px solid #dde3ed; border-radius:8px; font-size:13.5px; color:#334155; outline:none; box-sizing:border-box"
                placeholder="Ulangi password Anda">
        </div>

        {{-- Submit --}}
        <button type="submit"
            style="width:100%; padding:12px; background:#2e86de; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer">
            🚀 Daftar Sekarang
        </button>

        <div style="text-align:center; margin-top:16px; font-size:13px; color:#888">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:#2e86de; font-weight:600; text-decoration:none">Masuk di sini</a>
        </div>
    </form>
</x-guest-layout>