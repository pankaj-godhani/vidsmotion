<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-900 text-white relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900/20 via-transparent to-blue-900/20"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-purple-600/10 via-transparent to-transparent"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-blue-600/10 via-transparent to-transparent"></div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-purple-500/30 rounded-full animate-pulse"></div>
            <div class="absolute top-3/4 right-1/4 w-1 h-1 bg-blue-500/40 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 right-1/3 w-1.5 h-1.5 bg-purple-400/20 rounded-full animate-pulse delay-2000"></div>
        </div>

        <!-- Header Menu Component -->
        <HeaderMenu current-page="video-generator" :can-login="true" :can-register="true" />

        <!-- Main Content -->
        <div class="pt-16 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Panel - Generation Controls -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-900/80 backdrop-blur-xl rounded-3xl border border-gray-700/50 p-8 sticky top-24 shadow-2xl shadow-purple-500/10">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-white via-purple-200 to-blue-200 bg-clip-text text-transparent">
                                    {{ isRecreateMode ? 'Recreate Video' : 'Create Video' }}
                                </h3>
                                <button
                                    v-if="isRecreateMode"
                                    @click="exitRecreateMode"
                                    class="text-gray-400 hover:text-white transition-colors"
                                    title="Exit recreate mode"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Recreate Info Banner -->
                            <div v-if="isRecreateMode" class="mb-8 p-6 bg-gradient-to-r from-purple-900/60 to-blue-900/60 border border-purple-500/40 rounded-2xl backdrop-blur-sm">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold text-sm">Recreating: {{ recreateData?.video_title }}</p>
                                        <p class="text-gray-300 text-xs mt-1">Modify the prompt below to create a variation</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Prompt Input -->
                            <div class="mb-8">
                                <label class="block text-sm font-semibold text-gray-200 mb-3">
                                    Describe your video
                                </label>
                                <div class="relative">
                                    <textarea
                                        v-model="prompt"
                                        placeholder="A cinematic shot of a futuristic city at sunset, with flying cars and neon lights..."
                                        class="w-full h-36 px-5 py-4 bg-gray-800/60 border border-gray-600/50 rounded-2xl text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 resize-none backdrop-blur-sm transition-all duration-200"
                                        :disabled="isGenerating"
                                    ></textarea>
                                    <div class="absolute bottom-3 right-3 flex items-center space-x-3">
                                        <span class="text-xs text-gray-400 bg-gray-800/80 px-2 py-1 rounded-lg">{{ prompt.length }}/500</span>
                                        <button
                                            v-if="prompt.length > 0"
                                            @click="clearPrompt"
                                            class="text-xs text-gray-400 hover:text-white bg-gray-800/80 px-2 py-1 rounded-lg transition-colors"
                                        >
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload Section -->
                            <div class="mb-8">
                                <label class="block text-sm font-semibold text-gray-200 mb-3">
                                    Reference Image (Optional)
                                </label>

                                <!-- Upload Area -->
                                <div
                                    @click="triggerFileInput"
                                    @dragover.prevent
                                    @drop.prevent="handleFileDrop"
                                    :class="[
                                        'relative border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all duration-300 backdrop-blur-sm',
                                        isDragOver
                                            ? 'border-purple-500 bg-purple-500/20 shadow-lg shadow-purple-500/25'
                                            : 'border-gray-600/50 hover:border-purple-500/50 hover:bg-gray-800/30'
                                    ]"
                                >
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        accept="image/*"

                                        class="hidden"
                                        :disabled="isGenerating"
                                    />

                                    <!-- Upload Icon and Text -->
                                    <div v-if="!uploadedImage" class="space-y-4">
                                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500/20 to-blue-500/20 rounded-2xl flex items-center justify-center mx-auto">
                                            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-gray-300 text-sm">
                                                <span class="text-purple-400 font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-gray-500 text-xs mt-2">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                    </div>

                                    <!-- Uploaded Image Preview -->
                                    <div v-else class="space-y-3">
                                        <div class="relative inline-block">
                                            <img
                                                :src="uploadedImagePreview"
                                                :alt="uploadedImageName"
                                                class="w-32 h-32 object-cover rounded-lg mx-auto"
                                            />
                                            <button
                                                @click.stop="removeImage"
                                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-xs transition-colors"
                                            >
                                                ×
                                            </button>
                                        </div>
                                        <div>
                                            <p class="text-white text-sm font-medium">{{ uploadedImageName }}</p>
                                            <p class="text-gray-400 text-xs">{{ formatFileSize(uploadedImageSize) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Progress -->
                                <div v-if="isUploading" class="mt-3">
                                    <div class="flex justify-between text-sm text-gray-300 mb-2">
                                        <span>Uploading...</span>
                                        <span>{{ uploadProgress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        <div
                                            class="bg-purple-500 h-2 rounded-full transition-all duration-300"
                                            :style="{ width: uploadProgress + '%' }"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Generate Button -->
                            <button
                                @click="generateVideo"
                                :disabled="!prompt.trim() || isGenerating || isUploading"
                                :class="[
                                    'w-full py-5 rounded-2xl font-bold text-lg transition-all duration-300 relative overflow-hidden group',
                                    (isGenerating || isUploading)
                                        ? 'bg-gray-700/50 text-gray-400 cursor-not-allowed'
                                        : 'bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white shadow-lg shadow-purple-500/25 hover:shadow-purple-500/40 hover:scale-[1.02]'
                                ]"
                            >
                                <div v-if="isUploading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Uploading Image...
                                </div>
                                <div v-else-if="isGenerating" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Generating Video...
                                </div>
                                <div v-else class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-6a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Generate Video
                                </div>
                            </button>

                            <!-- Generation Progress -->
                            <div v-if="isGenerating" class="mt-6">
                                <div class="flex justify-between text-sm text-gray-300 mb-3">
                                    <span class="font-medium">Generating your video...</span>
                                    <span class="font-semibold text-purple-400">{{ generationProgress }}%</span>
                                </div>
                                <div class="w-full bg-gray-700/50 rounded-full h-3 overflow-hidden">
                                    <div
                                        class="bg-gradient-to-r from-purple-500 to-blue-500 h-3 rounded-full transition-all duration-500 ease-out shadow-lg"
                                        :style="{ width: generationProgress + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Video Preview & Gallery -->
                    <div class="lg:col-span-2">
                        <!-- Current Video Preview -->
                        <div class="bg-gray-900/80 backdrop-blur-xl rounded-3xl border border-gray-700/50 p-8 mb-8 shadow-2xl shadow-blue-500/10">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold bg-gradient-to-r from-white via-blue-200 to-purple-200 bg-clip-text text-transparent">
                                    {{ isRecreateMode ? 'Original Video' : 'Preview' }}
                                </h3>
                                <div v-if="isRecreateMode" class="flex items-center space-x-2 text-sm text-purple-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Recreating this video</span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div v-if="isGenerating && videoProgress > 0" class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-300">Generating Video</span>
                                    <span class="text-sm text-gray-400">{{ videoProgress }}%</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2">
                                    <div
                                        class="bg-gradient-to-r from-purple-600 to-blue-600 h-2 rounded-full transition-all duration-500 ease-out"
                                        :style="{ width: videoProgress + '%' }"
                                    ></div>
                                </div>
                            </div>

                            <div class="aspect-video bg-gray-800 rounded-xl overflow-hidden relative">
                                <!-- Show original video in recreate mode -->
                                <div v-if="isRecreateMode && recreateData" class="w-full h-full">
                                    <video
                                        :src="recreateData.video_url || recreateData.thumbnail"
                                        controls
                                        class="w-full h-full object-cover"
                                    ></video>
                                    <!-- Recreate overlay -->
                                    <div class="absolute top-3 right-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                        Original
                                    </div>
                                </div>
                                <!-- Show generated video -->
                                <div v-else-if="currentVideo" class="w-full h-full relative">
                                    <video
                                        v-if="currentVideo.type === 'video'"
                                        :src="currentVideo.url"
                                        controls
                                        class="w-full h-full object-cover"
                                        @loadstart="isVideoLoading = true; console.log('Video load started:', currentVideo.url)"
                                        @loadeddata="isVideoLoading = false; console.log('Video data loaded:', currentVideo.url)"
                                        @error="isVideoLoading = false; console.error('Video load error:', $event)"
                                        @canplay="isVideoLoading = false; console.log('Video can play:', currentVideo.url)"
                                    >
                                        <source :src="currentVideo.url" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <img
                                        v-else
                                        :src="currentVideo.url"
                                        :alt="currentVideo.prompt"
                                        class="w-full h-full object-cover"
                                    />
                                    <!-- Debug info -->
                                    <div class="absolute bottom-2 left-2 bg-black bg-opacity-75 text-white text-xs p-2 rounded max-w-xs">
                                        <div>Proxy URL: {{ currentVideo.url }}</div>
                                        <div v-if="currentVideo.originalUrl">Original URL: {{ currentVideo.originalUrl }}</div>
                                        <div>Type: {{ currentVideo.type }}</div>
                                        <a :href="currentVideo.url" target="_blank" class="text-blue-400 hover:text-blue-300 underline">
                                            Open video in new tab
                                        </a>
                                    </div>
                                    <!-- Loading overlay -->
                                    <div v-if="isVideoLoading" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                        <div class="text-white text-center">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white mx-auto mb-2"></div>
                                            <div>Loading video...</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Empty state -->
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-gray-400 text-lg">
                                            {{ isRecreateMode ? 'Your recreation will appear here' : 'Your video will appear here' }}
                                        </p>
                                        <p class="text-gray-500 text-sm mt-2">
                                            {{ isRecreateMode ? 'Modify the prompt and click generate to create a variation' : 'Enter a prompt and click generate to create your video' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Video Actions -->
                            <div v-if="currentVideo || (isRecreateMode && recreateData)" class="flex justify-between items-center mt-4">
                                <div>
                                    <h4 class="font-semibold text-white">
                                        {{ isRecreateMode && recreateData ? recreateData.video_title : currentVideo.prompt }}
                                    </h4>
                                    <p class="text-sm text-gray-400">
                                        {{ isRecreateMode && recreateData ? 'Original video from Explore' : new Date(currentVideo.created_at).toLocaleDateString() }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        v-if="!isRecreateMode"
                                        @click="downloadVideo"
                                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download
                                    </button>
                                    <button
                                        v-if="!isRecreateMode"
                                        @click="shareVideo"
                                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                        Share
                                    </button>
                                    <button
                                        v-if="isRecreateMode"
                                        @click="exitRecreateMode"
                                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Exit Recreate
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
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import Footer from '@/Components/Footer.vue'
import HeaderMenu from '@/Components/HeaderMenu.vue'

// Get page props
const page = usePage()

// Reactive data
const prompt = ref('')
const isGenerating = ref(false)
const generationProgress = ref(0)
const currentVideo = ref(null)
const isRecreateMode = ref(false)
const recreateData = ref(null)
const isVideoLoading = ref(false)
const videoProgress = ref(0)

// Image upload data
const uploadedImage = ref(null)
const uploadedImagePreview = ref('')
const uploadedImageName = ref('')
const uploadedImageSize = ref(0)
const isUploading = ref(false)
const uploadProgress = ref(0)
const isDragOver = ref(false)
const fileInput = ref(null)
const uploadedImageUrl = ref('')
const isImageUploaded = ref(false)




// Methods
const clearPrompt = () => {
    prompt.value = ''
}

const exitRecreateMode = () => {
    isRecreateMode.value = false
    recreateData.value = null
    prompt.value = ''
    removeImage() // Clear uploaded image when exiting recreate mode
}

const initializeRecreateMode = () => {
    // Check if we have recreate data from page props
    if (page.props.isRecreateMode && page.props.recreateData) {
        isRecreateMode.value = true
        recreateData.value = page.props.recreateData

        // Pre-fill the prompt with the original video title
        prompt.value = page.props.recreateData.video_title || ''
    } else {
        // Fallback to session storage for direct navigation
        const recreateIntent = sessionStorage.getItem('recreate_intent')
        if (recreateIntent) {
            try {
                const data = JSON.parse(recreateIntent)
                isRecreateMode.value = true
                recreateData.value = data

                // Pre-fill the prompt with the original video title
                prompt.value = data.video_title || ''

                // Clear the session storage after use
                sessionStorage.removeItem('recreate_intent')
            } catch (error) {
                console.error('Failed to parse recreate intent:', error)
            }
        }
    }
}

const logout = () => {
    router.post(route('logout'));
}

// Image upload methods
const triggerFileInput = () => {
    if (!isGenerating.value) {
        fileInput.value?.click()
    }
}

const handleFileSelect = (event) => {
    const file = event.target.files[0]
    if (file) {
        processFile(file)
    }
}

const handleFileDrop = (event) => {
    isDragOver.value = false
    const file = event.dataTransfer.files[0]
    if (file && file.type.startsWith('image/')) {
        processFile(file)
    }
}

const processFile = async (file) => {
    // Validate file type
    if (!file.type.startsWith('image/')) {
        if (window.$notify) {
            window.$notify({
                type: 'error',
                title: 'Invalid File Type',
                message: 'Please select an image file (PNG, JPG, GIF)',
                duration: 3000
            })
        }
        return
    }

    // Validate file size (10MB limit)
    const maxSize = 10 * 1024 * 1024 // 10MB
    if (file.size > maxSize) {
        if (window.$notify) {
            window.$notify({
                type: 'error',
                title: 'File Too Large',
                message: 'Please select an image smaller than 10MB',
                duration: 3000
            })
        }
        return
    }

    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
        uploadedImage.value = file
        uploadedImagePreview.value = e.target.result
        uploadedImageName.value = file.name
        uploadedImageSize.value = file.size
    }
    reader.readAsDataURL(file)

    // Upload image to Onrender API
    await uploadImageToAPI(file)
}

const uploadImageToAPI = async (file) => {
    isUploading.value = true
    uploadProgress.value = 0

    try {
        const formData = new FormData()
        formData.append('reference_image', file)

        const response = await axios.post('/api/upload-image', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onUploadProgress: (progressEvent) => {
                uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total)
            }
        })

        if (response.data.success) {
            uploadedImageUrl.value = response.data.data.image_url
            isImageUploaded.value = true

            if (window.$notify) {
                window.$notify({
                    type: 'success',
                    title: 'Image Uploaded!',
                    message: 'Your reference image has been uploaded successfully. You can now generate a video.',
                    duration: 3000
                })
            }
        } else {
            throw new Error(response.data.message || 'Upload failed')
        }
    } catch (error) {
        console.error('Image upload failed:', error)

        if (window.$notify) {
            window.$notify({
                type: 'error',
                title: 'Upload Failed',
                message: error.response?.data?.message || 'Failed to upload image. Please try again.',
                duration: 5000
            })
        }

        // Reset image state on failure
        removeImage()
    } finally {
        isUploading.value = false
        uploadProgress.value = 0
    }
}

const removeImage = () => {
    uploadedImage.value = null
    uploadedImagePreview.value = ''
    uploadedImageName.value = ''
    uploadedImageSize.value = 0
    uploadedImageUrl.value = ''
    isImageUploaded.value = false
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const generateVideo = async () => {
    if (!prompt.value.trim()) return

    isGenerating.value = true
    generationProgress.value = 0
    videoProgress.value = 0

    try {
        // Simulate video generation progress
        const progressInterval = setInterval(() => {
            if (generationProgress.value < 90) {
                generationProgress.value += Math.random() * 10
            }
        }, 1000)

        // Create request data
        const requestData = {
            prompt: prompt.value,
            duration: 6
        }

        // Add image URL if available
        if (isImageUploaded.value && uploadedImageUrl.value) {
            requestData.image_url = uploadedImageUrl.value
        }

        // Add recreate metadata if in recreate mode
        if (isRecreateMode.value && recreateData.value) {
            requestData.is_recreate = true
            requestData.original_video_id = recreateData.value.video_id || ''
            requestData.original_video_title = recreateData.value.video_title || ''
        }

        const response = await axios.post('/api/upload', requestData, {
            headers: {
                'Content-Type': 'application/json',
            }
        })

        clearInterval(progressInterval)
        generationProgress.value = 100

        if (response.data.success) {
            // Start polling for video completion using task_id
            await pollVideoStatus(response.data.data.task_id)
        }
    } catch (error) {
        console.error('Video generation failed:', error)
        isGenerating.value = false
        generationProgress.value = 0
        videoProgress.value = 0

        // Show error notification
        if (window.$notify) {
            window.$notify({
                type: 'error',
                title: 'Generation Failed',
                message: error.response?.data?.message || 'Failed to generate video. Please try again.',
                duration: 5000
            })
        }
    }
}

const pollVideoStatus = async (taskId) => {
    const maxAttempts = 60 // Poll for up to 5 minutes (5 seconds * 60)
    let attempts = 0

    const poll = async () => {
        try {
            const response = await axios.get(`/api/status/${taskId}`)

            if (response.data.success) {
                const upload = response.data.data

                // Update progress percentage
                if (upload.progress_percent !== undefined) {
                    videoProgress.value = upload.progress_percent
                    console.log('Video progress:', upload.progress_percent + '%')
                }

                if (upload.status === 'completed') {
                    // Video is ready, use video_url from status response
                    console.log('Video completed, upload data:', upload)
                    console.log('Video URL:', upload.video_url)

                    // Test if video URL is accessible
                    if (upload.video_url) {
                        fetch(upload.video_url, { method: 'HEAD' })
                            .then(response => {
                                console.log('Video URL accessibility test:', response.status, response.headers)
                            })
                            .catch(error => {
                                console.error('Video URL accessibility error:', error)
                            })
                    }

                    // Create proxy URL for video to bypass CORS issues
                    const videoUrl = upload.video_url || '/placeholder-video.mp4'
                    const proxyUrl = videoUrl.startsWith('http')
                        ? `/api/video-proxy?url=${encodeURIComponent(videoUrl)}`
                        : videoUrl

                    const newVideo = {
                        id: upload.id,
                        prompt: prompt.value,
                        url: proxyUrl,
                        originalUrl: upload.video_url, // Keep original URL for download
                        thumbnail: upload.image_url || '/placeholder-thumbnail.jpg',
                        type: 'video',
                        created_at: upload.created_at,
                        isRecreate: isRecreateMode.value,
                        originalVideoId: isRecreateMode.value ? recreateData.value?.video_id : null
                    }

                    console.log('New video object:', newVideo)

                    currentVideo.value = newVideo

                    isGenerating.value = false
                    generationProgress.value = 0
                    videoProgress.value = 100

                    // Show success notification
                    if (window.$notify) {
                        window.$notify({
                            type: 'success',
                            title: isRecreateMode.value ? 'Video Recreated!' : 'Video Generated!',
                            message: isRecreateMode.value
                                ? 'Your video variation has been created successfully!'
                                : 'Your video has been generated successfully!',
                            duration: 5000
                        })
                    }

                    // Exit recreate mode after successful generation
                    if (isRecreateMode.value) {
                        exitRecreateMode()
                    } else {
                        prompt.value = ''
                    }
                } else if (upload.status === 'failed') {
                    isGenerating.value = false
                    generationProgress.value = 0
                    videoProgress.value = 0

                    if (window.$notify) {
                        window.$notify({
                            type: 'error',
                            title: 'Generation Failed',
                            message: 'Video generation failed. Please try again.',
                            duration: 5000
                        })
                    }
                } else if (attempts < maxAttempts) {
                    // Still processing, poll again in 5 seconds
                    attempts++
                    setTimeout(poll, 5000)
                } else {
                    // Timeout
                    isGenerating.value = false
                    generationProgress.value = 0
                    videoProgress.value = 0

                    if (window.$notify) {
                        window.$notify({
                            type: 'error',
                            title: 'Generation Timeout',
                            message: 'Video generation is taking longer than expected. Please check back later.',
                            duration: 5000
                        })
                    }
                }
            }
        } catch (error) {
            console.error('Error polling video status:', error)
            isGenerating.value = false
            generationProgress.value = 0
        }
    }

    // Start polling
    poll()
}


const downloadVideo = (video = null) => {
    const targetVideo = video || currentVideo.value
    if (targetVideo) {
        // Use original URL for download to get the actual video file
        const downloadUrl = targetVideo.originalUrl || targetVideo.url

        if (downloadUrl && downloadUrl.startsWith('http')) {
            // Create a temporary link to download the video
            const link = document.createElement('a')
            link.href = downloadUrl
            link.download = `video-${targetVideo.id}.mp4`
            link.target = '_blank'
            document.body.appendChild(link)
            link.click()
            document.body.removeChild(link)

            console.log('Downloading video:', targetVideo.id, 'from:', downloadUrl)
        } else {
            console.error('No valid download URL available')
        }
    }
}

const shareVideo = () => {
    if (currentVideo.value) {
        // Implement share logic
        console.log('Sharing video:', currentVideo.value.id)
    }
}




onMounted(() => {
    initializeRecreateMode()

    // Add drag and drop event listeners
    const handleDragOver = (e) => {
        e.preventDefault()
        isDragOver.value = true
    }

    const handleDragLeave = (e) => {
        e.preventDefault()
        isDragOver.value = false
    }

    document.addEventListener('dragover', handleDragOver)
    document.addEventListener('dragleave', handleDragLeave)

    // Cleanup on unmount
    onUnmounted(() => {
        document.removeEventListener('dragover', handleDragOver)
        document.removeEventListener('dragleave', handleDragLeave)
    })
})

onUnmounted(() => {
    // Cleanup if needed
})
</script>
