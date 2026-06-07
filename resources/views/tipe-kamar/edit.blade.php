<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Tipe Kamar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('tipe-kamar.update', $tipeKamar->id_tipe) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Nama Tipe Kamar</label>
                        <input type="text" name="nama_tipe"
                               value="{{ old('nama_tipe', $tipeKamar->nama_tipe) }}"
                               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('nama_tipe')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Harga per Malam (Rp)</label>
                        <input type="number" name="harga_per_malam"
                               value="{{ old('harga_per_malam', $tipeKamar->harga_per_malam) }}"
                               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('harga_per_malam')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block font-semibold mb-1">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" rows="3"
                                  class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('deskripsi', $tipeKamar->deskripsi) }}</textarea>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="bg-yellow-500 text-white px-5 py-2 rounded hover:bg-yellow-600">
                            Update
                        </button>
                        <a href="{{ route('tipe-kamar.index') }}"
                           class="bg-gray-400 text-white px-5 py-2 rounded hover:bg-gray-500">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>