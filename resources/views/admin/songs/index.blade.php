@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">List Lagu (Top 100)</h1>
    <div class="flex items-center gap-4">
        <form method="GET" class="flex items-center gap-2">
            <select name="tipe" class="border rounded p-2">
                <option value="">Semua Tipe</option>
                <option value="opening" {{ request('tipe') == 'opening' ? 'selected' : '' }}>Opening</option>
                <option value="ending" {{ request('tipe') == 'ending' ? 'selected' : '' }}>Ending</option>
                <option value="movie" {{ request('tipe') == 'movie' ? 'selected' : '' }}>Movie</option>
            </select>
            <button type="submit" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Filter</button>
        </form>
        <a href="{{ route('admin.songs.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Tambah Lagu</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-4 border-b">Peringkat</th>
                <th class="p-4 border-b">Judul Lagu</th>
                <th class="p-4 border-b">Anime</th>
                <th class="p-4 border-b">Penyanyi</th>
                <th class="p-4 border-b">Tipe</th>
                <th class="p-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($songs as $song)
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-4 flex items-center gap-2">
                    <span class="font-bold text-xl w-8 text-center">{{ $song->peringkat }}</span>
                    <div class="flex flex-col">
                        <button onclick="reorder({{ $song->id }}, {{ max(1, $song->peringkat - 1) }})" class="text-gray-400 hover:text-blue-600">▲</button>
                        <button onclick="reorder({{ $song->id }}, {{ min(100, $song->peringkat + 1) }})" class="text-gray-400 hover:text-blue-600">▼</button>
                    </div>
                </td>
                <td class="p-4">{{ $song->judul_lagu }}</td>
                <td class="p-4">{{ $song->franchise->nama ?? $song->anime_name }}</td>
                <td class="p-4">{{ $song->penyanyi }}</td>
                <td class="p-4 capitalize">{{ $song->tipe }}</td>
                <td class="p-4 flex gap-2">
                    <a href="{{ route('admin.songs.edit', $song) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                    <form action="{{ route('admin.songs.destroy', $song) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $songs->links() }}
    </div>
</div>

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
    });
}
</script>
@endsection
