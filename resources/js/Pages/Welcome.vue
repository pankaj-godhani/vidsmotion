<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import Footer from '@/Components/Footer.vue';
import HeaderMenu from '@/Components/HeaderMenu.vue';
import NotificationManager from '@/Components/NotificationManager.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const visibleVideos = ref(6);
const isLoadingMore = ref(false);

// AI Animation particles
const particles = ref([]);

// Sample video data
const allVideos = ref([
    {
        id: 1,
        title: "Futuristic cityscape at sunset",
        description: "A cinematic view of a futuristic city with flying cars and neon lights",
        duration: "0:05",
        views: "1.2k",
        timeAgo: "2 days ago",
        gradient: "from-purple-500/20 to-pink-500/20"
    },
    {
        id: 2,
        title: "Ocean waves in slow motion",
        description: "Beautiful ocean waves crashing against rocks in cinematic slow motion",
        duration: "0:08",
        views: "856",
        timeAgo: "5 days ago",
        gradient: "from-blue-500/20 to-cyan-500/20"
    },
    {
        id: 3,
        title: "Forest with magical creatures",
        description: "An enchanted forest with glowing mushrooms and mystical creatures",
        duration: "0:12",
        views: "2.1k",
        timeAgo: "1 week ago",
        gradient: "from-green-500/20 to-emerald-500/20"
    },
    {
        id: 4,
        title: "Space exploration journey",
        description: "A spacecraft traveling through the cosmos with stunning nebula effects",
        duration: "0:06",
        views: "3.4k",
        timeAgo: "3 days ago",
        gradient: "from-orange-500/20 to-red-500/20"
    },
    {
        id: 5,
        title: "Abstract digital art",
        description: "Flowing digital patterns and geometric shapes in motion",
        duration: "0:10",
        views: "892",
        timeAgo: "1 day ago",
        gradient: "from-indigo-500/20 to-purple-500/20"
    },
    {
        id: 6,
        title: "Underwater coral reef",
        description: "Colorful coral reef with tropical fish swimming in crystal clear water",
        duration: "0:07",
        views: "1.5k",
        timeAgo: "4 days ago",
        gradient: "from-teal-500/20 to-cyan-500/20"
    },
    {
        id: 7,
        title: "Mountain peak at dawn",
        description: "Majestic mountain peaks with golden sunrise lighting and misty valleys",
        duration: "0:09",
        views: "2.3k",
        timeAgo: "6 days ago",
        gradient: "from-amber-500/20 to-yellow-500/20"
    },
    {
        id: 8,
        title: "Cyberpunk street scene",
        description: "Neon-lit streets with futuristic architecture and flying vehicles",
        duration: "0:11",
        views: "1.8k",
        timeAgo: "2 days ago",
        gradient: "from-violet-500/20 to-fuchsia-500/20"
    },
    {
        id: 9,
        title: "Desert sand dunes",
        description: "Endless sand dunes with dramatic shadows and wind patterns",
        duration: "0:08",
        views: "967",
        timeAgo: "1 week ago",
        gradient: "from-yellow-500/20 to-orange-500/20"
    },
    {
        id: 10,
        title: "Arctic aurora display",
        description: "Northern lights dancing across the arctic sky with snow-covered landscape",
        duration: "0:13",
        views: "3.1k",
        timeAgo: "4 days ago",
        gradient: "from-cyan-500/20 to-blue-500/20"
    },
    {
        id: 11,
        title: "Tropical waterfall",
        description: "Crystal clear waterfall cascading through lush tropical vegetation",
        duration: "0:07",
        views: "1.4k",
        timeAgo: "3 days ago",
        gradient: "from-emerald-500/20 to-teal-500/20"
    },
    {
        id: 12,
        title: "Steampunk laboratory",
        description: "Victorian-era laboratory with brass machinery and steam-powered devices",
        duration: "0:10",
        views: "1.7k",
        timeAgo: "5 days ago",
        gradient: "from-rose-500/20 to-pink-500/20"
    }
]);


