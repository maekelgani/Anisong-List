<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const songs = ref([]);
const search = ref('');
const typeFilter = ref('');
const activeVideo = ref(null);

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

    <div class="min-h-screen bg-gray-100 p-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Top 100 Anime Songs</h1>
                <Link href="/" class="text-blue-600 hover:underline">Kembali ke Home</Link>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow mb-6 flex flex-col sm:flex-row gap-4">
                <input 
                    v-model="search" 
                    @input="fetchSongs" 
                    type="text" 
                    placeholder="Cari lagu, penyanyi, anime..." 
                    class="flex-1 border-gray-300 rounded-md shadow-sm p-2 border"
                >
                <select v-model="typeFilter" @change="fetchSongs" class="border-gray-300 rounded-md shadow-sm p-2 border">
                    <option value="">Semua Tipe</option>
                    <option value="opening">Opening</option>
                    <option value="ending">Ending</option>
                    <option value="movie">Movie</option>
                </select>
            </div>

            <!-- List Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="song in songs" :key="song.id" class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col relative transition-transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 bg-blue-600 text-white font-bold text-xl px-3 py-1 rounded-br-lg z-10">
                        #{{ song.peringkat }}
                    </div>
                    <div class="p-6 pt-10 flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-1 line-clamp-1">{{ song.judul_lagu }}</h2>
                        <p class="text-gray-600 mb-2">{{ song.penyanyi }}</p>
                        <div class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full mb-4">
                            {{ song.franchise?.nama || song.anime_name }} • {{ song.tahun_rilis }} • <span class="capitalize">{{ song.tipe }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="font-bold text-green-600">Score: {{ parseFloat(song.score).toFixed(2) }}</span>
                        <button v-if="song.link_video" @click="openVideo(song.link_video)" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm flex items-center gap-1 shadow">
                            ▶ Play MV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Modal -->
    <div v-if="activeVideo" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4" @click.self="closeVideo">
        <div class="bg-gray-900 p-2 rounded-lg w-full max-w-4xl shadow-2xl relative">
            <button @click="closeVideo" class="absolute -top-10 right-0 text-white hover:text-red-500 font-bold text-xl">Tutup &times;</button>
            <div class="aspect-video w-full">
                <iframe 
                    :src="activeVideo" 
                    class="w-full h-full rounded" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</template>
