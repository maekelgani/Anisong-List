@extends('layouts.admin')

@section('content')
<div x-data="{ showImportModal: false, fileName: '', dragging: false }">
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">Manajemen Lagu</h1>
        <p class="text-gray-400 mt-1">Atur urutan dan data Top 100 Soundtrack Anime.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <!-- Export Excel -->
        <a href="{{ route('admin.songs.export') }}" class="bg-cyan-500/10 text-cyan-400 px-4 py-2 rounded-lg font-bold border border-cyan-500/30 hover:bg-cyan-500 hover:text-white transition-all shadow-[0_0_10px_rgba(6,182,212,0.1)] hover:shadow-[0_0_15px_rgba(6,182,212,0.5)] flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export Excel
        </a>
        <!-- Import Excel -->
        <button @click="showImportModal = true" class="bg-[#9D00FF]/10 text-[#9D00FF] px-4 py-2 rounded-lg font-bold border border-[#9D00FF]/30 hover:bg-[#9D00FF] hover:text-white transition-all shadow-[0_0_10px_rgba(157,0,255,0.1)] hover:shadow-[0_0_15px_rgba(157,0,255,0.5)] flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Import Data
        </button>
        <!-- Tambah Lagu -->
        <a href="{{ route('admin.songs.create') }}" class="bg-[#9D00FF] text-white px-6 py-2 rounded-lg font-bold shadow-[0_0_15px_rgba(157,0,255,0.4)] hover:bg-[#b033ff] hover:shadow-[0_0_25px_rgba(157,0,255,0.8)] transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Lagu
        </a>
        <!-- Hapus Semua Lagu -->
        <form action="{{ route('admin.songs.deleteAll') }}" method="POST" onsubmit="return confirm('Peringatan Kritis: Anda yakin ingin menghapus SELURUH lagu? Data yang dihapus tidak dapat dikembalikan!');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500/10 text-red-500 px-4 py-2 rounded-lg font-bold border border-red-500/30 hover:bg-red-500 hover:text-white transition-all shadow-[0_0_10px_rgba(239,68,68,0.1)] hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus Semua
            </button>
        </form>
    </div>
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

