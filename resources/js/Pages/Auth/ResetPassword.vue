<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found');
        form.setError('email', 'Security token not found. Please refresh the page and try again.');
        return;
    }

    // Clear any previous errors before submission
    form.clearErrors();

    form.post(route('password.store'), {
        onFinish: () => {
            // Only reset password fields if there are no errors
            if (!form.hasErrors) {
                form.reset('password', 'password_confirmation');
            }
        },
        onError: (errors) => {
            console.error('Password reset errors:', errors);

            // Handle specific error cases
            if (errors.email && errors.email.includes('expired')) {
                form.setError('email', 'This password reset link has expired. Please request a new one.');
            } else if (errors.email && errors.email.includes('419')) {
                form.setError('email', 'Security token expired. Please refresh the page and try again.');
            } else if (errors.password) {
                // Handle password validation errors (like confirmation mismatch)
                form.setError('password', errors.password);
            } else if (errors.password_confirmation) {
                // Handle password confirmation errors
                form.setError('password_confirmation', errors.password_confirmation);
            }
        },
        onSuccess: () => {
            // Clear any previous errors on success
            form.clearErrors();
        },
        preserveState: true, // Keep form state to show validation errors
        preserveScroll: true, // Keep scroll position
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
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

    // Check if token might be expired (basic check)
    if (!props.token || props.token.length < 10) {
        form.setError('email', 'Invalid or expired password reset link. Please request a new one.');
    }
});
</script>

<template>
    <Head title="Reset Password - VidsMotion" />

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
                            Reset Your Password
                        </h2>
                        <p class="text-xl text-white mb-8 drop-shadow-lg">
                            🔐 Secure your account with a new password and unlock the power of AI video creation. Your creative journey continues here!
                        </p>

                        <!-- Feature Highlights -->
                        <div class="space-y-4 text-left">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                                <span class="text-white drop-shadow-md">🔒 Bank-level security protection</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                                <span class="text-white drop-shadow-md">⚡ Instant access to your AI studio</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                                <span class="text-white drop-shadow-md">🎬 Resume creating amazing videos</span>
                            </div>
                        </div>

                        <!-- Security Icon -->
                        <div class="mt-8 relative">
                            <div class="w-full h-32 bg-gradient-to-r from-purple-600/20 to-blue-600/20 rounded-lg border border-white/20 backdrop-blur-sm flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-white/60 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <p class="text-white/60 text-sm">Secure Reset Process</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Reset Form -->
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
                            <span class="text-2xl font-bold text-white">VidsMotion</span>
                        </Link>
                    </div>

                    <!-- Reset Form -->
                    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-2xl p-8">
                        <div class="text-center mb-8">
                            <h1 class="text-2xl font-bold text-white mb-2">Create New Password</h1>
                            <p class="text-gray-400">Choose a strong password to protect your VidsMotion account and continue creating stunning AI videos.</p>

                            <!-- Error Message for Expired Token or CSRF Issues -->
                            <div v-if="form.errors.email && (form.errors.email.includes('expired') || form.errors.email.includes('419') || form.errors.email.includes('Security token'))" class="mt-4 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <p class="text-red-300 text-sm font-medium">{{ form.errors.email }}</p>
                                </div>
                                <div class="mt-3 flex flex-col space-y-2">
                                    <button @click="window.location.reload()" class="text-red-400 hover:text-red-300 text-sm underline">
                                        🔄 Refresh Page and Try Again
                                    </button>
                                    <Link :href="route('password.request')" class="text-red-400 hover:text-red-300 text-sm underline">
                                        Request a new password reset link
                                    </Link>
                                </div>
                            </div>

                            <!-- General Error Display -->
                            <div v-if="form.hasErrors && !form.errors.email" class="mt-4 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <p class="text-red-300 text-sm font-medium">Please fix the errors below and try again.</p>
                                </div>
                            </div>

                            <!-- Email Display (only show if no expired token error) -->
                            <div v-else-if="!form.errors.email" class="mt-4 p-3 bg-blue-500/20 border border-blue-500/30 rounded-lg">
                                <p class="text-blue-300 text-sm">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    {{ email }}
                                </p>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6" v-if="!form.errors.email || (!form.errors.email.includes('expired') && !form.errors.email.includes('419') && !form.errors.email.includes('Security token'))">
                            <!-- Email Field (Hidden) -->
                            <input type="hidden" v-model="form.email" />

                            <!-- New Password Field -->
                            <div>
                                <InputLabel for="password" value="New Password" class="text-gray-300 mb-2" />
                                <div class="relative">
                <TextInput
                    id="password"
                    type="password"
                                        class="w-full bg-gray-800/50 border-gray-700 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-purple-500/20 rounded-lg"
                    v-model="form.password"
                    required
                                        autofocus
                    autocomplete="new-password"
                                        placeholder="Enter your new password"
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
                                <InputLabel for="password_confirmation" value="Confirm New Password" class="text-gray-300 mb-2" />
                                <div class="relative">
                <TextInput
                    id="password_confirmation"
                    type="password"
                                        class="w-full bg-gray-800/50 border-gray-700 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-purple-500/20 rounded-lg"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                                        placeholder="Confirm your new password"
                                    />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
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
                                    Resetting password...
                                </span>
                                <span v-else class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                    Reset Password
                                </span>
                </PrimaryButton>
                        </form>

                        <!-- Back to Login -->
                        <div class="mt-8 text-center">
                            <p class="text-gray-400">
                                Remember your password?
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
