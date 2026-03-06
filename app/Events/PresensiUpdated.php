<?php

namespace App\Events;

use App\Models\Presensi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresensiUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $presensi;

    public function __construct(Presensi $presensi)
    {
        $this->presensi = $presensi;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('presensi-channel');
    }

    public function broadcastAs(): string
    {
        return 'PresensiUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'id_event'    => $this->presensi->id_event,
            'nama_tamu'   => $this->presensi->tamu->nama_tamu,
            'waktu_hadir' => $this->presensi->waktu_hadir,
        ];
    }
}
