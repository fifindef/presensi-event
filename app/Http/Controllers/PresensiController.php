<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Event;
use App\Models\Tamu;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Events\PresensiUpdated;

class PresensiController extends Controller
{
    // =====================================================
    // HALAMAN PRESENSI (FILTER BERDASARKAN EVENT USER)
    // =====================================================
    public function index(Request $request)
{
    $user = auth()->user();

    // Event yang user join
    $events = $user->events()->with('kategori')->get();

    // 🔥 PRIORITAS: request → session
    $selectedEventId = $request->id_event 
        ?? session('active_event_id');

    // 🔥 Jika ada event aktif dari dashboard, simpan lagi
    if ($selectedEventId) {
        session(['active_event_id' => $selectedEventId]);
    }

    // Tamu tampil semua
    $tamus = Tamu::all();

    $presensis = collect();

    if ($selectedEventId) {

        if (!$events->pluck('id_event')->contains($selectedEventId)) {
            abort(403, 'Anda tidak memiliki akses ke event ini');
        }

        $presensis = Presensi::with(['event.kategori', 'tamu'])
            ->where('id_event', $selectedEventId)
            ->orderBy('id_presensi', 'desc')
            ->get();
    }

    return view('presensi.index', compact(
        'events',
        'presensis',
        'selectedEventId',
        'tamus'
    ));
}

    // =====================================================
    // FETCH DATA UNTUK AJAX (FILTER BERDASARKAN EVENT)
    // =====================================================
    public function fetchData(Request $request)
    {
        $user = auth()->user();

        $query = Presensi::with([
            'event.kategori',
            'tamu.jenisTamu'
        ])->orderBy('id_presensi', 'desc');

        if ($request->id_event) {

            // 🔥 FIXED DI SINI (pakai events.id_event)
            if (!$user->events()
                    ->where('events.id_event', $request->id_event)
                    ->exists()) {
                return response()->json([], 403);
            }

            $query->where('id_event', $request->id_event);
        }

        return response()->json($query->get());
    }

    // =====================================================
    // SIMPAN PRESENSI + GENERATE QR (VALIDASI EVENT USER)
    // =====================================================
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id_event' => 'required',
            'id_tamu'  => 'required',
        ]);

        // 🔥 FIXED DI SINI (pakai events.id_event)
        if (!$user->events()
                ->where('events.id_event', $request->id_event)
                ->exists()) {
            abort(403, 'Anda tidak memiliki akses ke event ini');
        }

        $event = Event::with('kategori')->findOrFail($request->id_event);

        // Prefix kategori
        $namaKategori = $event->kategori->nama_kategori;
        $prefix = strtoupper(substr($namaKategori, 0, 4));

        // Simpan dulu untuk dapat ID
        $presensi = Presensi::create([
            'id_event'     => $request->id_event,
            'id_tamu'      => $request->id_tamu,
            'kode_unik'    => 'TEMP',
            'qr_code'      => null,
            'status_hadir' => 0,
        ]);

        // Generate kode unik
        $tanggal = Carbon::now()->format('Ymd');
        $kodeUnik = $prefix . '-' . $tanggal . '-' . $presensi->id_presensi;

        $presensi->update([
            'kode_unik' => $kodeUnik
        ]);

        // Generate QR Code (SVG)
        $qrImage = QrCode::format('svg')->size(300)->generate($kodeUnik);

        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0755, true);
        }

        $fileName = 'qrcodes/presensi_' . $presensi->id_presensi . '.svg';
        file_put_contents(public_path($fileName), $qrImage);

        $presensi->update([
            'qr_code' => $fileName
        ]);

        return redirect()->route('presensi.index', [
            'id_event' => $request->id_event
        ])->with('success', 'Presensi & QR Code berhasil dibuat!');
    }

    // =====================================================
    // HALAMAN SCAN QR
    // =====================================================
    public function scan()
    {
        return view('presensi.scan');
    }

    // =====================================================
    // PROSES HASIL SCAN QR
    // =====================================================
public function prosesScan(Request $request)
{
    $request->validate([
        'kode_unik' => 'required'
    ]);

    $presensi = Presensi::with(['tamu'])
        ->where('kode_unik', $request->kode_unik)
        ->first();

    if (!$presensi) {
        return response()->json([
            'status' => 'error',
            'message' => 'QR Code tidak ditemukan!'
        ]);
    }

    if ($presensi->status_hadir == 1) {
        return response()->json([
            'status' => 'error',
            'message' => $presensi->tamu->nama_tamu . ' sudah melakukan presensi!'
        ]);
    }

    $presensi->update([
        'status_hadir' => 1,
        'waktu_hadir'  => now()
    ]);

    broadcast(new PresensiUpdated($presensi));

    return response()->json([
        'status' => 'success',
        'message' => 'Selamat datang, ' . $presensi->tamu->nama_tamu
    ]);
}




    public function destroy($id)
{
    $presensi = Presensi::findOrFail($id);

    if ($presensi->qr_code && file_exists(public_path($presensi->qr_code))) {
        unlink(public_path($presensi->qr_code));
    }

    $presensi->delete();

    return redirect()->back()->with('success', 'Data berhasil dihapus');
}
}