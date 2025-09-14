<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <title inertia>{{ config('app.name', 'VidsMotion - AI Video Generator') }}</title>
        <meta name="description" content="Create stunning AI-generated videos with VidsMotion. Transform your ideas into professional videos using advanced artificial intelligence technology.">
        <meta name="keywords" content="AI video generator, artificial intelligence, video creation, AI videos, video maker, automated video, AI content creation">
        <meta name="author" content="VidsMotion">
        <meta name="robots" content="index, follow">
        <meta name="language" content="English">
        <meta name="revisit-after" content="7 days">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph Meta Tags -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ config('app.name', 'VidsMotion - AI Video Generator') }}">
        <meta property="og:description" content="Create stunning AI-generated videos with VidsMotion. Transform your ideas into professional videos using advanced artificial intelligence technology.">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="VidsMotion">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="VidsMotion - AI Video Generator">
        <meta property="og:locale" content="en_US">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.name', 'VidsMotion - AI Video Generator') }}">
        <meta name="twitter:description" content="Create stunning AI-generated videos with VidsMotion. Transform your ideas into professional videos using advanced artificial intelligence technology.">
        <meta name="twitter:image" content="{{ asset('images/twitter-card.jpg') }}">
        <meta name="twitter:image:alt" content="VidsMotion - AI Video Generator">

        <!-- Additional SEO Meta Tags -->
        <meta name="theme-color" content="#7c3aed">
        <meta name="msapplication-TileColor" content="#7c3aed">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="VidsMotion">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

        <!-- Preconnect to external domains -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="dns-prefetch" href="https://fonts.bunny.net">

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
