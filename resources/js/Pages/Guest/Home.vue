<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const mouseX = ref(0);
const mouseY = ref(0);

const handleMouseMove = (e) => {
    mouseX.value = e.clientX;
    mouseY.value = e.clientY;
};

const props = defineProps({
    canLogin: Boolean,
    lastUpdate: String,
    favoriteAnimes: {
        type: Array,
        default: () => []
    },
    favoriteMangas: {
        type: Array,
        default: () => []
    },
    waifus: {
        type: Array,
        default: () => []
    }
});

const loadingWaifus = ref(true);

const splitTextToSpans = (text) => {
    return text.split('').map(char => {
        if (char === ' ') return '&nbsp;';
        return `<span class="inline-block char-anim">${char}</span>`;
    }).join('');
};

onMounted(() => {
    window.addEventListener('mousemove', handleMouseMove);

    nextTick(() => {
        // Anime Title Animation
        const animeTitle = document.querySelector('.anime-title');
        if (animeTitle) {
            animeTitle.innerHTML = splitTextToSpans(animeTitle.innerText);
            gsap.from('.anime-title .char-anim', {
                scrollTrigger: {
                    trigger: '.anime-title',
                    start: 'top bottom-=100',
                },
                rotationY: 360,
                opacity: 0,
                duration: 1,
                stagger: 0.05,
                ease: 'power3.out'
            });
        }

        // Manga Title Animation
        const mangaTitle = document.querySelector('.manga-title');
        if (mangaTitle) {
            mangaTitle.innerHTML = splitTextToSpans(mangaTitle.innerText);
            gsap.from('.manga-title .char-anim', {
                scrollTrigger: {
                    trigger: '.manga-title',
                    start: 'top bottom-=100',
                },
                rotationY: -360,
                opacity: 0,
                duration: 1,
                stagger: 0.05,
                ease: 'power3.out'
            });
        }
    });
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
    ScrollTrigger.getAll().forEach(t => t.kill());
});
</script>

