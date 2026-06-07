<?php
namespace App\Http\Controllers;

use App\Models\TipeKamar;
use Illuminate\Http\Request;

class TipeKamarController extends Controller
{
    public function index()
    {
        $tipeKamar = TipeKamar::latest()->get();
        return view('tipe-kamar.index', compact('tipeKamar'));
    }

    public function create()
    {
        return view('tipe-kamar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tipe'      => 'required|string|max:100',
            'harga_per_malam'=> 'required|numeric|min:0',
            'deskripsi'      => 'nullable|string',
        ]);

        TipeKamar::create($request->all());
        return redirect()->route('tipe-kamar.index')
                         ->with('success', 'Tipe kamar berhasil ditambahkan!');
    }

    public function edit(TipeKamar $tipeKamar)
    {
        return view('tipe-kamar.edit', compact('tipeKamar'));
    }

    public function update(Request $request, TipeKamar $tipeKamar)
    {
        $request->validate([
            'nama_tipe'      => 'required|string|max:100',
            'harga_per_malam'=> 'required|numeric|min:0',
            'deskripsi'      => 'nullable|string',
        ]);

        $tipeKamar->update($request->all());
        return redirect()->route('tipe-kamar.index')
                         ->with('success', 'Tipe kamar berhasil diupdate!');
    }

    public function destroy(TipeKamar $tipeKamar)
    {
        $tipeKamar->delete();
        return redirect()->route('tipe-kamar.index')
                         ->with('success', 'Tipe kamar berhasil dihapus!');
    }
}