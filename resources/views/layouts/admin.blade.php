<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Anime Song Management System</title>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <nav class="bg-blue-800 p-4 shadow-md text-white flex justify-between items-center">
        <div class="font-bold text-xl">Admin Panel - Anisong</div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="px-3 hover:text-gray-300">Dashboard</a>
            <a href="{{ route('admin.franchises.index') }}" class="px-3 hover:text-gray-300">Franchises</a>
            <a href="{{ route('admin.songs.index') }}" class="px-3 hover:text-gray-300">Songs</a>
            <a href="{{ route('admin.guest_rates.index') }}" class="px-3 hover:text-gray-300">Guest Rates</a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="ml-4 px-3 py-1 bg-red-600 rounded hover:bg-red-700">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container mx-auto mt-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>
