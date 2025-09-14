<template>
    <Head title="Explore - Vidsmotion" />

    <div class="min-h-screen bg-black text-white">
        <!-- Header Menu Component -->
        <HeaderMenu current-page="explore" :can-login="canLogin" :can-register="canRegister" />

        <!-- Main Content -->
        <div class="pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Page Header -->
                <div class="text-center mb-12">
                    <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 leading-tight">
                        Explore
                        <span class="bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">AI Videos</span>
                    </h1>
                    <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                        Discover amazing AI-generated videos created by our community. Get inspired and see what's possible with Vidsmotion.
                    </p>
                </div>


                <!-- Filter and Sort Bar -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 space-y-4 md:space-y-0">
                    <!-- Filter Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="filter in filters"
                            :key="filter.id"
                            @click="activeFilter = filter.id"
                            :class="[
                                'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                activeFilter === filter.id
                                    ? 'bg-white text-black'
                                    : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            {{ filter.name }}
                        </button>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-400 text-sm">Sort by:</span>
                        <select
                            v-model="sortBy"
                            :disabled="isSorting"
                            class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <option value="latest">Latest</option>
                            <option value="popular">Most Popular</option>
                            <option value="trending">Trending</option>
                        </select>

                        <!-- AI Loader -->
                        <div v-if="isSorting" class="flex items-center space-x-2 ml-4">
                            <div class="ai-loader">
                                <div class="ai-loader-dot"></div>
                                <div class="ai-loader-dot"></div>
                                <div class="ai-loader-dot"></div>
                            </div>
                            <span class="text-sm text-gray-400">AI is sorting...</span>
                        </div>
                    </div>
                </div>

                <!-- Video Grid -->
                <div class="relative">
                    <!-- Sorting Overlay -->
                    <div v-if="isSorting" class="absolute inset-0 bg-black/20 backdrop-blur-sm z-10 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <div class="ai-loader mx-auto mb-3">
                                <div class="ai-loader-dot"></div>
                                <div class="ai-loader-dot"></div>
                                <div class="ai-loader-dot"></div>
                            </div>
                            <p class="text-white text-sm font-medium">AI is organizing videos...</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" :class="{ 'opacity-50': isSorting }">
                    <div
                        v-for="video in filteredVideos"
                        :key="video.id"
                        class="group cursor-pointer bg-gray-900/50 rounded-2xl p-4 border border-gray-800 hover:border-gray-600 transition-all duration-300 hover:bg-gray-900/70"
                    >
                        <div class="relative aspect-video bg-gray-900 rounded-xl overflow-hidden border border-gray-700 hover:border-gray-500 transition-all duration-300 group-hover:scale-[1.02] mb-4" @click="openVideoModal(video)">
                            <!-- Video Thumbnail -->
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-blue-500/20"></div>
                            <img
                                :src="video.thumbnail"
                                :alt="video.title"
                                class="w-full h-full object-cover"
                            />

                            <!-- Play Button Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Duration Badge -->
                            <div class="absolute bottom-3 right-3 bg-black/80 text-white text-xs px-2 py-1 rounded">
                                {{ video.duration }}
                            </div>

                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                {{ video.category }}
                            </div>

                        </div>

                        <!-- Video Info -->
                        <div class="mt-4">
                            <h3 class="text-white font-semibold text-lg mb-3 line-clamp-2 group-hover:text-purple-300 transition-colors">
                                {{ video.title }}
                            </h3>

                            <!-- Author and Stats -->
                            <div class="flex items-center justify-between text-sm text-gray-400 mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-white">{{ video.author.charAt(0) }}</span>
                                    </div>
                                    <span>{{ video.author }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>{{ video.views }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Recreate Button -->
                            <button
                                @click.stop="recreateVideo(video)"
                                class="w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg font-medium text-sm transition-all duration-200 flex items-center justify-center space-x-2 group/btn"
                            >
                                <svg class="w-4 h-4 group-hover/btn:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Recreate</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-12">
                    <button
                        @click="loadMoreVideos"
                        :disabled="isLoadingMore"
                        class="px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-xl font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <div v-if="isLoadingMore" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </div>
                        <span v-else>Load More Videos</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Video Modal -->
        <div v-if="selectedVideo" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm" @click="closeVideoModal">
            <div class="relative max-w-4xl w-full mx-4" @click.stop>
                <button
                    @click="closeVideoModal"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors"
                >
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">
                    <div class="aspect-video bg-black">
                        <video
                            :src="selectedVideo.videoUrl"
                            controls
                            autoplay
                            class="w-full h-full"
                        ></video>
                    </div>
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-white mb-4">{{ selectedVideo.title }}</h2>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold text-white">{{ selectedVideo.author.charAt(0) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">{{ selectedVideo.author }}</p>
                                        <p class="text-gray-400 text-sm">{{ selectedVideo.timeAgo }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button
                                    @click="recreateVideo(selectedVideo)"
                                    class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Recreate</span>
                                </button>
                                <button class="flex items-center space-x-2 px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                    <span>Share</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <Footer />

        <!-- Notification Manager -->
        <NotificationManager />
    </div>
</template>

<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
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
    auth: {
        type: Object,
        default: () => ({})
    },
    hasActiveSubscription: {
        type: Boolean,
        default: false
    },
    activeSubscription: {
        type: Object,
        default: null
    },
});

// Get page props using usePage
const page = usePage();

// Reactive data
const activeFilter = ref('all');
const sortBy = ref('latest');
const selectedVideo = ref(null);
const isLoadingMore = ref(false);
const isSorting = ref(false);
const isMenuOpen = ref(false);
const showUserMenu = ref(false);


// Filters
const filters = ref([
    { id: 'all', name: 'All' },
    { id: 'cinematic', name: 'Cinematic' },
    { id: 'realistic', name: 'Realistic' },
    { id: 'anime', name: 'Anime' },
    { id: 'cartoon', name: 'Cartoon' },
    { id: 'artistic', name: 'Artistic' }
]);

// Sample video data
const allVideos = ref([
    {
        id: 1,
        title: "Futuristic cityscape at sunset with flying cars",
        author: "Alex Chen",
        thumbnail: "https://images.unsplash.com/photo-1446776877081-d282a0f896e2?w=400&h=300&fit=crop",
        videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4",
        duration: "0:05",
        views: "45.2k",
        timeAgo: "1 hour ago",
        category: "cinematic"
    },
    {
        id: 2,
        title: "Ocean waves in slow motion with dramatic lighting",
        author: "Sarah Johnson",
        thumbnail: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop",
        videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4",
        duration: "0:08",
        views: "12.8k",
        timeAgo: "3 hours ago",
        category: "realistic"
    },
    {
        id: 3,
        title: "Magical forest with glowing creatures and mushrooms",
        author: "Mike Rodriguez",
        thumbnail: "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=300&fit=crop",
        videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4",
        duration: "0:12",
        views: "8.5k",
        timeAgo: "2 days ago",
        category: "artistic"
    },
    {
        id: 4,
        title: "Space exploration journey through colorful nebula",
        author: "Emma Wilson",
        thumbnail: "https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?w=400&h=300&fit=crop",
        videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
        duration: "0:06",
        views: "67.3k",
        timeAgo: "4 hours ago",
        category: "cinematic"
    },
    {
        id: 5,
        title: "Abstract digital art with flowing patterns",
        author: "David Kim",
        thumbnail: "https://images.unsplash.com/photo-1557683316-973673baf926?w=400&h=300&fit=crop",
        videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4",
        duration: "0:10",
        views: "3.1k",
        timeAgo: "1 week ago",
        category: "artistic"
    },
    {
        id: 6,
        title: "Underwater coral reef with tropical fish",
        author: "Lisa Park",
        thumbnail: "https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&h=300&fit=crop",
        videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4",
        duration: "0:07",
        views: "23.7k",
        timeAgo: "6 hours ago",
        category: "realistic"
    },
    {
        id: 7,
        title: "Mountain peak at dawn with golden sunrise",
        author: "Tom Anderson",
        thumbnail: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop",
        videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4",
        duration: "0:09",
        views: "15.2k",
        timeAgo: "3 days ago",
        category: "cinematic"
    },
    {
        id: 8,
        title: "Cyberpunk street scene with neon lights",
        author: "Rachel Green",
        thumbnail: "https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400&h=300&fit=crop",
        videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4",
        duration: "0:11",
        views: "5.8k",
        timeAgo: "2 weeks ago",
        category: "artistic"
    }
]);

// Helper functions for sorting
const parseViews = (viewsString) => {
    const num = parseFloat(viewsString.replace(/[^\d.]/g, ''));
    if (viewsString.includes('k')) {
        return num * 1000;
    } else if (viewsString.includes('M')) {
        return num * 1000000;
    }
    return num;
};

const parseTimeAgo = (timeAgoString) => {
    const now = new Date();
    const timeAgo = timeAgoString.toLowerCase();

    if (timeAgo.includes('hour')) {
        const hours = parseInt(timeAgo.match(/\d+/)?.[0] || '0');
        return now.getTime() - (hours * 60 * 60 * 1000);
    } else if (timeAgo.includes('day')) {
        const days = parseInt(timeAgo.match(/\d+/)?.[0] || '0');
        return now.getTime() - (days * 24 * 60 * 60 * 1000);
    } else if (timeAgo.includes('week')) {
        const weeks = parseInt(timeAgo.match(/\d+/)?.[0] || '0');
        return now.getTime() - (weeks * 7 * 24 * 60 * 60 * 1000);
    } else if (timeAgo.includes('month')) {
        const months = parseInt(timeAgo.match(/\d+/)?.[0] || '0');
        return now.getTime() - (months * 30 * 24 * 60 * 60 * 1000);
    } else if (timeAgo.includes('year')) {
        const years = parseInt(timeAgo.match(/\d+/)?.[0] || '0');
        return now.getTime() - (years * 365 * 24 * 60 * 60 * 1000);
    }
    return now.getTime(); // Default to now if can't parse
};

// Computed properties
const filteredVideos = computed(() => {
    let filtered = allVideos.value;

    // Filter by category
    if (activeFilter.value !== 'all') {
        filtered = filtered.filter(video => video.category === activeFilter.value);
    }

    // Sort videos
    switch (sortBy.value) {
        case 'popular':
            filtered = filtered.sort((a, b) => parseViews(b.views) - parseViews(a.views));
            break;
        case 'trending':
            // For trending, we'll use a combination of views and recency
            filtered = filtered.sort((a, b) => {
                const viewsA = parseViews(a.views);
                const viewsB = parseViews(b.views);
                const timeA = parseTimeAgo(a.timeAgo);
                const timeB = parseTimeAgo(b.timeAgo);
                // Trending score: views / time factor (newer videos get higher score)
                const scoreA = viewsA / timeA;
                const scoreB = viewsB / timeB;
                return scoreB - scoreA;
            });
            break;
        default: // latest
            filtered = filtered.sort((a, b) => parseTimeAgo(a.timeAgo) - parseTimeAgo(b.timeAgo));
    }

    return filtered;
});

// Methods
const openVideoModal = (video) => {
    selectedVideo.value = video;
};

const closeVideoModal = () => {
    selectedVideo.value = null;
};

const recreateVideo = (video) => {
    console.log('Recreate button clicked for video:', video);
    console.log('Video type:', typeof video);
    console.log('Video keys:', video ? Object.keys(video) : 'video is null/undefined');
    console.log('Page props:', page.props);
    console.log('Auth user:', page.props.auth?.user);
    console.log('Has active subscription:', page.props.hasActiveSubscription);

    // Check if video object is valid
    if (!video || !video.id) {
        console.error('Invalid video object:', video);
        return;
    }

    // Store recreate intent data
    const recreateData = {
        video_id: video.id,
        video_title: video.title,
        video_category: video.category,
        video_url: video.videoUrl,
        thumbnail: video.thumbnail,
        author: video.author,
        duration: video.duration,
        views: video.views,
        timestamp: new Date().toISOString()
    };

    // Check if user is authenticated
    if (!page.props.auth?.user) {
        console.log('User not authenticated, redirecting to login');
        // Redirect to login with recreate intent
        sessionStorage.setItem('recreate_intent', JSON.stringify(recreateData));
        router.visit(route('login'));
        return;
    }

    // Check if user has active subscription
    if (!page.props.hasActiveSubscription) {
        console.log('User has no active subscription, redirecting to pricing');
        // Redirect to pricing page with recreate intent
        sessionStorage.setItem('recreate_intent', JSON.stringify(recreateData));
        router.visit(route('pricing'));
        return;
    }

    console.log('User authenticated and has subscription, redirecting to video generator');
    // Store recreate intent for video generator
    sessionStorage.setItem('recreate_intent', JSON.stringify(recreateData));

    // Try different approaches to redirect
    console.log('Route URL:', route('video-generator'));
    console.log('Route with params:', route('video-generator', { recreate: 'true' }));

    // Redirect to video generator with recreate parameters
    window.location.href = route('video-generator', { recreate: 'true' });
};


const loadMoreVideos = async () => {
    isLoadingMore.value = true;

    // Simulate loading delay
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Add 4 more sample videos
    const newVideos = [
        {
            id: allVideos.value.length + 1,
            title: "Desert landscape with sand dunes at sunset",
            author: "John Smith",
            thumbnail: "https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9?w=400&h=300&fit=crop",
            videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4",
            duration: "0:08",
            views: "9.1k",
            timeAgo: "1 week ago",
            category: "realistic"
        },
        {
            id: allVideos.value.length + 2,
            title: "Fantasy castle in the clouds",
            author: "Maria Garcia",
            thumbnail: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop",
            videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/VolkswagenGTIReview.mp4",
            duration: "0:06",
            views: "12.1k",
            timeAgo: "1 week ago",
            category: "artistic"
        },
        {
            id: allVideos.value.length + 3,
            title: "Urban street art with vibrant colors",
            author: "Carlos Mendez",
            thumbnail: "https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=400&h=300&fit=crop",
            videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4",
            duration: "0:07",
            views: "18.3k",
            timeAgo: "1 week ago",
            category: "artistic"
        },
        {
            id: allVideos.value.length + 4,
            title: "Snow-covered mountain peak at dawn",
            author: "Anna Thompson",
            thumbnail: "https://images.unsplash.com/photo-1464822759844-d150baec0b0b?w=400&h=300&fit=crop",
            videoUrl: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WhatCarCanYouGetForAGrand.mp4",
            duration: "0:09",
            views: "21.7k",
            timeAgo: "1 week ago",
            category: "cinematic"
        }
    ];

    allVideos.value.push(...newVideos);
    isLoadingMore.value = false;
};

const logout = () => {
    router.post(route('logout'));
};

// Close dropdowns when clicking outside
const closeDropdowns = (event) => {
    if (!event.target.closest('.relative')) {
        showUserMenu.value = false;
    }
    if (!event.target.closest('.md\\:hidden')) {
        isMenuOpen.value = false;
    }
};

// Watch for changes in sortBy to trigger re-sorting
watch(sortBy, async (newValue, oldValue) => {
    console.log(`Sort changed from ${oldValue} to ${newValue}`);
    isSorting.value = true;

    // Simulate sorting delay for better UX
    await new Promise(resolve => setTimeout(resolve, 800));

    isSorting.value = false;
});

// Add event listener for clicking outside
onMounted(() => {
    document.addEventListener('click', closeDropdowns);
    console.log('Explore page mounted, initial sort:', sortBy.value);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdowns);
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* AI Loader Styles */
.ai-loader {
    display: flex;
    align-items: center;
    gap: 4px;
}

.ai-loader-dot {
    position: relative;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: linear-gradient(45deg, #8b5cf6, #3b82f6, #06b6d4);
    animation: ai-loader-bounce 1.4s ease-in-out infinite both;
}

.ai-loader-dot:nth-child(1) {
    animation-delay: -0.32s;
}

.ai-loader-dot:nth-child(2) {
    animation-delay: -0.16s;
}

.ai-loader-dot:nth-child(3) {
    animation-delay: 0s;
}

@keyframes ai-loader-bounce {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1.2);
        opacity: 1;
    }
}

/* Enhanced AI Loader with Glow Effect */
.ai-loader-dot::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 50%;
    background: inherit;
    filter: blur(4px);
    opacity: 0.3;
    animation: ai-loader-glow 1.4s ease-in-out infinite both;
}

@keyframes ai-loader-glow {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.2;
    }
    40% {
        transform: scale(1.4);
        opacity: 0.6;
    }
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

/* Enhanced card hover effects */
.group:hover .group-hover\:scale-\[1\.02\] {
    transform: scale(1.02);
}

/* Recreate button hover effects */
.group\/btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.4);
}

/* Category badge styling */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

/* Enhanced video card styling */
.group:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
}
</style>
