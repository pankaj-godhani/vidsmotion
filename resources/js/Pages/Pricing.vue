<template>
    <Head title="Pricing - Vidsmotion" />
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
                        <Link :href="route('pricing')" class="text-white font-medium text-sm">Pricing</Link>
                        <div v-if="canLogin" class="flex items-center space-x-4">
                            <template v-if="$page.props.auth.user">
                                <!-- User Dropdown -->
                                <div class="relative">
                                    <button
                                        @click="showUserMenu = !showUserMenu"
                                        class="flex items-center space-x-2 text-gray-300 hover:text-white transition-colors"
                                    >
                                        <img
                                            v-if="$page.props.auth.user.avatar_url"
                                            :src="$page.props.auth.user.avatar_url"
                                            :alt="$page.props.auth.user.name"
                                            class="w-8 h-8 rounded-full object-cover border-2 border-gray-600"
                                        />
                                        <div
                                            v-else
                                            class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center border-2 border-gray-600"
                                        >
                                            <span class="text-sm font-medium text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
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
                                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors"
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
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="text-gray-300 hover:text-white transition-colors text-sm"
                                >
                                    Sign In
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-sm font-medium"
                                >
                                    Get Started
                                </Link>
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

                <!-- Mobile Menu -->
                <div v-if="isMenuOpen" class="md:hidden py-4 border-t border-gray-800">
                    <div class="flex flex-col space-y-4">
                        <Link :href="route('welcome')" class="text-gray-300 hover:text-white transition-colors text-sm">Home</Link>
                        <Link :href="route('features')" class="text-gray-300 hover:text-white transition-colors text-sm">Features</Link>
                        <Link :href="route('explore')" class="text-gray-300 hover:text-white transition-colors text-sm">Explore</Link>
                        <Link :href="route('pricing')" class="text-white font-medium text-sm">Pricing</Link>
                        <div v-if="canLogin" class="pt-4 border-t border-gray-800">
                            <template v-if="$page.props.auth.user">
                                <!-- Mobile User Menu -->
                                <div class="space-y-2">
                                    <div class="px-4 py-2 border-b border-gray-800">
                                        <div class="flex items-center space-x-3">
                                            <img
                                                v-if="$page.props.auth.user.avatar_url"
                                                :src="$page.props.auth.user.avatar_url"
                                                :alt="$page.props.auth.user.name"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-600"
                                            />
                                            <div
                                                v-else
                                                class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center border-2 border-gray-600"
                                            >
                                                <span class="text-sm font-medium text-white">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-white">{{ $page.props.auth.user.name }}</p>
                                                <p class="text-xs text-gray-400">{{ $page.props.auth.user.email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <Link
                                        :href="route('dashboard')"
                                        class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                    >
                                        Dashboard
                                    </Link>
                                    <Link
                                        :href="route('user.dashboard')"
                                        class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                    >
                                        My Files
                                    </Link>
                                    <Link
                                        :href="route('my-profile')"
                                        class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
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
                            </template>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="block text-gray-300 hover:text-white transition-colors text-sm py-2"
                                >
                                    Sign In
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="block px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-sm font-medium text-center mt-2"
                                >
                                    Get Started
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="pt-16">
            <!-- Header Section -->
            <div class="py-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                        Choose Your Plan
                    </h1>
                    <p class="text-xl text-gray-300 mb-6 max-w-2xl mx-auto">
                        Unlock the full potential of AI video generation with our flexible pricing plans
                    </p>
                    <div v-if="$page.props.auth.user && !hasActiveSubscription" class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-medium mb-6">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        You can subscribe to any plan below
                    </div>
                </div>
            </div>

            <!-- Active Subscription Info -->
            <div v-if="hasActiveSubscription && activeSubscription" class="pb-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-gradient-to-r from-green-600/20 to-blue-600/20 border border-green-500/30 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ activeSubscription.plan_name }} Plan Active</h3>
                                    <p class="text-gray-300">Your subscription is currently active and will expire on {{ new Date(activeSubscription.subscription_end).toLocaleDateString() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-white">₹{{ activeSubscription.amount }}</p>
                                <p class="text-sm text-gray-400">Paid on {{ new Date(activeSubscription.created_at).toLocaleDateString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Cards -->
            <div class="pb-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Free Plan -->
                        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8 relative transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/20 hover:border-purple-500/50 group cursor-pointer"
                             :class="{
                                 'ring-4 ring-purple-500 ring-opacity-50 border-purple-500 shadow-2xl shadow-purple-500/30 plan-highlighted': highlightedPlan === 'Standard'
                             }">
                            <!-- Active Plan Badge -->
                            <div v-if="activePlanName === 'Standard'" class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-green-600 text-white px-4 py-1 rounded-full text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Active Plan
                                </span>
                            </div>
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-purple-400 transition-colors duration-300">3 days</h3>
                                <p class="text-gray-400 mb-6">3 days trial</p>
                                <div class="mb-8 group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-4xl font-bold text-white group-hover:text-purple-400 transition-colors duration-300">₹50</span>
                                </div>

                                <ul class="space-y-4 mb-8 text-left">
                                    <li class="flex items-center group-hover:translate-x-2 transition-transform duration-300">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 group-hover:text-purple-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300 group-hover:text-white transition-colors duration-300">5 video generations per month</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Up to 5 seconds per video</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Standard quality</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Community support</span>
                                    </li>
                                </ul>

                                <button
                                    v-if="!$page.props.auth.user"
                                    @click="redirectToLoginWithIntent('Standard', 50)"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Subscribe Standard
                                </button>
                                <button
                                    v-else-if="$page.props.auth.user && !hasActiveSubscription"
                                    @click="handleStandardSubscription"
                                    :disabled="isAnyButtonLoading"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="isStandardLoading" class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-black" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                    <span v-else>Subscribe Standard</span>
                                </button>
                                <button
                                    v-else-if="$page.props.auth.user && hasActiveSubscription && activePlanName !== 'Standard'"
                                    @click="checkAndNotifyForDifferentPlan('Standard')"
                                    class="w-full block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-center font-medium"
                                >
                                    {{ getActivePlanButtonText('Standard') }}
                                </button>
                                <Link
                                    v-else-if="$page.props.auth.user && hasActiveSubscription && activePlanName === 'Standard'"
                                    :href="route('dashboard')"
                                    class="w-full block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-center font-medium"
                                >
                                    {{ getActivePlanButtonText('Standard') }}
                                </Link>
                            </div>
                        </div>

                        <!-- Pro Plan -->
                        <div class="bg-gray-900 rounded-2xl border-2 border-white p-8 relative transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-white/30 hover:border-white group cursor-pointer"
                             :class="{
                                 'ring-4 ring-blue-500 ring-opacity-50 border-blue-500 shadow-2xl shadow-blue-500/30 plan-highlighted': highlightedPlan === 'Pro Monthly'
                             }">
                            <!-- Active Plan Badge -->
                            <div v-if="activePlanName === 'Pro Monthly'" class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-green-600 text-white px-4 py-1 rounded-full text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Active Plan
                                </span>
                            </div>
                            <!-- Popular Badge -->
                            <div v-else class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-white text-black px-4 py-1 rounded-full text-sm font-medium">
                                    Most Popular
                                </span>
                            </div>

                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors duration-300">Monthly</h3>
                                <p class="text-gray-400 mb-6">Monthly billing</p>
                                <div class="mb-8 group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-4xl font-bold text-white group-hover:text-blue-400 transition-colors duration-300">₹100</span>
                                </div>

                                <ul class="space-y-4 mb-8 text-left">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">100 video generations per month</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Up to 10 seconds per video</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">High quality output</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Priority processing</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Email support</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Commercial license</span>
                                    </li>
                                </ul>

                                <button
                                    v-if="!$page.props.auth.user"
                                    @click="redirectToLoginWithIntent('Pro Monthly', 100)"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Subscribe Pro Monthly
                                </button>
                                <button
                                    v-else-if="$page.props.auth.user && !hasActiveSubscription"
                                    @click="handleProMonthlySubscription"
                                    :disabled="isAnyButtonLoading"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="isProMonthlyLoading" class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-black" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                    <span v-else>Subscribe Pro Monthly</span>
                                </button>
                                <button
                                    v-else-if="$page.props.auth.user && hasActiveSubscription && activePlanName !== 'Pro Monthly'"
                                    @click="checkAndNotifyForDifferentPlan('Pro Monthly')"
                                    class="w-full block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-center font-medium"
                                >
                                    {{ getActivePlanButtonText('Pro Monthly') }}
                                </button>
                                <Link
                                    v-else-if="$page.props.auth.user && hasActiveSubscription && activePlanName === 'Pro Monthly'"
                                    :href="route('dashboard')"
                                    class="w-full block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-center font-medium"
                                >
                                    {{ getActivePlanButtonText('Pro Monthly') }}
                                </Link>
                            </div>
                        </div>

                        <!-- Enterprise Plan -->
                        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8 relative transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-cyan-500/20 hover:border-cyan-500/50 group cursor-pointer"
                             :class="{
                                 'ring-4 ring-cyan-500 ring-opacity-50 border-cyan-500 shadow-2xl shadow-cyan-500/30 plan-highlighted': highlightedPlan === 'Premier Yearly'
                             }">
                            <!-- Active Plan Badge -->
                            <div v-if="activePlanName === 'Premier Yearly'" class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-green-600 text-white px-4 py-1 rounded-full text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Active Plan
                                </span>
                            </div>
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-cyan-400 transition-colors duration-300">Yearly</h3>
                                <p class="text-gray-400 mb-6">Yearly billing</p>
                                <div class="mb-8 group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-4xl font-bold text-white group-hover:text-cyan-400 transition-colors duration-300">₹1100</span>
                                </div>

                                <ul class="space-y-4 mb-8 text-left">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Unlimited video generations</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Up to 15 seconds per video</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Ultra high quality</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Instant processing</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">24/7 priority support</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Team collaboration</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-300">Custom integrations</span>
                                    </li>
                                </ul>

                                <button
                                    v-if="!$page.props.auth.user"
                                    @click="redirectToLoginWithIntent('Premier Yearly', 1100)"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium"
                                >
                                    Subscribe Premier Yearly
                                </button>
                                <button
                                    v-else-if="$page.props.auth.user && !hasActiveSubscription"
                                    @click="handlePremierYearlySubscription"
                                    :disabled="isAnyButtonLoading"
                                    class="w-full block px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-100 transition-all text-center font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="isPremierYearlyLoading" class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-black" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                    <span v-else>Subscribe Premier Yearly</span>
                                </button>
                                <button
                                    v-else-if="$page.props.auth.user && hasActiveSubscription && activePlanName !== 'Premier Yearly'"
                                    @click="checkAndNotifyForDifferentPlan('Premier Yearly')"
                                    class="w-full block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-center font-medium"
                                >
                                    {{ getActivePlanButtonText('Premier Yearly') }}
                                </button>
                                <Link
                                    v-else-if="$page.props.auth.user && hasActiveSubscription && activePlanName === 'Premier Yearly'"
                                    :href="route('dashboard')"
                                    class="w-full block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-center font-medium"
                                >
                                    {{ getActivePlanButtonText('Premier Yearly') }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-900/50">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-white mb-6">Frequently Asked Questions</h2>
                        <p class="text-xl text-gray-300">Everything you need to know about our pricing</p>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-gray-800 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-3">Can I change my plan anytime?</h3>
                            <p class="text-gray-300">Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.</p>
                        </div>

                        <div class="bg-gray-800 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-3">What happens if I exceed my monthly limit?</h3>
                            <p class="text-gray-300">If you exceed your monthly video generation limit, you can purchase additional credits or upgrade to a higher plan.</p>
                        </div>

                        <div class="bg-gray-800 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-3">Do you offer refunds?</h3>
                            <p class="text-gray-300">We offer a 30-day money-back guarantee for all paid plans. Contact our support team if you're not satisfied.</p>
                        </div>

                        <div class="bg-gray-800 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-3">Is there a free trial for paid plans?</h3>
                            <p class="text-gray-300">Yes, we offer a 7-day free trial for our Pro plan. No credit card required to start.</p>
                        </div>
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
/* Pricing card hover effects */
.group:hover {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.05) 0%, rgba(59, 130, 246, 0.05) 100%);
}

/* Highlighted plan animation */
@keyframes pulse-highlight {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.7);
    }
    50% {
        transform: scale(1.02);
        box-shadow: 0 0 0 10px rgba(139, 92, 246, 0);
    }
}

