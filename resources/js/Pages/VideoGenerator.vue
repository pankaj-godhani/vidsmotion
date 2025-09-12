<template>
    <div class="min-h-screen bg-black text-white">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-black/80 backdrop-blur-md border-b border-gray-800">
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

                    <!-- Navigation -->
                    <div class="flex items-center space-x-6">
                        <button
                            @click="showHistory = !showHistory"
                            class="text-gray-300 hover:text-white transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        <button
                            @click="showSettings = !showSettings"
                            class="text-gray-300 hover:text-white transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>

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
                                        :href="route('user.dashboard')"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                        @click="showUserMenu = false"
                                    >
                                        My Files
                                    </Link>
                                    <Link
                                        :href="route('my-profile')"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                        @click="showUserMenu = false"
                                    >
                                        My Profile
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
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Panel - Generation Controls -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sticky top-24">
                            <h3 class="text-xl font-bold text-white mb-6">Create Video</h3>

                            <!-- Prompt Input -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Describe your video
                                </label>
                                <textarea
                                    v-model="prompt"
                                    placeholder="A cinematic shot of a futuristic city at sunset, with flying cars and neon lights..."
                                    class="w-full h-32 px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
                                    :disabled="isGenerating"
                                ></textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-xs text-gray-400">{{ prompt.length }}/500</span>
                                    <button
                                        v-if="prompt.length > 0"
                                        @click="clearPrompt"
                                        class="text-xs text-gray-400 hover:text-white"
                                    >
                                        Clear
                                    </button>
                                </div>
                            </div>

                            <!-- Style Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-300 mb-3">
                                    Video Style
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button
                                        v-for="style in videoStyles"
                                        :key="style.id"
                                        @click="selectedStyle = style.id"
                                        :class="[
                                            'p-3 rounded-lg border-2 transition-all text-sm font-medium',
                                            selectedStyle === style.id
                                                ? 'border-white bg-white text-black'
                                                : 'border-gray-700 hover:border-gray-600 text-gray-300'
                                        ]"
                                    >
                                        {{ style.name }}
                                    </button>
                                </div>
                            </div>

                            <!-- Duration Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-300 mb-3">
                                    Duration
                                </label>
                                <div class="p-3 rounded-lg border-2 border-white bg-white text-black text-sm font-medium">
                                    5 seconds
                                </div>
                            </div>

                            <!-- Generate Button -->
                            <button
                                @click="generateVideo"
                                :disabled="!prompt.trim() || isGenerating"
                                :class="[
                                    'w-full py-4 rounded-lg font-semibold text-lg transition-all duration-200',
                                    isGenerating
                                        ? 'bg-gray-700 text-gray-400 cursor-not-allowed'
                                        : 'bg-white text-black hover:bg-gray-100'
                                ]"
                            >
                                <div v-if="isGenerating" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Generating...
                                </div>
                                <div v-else class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-6a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Generate Video
                                </div>
                            </button>

                            <!-- Generation Progress -->
                            <div v-if="isGenerating" class="mt-4">
                                <div class="flex justify-between text-sm text-gray-300 mb-2">
                                    <span>Progress</span>
                                    <span>{{ generationProgress }}%</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2">
                                    <div
                                        class="bg-white h-2 rounded-full transition-all duration-300"
                                        :style="{ width: generationProgress + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Video Preview & Gallery -->
                    <div class="lg:col-span-2">
                        <!-- Current Video Preview -->
                        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Preview</h3>
                            <div class="aspect-video bg-gray-800 rounded-xl overflow-hidden relative">
                                <div v-if="currentVideo" class="w-full h-full">
                                    <video
                                        v-if="currentVideo.type === 'video'"
                                        :src="currentVideo.url"
                                        controls
                                        class="w-full h-full object-cover"
                                    ></video>
                                    <img
                                        v-else
                                        :src="currentVideo.url"
                                        :alt="currentVideo.prompt"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-gray-400 text-lg">Your video will appear here</p>
                                        <p class="text-gray-500 text-sm mt-2">Enter a prompt and click generate to create your video</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Video Actions -->
                            <div v-if="currentVideo" class="flex justify-between items-center mt-4">
                                <div>
                                    <h4 class="font-semibold text-white">{{ currentVideo.prompt }}</h4>
                                    <p class="text-sm text-gray-400">{{ formatDate(currentVideo.created_at) }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        @click="downloadVideo"
                                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download
                                    </button>
                                    <button
                                        @click="shareVideo"
                                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                        Share
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Video Gallery -->
                        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold text-white">Your Videos</h3>
                                <div class="flex space-x-2">
                                    <button
                                        @click="viewMode = 'grid'"
                                        :class="[
                                            'p-2 rounded-lg transition-colors',
                                            viewMode === 'grid' ? 'bg-white text-black' : 'text-gray-400 hover:text-white'
                                        ]"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="viewMode = 'list'"
                                        :class="[
                                            'p-2 rounded-lg transition-colors',
                                            viewMode === 'list' ? 'bg-white text-black' : 'text-gray-400 hover:text-white'
                                        ]"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Grid View -->
                            <div v-if="viewMode === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div
                                    v-for="video in videoHistory"
                                    :key="video.id"
                                    @click="selectVideo(video)"
                                    class="aspect-video bg-gray-800 rounded-lg overflow-hidden cursor-pointer hover:bg-gray-700 transition-colors relative group"
                                >
                                    <img
                                        :src="video.thumbnail"
                                        :alt="video.prompt"
                                        class="w-full h-full object-cover"
                                    />
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-6a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- List View -->
                            <div v-else class="space-y-3">
                                <div
                                    v-for="video in videoHistory"
                                    :key="video.id"
                                    @click="selectVideo(video)"
                                    class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-800 cursor-pointer transition-colors"
                                >
                                    <img
                                        :src="video.thumbnail"
                                        :alt="video.prompt"
                                        class="w-16 h-12 object-cover rounded"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-white truncate">{{ video.prompt }}</p>
                                        <p class="text-xs text-gray-400">{{ formatDate(video.created_at) }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            @click.stop="downloadVideo(video)"
                                            class="p-2 text-gray-400 hover:text-white transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            @click.stop="deleteVideo(video.id)"
                                            class="p-2 text-gray-400 hover:text-red-400 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div v-if="videoHistory.length === 0" class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-400 text-lg">No videos yet</p>
                                <p class="text-gray-500 text-sm mt-2">Create your first video to get started</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import axios from 'axios'
import Footer from '@/Components/Footer.vue'

// Reactive data
const prompt = ref('')
const selectedStyle = ref('cinematic')
const isGenerating = ref(false)
const generationProgress = ref(0)
const currentVideo = ref(null)
const videoHistory = ref([])
const viewMode = ref('grid')
const showHistory = ref(false)
const showSettings = ref(false)
const showUserMenu = ref(false)

// Video styles
const videoStyles = ref([
    { id: 'cinematic', name: 'Cinematic' },
    { id: 'realistic', name: 'Realistic' },
    { id: 'anime', name: 'Anime' },
    { id: 'cartoon', name: 'Cartoon' },
    { id: 'artistic', name: 'Artistic' },
    { id: 'vintage', name: 'Vintage' }
])

// Duration is fixed at 5 seconds
const selectedDuration = ref(5)

// Methods
const clearPrompt = () => {
    prompt.value = ''
}

const logout = () => {
    router.post(route('logout'));
}

// Close dropdowns when clicking outside
const closeDropdowns = (event) => {
    if (!event.target.closest('.relative')) {
        showUserMenu.value = false;
        showHistory.value = false;
        showSettings.value = false;
    }
};

const generateVideo = async () => {
    if (!prompt.value.trim()) return

    isGenerating.value = true
    generationProgress.value = 0

    try {
        // Simulate video generation progress
        const progressInterval = setInterval(() => {
            if (generationProgress.value < 90) {
                generationProgress.value += Math.random() * 10
            }
        }, 1000)

        // Create form data for upload
        const formData = new FormData()
        formData.append('file', new Blob([prompt.value], { type: 'text/plain' }), 'prompt.txt')
        formData.append('prompt', prompt.value)
        formData.append('style', selectedStyle.value)
        formData.append('duration', selectedDuration.value)

        const response = await axios.post('/api/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })

        clearInterval(progressInterval)
        generationProgress.value = 100

        if (response.data.success) {
            // Simulate successful generation
            setTimeout(() => {
                const newVideo = {
                    id: response.data.data.id,
                    prompt: prompt.value,
                    url: '/placeholder-video.mp4', // This would be the actual video URL
                    thumbnail: '/placeholder-thumbnail.jpg',
                    type: 'video',
                    created_at: new Date().toISOString()
                }

                currentVideo.value = newVideo
                videoHistory.value.unshift(newVideo)

                isGenerating.value = false
                generationProgress.value = 0
                prompt.value = ''
            }, 1000)
        }
    } catch (error) {
        console.error('Video generation failed:', error)
        isGenerating.value = false
        generationProgress.value = 0
    }
}

const selectVideo = (video) => {
    currentVideo.value = video
}

const downloadVideo = (video = null) => {
    const targetVideo = video || currentVideo.value
    if (targetVideo) {
        // Implement download logic
        console.log('Downloading video:', targetVideo.id)
    }
}

const shareVideo = () => {
    if (currentVideo.value) {
        // Implement share logic
        console.log('Sharing video:', currentVideo.value.id)
    }
}

const deleteVideo = async (videoId) => {
    if (confirm('Are you sure you want to delete this video?')) {
        try {
            // Implement delete logic
            videoHistory.value = videoHistory.value.filter(v => v.id !== videoId)
            if (currentVideo.value && currentVideo.value.id === videoId) {
                currentVideo.value = null
            }
        } catch (error) {
            console.error('Failed to delete video:', error)
        }
    }
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString()
}

const loadVideoHistory = async () => {
    try {
        const response = await axios.get('/api/user/uploads')
        // Transform uploads to video format
        videoHistory.value = response.data.data.map(upload => ({
            id: upload.id,
            prompt: upload.original_filename,
            url: upload.file_path,
            thumbnail: upload.file_path,
            type: 'video',
            created_at: upload.created_at
        }))
    } catch (error) {
        console.error('Failed to load video history:', error)
    }
}

onMounted(() => {
    loadVideoHistory()

    // Add event listener for clicking outside
    document.addEventListener('click', closeDropdowns);
})

onUnmounted(() => {
    document.removeEventListener('click', closeDropdowns);
})
</script>
