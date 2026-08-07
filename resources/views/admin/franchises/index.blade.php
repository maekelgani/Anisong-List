@extends('layouts.admin')

@section('content')
<div x-data="franchiseManager()" class="relative">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">Manajemen Franchise</h1>
            <p class="text-gray-400 mt-1">Kelola daftar franchise anime yang tersedia.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openModal('create')" class="bg-[#9D00FF] text-white px-6 py-2 rounded-lg font-bold shadow-[0_0_15px_rgba(157,0,255,0.4)] hover:bg-[#b033ff] hover:shadow-[0_0_25px_rgba(157,0,255,0.8)] transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Franchise
            </button>
            <form action="{{ route('admin.franchises.deleteAll') }}" method="POST" onsubmit="return confirm('Peringatan: Anda yakin ingin menghapus SELURUH franchise? Semua lagu yang terkait akan kehilangan tag franchisenya.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500/10 text-red-500 px-4 py-2 rounded-lg font-bold border border-red-500/30 hover:bg-red-500 hover:text-white transition-all shadow-[0_0_10px_rgba(239,68,68,0.1)] hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus Semua
                </button>
            </form>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.franchises.index') }}" class="mb-6 flex flex-col md:flex-row gap-4 bg-gray-900/60 p-4 rounded-xl border border-gray-800">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama franchise..." class="flex-1 bg-black/50 border border-gray-700 text-white rounded-lg px-4 py-2 focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] outline-none">
        <button type="submit" class="bg-[#9D00FF] hover:bg-[#b033ff] text-white px-6 py-2 rounded-lg font-bold transition-all shadow-[0_0_10px_rgba(157,0,255,0.4)] w-full md:w-auto">Cari</button>
        <a href="{{ route('admin.franchises.index') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-2 rounded-lg font-bold transition-all w-full md:w-auto text-center flex items-center justify-center">Reset</a>
    </form>

    <!-- Table Container (Desktop) -->
    <div class="hidden md:block bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/50 text-gray-400 text-sm uppercase tracking-wider">
                        <th class="p-4 font-bold border-b border-gray-800 w-24">ID</th>
                        <th class="p-4 font-bold border-b border-gray-800">Nama Franchise</th>
                        <th class="p-4 font-bold border-b border-gray-800">Jumlah Lagu</th>
                        <th class="p-4 font-bold border-b border-gray-800 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @forelse($franchises as $franchise)
                    <tr class="hover:bg-gray-800/30 border-b border-gray-800/50 transition-colors">
                        <td class="p-4 text-gray-500 font-bold">#{{ $franchise->id }}</td>
                        <td class="p-4 font-bold text-white text-lg">{{ $franchise->nama }}</td>
                        <td class="p-4">
                            <span class="bg-[#9D00FF]/10 text-[#9D00FF] px-3 py-1 rounded-full border border-[#9D00FF]/30 text-xs font-bold">{{ $franchise->songs_count }} Lagu</span>
                        </td>
                        <td class="p-4 flex gap-3 justify-end">
                            <button @click="openModal('edit', {{ $franchise->id }}, '{{ addslashes($franchise->nama) }}')" class="text-yellow-500 hover:text-yellow-400 bg-yellow-500/10 hover:bg-yellow-500/20 p-2 rounded-lg transition-colors border border-transparent hover:border-yellow-500/30" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('admin.franchises.destroy', $franchise) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus franchise ini? Lagu-lagu yang terkait mungkin akan kehilangan data franchise-nya.');">
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
                        <td colspan="4" class="p-8 text-center text-gray-500 font-bold">
                            Belum ada data franchise.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-800 bg-black/30">
            {{ $franchises->links() }}
        </div>
    </div>

    <!-- Mobile List Container -->
    <div class="block md:hidden space-y-4 mb-6">
        @forelse($franchises as $franchise)
            <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-lg p-4 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#9D00FF]/20 text-[#9D00FF] flex items-center justify-center font-bold shadow-[0_0_10px_rgba(157,0,255,0.2)]">
                            #{{ $franchise->id }}
                        </div>
                        <h3 class="font-bold text-lg text-white drop-shadow-sm">{{ $franchise->nama }}</h3>
                    </div>
                    <span class="bg-[#9D00FF]/10 text-[#9D00FF] px-3 py-1 rounded-full border border-[#9D00FF]/30 text-xs font-bold">{{ $franchise->songs_count }} Lagu</span>
                </div>
                <!-- Actions -->
                <div class="flex gap-2 border-t border-gray-800 pt-4">
                    <button @click="openModal('edit', {{ $franchise->id }}, '{{ addslashes($franchise->nama) }}')" class="flex-1 bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500 py-2 rounded-xl flex items-center justify-center transition-colors min-h-[44px]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('admin.franchises.destroy', $franchise) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?');" class="flex-1 flex">
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
                Belum ada data franchise.
            </div>
        @endforelse

        <div class="mt-4">
            {{ $franchises->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm px-4" x-transition.opacity>
        <div @click.away="closeModal()" class="bg-gray-900 border border-[#9D00FF]/50 rounded-2xl shadow-[0_0_30px_rgba(157,0,255,0.2)] w-full max-w-md overflow-hidden" x-transition.scale>
            <div class="p-6 border-b border-gray-800 bg-black/40">
                <h2 class="text-xl font-bold text-white" x-text="mode === 'create' ? 'Tambah Franchise' : 'Edit Franchise'"></h2>
            </div>
            <form :action="formAction" method="POST" class="p-6">
                @csrf
                <!-- Dynamic Method Spoofer for PUT -->
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Nama Franchise</label>
                    <input type="text" name="nama" x-model="formData.nama" required class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" placeholder="Contoh: Naruto">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="closeModal()" class="px-5 py-2 rounded-lg font-bold text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="bg-[#9D00FF] text-white px-5 py-2 rounded-lg font-bold shadow-[0_0_15px_rgba(157,0,255,0.4)] hover:bg-[#b033ff] hover:shadow-[0_0_25px_rgba(157,0,255,0.8)] transition-all">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function franchiseManager() {
    return {
        showModal: false,
        mode: 'create',
        formAction: '{{ route('admin.franchises.store') }}',
        formData: {
            id: null,
            nama: ''
        },
        openModal(mode, id = null, nama = '') {
            this.mode = mode;
            this.formData.id = id;
            this.formData.nama = nama;
            
            if (mode === 'edit') {
                this.formAction = `/admin/franchises/${id}`;
            } else {
                this.formAction = '{{ route('admin.franchises.store') }}';
            }
            
            this.showModal = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.showModal = false;
            document.body.style.overflow = 'auto';
        }
    }
}
</script>
@endpush
