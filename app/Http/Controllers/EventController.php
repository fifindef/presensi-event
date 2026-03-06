<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\KategoriEvent;

class EventController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EVENT HUB
    |--------------------------------------------------------------------------
    */
    public function hub()
    {
        $user = Auth::user();
        $events = $user->events()->get();

        if (!session()->has('active_event_id') && $events->count() > 0) {
            session(['active_event_id' => $events->first()->id_event]);
        }

        return view('event.hub', compact('events'));
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX EVENT MANAGEMENT
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $events = Event::all();
        $kategoris = KategoriEvent::all();

        return view('event.index', compact('events', 'kategoris'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE EVENT
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'lokasi' => 'required|string|max:255',
            'id_kategori' => 'required'
        ]);

        $event = Event::create([
            'nama_event' => $request->nama_event,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'lokasi' => $request->lokasi,
            'id_kategori' => $request->id_kategori,
            'access_code' => $request->access_code ?? null,
            'user_id' => Auth::id()
        ]);

        $event->users()->attach(Auth::id());

        session(['active_event_id' => $event->id_event]);

        return redirect()->route('dashboard')
            ->with('success', 'Event berhasil dibuat & diaktifkan!');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW EVENT DETAIL
    |--------------------------------------------------------------------------
    */
    public function show(Event $event)
    {
        if (!$event->users->contains(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        return view('event.show', compact('event'));
    }

    /*
    |--------------------------------------------------------------------------
    | JOIN EVENT
    |--------------------------------------------------------------------------
    */
    public function join(Request $request)
    {
        $request->validate([
            'access_code' => 'required'
        ]);

        $event = Event::where('access_code', $request->access_code)->first();

        if (!$event) {
            return back()->with('error', 'Kode akses tidak ditemukan.');
        }

        if (!$event->users()->where('user_id', Auth::id())->exists()) {
            $event->users()->attach(Auth::id());
        }

        session(['active_event_id' => $event->id_event]);

        return redirect()->route('dashboard')
            ->with('success', 'Berhasil join & event diaktifkan!');
    }

    public function create()
    {
        $kategoris = KategoriEvent::all();
        return view('event.create', compact('kategoris'));
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVATE EVENT
    |--------------------------------------------------------------------------
    */
    public function activate($id)
    {
        $user = Auth::user();

        $event = $user->events()
            ->where('events.id_event', $id)
            ->firstOrFail();

        session(['active_event_id' => $event->id_event]);

        return redirect()->route('dashboard')
            ->with('success', 'Event berhasil diganti!');
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL / LEAVE EVENT
    |--------------------------------------------------------------------------
    */
    public function cancel($id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);

        if ($event->users()->where('user_id', $user->id)->exists()) {

            $event->users()->detach($user->id);

            if (session('active_event_id') == $id) {
                session()->forget('active_event_id');
            }

            return redirect()->route('event.hub')
                ->with('success', 'Berhasil membatalkan join event.');
        }

        return redirect()->route('event.hub')
            ->with('error', 'Kamu belum join event ini.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE EVENT (HARUS INPUT ACCESS CODE)
    |--------------------------------------------------------------------------
    */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'access_code_confirm' => 'required'
        ]);

        $event = Event::findOrFail($id);

        // VALIDASI ACCESS CODE
        if ($request->access_code_confirm !== $event->access_code) {
            return redirect('/event')
                ->with('error', 'Access Code salah! Event gagal dihapus.');
        }

        $event->users()->detach();
        $event->delete();

        if (session('active_event_id') == $id) {
            session()->forget('active_event_id');
        }

        return redirect('/event')
            ->with('success', 'Event berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE EVENT (HARUS INPUT ACCESS CODE)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'lokasi' => 'required|string|max:255',
            'id_kategori' => 'required',
            'access_code_confirm' => 'required'
        ]);

        $event = Event::findOrFail($id);

        // VALIDASI ACCESS CODE
        if ($request->access_code_confirm !== $event->access_code) {
            return redirect('/event')
                ->with('error', 'Access Code salah! Event gagal diupdate.');
        }

        $event->update([
            'nama_event' => $request->nama_event,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'lokasi' => $request->lokasi,
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect('/event')
            ->with('success', 'Event berhasil diupdate!');
    }
}