<!-- Filter Form -->
<form method="GET" action="{{ route('admin.songs.type', $tipe) }}" class="mb-6 flex flex-col md:flex-row gap-4 bg-gray-900/60 p-4 rounded-xl border border-gray-800">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penyanyi, anime..." class="flex-1 bg-black/50 border border-gray-700 text-white rounded-lg px-4 py-2 focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] outline-none">
    <select name="franchise_id" class="flex-1 bg-black/50 border border-gray-700 text-white rounded-lg px-4 py-2 focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] outline-none appearance-none">
        <option value="">-- Semua Franchise --</option>
        @foreach($franchises as $f)
            <option value="{{ $f->id }}" {{ request('franchise_id') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-[#9D00FF] hover:bg-[#b033ff] text-white px-6 py-2 rounded-lg font-bold transition-all shadow-[0_0_10px_rgba(157,0,255,0.4)]">Filter</button>
    <a href="{{ route('admin.songs.type', $tipe) }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-2 rounded-lg font-bold transition-all flex items-center justify-center">Reset</a>
</form>

<!-- Table Container (Desktop) -->
<div class="hidden md:block bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-xl overflow-hidden">
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
                            @if(!request('search') && !request('franchise_id'))
                                <button onclick="reorder({{ $song->id }}, {{ max(1, $song->peringkat - 1) }})" class="w-6 h-6 rounded bg-gray-800 text-gray-400 flex items-center justify-center hover:bg-[#9D00FF] hover:text-white hover:shadow-[0_0_10px_rgba(157,0,255,0.6)] transition-all" title="Naik Rank">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                </button>
                                <button onclick="reorder({{ $song->id }}, {{ $song->peringkat + 1 }})" class="w-6 h-6 rounded bg-gray-800 text-gray-400 flex items-center justify-center hover:bg-[#9D00FF] hover:text-white hover:shadow-[0_0_10px_rgba(157,0,255,0.6)] transition-all" title="Turun Rank">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            @else
                                <span class="text-xs text-gray-600 text-center">-</span>
                            @endif
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

<!-- Mobile List Container -->
<div class="block md:hidden space-y-4">
    @forelse($songs as $song)
        <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-lg p-4 relative overflow-hidden group">
            <div class="flex items-center gap-4">
                <!-- Rank Box -->
                <div class="w-14 h-14 shrink-0 rounded-full bg-[#9D00FF]/20 border border-[#9D00FF]/50 flex items-center justify-center font-black text-2xl text-white shadow-[0_0_15px_rgba(157,0,255,0.4)]">
                    {{ $song->peringkat }}
                </div>
                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-lg text-white truncate drop-shadow-sm">{{ $song->judul_lagu }}</h3>
                    <p class="text-sm text-gray-400 truncate">{{ $song->penyanyi }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="bg-blue-900/30 text-blue-400 px-2 py-0.5 rounded border border-blue-800/50 text-xs font-bold">{{ $song->tahun_rilis }}</span>
                        @if($song->cover_image)
                            <span class="text-xs text-[#9D00FF] font-semibold">Cover ✔</span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Actions (Large Touch Targets) -->
            <div class="mt-4 flex gap-2 border-t border-gray-800 pt-4">
                @if(!request('search') && !request('franchise_id'))
                    <button onclick="reorder({{ $song->id }}, {{ max(1, $song->peringkat - 1) }})" class="flex-1 bg-gray-800/50 hover:bg-[#9D00FF]/20 text-gray-400 hover:text-[#9D00FF] py-2 rounded-xl flex items-center justify-center transition-colors min-h-[44px]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    </button>
                    <button onclick="reorder({{ $song->id }}, {{ $song->peringkat + 1 }})" class="flex-1 bg-gray-800/50 hover:bg-[#9D00FF]/20 text-gray-400 hover:text-[#9D00FF] py-2 rounded-xl flex items-center justify-center transition-colors min-h-[44px]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                @endif
                <a href="{{ route('admin.songs.edit', $song) }}" class="flex-1 bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500 py-2 rounded-xl flex items-center justify-center transition-colors min-h-[44px]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
                <form action="{{ route('admin.songs.destroy', $song) }}" method="POST" onsubmit="return confirm('Yakin hapus?');" class="flex-1 flex">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-500 py-2 rounded-xl flex items-center justify-center transition-colors min-h-[44px]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="p-8 text-center text-gray-500 font-bold bg-gray-900/60 rounded-2xl border border-gray-800 shadow-lg">
            Belum ada lagu untuk kategori ini.
        </div>
    @endforelse
</div>

<!-- Pagination (Desktop & Mobile) -->
<div class="mt-8 pagination-wrapper">
    {{ $songs->links() }}
</div>

<!-- Import Modal -->
<div x-show="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" style="display: none;">
    <div @click.away="showImportModal = false" class="bg-gray-900 border border-[#9D00FF]/50 rounded-2xl w-full max-w-md shadow-[0_0_40px_rgba(157,0,255,0.2)] overflow-hidden transform transition-all">
        <div class="p-6 border-b border-gray-800 flex justify-between items-center bg-black/40">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-[#9D00FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Data Lagu
            </h3>
            <button @click="showImportModal = false" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('admin.songs.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="mb-6">
                <p class="text-gray-400 text-sm mb-4">Unggah file Excel (.xlsx) atau CSV yang berisi data lagu. Sistem akan otomatis melakukan penyesuaian peringkat (reorder).</p>
                <div class="flex items-center justify-center w-full"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="dragging = false; if($event.dataTransfer.files.length > 0) { $refs.fileInput.files = $event.dataTransfer.files; fileName = $event.dataTransfer.files[0].name }">
                    <label for="dropzone-file" 
                           class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer transition-all group"
                           :class="dragging ? 'border-[#9D00FF] bg-[#9D00FF]/10' : 'border-gray-700 bg-black/30 hover:bg-black/50 hover:border-[#9D00FF]/50'">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 transition-colors" :class="dragging ? 'text-[#9D00FF]' : 'text-gray-500 group-hover:text-[#9D00FF]'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-center px-4" :class="dragging ? 'text-[#9D00FF]' : 'text-gray-400'">
                                <span x-text="fileName ? fileName : 'Klik untuk upload atau drag and drop'" class="font-semibold" :class="fileName ? 'text-[#9D00FF]' : 'text-[#9D00FF]'"></span>
                            </p>
                            <p class="text-xs text-gray-500" x-show="!fileName">XLSX, CSV (Max. 5MB)</p>
                            <p class="text-xs text-green-400 mt-2 font-bold" x-show="fileName">File siap diimpor!</p>
                        </div>
                        <input id="dropzone-file" type="file" name="file" class="hidden" accept=".xlsx,.csv,.xls" required x-ref="fileInput" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                    </label>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <button type="submit" class="w-full bg-[#9D00FF] text-white py-3 rounded-xl font-bold hover:bg-[#b033ff] shadow-[0_0_15px_rgba(157,0,255,0.4)] transition-all">
                    Mulai Import
                </button>
                <div class="text-center">
                    <a href="{{ route('admin.songs.template') }}" class="text-sm font-semibold text-gray-400 hover:text-cyan-400 transition-colors inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Format Template
                    </a>
                </div>
            </div>
        </form>
    </div>
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
