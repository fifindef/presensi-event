<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeEventId = session('active_event_id');

        /*
        |--------------------------------------------------------------------------
        | AUTO SET EVENT AKTIF JIKA BELUM ADA
        |--------------------------------------------------------------------------
        */

        if (!$activeEventId) {

            $firstEvent = $user->events()->first();

            if ($firstEvent) {
                session(['active_event_id' => $firstEvent->id_event]);
                return redirect()->route('dashboard');
            }

            // Jika user belum punya event sama sekali
            return view('dashboard', [
                'summary' => [
                    'totalEvent' => 0,
                    'totalTamu' => 0,
                    'totalUndangan' => 0,
                    'totalHadir' => 0,
                ],
                'event' => null
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL EVENT AKTIF MILIK USER
        |--------------------------------------------------------------------------
        */

        $event = $user->events()
            ->select('events.*')
            ->where('events.id_event', $activeEventId)
            ->first();

        if (!$event) {

            // Jika event sudah tidak valid / tidak punya akses lagi
            session()->forget('active_event_id');

            return redirect()->route('dashboard')
                ->with('error', 'Event aktif tidak valid.');
        }

        /*
        |--------------------------------------------------------------------------
        | HITUNG SUMMARY EVENT AKTIF SAJA
        |--------------------------------------------------------------------------
        */

        $totalTamu = $event->presensis()
            ->distinct('id_tamu')
            ->count('id_tamu');

        $totalUndangan = $event->presensis()->count();

        $totalHadir = $event->presensis()
            ->where('status_hadir', 1)
            ->count();

        $summary = [
            'totalEvent' => 1,
            'totalTamu' => $totalTamu,
            'totalUndangan' => $totalUndangan,
            'totalHadir' => $totalHadir,
        ];

        return view('dashboard', compact(
            'summary',
            'event'
        ));
    }
}