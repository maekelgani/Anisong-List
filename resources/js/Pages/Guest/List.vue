<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const songs = ref([]);
const search = ref('');
const typeFilter = ref('opening'); // Default to opening based on implementation plan
const activeSongDetail = ref(null);

const mouseX = ref(0);
const mouseY = ref(0);

const handleMouseMove = (e) => {
    mouseX.value = e.clientX;
    mouseY.value = e.clientY;
};

const fetchSongs = async () => {
    try {
        const response = await axios.get('/api/songs', {
            params: {
                search: search.value,
                tipe: typeFilter.value
            }
        });
        songs.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    fetchSongs();
    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
});

const openDetail = (song) => {
    activeSongDetail.value = song;
};

const closeDetail = () => {
    activeSongDetail.value = null;
};

const setTypeFilter = (type) => {
    typeFilter.value = type;
    fetchSongs();
};

const getEmbedUrl = (url) => {
    if (!url) return '';
    if (url.includes('/embed/')) return url;
    
    try {
        if (url.includes('youtube.com/watch')) {
            const urlObj = new URL(url);
            const v = urlObj.searchParams.get('v');
            return v ? `https://www.youtube.com/embed/${v}` : url;
        }
        if (url.includes('youtu.be/')) {
            const urlObj = new URL(url);
            const v = urlObj.pathname.substring(1);
            return v ? `https://www.youtube.com/embed/${v}` : url;
        }
    } catch (e) {
        return url;
    }
    return url;
};
</script>

