<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu;
use App\Models\JenisTamu;
use App\Models\Presensi;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Events\PresensiUpdated;

class TamuController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMU CRUD INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $tamus = Tamu::with('jenisTamu')
            ->orderBy('id_tamu', 'desc')
            ->get();

        $jenisTamus = JenisTamu::orderBy('nama_jenis')->get();

        return view('tamu.index', compact('tamus', 'jenisTamus'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE TAMU
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'nama_tamu' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20|unique:tamus,nomor_hp',
            'id_jenis_tamu' => 'required|exists:jenis_tamus,id_jenis_tamu',
        ]);

        Tamu::create([
            'nama_tamu' => $request->nama_tamu,
            'nomor_hp' => $request->nomor_hp,
            'id_jenis_tamu' => $request->id_jenis_tamu,
        ]);

        return back()->with('success', 'Tamu berhasil ditambahkan!');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TAMU
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $tamu = Tamu::findOrFail($id);

        $request->validate([
            'nama_tamu' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20|unique:tamus,nomor_hp,' .
                $tamu->id_tamu . ',id_tamu',

            'id_jenis_tamu' => 'required|exists:jenis_tamus,id_jenis_tamu',
        ]);

        $tamu->update([
            'nama_tamu' => $request->nama_tamu,
            'nomor_hp' => $request->nomor_hp,
            'id_jenis_tamu' => $request->id_jenis_tamu,
        ]);

        return back()->with('success', 'Data tamu berhasil diperbarui!');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TAMU
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        Tamu::findOrFail($id)->delete();

        return back()->with('success', 'Tamu berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | DAFTAR TAMU (EVENT ACTIVE CONTEXT ⭐)
    |--------------------------------------------------------------------------
    */

    public function daftarTamu()
{
    $user = Auth::user();

    $activeEventId = session('active_event_id');

    if (!$activeEventId) {
        return redirect()->route('event.hub')
            ->with('error', 'Silakan pilih event terlebih dahulu');
    }

    $hasAccess = $user->events()
        ->where('events.id_event', $activeEventId)
        ->exists();

    if (!$hasAccess) {
        abort(403);
    }

    $event = Event::findOrFail($activeEventId);

    $presensis = Presensi::with(['tamu.jenisTamu', 'event'])
        ->where('id_event', $activeEventId)
        ->orderBy('id_presensi', 'desc')
        ->get();

    return view('tamu.daftar', compact('presensis', 'event'));
}

public function search(Request $request)
{
    $search = $request->q;

    $tamus = \App\Models\Tamu::where('nama_tamu', 'like', "%{$search}%")
        ->limit(10)
        ->get();

    return response()->json(
        $tamus->map(function ($t) {
            return [
                'id' => $t->id_tamu,
                'text' => $t->nama_tamu
            ];
        })
    );
}

    /*
    |--------------------------------------------------------------------------
    | QR PRESENSI UPDATE
    |--------------------------------------------------------------------------
    */

    public function hadir($kode_unik)
{
    $presensi = Presensi::with([
            'tamu.jenisTamu',
            'event'
        ])
        ->where('kode_unik', $kode_unik)
        ->firstOrFail();

    if ($presensi->status_hadir) {
        return response()->json([
            'message' => 'Tamu sudah presensi'
        ]);
    }

    $presensi->update([
        'status_hadir' => true,
        'waktu_hadir' => now()
    ]);

    $presensi->refresh()->load(['tamu.jenisTamu', 'event']);

    // 🔥 JANGAN pakai toOthers()
    broadcast(new PresensiUpdated($presensi));

    return response()->json([
        'message' => 'Presensi berhasil',
        'data' => $presensi
    ]);
}
}