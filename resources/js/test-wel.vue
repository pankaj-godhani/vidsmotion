<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

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

const isMenuOpen = ref(false);
const showUserMenu = ref(false);
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

const logout = () => {
    router.post(route('logout'));
};

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

// Close dropdowns when clicking outside
const closeDropdowns = (event) => {
    if (!event.target.closest('.relative')) {
        showUserMenu.value = false;
        isMenuOpen.value = false;
    }
};

// Add event listener for clicking outside and initialize particles
onMounted(() => {
    document.addEventListener('click', closeDropdowns);

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
    document.removeEventListener('click', closeDropdowns);
});
</script>

<template>
    <Head title="Vidsmotion - Create Videos with AI" />
    <div class="min-h-screen bg-black text-white">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-black/80 backdrop-blur-md border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <Link :href="route('welcome')" class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                            </div>
                            <span class="text-xl font-bold text-white">Vidsmotion</span>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-gray-300 hover:text-white transition-colors text-sm">Features</a>
                        <Link :href="route('pricing')" class="text-gray-300 hover:text-white transition-colors text-sm">Pricing</Link>
                        <a href="#docs" class="text-gray-300 hover:text-white transition-colors text-sm">Docs</a>
                        <div v-if="canLogin" class="flex items-center space-x-4">
                            <template v-if="$page.props.auth.user">
                                <Link
                                    :href="route('video-generator')"
                                    class="px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-sm font-medium"
                                >
                                    Create Video
                                </Link>
                                <!-- User Dropdown -->
                                <div class="relative">
                                    <button
                                        @click="showUserMenu = !showUserMenu"
                                        class="flex items-center space-x-2 text-gray-300 hover:text-white transition-colors"
                                    >
                                        <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                        </div>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-gray-900 border border-gray-800 rounded-lg shadow-lg z-50">
                                        <div class="py-1">
                                            <div class="px-4 py-2 border-b border-gray-800">
                                                <p class="text-sm font-medium text-white">{{ $page.props.auth.user.name }}</p>
                                                <p class="text-xs text-gray-400">{{ $page.props.auth.user.email }}</p>
                                            </div>
                        <Link
                            :href="route('dashboard')"
                                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                                @click="showUserMenu = false"
                        >
                            Dashboard
                        </Link>
                                            <Link
                                                :href="route('video-generator')"
                                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                                @click="showUserMenu = false"
                                            >
                                                Video Generator
                                            </Link>
                                            <Link
                                                :href="route('user.dashboard')"
                                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                                @click="showUserMenu = false"
                                            >
                                                My Files
                                            </Link>
                                            <div class="border-t border-gray-800"></div>
                                            <button
                                                @click="logout"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                            >
                                                Sign Out
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                    class="text-gray-300 hover:text-white transition-colors text-sm"
                            >
                                    Sign In
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                    class="px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-sm font-medium"
                            >
                                    Get Started
                            </Link>
                        </template>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button
                        @click="isMenuOpen = !isMenuOpen"
                        class="md:hidden text-white"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                            </div>

                <!-- Mobile Menu -->
                <div v-if="isMenuOpen" class="md:hidden py-4 border-t border-gray-800">
                    <div class="flex flex-col space-y-4">
                        <a href="#features" class="text-gray-300 hover:text-white transition-colors text-sm">Features</a>
                        <Link :href="route('pricing')" class="text-gray-300 hover:text-white transition-colors text-sm">Pricing</Link>
                        <a href="#docs" class="text-gray-300 hover:text-white transition-colors text-sm">Docs</a>
                        <div v-if="canLogin" class="pt-4 border-t border-gray-800">
                            <template v-if="$page.props.auth.user">
                                <Link
                                    :href="route('video-generator')"
                                    class="block px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-sm font-medium text-center mb-3"
                                >
                                    Create Video
                                </Link>
                                <!-- Mobile User Menu -->
                                <div class="space-y-2">
                                    <div class="px-4 py-2 border-b border-gray-800">
                                        <p class="text-sm font-medium text-white">{{ $page.props.auth.user.name }}</p>
                                        <p class="text-xs text-gray-400">{{ $page.props.auth.user.email }}</p>
                                    </div>
                                    <Link
                                        :href="route('dashboard')"
                                        class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                    >
                                        Dashboard
                                    </Link>
                                    <Link
                                        :href="route('user.dashboard')"
                                        class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                    >
                                        My Files
                                    </Link>
                                    <button
                                        @click="logout"
                                        class="block w-full text-left text-gray-300 hover:text-white transition-colors text-sm py-2"
                                    >
                                        Sign Out
                                    </button>
                                    </div>
                            </template>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                >
                                    Sign In
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="block px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-sm font-medium text-center mt-2"
                                >
                                    Get Started
                                </Link>
                            </template>
                        </div>
                                    </div>
                                </div>
                            </div>
        </nav>

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
                <div class="text-center mb-16">
                    <h1 class="text-6xl md:text-8xl font-bold text-white mb-8 leading-tight drop-shadow-2xl">
                        Create Videos
                        <span class="block bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">with AI</span>
                    </h1>
                    <p class="text-xl text-gray-200 mb-12 max-w-2xl mx-auto drop-shadow-lg">
                        Transform your ideas into stunning videos using advanced AI technology.
                        Just describe what you want to see, and watch the magic happen.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-20">
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-8 py-4 bg-white text-black rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl"
                        >
                            Start Creating Free
                        </Link>
                        <Link
                            v-if="canLogin && $page.props.auth.user"
                            :href="route('video-generator')"
                            class="px-8 py-4 bg-white text-black rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl"
                        >
                            Go to Video Generator
                        </Link>
                        <button class="px-8 py-4 border border-gray-400 text-white rounded-lg font-semibold text-lg hover:border-white transition-all transform hover:scale-105 backdrop-blur-sm bg-white/10">
                            Watch Demo
                        </button>
                    </div>
                </div>

                <!-- Video Preview with Enhanced Styling -->
                <div class="relative max-w-5xl mx-auto">
                    <div class="aspect-video bg-gray-900/80 backdrop-blur-sm rounded-2xl overflow-hidden border border-gray-700/50 shadow-2xl">
                        <!-- AI Video Background Inside Preview -->
                        <div class="absolute inset-0 opacity-10">
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

                        <!-- Content Overlay -->
                        <div class="relative z-10 w-full h-full flex items-center justify-center">
                            <div class="text-center">
                                <!-- Animated AI Icon -->
                                <div class="w-24 h-24 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse shadow-2xl">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-200 text-lg font-medium drop-shadow-lg">Your AI-generated video will appear here</p>
                                <p class="text-gray-400 text-sm mt-2">Powered by advanced neural networks</p>
                            </div>
                        </div>

                        <!-- Glowing Border Effect -->
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-500/20 via-blue-500/20 to-cyan-500/20 blur-sm -z-10"></div>
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

        <!-- Features Section -->
        <div id="features" class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Powerful Features
                                </h2>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                        Everything you need to create professional-quality videos with AI
                                </p>
                            </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800 hover:border-gray-700 transition-all">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Lightning Fast</h3>
                        <p class="text-gray-300 leading-relaxed">
                            Generate high-quality videos in seconds, not hours. Our AI technology delivers results faster than ever before.
                        </p>
                    </div>

                    <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800 hover:border-gray-700 transition-all">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Smart AI</h3>
                        <p class="text-gray-300 leading-relaxed">
                            Advanced AI understands context and creates videos that match your vision perfectly, every time.
                                </p>
                            </div>

                    <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800 hover:border-gray-700 transition-all">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Easy to Use</h3>
                        <p class="text-gray-300 leading-relaxed">
                            No technical skills required. Simply describe your idea and let our AI do the rest.
                                </p>
                            </div>
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
                    Join thousands of creators who are already using Kling to bring their ideas to life.
                </p>
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="inline-block px-12 py-4 bg-white text-black rounded-lg font-bold text-xl hover:bg-gray-100 transition-all"
                >
                    Get Started Now
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-12 px-4 sm:px-6 lg:px-8 border-t border-gray-800">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-3 mb-4 md:mb-0">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">Vidsmotion</span>
                    </div>
                    <div class="text-gray-400 text-sm">
                        © 2025 Vidsmotion. Built with Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                    </div>
                </div>
            </div>
        </footer>
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
