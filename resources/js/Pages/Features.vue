<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Footer from '@/Components/Footer.vue';
import HeaderMenu from '@/Components/HeaderMenu.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        default: '11.0'
    },
    phpVersion: {
        type: String,
        default: '8.2'
    }
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
        isMenuOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeDropdowns);
});

const features = ref([
    {
        id: 1,
        title: "AI-Powered Video Generation",
        description: "Create stunning videos from simple text descriptions using advanced artificial intelligence technology.",
        icon: "M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z",
        gradient: "from-purple-500 to-blue-500",
        benefits: [
            "Natural language processing",
            "Context-aware generation",
            "Multiple video styles",
            "High-quality output"
        ]
    },
    {
        id: 2,
        title: "Lightning Fast Processing",
        description: "Generate professional-quality videos in seconds, not hours. Our optimized AI delivers results faster than ever before.",
        icon: "M13 10V3L4 14h7v7l9-11h-7z",
        gradient: "from-blue-500 to-cyan-500",
        benefits: [
            "Sub-minute generation",
            "Real-time preview",
            "Batch processing",
            "Optimized algorithms"
        ]
    },
    {
        id: 3,
        title: "Multiple Video Styles",
        description: "Choose from various artistic styles including cinematic, animated, realistic, and abstract to match your vision.",
        icon: "M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V1a1 1 0 011-1h2a1 1 0 011 1v3m0 0h8m-8 0v16a1 1 0 001 1h6a1 1 0 001-1V4M7 4h8",
        gradient: "from-cyan-500 to-teal-500",
        benefits: [
            "Cinematic quality",
            "Animated styles",
            "Realistic rendering",
            "Abstract art modes"
        ]
    },
    {
        id: 4,
        title: "Smart Content Understanding",
        description: "Our AI understands context, emotions, and narrative flow to create videos that tell compelling stories.",
        icon: "M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z",
        gradient: "from-teal-500 to-green-500",
        benefits: [
            "Context awareness",
            "Emotion recognition",
            "Storytelling logic",
            "Character consistency"
        ]
    },
    {
        id: 5,
        title: "Easy-to-Use Interface",
        description: "No technical skills required. Simply describe your idea and let our AI do the rest with our intuitive interface.",
        icon: "M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4",
        gradient: "from-green-500 to-emerald-500",
        benefits: [
            "Simple text input",
            "One-click generation",
            "Drag-and-drop interface",
            "No learning curve"
        ]
    },
    {
        id: 6,
        title: "High-Quality Output",
        description: "Generate videos in up to 4K resolution with professional-grade quality suitable for any platform or use case.",
        icon: "M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z",
        gradient: "from-emerald-500 to-blue-500",
        benefits: [
            "4K resolution support",
            "Professional quality",
            "Multiple formats",
            "Platform optimization"
        ]
    }
]);

const useCases = ref([
    {
        title: "Marketing & Advertising",
        description: "Create compelling promotional videos for your products and services",
        icon: "M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z",
        examples: ["Product demos", "Social media ads", "Brand storytelling", "Event promotion"]
    },
    {
        title: "Education & Training",
        description: "Develop engaging educational content and training materials",
        icon: "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253",
        examples: ["Course content", "Tutorial videos", "Explainer videos", "Training modules"]
    },
    {
        title: "Entertainment & Media",
        description: "Produce creative content for entertainment and media platforms",
        icon: "M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-6a9 9 0 11-18 0 9 9 0 0118 0z",
        examples: ["Short films", "Music videos", "Social content", "Creative projects"]
    },
    {
        title: "Business Presentations",
        description: "Create professional presentations and corporate communications",
        icon: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z",
        examples: ["Pitch decks", "Company updates", "Training materials", "Client presentations"]
    }
]);

const stats = ref([
    { number: "10M+", label: "Videos Generated" },
    { number: "50K+", label: "Happy Users" },
    { number: "99.9%", label: "Uptime" },
    { number: "< 30s", label: "Average Generation Time" }
]);
</script>

<template>
    <Head title="Features - Vidsmotion" />
    <div class="min-h-screen bg-black text-white">
        <!-- Header Menu Component -->
        <HeaderMenu current-page="features" :can-login="canLogin" :can-register="canRegister" />

        <!-- Hero Section -->
        <div class="pt-24 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 leading-tight">
                        Powerful
                        <span class="bg-gradient-to-r from-purple-400 via-blue-400 to-cyan-400 bg-clip-text text-transparent">
                            Features
                        </span>
                    </h1>
                    <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                        Discover the advanced capabilities that make Vidsmotion the leading AI video generation platform.
                        From lightning-fast processing to professional-quality output, we've got everything you need.
                    </p>
                </div>

                <!-- Stats Section -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-20">
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="text-center"
                    >
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2">
                            {{ stat.number }}
                        </div>
                        <div class="text-gray-400 text-sm">
                            {{ stat.label }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Core Features
                    </h2>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                        Everything you need to create professional-quality videos with AI
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        v-for="feature in features"
                        :key="feature.id"
                        class="group bg-gray-900 rounded-2xl p-8 border border-gray-800 hover:border-gray-700 transition-all duration-300 hover:transform hover:scale-105"
                    >
                        <!-- Icon -->
                        <div :class="`w-16 h-16 bg-gradient-to-r ${feature.gradient} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300`">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon"></path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-purple-300 transition-colors">
                            {{ feature.title }}
                        </h3>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            {{ feature.description }}
                        </p>

                        <!-- Benefits -->
                        <ul class="space-y-2">
                            <li
                                v-for="benefit in feature.benefits"
                                :key="benefit"
                                class="flex items-center text-sm text-gray-400"
                            >
                                <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ benefit }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Use Cases Section -->
        <div class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-900/50">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Perfect For
                    </h2>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                        Whether you're a marketer, educator, or content creator, Vidsmotion has you covered
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div
                        v-for="useCase in useCases"
                        :key="useCase.title"
                        class="bg-gray-800 rounded-2xl p-8 border border-gray-700 hover:border-gray-600 transition-all"
                    >
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="useCase.icon"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white mb-3">
                                    {{ useCase.title }}
                                </h3>
                                <p class="text-gray-300 mb-4 leading-relaxed">
                                    {{ useCase.description }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="example in useCase.examples"
                                        :key="example"
                                        class="px-3 py-1 bg-gray-700 text-gray-300 text-sm rounded-full"
                                    >
                                        {{ example }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Ready to Experience These Features?
                </h2>
                <p class="text-xl text-gray-300 mb-12">
                    Join thousands of creators who are already using Vidsmotion to bring their ideas to life.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-10 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-semibold text-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105"
                    >
                        Start Creating Free
                    </Link>
                    <Link
                        :href="route('pricing')"
                        class="px-10 py-4 border-2 border-white/30 text-white rounded-xl font-semibold text-lg hover:border-white hover:bg-white/10 transition-all transform hover:scale-105"
                    >
                        View Pricing
                    </Link>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer :laravel-version="laravelVersion" :php-version="phpVersion" />
    </div>
</template>

<style scoped>
/* Smooth animations */
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
