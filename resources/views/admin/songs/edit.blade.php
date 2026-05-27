@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">Edit Lagu</h1>
            <p class="text-gray-400 mt-1">Ubah data lagu <span class="font-bold text-[#9D00FF]">{{ $song->judul_lagu }}</span>.</p>
        </div>
        <a href="{{ route('admin.songs.type', $song->tipe) }}" class="text-gray-400 hover:text-white bg-gray-900 border border-gray-700 px-4 py-2 rounded-lg font-bold transition-all shadow-md">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-gray-900/60 backdrop-blur-md border border-yellow-500/30 p-8 rounded-2xl shadow-[0_0_20px_rgba(234,179,8,0.1)] relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-yellow-500/10 rounded-full blur-[50px] pointer-events-none"></div>

        <form action="{{ route('admin.songs.update', $song) }}" method="POST" enctype="multipart/form-data" class="relative z-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Franchise (Opsional)</label>
                    <select name="franchise_id" class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                        <option value="">-- Pilih Franchise --</option>
                        @foreach($franchises as $franchise)
                            <option value="{{ $franchise->id }}" {{ $song->franchise_id == $franchise->id ? 'selected' : '' }}>{{ $franchise->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Nama Anime <span class="text-xs text-gray-500 font-normal">(Jika tidak ada franchise)</span></label>
                    <input type="text" name="anime_name" value="{{ old('anime_name', $song->anime_name) }}" class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" placeholder="Contoh: Kimi no Na Wa">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Judul Lagu <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_lagu" value="{{ old('judul_lagu', $song->judul_lagu) }}" required class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" placeholder="Contoh: Gurenge">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Penyanyi / Artis <span class="text-red-500">*</span></label>
                    <input type="text" name="penyanyi" value="{{ old('penyanyi', $song->penyanyi) }}" required class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" placeholder="Contoh: LiSA">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Tipe <span class="text-red-500">*</span></label>
                    <select name="tipe" required class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                        <option value="opening" {{ $song->tipe == 'opening' ? 'selected' : '' }}>Opening (OP)</option>
                        <option value="ending" {{ $song->tipe == 'ending' ? 'selected' : '' }}>Ending (ED)</option>
                        <option value="movie" {{ $song->tipe == 'movie' ? 'selected' : '' }}>Movie (MV)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Tahun Rilis <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun_rilis" value="{{ old('tahun_rilis', $song->tahun_rilis) }}" required class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" placeholder="2023">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-yellow-500 mb-2 drop-shadow-md">Peringkat <span class="text-gray-500 font-normal">(Mengubah akan menggeser rank lain)</span> <span class="text-red-500">*</span></label>
                    <input type="number" name="peringkat" min="1" max="100" value="{{ old('peringkat', $song->peringkat) }}" required class="w-full bg-black border border-yellow-500/50 text-white font-black text-xl rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_10px_rgba(234,179,8,0.2)]">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Base Score</label>
                    <input type="number" step="0.01" name="score" value="{{ old('score', $song->score) }}" class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Link Video YouTube Embed <span class="text-xs text-gray-500 font-normal">(Opsional)</span></label>
                    <input type="url" name="link_video" value="{{ old('link_video', $song->link_video) }}" class="w-full bg-black border border-gray-700 text-gray-300 text-base font-mono rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" placeholder="https://www.youtube.com/embed/XXXXXX">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Cover Anime</label>
                    @if($song->cover_image)
                        <div class="mb-4 flex items-start gap-4">
                            <img src="{{ Storage::url($song->cover_image) }}" alt="Cover" class="w-32 h-32 object-cover rounded-xl shadow-[0_0_15px_rgba(0,0,0,0.5)] border border-gray-700">
                            <div class="text-xs text-gray-400 bg-gray-900 px-3 py-2 rounded-lg border border-gray-800">
                                <span class="font-bold text-yellow-500">Info:</span> Gambar di samping adalah cover saat ini. Jika Anda tidak memilih file baru, gambar lama akan tetap dipertahankan.
                            </div>
                        </div>
                    @endif
                    <div class="w-full bg-black border border-gray-700 border-dashed text-gray-400 rounded-lg p-4 text-center hover:border-yellow-500/50 transition-colors cursor-pointer relative overflow-hidden group">
                        <input type="file" name="cover_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('filename').textContent = this.files[0] ? this.files[0].name : 'Pilih File Gambar Baru (Opsional)'">
                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-600 group-hover:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <span id="filename" class="text-sm font-semibold">Pilih File Gambar Baru (Opsional)</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-800 gap-3">
                <a href="{{ route('admin.songs.type', $song->tipe) }}" class="px-6 py-3 rounded-lg font-bold text-gray-400 hover:text-white hover:bg-gray-800 transition-colors border border-transparent">
                    Batal
                </a>
                <button type="submit" class="bg-yellow-500 text-black px-8 py-3 rounded-lg font-black text-lg shadow-[0_0_15px_rgba(234,179,8,0.4)] hover:bg-yellow-400 hover:shadow-[0_0_25px_rgba(234,179,8,0.8)] transition-all">
                    Update Lagu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
