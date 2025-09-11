<template>
    <Head title="Pricing - Vidsmotion" />
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
                        <Link :href="route('welcome')" class="text-gray-300 hover:text-white transition-colors text-sm">Home</Link>
                        <Link :href="route('features')" class="text-gray-300 hover:text-white transition-colors text-sm">Features</Link>
                        <Link :href="route('explore')" class="text-gray-300 hover:text-white transition-colors text-sm">Explore</Link>
                        <Link :href="route('pricing')" class="text-white font-medium text-sm">Pricing</Link>
                        <div v-if="canLogin" class="flex items-center space-x-4">
                            <template v-if="$page.props.auth.user">
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
                        <Link :href="route('welcome')" class="text-gray-300 hover:text-white transition-colors text-sm">Home</Link>
                        <Link :href="route('features')" class="text-gray-300 hover:text-white transition-colors text-sm">Features</Link>
                        <Link :href="route('explore')" class="text-gray-300 hover:text-white transition-colors text-sm">Explore</Link>
                        <Link :href="route('pricing')" class="text-white font-medium text-sm">Pricing</Link>
                        <div v-if="canLogin" class="pt-4 border-t border-gray-800">
                            <template v-if="$page.props.auth.user">
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

        <!-- Main Content -->
        <div class="pt-16">
            <!-- Header Section -->
            <div class="py-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                        Choose Your Plan
                    </h1>
                    <p class="text-xl text-gray-300 mb-12 max-w-2xl mx-auto">
                        Unlock the full potential of AI video generation with our flexible pricing plans
                    </p>
                </div>
            </div>

            <!-- Pricing Cards -->
            <div class="pb-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Free Plan -->
                        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8 relative transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/20 hover:border-purple-500/50 group cursor-pointer">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-purple-400 transition-colors duration-300">3 days</h3>
                                <p class="text-gray-400 mb-6">3 days trial</p>
                                <div class="mb-8 group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-4xl font-bold text-white group-hover:text-purple-400 transition-colors duration-300">₹50</span>
                                </div>

                                <ul class="space-y-4 mb-8 text-left">
                                    <li class="flex items-center group-hover:translate-x-2 transition-transform duration-300">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 group-hover:text-purple-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300 group-hover:text-white transition-colors duration-300">5 video generations per month</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Up to 5 seconds per video</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Standard quality</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Community support</span>
                                    </li>
                                </ul>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Subscribe Standard
                                </Link>
                                <Link
                                    v-else-if="$page.props.auth.user"
                                    :href="route('video-generator')"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Start Creating
                                </Link>
                            </div>
                        </div>

                        <!-- Pro Plan -->
                        <div class="bg-gray-900 rounded-2xl border-2 border-white p-8 relative transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-white/30 hover:border-white group cursor-pointer">
                            <!-- Popular Badge -->
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-white text-black px-4 py-1 rounded-full text-sm font-medium">
                                    Most Popular
                                </span>
                            </div>

                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors duration-300">Monthly</h3>
                                <p class="text-gray-400 mb-6">Monthly billing</p>
                                <div class="mb-8 group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-4xl font-bold text-white group-hover:text-blue-400 transition-colors duration-300">₹100</span>
                                </div>

                                <ul class="space-y-4 mb-8 text-left">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">100 video generations per month</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Up to 10 seconds per video</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">High quality output</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Priority processing</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Email support</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Commercial license</span>
                                    </li>
                                </ul>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Subscribe Pro Monthly
                                </Link>
                                <Link
                                    v-else-if="$page.props.auth.user"
                                    :href="route('video-generator')"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Subscribe Pro Monthly
                                </Link>
                            </div>
                        </div>

                        <!-- Enterprise Plan -->
                        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8 relative transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-cyan-500/20 hover:border-cyan-500/50 group cursor-pointer">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-cyan-400 transition-colors duration-300">Yearly</h3>
                                <p class="text-gray-400 mb-6">Yearly billing</p>
                                <div class="mb-8 group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-4xl font-bold text-white group-hover:text-cyan-400 transition-colors duration-300">₹1100</span>
                                </div>

                                <ul class="space-y-4 mb-8 text-left">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Unlimited video generations</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Up to 15 seconds per video</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Ultra high quality</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Instant processing</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">24/7 priority support</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Team collaboration</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Custom integrations</span>
                                    </li>
                                </ul>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Subscribe Premier yearly
                                </Link>
                                <Link
                                    v-else-if="$page.props.auth.user"
                                    :href="route('video-generator')"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Subscribe Premier yearly
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-900/50">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-white mb-6">Frequently Asked Questions</h2>
                        <p class="text-xl text-gray-300">Everything you need to know about our pricing</p>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-gray-800 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-3">Can I change my plan anytime?</h3>
                            <p class="text-gray-300">Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.</p>
                        </div>

                        <div class="bg-gray-800 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-3">What happens if I exceed my monthly limit?</h3>
                            <p class="text-gray-300">If you exceed your monthly video generation limit, you can purchase additional credits or upgrade to a higher plan.</p>
                        </div>

                        <div class="bg-gray-800 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-3">Do you offer refunds?</h3>
                            <p class="text-gray-300">We offer a 30-day money-back guarantee for all paid plans. Contact our support team if you're not satisfied.</p>
                        </div>

                        <div class="bg-gray-800 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-3">Is there a free trial for paid plans?</h3>
                            <p class="text-gray-300">Yes, we offer a 7-day free trial for our Pro plan. No credit card required to start.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>

<style scoped>
/* Pricing card hover effects */
.group:hover {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.05) 0%, rgba(59, 130, 246, 0.05) 100%);
}

/* Smooth animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
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

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const isMenuOpen = ref(false);
const showUserMenu = ref(false);

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