const loadMoreVideos = async () => {
    isLoadingMore.value = true;

    // Simulate loading delay
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Show 6 more videos
    visibleVideos.value = Math.min(visibleVideos.value + 6, allVideos.value.length);
    isLoadingMore.value = false;
};

const displayedVideos = computed(() => {
    return allVideos.value.slice(0, visibleVideos.value);
});

const hasMoreVideos = computed(() => {
    return visibleVideos.value < allVideos.value.length;
});

// Initialize particles
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

onUnmounted(() => {
    // Cleanup if needed
});
</script>

<template>
    <Head title="Vidsmotion - Create Videos with AI" />
    <div class="min-h-screen bg-black text-white">
        <!-- Header Menu Component -->
        <HeaderMenu current-page="welcome" :can-login="canLogin" :can-register="canRegister" />




        <!-- Hero Section with AI Video Background -->
        <div class="relative pt-24 pb-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
            <!-- AI Video Background -->
            <div class="absolute inset-0 z-0">
                <!-- Video Background -->
                <video
                    class="w-full h-full object-cover opacity-15"
                    autoplay
                    muted
                    loop
                    playsinline
                >
                    <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4" type="video/mp4">
                    <!-- Fallback for browsers that don't support video -->
                    <div class="w-full h-full bg-gradient-to-br from-purple-900/20 via-blue-900/20 to-cyan-900/20"></div>
                </video>

                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/80"></div>

                <!-- AI Pattern Overlay -->
                <div class="absolute inset-0 opacity-20">
                    <div class="w-full h-full grid-pattern"></div>
                </div>

                <!-- Floating Particles -->
                <div class="absolute inset-0 overflow-hidden">
                    <div
                        v-for="particle in particles"
                        :key="particle.id"
                        class="absolute rounded-full bg-white/5 animate-pulse"
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
            </div>

            <!-- Content -->
            <div class="relative z-10 max-w-6xl mx-auto">
                <!-- Floating AI Elements -->
                <div class="absolute -top-20 -left-20 w-40 h-40 bg-gradient-to-r from-purple-500/20 to-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -bottom-20 -right-20 w-60 h-60 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>

                <div class="text-center mb-16 relative">
                    <!-- Animated Badge -->
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600/20 to-blue-600/20 border border-purple-500/30 rounded-full mb-8 backdrop-blur-sm">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-ping mr-3"></div>
                        <span class="text-sm font-medium text-white">✨ AI-Powered Video Generation</span>
                    </div>

                    <!-- Enhanced Main Heading -->
                    <h1 class="text-6xl md:text-8xl font-bold text-white mb-8 leading-tight drop-shadow-2xl relative">
                        <span class="inline-block animate-float">Create</span>
                        <span class="inline-block animate-float" style="animation-delay: 0.5s;">Videos</span>
                        <span class="block bg-gradient-to-r from-purple-400 via-blue-400 to-cyan-400 bg-clip-text text-transparent animate-float" style="animation-delay: 1s;">
                            with AI
                        </span>
                        <!-- Floating particles around text -->
                        <div class="absolute -top-4 -right-4 w-3 h-3 bg-purple-400 rounded-full animate-ping opacity-60"></div>
                        <div class="absolute -bottom-2 -left-4 w-2 h-2 bg-blue-400 rounded-full animate-ping opacity-60" style="animation-delay: 0.5s;"></div>
                        <div class="absolute top-1/2 -right-8 w-2 h-2 bg-cyan-400 rounded-full animate-ping opacity-60" style="animation-delay: 1s;"></div>
                    </h1>

                    <!-- Enhanced Description -->
                    <div class="max-w-3xl mx-auto mb-12">
                        <p class="text-xl text-gray-200 mb-6 drop-shadow-lg leading-relaxed">
                            Transform your ideas into stunning videos using advanced AI technology.
                        </p>
                        <p class="text-lg text-gray-300 drop-shadow-lg">
                            Just describe what you want to see, and watch the magic happen.
                        </p>

                        <!-- Feature Highlights -->
                        <div class="flex flex-wrap justify-center gap-6 mt-8">
                            <div class="flex items-center space-x-2 bg-white/5 backdrop-blur-sm rounded-full px-4 py-2 border border-white/10">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-300">Lightning Fast</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white/5 backdrop-blur-sm rounded-full px-4 py-2 border border-white/10">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-300">High Quality</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white/5 backdrop-blur-sm rounded-full px-4 py-2 border border-white/10">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-300">Easy to Use</span>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-20">
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="group relative px-10 py-5 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-semibold text-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-2xl hover:shadow-purple-500/25 overflow-hidden"
                        >
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="relative flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Start Creating Free
                            </span>
                        </Link>
                        <Link
                            v-if="canLogin && $page.props.auth.user && $page.props.auth.activeSubscription"
                            :href="route('video-generator')"
                            class="group relative px-10 py-5 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-semibold text-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-2xl hover:shadow-purple-500/25 overflow-hidden"
                        >
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="relative flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Go to Video Generator
                            </span>
                        </Link>
                        <button class="group relative px-10 py-5 border-2 border-white/30 text-white rounded-xl font-semibold text-lg hover:border-white hover:bg-white/10 transition-all transform hover:scale-105 backdrop-blur-sm overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="relative flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-6a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Watch Demo
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Enhanced Video Preview Section -->
                <div class="relative max-w-6xl mx-auto">
                    <!-- Floating Elements Around Video -->
                    <div class="absolute -top-10 -left-10 w-20 h-20 bg-gradient-to-r from-purple-500/30 to-blue-500/30 rounded-full blur-2xl animate-pulse"></div>
                    <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-gradient-to-r from-blue-500/30 to-cyan-500/30 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>

                    <div class="relative group">
                        <!-- Main Video Container -->
                        <div class="aspect-video bg-gray-900/90 backdrop-blur-sm rounded-3xl overflow-hidden border border-gray-600/50 shadow-2xl group-hover:shadow-purple-500/20 transition-all duration-500 group-hover:scale-[1.02]">
                            <!-- AI Video Background Inside Preview -->
                            <div class="absolute inset-0 opacity-40 group-hover:opacity-50 transition-opacity duration-500">
                                <video
                                    class="w-full h-full object-cover"
                                    autoplay
                                    muted
                                    loop
                                    playsinline
                                >
                                    <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4" type="video/mp4">
                                </video>
                            </div>

                            <!-- Animated Grid Overlay -->
                            <div class="absolute inset-0 opacity-10 group-hover:opacity-15 transition-opacity duration-500">
                                <div class="w-full h-full grid-pattern"></div>
                            </div>

                            <!-- Content Overlay -->
                            <div class="relative z-10 w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <!-- Enhanced Animated AI Icon -->
                                    <div class="relative mb-8">
                                        <div class="w-32 h-32 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center mx-auto shadow-2xl group-hover:shadow-purple-500/50 transition-all duration-500">
                                            <!-- Rotating Ring -->
                                            <div class="absolute inset-0 rounded-full border-2 border-white/20 animate-spin" style="animation-duration: 8s;"></div>
                                            <!-- Inner Ring -->
                                            <div class="absolute inset-4 rounded-full border border-white/30 animate-spin" style="animation-duration: 6s; animation-direction: reverse;"></div>
                                            <!-- Main Icon -->
                                            <svg class="w-16 h-16 text-white group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <!-- Floating Particles Around Icon -->
                                        <div class="absolute -top-2 -right-2 w-3 h-3 bg-purple-400 rounded-full animate-ping opacity-60"></div>
                                        <div class="absolute -bottom-2 -left-2 w-2 h-2 bg-blue-400 rounded-full animate-ping opacity-60" style="animation-delay: 0.5s;"></div>
                                        <div class="absolute top-1/2 -right-4 w-2 h-2 bg-cyan-400 rounded-full animate-ping opacity-60" style="animation-delay: 1s;"></div>
                                    </div>

                                    <!-- Enhanced Text Content -->
                                    <div class="space-y-4">
                                        <h3 class="text-2xl font-bold text-white drop-shadow-lg group-hover:text-purple-300 transition-colors duration-300">
                                            Your AI-generated video will appear here
                                        </h3>
                                        <p class="text-gray-300 text-lg group-hover:text-gray-200 transition-colors duration-300">
                                            Powered by advanced neural networks
                                        </p>

                                        <!-- Progress Indicator -->
                                        <div class="flex items-center justify-center space-x-2 mt-6">
                                            <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                                            <div class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Glowing Border Effect -->
                            <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-purple-500/20 via-blue-500/20 to-cyan-500/20 blur-sm -z-10 group-hover:from-purple-500/30 group-hover:via-blue-500/30 group-hover:to-cyan-500/30 transition-all duration-500"></div>
                        </div>

                        <!-- Corner Decorations -->
                        <div class="absolute -top-4 -left-4 w-8 h-8 border-l-2 border-t-2 border-purple-400/50 rounded-tl-lg"></div>
                        <div class="absolute -top-4 -right-4 w-8 h-8 border-r-2 border-t-2 border-blue-400/50 rounded-tr-lg"></div>
                        <div class="absolute -bottom-4 -left-4 w-8 h-8 border-l-2 border-b-2 border-cyan-400/50 rounded-bl-lg"></div>
                        <div class="absolute -bottom-4 -right-4 w-8 h-8 border-r-2 border-b-2 border-purple-400/50 rounded-br-lg"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Gallery Section -->
        <div class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Explore AI-Generated Videos
                    </h2>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                        Discover amazing videos created by our AI. Get inspired and see what's possible.
                    </p>
                </div>

                <!-- Video Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <div
                        v-for="video in displayedVideos"
                        :key="video.id"
                        class="group cursor-pointer"
                    >
                        <div class="relative aspect-video bg-gray-800 rounded-xl overflow-hidden mb-4">
                            <div :class="`absolute inset-0 bg-gradient-to-br ${video.gradient}`"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-6a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-white text-sm font-medium">{{ video.duration }}</span>
                                    <div class="flex items-center space-x-2">
                                        <button class="p-2 bg-white/20 rounded-full backdrop-blur-sm hover:bg-white/30 transition-colors">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                        <button class="p-2 bg-white/20 rounded-full backdrop-blur-sm hover:bg-white/30 transition-colors">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-medium text-white">AI</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-white font-medium mb-1 group-hover:text-gray-300 transition-colors">
                                    {{ video.title }}
                                </h3>
                                <p class="text-gray-400 text-sm">
                                    {{ video.description }}
                                </p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-gray-500 text-xs">{{ video.timeAgo }}</span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                        <span class="text-gray-500 text-xs">{{ video.views }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                            </div>

                <!-- Load More Button -->
                <div class="text-center">
                    <button
                        v-if="hasMoreVideos"
                        @click="loadMoreVideos"
                        :disabled="isLoadingMore"
                        :class="[
                            'px-8 py-3 border border-gray-600 text-white rounded-lg transition-all',
                            isLoadingMore
                                ? 'opacity-50 cursor-not-allowed'
                                : 'hover:border-gray-500'
                        ]"
                    >
                        <div v-if="isLoadingMore" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </div>
                        <div v-else>
                            Load More Videos
                        </div>
                    </button>
                </div>
            </div>
        </div>


        <!-- CTA Section -->
        <div class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Ready to Create?
                </h2>
                <p class="text-xl text-gray-300 mb-12">
                    Join thousands of creators who are already using Vidsmotion to bring their ideas to life.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-12 py-4 bg-white text-black rounded-lg font-bold text-xl hover:bg-gray-100 transition-all"
                    >
                        Get Started Now
                    </Link>
                    <Link
                        :href="route('features')"
                        class="px-12 py-4 border-2 border-white text-white rounded-lg font-bold text-xl hover:bg-white hover:text-black transition-all"
                    >
                        Explore Features
                    </Link>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer :laravel-version="laravelVersion" :php-version="phpVersion" />

        <!-- Notification Manager -->
        <NotificationManager />
    </div>
</template>

<style scoped>
/* Grid Pattern */
.grid-pattern {
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

/* Smooth motion animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #1f2937;
}

::-webkit-scrollbar-thumb {
    background: #8b5cf6;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #7c3aed;
}
</style>
