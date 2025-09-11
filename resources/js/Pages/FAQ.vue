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

const openFaq = ref(null);

const faqs = ref([
    {
        id: 1,
        category: 'General',
        question: 'What is Vidsmotion?',
        answer: 'Vidsmotion is an AI-powered video generation platform that allows you to create professional-quality videos from text descriptions. Simply describe what you want to see, and our advanced AI will generate stunning videos for you.'
    },
    {
        id: 2,
        category: 'General',
        question: 'How does AI video generation work?',
        answer: 'Our AI uses advanced machine learning models trained on vast datasets of video content. When you provide a text prompt, the AI analyzes your description and generates corresponding video content using neural networks that understand visual concepts, motion, and storytelling.'
    },
    {
        id: 3,
        category: 'General',
        question: 'What types of videos can I create?',
        answer: 'You can create various types of videos including promotional content, educational videos, social media clips, product demonstrations, storytelling videos, and more. The AI can generate content in different styles, from realistic to animated, depending on your prompt.'
    },
    {
        id: 4,
        category: 'Pricing',
        question: 'Is there a free trial?',
        answer: 'Yes! We offer a free tier that includes a limited number of video generations per month. This allows you to test our platform and see the quality of our AI-generated videos before committing to a paid plan.'
    },
    {
        id: 5,
        category: 'Pricing',
        question: 'What are the pricing plans?',
        answer: 'We offer several pricing tiers: Free (limited generations), Basic ($19/month), Pro ($49/month), and Enterprise (custom pricing). Each plan includes different generation limits, video quality options, and additional features. Check our pricing page for detailed information.'
    },
    {
        id: 6,
        category: 'Pricing',
        question: 'Can I cancel my subscription anytime?',
        answer: 'Yes, you can cancel your subscription at any time. Your access will continue until the end of your current billing period, and you won\'t be charged for the next period. You can manage your subscription in your account settings.'
    },
    {
        id: 7,
        category: 'Technical',
        question: 'What video formats are supported?',
        answer: 'We support multiple video formats including MP4, MOV, and AVI. Videos are typically generated in HD quality (1080p) with options for different aspect ratios including 16:9, 9:16 (vertical), and 1:1 (square) for social media platforms.'
    },
    {
        id: 8,
        category: 'Technical',
        question: 'How long does it take to generate a video?',
        answer: 'Video generation time varies depending on the complexity and length of your request. Simple videos typically take 1-3 minutes, while more complex or longer videos may take 5-10 minutes. You\'ll receive an email notification when your video is ready.'
    },
    {
        id: 9,
        category: 'Technical',
        question: 'What are the video length limits?',
        answer: 'Free tier users can generate videos up to 10 seconds long. Basic plan users can create videos up to 30 seconds, Pro users up to 60 seconds, and Enterprise users can create longer videos based on their specific needs.'
    },
    {
        id: 10,
        category: 'Technical',
        question: 'Can I edit the generated videos?',
        answer: 'Yes! You can download your generated videos and edit them using any video editing software. We also provide basic editing tools within our platform, including trimming, adding text overlays, and adjusting playback speed.'
    },
    {
        id: 11,
        category: 'Usage',
        question: 'Can I use the generated videos commercially?',
        answer: 'Yes, you have full commercial rights to videos generated with our service. You can use them for marketing, advertising, social media, presentations, and any other commercial purposes. However, you cannot resell the videos as standalone products.'
    },
    {
        id: 12,
        category: 'Usage',
        question: 'Are there any content restrictions?',
        answer: 'Yes, we have content policies to ensure responsible use. You cannot generate content that is illegal, harmful, defamatory, or violates intellectual property rights. We also prohibit generating content that promotes violence, discrimination, or hate speech.'
    },
    {
        id: 13,
        category: 'Usage',
        question: 'How do I get the best results from the AI?',
        answer: 'For best results, be specific and descriptive in your prompts. Include details about the scene, characters, actions, style, and mood. For example, instead of "a dog," try "a golden retriever playing fetch in a sunny park with children laughing."'
    },
    {
        id: 14,
        category: 'Account',
        question: 'How do I create an account?',
        answer: 'Creating an account is simple! Click the "Get Started" button on our homepage, enter your email address, create a password, and verify your email. You can also sign up using your Google or Apple account for faster registration.'
    },
    {
        id: 15,
        category: 'Account',
        question: 'Can I share my account with others?',
        answer: 'Account sharing is not allowed as it violates our terms of service. Each user should have their own account. However, Enterprise plans may include team collaboration features that allow multiple users under a single organization.'
    },
    {
        id: 16,
        category: 'Support',
        question: 'How can I get help if I\'m having issues?',
        answer: 'We offer multiple support channels: email support (support@vidsmotion.com), live chat during business hours, comprehensive documentation, and video tutorials. Premium users also get priority support with faster response times.'
    },
    {
        id: 17,
        category: 'Support',
        question: 'What if I\'m not satisfied with a generated video?',
        answer: 'If you\'re not satisfied with a generated video, you can regenerate it with the same prompt or try a different approach. We also offer refunds for unused generations if you\'re completely unsatisfied with the service quality.'
    },
    {
        id: 18,
        category: 'Privacy',
        question: 'Is my data secure?',
        answer: 'Yes, we take data security seriously. All data is encrypted in transit and at rest. We use industry-standard security measures and comply with GDPR and other privacy regulations. Your prompts and generated content are kept private and not shared with third parties.'
    }
]);

