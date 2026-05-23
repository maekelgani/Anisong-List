@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">Manajemen Lagu</h1>
        <p class="text-gray-400 mt-1">Atur urutan dan data Top 100 Soundtrack Anime.</p>
    </div>
    <a href="{{ route('admin.songs.create') }}" class="bg-[#9D00FF] text-white px-6 py-2 rounded-lg font-bold shadow-[0_0_15px_rgba(157,0,255,0.4)] hover:bg-[#b033ff] hover:shadow-[0_0_25px_rgba(157,0,255,0.8)] transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Lagu
    </a>
</div>

<!-- Pill Tabs Navigation -->
<div class="flex gap-4 mb-8 border-b border-gray-800 pb-4 overflow-x-auto custom-scrollbar">
    <a href="{{ route('admin.songs.type', 'opening') }}" class="px-6 py-2 rounded-full font-bold transition-all whitespace-nowrap {{ $tipe == 'opening' ? 'bg-[#9D00FF] text-white shadow-[0_0_15px_rgba(157,0,255,0.5)]' : 'bg-gray-900/50 text-gray-400 hover:text-white border border-gray-800 hover:border-[#9D00FF]/50' }}">
        Opening (OP)
    </a>
    <a href="{{ route('admin.songs.type', 'ending') }}" class="px-6 py-2 rounded-full font-bold transition-all whitespace-nowrap {{ $tipe == 'ending' ? 'bg-[#9D00FF] text-white shadow-[0_0_15px_rgba(157,0,255,0.5)]' : 'bg-gray-900/50 text-gray-400 hover:text-white border border-gray-800 hover:border-[#9D00FF]/50' }}">
        Ending (ED)
    </a>
    <a href="{{ route('admin.songs.type', 'movie') }}" class="px-6 py-2 rounded-full font-bold transition-all whitespace-nowrap {{ $tipe == 'movie' ? 'bg-[#9D00FF] text-white shadow-[0_0_15px_rgba(157,0,255,0.5)]' : 'bg-gray-900/50 text-gray-400 hover:text-white border border-gray-800 hover:border-[#9D00FF]/50' }}">
        Movie (MV)
    </a>
</div>

<!-- Table Container -->
<div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black/50 text-gray-400 text-sm uppercase tracking-wider">
                    <th class="p-4 font-bold border-b border-gray-800 w-24 text-center">Rank</th>
                    <th class="p-4 font-bold border-b border-gray-800">Judul Lagu</th>
                    <th class="p-4 font-bold border-b border-gray-800">Anime</th>
                    <th class="p-4 font-bold border-b border-gray-800">Penyanyi</th>
                    <th class="p-4 font-bold border-b border-gray-800">Tahun</th>
                    <th class="p-4 font-bold border-b border-gray-800 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse($songs as $song)
                <tr class="hover:bg-gray-800/30 border-b border-gray-800/50 transition-colors group">
                    <td class="p-4 flex items-center justify-center gap-3">
                        <span class="font-black text-2xl text-transparent bg-clip-text bg-gradient-to-br from-gray-300 to-gray-600 group-hover:from-[#9D00FF] group-hover:to-[#b033ff] w-10 text-center transition-all drop-shadow-md">{{ $song->peringkat }}</span>
                        <div class="flex flex-col gap-1">
                            <button onclick="reorder({{ $song->id }}, {{ max(1, $song->peringkat - 1) }})" class="w-6 h-6 rounded bg-gray-800 text-gray-400 flex items-center justify-center hover:bg-[#9D00FF] hover:text-white hover:shadow-[0_0_10px_rgba(157,0,255,0.6)] transition-all" title="Naik Rank">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <button onclick="reorder({{ $song->id }}, {{ $song->peringkat + 1 }})" class="w-6 h-6 rounded bg-gray-800 text-gray-400 flex items-center justify-center hover:bg-[#9D00FF] hover:text-white hover:shadow-[0_0_10px_rgba(157,0,255,0.6)] transition-all" title="Turun Rank">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-white text-lg drop-shadow-sm">{{ $song->judul_lagu }}</div>
                        @if($song->cover_image)
                            <span class="text-xs text-[#9D00FF] font-semibold tracking-wide">Cover Image ✔</span>
                        @endif
                    </td>
                    <td class="p-4 text-gray-400">{{ $song->franchise->nama ?? $song->anime_name }}</td>
                    <td class="p-4 font-medium text-gray-300">{{ $song->penyanyi }}</td>
                    <td class="p-4">
                        <span class="bg-blue-900/30 text-blue-400 px-3 py-1 rounded-full border border-blue-800/50 text-xs font-bold">{{ $song->tahun_rilis }}</span>
                    </td>
                    <td class="p-4 flex gap-3 justify-end">
                        <a href="{{ route('admin.songs.edit', $song) }}" class="text-yellow-500 hover:text-yellow-400 bg-yellow-500/10 hover:bg-yellow-500/20 p-2 rounded-lg transition-colors border border-transparent hover:border-yellow-500/30" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.songs.destroy', $song) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lagu ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400 bg-red-500/10 hover:bg-red-500/20 p-2 rounded-lg transition-colors border border-transparent hover:border-red-500/30" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500 font-bold">
                        Belum ada lagu untuk kategori ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
function reorder(songId, newRank) {
    fetch('{{ route("admin.songs.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id: songId, new_rank: newRank })
    }).then(res => res.json()).then(data => {
        if(data.success) {
            window.location.reload();
        }
    }).catch(err => {
        alert('Terjadi kesalahan saat memproses reorder.');
    });
}
</script>
@endpush
