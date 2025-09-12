<template>
    <Head title="My Profile - Vidsmotion" />
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
                        <Link :href="route('features')" class="text-gray-300 hover:text-white transition-colors text-sm">Features</Link>
                        <Link :href="route('explore')" class="text-gray-300 hover:text-white transition-colors text-sm">Explore</Link>
                        <Link :href="route('pricing')" class="text-gray-300 hover:text-white transition-colors text-sm">Pricing</Link>
                        <div v-if="canLogin" class="flex items-center space-x-4">
                            <template v-if="$page.props.auth.user">
                                <!-- User Dropdown -->
                                <div class="relative">
                                    <button
                                        @click="showUserMenu = !showUserMenu"
                                        class="flex items-center space-x-2 text-gray-300 hover:text-white transition-colors"
                                    >
                                        <div class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center">
                                            <img
                                                v-if="$page.props.auth.user.avatar_url"
                                                :src="$page.props.auth.user.avatar_url"
                                                :alt="$page.props.auth.user.name"
                                                class="w-full h-full object-cover"
                                            />
                                            <div v-else class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-gray-900 border border-gray-800 rounded-lg shadow-lg z-50">
                                        <div class="py-1">
                                            <div class="px-4 py-2 border-b border-gray-800">
                                                <p class="text-sm font-medium text-white">{{ $page.props.auth.user.name }}</p>
                                                <p class="text-xs text-gray-400">{{ $page.props.auth.user.email }}</p>
                                            </div>
                                            <Link
                                                :href="route('dashboard')"
                                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                                @click="showUserMenu = false"
                                            >
                                                Dashboard
                                            </Link>
                                            <Link
                                                :href="route('video-generator')"
                                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                                @click="showUserMenu = false"
                                            >
                                                Video Generator
                                            </Link>
                                            <Link
                                                :href="route('user.dashboard')"
                                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                                @click="showUserMenu = false"
                                            >
                                                My Files
                                            </Link>
                                            <Link
                                                :href="route('my-profile')"
                                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors bg-gray-800"
                                                @click="showUserMenu = false"
                                            >
                                                My Profile
                                            </Link>
                                            <div class="border-t border-gray-800"></div>
                                            <button
                                                @click="logout"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
                                            >
                                                Sign Out
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button
                        @click="isMenuOpen = !isMenuOpen"
                        class="md:hidden text-white"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Dropdown -->
        <div v-if="isMenuOpen" class="md:hidden fixed inset-0 z-40 bg-black/50 backdrop-blur-sm" @click="isMenuOpen = false">
            <div class="fixed top-16 left-0 right-0 bg-gray-900 border-b border-gray-800 shadow-lg" @click.stop>
                <div class="px-4 py-6">
                    <!-- User Info -->
                    <div class="flex items-center space-x-4 mb-6 pb-6 border-b border-gray-800">
                        <div class="w-12 h-12 rounded-full overflow-hidden flex items-center justify-center">
                            <img
                                v-if="$page.props.auth.user.avatar_url"
                                :src="$page.props.auth.user.avatar_url"
                                :alt="$page.props.auth.user.name"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center">
                                <span class="text-lg font-medium text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-gray-400">{{ $page.props.auth.user.email }}</p>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="space-y-2">
                        <Link
                            :href="route('dashboard')"
                            class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                            @click="isMenuOpen = false"
                        >
                            Dashboard
                        </Link>
                        <Link
                            :href="route('video-generator')"
                            class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                            @click="isMenuOpen = false"
                        >
                            Video Generator
                        </Link>
                        <Link
                            :href="route('user.dashboard')"
                            class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                            @click="isMenuOpen = false"
                        >
                            My Files
                        </Link>
                        <Link
                            :href="route('my-profile')"
                            class="block text-gray-300 hover:text-white transition-colors text-sm py-2 bg-gray-800"
                            @click="isMenuOpen = false"
                        >
                            My Profile
                        </Link>
                        <button
                            @click="logout"
                            class="block w-full text-left text-gray-300 hover:text-white transition-colors text-sm py-2"
                        >
                            Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="pt-16">
            <!-- Hero Section with AI Video Background -->
            <div class="relative overflow-hidden">
                <!-- AI Video Background -->
                <div class="absolute inset-0">
                    <!-- AI-generated video background -->
                    <video
                        class="absolute inset-0 w-full h-full object-cover opacity-20"
                        autoplay
                        muted
                        loop
                        playsinline
                        poster="https://images.unsplash.com/photo-1677442136019-21780ecad995?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                    >
                        <source src="https://cdn.pixabay.com/vimeo/123456789/ai-generated-123456.mp4" type="video/mp4">
                        <!-- Fallback to AI-generated image if video fails -->
                    </video>

                    <!-- Primary AI Background Image -->
                    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20"
                         style="background-image: url('https://images.unsplash.com/photo-1677442136019-21780ecad995?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                    </div>

                    <!-- Alternative AI Background Images -->
                    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-15"
                         style="background-image: url('https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                    </div>

                    <!-- AI Neural Network Pattern -->
                    <div class="absolute inset-0 opacity-5">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid slice">
                            <defs>
                                <pattern id="neural-network" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <circle cx="10" cy="10" r="1" fill="white" opacity="0.3"/>
                                    <line x1="10" y1="10" x2="30" y2="10" stroke="white" stroke-width="0.5" opacity="0.2"/>
                                    <line x1="10" y1="10" x2="10" y2="30" stroke="white" stroke-width="0.5" opacity="0.2"/>
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#neural-network)"/>
                        </svg>
                    </div>

                    <!-- AI Pattern Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-900/40 via-blue-900/40 to-indigo-900/40"></div>

                    <!-- Animated AI Particles -->
                    <div class="absolute inset-0">
                        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-500/15 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob"></div>
                        <div class="absolute top-0 -right-4 w-72 h-72 bg-cyan-500/15 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-2000"></div>
                        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500/15 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-blob animation-delay-4000"></div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-500/10 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-blob animation-delay-1000"></div>
                    </div>

                    <!-- AI Grid Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 20px 20px;"></div>
                    </div>
                </div>

                <!-- Header Section -->
                <div class="relative py-20 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-6xl mx-auto">
                        <div class="text-center mb-16">
                            <!-- Enhanced Profile Icon with Glow Effect -->
                            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-purple-500 via-blue-500 to-indigo-600 rounded-3xl mb-8 shadow-2xl relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 via-blue-500 to-indigo-600 rounded-3xl blur-lg opacity-50 animate-pulse"></div>
                                <svg class="w-12 h-12 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>

                            <!-- Enhanced Title with Better Contrast -->
                            <h1 class="text-6xl md:text-7xl font-bold mb-8 relative">
                                <span class="bg-gradient-to-r from-white via-purple-100 to-blue-100 bg-clip-text text-transparent drop-shadow-2xl">
                                    My Profile
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 via-blue-500/20 to-indigo-500/20 blur-3xl -z-10"></div>
                            </h1>

                            <!-- Enhanced Subtitle with Background -->
                            <div class="inline-block bg-black/30 backdrop-blur-sm rounded-2xl px-8 py-4 border border-white/10">
                                <p class="text-xl md:text-2xl text-white max-w-3xl mx-auto leading-relaxed font-medium">
                                    Welcome back! Manage your account, view subscription details, and explore your
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400 font-semibold">
                                        AI journey
                                    </span>
                                    with Vidsmotion.
                                </p>
                            </div>

                            <!-- AI Status Indicator -->
                            <div class="mt-8 flex justify-center">
                                <div class="inline-flex items-center px-6 py-3 bg-green-500/20 border border-green-500/30 rounded-full backdrop-blur-sm">
                                    <div class="w-3 h-3 bg-green-400 rounded-full mr-3 animate-pulse"></div>
                                    <span class="text-green-300 font-medium">AI-Powered Profile Management</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="pb-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-6xl mx-auto">
                    <!-- User Profile Card -->
                    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-3xl border border-gray-700/50 p-8 mb-8 shadow-2xl backdrop-blur-sm">
                        <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-6 lg:space-y-0 lg:space-x-8">
                            <!-- Avatar Section -->
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-3xl overflow-hidden shadow-2xl relative">
                                    <img
                                        v-if="$page.props.auth.user.avatar_url"
                                        :src="$page.props.auth.user.avatar_url"
                                        :alt="$page.props.auth.user.name"
                                        class="w-full h-full object-cover"
                                    />
                                    <div v-else class="w-full h-full bg-gradient-to-br from-purple-500 via-blue-500 to-indigo-600 flex items-center justify-center">
                                        <span class="text-4xl font-bold text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                    </div>

                                    <!-- Upload Overlay -->
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <button
                                            @click="showAvatarUpload = true"
                                            class="bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl px-4 py-2 text-white text-sm font-medium hover:bg-white/30 transition-all duration-300"
                                        >
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Change Photo
                                        </button>
                                    </div>
                                </div>

                                <!-- Status Indicator -->
                                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-gray-900 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="flex-1 text-center lg:text-left">
                                <div class="flex items-center justify-center lg:justify-start mb-2">
                                    <!-- Avatar next to name -->
                                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4 flex-shrink-0">
                                        <img
                                            v-if="$page.props.auth.user.avatar_url"
                                            :src="$page.props.auth.user.avatar_url"
                                            :alt="$page.props.auth.user.name"
                                            class="w-full h-full object-cover"
                                        />
                                        <div v-else class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center">
                                            <span class="text-lg font-bold text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                        </div>
                                    </div>
                                    <h2 class="text-3xl font-bold text-white">{{ $page.props.auth.user.name }}</h2>
                                </div>
                                <p class="text-lg text-gray-300 mb-4">{{ $page.props.auth.user.email }}</p>
                                <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                                    <span class="inline-flex items-center px-4 py-2 bg-green-500/20 text-green-400 rounded-full text-sm font-medium border border-green-500/30">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Active Member
                                    </span>
                                    <span class="inline-flex items-center px-4 py-2 bg-blue-500/20 text-blue-400 rounded-full text-sm font-medium border border-blue-500/30">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Since {{ formatDate($page.props.auth.user.created_at) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- User Information -->
                        <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/80 rounded-3xl border border-gray-700/50 p-8 backdrop-blur-sm hover:border-purple-500/50 transition-all duration-300 group">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-white">Personal Information</h2>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="bg-gray-800/50 rounded-2xl p-4 border border-gray-700/50">
                                        <label class="block text-sm font-medium text-gray-400 mb-2">Full Name</label>
                                        <p class="text-white font-medium">{{ $page.props.auth.user.name }}</p>
                                    </div>
                                    <div class="bg-gray-800/50 rounded-2xl p-4 border border-gray-700/50">
                                        <label class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                                        <p class="text-white font-medium">{{ $page.props.auth.user.email }}</p>
                                    </div>
                                    <div class="bg-gray-800/50 rounded-2xl p-4 border border-gray-700/50">
                                        <label class="block text-sm font-medium text-gray-400 mb-2">Member Since</label>
                                        <p class="text-white font-medium">{{ formatDate($page.props.auth.user.created_at) }}</p>
                                    </div>
                                    <div class="bg-gray-800/50 rounded-2xl p-4 border border-gray-700/50">
                                        <label class="block text-sm font-medium text-gray-400 mb-2">Account Status</label>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                            Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Information -->
                        <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/80 rounded-3xl border border-gray-700/50 p-8 backdrop-blur-sm hover:border-purple-500/50 transition-all duration-300 group">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-white">Subscription Details</h2>
                            </div>

                            <div v-if="activeSubscription" class="space-y-6">
                                <div class="bg-gradient-to-r from-gray-800/80 to-gray-700/80 rounded-2xl p-6 border border-gray-600/50 backdrop-blur-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-bold text-white">{{ activeSubscription.plan_name }}</h3>
                                                <p class="text-gray-400">₹{{ activeSubscription.amount }} - {{ activeSubscription.plan_type }}</p>
                                            </div>
                                        </div>
                                        <span v-if="activeSubscription.is_active" class="inline-flex items-center px-4 py-2 bg-green-500/20 text-green-400 rounded-full text-sm font-medium border border-green-500/30">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                            Active
                                        </span>
                                        <span v-else class="inline-flex items-center px-4 py-2 bg-red-500/20 text-red-400 rounded-full text-sm font-medium border border-red-500/30">
                                            <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                            Deactivated
                                        </span>
                                    </div>

                                    <div class="bg-gray-900/50 rounded-xl p-4 mb-4">
                                        <p class="text-sm text-gray-400 mb-1">
                                            <span v-if="activeSubscription.is_active">Expires on</span>
                                            <span v-else>Deactivated on</span>
                                        </p>
                                        <p class="text-white font-medium">{{ formatDate(activeSubscription.subscription_end) }}</p>
                                    </div>

                                    <button
                                        v-if="activeSubscription.is_active"
                                        @click="showDeactivateModal = true"
                                        class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-3 rounded-xl transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-red-500/25 transform hover:scale-105"
                                    >
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Deactivate Subscription
                                    </button>

                                    <div v-else class="text-center py-4">
                                        <div class="bg-gray-900/50 rounded-xl p-4 mb-4">
                                            <svg class="w-12 h-12 mx-auto text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-gray-400 text-sm">Your subscription has been deactivated</p>
                                        </div>
                                        <Link
                                            :href="route('pricing')"
                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white rounded-xl transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-purple-500/25 transform hover:scale-105"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            Subscribe Again
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="text-center py-8">
                                <div class="bg-gray-800/50 rounded-2xl p-8 border border-gray-700/50">
                                    <div class="w-16 h-16 bg-gradient-to-r from-gray-600 to-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-white mb-2">No Active Subscription</h3>
                                    <p class="text-gray-400 mb-6">Unlock premium AI features with our subscription plans</p>
                                    <Link
                                        :href="route('pricing')"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white rounded-xl transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-purple-500/25 transform hover:scale-105"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        View Plans
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div class="mt-8 bg-gradient-to-br from-gray-900/80 to-gray-800/80 rounded-3xl border border-gray-700/50 p-8 backdrop-blur-sm hover:border-green-500/50 transition-all duration-300 group">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Payment History</h2>
                        </div>

                        <div v-if="payments.length > 0" class="space-y-4">
                            <div
                                v-for="payment in payments"
                                :key="payment.id"
                                class="bg-gradient-to-r from-gray-800/80 to-gray-700/80 rounded-2xl p-6 border border-gray-600/50 backdrop-blur-sm hover:border-green-500/30 transition-all duration-300 group"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-white">{{ payment.plan_name }}</h3>
                                            <p class="text-sm text-gray-400 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ formatDate(payment.payment_date) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xl font-bold text-white">₹{{ payment.amount }}</p>
                                        <span class="inline-flex items-center px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium border border-green-500/30">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                            {{ payment.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <div class="bg-gray-800/50 rounded-2xl p-8 border border-gray-700/50">
                                <div class="w-16 h-16 bg-gradient-to-r from-gray-600 to-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white mb-2">No Payment History</h3>
                                <p class="text-gray-400 mb-6">Start your AI journey by subscribing to one of our plans</p>
                                <Link
                                    :href="route('pricing')"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-xl transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-green-500/25 transform hover:scale-105"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    View Plans
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Section -->
                    <div class="mt-8 bg-gradient-to-br from-gray-900/80 to-gray-800/80 rounded-3xl border border-gray-700/50 p-8 backdrop-blur-sm hover:border-purple-500/50 transition-all duration-300 group">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Quick Actions</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <Link
                                :href="route('dashboard')"
                                class="bg-gradient-to-r from-blue-500/20 to-blue-600/20 hover:from-blue-500/30 hover:to-blue-600/30 border border-blue-500/30 hover:border-blue-500/50 text-blue-400 px-6 py-4 rounded-2xl transition-all duration-300 text-center group"
                            >
                                <svg class="w-8 h-8 mx-auto mb-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                <p class="font-medium">Dashboard</p>
                            </Link>

                            <Link
                                :href="route('video-generator')"
                                class="bg-gradient-to-r from-purple-500/20 to-purple-600/20 hover:from-purple-500/30 hover:to-purple-600/30 border border-purple-500/30 hover:border-purple-500/50 text-purple-400 px-6 py-4 rounded-2xl transition-all duration-300 text-center group"
                            >
                                <svg class="w-8 h-8 mx-auto mb-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p class="font-medium">AI Generator</p>
                            </Link>

                            <Link
                                :href="route('user.dashboard')"
                                class="bg-gradient-to-r from-green-500/20 to-green-600/20 hover:from-green-500/30 hover:to-green-600/30 border border-green-500/30 hover:border-green-500/50 text-green-400 px-6 py-4 rounded-2xl transition-all duration-300 text-center group"
                            >
                                <svg class="w-8 h-8 mx-auto mb-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                <p class="font-medium">My Files</p>
                            </Link>

                            <Link
                                :href="route('pricing')"
                                class="bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border border-yellow-500/30 hover:border-yellow-500/50 text-yellow-400 px-6 py-4 rounded-2xl transition-all duration-300 text-center group"
                            >
                                <svg class="w-8 h-8 mx-auto mb-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <p class="font-medium">Pricing</p>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deactivate Subscription Modal -->
        <div v-if="showDeactivateModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-3xl border border-gray-700/50 p-8 max-w-md w-full shadow-2xl backdrop-blur-sm">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-red-500 to-red-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Deactivate Subscription</h3>
                    <p class="text-gray-300 mb-8 leading-relaxed">
                        Are you sure you want to deactivate your subscription? This action cannot be undone and you will lose access to premium AI features.
                    </p>
                    <div class="flex space-x-4">
                        <button
                            @click="showDeactivateModal = false"
                            class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white px-6 py-3 rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-gray-500/25 transform hover:scale-105"
                        >
                            Cancel
                        </button>
                        <button
                            @click="deactivateSubscription"
                            :disabled="isDeactivating"
                            class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 disabled:from-red-400 disabled:to-red-500 text-white px-6 py-3 rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-red-500/25 transform hover:scale-105 disabled:transform-none"
                        >
                            <span v-if="isDeactivating" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Deactivating...
                            </span>
                            <span v-else class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Deactivate
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avatar Upload Modal -->
        <div v-if="showAvatarUpload" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-3xl border border-gray-700/50 p-8 max-w-md w-full shadow-2xl backdrop-blur-sm">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-blue-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Update Profile Picture</h3>
                    <p class="text-gray-300 mb-8 leading-relaxed">
                        Choose a new profile picture. Supported formats: JPG, PNG, GIF (max 2MB)
                    </p>

                    <!-- File Upload Area -->
                    <div class="mb-6">
                        <div
                            @click="triggerFileInput"
                            @dragover.prevent
                            @drop.prevent="handleFileDrop"
                            class="border-2 border-dashed border-gray-600 rounded-2xl p-8 cursor-pointer hover:border-purple-500 transition-colors duration-300"
                            :class="{ 'border-purple-500 bg-purple-500/10': isDragOver }"
                        >
                            <div v-if="!selectedFile" class="text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-300 mb-2">Click to upload or drag and drop</p>
                                <p class="text-sm text-gray-400">JPG, PNG, GIF up to 2MB</p>
                            </div>

                            <div v-else class="text-center">
                                <img :src="previewUrl" alt="Preview" class="w-24 h-24 mx-auto rounded-xl object-cover mb-4">
                                <p class="text-white font-medium">{{ selectedFile.name }}</p>
                                <p class="text-sm text-gray-400">{{ formatFileSize(selectedFile.size) }}</p>
                            </div>
                        </div>

                        <input
                            ref="fileInput"
                            type="file"
                            accept="image/*"
                            @change="handleFileSelect"
                            class="hidden"
                        />
                    </div>

                    <div class="flex space-x-4">
                        <button
                            @click="cancelAvatarUpload"
                            class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white px-6 py-3 rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-gray-500/25 transform hover:scale-105"
                        >
                            Cancel
                        </button>
                        <button
                            @click="uploadAvatar"
                            :disabled="!selectedFile || isUploading"
                            class="flex-1 bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 disabled:from-purple-400 disabled:to-blue-400 text-white px-6 py-3 rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-purple-500/25 transform hover:scale-105 disabled:transform-none"
                        >
                            <span v-if="isUploading" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </span>
                            <span v-else class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Upload
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer />

        <!-- Notification Manager -->
        <NotificationManager />
    </div>
</template>

<style scoped>
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

/* Animated blob backgrounds */
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

/* Gradient text animation */
@keyframes gradient-shift {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradient-shift 3s ease infinite;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Glass morphism effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}

/* Custom shadow effects */
.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Pulse animation for status indicators */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Glow effect for profile icon */
@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
    }
    50% {
        box-shadow: 0 0 40px rgba(139, 92, 246, 0.6), 0 0 60px rgba(59, 130, 246, 0.3);
    }
}

.animate-glow {
    animation: glow 3s ease-in-out infinite;
}

/* Floating animation for background elements */
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

/* Text shimmer effect */
@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

.animate-shimmer {
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
}

/* Drop shadow animation */
.drop-shadow-2xl {
    filter: drop-shadow(0 25px 25px rgba(0, 0, 0, 0.15));
}
</style>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import Footer from '@/Components/Footer.vue';
import NotificationManager from '@/Components/NotificationManager.vue';
import { showSuccess, showError, showWarning, showInfo } from '@/utils/notifications';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    payments: {
        type: Array,
        default: () => []
    },
    activeSubscription: {
        type: Object,
        default: null
    }
});

