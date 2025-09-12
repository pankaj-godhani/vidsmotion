<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const showUserMenu = ref(false);
const isMenuOpen = ref(false);

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

// Add event listener for clicking outside
onMounted(() => {
    document.addEventListener('click', closeDropdowns);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdowns);
});
</script>

<template>
    <Head title="Dashboard" />

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

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-gray-300 hover:text-white transition-colors text-sm">Features</a>
                        <Link :href="route('explore')" class="text-gray-300 hover:text-white transition-colors text-sm">Explore</Link>
                        <Link :href="route('pricing')" class="text-gray-300 hover:text-white transition-colors text-sm">Pricing</Link>
                        <a href="#docs" class="text-gray-300 hover:text-white transition-colors text-sm">Docs</a>
                        <div class="flex items-center space-x-4">
                            <!-- User Dropdown -->
                            <div class="relative">
                                <button
                                    @click="showUserMenu = !showUserMenu"
                                    class="flex items-center space-x-2 text-gray-300 hover:text-white transition-colors"
                                >
                                    <img
                                        v-if="$page.props.auth.user.avatar_url"
                                        :src="$page.props.auth.user.avatar_url"
                                        :alt="$page.props.auth.user.name"
                                        class="w-8 h-8 rounded-full object-cover border-2 border-gray-600"
                                    />
                                    <div
                                        v-else
                                        class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center border-2 border-gray-600"
                                    >
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
                        <Link :href="route('explore')" class="text-gray-300 hover:text-white transition-colors text-sm">Explore</Link>
                        <Link :href="route('pricing')" class="text-gray-300 hover:text-white transition-colors text-sm">Pricing</Link>
                        <a href="#docs" class="text-gray-300 hover:text-white transition-colors text-sm">Docs</a>
                        <div class="pt-4 border-t border-gray-800">
                            <!-- Mobile User Menu -->
                            <div class="space-y-2">
                                <div class="px-4 py-2 border-b border-gray-800">
                                    <div class="flex items-center space-x-3">
                                        <img
                                            v-if="$page.props.auth.user.avatar_url"
                                            :src="$page.props.auth.user.avatar_url"
                                            :alt="$page.props.auth.user.name"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-600"
                                        />
                                        <div
                                            v-else
                                            class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center border-2 border-gray-600"
                                        >
                                            <span class="text-sm font-medium text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-white">{{ $page.props.auth.user.name }}</p>
                                            <p class="text-xs text-gray-400">{{ $page.props.auth.user.email }}</p>
                                        </div>
                                    </div>
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
                                <Link
                                    :href="route('my-profile')"
                                    class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                >
                                    My Profile
                                </Link>
                                <button
                                    @click="logout"
                                    class="block w-full text-left text-gray-300 hover:text-white transition-colors text-sm py-2"
                                >
                                    Sign Out
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="pt-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Welcome to Kling
                    </h1>
                    <p class="text-xl text-gray-300 mb-12">Choose your dashboard to get started</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
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
                        href="/user/dashboard"
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

                    <a
                        href="/admin/dashboard"
                        class="block p-8 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-all"
                    >
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-white mb-3">Admin Dashboard</h4>
                            <p class="text-gray-300">Manage users, monitor system activity, and oversee all uploads</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