<template>
    <Head title="Top 100 Anime Songs" />

    <div class="min-h-screen bg-[#0D0D12] text-white flex flex-col relative font-sans selection:bg-[#9D00FF] selection:text-white">
        <!-- Interactive Glowing Aura -->
        <div 
            class="pointer-events-none fixed inset-0 z-0 transition-opacity duration-300"
            :style="{
                background: `radial-gradient(circle 600px at ${mouseX}px ${mouseY}px, rgba(157, 0, 255, 0.1), transparent 80%)`
            }"
        ></div>

        <!-- Header -->
        <nav class="relative z-20 w-full p-6 flex justify-between items-center max-w-5xl mx-auto border-b border-gray-800">
            <div class="font-black text-2xl tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
                ANI<span class="text-[#9D00FF]">SONG</span>.
            </div>
            <Link href="/" class="text-sm font-semibold text-gray-400 hover:text-white transition-colors border border-gray-700 px-4 py-2 rounded-full hover:border-[#9D00FF] hover:shadow-[0_0_10px_rgba(157,0,255,0.3)]">
                &larr; Home
            </Link>
        </nav>

        <main class="flex-1 w-full max-w-5xl mx-auto p-4 md:p-6 relative z-10">
            
            <div class="mb-10 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-[0_0_15px_rgba(157,0,255,0.4)]">Top 100 Ranking</h1>
                <p class="text-gray-400">Temukan soundtrack anime terbaik favorit komunitas.</p>
            </div>

            <!-- Search & Pill Tabs (Filter) Bar -->
            <div class="sticky top-4 z-30 mb-8 bg-[#0D0D12]/80 backdrop-blur-md border border-gray-800 rounded-2xl p-4 shadow-2xl flex flex-col gap-4">
                
                <!-- Pill Tabs -->
                <div class="flex justify-center bg-gray-900/50 rounded-xl p-2 border border-gray-800 gap-2 overflow-x-auto">
                    <button 
                        @click="setTypeFilter('opening')" 
                        class="px-6 py-2 rounded-lg font-bold transition-all whitespace-nowrap"
                        :class="typeFilter === 'opening' ? 'bg-[#9D00FF]/20 text-[#9D00FF] border border-[#9D00FF]/50 shadow-[0_0_15px_rgba(157,0,255,0.6)]' : 'text-gray-400 hover:text-white hover:bg-gray-800 border border-transparent'"
                    >
                        Opening (OP)
                    </button>
                    <button 
                        @click="setTypeFilter('ending')" 
                        class="px-6 py-2 rounded-lg font-bold transition-all whitespace-nowrap"
                        :class="typeFilter === 'ending' ? 'bg-[#9D00FF]/20 text-[#9D00FF] border border-[#9D00FF]/50 shadow-[0_0_15px_rgba(157,0,255,0.6)]' : 'text-gray-400 hover:text-white hover:bg-gray-800 border border-transparent'"
                    >
                        Ending (ED)
                    </button>
                    <button 
                        @click="setTypeFilter('movie')" 
                        class="px-6 py-2 rounded-lg font-bold transition-all whitespace-nowrap"
                        :class="typeFilter === 'movie' ? 'bg-[#9D00FF]/20 text-[#9D00FF] border border-[#9D00FF]/50 shadow-[0_0_15px_rgba(157,0,255,0.6)]' : 'text-gray-400 hover:text-white hover:bg-gray-800 border border-transparent'"
                    >
                        Movie Theme
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative w-full focus-within:shadow-[0_0_20px_rgba(157,0,255,0.2)] rounded-lg transition-all">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input 
                        v-model="search" 
                        @input="fetchSongs" 
                        type="text" 
                        placeholder="Cari lagu, penyanyi, anime..." 
                        class="w-full bg-gray-950/50 border border-gray-700 rounded-lg pl-12 pr-4 py-4 text-white placeholder-gray-500 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-colors"
                    >
                </div>
                
            </div>

            <!-- List Card Layout -->
            <div class="flex flex-col gap-6 pb-20">
                <div 
                    v-for="song in songs" 
                    :key="song.id"
                    @click="openDetail(song)"
                    class="group relative bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-[0_0_25px_rgba(157,0,255,0.3)] hover:scale-[1.02] hover:border-[#9D00FF]/50 transition-all duration-300 flex items-center min-h-[140px] cursor-pointer"
                >
                    <!-- Background Cover Image with Gradient -->
                    <div class="absolute inset-0 z-0 pointer-events-none">
                        <img v-if="song.cover_image" :src="'/storage/' + song.cover_image" alt="" class="w-full h-full object-cover opacity-30 group-hover:opacity-40 transition-opacity">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#0D0D12] via-[#0D0D12]/90 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D12] to-transparent md:hidden"></div>
                    </div>

                    <div class="relative z-10 flex flex-col md:flex-row items-center w-full p-4 md:p-6 gap-6">
                        
                        <!-- Rank Number -->
                        <div class="w-20 md:w-24 shrink-0 text-center">
                            <span class="text-4xl md:text-6xl not-italic select-none font-black text-transparent bg-clip-text bg-gradient-to-br from-gray-300 to-gray-600 group-hover:from-[#9D00FF] group-hover:to-[#b033ff] transition-all duration-500 drop-shadow-md">
                                {{ song.peringkat }}
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 text-center md:text-left min-w-0">
                            <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 truncate drop-shadow-md">{{ song.judul_lagu }}</h2>
                            <p class="text-gray-300 text-lg mb-2 truncate">{{ song.penyanyi }}</p>
                            <div class="flex flex-wrap justify-center md:justify-start gap-2 text-xs font-semibold uppercase tracking-wider">
                                <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full border border-gray-700">{{ song.franchise?.nama || song.anime_name }}</span>
                                <span class="bg-blue-900/30 text-blue-400 px-3 py-1 rounded-full border border-blue-800/50">{{ song.tahun_rilis }}</span>
                            </div>
                        </div>

                        <!-- Click Indicator (Right Arrow) -->
                        <div class="shrink-0 hidden md:flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-x-4 group-hover:translate-x-0">
                            <div class="w-12 h-12 rounded-full bg-[#9D00FF]/20 border border-[#9D00FF] flex items-center justify-center text-[#9D00FF]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div v-if="songs.length === 0" class="text-center py-20 text-gray-500">
                    Tidak ada lagu yang ditemukan untuk kategori ini.
                </div>
            </div>
        </main>
    </div>

    <!-- Detail Modal (Overlay) -->
    <div v-if="activeSongDetail" class="fixed inset-0 bg-[#0D0D12]/90 backdrop-blur-md flex items-center justify-center z-50 p-4 transition-opacity" @click.self="closeDetail">
        <div class="bg-gray-900 rounded-2xl w-full max-w-4xl shadow-[0_0_40px_rgba(157,0,255,0.4)] relative border border-[#9D00FF]/50 transform scale-100 transition-all flex flex-col md:flex-row overflow-hidden max-h-[90vh]">
            
            <!-- Close Button -->
            <button @click="closeDetail" class="absolute top-4 right-4 z-50 text-gray-400 hover:text-white bg-black/50 hover:bg-red-500/80 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Left Side: Cover Image (Visible mostly on Desktop, Header on Mobile) -->
            <div class="w-full md:w-2/5 relative min-h-[200px] md:min-h-full">
                <img v-if="activeSongDetail.cover_image" :src="'/storage/' + activeSongDetail.cover_image" alt="Cover" class="absolute inset-0 w-full h-full object-cover">
                <div v-else class="absolute inset-0 bg-gradient-to-br from-gray-800 to-black flex items-center justify-center">
                    <span class="text-gray-600 font-bold uppercase tracking-widest">No Cover</span>
                </div>
                <!-- Gradient Overlay for smooth transition to text -->
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent md:bg-gradient-to-r md:from-transparent md:to-gray-900"></div>
                
                <!-- Rank Badge Overlay -->
                <div class="absolute top-4 left-4">
                    <span class="bg-[#9D00FF] text-white px-4 py-2 rounded-lg font-black text-xl shadow-[0_0_15px_rgba(157,0,255,0.6)] border border-white/20">
                        #{{ activeSongDetail.peringkat }}
                    </span>
                </div>
            </div>

            <!-- Right Side: Content -->
            <div class="w-full md:w-3/5 p-6 md:p-8 flex flex-col overflow-y-auto">
                <div class="mb-6 relative z-10">
                    <div class="flex gap-2 mb-3 text-xs font-bold uppercase tracking-widest text-[#9D00FF]">
                        <span>{{ activeSongDetail.tipe }}</span>
                        <span class="text-gray-500">&bull;</span>
                        <span class="text-gray-400">{{ activeSongDetail.tahun_rilis }}</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-2 leading-tight drop-shadow-md">
                        {{ activeSongDetail.judul_lagu }}
                    </h2>
                    <p class="text-xl text-gray-300 font-medium mb-1">{{ activeSongDetail.penyanyi }}</p>
                    <p class="text-gray-500 font-medium">{{ activeSongDetail.franchise?.nama || activeSongDetail.anime_name }}</p>
                </div>

                <!-- Ratings Info -->
                <div class="bg-black/50 border border-gray-800 rounded-xl p-4 mb-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Base Score</p>
                        <p class="text-lg font-bold text-white">{{ parseFloat(activeSongDetail.score).toFixed(2) }}</p>
                    </div>
                    <div class="w-px h-10 bg-gray-800"></div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-[#9D00FF] uppercase tracking-widest mb-1 drop-shadow-sm">Average Guest Score</p>
                        <div class="text-2xl font-black text-yellow-400 drop-shadow-[0_0_10px_rgba(250,204,21,0.5)] flex items-center gap-2 justify-end">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span v-if="activeSongDetail.guest_rating_details_avg_score_diberikan !== null">
                                {{ parseFloat(activeSongDetail.guest_rating_details_avg_score_diberikan).toFixed(2) }} <span class="text-sm text-gray-500 font-medium">/ 10</span>
                            </span>
                            <span v-else class="text-base text-gray-500 font-medium italic">
                                Belum ada rating
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Video Player -->
                <div class="mt-auto">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Watch & Listen</p>
                    <div v-if="activeSongDetail.link_video" class="aspect-video w-full rounded-xl overflow-hidden bg-black border border-gray-800 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <iframe 
                            :src="getEmbedUrl(activeSongDetail.link_video)" 
                            class="w-full h-full" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div v-else class="aspect-video w-full rounded-xl overflow-hidden bg-black border border-gray-800 flex items-center justify-center flex-col gap-3 text-gray-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="font-bold">Video tidak tersedia</span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</template>
