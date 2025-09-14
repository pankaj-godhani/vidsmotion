<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
});

// AI Animation elements
const particles = ref([]);

onMounted(() => {
    // Create floating particles for AI effect
    for (let i = 0; i < 50; i++) {
        particles.value.push({
            id: i,
            x: Math.random() * 100,
            y: Math.random() * 100,
            size: Math.random() * 4 + 1,
            speed: Math.random() * 2 + 0.5,
            opacity: Math.random() * 0.5 + 0.1,
        });
    }
});
</script>

<template>
    <Head title="Password Reset Successful - VidsMotion" />

    <div class="min-h-screen bg-black text-white relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-900/20 via-blue-900/20 to-purple-900/20"></div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div
                v-for="particle in particles"
                :key="particle.id"
                class="absolute rounded-full bg-white/10 animate-pulse"
                :style="{
                    left: particle.x + '%',
                    top: particle.y + '%',
                    width: particle.size + 'px',
                    height: particle.size + 'px',
                    opacity: particle.opacity,
                    animationDelay: Math.random() * 2 + 's',
                    animationDuration: (Math.random() * 3 + 2) + 's'
                }"
            ></div>
        </div>

        <!-- Success Animation -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="successGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#10B981;stop-opacity:0.3" />
                        <stop offset="50%" style="stop-color:#3B82F6;stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:#8B5CF6;stop-opacity:0.3" />
                    </linearGradient>
                </defs>
                <circle cx="20" cy="20" r="2" fill="url(#successGradient)" opacity="0.6">
                    <animate attributeName="r" values="2;4;2" dur="3s" repeatCount="indefinite" />
                </circle>
                <circle cx="80" cy="30" r="1.5" fill="url(#successGradient)" opacity="0.4">
                    <animate attributeName="r" values="1.5;3;1.5" dur="4s" repeatCount="indefinite" />
                </circle>
                <circle cx="50" cy="70" r="2.5" fill="url(#successGradient)" opacity="0.5">
                    <animate attributeName="r" values="2.5;4.5;2.5" dur="5s" repeatCount="indefinite" />
                </circle>
            </svg>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 flex min-h-screen items-center justify-center p-8">
            <div class="max-w-md mx-auto w-full text-center">
                <!-- Success Icon -->
                <div class="relative mb-8">
                    <div class="w-32 h-32 mx-auto relative">
                        <!-- Outer Ring -->
                        <div class="absolute inset-0 rounded-full border-4 border-green-500/50 animate-ping"></div>
                        <!-- Middle Ring -->
                        <div class="absolute inset-4 rounded-full border-4 border-green-500/70 animate-pulse"></div>
                        <!-- Inner Core -->
                        <div class="absolute inset-8 rounded-full bg-gradient-to-r from-green-500 to-blue-500 flex items-center justify-center">
                            <!-- Checkmark -->
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-8">
                    <h1 class="text-3xl font-bold text-white mb-4 bg-gradient-to-r from-green-400 to-blue-400 bg-clip-text text-transparent">
                        🎉 Password Reset Successful!
                    </h1>

                    <p class="text-xl text-gray-300 mb-6">
                        Your VidsMotion password has been successfully updated. You can now log in with your new password.
                    </p>

                    <!-- Email Display -->
                    <div class="mb-8 p-4 bg-green-500/20 border border-green-500/30 rounded-lg">
                        <p class="text-green-300 text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            {{ email }}
                        </p>
                    </div>

                    <!-- Next Steps -->
                    <div class="space-y-4 text-left mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-white">✅ Password successfully updated</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                            <span class="text-white">🔐 Account security enhanced</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                            <span class="text-white">🎬 Ready to create amazing videos</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <Link
                            :href="route('login')"
                            class="w-full bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500/50 inline-flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign In to Your Account
                        </Link>

                        <Link
                            :href="route('welcome')"
                            class="w-full bg-gray-800/50 hover:bg-gray-700/50 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 border border-gray-600 hover:border-gray-500 inline-flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Back to Home
                        </Link>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mt-8 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                    <p class="text-blue-300 text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Your account is now secure with your new password. Keep it safe!
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
