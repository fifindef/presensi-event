<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-xl rounded-2xl w-[450px] p-10 relative">

    <!-- Decoration Blur -->
    <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-100 rounded-full blur-2xl opacity-50"></div>

    <h2 class="text-3xl font-bold mb-8 text-center flex items-center justify-center gap-2">
        📝 Register
    </h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-5 relative z-10">
        @csrf

        <!-- Name -->
        <input type="text"
               name="name"
               placeholder="Name"
               value="{{ old('name') }}"
               required
               class="w-full px-4 py-3 rounded-lg bg-gray-100 focus:ring-2 focus:ring-green-500 border-none">

        @error('name')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <!-- Email -->
        <input type="email"
               name="email"
               placeholder="Email"
               value="{{ old('email') }}"
               required
               class="w-full px-4 py-3 rounded-lg bg-gray-100 focus:ring-2 focus:ring-green-500 border-none">

        @error('email')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <!-- Password -->
        <input type="password"
               name="password"
               placeholder="Password"
               required
               class="w-full px-4 py-3 rounded-lg bg-gray-100 focus:ring-2 focus:ring-green-500 border-none">

        @error('password')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <!-- Confirm Password -->
        <input type="password"
               name="password_confirmation"
               placeholder="Confirm Password"
               required
               class="w-full px-4 py-3 rounded-lg bg-gray-100 focus:ring-2 focus:ring-green-500 border-none">

        @error('password_confirmation')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <!-- Button -->
<button type="submit"
    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-lg font-semibold transition duration-300">
    Register
</button>

        <div class="text-center mt-3">
    <a href="{{ route('login') }}"
       class="text-sm text-blue-600 hover:text-blue-700 font-medium transition duration-300">
        Already registered? Signin
    </a>
</div>

    </form>

</div>

</body>
</html>