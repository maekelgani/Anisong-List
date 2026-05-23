@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">List Franchise</h1>
    <a href="{{ route('admin.franchises.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Tambah Franchise</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-4 border-b">ID</th>
                <th class="p-4 border-b">Nama Franchise</th>
                <th class="p-4 border-b">Jumlah Lagu</th>
                <th class="p-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($franchises as $franchise)
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-4">{{ $franchise->id }}</td>
                <td class="p-4">{{ $franchise->nama }}</td>
                <td class="p-4">{{ $franchise->songs_count }}</td>
                <td class="p-4 flex gap-2">
                    <a href="{{ route('admin.franchises.edit', $franchise) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                    <form action="{{ route('admin.franchises.destroy', $franchise) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-4 text-center">Belum ada franchise.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $franchises->links() }}
    </div>
</div>
@endsection
