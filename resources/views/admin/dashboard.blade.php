@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <h2 class="text-xl font-semibold text-gray-700">Total Lagu</h2>
        <p class="text-4xl font-bold text-blue-600 mt-2">{{ $totalLagu }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <h2 class="text-xl font-semibold text-gray-700">Total Guest Rate</h2>
        <p class="text-4xl font-bold text-green-600 mt-2">{{ $totalGuest }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <h2 class="text-xl font-semibold text-gray-700">Rata-Rata Skor</h2>
        <p class="text-4xl font-bold text-yellow-500 mt-2">{{ number_format($avgScore, 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 border-b pb-2">Top 10 Penyanyi</h2>
        <ul>
            @foreach($topSingers as $singer)
                <li class="flex justify-between py-2 border-b last:border-0">
                    <span>{{ $singer->penyanyi }}</span>
                    <span class="font-bold">{{ $singer->total }} Lagu</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 border-b pb-2">Top Franchise Anime</h2>
        <ul>
            @foreach($topFranchises as $franchise)
                <li class="flex justify-between py-2 border-b last:border-0">
                    <span>{{ $franchise->nama }}</span>
                    <span class="font-bold">{{ $franchise->songs_count }} Lagu</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
