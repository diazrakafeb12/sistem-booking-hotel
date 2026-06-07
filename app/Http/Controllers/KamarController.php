<?php
namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\TipeKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    public function index(Request $request)
{
    $tipeKamar = TipeKamar::orderBy('nama_tipe')->get();

    $kamar = Kamar::with('tipeKamar')
        ->when($request->tipe, fn($q) => $q->where('id_tipe', $request->tipe))
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->latest()
        ->get();

    return view('kamar.index', compact('kamar', 'tipeKamar'));
}

    public function create()
    {
        $tipeKamar = TipeKamar::orderBy('nama_tipe')->get();
        return view('kamar.create', compact('tipeKamar'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nomor_kamar' => 'required|string|max:10|unique:kamar,nomor_kamar',
        'id_tipe'     => 'required|exists:tipe_kamar,id_tipe',
        'lantai'      => 'nullable|integer',
        'kapasitas'   => 'required|integer|min:1',
        'status'      => 'required|in:tersedia,terisi,maintenance',
        'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'foto_2'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'foto_3'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->except(['foto', 'foto_2', 'foto_3']);

    foreach (['foto', 'foto_2', 'foto_3'] as $f) {
        if ($request->hasFile($f)) {
            $data[$f] = $request->file($f)->store('kamar', 'public');
        }
    }

    Kamar::create($data);
    return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil ditambahkan!');
}


    public function edit(Kamar $kamar)
    {
        $tipeKamar = TipeKamar::orderBy('nama_tipe')->get();
        return view('kamar.edit', compact('kamar', 'tipeKamar'));
    }

    public function update(Request $request, Kamar $kamar)
{
    $request->validate([
        'nomor_kamar' => 'required|string|max:10|unique:kamar,nomor_kamar,' . $kamar->id_kamar . ',id_kamar',
        'id_tipe'     => 'required|exists:tipe_kamar,id_tipe',
        'lantai'      => 'nullable|integer',
        'kapasitas'   => 'required|integer|min:1',
        'status'      => 'required|in:tersedia,terisi,maintenance',
        'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'foto_2'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'foto_3'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->except(['foto', 'foto_2', 'foto_3']);

    foreach (['foto', 'foto_2', 'foto_3'] as $f) {
        if ($request->hasFile($f)) {
            if ($kamar->$f) Storage::disk('public')->delete($kamar->$f);
            $data[$f] = $request->file($f)->store('kamar', 'public');
        }
    }

    $kamar->update($data);
    return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil diupdate!');
}

    public function destroy(Kamar $kamar)
    {
        if ($kamar->foto) Storage::disk('public')->delete($kamar->foto);
        $kamar->delete();
        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil dihapus!');
    }
}