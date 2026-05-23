@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold">Detail Guest Rate: {{ $guest_rate->nama_guest }}</h1>
    <a href="{{ route('admin.guest_rates.index') }}" class="text-gray-600 hover:underline">&larr; Kembali</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-md col-span-2">
        <h2 class="font-bold text-lg mb-2">Kritik & Saran</h2>
        <p class="text-gray-700 italic border-l-4 border-blue-500 pl-4 py-2 bg-gray-50">
            "{{ $guest_rate->komentar_guest ?? 'Tidak ada komentar.' }}"
        </p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="font-bold text-lg mb-2">Informasi Sesi</h2>
        <ul class="text-gray-700">
            <li><strong>Tipe:</strong> <span class="capitalize">{{ $guest_rate->tipe_rate }}</span></li>
            <li><strong>Limit Top:</strong> {{ $guest_rate->limit_top }}</li>
            <li><strong>Rata-Rata Skor:</strong> {{ number_format($guest_rate->rata_rata_score, 2) }}</li>
            <li><strong>Tanggal:</strong> {{ $guest_rate->created_at->format('d M Y H:i') }}</li>
        </ul>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <h2 class="font-bold text-lg p-4 border-b bg-gray-50">Rincian Skor per Lagu</h2>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-4 border-b">Skor Diberikan</th>
                <th class="p-4 border-b">Peringkat Asli</th>
                <th class="p-4 border-b">Judul Lagu</th>
                <th class="p-4 border-b">Anime</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $detail)
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-4 font-bold text-blue-600 text-lg">{{ $detail->score_diberikan }}</td>
                <td class="p-4">#{{ $detail->song->peringkat }}</td>
                <td class="p-4">{{ $detail->song->judul_lagu }}<br><span class="text-sm text-gray-500">{{ $detail->song->penyanyi }}</span></td>
                <td class="p-4">{{ $detail->song->franchise->nama ?? $detail->song->anime_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
