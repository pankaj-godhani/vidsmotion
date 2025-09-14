<template>
    <Head>
        <!-- Dynamic Title -->
        <title>{{ seoData?.title || 'VidsMotion - AI Video Generator' }}</title>

        <!-- Dynamic Meta Description -->
        <meta name="description" :content="seoData?.description || 'Create stunning AI-generated videos with VidsMotion'">

        <!-- Dynamic Keywords -->
        <meta name="keywords" :content="seoData?.keywords || 'AI video generator, artificial intelligence, video creation'">

        <!-- Dynamic Canonical URL -->
        <link rel="canonical" :href="seoData?.canonical || $page.url">

        <!-- Dynamic Open Graph Tags -->
        <meta property="og:title" :content="seoData?.og_title || seoData?.title">
        <meta property="og:description" :content="seoData?.og_description || seoData?.description">
        <meta property="og:image" :content="seoData?.og_image">
        <meta property="og:url" :content="seoData?.canonical || $page.url">

        <!-- Dynamic Twitter Card Tags -->
        <meta name="twitter:title" :content="seoData?.og_title || seoData?.title">
        <meta name="twitter:description" :content="seoData?.og_description || seoData?.description">
        <meta name="twitter:image" :content="seoData?.og_image">

        <!-- Structured Data -->
        <script v-if="seoData?.structured_data" type="application/ld+json" v-html="JSON.stringify(seoData.structured_data)"></script>
    </Head>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
    seoData: {
        type: Object,
        default: () => ({})
    }
})
</script>
