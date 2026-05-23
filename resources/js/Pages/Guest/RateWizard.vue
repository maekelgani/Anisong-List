<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

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
    ratings: [] // {song_id, score, song_data} (song_data for preview)
});

// Logic variables
const filteredSongs = ref([]);
const currentIndex = ref(0);
const currentScore = ref(5);

// Modals
const showLeaveWarning = ref(false);
const showSubmitConfirm = ref(false);
let leaveTargetUrl = '/';

const startRate = () => {
    if (!form.value.nama_guest) {
        alert('Nama Guest harus diisi!');
        return;
    }

    let temp = props.songs;
    if (form.value.tipe_rate !== 'all') {
        temp = temp.filter(s => s.tipe === form.value.tipe_rate);
    }
    
    const limit = parseInt(form.value.limit_top);
    temp = temp.slice(0, limit);
    
    if (temp.length === 0) {
        alert('Tidak ada lagu yang sesuai kriteria.');
        return;
    }

    filteredSongs.value = temp.reverse();
    currentIndex.value = 0;
    
    // Initialize ratings array
    form.value.ratings = filteredSongs.value.map(song => ({
        song_id: song.id,
        score: 5, // default
        song_data: song
    }));
    
    loadScoreForCurrentIndex();
    step.value = 2;
};

const currentSong = computed(() => {
    if (filteredSongs.value.length === 0) return null;
    return filteredSongs.value[currentIndex.value];
});

const loadScoreForCurrentIndex = () => {
    currentScore.value = form.value.ratings[currentIndex.value].score;
};

const saveScoreForCurrentIndex = () => {
    form.value.ratings[currentIndex.value].score = currentScore.value;
};

const nextSong = () => {
    saveScoreForCurrentIndex();
    if (currentIndex.value < filteredSongs.value.length - 1) {
        currentIndex.value++;
        loadScoreForCurrentIndex();
    } else {
        step.value = 3;
    }
};

const prevSong = () => {
    saveScoreForCurrentIndex();
    if (currentIndex.value > 0) {
        currentIndex.value--;
        loadScoreForCurrentIndex();
    }
};

const attemptLeave = (url) => {
    if (step.value === 2) {
        leaveTargetUrl = url;
        showLeaveWarning.value = true;
    } else {
        router.visit(url);
    }
};

const confirmLeave = () => {
    showLeaveWarning.value = false;
    router.visit(leaveTargetUrl);
};

const cancelLeave = () => {
    showLeaveWarning.value = false;
};

const confirmSubmit = () => {
    showSubmitConfirm.value = true;
};

const submitRating = () => {
    showSubmitConfirm.value = false;
    // Map out song_data before submit to reduce payload
    const payload = {
        ...form.value,
        ratings: form.value.ratings.map(r => ({
            song_id: r.song_id,
            score: r.score
        }))
    };
    router.post('/rate/submit', payload);
};

// Mouse tracking for subtle background effect
const mouseX = ref(0);
const mouseY = ref(0);
const handleMouseMove = (e) => {
    mouseX.value = e.clientX;
    mouseY.value = e.clientY;
};
onMounted(() => window.addEventListener('mousemove', handleMouseMove));
onUnmounted(() => window.removeEventListener('mousemove', handleMouseMove));
</script>

