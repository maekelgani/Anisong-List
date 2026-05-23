@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4">Tambah Franchise</h1>
    <form action="{{ route('admin.franchises.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Franchise</label>
            <input type="text" name="nama" class="w-full border p-2 rounded focus:ring" required>
            @error('nama') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        <a href="{{ route('admin.franchises.index') }}" class="ml-2 text-gray-600">Batal</a>
    </form>
</div>
@endsection
