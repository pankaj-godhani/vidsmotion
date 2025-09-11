<script setup>
import { ref, onMounted } from 'vue';
import { cookieManager } from '@/utils/cookieManager';

const showBanner = ref(false);
const isVisible = ref(false);

// Show banner if cookies haven't been accepted
onMounted(() => {
    if (!cookieManager.hasConsent()) {
        showBanner.value = true;
        // Add slight delay for smooth animation
        setTimeout(() => {
            isVisible.value = true;
        }, 100);
    }
});

// Accept all cookies
const acceptAllCookies = () => {
    cookieManager.updatePreferences({
        necessary: true,
        analytics: true,
        marketing: true,
        preferences: true
    });

    localStorage.setItem('cookieConsent', 'accepted');

    // Hide banner with animation
    isVisible.value = false;
    setTimeout(() => {
        showBanner.value = false;
    }, 300);

    // Initialize all tracking
    cookieManager.initializeAnalytics();
    cookieManager.initializeMarketing();

    // Set a welcome cookie
    cookieManager.setCookie('welcome_message_shown', 'true', {
        expires: 30,
        type: 'preferences'
    });
};

// Reject non-essential cookies
const rejectNonEssential = () => {
    cookieManager.updatePreferences({
        necessary: true,
        analytics: false,
        marketing: false,
        preferences: false
    });

    localStorage.setItem('cookieConsent', 'accepted');

    // Hide banner with animation
    isVisible.value = false;
    setTimeout(() => {
        showBanner.value = false;
    }, 300);

    // Clear any existing non-essential cookies
    cookieManager.clearNonEssentialCookies();
};

// Customize preferences
const showCustomize = ref(false);
const cookiePreferences = ref({
    necessary: true, // Always true, can't be disabled
    analytics: false,
    marketing: false,
    preferences: false
});

const toggleCustomize = () => {
    showCustomize.value = !showCustomize.value;
};

const saveCustomPreferences = () => {
    cookieManager.updatePreferences(cookiePreferences.value);
    localStorage.setItem('cookieConsent', 'accepted');

    // Hide banner with animation
    isVisible.value = false;
    setTimeout(() => {
        showBanner.value = false;
    }, 300);

    // Initialize tracking based on preferences
    if (cookiePreferences.value.analytics) {
        cookieManager.initializeAnalytics();
    }
    if (cookiePreferences.value.marketing) {
        cookieManager.initializeMarketing();
    }
    if (cookiePreferences.value.preferences) {
        cookieManager.setCookie('user_preferences', 'saved', {
            expires: 365,
            type: 'preferences'
        });
    }
};
</script>

<template>
    <!-- Cookie Consent Banner -->
    <div
        v-if="showBanner"
        :class="[
            'fixed bottom-0 left-0 right-0 z-50 bg-gray-900 border-t border-gray-700 shadow-2xl transition-all duration-300 ease-in-out',
            isVisible ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0'
        ]"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <!-- Content -->
                <div class="flex-1">
                    <div class="flex items-start space-x-3">
                        <!-- Cookie Icon -->
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white mb-2">
                                We use cookies to enhance your experience
                            </h3>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                We use cookies to improve your browsing experience, serve personalized content, and analyze our traffic.
                                By clicking "Accept All", you consent to our use of cookies. You can customize your preferences or learn more in our
                                <a href="/privacy" class="text-purple-400 hover:text-purple-300 underline">Privacy Policy</a>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 lg:ml-6">
                    <!-- Customize Button -->
                    <button
                        @click="toggleCustomize"
                        class="px-4 py-2 text-sm font-medium text-gray-300 border border-gray-600 rounded-lg hover:border-gray-500 hover:text-white transition-colors"
                    >
                        Customize
                    </button>

                    <!-- Reject Non-Essential Button -->
                    <button
                        @click="rejectNonEssential"
                        class="px-4 py-2 text-sm font-medium text-gray-300 border border-gray-600 rounded-lg hover:border-gray-500 hover:text-white transition-colors"
                    >
                        Reject Non-Essential
                    </button>

                    <!-- Accept All Button -->
                    <button
                        @click="acceptAllCookies"
                        class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105"
                    >
                        Accept All
                    </button>
                </div>
            </div>

            <!-- Customize Preferences Panel -->
            <div
                v-if="showCustomize"
                class="mt-6 pt-6 border-t border-gray-700"
            >
                <h4 class="text-white font-semibold mb-4">Cookie Preferences</h4>

                <div class="space-y-4">
                    <!-- Necessary Cookies -->
                    <div class="flex items-center justify-between p-4 bg-gray-800 rounded-lg">
                        <div>
                            <h5 class="text-white font-medium">Necessary Cookies</h5>
                            <p class="text-gray-400 text-sm">Essential for the website to function properly</p>
                        </div>
                        <div class="flex items-center">
                            <span class="text-green-400 text-sm font-medium mr-3">Always Active</span>
                            <div class="w-12 h-6 bg-green-600 rounded-full flex items-center justify-end px-1">
                                <div class="w-4 h-4 bg-white rounded-full"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics Cookies -->
                    <div class="flex items-center justify-between p-4 bg-gray-800 rounded-lg">
                        <div>
                            <h5 class="text-white font-medium">Analytics Cookies</h5>
                            <p class="text-gray-400 text-sm">Help us understand how visitors interact with our website</p>
                        </div>
                        <div class="flex items-center">
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
                    <div class="flex items-center justify-between p-4 bg-gray-800 rounded-lg">
                        <div>
                            <h5 class="text-white font-medium">Marketing Cookies</h5>
                            <p class="text-gray-400 text-sm">Used to track visitors across websites for advertising purposes</p>
                        </div>
                        <div class="flex items-center">
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
                    <div class="flex items-center justify-between p-4 bg-gray-800 rounded-lg">
                        <div>
                            <h5 class="text-white font-medium">Preference Cookies</h5>
                            <p class="text-gray-400 text-sm">Remember your settings and preferences</p>
                        </div>
                        <div class="flex items-center">
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

                <!-- Save Preferences Button -->
                <div class="mt-6 flex justify-end">
                    <button
                        @click="saveCustomPreferences"
                        class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105"
                    >
                        Save Preferences
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Smooth animations for the banner */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar for the customize panel */
::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: #374151;
}

::-webkit-scrollbar-thumb {
    background: #8b5cf6;
    border-radius: 2px;
}

::-webkit-scrollbar-thumb:hover {
    background: #7c3aed;
}
</style>
