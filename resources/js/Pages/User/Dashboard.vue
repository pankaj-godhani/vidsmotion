<template>
    <Head title="My Files" />

    <div class="min-h-screen bg-black text-white">
        <!-- Header Menu Component -->
        <HeaderMenu current-page="user-dashboard" :can-login="true" :can-register="true" />

        <!-- Page Header with Search and Filter -->
        <div class="pt-16 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            My Files
                        </h2>
                        <p class="text-gray-400 mt-1">Manage your AI-generated videos and uploads</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Search Bar -->
                        <div class="relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search files..."
                                class="w-64 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                            />
                            <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-h-screen bg-gray-900">
            <!-- Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-r from-purple-500/10 to-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-purple-600/20 to-blue-600/20 backdrop-blur-sm border border-purple-500/30 rounded-2xl p-6 hover:border-purple-400/50 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-300">Total Files</p>
                                    <p class="text-3xl font-bold text-white">{{ stats.totalUploads }}</p>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-600/20 to-emerald-600/20 backdrop-blur-sm border border-green-500/30 rounded-2xl p-6 hover:border-green-400/50 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-300">Completed</p>
                                    <p class="text-3xl font-bold text-white">{{ stats.completedUploads }}</p>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-600/20 to-orange-600/20 backdrop-blur-sm border border-yellow-500/30 rounded-2xl p-6 hover:border-yellow-400/50 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-300">Processing</p>
                                    <p class="text-3xl font-bold text-white">{{ stats.processingUploads }}</p>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-red-600/20 to-pink-600/20 backdrop-blur-sm border border-red-500/30 rounded-2xl p-6 hover:border-red-400/50 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-300">Failed</p>
                                    <p class="text-3xl font-bold text-white">{{ stats.failedUploads || 0 }}</p>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Files List -->
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-700/50">
                            <h3 class="text-lg font-semibold text-white">Your Files</h3>
                            <p class="text-sm text-gray-400">Manage and track your uploaded files</p>
                        </div>

                        <div v-if="filteredUploads.length === 0" class="p-12 text-center">
                            <div class="w-16 h-16 bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-gray-300 mb-2">No files found</h4>
                            <p class="text-gray-500">Upload your first file to get started with AI video generation</p>
                        </div>

                        <div v-else class="divide-y divide-gray-700/50">
                            <div
                                v-for="upload in filteredUploads"
                                :key="upload.id"
                                class="p-6 hover:bg-gray-700/30 transition-colors duration-200"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- File Icon -->
                                        <div class="w-12 h-12 bg-gradient-to-r from-purple-600/20 to-blue-600/20 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>

                                        <!-- File Info -->
                                        <div>
                                            <h4 class="text-white font-medium">{{ upload.original_filename || 'generated_video.mp4' }}</h4>
                                            <div class="flex items-center space-x-4 mt-1">
                                                <span class="text-sm text-gray-400">{{ formatFileSize(upload.file_size) }}</span>
                                                <span class="text-sm text-gray-400">{{ formatDate(upload.created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status and Actions -->
                                    <div class="flex items-center space-x-4">
                                        <!-- Status Badge -->
                                        <span :class="getStatusClass(upload.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                                            <span class="flex items-center space-x-1">
                                                <span v-if="upload.status === 'processing'" class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                                                <span v-else-if="upload.status === 'completed'" class="w-2 h-2 bg-green-400 rounded-full"></span>
                                                <span v-else-if="upload.status === 'failed'" class="w-2 h-2 bg-red-400 rounded-full"></span>
                                                <span v-else class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                                                <span>{{ (upload.status || 'unknown').charAt(0).toUpperCase() + (upload.status || 'unknown').slice(1) }}</span>
                                            </span>
                                        </span>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-2">
                                            <button
                                                v-if="upload.status === 'completed'"
                                                @click="downloadResult(upload.id)"
                                                class="px-3 py-1.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm rounded-lg transition-all duration-200"
                                            >
                                                Download
                                            </button>
                                            <button
                                                v-if="upload.status === 'failed'"
                                                @click="retryUpload(upload.id)"
                                                class="px-3 py-1.5 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white text-sm rounded-lg transition-all duration-200"
                                            >
                                                Retry
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import HeaderMenu from '@/Components/HeaderMenu.vue'

// HeaderMenu component handles all navigation logic


onMounted(() => {
    // Initialize if needed
});

onUnmounted(() => {
    // Cleanup if needed
});

const stats = ref({
    totalUploads: 0,
    completedUploads: 0,
    processingUploads: 0,
    failedUploads: 0
})

const recentUploads = ref([])
const searchQuery = ref('')

const filteredUploads = computed(() => {
    let filtered = recentUploads.value

    if (searchQuery.value) {
        filtered = filtered.filter(upload =>
            upload.original_filename.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    }

    return filtered
})


const loadStats = async () => {
    try {
        const response = await axios.get('/api/user/stats')
        stats.value = response.data.data
    } catch (error) {
        console.error('Failed to load stats:', error)
    }
}

const loadRecentUploads = async () => {
    try {
        const response = await axios.get('/api/user/uploads')
        recentUploads.value = response.data.data
    } catch (error) {
        console.error('Failed to load uploads:', error)
    }
}

const getStatusClass = (status) => {
    const classes = {
        'pending': 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
        'processing': 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
        'completed': 'bg-green-500/20 text-green-400 border border-green-500/30',
        'failed': 'bg-red-500/20 text-red-400 border border-red-500/30'
    }
    return classes[status] || 'bg-gray-500/20 text-gray-400 border border-gray-500/30'
}

const formatDate = (date) => {
    try {
        if (!date) return '—'
        const d = new Date(date)
        if (isNaN(d.getTime())) return '—'
        return d.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })
    } catch (_) {
        return '—'
    }
}

const formatFileSize = (bytes) => {
    try {
        if (bytes === null || bytes === undefined) return '—'
        const n = Number(bytes)
        if (!isFinite(n) || n < 0) return '—'
        if (n === 0) return '0 Bytes'
        const k = 1024
        const sizes = ['Bytes', 'KB', 'MB', 'GB']
        const i = Math.min(sizes.length - 1, Math.floor(Math.log(n) / Math.log(k)))
        const value = n / Math.pow(k, i)
        return `${value.toFixed(2)} ${sizes[i]}`
    } catch (_) {
        return '—'
    }
}

const downloadResult = (id) => {
    // Trigger secure download from backend
    window.location.href = `/api/download/${id}`
}

const retryUpload = async (id) => {
    try {
        const response = await axios.post(`/api/admin/uploads/${id}/retry`)
        if (response.data.success) {
            loadRecentUploads()
            loadStats()
        }
    } catch (error) {
        console.error('Failed to retry upload:', error)
    }
}

onMounted(() => {
    loadStats()
    loadRecentUploads()
})
</script>
