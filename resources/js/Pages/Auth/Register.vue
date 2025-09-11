<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

// AI Animation elements
const particles = ref([]);

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
</script>

<template>
    <Head title="Create Account - Vidsmotion" />

    <div class="min-h-screen bg-black text-white relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900/20 via-blue-900/20 to-cyan-900/20"></div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div
                v-for="particle in particles"
                :key="particle.id"
                class="absolute rounded-full bg-white/10 animate-pulse"
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

        <!-- Neural Network Lines -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="neuralGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#8B5CF6;stop-opacity:0.3" />
                        <stop offset="50%" style="stop-color:#3B82F6;stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:#06B6D4;stop-opacity:0.3" />
                    </linearGradient>
                </defs>
                <path d="M10,20 Q30,10 50,30 T90,20" stroke="url(#neuralGradient)" stroke-width="0.5" fill="none" opacity="0.6">
                    <animate attributeName="d"
                        values="M10,20 Q30,10 50,30 T90,20;M10,20 Q30,30 50,10 T90,20;M10,20 Q30,10 50,30 T90,20"
                        dur="8s" repeatCount="indefinite" />
                </path>
                <path d="M10,50 Q30,40 50,60 T90,50" stroke="url(#neuralGradient)" stroke-width="0.5" fill="none" opacity="0.4">
                    <animate attributeName="d"
                        values="M10,50 Q30,40 50,60 T90,50;M10,50 Q30,60 50,40 T90,50;M10,50 Q30,40 50,60 T90,50"
                        dur="6s" repeatCount="indefinite" />
                </path>
                <path d="M10,80 Q30,70 50,90 T90,80" stroke="url(#neuralGradient)" stroke-width="0.5" fill="none" opacity="0.5">
                    <animate attributeName="d"
                        values="M10,80 Q30,70 50,90 T90,80;M10,80 Q30,90 50,70 T90,80;M10,80 Q30,70 50,90 T90,80"
                        dur="10s" repeatCount="indefinite" />
                </path>
            </svg>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 flex min-h-screen">
            <!-- Left Side - AI Video Background -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
                <!-- AI Video Background -->
                <div class="absolute inset-0">
                    <!-- Video Background -->
                    <video
                        class="w-full h-full object-cover opacity-20"
                        autoplay
                        muted
                        loop
                        playsinline
                    >
                        <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" type="video/mp4">
                        <!-- Fallback for browsers that don't support video -->
                        <div class="w-full h-full bg-gradient-to-br from-purple-900/30 via-blue-900/30 to-cyan-900/30"></div>
                    </video>

                    <!-- Overlay for better text readability -->
                    <div class="absolute inset-0 bg-black/40"></div>

                    <!-- AI Pattern Overlay -->
                    <div class="absolute inset-0 opacity-30">
                        <div class="w-full h-full grid-pattern"></div>
                    </div>
                </div>

                <!-- Content Overlay -->
                <div class="relative z-10 flex flex-col justify-center items-center p-12 w-full">
                    <div class="max-w-md text-center">
                        <!-- AI Brain Animation -->
                        <div class="relative mb-8">
                            <div class="w-32 h-32 mx-auto relative">
                                <!-- Outer Ring -->
                                <div class="absolute inset-0 rounded-full border-2 border-purple-500/50 animate-spin" style="animation-duration: 8s;"></div>
                                <!-- Middle Ring -->
                                <div class="absolute inset-4 rounded-full border-2 border-blue-500/60 animate-spin" style="animation-duration: 6s; animation-direction: reverse;"></div>
                                <!-- Inner Core -->
                                <div class="absolute inset-8 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 animate-pulse"></div>
                                <!-- Central Dot -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-4 h-4 bg-white rounded-full animate-ping"></div>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-4xl font-bold mb-4 bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent drop-shadow-lg">
                            Join Vidsmotion
                        </h2>
                        <p class="text-xl text-white mb-8 drop-shadow-lg">
                            Start your journey into the future of AI video generation. Create amazing content with cutting-edge technology.
                        </p>

                        <!-- Feature Highlights -->
                        <div class="space-y-4 text-left">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                                <span class="text-white drop-shadow-md">Free account to get started</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                                <span class="text-white drop-shadow-md">Access to AI video tools</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                                <span class="text-white drop-shadow-md">Join the creative community</span>
                            </div>
                        </div>

                        <!-- AI Video Preview -->
                        <div class="mt-8 relative">
                            <div class="w-full h-32 bg-gradient-to-r from-purple-600/20 to-blue-600/20 rounded-lg border border-white/20 backdrop-blur-sm flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-white/60 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-white/60 text-sm">Start Creating Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 lg:p-12">
                <div class="max-w-md mx-auto w-full">
                    <!-- Logo -->
                    <div class="text-center mb-8">
                        <Link :href="route('welcome')" class="inline-flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-7 h-7 text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-white">Vidsmotion</span>
                        </Link>
                    </div>

                    <!-- Register Form -->
                    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-8">
                        <div class="text-center mb-8">
                            <h1 class="text-2xl font-bold text-white mb-2">Create Account</h1>
                            <p class="text-gray-400">Join Vidsmotion and start creating amazing videos.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name Field -->
                            <div>
                                <InputLabel for="name" value="Full Name" class="text-gray-300 mb-2" />
                                <div class="relative">
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="w-full bg-gray-800/50 border-gray-700 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-purple-500/20 rounded-lg"
                                        v-model="form.name"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        placeholder="Enter your full name"
                                    />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Email Field -->
                            <div>
                                <InputLabel for="email" value="Email Address" class="text-gray-300 mb-2" />
                                <div class="relative">
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="w-full bg-gray-800/50 border-gray-700 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-purple-500/20 rounded-lg"
                                        v-model="form.email"
                                        required
                                        autocomplete="username"
                                        placeholder="Enter your email"
                                    />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <!-- Password Field -->
                            <div>
                                <InputLabel for="password" value="Password" class="text-gray-300 mb-2" />
                                <div class="relative">
                                    <TextInput
                                        id="password"
                                        type="password"
                                        class="w-full bg-gray-800/50 border-gray-700 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-purple-500/20 rounded-lg"
                                        v-model="form.password"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Create a password"
                                    />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <!-- Confirm Password Field -->
                            <div>
                                <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-300 mb-2" />
                                <div class="relative">
                                    <TextInput
                                        id="password_confirmation"
                                        type="password"
                                        class="w-full bg-gray-800/50 border-gray-700 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-purple-500/20 rounded-lg"
                                        v-model="form.password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Confirm your password"
                                    />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>

                            <!-- Submit Button -->
                            <PrimaryButton
                                class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500/50"
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Creating account...
                                </span>
                                <span v-else>Create Account</span>
                            </PrimaryButton>
                        </form>

                        <!-- Sign In Link -->
                        <div class="mt-8 text-center">
                            <p class="text-gray-400">
                                Already have an account?
                                <Link :href="route('login')" class="text-purple-400 hover:text-purple-300 font-medium transition-colors">
                                    Sign in here
                                </Link>
                            </p>
                        </div>
                    </div>

                    <!-- Back to Home -->
                    <div class="mt-6 text-center">
                        <Link :href="route('welcome')" class="text-gray-400 hover:text-white transition-colors text-sm">
                            ← Back to Home
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Grid Pattern */
.grid-pattern {
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
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
