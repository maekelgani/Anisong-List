@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4">Edit Lagu</h1>
    <form action="{{ route('admin.songs.update', $song) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Franchise (Opsional)</label>
                <select name="franchise_id" class="w-full border p-2 rounded">
                    <option value="">-- Pilih Franchise --</option>
                    @foreach($franchises as $franchise)
                        <option value="{{ $franchise->id }}" {{ $song->franchise_id == $franchise->id ? 'selected' : '' }}>{{ $franchise->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Anime (Jika non-franchise)</label>
                <input type="text" name="anime_name" value="{{ old('anime_name', $song->anime_name) }}" class="w-full border p-2 rounded">
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Judul Lagu</label>
                <input type="text" name="judul_lagu" value="{{ old('judul_lagu', $song->judul_lagu) }}" class="w-full border p-2 rounded" required>
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Penyanyi</label>
                <input type="text" name="penyanyi" value="{{ old('penyanyi', $song->penyanyi) }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tipe</label>
                <select name="tipe" class="w-full border p-2 rounded" required>
                    <option value="opening" {{ $song->tipe == 'opening' ? 'selected' : '' }}>Opening</option>
                    <option value="ending" {{ $song->tipe == 'ending' ? 'selected' : '' }}>Ending</option>
                    <option value="movie" {{ $song->tipe == 'movie' ? 'selected' : '' }}>Movie</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tahun Rilis</label>
                <input type="number" name="tahun_rilis" value="{{ old('tahun_rilis', $song->tahun_rilis) }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Peringkat (1-100)</label>
                <input type="number" name="peringkat" min="1" max="100" value="{{ old('peringkat', $song->peringkat) }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Score (Bawaan)</label>
                <input type="number" step="0.01" name="score" value="{{ old('score', $song->score) }}" class="w-full border p-2 rounded">
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Link Video (YouTube Embed URL)</label>
                <input type="url" name="link_video" value="{{ old('link_video', $song->link_video) }}" class="w-full border p-2 rounded">
            </div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('admin.songs.index') }}" class="ml-2 text-gray-600">Batal</a>
    </form>
</div>
@endsection
