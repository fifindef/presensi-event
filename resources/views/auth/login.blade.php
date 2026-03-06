<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-xl rounded-2xl w-[450px] p-10 relative">

    <!-- Decoration Blur -->
    <div class="absolute -top-10 -left-10 w-32 h-32 bg-green-100 rounded-full blur-2xl opacity-50"></div>

    <h2 class="text-3xl font-bold mb-8 text-center flex items-center justify-center gap-2">
        🔐 Signin
    </h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-6 relative z-10">
        @csrf

        <!-- Email -->
        <div class="relative">
            <input type="email"
                   name="email"
                   placeholder="Email"
                   value="{{ old('email') }}"
                   required
                   class="w-full px-4 py-3 rounded-lg bg-gray-100 focus:ring-2 focus:ring-green-500 border-none">
        </div>

        @error('email')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <!-- Password -->
        <div class="relative">
            <input type="password"
                   name="password"
                   placeholder="Password"
                   required
                   class="w-full px-4 py-3 rounded-lg bg-gray-100 focus:ring-2 focus:ring-green-500 border-none">
        </div>

        @error('password')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <!-- Button -->
<button type="submit"
    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-lg font-semibold transition duration-300">
    Signin
</button>


    </form>

</div>

</body>
</html>