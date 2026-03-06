<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>D3vent - Sistem Presensi Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        .navbar {
            padding: 20px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 24px;
            letter-spacing: 1px;
        }

        .hero {
            padding: 120px 0 100px 0;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.3;
        }

        .hero p {
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 35px;
            color: rgba(255,255,255,0.85);
        }

        .btn-main {
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .btn-primary-custom {
            background: white;
            color: #1e3c72;
            border: none;
        }

        .btn-primary-custom:hover {
            background: #f1f1f1;
        }

        .btn-outline-custom {
            border: 1px solid white;
            color: white;
        }

        .btn-outline-custom:hover {
            background: white;
            color: #1e3c72;
        }

        .feature-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 35px;
            border-radius: 20px;
            transition: 0.3s ease;
            height: 100%;
        }

        .feature-box:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.15);
        }

        .feature-box h5 {
            font-weight: 700;
            margin-bottom: 15px;
        }

        .feature-box p {
            font-size: 15px;
            color: rgba(255,255,255,0.85);
        }

        footer {
            margin-top: 100px;
            padding: 25px;
            text-align: center;
            background: rgba(0,0,0,0.25);
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark container">
    <a class="navbar-brand" href="#">D3vent</a>

    <div class="ms-auto">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm">Register</a>
                @endif
            @endauth
        @endif
    </div>
</nav>

<!-- Hero Section -->
<div class="container hero text-center">
    <h1>Solusi Presensi Event Modern dengan QR Code</h1>

    <p>
        <strong>D3vent</strong> membantu Anda mengelola event, tamu, dan presensi
        dengan sistem QR Code yang cepat, aman, dan profesional.
    </p>

    <a href="{{ route('login') }}" class="btn btn-primary-custom btn-main me-3">
        Mulai Sekarang
    </a>

    <a href="#fitur" class="btn btn-outline-custom btn-main">
        Lihat Fitur
    </a>
</div>

<!-- Fitur -->
<div class="container text-center mt-5" id="fitur">
    <div class="row g-4">

        <div class="col-md-4">
            <div class="feature-box">
                <h5>Manajemen Event</h5>
                <p>Buat dan kelola berbagai jenis event dengan mudah dan terstruktur.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-box">
                <h5>Data Tamu Terintegrasi</h5>
                <p>Kelola data peserta dengan sistem yang rapi dan scalable.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-box">
                <h5>QR Code Otomatis</h5>
                <p>Generate dan gunakan QR Code untuk presensi cepat dan akurat.</p>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<footer>
    © 2026 D3vent — Smart Event Attendance System
</footer>

</body>
</html>