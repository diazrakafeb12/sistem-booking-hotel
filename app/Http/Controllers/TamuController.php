<?php
namespace App\Http\Controllers;

use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function index()
{
    $bookings = \App\Models\Booking::with(['tamu', 'kamar.tipeKamar'])
                ->latest()
                ->get();
    return view('tamu.index', compact('bookings'));
}

    public function create()
    {
        return view('tamu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik'          => 'nullable|string|max:20|unique:tamu,nik',
            'no_hp'        => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:100',
            'alamat'       => 'nullable|string',
        ]);

        Tamu::create($request->all());
        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil ditambahkan!');
    }

    public function edit(Tamu $tamu)
    {
        return view('tamu.edit', compact('tamu'));
    }

    public function update(Request $request, Tamu $tamu)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik'          => 'nullable|string|max:20|unique:tamu,nik,' . $tamu->id_tamu . ',id_tamu',
            'no_hp'        => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:100',
            'alamat'       => 'nullable|string',
        ]);

        $tamu->update($request->all());
        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diupdate!');
    }

    public function destroy(Tamu $tamu)
    {
        $tamu->delete();
        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil dihapus!');
    }
}