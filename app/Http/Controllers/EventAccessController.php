<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventAccessController extends Controller
{
    // Form input kode event
    public function form()
    {
        return view('event.join');
    }

    // Proses join event pakai kode
    public function join(Request $request)
    {
        $request->validate([
            'access_code' => 'required'
        ]);

        $event = Event::where('access_code', $request->access_code)->first();

        if (!$event) {
            return back()->with('error', 'Kode event tidak valid');
        }

        $user = auth()->user();

        // Simpan relasi user <-> event (tanpa duplikat)
        $user->events()->syncWithoutDetaching([$event->id_event]);

        return redirect()->route('dashboard')->with('success', 'Berhasil masuk ke event: ' . $event->nama_event);
    }
}
