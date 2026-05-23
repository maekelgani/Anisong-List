@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">
            Guest Rate: <span class="text-[#9D00FF]">{{ $guest_rate->nama_guest }}</span>
        </h1>
        <p class="text-gray-400 mt-1">Detail evaluasi dan penilaian dari sesi ini.</p>
    </div>
    <a href="{{ route('admin.guest_rates.index') }}" class="bg-gray-800 text-gray-300 px-6 py-2 rounded-lg font-bold hover:bg-gray-700 hover:text-white transition-all flex items-center gap-2 border border-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Terminal Bubble for Comments -->
    <div class="md:col-span-2 bg-black border border-gray-800 rounded-2xl p-1 shadow-2xl relative overflow-hidden group">
        <!-- Terminal Header -->
        <div class="bg-gray-900 rounded-t-xl px-4 py-2 flex items-center gap-2 border-b border-gray-800">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
            <div class="w-3 h-3 rounded-full bg-green-500"></div>
            <span class="ml-2 text-xs font-mono text-gray-500">guest_feedback.sh</span>
        </div>
        <!-- Terminal Body -->
        <div class="p-6 font-mono text-sm leading-relaxed text-green-400 min-h-[120px] bg-black/80">
            <div class="mb-2 text-blue-400">$ cat feedback.txt</div>
            @if($guest_rate->komentar_guest)
                <p class="whitespace-pre-wrap">{{ $guest_rate->komentar_guest }}</p>
            @else
                <p class="text-gray-600 italic">// Tidak ada komentar atau saran dari guest.</p>
            @endif
            <div class="mt-4 animate-pulse inline-block w-2 h-4 bg-green-400"></div>
        </div>
    </div>

    <!-- Session Info Card -->
    <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl p-6 shadow-xl flex flex-col justify-center relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-[#9D00FF]/10 rounded-full blur-[30px]"></div>
        
        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Informasi Sesi</h2>
        <div class="space-y-4 relative z-10">
            <div class="flex justify-between items-center border-b border-gray-800 pb-2">
                <span class="text-gray-400">Tipe Rate</span>
                <span class="font-bold text-white uppercase">{{ $guest_rate->tipe_rate }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-800 pb-2">
                <span class="text-gray-400">Limit Top</span>
                <span class="font-bold text-white">{{ $guest_rate->limit_top }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-800 pb-2">
                <span class="text-gray-400">Rata-Rata Skor</span>
                <span class="font-black text-yellow-400">{{ number_format($guest_rate->rata_rata_score, 2) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Tanggal</span>
                <span class="font-medium text-gray-300 text-sm">{{ $guest_rate->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Ranking Board -->
<div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-xl overflow-hidden">
    <div class="p-6 border-b border-gray-800 flex items-center justify-between bg-black/40">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-[#9D00FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Ranking Board
        </h2>
    </div>
    <div class="p-6 space-y-6">
        @foreach($details as $detail)
            @php
                $percentage = ($detail->score_diberikan / 10) * 100;
                $barColor = $percentage >= 80 ? 'from-[#9D00FF] to-[#00f2fe]' : 
                           ($percentage >= 50 ? 'from-blue-500 to-cyan-400' : 'from-red-500 to-orange-400');
            @endphp
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4 bg-black/40 p-4 rounded-xl border border-gray-800 hover:border-gray-600 transition-colors">
                
                <div class="flex-1 w-full">
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <div class="font-bold text-white text-lg drop-shadow-sm flex items-center gap-2">
                                <span class="text-gray-500 text-sm font-black">#{{ $detail->song->peringkat }}</span>
                                {{ $detail->song->judul_lagu }}
                            </div>
                            <div class="text-sm text-gray-400 mt-1">
                                {{ $detail->song->penyanyi }} &bull; <span class="text-gray-500">{{ $detail->song->franchise->nama ?? $detail->song->anime_name }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-2xl text-transparent bg-clip-text bg-gradient-to-r {{ $barColor }} drop-shadow-md">
                                {{ $detail->score_diberikan }}<span class="text-sm text-gray-600">/10</span>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-800 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r {{ $barColor }} h-2.5 rounded-full shadow-[0_0_10px_rgba(157,0,255,0.5)] transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
</div>
@endsection
