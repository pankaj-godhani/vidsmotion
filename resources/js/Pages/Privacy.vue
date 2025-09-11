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
    <Head title="Privacy Policy - Vidsmotion" />
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
                        Privacy Policy
                    </h1>
                    <p class="text-xl text-gray-300">
                        Last updated: January 2025
                    </p>
                </div>

                <!-- Content -->
                <div class="prose prose-invert max-w-none">
                    <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
                        <h2 class="text-2xl font-bold text-white mb-6">1. Introduction</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            Vidsmotion ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our AI-powered video generation service. Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the service.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">2. Information We Collect</h2>

                        <h3 class="text-xl font-semibold text-white mb-4">2.1 Personal Information</h3>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            We may collect personal information that you voluntarily provide to us when you:
                        </p>
                        <ul class="text-gray-300 leading-relaxed mb-6 list-disc list-inside space-y-2">
                            <li>Register for an account</li>
                            <li>Use our video generation services</li>
                            <li>Contact us for support</li>
                            <li>Subscribe to our newsletter</li>
                            <li>Participate in surveys or promotions</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-white mb-4">2.2 Information Collected Automatically</h3>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            We automatically collect certain information when you use our service:
                        </p>
                        <ul class="text-gray-300 leading-relaxed mb-6 list-disc list-inside space-y-2">
                            <li>Device information (IP address, browser type, operating system)</li>
                            <li>Usage data (pages visited, time spent, features used)</li>
                            <li>Cookies and similar tracking technologies</li>
                            <li>Log files and analytics data</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-white mb-6">3. How We Use Your Information</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            We use the information we collect to:
                        </p>
                        <ul class="text-gray-300 leading-relaxed mb-6 list-disc list-inside space-y-2">
                            <li>Provide, operate, and maintain our service</li>
                            <li>Process your video generation requests</li>
                            <li>Improve, personalize, and expand our service</li>
                            <li>Understand and analyze how you use our service</li>
                            <li>Develop new products, services, features, and functionality</li>
                            <li>Communicate with you for customer service and support</li>
                            <li>Send you marketing communications (with your consent)</li>
                            <li>Process transactions and send related information</li>
                            <li>Find and prevent fraud and abuse</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-white mb-6">4. AI and Content Processing</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            When you use our AI video generation service, we process your input prompts and generate videos based on your requests. We may temporarily store your prompts and generated content to improve our AI models and provide better service. We do not use your content for training purposes without your explicit consent.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">5. Information Sharing and Disclosure</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            We may share your information in the following circumstances:
                        </p>
                        <ul class="text-gray-300 leading-relaxed mb-6 list-disc list-inside space-y-2">
                            <li><strong>Service Providers:</strong> With third-party vendors who assist us in operating our service</li>
                            <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                            <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                            <li><strong>Consent:</strong> With your explicit consent for other purposes</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-white mb-6">6. Data Security</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet or electronic storage is 100% secure, and we cannot guarantee absolute security.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">7. Data Retention</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. When we no longer need your information, we will securely delete or anonymize it.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">8. Your Rights and Choices</h2>
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Depending on your location, you may have the following rights regarding your personal information:
                        </p>
                        <ul class="text-gray-300 leading-relaxed mb-6 list-disc list-inside space-y-2">
                            <li><strong>Access:</strong> Request access to your personal information</li>
                            <li><strong>Correction:</strong> Request correction of inaccurate information</li>
                            <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                            <li><strong>Portability:</strong> Request transfer of your data to another service</li>
                            <li><strong>Objection:</strong> Object to processing of your personal information</li>
                            <li><strong>Restriction:</strong> Request restriction of processing</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-white mb-6">9. Cookies and Tracking Technologies</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            We use cookies and similar tracking technologies to enhance your experience on our service. You can control cookie settings through your browser preferences. However, disabling cookies may affect the functionality of our service.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">10. Third-Party Services</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            Our service may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties. We encourage you to read their privacy policies before providing any personal information.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">11. International Data Transfers</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            Your information may be transferred to and processed in countries other than your own. We ensure that such transfers comply with applicable data protection laws and implement appropriate safeguards to protect your information.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">12. Children's Privacy</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            Our service is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe your child has provided us with personal information, please contact us.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">13. Changes to This Privacy Policy</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. You are advised to review this Privacy Policy periodically for any changes.
                        </p>

                        <h2 class="text-2xl font-bold text-white mb-6">14. Contact Us</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            If you have any questions about this Privacy Policy or our privacy practices, please contact us:
                        </p>
                        <div class="bg-gray-800 rounded-lg p-4 mb-6">
                            <p class="text-gray-300">
                                Email: privacy@vidsmotion.com<br>
                                Address: 123 AI Street, Tech City, TC 12345<br>
                                Phone: +1 (555) 123-4567
                            </p>
                        </div>

                        <h2 class="text-2xl font-bold text-white mb-6">15. GDPR Compliance</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            If you are located in the European Union, you have additional rights under the General Data Protection Regulation (GDPR). We are committed to complying with GDPR requirements and protecting your privacy rights. For GDPR-related inquiries, please contact our Data Protection Officer at dpo@vidsmotion.com.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer :laravel-version="laravelVersion" :php-version="phpVersion" />
    </div>
</template>
