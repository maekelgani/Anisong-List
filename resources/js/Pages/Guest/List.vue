<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const songs = ref([]);
const search = ref('');
const typeFilter = ref('');
const activeVideo = ref(null);

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

const openVideo = (url) => {
    activeVideo.value = url;
};

const closeVideo = () => {
    activeVideo.value = null;
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

            <!-- Sticky Search & Filter Bar -->
            <div class="sticky top-4 z-30 mb-8 bg-[#0D0D12]/70 backdrop-blur-md border border-gray-800 rounded-xl p-4 shadow-2xl flex flex-col sm:flex-row gap-4 transition-all focus-within:border-[#9D00FF]/50 focus-within:shadow-[0_0_20px_rgba(157,0,255,0.2)]">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input 
                        v-model="search" 
                        @input="fetchSongs" 
                        type="text" 
                        placeholder="Cari lagu, penyanyi, anime..." 
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-colors"
                    >
                </div>
                <select v-model="typeFilter" @change="fetchSongs" class="bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-colors appearance-none cursor-pointer">
                    <option value="">Semua Tipe</option>
                    <option value="opening">Opening</option>
                    <option value="ending">Ending</option>
                    <option value="movie">Movie</option>
                </select>
            </div>

            <!-- List Card Layout -->
            <div class="flex flex-col gap-6 pb-20">
                <div 
                    v-for="song in songs" 
                    :key="song.id" 
                    class="group relative bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-[0_0_25px_rgba(157,0,255,0.3)] hover:-translate-y-1 hover:border-[#9D00FF]/40 transition-all duration-300 flex items-center min-h-[140px]"
                >
                    <!-- Background Cover Image with Gradient -->
                    <div class="absolute inset-0 z-0">
                        <img v-if="song.cover_image" :src="'/storage/' + song.cover_image" alt="" class="w-full h-full object-cover opacity-30 group-hover:opacity-40 transition-opacity">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#0D0D12] via-[#0D0D12]/90 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D12] to-transparent md:hidden"></div>
                    </div>

                    <div class="relative z-10 flex flex-col md:flex-row items-center w-full p-4 md:p-6 gap-6">
                        
                        <!-- Rank Number -->
                        <div class="w-20 md:w-24 shrink-0 text-center">
                            <span class="text-4xl md:text-6xl font-black italic text-transparent bg-clip-text bg-gradient-to-br from-gray-300 to-gray-600 group-hover:from-[#9D00FF] group-hover:to-[#b033ff] transition-all duration-500 drop-shadow-md">
                                {{ song.peringkat }}
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 text-center md:text-left min-w-0">
                            <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 truncate drop-shadow-md">{{ song.judul_lagu }}</h2>
                            <p class="text-gray-300 text-lg mb-2 truncate">{{ song.penyanyi }}</p>
                            <div class="flex flex-wrap justify-center md:justify-start gap-2 text-xs font-semibold uppercase tracking-wider">
                                <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full border border-gray-700">{{ song.franchise?.nama || song.anime_name }}</span>
                                <span class="bg-[#9D00FF]/20 text-[#9D00FF] px-3 py-1 rounded-full border border-[#9D00FF]/30">{{ song.tipe }}</span>
                                <span class="bg-blue-900/30 text-blue-400 px-3 py-1 rounded-full border border-blue-800/50">{{ song.tahun_rilis }}</span>
                            </div>
                        </div>

                        <!-- Play Button -->
                        <div class="shrink-0 flex flex-col items-center gap-2">
                            <div class="text-green-400 font-bold drop-shadow-[0_0_8px_rgba(74,222,128,0.5)]">
                                ★ {{ parseFloat(song.score).toFixed(2) }}
                            </div>
                            <button 
                                v-if="song.link_video" 
                                @click="openVideo(song.link_video)" 
                                class="w-14 h-14 rounded-full bg-white text-black flex items-center justify-center hover:bg-[#9D00FF] hover:text-white hover:scale-110 shadow-[0_0_15px_rgba(255,255,255,0.2)] hover:shadow-[0_0_20px_rgba(157,0,255,0.6)] transition-all duration-300"
                                title="Play Video"
                            >
                                <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </button>
                        </div>

                    </div>
                </div>
                
                <div v-if="songs.length === 0" class="text-center py-20 text-gray-500">
                    Tidak ada lagu yang ditemukan.
                </div>
            </div>
        </main>
    </div>

    <!-- Video Modal -->
    <div v-if="activeVideo" class="fixed inset-0 bg-[#0D0D12]/90 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click.self="closeVideo">
        <div class="bg-black p-1 rounded-xl w-full max-w-5xl shadow-[0_0_40px_rgba(157,0,255,0.4)] relative border border-[#9D00FF]/30 transform scale-100 transition-all">
            <button @click="closeVideo" class="absolute -top-12 right-0 text-white hover:text-red-500 font-bold text-xl drop-shadow-md transition-colors flex items-center gap-2">
                Tutup <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="aspect-video w-full rounded-lg overflow-hidden bg-gray-900">
                <iframe 
                    :src="activeVideo" 
                    class="w-full h-full" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</template>
