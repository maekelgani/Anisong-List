<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome to Anisong" />

    <div class="min-h-screen bg-gray-900 text-white flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-900/40 to-purple-900/40 z-0"></div>
        
        <div class="z-10 text-center max-w-3xl">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
                Anime Song Management System
            </h1>
            <p class="text-lg md:text-xl text-gray-300 mb-10 leading-relaxed">
                Platform kurasi lagu-lagu anime terbaik. Jelajahi Top 100 Anisong pilihan, berikan penilaian (rate) versi Anda, dan jadilah bagian dari komunitas pecinta musik anime!
            </p>

            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <Link href="/list" class="px-8 py-4 bg-white text-gray-900 rounded-full font-bold text-lg shadow-lg hover:bg-gray-200 transition-all transform hover:scale-105">
                    Lihat List Lagu
                </Link>
                <Link href="/rate" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full font-bold text-lg shadow-lg hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105">
                    Mulai Rate List
                </Link>
            </div>
            
            <div class="mt-16 text-sm text-gray-500">
                Created for Portfolio purposes by M. Mahdi F G.
            </div>
        </div>
    </div>
</template>
