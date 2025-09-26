<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Vidsmotion Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
        }
        .card-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar-gradient w-64 text-white shadow-lg">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-video text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Vidsmotion</h1>
                        <p class="text-blue-200 text-sm">Admin Panel</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-8">
                <a href="{{ route('adminpanel.dashboard') }}" class="flex items-center px-6 py-3 text-blue-200 hover:bg-white hover:bg-opacity-10 hover:text-white transition-colors {{ request()->routeIs('adminpanel.dashboard') ? 'bg-white bg-opacity-20 text-white' : '' }}">
                    <i class="fas fa-chart-line w-5 mr-3"></i>
                    Dashboard
                </a>
                <div class="px-3">
                    <button class="w-full flex items-center px-3 py-3 text-blue-200 hover:bg-white hover:bg-opacity-10 hover:text-white transition-colors rounded {{ request()->routeIs('adminpanel.users*') ? 'bg-white bg-opacity-20 text-white' : '' }}" onclick="toggleUsersMenu()">
                        <i class="fas fa-users w-5 mr-3"></i>
                        <span class="flex-1 text-left">Users</span>
                        <i id="usersChevron" class="fas fa-chevron-down text-xs transition-transform {{ request()->routeIs('adminpanel.users*') ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="usersSubmenu" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('adminpanel.users*') ? '' : 'hidden' }}">
                        <a href="{{ route('adminpanel.users') }}" class="block px-3 py-2 text-blue-200 hover:text-white hover:bg-white hover:bg-opacity-10 rounded {{ request()->routeIs('adminpanel.users') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                            All Users
                        </a>
                        <a href="{{ route('adminpanel.users.active') }}" class="block px-3 py-2 text-blue-200 hover:text-white hover:bg-white hover:bg-opacity-10 rounded {{ request()->routeIs('adminpanel.users.active') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                            Active Users
                        </a>
                        <a href="{{ route('adminpanel.users.inactive') }}" class="block px-3 py-2 text-blue-200 hover:text-white hover:bg-white hover:bg-opacity-10 rounded {{ request()->routeIs('adminpanel.users.inactive') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                            Inactive Users
                        </a>
                    </div>
                </div>
                <a href="{{ route('adminpanel.plans') }}" class="flex items-center px-6 py-3 text-blue-200 hover:bg-white hover:bg-opacity-10 hover:text-white transition-colors {{ request()->routeIs('adminpanel.plans') ? 'bg-white bg-opacity-20 text-white' : '' }}">
                    <i class="fas fa-crown w-5 mr-3"></i>
                    Plans
                </a>
                <a href="{{ route('adminpanel.payments') }}" class="flex items-center px-6 py-3 text-blue-200 hover:bg-white hover:bg-opacity-10 hover:text-white transition-colors {{ request()->routeIs('adminpanel.payments') ? 'bg-white bg-opacity-20 text-white' : '' }}">
                    <i class="fas fa-credit-card w-5 mr-3"></i>
                    Payments
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6">
                <div class="glass-effect rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">{{ session('admin_name', 'Admin') }}</p>
                            <p class="text-blue-200 text-xs">{{ session('admin_email', 'admin@example.com') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600">@yield('page-description', 'Welcome to the admin panel')</p>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profileDropdown" class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 focus:outline-none">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-medium">{{ session('admin_name', 'Admin') }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                            <div class="py-1">
                                <a href="{{ route('adminpanel.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit w-4 mr-3"></i>
                                    Update Profile
                                </a>
                                <a href="{{ route('adminpanel.change-password') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-key w-4 mr-3"></i>
                                    Change Password
                                </a>
                                <hr class="my-1">
                                <form action="{{ route('adminpanel.logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt w-4 mr-3"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-6">
                    @if(session('success'))
                        <div data-alert class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div data-alert class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Profile dropdown toggle
        document.getElementById('profileDropdown').addEventListener('click', function() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('hidden');
        });

        // Users submenu toggle
        function toggleUsersMenu() {
            var submenu = document.getElementById('usersSubmenu');
            var chevron = document.getElementById('usersChevron');
            if (!submenu || !chevron) return;
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                submenu.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const menu = document.getElementById('profileMenu');
            
            if (!dropdown.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
        
        // Auto-hide only flash alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[data-alert]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>
