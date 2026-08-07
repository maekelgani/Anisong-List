@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">Guest Rates</h1>
        <p class="text-gray-400 mt-1">Daftar sesi rating yang telah diselesaikan oleh Guest.</p>
    </div>
</div>

<!-- Filter Form -->
<form method="GET" action="{{ route('admin.guest_rates.index') }}" class="mb-6 bg-gray-900/60 p-5 rounded-2xl border border-gray-800 flex flex-col gap-4 shadow-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guest..." class="w-full bg-black/50 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] outline-none transition-all">
        </div>
        
        <!-- Tipe Rate -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tipe Rate</label>
            <select name="tipe_rate" class="w-full bg-black/50 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] outline-none appearance-none transition-all">
                <option value="">Semua Tipe</option>
                <option value="opening" {{ request('tipe_rate') == 'opening' ? 'selected' : '' }}>Opening</option>
                <option value="ending" {{ request('tipe_rate') == 'ending' ? 'selected' : '' }}>Ending</option>
                <option value="movie" {{ request('tipe_rate') == 'movie' ? 'selected' : '' }}>Movie</option>
                <option value="all" {{ request('tipe_rate') == 'all' ? 'selected' : '' }}>All (Campur)</option>
            </select>
        </div>

        <!-- Limit Top -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Limit Top</label>
            <select name="limit_top" class="w-full bg-black/50 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] outline-none appearance-none transition-all">
                <option value="">Semua Limit</option>
                <option value="10" {{ request('limit_top') == '10' ? 'selected' : '' }}>Top 10</option>
                <option value="25" {{ request('limit_top') == '25' ? 'selected' : '' }}>Top 25</option>
                <option value="50" {{ request('limit_top') == '50' ? 'selected' : '' }}>Top 50</option>
                <option value="100" {{ request('limit_top') == '100' ? 'selected' : '' }}>Top 100</option>
            </select>
        </div>

        <!-- Order -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Urutkan</label>
            <select name="order" class="w-full bg-black/50 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] outline-none appearance-none transition-all">
                <option value="newest" {{ request('order') == 'newest' || !request()->has('order') ? 'selected' : '' }}>Terbaru</option>
                <option value="highest" {{ request('order') == 'highest' ? 'selected' : '' }}>Rata-Rata Skor Tertinggi</option>
                <option value="lowest" {{ request('order') == 'lowest' ? 'selected' : '' }}>Rata-Rata Skor Terendah</option>
            </select>
        </div>
    </div>
    
    <div class="flex flex-col md:flex-row justify-end gap-3 mt-2 border-t border-gray-800/50 pt-4">
        <a href="{{ route('admin.guest_rates.index') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-2.5 rounded-xl font-bold transition-all text-center flex items-center justify-center gap-2">
            Reset Filter
        </a>
        <button type="submit" class="bg-[#9D00FF] hover:bg-[#b033ff] text-white px-8 py-2.5 rounded-xl font-bold transition-all shadow-[0_0_15px_rgba(157,0,255,0.4)] flex items-center justify-center gap-2">
            Terapkan Filter
        </button>
    </div>
</form>

<div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black/50 text-gray-400 text-sm uppercase tracking-wider">
                    <th class="p-4 font-bold border-b border-gray-800">Nama Guest</th>
                    <th class="p-4 font-bold border-b border-gray-800">Tipe Rate</th>
                    <th class="p-4 font-bold border-b border-gray-800">Limit Top</th>
                    <th class="p-4 font-bold border-b border-gray-800">Rata-Rata Skor</th>
                    <th class="p-4 font-bold border-b border-gray-800">Tanggal</th>
                    <th class="p-4 font-bold border-b border-gray-800 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse($sessions as $session)
                <tr class="hover:bg-gray-800/30 border-b border-gray-800/50 transition-colors">
                    <td class="p-4 font-bold text-white text-lg flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#9D00FF] to-[#00f2fe] flex items-center justify-center text-white text-xs font-black shadow-[0_0_10px_rgba(157,0,255,0.3)]">
                            {{ substr(strtoupper($session->nama_guest), 0, 1) }}
                        </div>
                        {{ $session->nama_guest }}
                    </td>
                    <td class="p-4">
                        @php
                            $tipeColor = match(strtolower($session->tipe_rate)) {
                                'opening' => 'bg-pink-500/10 text-pink-400 border-pink-500/30',
                                'ending' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                                'movie' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                default => 'bg-gray-500/10 text-gray-400 border-gray-500/30'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full border text-xs font-bold uppercase tracking-wider {{ $tipeColor }}">
                            {{ $session->tipe_rate }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="bg-blue-500/10 text-blue-400 px-3 py-1 rounded-full border border-blue-500/30 text-xs font-bold shadow-[0_0_10px_rgba(59,130,246,0.1)]">
                            Top {{ $session->limit_top }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="font-black text-xl text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-600 drop-shadow-md">
                            {{ number_format($session->rata_rata_score, 2) }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-400 text-sm">
                        {{ $session->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="p-4 text-right flex items-center justify-end gap-2">
                        <a href="{{ route('admin.guest_rates.show', $session) }}" class="inline-flex items-center gap-2 bg-[#9D00FF]/10 text-[#9D00FF] px-4 py-2 rounded-lg font-bold border border-[#9D00FF]/30 hover:bg-[#9D00FF] hover:text-white transition-all shadow-[0_0_10px_rgba(157,0,255,0.1)] hover:shadow-[0_0_15px_rgba(157,0,255,0.5)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Detail
                        </a>
                        <form action="{{ route('admin.guest_rates.destroy', $session) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sesi rating ini? Seluruh data riwayat skor di dalamnya akan ikut terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 bg-red-500/10 text-red-500 px-4 py-2 rounded-lg font-bold border border-red-500/30 hover:bg-red-500 hover:text-white transition-all shadow-[0_0_10px_rgba(239,68,68,0.1)] hover:shadow-[0_0_15px_rgba(239,68,68,0.5)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500 font-bold">
                        Belum ada rating dari guest.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-800 bg-black/30">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
