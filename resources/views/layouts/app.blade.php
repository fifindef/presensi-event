<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'D3vent') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }

        .content-scroll {
            overflow-y: auto;
            height: 100vh;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.25s ease;
        }

        .sidebar-link:hover {
            background-color: #374151;
            color: white;
        }

        .sidebar-active {
            background-color: #4f46e5;
            color: white !important;
            box-shadow: 0 6px 20px rgba(79,70,229,0.4);
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-screen">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-80 bg-gray-900 text-gray-300 flex flex-col justify-between px-6 py-8">

        <!-- Top -->
        <div>
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-white">
                    {{ config('app.name') }}
                </h2>
                <p class="text-sm text-gray-400">
                    Admin Panel
                </p>
            </div>

            <nav class="space-y-2 text-base">

                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                    📊 Dashboard
                </a>

                <a href="{{ url('kategori-event') }}"
                   class="sidebar-link {{ request()->is('kategori-event*') ? 'sidebar-active' : '' }}">
                    📂 Kategori Event
                </a>

                <a href="{{ url('jenis-tamu') }}"
                   class="sidebar-link {{ request()->is('jenis-tamu*') ? 'sidebar-active' : '' }}">
                    👤 Jenis Tamu
                </a>

                <a href="{{ url('event') }}"
                   class="sidebar-link {{ request()->is('event*') ? 'sidebar-active' : '' }}">
                    📅 Event
                </a>

                <a href="{{ url('tamu') }}"
                   class="sidebar-link {{ request()->is('tamu*') ? 'sidebar-active' : '' }}">
                    👥 Tamu
                </a>

                <a href="{{ url('scan') }}"
                   class="sidebar-link {{ request()->is('scan*') ? 'sidebar-active' : '' }}">
                    📷 Scan
                </a>

                <a href="{{ url('presensi') }}"
                   class="sidebar-link {{ request()->is('presensi*') ? 'sidebar-active' : '' }}">
                    ✅ Barcode 
                </a>

                <a href="{{ route('tamu.daftar') }}"
                   class="sidebar-link {{ request()->is('daftar-tamu*') ? 'sidebar-active' : '' }}">
                    👥 Daftar Tamu
                </a>

            </nav>
        </div>

        <!-- Bottom User -->
        <div class="border-t border-gray-700 pt-6">
            <div class="mb-4">
                <div class="font-semibold text-white">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-sm text-gray-400">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-xl font-semibold transition">
                    Logout
                </button>
            </form>
        </div>

    </aside>


    <!-- ================= CONTENT ================= -->
    <div class="flex-1 content-scroll p-10">

        @isset($header)
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-800">
                {{ $header }}
            </h1>
        </div>
        @endisset

        {{ $slot }}

    </div>

</div>

@stack('scripts')
</body>
</html>