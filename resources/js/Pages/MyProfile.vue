<template>
    <Head title="My Profile - Vidsmotion" />
    <div class="min-h-screen bg-black text-white">
        <!-- Header Menu Component -->
        <HeaderMenu current-page="my-profile" :can-login="canLogin" :can-register="canRegister" />

                                <!-- User Dropdown -->

                                    <!-- Dropdown Menu -->




        <!-- Main Content -->
        <div class="pt-16">
            <!-- Header Section -->
            <div class="py-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                        My Profile
                    </h1>
                    <p class="text-xl text-gray-300 mb-6 max-w-2xl mx-auto">
                        Manage your account, view subscription details, and explore your AI journey with Vidsmotion
                    </p>
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
                                    <span v-if="activeSubscription && activeSubscription.is_active" class="inline-flex items-center px-4 py-2 bg-purple-500/20 text-purple-400 rounded-full text-sm font-medium border border-purple-500/30">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Expires {{ formatDate(activeSubscription.subscription_end) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <!-- Personal Information and Change Password in One Row -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Personal Information Section -->
                            <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/80 rounded-3xl border border-gray-700/50 p-8 backdrop-blur-sm hover:border-purple-500/50 transition-all duration-300 group">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-2xl font-bold text-white">Personal Information</h2>
                                    </div>

                                    <!-- Edit Button -->
                                    <button
                                        @click="openEditProfileModal"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white rounded-xl transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-blue-500/25 transform hover:scale-105"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                </div>

                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 gap-6">
                                        <div class="bg-gray-800/50 rounded-2xl p-4 border border-gray-700/50">
                                            <label class="block text-sm font-medium text-gray-400 mb-2">Full Name</label>
                                            <p class="text-white font-medium">{{ $page.props.auth.user.name }}</p>
                                        </div>
                                        <div class="bg-gray-800/50 rounded-2xl p-4 border border-gray-700/50">
                                            <label class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                                            <p class="text-white font-medium">{{ $page.props.auth.user.email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password Section -->
                            <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/80 rounded-3xl border border-gray-700/50 p-8 backdrop-blur-sm hover:border-green-500/50 transition-all duration-300 group">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-2xl font-bold text-white">Change Password</h2>
                                </div>

                                <div class="space-y-6">
                                    <div class="bg-gray-800/50 rounded-2xl p-6 border border-gray-700/50">
                                        <p class="text-gray-300 text-sm mb-4">
                                            Update your password to keep your account secure. Make sure to use a strong password.
                                        </p>
                                        <button
                                            @click="showChangePasswordModal = true"
                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-xl transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-green-500/25 transform hover:scale-105"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                            </svg>
                                            Change Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credits and Subscription in One Row -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Video Credits Section -->
                            <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/80 rounded-3xl border border-gray-700/50 p-8 backdrop-blur-sm hover:border-purple-500/50 transition-all duration-300 group">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-2xl font-bold text-white">Video Credits</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Current Credits -->
                                    <div class="bg-gradient-to-br from-purple-500/10 to-blue-500/10 rounded-2xl p-6 border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-base text-gray-400 mb-1">Current Balance</p>
                                                <p class="text-3xl font-bold text-white">{{ $page.props.auth.user.credits || 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center text-sm text-purple-400">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                            Available for use
                                        </div>
                                    </div>

                                    <!-- Total Used -->
                                    <div class="bg-gradient-to-br from-orange-500/10 to-red-500/10 rounded-2xl p-6 border border-orange-500/20 hover:border-orange-500/40 transition-all duration-300">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-base text-gray-400 mb-1">Total Used</p>
                                                <p class="text-3xl font-bold text-orange-400">{{ $page.props.auth.user.total_credits_used || 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center text-sm text-orange-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            For video generation
                                        </div>
                                    </div>
                                </div>

                                <!-- Credit Usage Info -->
                                <div class="mt-6 p-4 bg-blue-500/10 rounded-xl border border-blue-500/20">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-blue-400 font-medium mb-2">How Credits Work</h4>
                                            <p class="text-sm text-gray-300 mb-3">
                                                Each video generation uses 1 credit. Credits are automatically added when you purchase a subscription plan.
                                            </p>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                                <div class="bg-purple-500/10 rounded-lg p-3 border border-purple-500/20">
                                                    <span class="text-purple-400 font-medium">Standard</span>
                                                    <p class="text-gray-400 mt-1">200 credits</p>
                                                </div>
                                                <div class="bg-blue-500/10 rounded-lg p-3 border border-blue-500/20">
                                                    <span class="text-blue-400 font-medium">Pro Monthly</span>
                                                    <p class="text-gray-400 mt-1">500 credits</p>
                                                </div>
                                                <div class="bg-green-500/10 rounded-lg p-3 border border-green-500/20">
                                                    <span class="text-green-400 font-medium">Pro Yearly</span>
                                                    <p class="text-gray-400 mt-1">7500 credits</p>
                                                </div>
                                            </div>
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
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-sm text-gray-400">
                                                <span v-if="activeSubscription.is_active">Expires on</span>
                                                <span v-else>Deactivated on</span>
                                            </p>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ formatDate(activeSubscription.subscription_end) }}
                                            </div>
                                        </div>
                                        <p class="text-white font-medium text-lg">{{ formatDate(activeSubscription.subscription_end) }}</p>
                                        <div v-if="activeSubscription.is_active" class="mt-2">
                                            <div class="flex items-center text-xs text-gray-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ getDaysRemaining(activeSubscription.subscription_end) }} days remaining
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Auto-Renew Toggle -->
                                    <div v-if="activeSubscription.is_active" class="mb-4">
                                        <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700/50">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-white font-medium">Auto-Renewal</h4>
                                                        <p class="text-sm text-gray-400">Automatically renew your subscription when it expires</p>
                                                    </div>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input
                                                        type="checkbox"
                                                        :checked="activeSubscription.auto_renew"
                                                        @change="toggleAutoRenew"
                                                        class="sr-only peer"
                                                    />
                                                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-purple-500"></div>
                                                </label>
                                            </div>
                                            <div class="mt-3 text-xs text-gray-400">
                                                <span v-if="activeSubscription.auto_renew" class="text-green-400">
                                                    ✓ Your subscription will automatically renew on {{ formatDate(activeSubscription.subscription_end) }}
                                                </span>
                                                <span v-else class="text-orange-400">
                                                    ⚠ Your subscription will expire on {{ formatDate(activeSubscription.subscription_end) }} and will not auto-renew
                                                </span>
                                            </div>
                                        </div>
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

        <!-- Change Password Modal -->
        <div v-if="showChangePasswordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl border border-gray-700/50 p-8 w-full max-w-md shadow-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Change Password</h3>
                    </div>
                    <button
                        @click="closeChangePasswordModal"
                        class="text-gray-400 hover:text-white transition-colors duration-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Form -->
                <form @submit.prevent="changePassword" class="space-y-6">
                    <!-- Error Display -->
                    <div v-if="passwordError" class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-400 text-sm">{{ passwordError }}</p>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                        <input
                            v-model="changePasswordForm.password"
                            type="password"
                            required
                            minlength="8"
                            class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-500/50 transition-all duration-300"
                            placeholder="Enter your new password"
                        />
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
                        <input
                            v-model="changePasswordForm.password_confirmation"
                            type="password"
                            required
                            minlength="8"
                            class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-500/50 transition-all duration-300"
                            placeholder="Confirm your new password"
                        />
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4">
                        <h4 class="text-blue-400 font-medium mb-2">Password Requirements:</h4>
                        <ul class="text-sm text-gray-300 space-y-1">
                            <li>• At least 8 characters long</li>
                            <li>• Mix of letters, numbers, and symbols</li>
                            <li>• Strong and secure password</li>
                        </ul>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex space-x-4 pt-4">
                        <button
                            type="button"
                            @click="closeChangePasswordModal"
                            class="flex-1 px-6 py-3 bg-gray-700/50 hover:bg-gray-700 text-white rounded-xl transition-all duration-300 font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="isChangingPassword"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 disabled:from-gray-600 disabled:to-gray-700 text-white rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-green-500/25 transform hover:scale-105 disabled:transform-none disabled:shadow-none"
                        >
                            <span v-if="isChangingPassword" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating...
                            </span>
                            <span v-else>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div v-if="showEditProfileModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl border border-gray-700/50 p-8 w-full max-w-md shadow-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Edit Profile</h3>
                    </div>
                    <button
                        @click="closeEditProfileModal"
                        class="text-gray-400 hover:text-white transition-colors duration-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Form -->
                <form @submit.prevent="updateProfile" class="space-y-6">
                    <!-- Error Display -->
                    <div v-if="profileError" class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-400 text-sm">{{ profileError }}</p>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                        <input
                            v-model="editProfileForm.name"
                            type="text"
                            required
                            class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-300"
                            placeholder="Enter your full name"
                        />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                        <input
                            v-model="editProfileForm.email"
                            type="email"
                            required
                            class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-300"
                            placeholder="Enter your email address"
                        />
                    </div>

                    <!-- Information Note -->
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4">
                        <h4 class="text-blue-400 font-medium mb-2">Important:</h4>
                        <ul class="text-sm text-gray-300 space-y-1">
                            <li>• Your name will be displayed on your profile</li>
                            <li>• Email address is used for account notifications</li>
                            <li>• Changes will be saved immediately</li>
                        </ul>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex space-x-4 pt-4">
                        <button
                            type="button"
                            @click="closeEditProfileModal"
                            class="flex-1 px-6 py-3 bg-gray-700/50 hover:bg-gray-700 text-white rounded-xl transition-all duration-300 font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="isUpdatingProfile"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 disabled:from-gray-600 disabled:to-gray-700 text-white rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 disabled:transform-none disabled:shadow-none"
                        >
                            <span v-if="isUpdatingProfile" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating...
                            </span>
                            <span v-else>Update Profile</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import Footer from '@/Components/Footer.vue';
import HeaderMenu from '@/Components/HeaderMenu.vue';
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

// Get page data
const page = usePage();

// HeaderMenu component handles all navigation logic
const showDeactivateModal = ref(false);
const isDeactivating = ref(false);

// Edit profile functionality
const showEditProfileModal = ref(false);
const isUpdatingProfile = ref(false);
const profileError = ref('');
const editProfileForm = ref({
    name: '',
    email: ''
});

// Change password functionality
const showChangePasswordModal = ref(false);
const isChangingPassword = ref(false);
const passwordError = ref('');
const changePasswordForm = ref({
    password: '',
    password_confirmation: ''
});

// Avatar upload functionality
const showAvatarUpload = ref(false);
const selectedFile = ref(null);
const previewUrl = ref(null);
const isUploading = ref(false);
const isDragOver = ref(false);
const fileInput = ref(null);



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

// Change password function
const changePassword = async () => {
    // Clear any previous errors
    passwordError.value = '';

    // Client-side validation
    if (changePasswordForm.value.password !== changePasswordForm.value.password_confirmation) {
        passwordError.value = 'Passwords do not match. Please make sure both password fields are identical.';
        return;
    }

    if (changePasswordForm.value.password.length < 8) {
        passwordError.value = 'Password must be at least 8 characters long.';
        return;
    }

    isChangingPassword.value = true;

    try {
        await router.put(route('password.update'), changePasswordForm.value, {
            onSuccess: () => {
                showChangePasswordModal.value = false;
                // Reset form
                changePasswordForm.value = {
                    password: '',
                    password_confirmation: ''
                };
                // Show success notification
                showSuccess('Your password has been updated successfully.');
            },
            onError: (errors) => {
                console.error('Error changing password:', errors);
                // Show specific error messages in popup
                if (errors.password) {
                    passwordError.value = 'Password error: ' + errors.password[0];
                } else if (errors.password_confirmation) {
                    passwordError.value = 'Password confirmation error: ' + errors.password_confirmation[0];
                } else {
                    passwordError.value = 'Failed to update password. Please check your password requirements and try again.';
                }
            }
        });
    } catch (error) {
        console.error('Error changing password:', error);
        passwordError.value = 'Failed to update password. Please try again.';
    } finally {
        isChangingPassword.value = false;
    }
};

// Close change password modal
const closeChangePasswordModal = () => {
    showChangePasswordModal.value = false;
    // Clear error
    passwordError.value = '';
    // Reset form
    changePasswordForm.value = {
        password: '',
        password_confirmation: ''
    };
};

// Edit profile functions
const openEditProfileModal = () => {
    // Initialize form with current user data
    editProfileForm.value = {
        name: page.props.auth.user.name,
        email: page.props.auth.user.email
    };
    profileError.value = '';
    showEditProfileModal.value = true;
};

const updateProfile = async () => {
    // Clear any previous errors
    profileError.value = '';

    // Client-side validation
    if (!editProfileForm.value.name.trim()) {
        profileError.value = 'Name is required.';
        return;
    }

    if (!editProfileForm.value.email.trim()) {
        profileError.value = 'Email is required.';
        return;
    }

    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(editProfileForm.value.email)) {
        profileError.value = 'Please enter a valid email address.';
        return;
    }

    isUpdatingProfile.value = true;

    try {
        await router.patch(route('profile.update'), editProfileForm.value, {
            onSuccess: () => {
                showEditProfileModal.value = false;
                // Show success notification
                showSuccess('Your profile has been updated successfully.');
                // Backend will redirect to my-profile page automatically
            },
            onError: (errors) => {
                console.error('Error updating profile:', errors);
                // Show specific error messages in popup
                if (errors.name) {
                    profileError.value = 'Name error: ' + errors.name[0];
                } else if (errors.email) {
                    profileError.value = 'Email error: ' + errors.email[0];
                } else {
                    profileError.value = 'Failed to update profile. Please try again.';
                }
            }
        });
    } catch (error) {
        console.error('Error updating profile:', error);
        profileError.value = 'Failed to update profile. Please try again.';
    } finally {
        isUpdatingProfile.value = false;
    }
};

const closeEditProfileModal = () => {
    showEditProfileModal.value = false;
    // Clear error
    profileError.value = '';
    // Reset form
    editProfileForm.value = {
        name: '',
        email: ''
    };
};

// Toggle auto-renew function
const toggleAutoRenew = async (event) => {
    const autoRenew = event.target.checked;

    console.log('Toggling auto-renew:', autoRenew, typeof autoRenew);

    try {
        await router.post(route('subscription.auto-renew'), {
            auto_renew: autoRenew ? 1 : 0  // Convert boolean to integer for proper validation
        }, {
            onSuccess: () => {
                // Show success notification
                const message = autoRenew
                    ? 'Auto-renewal has been enabled for your subscription.'
                    : 'Auto-renewal has been disabled for your subscription.';
                showSuccess(message);
                // Refresh the page to update the subscription status
                router.reload();
            },
            onError: (errors) => {
                console.error('Error toggling auto-renew:', errors);
                // Revert the toggle state
                event.target.checked = !autoRenew;
                // Show error notification
                showError('Failed to update auto-renewal setting. Please try again.');
            }
        });
    } catch (error) {
        console.error('Error toggling auto-renew:', error);
        // Revert the toggle state
        event.target.checked = !autoRenew;
        showError('Failed to update auto-renewal setting. Please try again.');
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

// Calculate days remaining until expiry
const getDaysRemaining = (dateString) => {
    if (!dateString) return 0;
    const expiryDate = new Date(dateString);
    const today = new Date();
    const timeDiff = expiryDate.getTime() - today.getTime();
    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
    return Math.max(0, daysDiff);
};

// Initialize if needed
onMounted(() => {

    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        // Flash messages will be cleared on next page load
    }, 5000);
});

// Clean up preview URL when component unmounts
onUnmounted(() => {
    // Cleanup if needed
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>