const isMenuOpen = ref(false);
const showUserMenu = ref(false);
const showDeactivateModal = ref(false);
const isDeactivating = ref(false);

// Avatar upload functionality
const showAvatarUpload = ref(false);
const selectedFile = ref(null);
const previewUrl = ref(null);
const isUploading = ref(false);
const isDragOver = ref(false);
const fileInput = ref(null);


const logout = () => {
    router.post(route('logout'));
};

// Deactivate subscription function
const deactivateSubscription = async () => {
    isDeactivating.value = true;

    try {
        await router.post(route('subscription.deactivate'), {}, {
            onSuccess: () => {
                showDeactivateModal.value = false;
                // Show success notification
                showSuccess('Your subscription has been deactivated successfully.');
                // Refresh the page to update the subscription status
                router.reload();
            },
            onError: (errors) => {
                console.error('Error deactivating subscription:', errors);
                showDeactivateModal.value = false;
                // Show error notification
                showError('Failed to deactivate subscription. Please try again.');
            }
        });
    } catch (error) {
        console.error('Error deactivating subscription:', error);
        showError('Failed to deactivate subscription. Please try again.');
    } finally {
        isDeactivating.value = false;
    }
};

// Avatar upload methods
const triggerFileInput = () => {
    fileInput.value?.click();
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        processFile(file);
    }
};

