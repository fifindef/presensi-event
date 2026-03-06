<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriEvent;

class KategoriEventController extends Controller
{
    // Menampilkan semua kategori
    public function index()
    {
        $kategori = KategoriEvent::orderBy('id_kategori', 'desc')->get();
        return view('kategori_event.index', compact('kategori'));
    }

    // Menambahkan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        KategoriEvent::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori event berhasil ditambahkan!');
    }

    // Update kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        $kategori = KategoriEvent::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori event berhasil diupdate!');
    }

    // Hapus kategori
    public function destroy($id)
    {
        $kategori = KategoriEvent::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori event berhasil dihapus!');
    }
}