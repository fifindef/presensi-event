{{-- resources/views/event/show.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">

    <a href="{{ route('event.hub') }}" class="btn btn-secondary mb-4">
        ⬅ Kembali ke Event Hub
    </a>

    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="mb-3">{{ $event->nama_event }}</h3>

            <p>
                <strong>Tanggal:</strong>
                {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }} <br>
                <strong>Jam:</strong> {{ $event->jam }} <br>
                <strong>Lokasi:</strong> {{ $event->lokasi }}
            </p>

            <hr>

            <h5 class="mb-3">Menu Event</h5>

            <a href="{{ route('tamu.index', $event->id_event) }}"
               class="btn btn-outline-primary me-2">
                👥 Daftar Tamu
            </a>

            <a href="{{ route('presensi.index', $event->id_event) }}"
               class="btn btn-outline-success me-2">
                ✅ Presensi
            </a>
        </div>
    </div>

</div>
</body>
</html>