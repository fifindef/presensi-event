<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisTamu;

class JenisTamuController extends Controller
{
    // Tampilkan halaman + daftar jenis tamu
    public function index()
    {
        $jenisTamus = JenisTamu::orderBy('id_jenis_tamu', 'desc')->get();
        return view('jenis_tamu.index', compact('jenisTamus'));
    }

    // Simpan data jenis tamu baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255'
        ]);

        JenisTamu::create([
            'nama_jenis' => $request->nama_jenis
        ]);

        return redirect()->back()->with('success', 'Jenis tamu berhasil ditambahkan!');
    }

    // Tampilkan form edit jenis tamu
    public function edit($id)
    {
        $jenisTamu = JenisTamu::findOrFail($id);
        return view('jenis_tamu.edit', compact('jenisTamu'));
    }

    // Update data jenis tamu
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255'
        ]);

        $jenisTamu = JenisTamu::findOrFail($id);
        $jenisTamu->update([
            'nama_jenis' => $request->nama_jenis
        ]);

        return redirect()->route('jenis-tamu.index')->with('success', 'Jenis tamu berhasil diperbarui!');
    }

    // Hapus data jenis tamu
    public function destroy($id)
    {
        $jenisTamu = JenisTamu::findOrFail($id);
        $jenisTamu->delete();

        return redirect()->route('jenis-tamu.index')->with('success', 'Jenis tamu berhasil dihapus!');
    }
}