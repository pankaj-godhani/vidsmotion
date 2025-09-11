<script setup>
import { Head, Link } from '@inertiajs/vue3';
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
</script>

<template>
    <Head title="Terms & Conditions - Vidsmotion" />
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
                        Terms & Conditions
                    </h1>
                    <p class="text-xl text-gray-300">
                        Last updated: January 2025
                    </p>
                </div>

                <!-- Content -->
                <div class="prose prose-invert max-w-none">
                    <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
                        <h2 class="text-2xl font-bold text-white mb-6">1. Acceptance of Terms</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            By accessing and using Vidsmotion ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">2. Use License</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Permission is granted to temporarily download one copy of Vidsmotion per device for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
                        </p>
                        <ul class="text-gray-300 leading-relaxed mb-6 list-disc list-inside space-y-2">
                            <li>modify or copy the materials</li>
                            <li>use the materials for any commercial purpose or for any public display (commercial or non-commercial)</li>
                            <li>attempt to decompile or reverse engineer any software contained on Vidsmotion's website</li>
                            <li>remove any copyright or other proprietary notations from the materials</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-white mb-6">3. AI-Generated Content</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            All videos generated through our AI service are created using artificial intelligence technology. While we strive for high quality, we cannot guarantee the accuracy, appropriateness, or suitability of AI-generated content for your specific needs. You are responsible for reviewing and ensuring the content meets your requirements before use.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">4. User Content and Conduct</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            You agree not to use the Service to:
                        </p>
                        <ul class="text-gray-300 leading-relaxed mb-6 list-disc list-inside space-y-2">
                            <li>Generate content that is illegal, harmful, threatening, abusive, defamatory, or otherwise objectionable</li>
                            <li>Infringe upon the rights of others, including intellectual property rights</li>
                            <li>Generate content that promotes violence, discrimination, or hate speech</li>
                            <li>Attempt to gain unauthorized access to any part of the Service</li>
                            <li>Use the Service for any commercial purpose without explicit permission</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-white mb-6">5. Intellectual Property</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            The Service and its original content, features, and functionality are and will remain the exclusive property of Vidsmotion and its licensors. The Service is protected by copyright, trademark, and other laws. Our trademarks and trade dress may not be used in connection with any product or service without our prior written consent.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">6. Privacy Policy</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the Service, to understand our practices.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">7. Service Availability</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            We reserve the right to modify, suspend, or discontinue the Service (or any part thereof) at any time with or without notice. You agree that we shall not be liable to you or to any third party for any modification, suspension, or discontinuance of the Service.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">8. Limitation of Liability</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            In no event shall Vidsmotion, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your use of the Service.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">9. Indemnification</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            You agree to defend, indemnify, and hold harmless Vidsmotion and its licensee and licensors, and their employees, contractors, agents, officers and directors, from and against any and all claims, damages, obligations, losses, liabilities, costs or debt, and expenses (including but not limited to attorney's fees).
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">10. Governing Law</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            These Terms shall be interpreted and governed by the laws of the United States, without regard to its conflict of law provisions. Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">11. Changes to Terms</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will try to provide at least 30 days notice prior to any new terms taking effect.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">12. Contact Information</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            If you have any questions about these Terms & Conditions, please contact us at:
                        </p>
                        <div class="bg-gray-800 rounded-lg p-4 mb-6">
                            <p class="text-gray-300">
                                Email: legal@vidsmotion.com<br>
                                Address: 123 AI Street, Tech City, TC 12345
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
