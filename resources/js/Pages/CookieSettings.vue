<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Footer from '@/Components/Footer.vue';
import { cookieManager } from '@/utils/cookieManager';

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

const cookiePreferences = ref({
    necessary: true,
    analytics: false,
    marketing: false,
    preferences: false
});

const showSuccessMessage = ref(false);

onMounted(() => {
    // Load current preferences
    cookiePreferences.value = cookieManager.getCookiePreferences();
});

const savePreferences = () => {
    cookieManager.updatePreferences(cookiePreferences.value);
    localStorage.setItem('cookieConsent', 'accepted');

    showSuccessMessage.value = true;
    setTimeout(() => {
        showSuccessMessage.value = false;
    }, 3000);
};

const resetPreferences = () => {
    cookieManager.resetPreferences();
    cookiePreferences.value = {
        necessary: true,
        analytics: false,
        marketing: false,
        preferences: false
    };

    showSuccessMessage.value = true;
    setTimeout(() => {
        showSuccessMessage.value = false;
    }, 3000);
};
</script>

<template>
    <Head title="Cookie Settings - Vidsmotion" />
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
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Cookie Settings
                    </h1>
                    <p class="text-xl text-gray-300">
                        Manage your cookie preferences and privacy settings
                    </p>
                </div>

                <!-- Success Message -->
                <div
                    v-if="showSuccessMessage"
                    class="mb-8 p-4 bg-green-900/20 border border-green-500/30 rounded-lg"
                >
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-green-400">Your cookie preferences have been saved successfully!</p>
                    </div>
                </div>

                <!-- Cookie Preferences -->
                <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
                    <h2 class="text-2xl font-bold text-white mb-6">Cookie Preferences</h2>

                    <div class="space-y-6">
                        <!-- Necessary Cookies -->
                        <div class="flex items-center justify-between p-6 bg-gray-800 rounded-lg">
                            <div class="flex-1">
                                <h3 class="text-white font-semibold text-lg mb-2">Necessary Cookies</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">
                                    These cookies are essential for the website to function properly. They enable basic functions like page navigation, access to secure areas, and remembering your login status. The website cannot function properly without these cookies.
                                </p>
                            </div>
                            <div class="flex items-center ml-6">
                                <span class="text-green-400 text-sm font-medium mr-3">Always Active</span>
                                <div class="w-12 h-6 bg-green-600 rounded-full flex items-center justify-end px-1">
                                    <div class="w-4 h-4 bg-white rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Analytics Cookies -->
                        <div class="flex items-center justify-between p-6 bg-gray-800 rounded-lg">
                            <div class="flex-1">
                                <h3 class="text-white font-semibold text-lg mb-2">Analytics Cookies</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">
                                    These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously. This helps us improve our website performance and user experience.
                                </p>
                            </div>
                            <div class="flex items-center ml-6">
                                <button
                                    @click="cookiePreferences.analytics = !cookiePreferences.analytics"
                                    :class="[
                                        'w-12 h-6 rounded-full flex items-center transition-colors',
                                        cookiePreferences.analytics ? 'bg-purple-600 justify-end' : 'bg-gray-600 justify-start'
                                    ]"
                                >
                                    <div class="w-4 h-4 bg-white rounded-full mx-1"></div>
                                </button>
                            </div>
                        </div>

                        <!-- Marketing Cookies -->
                        <div class="flex items-center justify-between p-6 bg-gray-800 rounded-lg">
                            <div class="flex-1">
                                <h3 class="text-white font-semibold text-lg mb-2">Marketing Cookies</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">
                                    These cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging for individual users and thereby more valuable for publishers and third party advertisers.
                                </p>
                            </div>
                            <div class="flex items-center ml-6">
                                <button
                                    @click="cookiePreferences.marketing = !cookiePreferences.marketing"
                                    :class="[
                                        'w-12 h-6 rounded-full flex items-center transition-colors',
                                        cookiePreferences.marketing ? 'bg-purple-600 justify-end' : 'bg-gray-600 justify-start'
                                    ]"
                                >
                                    <div class="w-4 h-4 bg-white rounded-full mx-1"></div>
                                </button>
                            </div>
                        </div>

                        <!-- Preferences Cookies -->
                        <div class="flex items-center justify-between p-6 bg-gray-800 rounded-lg">
                            <div class="flex-1">
                                <h3 class="text-white font-semibold text-lg mb-2">Preference Cookies</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">
                                    These cookies enable the website to remember information that changes the way the website behaves or looks, like your preferred language or the region you are in.
                                </p>
                            </div>
                            <div class="flex items-center ml-6">
                                <button
                                    @click="cookiePreferences.preferences = !cookiePreferences.preferences"
                                    :class="[
                                        'w-12 h-6 rounded-full flex items-center transition-colors',
                                        cookiePreferences.preferences ? 'bg-purple-600 justify-end' : 'bg-gray-600 justify-start'
                                    ]"
                                >
                                    <div class="w-4 h-4 bg-white rounded-full mx-1"></div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-end">
                        <button
                            @click="resetPreferences"
                            class="px-6 py-3 text-sm font-medium text-gray-300 border border-gray-600 rounded-lg hover:border-gray-500 hover:text-white transition-colors"
                        >
                            Reset to Default
                        </button>
                        <button
                            @click="savePreferences"
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105"
                        >
                            Save Preferences
                        </button>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-8 bg-gray-900 rounded-2xl p-8 border border-gray-800">
                    <h2 class="text-2xl font-bold text-white mb-6">More Information</h2>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-white font-semibold mb-2">How to manage cookies in your browser</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                Most web browsers allow you to control cookies through their settings preferences. You can set your browser to refuse cookies or delete certain cookies. However, if you choose to delete or refuse cookies, some features of our website may not function properly.
                            </p>
                        </div>

                        <div>
                            <h3 class="text-white font-semibold mb-2">Third-party cookies</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                Some cookies on our website are set by third-party services. We have no control over these cookies and they are subject to the privacy policies of the respective third parties.
                            </p>
                        </div>

                        <div>
                            <h3 class="text-white font-semibold mb-2">Contact us</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                If you have any questions about our use of cookies, please contact us at
                                <a href="mailto:privacy@vidsmotion.com" class="text-purple-400 hover:text-purple-300 underline">privacy@vidsmotion.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer :laravel-version="laravelVersion" :php-version="phpVersion" />
    </div>
</template>