<template>
    <Head title="Welcome to Anisong" />

    <div class="min-h-screen bg-[#0D0D12] text-white flex flex-col relative overflow-x-hidden font-sans selection:bg-[#9D00FF] selection:text-white">
        <!-- Interactive Glowing Aura -->
        <div 
            class="pointer-events-none fixed inset-0 z-0 transition-opacity duration-300"
            :style="{
                background: `radial-gradient(circle 600px at ${mouseX}px ${mouseY}px, rgba(157, 0, 255, 0.15), transparent 80%)`
            }"
        ></div>

        <!-- Header / Nav (Optional, mostly clean for SPA) -->
        <nav class="relative z-10 w-full p-6 flex justify-between items-center max-w-7xl mx-auto">
            <div class="font-black text-2xl tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
                ANI<span class="text-[#9D00FF]">SONG</span>.
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col items-center justify-center relative z-10 px-4 w-full max-w-7xl mx-auto py-20 gap-32">
            
            <!-- Hero Section -->
            <div class="text-center max-w-4xl mx-auto w-full min-h-[60vh] flex flex-col justify-center">
                <h1 class="text-5xl md:text-7xl font-black mb-6 tracking-tighter drop-shadow-md">
                    Top 100 <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#9D00FF] to-fuchsia-500">Anime Songs</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-2xl mx-auto font-medium">
                    Temukan dan beri rating pada lagu tema anime favoritmu berdasarkan kualitas musik dan emosi yang dibawanya.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link href="/list" class="px-8 py-4 bg-gray-900 border border-gray-700 text-white rounded-lg font-bold text-lg hover:border-[#9D00FF] hover:text-[#9D00FF] hover:shadow-[0_0_20px_rgba(157,0,255,0.4)] transition-all duration-300">
                        Lihat List Lagu
                    </Link>
                    <Link href="/rate" class="px-8 py-4 bg-[#9D00FF] text-white rounded-lg font-bold text-lg shadow-[0_0_15px_rgba(157,0,255,0.6)] hover:bg-[#b033ff] hover:shadow-[0_0_30px_rgba(157,0,255,0.8)] hover:-translate-y-1 transition-all duration-300">
                        Mulai Rate List
                    </Link>
                </div>
            </div>

            <!-- About Me Section -->
            <div class="w-full max-w-4xl mx-auto bg-gray-900/50 backdrop-blur-xl border border-gray-800 rounded-2xl p-8 md:p-12 shadow-2xl relative overflow-hidden group hover:border-[#9D00FF]/50 transition-colors duration-500">
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#9D00FF]/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <h2 class="text-3xl font-bold mb-8 text-white relative z-10">About Creator</h2>
                <div class="flex flex-col md:flex-row gap-8 items-center md:items-start relative z-10">
                    
                    <!-- Profile Picture with Skeleton Shimmer -->
                    <div class="w-32 h-32 md:w-40 md:h-40 shrink-0 relative rounded-full overflow-hidden border-2 border-gray-700 group-hover:border-[#9D00FF] transition-colors duration-500 shadow-[0_0_15px_rgba(0,0,0,0.5)] group-hover:shadow-[0_0_25px_rgba(157,0,255,0.3)]">
                        <div class="absolute inset-0 bg-gray-800 animate-pulse"></div>
                        <img src="" alt="" class="absolute inset-0 w-full h-full object-cover z-10 opacity-0 transition-opacity duration-300" onload="this.classList.remove('opacity-0'); this.previousElementSibling.remove()">
                    </div>

                    <div class="text-center md:text-left">
                        <h3 class="text-2xl font-bold text-gray-100 mb-2">DaremonAxe2</h3>
                        <p class="text-[#9D00FF] font-medium mb-4">Fullstack Developer</p>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            Saya adalah seorang Mahasiswa aktif tahun terakhir, dan juga sebagai Junior Web Developer. Memiliki minat dan ketertarikan dibidang teknologi terutama pada pengembangan web dan mobile.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Anime Favorite Section -->
            <div class="w-full relative py-10">
                <div class="text-center mb-12">
                    <h2 class="anime-title text-4xl md:text-5xl font-black text-white inline-block perspective-[1000px]">Anime Favorite</h2>
                </div>
                <!-- Infinite Looping Carousel Container -->
                <div class="relative w-full overflow-hidden z-10 py-8 flex group/carousel" style="-webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);">
                    <!-- Marquee Track -->
                    <div class="flex w-max animate-marquee group-hover/carousel:[animation-play-state:paused]">
                        <!-- Set 1 -->
                        <div class="flex gap-4 md:gap-6 px-2 md:px-3">
                            <div v-for="anime in favoriteAnimes" :key="anime.id" class="w-64 md:w-80 h-96 relative rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.5)] group border border-gray-800 hover:border-[#9D00FF] transition-colors duration-500 shrink-0">
                                <img :src="'/storage/' + anime.cover_image" :alt="anime.title" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 pointer-events-none">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D12] via-[#0D0D12]/40 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-6 w-full">
                                    <h3 class="text-2xl font-bold text-white mb-1 drop-shadow-lg">{{ anime.title }}</h3>
                                    <p class="text-sm text-[#9D00FF] font-semibold">{{ anime.studio }} • {{ anime.release_year }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-yellow-400 font-bold">★ {{ anime.rating }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Set 2 (Duplicate for seamless loop) -->
                        <div class="flex gap-4 md:gap-6 px-2 md:px-3" aria-hidden="true">
                            <div v-for="anime in favoriteAnimes" :key="'dup-'+anime.id" class="w-64 md:w-80 h-96 relative rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.5)] group border border-gray-800 hover:border-[#9D00FF] transition-colors duration-500 shrink-0">
                                <img :src="'/storage/' + anime.cover_image" :alt="anime.title" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 pointer-events-none">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D12] via-[#0D0D12]/40 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-6 w-full">
                                    <h3 class="text-2xl font-bold text-white mb-1 drop-shadow-lg">{{ anime.title }}</h3>
                                    <p class="text-sm text-[#9D00FF] font-semibold">{{ anime.studio }} • {{ anime.release_year }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-yellow-400 font-bold">★ {{ anime.rating }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manga Favorite Section -->
            <div class="w-full relative py-10 mt-[-2rem]">
                <div class="text-center mb-12">
                    <h2 class="manga-title text-4xl md:text-5xl font-black text-white inline-block perspective-[1000px]">Manga Favorite</h2>
                </div>
                <!-- Infinite Looping Carousel Container -->
                <div class="relative w-full overflow-hidden z-10 py-8 flex group/carousel" style="-webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);">
                    <!-- Marquee Track -->
                    <div class="flex w-max animate-marquee group-hover/carousel:[animation-play-state:paused]">
                        <!-- Set 1 -->
                        <div class="flex gap-4 md:gap-6 px-2 md:px-3">
                            <div v-for="manga in favoriteMangas" :key="manga.id" class="w-64 md:w-80 h-96 relative rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.5)] group border border-gray-800 hover:border-[#9D00FF] transition-colors duration-500 shrink-0">
                                <img :src="'/storage/' + manga.cover_image" :alt="manga.title" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 pointer-events-none">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D12] via-[#0D0D12]/40 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-6 w-full">
                                    <h3 class="text-2xl font-bold text-white mb-1 drop-shadow-lg">{{ manga.title }}</h3>
                                    <p class="text-sm text-[#9D00FF] font-semibold">{{ manga.author }} • {{ manga.release_year }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-yellow-400 font-bold">★ {{ manga.status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Set 2 (Duplicate for seamless loop) -->
                        <div class="flex gap-4 md:gap-6 px-2 md:px-3" aria-hidden="true">
                            <div v-for="manga in favoriteMangas" :key="'dup-'+manga.id" class="w-64 md:w-80 h-96 relative rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.5)] group border border-gray-800 hover:border-[#9D00FF] transition-colors duration-500 shrink-0">
                                <img :src="'/storage/' + manga.cover_image" :alt="manga.title" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 pointer-events-none">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D12] via-[#0D0D12]/40 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-6 w-full">
                                    <h3 class="text-2xl font-bold text-white mb-1 drop-shadow-lg">{{ manga.title }}</h3>
                                    <p class="text-sm text-[#9D00FF] font-semibold">{{ manga.author }} • {{ manga.release_year }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-yellow-400 font-bold">★ {{ manga.status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Waifu Favorite Section -->
            <div class="w-full relative py-10 mt-[-2rem]">
                <div class="text-center mb-12">
                    <h2 class="text-4xl md:text-5xl font-black text-white inline-block perspective-[1000px]">Gallery of Waifu</h2>
                </div>
                <!-- Infinite Looping Carousel Container -->
                <div class="relative w-full overflow-hidden z-10 py-8 flex group/carousel" style="-webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);">
                    <!-- Marquee Track -->
                    <div class="flex w-max animate-marquee group-hover/carousel:[animation-play-state:paused]">
                        <!-- Set 1 -->
                        <div class="flex gap-4 md:gap-6 px-2 md:px-3">
                            <div v-for="waifu in waifus" :key="waifu.id" class="w-64 md:w-80 h-96 relative rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.5)] group border border-gray-800 hover:border-[#9D00FF] transition-colors duration-500 shrink-0">
                                <img :src="'/storage/' + waifu.image_path" :alt="waifu.name" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 pointer-events-none">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D12] via-[#0D0D12]/40 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-6 w-full">
                                    <h3 class="text-2xl font-bold text-white mb-1 drop-shadow-lg">{{ waifu.name }}</h3>
                                    <p class="text-sm text-[#9D00FF] font-semibold">{{ waifu.anime_title }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Set 2 (Duplicate for seamless loop) -->
                        <div class="flex gap-4 md:gap-6 px-2 md:px-3" aria-hidden="true">
                            <div v-for="waifu in waifus" :key="'dup-'+waifu.id" class="w-64 md:w-80 h-96 relative rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.5)] group border border-gray-800 hover:border-[#9D00FF] transition-colors duration-500 shrink-0">
                                <img :src="'/storage/' + waifu.image_path" :alt="waifu.name" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 pointer-events-none">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D12] via-[#0D0D12]/40 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-6 w-full">
                                    <h3 class="text-2xl font-bold text-white mb-1 drop-shadow-lg">{{ waifu.name }}</h3>
                                    <p class="text-sm text-[#9D00FF] font-semibold">{{ waifu.anime_title }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

        </main>

        <!-- Footer / Social Media -->
        <footer class="relative z-10 border-t border-gray-800 bg-[#0D0D12]/80 backdrop-blur mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">© 2026 Anime Song Management. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="https://github.com/maekelgani" class="text-gray-500 hover:text-[#9D00FF] hover:drop-shadow-[0_0_8px_rgba(157,0,255,0.8)] transition-all duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                    </a>
                    <a href="https://myanimelist.net/profile/DaremonAxe2" class="text-gray-500 hover:text-[#9D00FF] hover:drop-shadow-[0_0_8px_rgba(157,0,255,0.8)] transition-all duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M14.921 6.479c-.82 0-3.683 0-4.947 3.156-.662 1.652-.986 4.812.876 7.886l1.934-1.41s-.767-1.095-1.083-3.191h2.897l.022 3.19h2.604V8.835h-2.581v2.043l-2.46-.023s.413-2.408 2.877-2.336h2.454l-.572-2.04ZM0 6.528v9.624h2.348v-5.84l2.031 2.664 2.047-2.652v5.828h2.336V6.528H6.437L4.368 9.474 2.31 6.528Zm18.447.022v9.583h5.022L24 14.09h-3.232V6.55Z"/></svg>
                    </a>
                    <a href="https://anilist.co/user/DaremonAxe2/" class="text-gray-500 hover:text-[#9D00FF] hover:drop-shadow-[0_0_8px_rgba(157,0,255,0.8)] transition-all duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M24 17.53v2.421c0 .71-.391 1.101-1.1 1.101h-5l-.057-.165L11.84 3.736c.106-.502.46-.788 1.053-.788h2.422c.71 0 1.1.391 1.1 1.1v12.38H22.9c.71 0 1.1.392 1.1 1.101zM11.034 2.947l6.337 18.104h-4.918l-1.052-3.131H6.019l-1.077 3.131H0L6.361 2.948h4.673zm-.66 10.96-1.69-5.014-1.541 5.015h3.23z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/eth0._" class="text-gray-500 hover:text-[#9D00FF] hover:drop-shadow-[0_0_8px_rgba(157,0,255,0.8)] transition-all duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" clip-rule="evenodd"></path></svg>
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
/* Utilities for 3D transforms */
.perspective-\[1000px\] {
    perspective: 1000px;
}
.transform-style-3d {
    transform-style: preserve-3d;
}
.rotate-y-5 {
    transform: rotateY(5deg);
}

/* Infinite Marquee Animation */
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-marquee {
    animation: marquee 30s linear infinite;
}
@keyframes marquee-reverse {
    0% { transform: translateX(-50%); }
    100% { transform: translateX(0); }
}
.animate-marquee-reverse {
    animation: marquee-reverse 30s linear infinite;
}
</style>
