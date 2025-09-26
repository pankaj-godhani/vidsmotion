@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome to the admin panel')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="card-gradient rounded-xl p-6 shadow-lg hover-scale">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</p>
                <p class="text-green-600 text-sm mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>
                    {{ $stats['active_users'] }} active
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="card-gradient rounded-xl p-6 shadow-lg hover-scale">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-800">₹{{ number_format($stats['total_revenue'], 2) }}</p>
                <p class="text-green-600 text-sm mt-1">
                    <i class="fas fa-rupee-sign mr-1"></i>
                    {{ $stats['total_payments'] }} payments
                </p>
            </div>
            <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Videos -->
    <div class="card-gradient rounded-xl p-6 shadow-lg hover-scale">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Videos</p>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_videos']) }}</p>
                <p class="text-blue-600 text-sm mt-1">
                    <i class="fas fa-video mr-1"></i>
                    {{ $stats['pending_videos'] }} processing
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-video text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Active Subscriptions -->
    <div class="card-gradient rounded-xl p-6 shadow-lg hover-scale">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Active Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['active_users']) }}</p>
                <p class="text-indigo-600 text-sm mt-1">
                    <i class="fas fa-user-check mr-1"></i>
                    {{ round(($stats['active_users'] / max($stats['total_users'], 1)) * 100, 1) }}% of total
                </p>
            </div>
            <div class="w-12 h-12 bg-indigo-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-check text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-users mr-2 text-blue-600"></i>
                Recent Users
            </h3>
            <a href="{{ route('adminpanel.users') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            @forelse($stats['recent_users'] as $user)
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-users text-4xl mb-4"></i>
                    <p>No users found</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Recent Payments -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-credit-card mr-2 text-green-600"></i>
                Recent Payments
            </h3>
            <a href="{{ route('adminpanel.payments') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            @forelse($stats['recent_payments'] as $payment)
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-rupee-sign text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $payment->user_name ?? 'Unknown User' }}</p>
                        <p class="text-sm text-gray-600">{{ $payment->plan_name }} Plan</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">₹{{ number_format($payment->amount, 2) }}</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-credit-card text-4xl mb-4"></i>
                    <p>No payments found</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        <i class="fas fa-bolt mr-2 text-yellow-600"></i>
        Quick Actions
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('adminpanel.users') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-white"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Manage Users</h4>
                <p class="text-sm text-gray-600">View and manage user accounts</p>
            </div>
        </a>
        
        <a href="{{ route('adminpanel.plans') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-crown text-white"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Manage Plans</h4>
                <p class="text-sm text-gray-600">Configure subscription plans</p>
            </div>
        </a>
        
        <a href="{{ route('adminpanel.payments') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-credit-card text-white"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">View Payments</h4>
                <p class="text-sm text-gray-600">Monitor payment transactions</p>
            </div>
        </a>
    </div>
</div>

<!-- System Status -->
<div class="mt-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold mb-2">
                <i class="fas fa-server mr-2"></i>
                System Status
            </h3>
            <p class="text-blue-100">All systems operational</p>
        </div>
        <div class="text-right">
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <p class="text-2xl font-bold">{{ $stats['total_users'] }}</p>
                    <p class="text-blue-200 text-sm">Users</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold">₹{{ number_format($stats['total_revenue']) }}</p>
                    <p class="text-blue-200 text-sm">Revenue</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold">{{ $stats['total_videos'] }}</p>
                    <p class="text-blue-200 text-sm">Videos</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
