<template>
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
                    <Link
                        :href="route('welcome')"
                        :class="[
                            'transition-colors text-sm',
                            currentPage === 'welcome' ? 'text-white font-medium' : 'text-gray-300 hover:text-white'
                        ]"
                    >
                        Home
                    </Link>
                    <Link
                        :href="route('features')"
                        :class="[
                            'transition-colors text-sm',
                            currentPage === 'features' ? 'text-white font-medium' : 'text-gray-300 hover:text-white'
                        ]"
                    >
                        Features
                    </Link>
                    <Link
                        :href="route('explore')"
                        :class="[
                            'transition-colors text-sm',
                            currentPage === 'explore' ? 'text-white font-medium' : 'text-gray-300 hover:text-white'
                        ]"
                    >
                        Explore
                    </Link>
                    <Link
                        :href="route('pricing')"
                        :class="[
                            'transition-colors text-sm',
                            currentPage === 'pricing' ? 'text-white font-medium' : 'text-gray-300 hover:text-white'
                        ]"
                    >
                        Pricing
                    </Link>
                    <div v-if="canLogin" class="flex items-center space-x-4">
                        <!-- Create Video Button for logged-in users with active subscription -->
                        <Link
                            v-if="$page.props.auth.user && $page.props.auth.activeSubscription"
                            :href="route('video-generator')"
                            class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all text-sm font-medium"
                        >
                            Create Video
                        </Link>
                        <template v-if="$page.props.auth.user">
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
                    <Link
                        :href="route('welcome')"
                        :class="[
                            'transition-colors text-sm',
                            currentPage === 'welcome' ? 'text-white font-medium' : 'text-gray-300 hover:text-white'
                        ]"
                        @click="isMenuOpen = false"
                    >
                        Home
                    </Link>
                    <Link
                        :href="route('features')"
                        :class="[
                            'transition-colors text-sm',
                            currentPage === 'features' ? 'text-white font-medium' : 'text-gray-300 hover:text-white'
                        ]"
                        @click="isMenuOpen = false"
                    >
                        Features
                    </Link>
                    <Link
                        :href="route('explore')"
                        :class="[
                            'transition-colors text-sm',
                            currentPage === 'explore' ? 'text-white font-medium' : 'text-gray-300 hover:text-white'
                        ]"
                        @click="isMenuOpen = false"
                    >
                        Explore
                    </Link>
                    <Link
                        :href="route('pricing')"
                        :class="[
                            'transition-colors text-sm',
                            currentPage === 'pricing' ? 'text-white font-medium' : 'text-gray-300 hover:text-white'
                        ]"
                        @click="isMenuOpen = false"
                    >
                        Pricing
                    </Link>
                    <!-- Create Video Button for logged-in users with active subscription in mobile menu -->
                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.activeSubscription"
                        :href="route('video-generator')"
                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all text-sm font-medium text-center"
                        @click="isMenuOpen = false"
                    >
                        Create Video
                    </Link>
                    <div v-if="canLogin" class="pt-4 border-t border-gray-800">
                        <template v-if="$page.props.auth.user">
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
                                    @click="isMenuOpen = false"
                                >
                                    Dashboard
                                </Link>
                                <Link
                                    :href="route('video-generator')"
                                    class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                    @click="isMenuOpen = false"
                                >
                                    Video Generator
                                </Link>
                                <Link
                                    :href="route('user.dashboard')"
                                    class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                    @click="isMenuOpen = false"
                                >
                                    My Files
                                </Link>
                                <Link
                                    :href="route('my-profile')"
                                    class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                    @click="isMenuOpen = false"
                                >
                                    My Profile
                                </Link>
                                <div class="border-t border-gray-800"></div>
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
    </header>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    currentPage: {
        type: String,
        default: 'welcome'
    },
    canLogin: {
        type: Boolean,
        default: true
    },
    canRegister: {
        type: Boolean,
        default: true
    }
});

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
