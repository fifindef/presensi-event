<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'D3vent') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        /* Card glass effect */
        .card-glass {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(25px);
            border-radius: 2rem;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background circles */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 7s ease-in-out infinite alternate;
        }
        .circle-purple { width: 20rem; height: 20rem; background: #8B5CF6; top: -5rem; left: -5rem; }
        .circle-pink { width: 18rem; height: 18rem; background: #EC4899; bottom: -5rem; right: -5rem; animation-delay: 2s; }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(25px) rotate(45deg); }
        }

        /* Logo animation */
        .logo-animate {
            animation: spin 10s linear infinite;
            transition: transform 0.3s;
        }
        .logo-animate:hover { transform: scale(1.1) rotate(360deg); }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Gradient text */
        .text-gradient {
            background: linear-gradient(90deg, #FDE68A, #F472B6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Stylish button */
        .btn-modern {
            display: inline-block;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            border-radius: 9999px;
            background: linear-gradient(90deg, #EC4899, #8B5CF6);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-modern:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 min-h-screen flex items-center justify-center">

    <div class="relative w-full sm:max-w-md card-glass text-white text-center">

        <!-- Background circles -->
        <div class="bg-circle circle-purple"></div>
        <div class="bg-circle circle-pink"></div>

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <x-application-logo class="w-24 h-24 logo-animate" />
        </div>

        <!-- Slot Konten -->
        <div class="space-y-4">
            {{ $slot }}

            <a href="#" class="btn-modern mt-6">Mulai</a>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-white/70 text-xs">
            &copy; {{ date('Y') }} <span class="text-gradient">{{ config('app.name') }}</span>. All rights reserved.
        </div>

    </div>

</body>
</html>