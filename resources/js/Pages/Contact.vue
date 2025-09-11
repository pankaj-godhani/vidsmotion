<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Footer from '@/Components/Footer.vue';

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

const form = ref({
    name: '',
    email: '',
    subject: '',
    message: '',
    type: 'general'
});

const isSubmitting = ref(false);
const submitMessage = ref('');

const submitForm = async () => {
    isSubmitting.value = true;
    submitMessage.value = '';

    // Simulate form submission
    await new Promise(resolve => setTimeout(resolve, 2000));

    submitMessage.value = 'Thank you for your message! We\'ll get back to you within 24 hours.';
    isSubmitting.value = false;

    // Reset form
    form.value = {
        name: '',
        email: '',
        subject: '',
        message: '',
        type: 'general'
    };
};
</script>

<template>
    <Head title="Contact Us - Vidsmotion" />
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
                        <Link :href="route('explore')" class="text-gray-300 hover:text-white transition-colors text-sm">Explore</Link>
                        <Link :href="route('pricing')" class="text-gray-300 hover:text-white transition-colors text-sm">Pricing</Link>
                        <div v-if="canLogin" class="flex items-center space-x-4">
                            <template v-if="$page.props.auth.user">
                                <Link :href="route('dashboard')" class="text-gray-300 hover:text-white transition-colors text-sm">Dashboard</Link>
                            </template>
                            <template v-else>
                                <Link :href="route('login')" class="text-gray-300 hover:text-white transition-colors text-sm">Sign In</Link>
                                <Link v-if="canRegister" :href="route('register')" class="px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-sm font-medium">Get Started</Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="pt-24 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Contact Us
                    </h1>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                        Have questions about Vidsmotion? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Form -->
                    <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
                        <h2 class="text-2xl font-bold text-white mb-6">Send us a message</h2>

                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Name *</label>
                                    <input
                                        type="text"
                                        id="name"
                                        v-model="form.name"
                                        required
                                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="Your name"
                                    />
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email *</label>
                                    <input
                                        type="email"
                                        id="email"
                                        v-model="form.email"
                                        required
                                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="your@email.com"
                                    />
                                </div>
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-300 mb-2">Subject *</label>
                                <input
                                    type="text"
                                    id="subject"
                                    v-model="form.subject"
                                    required
                                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="What's this about?"
                                />
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Inquiry Type</label>
                                <select
                                    id="type"
                                    v-model="form.type"
                                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                >
                                    <option value="general">General Question</option>
                                    <option value="technical">Technical Support</option>
                                    <option value="billing">Billing & Payments</option>
                                    <option value="feature">Feature Request</option>
                                    <option value="bug">Bug Report</option>
                                    <option value="partnership">Partnership</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Message *</label>
                                <textarea
                                    id="message"
                                    v-model="form.message"
                                    required
                                    rows="6"
                                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                                    placeholder="Tell us more about your inquiry..."
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                :class="[
                                    'w-full px-6 py-3 rounded-lg font-semibold text-white transition-all',
                                    isSubmitting
                                        ? 'bg-gray-700 cursor-not-allowed'
                                        : 'bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 transform hover:scale-105'
                                ]"
                            >
                                <div v-if="isSubmitting" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </div>
                                <div v-else>
                                    Send Message
                                </div>
                            </button>

                            <div v-if="submitMessage" class="p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
                                <p class="text-green-400 text-sm">{{ submitMessage }}</p>
                            </div>
                        </form>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-8">
                        <!-- Contact Details -->
                        <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
                            <h2 class="text-2xl font-bold text-white mb-6">Get in touch</h2>

                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-semibold mb-1">Email</h3>
                                        <p class="text-gray-400">support@vidsmotion.com</p>
                                        <p class="text-gray-400">business@vidsmotion.com</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-semibold mb-1">Address</h3>
                                        <p class="text-gray-400">123 AI Street<br>Tech City, TC 12345<br>United States</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-semibold mb-1">Business Hours</h3>
                                        <p class="text-gray-400">Monday - Friday: 9:00 AM - 6:00 PM PST<br>Saturday: 10:00 AM - 4:00 PM PST<br>Sunday: Closed</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Quick Links -->
                        <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
                            <h2 class="text-2xl font-bold text-white mb-6">Quick Help</h2>

                            <div class="space-y-4">
                                <Link :href="route('faq')" class="block p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                                    <h3 class="text-white font-semibold mb-1">Frequently Asked Questions</h3>
                                    <p class="text-gray-400 text-sm">Find answers to common questions</p>
                                </Link>

                                <a href="#docs" class="block p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                                    <h3 class="text-white font-semibold mb-1">Documentation</h3>
                                    <p class="text-gray-400 text-sm">Learn how to use Vidsmotion</p>
                                </a>

                                <a href="#status" class="block p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                                    <h3 class="text-white font-semibold mb-1">Service Status</h3>
                                    <p class="text-gray-400 text-sm">Check if our services are running</p>
                                </a>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
                            <h2 class="text-2xl font-bold text-white mb-6">Follow Us</h2>

                            <div class="flex space-x-4">
                                <a href="#" class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer :laravel-version="laravelVersion" :php-version="phpVersion" />
    </div>
</template>
