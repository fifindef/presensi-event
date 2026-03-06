<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan QR Presensi</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #eef2f7, #dfe7f3);
        }

        .card {
            background: white;
            padding: 45px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            text-align: center;
            width: 420px;
        }

        .scan-icon {
            font-size: 70px;
            margin-bottom: 20px;
        }

        .info {
            font-size: 18px;
            color: #555;
        }

        /* Input disembunyikan tapi tetap aktif */
        #kode_unik {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        /* ================= POPUP ================= */

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
            transition: opacity 0.2s ease;
        }

        .overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .popup {
            background: white;
            padding: 40px;
            border-radius: 18px;
            text-align: center;
            min-width: 350px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            animation: scaleIn 0.2s ease;
        }

        .popup.success h2 {
            color: #2e7d32;
        }

        .popup.error h2 {
            color: #c0392b;
        }

        .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .popup p {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>

<!-- OVERLAY SELALU ADA -->
<div id="overlay" class="overlay"></div>

<!-- CARD SCAN -->
<div class="card">
    <div class="scan-icon">📡</div>
    <h2>Scan QR Presensi</h2>
    <p class="info">Silakan arahkan scanner ke QR Code</p>

    <input 
        type="text"
        id="kode_unik"
        autocomplete="off"
        autofocus>
</div>

<script>
const input = document.getElementById('kode_unik');
const overlay = document.getElementById('overlay');
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// AUTO FOCUS TERUS
document.addEventListener('click', () => input.focus());
window.onload = () => input.focus();

// SAAT ENTER (scanner biasanya kirim ENTER otomatis)
input.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();

        if (input.value.trim() === '') return;

        fetch("{{ route('scan.proses') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                kode_unik: input.value.trim()
            })
        })
        .then(res => res.json())
        .then(data => {
            showPopup(data.status, data.message);
            input.value = '';
            input.focus();
        })
        .catch(error => {
            showPopup('error', 'Terjadi kesalahan sistem');
            input.value = '';
            input.focus();
        });
    }
});

function showPopup(status, message) {

    overlay.innerHTML = `
        <div class="popup ${status}">
            <div class="icon">
                ${status === 'success' ? '✅' : '❌'}
            </div>
            <h2>
                ${status === 'success' ? 'Presensi Berhasil' : 'Presensi Gagal'}
            </h2>
            <p>${message}</p>
        </div>
    `;

    overlay.classList.add('show');

    setTimeout(() => {
        overlay.classList.remove('show');
    }, 2000);
}
</script>

</body>
</html>