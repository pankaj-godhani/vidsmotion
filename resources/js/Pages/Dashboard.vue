<script setup>
import { Head } from '@inertiajs/vue3';
import HeaderMenu from '@/Components/HeaderMenu.vue';

// HeaderMenu component handles all navigation logic
</script>

<template>
    <Head title="Dashboard" />

    <div class="min-h-screen bg-black text-white">
        <!-- Header Menu Component -->
        <HeaderMenu current-page="dashboard" :can-login="true" :can-register="true" />

        <!-- Main Content -->
        <div class="pt-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Welcome to VidsMotion
                    </h1>
                    <p class="text-xl text-gray-300 mb-12">Choose your dashboard to get started</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <a
                        href="/video-generator"
                        class="block p-8 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-all transform hover:-translate-y-1"
                    >
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-white mb-3">Video Generator</h4>
                            <p class="text-gray-300">Create amazing videos from text prompts using AI</p>
                        </div>
                    </a>

                    <a
                        href="/my-files"
                        class="block p-8 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-all"
                    >
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-white mb-3">My Files</h4>
                            <p class="text-gray-300">Upload files, track processing status, and download results</p>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</template>