const handleFileDrop = (event) => {
    isDragOver.value = false;
    const file = event.dataTransfer.files[0];
    if (file) {
        processFile(file);
    }
};

const processFile = (file) => {
    // Validate file type
    if (!file.type.startsWith('image/')) {
        showError('Please select a valid image file.', 'Invalid File Type');
        return;
    }

    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showError('File size must be less than 2MB.', 'File Too Large');
        return;
    }

    selectedFile.value = file;
    previewUrl.value = URL.createObjectURL(file);
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const uploadAvatar = async () => {
    if (!selectedFile.value) return;

    isUploading.value = true;

    try {
        const formData = new FormData();
        formData.append('avatar', selectedFile.value);

        await router.post(route('profile.avatar.update'), formData, {
            forceFormData: true,
            onSuccess: () => {
                showAvatarUpload.value = false;
                showSuccess('Profile picture updated successfully!', 'Success');
                // Clean up preview URL
                if (previewUrl.value) {
                    URL.revokeObjectURL(previewUrl.value);
                }
                resetAvatarUpload();
            },
            onError: (errors) => {
                console.error('Avatar upload error:', errors);
                showError('Failed to update profile picture. Please try again.', 'Upload Error');
            }
        });
    } catch (error) {
        console.error('Avatar upload error:', error);
        showError('Failed to update profile picture. Please try again.', 'Upload Error');
    } finally {
        isUploading.value = false;
    }
};

const cancelAvatarUpload = () => {
    showAvatarUpload.value = false;
    resetAvatarUpload();
};

const resetAvatarUpload = () => {
    selectedFile.value = null;
    previewUrl.value = null;
    isDragOver.value = false;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

// Format date helper
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Close dropdowns when clicking outside
const closeDropdowns = (event) => {
    if (!event.target.closest('.relative')) {
        showUserMenu.value = false;
    }
    if (!event.target.closest('.md\\:hidden')) {
        isMenuOpen.value = false;
    }
};

// Add event listener for clicking outside
onMounted(() => {
    document.addEventListener('click', closeDropdowns);

    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        // Flash messages will be cleared on next page load
    }, 5000);
});

// Clean up preview URL when component unmounts
onUnmounted(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>