<template>
    <Head title="Interactive Rate Wizard" />

    <div class="min-h-screen bg-[#0D0D12] text-white flex flex-col relative font-sans selection:bg-[#9D00FF] selection:text-white">
        <!-- Background Aura -->
        <div class="pointer-events-none fixed inset-0 z-0 transition-opacity duration-300"
            :style="{ background: `radial-gradient(circle 800px at ${mouseX}px ${mouseY}px, rgba(157, 0, 255, 0.08), transparent 80%)` }">
        </div>

        <!-- Global Navigation -->
        <nav class="relative z-20 w-full p-6 flex justify-between items-center max-w-5xl mx-auto">
            <div class="font-black text-2xl tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
                ANI<span class="text-[#9D00FF]">SONG</span>.
            </div>
            <button @click="attemptLeave('/')" class="text-sm font-semibold text-gray-400 hover:text-white transition-colors border border-gray-700 px-4 py-2 rounded-full hover:border-red-500 hover:shadow-[0_0_10px_rgba(239,68,68,0.3)] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Keluar
            </button>
        </nav>

        <main class="flex-1 w-full max-w-4xl mx-auto p-4 md:p-6 relative z-10 flex flex-col justify-center">
            
            <!-- STEP 1: PENGATURAN -->
            <div v-if="step === 1" class="w-full bg-gray-900/60 backdrop-blur-xl border border-gray-800 rounded-2xl p-8 md:p-12 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-8 text-center text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">Game Setup</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-400 font-bold mb-2 uppercase tracking-wider text-sm">Player Name</label>
                        <input v-model="form.nama_guest" type="text" class="w-full bg-black/50 border border-gray-700 p-4 rounded-xl text-white focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all" placeholder="Enter your name..." required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-400 font-bold mb-2 uppercase tracking-wider text-sm">Category</label>
                            <select v-model="form.tipe_rate" class="w-full bg-black/50 border border-gray-700 p-4 rounded-xl text-white focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all appearance-none">
                                <option value="all">Mix All</option>
                                <option value="opening">Openings Only</option>
                                <option value="ending">Endings Only</option>
                                <option value="movie">Movies Only</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-400 font-bold mb-2 uppercase tracking-wider text-sm">Limit</label>
                            <select v-model="form.limit_top" class="w-full bg-black/50 border border-gray-700 p-4 rounded-xl text-white focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all appearance-none">
                                <option value="10">Top 10</option>
                                <option value="25">Top 25</option>
                                <option value="50">Top 50</option>
                                <option value="100">Top 100</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="pt-8 text-center">
                        <button @click="startRate" class="relative group inline-block w-full md:w-auto">
                            <div class="absolute -inset-1 bg-gradient-to-r from-[#9D00FF] to-indigo-600 rounded-xl blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-pulse"></div>
                            <div class="relative bg-black border border-gray-800 text-white font-black uppercase tracking-widest py-4 px-12 rounded-xl text-lg hover:bg-gray-900 transition-colors">
                                START RATING
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: PROSES RATING -->
            <div v-else-if="step === 2 && currentSong" class="w-full flex flex-col gap-6">
                <!-- Cinematic Video Player -->
                <div class="w-full bg-black rounded-2xl overflow-hidden shadow-[0_0_30px_rgba(157,0,255,0.2)] border border-gray-800 relative">
                    <div class="absolute top-4 left-4 z-10 bg-black/80 backdrop-blur border border-[#9D00FF]/50 text-[#9D00FF] px-4 py-1 rounded-full font-black text-xl drop-shadow-[0_0_10px_rgba(157,0,255,0.8)]">
                        #{{ currentSong.peringkat }}
                    </div>
                    <div class="absolute top-4 right-4 z-10 text-xs font-bold text-gray-400 bg-black/80 px-3 py-1 rounded-full border border-gray-700">
                        {{ currentIndex + 1 }} / {{ filteredSongs.length }}
                    </div>
                    <div class="aspect-video w-full">
                        <iframe v-if="currentSong.link_video" :src="currentSong.link_video" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                        <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-600">
                            <svg class="w-16 h-16 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Video Unavailable
                        </div>
                    </div>
                </div>

                <!-- Song Info & Slider -->
                <div class="w-full bg-gray-900/60 backdrop-blur-xl border border-gray-800 rounded-2xl p-6 md:p-8 flex flex-col items-center shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#9D00FF]/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    
                    <h2 class="text-3xl md:text-4xl font-black text-white text-center mb-2 drop-shadow-md">{{ currentSong.judul_lagu }}</h2>
                    <p class="text-[#9D00FF] text-xl font-medium mb-1 text-center">{{ currentSong.penyanyi }}</p>
                    <p class="text-gray-400 text-sm mb-8 text-center uppercase tracking-widest">{{ currentSong.franchise?.nama || currentSong.anime_name }}</p>

                    <!-- Custom Neon Slider -->
                    <div class="w-full max-w-2xl mb-8">
                        <div class="flex justify-between items-end mb-4 px-2">
                            <span class="text-gray-500 font-bold">0.0</span>
                            <span class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-400 drop-shadow-[0_0_15px_rgba(157,0,255,0.8)]">{{ parseFloat(currentScore).toFixed(1) }}</span>
                            <span class="text-gray-500 font-bold">10.0</span>
                        </div>
                        <input 
                            type="range" 
                            v-model.number="currentScore" 
                            min="0" 
                            max="10" 
                            step="0.5" 
                            class="w-full h-3 bg-gray-800 rounded-lg appearance-none cursor-pointer accent-[#9D00FF] drop-shadow-[0_0_10px_rgba(157,0,255,0.5)]"
                        >
                    </div>

                    <!-- Navigation -->
                    <div class="w-full flex justify-between items-center mt-4">
                        <button 
                            @click="prevSong" 
                            :disabled="currentIndex === 0"
                            class="px-6 py-3 rounded-lg font-bold transition-all flex items-center gap-2"
                            :class="currentIndex === 0 ? 'bg-gray-800 text-gray-600 cursor-not-allowed' : 'bg-gray-800 text-white hover:bg-gray-700 hover:text-[#9D00FF] border border-gray-700 hover:border-[#9D00FF]'"
                        >
                            &larr; Prev
                        </button>
                        <button 
                            @click="nextSong" 
                            class="px-8 py-3 bg-[#9D00FF] text-white rounded-lg font-black shadow-[0_0_15px_rgba(157,0,255,0.4)] hover:bg-[#b033ff] hover:shadow-[0_0_25px_rgba(157,0,255,0.8)] transition-all transform hover:scale-105 flex items-center gap-2"
                        >
                            {{ currentIndex === filteredSongs.length - 1 ? 'FINISH' : 'NEXT' }} &rarr;
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: SUMMARY -->
            <div v-else-if="step === 3" class="w-full flex flex-col gap-6 animate-fade-in">
                <div class="text-center mb-4">
                    <h2 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500 drop-shadow-[0_0_15px_rgba(74,222,128,0.3)] mb-2">Rating Complete!</h2>
                    <p class="text-gray-400">Review your scores before submitting.</p>
                </div>

                <div class="bg-gray-900/60 backdrop-blur border border-gray-800 rounded-2xl overflow-hidden shadow-2xl">
                    <div class="max-h-[50vh] overflow-y-auto p-4 custom-scrollbar">
                        <div v-for="(rate, index) in form.ratings" :key="index" class="flex items-center justify-between p-4 border-b border-gray-800 last:border-0 hover:bg-gray-800/50 transition-colors rounded-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-8 text-gray-500 font-bold text-sm">#{{ rate.song_data.peringkat }}</div>
                                <div>
                                    <div class="font-bold text-white">{{ rate.song_data.judul_lagu }}</div>
                                    <div class="text-xs text-gray-400">{{ rate.song_data.penyanyi }}</div>
                                </div>
                            </div>
                            <div class="font-black text-xl text-[#9D00FF] drop-shadow-[0_0_8px_rgba(157,0,255,0.5)]">
                                {{ parseFloat(rate.score).toFixed(1) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900/60 backdrop-blur border border-gray-800 rounded-2xl p-6">
                    <label class="block text-gray-400 font-bold mb-3 uppercase tracking-wider text-sm">Comments & Feedback (Optional)</label>
                    <textarea v-model="form.komentar_guest" rows="3" class="w-full bg-black/50 border border-gray-700 p-4 rounded-xl text-white focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all resize-none" placeholder="Share your thoughts about this ranking..."></textarea>
                </div>

                <button @click="confirmSubmit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-black uppercase tracking-widest py-5 rounded-2xl text-xl shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.7)] transition-all hover:-translate-y-1">
                    SUBMIT RESULTS
                </button>
            </div>

        </main>
    </div>

    <!-- Modals -->

    <!-- Leave Warning Modal -->
    <div v-if="showLeaveWarning" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-red-500/50 rounded-2xl p-8 max-w-md w-full shadow-[0_0_40px_rgba(239,68,68,0.2)] text-center transform scale-100 transition-all">
            <div class="w-20 h-20 bg-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Peringatan!</h3>
            <p class="text-gray-400 mb-8">Progres rating Anda akan hilang. Yakin ingin kembali ke Menu Utama?</p>
            <div class="flex gap-4">
                <button @click="cancelLeave" class="flex-1 bg-gray-800 text-white py-3 rounded-xl font-bold hover:bg-gray-700 transition-colors">Batal</button>
                <button @click="confirmLeave" class="flex-1 bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 shadow-[0_0_15px_rgba(239,68,68,0.4)] transition-colors">Ya, Keluar</button>
            </div>
        </div>
    </div>

    <!-- Submit Confirm Modal -->
    <div v-if="showSubmitConfirm" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-green-500/50 rounded-2xl p-8 max-w-md w-full shadow-[0_0_40px_rgba(16,185,129,0.2)] text-center transform scale-100 transition-all">
            <div class="w-20 h-20 bg-green-500/20 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Kirim Penilaian?</h3>
            <p class="text-gray-400 mb-8">Apakah Anda yakin dengan penilaian ini? Data yang sudah dikirim tidak dapat diubah kembali.</p>
            <div class="flex gap-4">
                <button @click="showSubmitConfirm = false" class="flex-1 bg-gray-800 text-white py-3 rounded-xl font-bold hover:bg-gray-700 transition-colors">Batal</button>
                <button @click="submitRating" class="flex-1 bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 shadow-[0_0_15px_rgba(16,185,129,0.4)] transition-colors">Ya, Kirim!</button>
            </div>
        </div>
    </div>

</template>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(157, 0, 255, 0.3);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(157, 0, 255, 0.5);
}
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.5s ease-out forwards;
}
/* Style range slider track slightly more */
input[type=range]::-webkit-slider-thumb {
    -webkit-appearance: none;
    height: 24px;
    width: 24px;
    border-radius: 50%;
    background: #fff;
    cursor: pointer;
    box-shadow: 0 0 10px rgba(157, 0, 255, 0.8), inset 0 0 4px #9D00FF;
    border: 2px solid #9D00FF;
}
</style>