const categories = ref(['All', 'General', 'Pricing', 'Technical', 'Usage', 'Account', 'Support', 'Privacy']);
const selectedCategory = ref('All');

const filteredFaqs = computed(() => {
    if (selectedCategory.value === 'All') {
        return faqs.value;
    }
    return faqs.value.filter(faq => faq.category === selectedCategory.value);
});

const toggleFaq = (id) => {
    openFaq.value = openFaq.value === id ? null : id;
};
</script>

<template>
    <Head title="FAQ - Vidsmotion" />
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
                        Frequently Asked Questions
                    </h1>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                        Find answers to common questions about Vidsmotion and our AI video generation service.
                    </p>
                </div>

                <!-- Search Bar -->
                <div class="mb-8">
                    <div class="relative max-w-2xl mx-auto">
                        <input
                            type="text"
                            placeholder="Search FAQs..."
                            class="w-full px-6 py-4 bg-gray-900 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="mb-8">
                    <div class="flex flex-wrap justify-center gap-3">
                        <button
                            v-for="category in categories"
                            :key="category"
                            @click="selectedCategory = category"
                            :class="[
                                'px-6 py-2 rounded-full text-sm font-medium transition-all',
                                selectedCategory === category
                                    ? 'bg-purple-600 text-white'
                                    : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                            ]"
                        >
                            {{ category }}
                        </button>
                    </div>
                </div>

                <!-- FAQ Items -->
                <div class="space-y-4">
                    <div
                        v-for="faq in filteredFaqs"
                        :key="faq.id"
                        class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden"
                    >
                        <button
                            @click="toggleFaq(faq.id)"
                            class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-800 transition-colors"
                        >
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="px-3 py-1 bg-purple-600/20 text-purple-400 text-xs font-medium rounded-full">
                                        {{ faq.category }}
                                    </span>
                                </div>
                                <h3 class="text-white font-semibold text-lg">{{ faq.question }}</h3>
                            </div>
                            <svg
                                :class="[
                                    'w-5 h-5 text-gray-400 transition-transform duration-200',
                                    openFaq === faq.id ? 'rotate-180' : ''
                                ]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div
                            v-if="openFaq === faq.id"
                            class="px-6 pb-4"
                        >
                            <div class="border-t border-gray-800 pt-4">
                                <p class="text-gray-300 leading-relaxed">{{ faq.answer }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Still Have Questions -->
                <div class="mt-16 text-center">
                    <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
                        <h2 class="text-2xl font-bold text-white mb-4">Still have questions?</h2>
                        <p class="text-gray-300 mb-6">
                            Can't find the answer you're looking for? Our support team is here to help.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link
                                :href="route('contact')"
                                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105"
                            >
                                Contact Support
                            </Link>
                            <a
                                href="mailto:support@vidsmotion.com"
                                class="px-6 py-3 border border-gray-600 text-white rounded-lg font-semibold hover:border-gray-500 hover:bg-gray-800 transition-all"
                            >
                                Email Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer :laravel-version="laravelVersion" :php-version="phpVersion" />
    </div>
</template>
