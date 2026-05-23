<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    songs: Array
});

// State
const step = ref(1);
const form = ref({
    nama_guest: '',
    tipe_rate: 'all',
    limit_top: '10',
    komentar_guest: '',
    ratings: [] // {song_id, score}
});

// Logic variables
const filteredSongs = ref([]);
const currentIndex = ref(0);
const currentScore = ref(5); // default middle score

const startRate = () => {
    if (!form.value.nama_guest) {
        alert('Nama Guest harus diisi!');
        return;
    }

    // Filter type
    let temp = props.songs;
    if (form.value.tipe_rate !== 'all') {
        temp = temp.filter(s => s.tipe === form.value.tipe_rate);
    }
    
    // Limit Top N
    const limit = parseInt(form.value.limit_top);
    temp = temp.slice(0, limit);
    
    if (temp.length === 0) {
        alert('Tidak ada lagu yang sesuai kriteria.');
        return;
    }

    // Reverse to rate from bottom to top (e.g., #10 to #1)
    filteredSongs.value = temp.reverse();
    currentIndex.value = 0;
    currentScore.value = 5;
    step.value = 2;
};

const currentSong = computed(() => {
    if (filteredSongs.value.length === 0) return null;
    return filteredSongs.value[currentIndex.value];
});

const nextSong = () => {
    // Save current score
    form.value.ratings.push({
        song_id: currentSong.value.id,
        score: currentScore.value
    });

    // Move next or finish
    if (currentIndex.value < filteredSongs.value.length - 1) {
        currentIndex.value++;
        currentScore.value = 5; // Reset UI score
    } else {
        step.value = 3;
    }
};

const submitRating = () => {
    router.post('/rate/submit', form.value);
};
</script>

<template>
    <Head title="Rate List Wizard" />

    <div class="min-h-screen bg-gray-100 p-6 flex items-center justify-center">
        <div class="max-w-3xl w-full bg-white rounded-xl shadow-xl overflow-hidden transition-all duration-500">
            
            <!-- STEP 1: PENGATURAN -->
            <div v-if="step === 1" class="p-8">
                <h2 class="text-3xl font-bold mb-6 text-gray-800 text-center border-b pb-4">Persiapan Rating</h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nama Guest</label>
                        <input v-model="form.nama_guest" type="text" class="w-full border p-3 rounded bg-gray-50 focus:bg-white focus:ring focus:ring-blue-200" placeholder="Masukkan nama Anda..." required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Tipe List</label>
                        <select v-model="form.tipe_rate" class="w-full border p-3 rounded bg-gray-50 focus:bg-white">
                            <option value="all">Semua (Campur)</option>
                            <option value="opening">Hanya Opening</option>
                            <option value="ending">Hanya Ending</option>
                            <option value="movie">Hanya Movie</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Total Top yang Dirate</label>
                        <select v-model="form.limit_top" class="w-full border p-3 rounded bg-gray-50 focus:bg-white">
                            <option value="10">Top 10</option>
                            <option value="25">Top 25</option>
                            <option value="50">Top 50</option>
                            <option value="100">Top 100</option>
                        </select>
                    </div>
                    <div class="pt-4 text-center">
                        <button @click="startRate" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transform transition hover:scale-105 w-full md:w-auto">
                            Mulai Rate!
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: PROSES RATING -->
            <div v-else-if="step === 2 && currentSong" class="p-0">
                <div class="bg-gray-900 aspect-video w-full relative">
                    <iframe v-if="currentSong.link_video" :src="currentSong.link_video" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-500">Video Tidak Tersedia</div>
                    
                    <div class="absolute top-4 right-4 bg-red-600 text-white font-black text-2xl px-4 py-1 rounded shadow-lg">
                        #{{ currentSong.peringkat }}
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ currentSong.judul_lagu }}</h2>
                        <p class="text-lg text-gray-600">{{ currentSong.penyanyi }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ currentSong.franchise?.nama || currentSong.anime_name }} • {{ currentSong.tahun_rilis }}</p>
                    </div>

                    <div class="mb-6 bg-blue-50 p-6 rounded-lg text-center">
                        <label class="block text-xl font-bold text-blue-900 mb-4">Berikan Skor (0 - 10)</label>
                        <div class="flex items-center justify-center gap-4">
                            <input type="range" v-model.number="currentScore" min="0" max="10" step="0.5" class="w-full md:w-2/3 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span class="text-3xl font-black text-blue-600 w-16 text-right">{{ currentScore }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="text-gray-500 font-semibold">
                            Progress: {{ currentIndex + 1 }} / {{ filteredSongs.length }}
                        </div>
                        <button @click="nextSong" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow transition-transform hover:scale-105">
                            {{ currentIndex === filteredSongs.length - 1 ? 'Selesai Rating' : 'Lanjut ke Lagu Berikutnya' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: SELESAI -->
            <div v-else-if="step === 3" class="p-8 text-center">
                <div class="text-5xl mb-4">🎉</div>
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Rating Selesai!</h2>
                <p class="text-gray-600 mb-8">Terima kasih telah berpartisipasi memberikan rating.</p>
                
                <div class="text-left mb-8">
                    <label class="block text-gray-700 font-bold mb-2">Kritik & Saran (Opsional)</label>
                    <textarea v-model="form.komentar_guest" rows="4" class="w-full border p-3 rounded bg-gray-50 focus:bg-white focus:ring focus:ring-green-200" placeholder="Bagaimana menurutmu tentang list lagu ini?"></textarea>
                </div>

                <button @click="submitRating" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-10 rounded-full shadow-lg transform transition hover:scale-105 text-lg w-full">
                    Submit Semua Rating
                </button>
            </div>
            
        </div>
    </div>
</template>
