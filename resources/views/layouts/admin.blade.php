<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Anime Song Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar-item {
            position: relative;
            transition: all 0.3s ease;
        }
        .sidebar-item:hover, .sidebar-item.active {
            color: white;
            background-color: rgba(157, 0, 255, 0.1);
            text-shadow: 0 0 10px rgba(157, 0, 255, 0.8);
        }
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #9D00FF;
            box-shadow: 0 0 10px #9D00FF;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(157, 0, 255, 0.3);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(157, 0, 255, 0.5);
        }
    </style>
</head>
<body class="bg-[#0D0D12] text-gray-300 font-sans leading-normal tracking-normal flex h-screen overflow-hidden selection:bg-[#9D00FF] selection:text-white">

    <!-- Sidebar -->
    <aside class="w-64 bg-black border-r border-[#9D00FF]/30 shadow-[4px_0_15px_rgba(157,0,255,0.1)] flex flex-col h-full z-20 shrink-0">
        <div class="p-6 border-b border-gray-800 text-center">
            <h1 class="font-black text-2xl tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500 drop-shadow-md">
                ANI<span class="text-[#9D00FF]">SONG</span>.
            </h1>
            <p class="text-xs text-[#9D00FF] font-bold tracking-widest uppercase mt-1">Admin Panel</p>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4 flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item px-6 py-3 font-semibold {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-400' }} flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.franchises.index') }}" class="sidebar-item px-6 py-3 font-semibold {{ request()->routeIs('admin.franchises.*') ? 'active' : 'text-gray-400' }} flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Franchise
            </a>
            <a href="{{ route('admin.songs.type', 'opening') }}" class="sidebar-item px-6 py-3 font-semibold {{ request()->routeIs('admin.songs.*') ? 'active' : 'text-gray-400' }} flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                Manajemen Lagu
            </a>
            <a href="{{ route('admin.guest_rates.index') }}" class="sidebar-item px-6 py-3 font-semibold {{ request()->routeIs('admin.guest_rates.*') ? 'active' : 'text-gray-400' }} flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                Guest Rates
            </a>
        </nav>

        <div class="p-6 border-t border-gray-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-gray-800 text-gray-300 py-2 px-4 rounded-lg font-bold hover:bg-red-900/50 hover:text-red-400 hover:border-red-500/50 border border-transparent transition-all shadow-md flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 h-full overflow-y-auto custom-scrollbar relative">
        <!-- Interactive Glowing Background Blob -->
        <div class="pointer-events-none fixed top-0 right-0 w-[500px] h-[500px] bg-[#9D00FF]/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 z-0"></div>

        <div class="p-8 relative z-10 w-full max-w-7xl mx-auto">
            @if (session('success'))
                <div class="bg-green-900/40 border border-green-500/50 text-green-400 px-4 py-3 rounded-xl relative mb-6 shadow-[0_0_15px_rgba(34,197,94,0.2)] flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-900/40 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl relative mb-6 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
