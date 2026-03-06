<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tamu - {{ $event->nama_event }}</title>

    <style>
        body { font-family: Arial, sans-serif; background: #f5f6fa; padding: 20px; }
        h2 { margin-bottom: 20px; }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th { background: #2d98da; color: white; }

        .status-hadir { color: green; font-weight: bold; }
        .status-belum { color: red; font-weight: bold; }

        /* ===== Popup ===== */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }

        .overlay.show { opacity: 1; pointer-events: auto; }

        .popup {
            background: white;
            padding: 36px 40px;
            border-radius: 18px;
            text-align: center;
            min-width: 320px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            transform: scale(0.7) translateY(20px);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .overlay.show .popup {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .check {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: #4CAF50;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            animation: bounce 0.6s ease;
        }

        @keyframes bounce {
            0% {transform: scale(0.3);}
            50% {transform: scale(1.1);}
            70% {transform: scale(0.95);}
            100% {transform: scale(1);}
        }

        .popup h2 { margin: 0 0 8px; color: #2e7d32; font-size: 24px; }
        .popup p { margin: 0; font-size: 20px; font-weight: bold; }
        .popup small { display: block; margin-top: 8px; color: #777; }
    </style>
</head>
<body>

<h2>📋 Daftar Tamu & Presensi: {{ $event->nama_event }}</h2>

<!-- Popup -->
<div id="overlay" class="overlay">
    <div class="popup">
        <div class="check">✓</div>
        <h2>Presensi Berhasil</h2>
        <p id="popup-nama">Selamat datang!</p>
        <small>Data presensi berhasil diperbarui</small>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Tamu</th>
            <th>Jenis Tamu</th>
            <th>Status Hadir</th>
            <th>Tanggal Hadir</th>
            <th>Jam Hadir</th>
        </tr>
    </thead>
    <tbody id="presensi-body">
        @foreach($presensis as $index => $p)
            <tr data-id="{{ $p->id_presensi }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->tamu->nama_tamu ?? '-' }}</td>
                <td>{{ $p->tamu->jenisTamu->nama_jenis ?? '-' }}</td>
                <td>
                    @if($p->status_hadir)
                        <span class="status-hadir">Hadir</span>
                    @else
                        <span class="status-belum">Belum Hadir</span>
                    @endif
                </td>
                <td>{{ $p->waktu_hadir ? $p->waktu_hadir->format('d-m-Y') : '-' }}</td>
                <td>{{ $p->waktu_hadir ? $p->waktu_hadir->format('H:i:s') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
const currentEventId = {{ $event->id_event }};
let lastUpdateIds = new Set();

// ===== FIX: Tandai semua yang SUDAH hadir saat halaman pertama dibuka =====
document.querySelectorAll("tr[data-id]").forEach(row => {
    const statusCell = row.children[3];

    if (statusCell.innerText.trim() === "Hadir") {
        const id = parseInt(row.getAttribute("data-id"));
        lastUpdateIds.add(id);
    }
});

// Popup
function showPopup(nama) {
    const overlay = document.getElementById("overlay");
    const namaEl = document.getElementById("popup-nama");
    namaEl.textContent = "✅ " + nama + " telah absen";
    overlay.classList.add("show");
    setTimeout(() => overlay.classList.remove("show"), 2500);
}

// Realtime AJAX Refresh
function loadPresensi() {
    fetch("{{ route('presensi.fetch') }}?id_event=" + currentEventId)
        .then(res => res.json())
        .then(data => {

            data.forEach(p => {

                const row = document.querySelector(`tr[data-id='${p.id_presensi}']`);

                if (row) {

                    // Jika baru berubah jadi hadir
                    if (p.status_hadir == 1 && !lastUpdateIds.has(p.id_presensi)) {

    row.children[3].innerHTML =
        '<span class="status-hadir">Hadir</span>';

    const waktu = new Date(p.waktu_hadir);

    // 🔥 FORMAT TANGGAL dd-mm-yyyy
    const day = String(waktu.getDate()).padStart(2, '0');
    const month = String(waktu.getMonth() + 1).padStart(2, '0');
    const year = waktu.getFullYear();

    row.children[4].innerText = `${day}-${month}-${year}`;

    // 🔥 FORMAT JAM HH:mm:ss
    const hours = String(waktu.getHours()).padStart(2, '0');
    const minutes = String(waktu.getMinutes()).padStart(2, '0');
    const seconds = String(waktu.getSeconds()).padStart(2, '0');

    row.children[5].innerText = `${hours}:${minutes}:${seconds}`;

    showPopup(p.tamu.nama_tamu);

    lastUpdateIds.add(p.id_presensi);
}
                }
            });

        })
        .catch(err => console.error(err));
}

// Load pertama
loadPresensi();

// Refresh tiap 2.5 detik
setInterval(loadPresensi, 2500);
</script>

</body>
</html>