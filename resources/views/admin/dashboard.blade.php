@extends('layouts.admin')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">Dashboard Overview</h1>
        <p class="text-gray-400 mt-1">Statistik utama dari Anime Song Management.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gray-900/60 backdrop-blur-md border border-[#9D00FF]/30 p-6 rounded-2xl shadow-[0_0_15px_rgba(157,0,255,0.1)] hover:shadow-[0_0_25px_rgba(157,0,255,0.3)] transition-all flex items-center gap-6 group">
        <div class="p-4 bg-[#9D00FF]/10 rounded-xl text-[#9D00FF] group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
        </div>
        <div>
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Lagu</h2>
            <p class="text-4xl font-black text-white mt-1">{{ $totalLagu }}</p>
        </div>
    </div>
    <div class="bg-gray-900/60 backdrop-blur-md border border-green-500/30 p-6 rounded-2xl shadow-[0_0_15px_rgba(34,197,94,0.1)] hover:shadow-[0_0_25px_rgba(34,197,94,0.3)] transition-all flex items-center gap-6 group">
        <div class="p-4 bg-green-500/10 rounded-xl text-green-400 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Guest Rate</h2>
            <p class="text-4xl font-black text-white mt-1">{{ $totalGuest }}</p>
        </div>
    </div>
    <div class="bg-gray-900/60 backdrop-blur-md border border-yellow-500/30 p-6 rounded-2xl shadow-[0_0_15px_rgba(234,179,8,0.1)] hover:shadow-[0_0_25px_rgba(234,179,8,0.3)] transition-all flex items-center gap-6 group">
        <div class="p-4 bg-yellow-500/10 rounded-xl text-yellow-400 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
        </div>
        <div>
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Rata-Rata Skor</h2>
            <p class="text-4xl font-black text-white mt-1">{{ number_format($avgScore, 2) }}</p>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 p-6 rounded-2xl shadow-xl mb-8">
    <h2 class="text-xl font-bold mb-6 text-white border-b border-gray-800 pb-4">Tren Tahunan Rilis Lagu</h2>
    <div id="trendChart" class="w-full h-[350px]"></div>
</div>

<!-- Lists Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 p-6 rounded-2xl shadow-xl">
        <h2 class="text-xl font-bold mb-4 text-white border-b border-gray-800 pb-2">Top 10 Penyanyi</h2>
        <ul class="space-y-3 mt-4">
            @foreach($topSingers as $singer)
                <li class="flex justify-between items-center bg-black/40 p-3 rounded-lg border border-gray-800 hover:border-[#9D00FF]/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#9D00FF]/20 text-[#9D00FF] flex items-center justify-center font-bold text-xs">{{ $loop->iteration }}</div>
                        <span class="font-semibold text-gray-200">{{ $singer->penyanyi }}</span>
                    </div>
                    <span class="font-bold text-[#9D00FF] bg-[#9D00FF]/10 px-3 py-1 rounded-full text-xs">{{ $singer->total }} Lagu</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 p-6 rounded-2xl shadow-xl">
        <h2 class="text-xl font-bold mb-4 text-white border-b border-gray-800 pb-2">Top Franchise Anime</h2>
        <ul class="space-y-3 mt-4">
            @foreach($topFranchises as $franchise)
                <li class="flex justify-between items-center bg-black/40 p-3 rounded-lg border border-gray-800 hover:border-blue-500/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-xs">{{ $loop->iteration }}</div>
                        <span class="font-semibold text-gray-200">{{ $franchise->nama }}</span>
                    </div>
                    <span class="font-bold text-blue-400 bg-blue-500/10 px-3 py-1 rounded-full text-xs">{{ $franchise->songs_count }} Lagu</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trendData = @json($trendTahun);
        
        const options = {
            series: [{
                name: 'Jumlah Lagu',
                data: trendData.map(item => item.total)
            }],
            chart: {
                type: 'area',
                height: 350,
                background: 'transparent',
                toolbar: { show: false }
            },
            theme: { mode: 'dark' },
            colors: ['#9D00FF'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: trendData.map(item => item.tahun_rilis),
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (val) { return Math.round(val); }
                }
            },
            grid: {
                borderColor: '#1f2937',
                strokeDashArray: 4,
            }
        };

        const chart = new ApexCharts(document.querySelector("#trendChart"), options);
        chart.render();
    });
</script>
@endpush