.plan-highlighted {
    animation: pulse-highlight 2s infinite;
}

/* Smooth animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

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
</style>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import Footer from '@/Components/Footer.vue';
import NotificationManager from '@/Components/NotificationManager.vue';
import { loadRazorpayScript, createRazorpayOrder, openRazorpayPayment, handlePaymentSuccess } from '@/utils/razorpay.js';
import { showInfo, showSuccess, showError } from '@/utils/notifications';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    hasActiveSubscription: {
        type: Boolean,
        default: false,
    },
    activeSubscription: {
        type: Object,
        default: null,
    },
});

// Watch for authentication changes
watch(() => props.canLogin, (newValue, oldValue) => {
    if (newValue && !oldValue) {
        // User just became authenticated, check for payment intent
        setTimeout(() => {
            checkPaymentIntent();
        }, 500);
    }
}, { immediate: true });

// Show notification for users with deactivated subscriptions
onMounted(() => {
    if (props.hasActiveSubscription === false && props.canLogin) {
        // User is logged in but has no active subscription
        setTimeout(() => {
            showInfo(
                'You can subscribe to any plan below to get started with premium features.',
                'Ready to Subscribe',
                5000
            );
        }, 1000);
    }
});

const isMenuOpen = ref(false);
const showUserMenu = ref(false);
const highlightedPlan = ref(null);

// Separate loading states for each subscription button
const isStandardLoading = ref(false);
const isProMonthlyLoading = ref(false);
const isPremierYearlyLoading = ref(false);

// Computed property to check if any button is loading
const isAnyButtonLoading = computed(() => {
    return isStandardLoading.value || isProMonthlyLoading.value || isPremierYearlyLoading.value;
});

// Determine which plan is currently active
const activePlanName = computed(() => {
    if (!props.activeSubscription) return null;

    const planName = props.activeSubscription.plan_name;
    switch (planName) {
        case 'Standard':
            return 'Standard';
        case 'Pro Monthly':
            return 'Pro Monthly';
        case 'Premier Yearly':
            return 'Premier Yearly';
        default:
            return null;
    }
});

// Generate dynamic button text based on active plan
const getActivePlanButtonText = (planType) => {
    if (!props.hasActiveSubscription || !props.activeSubscription) {
        return `Subscribe ${planType}`;
    }

    const activePlan = props.activeSubscription.plan_name;
    switch (planType) {
        case 'Standard':
            return activePlan === 'Standard' ? 'Your Active Plan' : 'Subscribe Standard';
        case 'Pro Monthly':
            return activePlan === 'Pro Monthly' ? 'Your Active Plan' : 'Subscribe Pro Monthly';
        case 'Premier Yearly':
            return activePlan === 'Premier Yearly' ? 'Your Active Plan' : 'Subscribe Premier Yearly';
        default:
            return `Subscribe ${planType}`;
    }
};

// Check if user is trying to purchase a different plan and show notification
const checkAndNotifyForDifferentPlan = (planType) => {
    console.log('Checking plan:', planType, 'Has active subscription:', props.hasActiveSubscription, 'Active plan:', props.activeSubscription?.plan_name);

    // Test notification system first
    if (window.$notify) {
        console.log('Notification system is available');
    } else {
        console.error('Notification system is NOT available - window.$notify is undefined');
        // Fallback to alert for now
        alert(`You currently have an active ${props.activeSubscription?.plan_name} subscription. Please deactivate your current plan first from your profile page, then you can purchase the ${planType} plan.`);
        return true;
    }

    if (props.hasActiveSubscription && props.activeSubscription) {
        const activePlan = props.activeSubscription.plan_name;
        if (activePlan !== planType) {
            console.log('Showing deactivate notification for plan:', planType, 'Current active plan:', activePlan);
            showError(
                `You currently have an active ${activePlan} subscription. Please deactivate your current plan first from your profile page, then you can purchase the ${planType} plan.`,
                'Active Subscription Found',
                8000
            );
            return true; // Prevent further action
        }
    }
    return false; // Allow action to proceed
};

const logout = () => {
    router.post(route('logout'));
};

// Razorpay payment handling - now using separate loading states for each button

// Redirect to login with payment intent
const redirectToLoginWithIntent = (planName, amount) => {
    // Store payment intent in sessionStorage
    sessionStorage.setItem('payment_intent', JSON.stringify({
        plan: planName,
        amount: amount,
        timestamp: new Date().toISOString()
    }));

    // Store intended URL in sessionStorage as well
    sessionStorage.setItem('intended_url', route('pricing'));

    // Redirect to login page
    router.visit(route('login'));
};

// Check for payment intent after login
const checkPaymentIntent = () => {
    const paymentIntent = sessionStorage.getItem('payment_intent');
    const intendedUrl = sessionStorage.getItem('intended_url');

    if (paymentIntent) {
        try {
            const intent = JSON.parse(paymentIntent);
            console.log('Payment intent found:', intent);

            // Clear the intent from storage
            sessionStorage.removeItem('payment_intent');
            sessionStorage.removeItem('intended_url');

            // Show a welcome message only (no automatic payment)
            setTimeout(() => {
                showInfo(
                    `Welcome back! You can now proceed with your ${intent.plan} subscription (₹${intent.amount}). The plan is highlighted below - click the subscription button to continue.`,
                    'Welcome Back!',
                    8000
                );

                // Highlight the intended plan
                highlightedPlan.value = intent.plan;

                // Remove highlight after 10 seconds
                setTimeout(() => {
                    highlightedPlan.value = null;
                }, 10000);
            }, 1000);
        } catch (error) {
            console.error('Error parsing payment intent:', error);
            sessionStorage.removeItem('payment_intent');
            sessionStorage.removeItem('intended_url');
        }
    } else if (intendedUrl && intendedUrl !== window.location.pathname) {
        // If there's an intended URL and we're not already there, redirect to it
        console.log('Redirecting to intended URL:', intendedUrl);
        sessionStorage.removeItem('intended_url');
        router.visit(intendedUrl);
    }
};

// Handle payment based on plan name
const handlePaymentByPlan = async (planName, amount) => {
    switch (planName) {
        case 'Standard':
            await handleStandardSubscription();
            break;
        case 'Pro Monthly':
            await handleProMonthlySubscription();
            break;
        case 'Premier Yearly':
            await handlePremierYearlySubscription();
            break;
        default:
            console.error('Unknown plan:', planName);
    }
};

const handleStandardSubscription = async () => {
    try {
        // Check if user is trying to purchase a different plan
        if (checkAndNotifyForDifferentPlan('Standard')) {
            return; // Stop execution if notification was shown
        }

        isStandardLoading.value = true;

        console.log('Starting payment process...');

        // Load Razorpay script
        await loadRazorpayScript();
        console.log('Razorpay script loaded');

        // Create order for Standard plan (₹50 for 3 days)
        const order = await createRazorpayOrder(50, 'Standard');

        // Open Razorpay payment modal
        openRazorpayPayment(
            order,
            'Standard Plan (3 days)',
            async (response) => {
                try {
                    // Payment successful - verify with backend
                    const result = await handlePaymentSuccess(response, 'Standard');

                    if (result.is_guest) {
                        showSuccess('Payment successful! Please create an account to access your Standard plan.', 'Payment Completed');
                        router.visit(route('register'));
                    } else {
                        showSuccess('Payment successful! Your Standard plan is now active.', 'Subscription Activated');
                        router.visit(route('video-generator'));
                    }
                } catch (error) {
                    console.error('Payment verification failed:', error);
                    showError(`Payment verification failed: ${error.message}`, 'Verification Error');
                }
            },
            (error) => {
                // Payment failed
                console.error('Payment failed:', error);
                showError(`Payment failed: ${error.description || error.message || 'Unknown error'}`, 'Payment Failed');
            }
        );
    } catch (error) {
        console.error('Error processing payment:', error);
        showError(`Error: ${error.message || 'An error occurred. Please try again.'}`, 'Payment Error');
    } finally {
        isStandardLoading.value = false;
    }
};

const handleProMonthlySubscription = async () => {
    try {
        // Check if user is trying to purchase a different plan
        if (checkAndNotifyForDifferentPlan('Pro Monthly')) {
            return; // Stop execution if notification was shown
        }

        isProMonthlyLoading.value = true;

        console.log('Starting Pro Monthly payment process...');

        // Load Razorpay script
        await loadRazorpayScript();
        console.log('Razorpay script loaded');

        // Create order for Pro Monthly plan (₹100)
        const order = await createRazorpayOrder(100, 'Pro Monthly');

        // Open Razorpay payment modal
        openRazorpayPayment(
            order,
            'Pro Monthly Plan',
            async (response) => {
                try {
                    // Payment successful - verify with backend
                    const result = await handlePaymentSuccess(response, 'Pro Monthly');

                    if (result.is_guest) {
                        showSuccess('Payment successful! Please create an account to access your Pro Monthly plan.', 'Payment Completed');
                        router.visit(route('register'));
                    } else {
                        showSuccess('Payment successful! Your Pro Monthly plan is now active.', 'Subscription Activated');
                        router.visit(route('video-generator'));
                    }
                } catch (error) {
                    console.error('Payment verification failed:', error);
                    showError(`Payment verification failed: ${error.message}`, 'Verification Error');
                }
            },
            (error) => {
                // Payment failed
                console.error('Payment failed:', error);
                showError(`Payment failed: ${error.description || error.message || 'Unknown error'}`, 'Payment Failed');
            }
        );
    } catch (error) {
        console.error('Error processing payment:', error);
        showError(`Error: ${error.message || 'An error occurred. Please try again.'}`, 'Payment Error');
    } finally {
        isProMonthlyLoading.value = false;
    }
};

const handlePremierYearlySubscription = async () => {
    try {
        // Check if user is trying to purchase a different plan
        if (checkAndNotifyForDifferentPlan('Premier Yearly')) {
            return; // Stop execution if notification was shown
        }

        isPremierYearlyLoading.value = true;

        console.log('Starting Premier Yearly payment process...');

        // Load Razorpay script
        await loadRazorpayScript();
        console.log('Razorpay script loaded');

        // Create order for Premier Yearly plan (₹1100)
        const order = await createRazorpayOrder(1100, 'Premier Yearly');

        // Open Razorpay payment modal
        openRazorpayPayment(
            order,
            'Premier Yearly Plan',
            async (response) => {
                try {
                    // Payment successful - verify with backend
                    const result = await handlePaymentSuccess(response, 'Premier Yearly');

                    if (result.is_guest) {
                        showSuccess('Payment successful! Please create an account to access your Premier Yearly plan.', 'Payment Completed');
                        router.visit(route('register'));
                    } else {
                        showSuccess('Payment successful! Your Premier Yearly plan is now active.', 'Subscription Activated');
                router.visit(route('video-generator'));
                    }
                } catch (error) {
                    console.error('Payment verification failed:', error);
                    showError(`Payment verification failed: ${error.message}`, 'Verification Error');
                }
            },
            (error) => {
                // Payment failed
                console.error('Payment failed:', error);
                showError(`Payment failed: ${error.description || error.message || 'Unknown error'}`, 'Payment Failed');
            }
        );
    } catch (error) {
        console.error('Error processing payment:', error);
        showError(`Error: ${error.message || 'An error occurred. Please try again.'}`, 'Payment Error');
    } finally {
        isPremierYearlyLoading.value = false;
    }
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

    // Check for payment intent if user is authenticated
    // We'll check this in a different way since we can't access props in onMounted
    setTimeout(() => {
        checkPaymentIntent();
    }, 100);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdowns);
});
</script>
