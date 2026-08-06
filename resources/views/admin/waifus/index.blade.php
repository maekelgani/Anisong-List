@extends('layouts.admin')

@section('content')
<div x-data="waifuManager()">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">Waifu Gallery</h1>
            <p class="text-gray-400 mt-1">Mengelola daftar karakter Waifu untuk ditampilkan di halaman utama.</p>
        </div>
        <button @click="openModal('create')" class="bg-[#9D00FF] hover:bg-[#b033ff] text-white px-6 py-3 rounded-xl font-bold shadow-[0_0_15px_rgba(157,0,255,0.4)] hover:shadow-[0_0_25px_rgba(157,0,255,0.6)] transition-all flex items-center gap-2 transform hover:-translate-y-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span class="hidden sm:inline">Tambah Waifu</span>
        </button>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-300">
                <thead class="bg-black/50 text-xs uppercase font-bold text-gray-500 border-b border-gray-800">
                    <tr>
                        <th class="p-4 tracking-wider w-24">Image</th>
                        <th class="p-4 tracking-wider">Name</th>
                        <th class="p-4 tracking-wider">Anime Title</th>
                        <th class="p-4 text-right tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($waifus as $waifu)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="p-4">
                            <img src="{{ Storage::url($waifu->image_path) }}" alt="Image" class="w-16 h-20 object-cover rounded-md shadow-md border border-gray-700">
                        </td>
                        <td class="p-4 font-bold text-white">{{ $waifu->name }}</td>
                        <td class="p-4 text-pink-400">{{ $waifu->anime_title ?? '-' }}</td>
                        <td class="p-4 flex gap-3 justify-end h-full mt-4">
                            <button @click="openModal('edit', {{ $waifu->id }}, '{{ addslashes($waifu->name) }}', '{{ addslashes($waifu->anime_title) }}')" class="text-yellow-500 hover:text-yellow-400 bg-yellow-500/10 hover:bg-yellow-500/20 p-2 rounded-lg transition-colors border border-transparent hover:border-yellow-500/30" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('admin.waifus.destroy', $waifu) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                            Belum ada data Waifu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-800 bg-black/30">
            {{ $waifus->links() }}
        </div>
    </div>

    <!-- Mobile List Container -->
    <div class="block md:hidden space-y-4 mb-6">
        @forelse($waifus as $waifu)
            <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-lg p-4 relative overflow-hidden group flex flex-col">
                <div class="flex gap-4 mb-4">
                    <img src="{{ Storage::url($waifu->image_path) }}" alt="Image" class="w-20 h-28 object-cover rounded-lg shadow-md border border-gray-700">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-white drop-shadow-sm">{{ $waifu->name }}</h3>
                        <p class="text-sm text-pink-400 font-semibold">{{ $waifu->anime_title }}</p>
                    </div>
                </div>
                <!-- Actions -->
                <div class="flex gap-2 border-t border-gray-800 pt-4">
                    <button @click="openModal('edit', {{ $waifu->id }}, '{{ addslashes($waifu->name) }}', '{{ addslashes($waifu->anime_title) }}')" class="flex-1 bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500 py-2 rounded-xl flex items-center justify-center transition-colors min-h-[44px]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('admin.waifus.destroy', $waifu) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?');" class="flex-1 flex">
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
                Belum ada data Waifu.
            </div>
        @endforelse
        <div class="mt-4">
            {{ $waifus->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm px-4" x-transition.opacity>
        <div @click.away="closeModal()" class="bg-gray-900 border border-[#9D00FF]/50 rounded-2xl shadow-[0_0_30px_rgba(157,0,255,0.2)] w-full max-w-md overflow-hidden max-h-[90vh] flex flex-col" x-transition.scale>
            <div class="p-6 border-b border-gray-800 bg-black/40 flex-shrink-0">
                <h2 class="text-xl font-bold text-white" x-text="mode === 'create' ? 'Tambah Waifu' : 'Edit Waifu'"></h2>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <form :action="formAction" method="POST" enctype="multipart/form-data" id="waifuForm">
                    @csrf
                    <!-- Dynamic Method Spoofer for PUT -->
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-400 mb-2">Name</label>
                        <input type="text" name="name" x-model="formData.name" required class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-400 mb-2">Image <span x-show="mode === 'edit'" class="text-gray-500 font-normal">(Biarkan kosong jika tidak diganti)</span></label>
                        <input type="file" name="image_path" accept="image/*" :required="mode === 'create'" class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-2 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#9D00FF]/20 file:text-[#9D00FF] hover:file:bg-[#9D00FF]/30">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-400 mb-2">Anime Title</label>
                        <input type="text" name="anime_title" x-model="formData.anime_title" class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-800 bg-black/40 flex justify-end gap-3 flex-shrink-0">
                <button type="button" @click="closeModal()" class="px-5 py-2 rounded-lg font-bold text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                    Batal
                </button>
                <button type="button" @click="document.getElementById('waifuForm').submit()" class="bg-[#9D00FF] text-white px-5 py-2 rounded-lg font-bold shadow-[0_0_15px_rgba(157,0,255,0.4)] hover:bg-[#b033ff] hover:shadow-[0_0_25px_rgba(157,0,255,0.8)] transition-all">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function waifuManager() {
    return {
        showModal: false,
        mode: 'create',
        formAction: '{{ route('admin.waifus.store') }}',
        formData: {
            id: null,
            name: '',
            anime_title: ''
        },
        openModal(mode, id = null, name = '', anime_title = '') {
            this.mode = mode;
            this.formData.id = id;
            this.formData.name = name;
            this.formData.anime_title = anime_title;
            
            if (mode === 'edit') {
                this.formAction = `/admin/waifus/${id}`;
            } else {
                this.formAction = '{{ route('admin.waifus.store') }}';
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
