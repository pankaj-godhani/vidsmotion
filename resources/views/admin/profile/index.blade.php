@extends('admin.layout')

@section('title', 'Profile Settings')
@section('page-title', 'Profile Settings')
@section('page-description', 'Update your admin profile information')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Profile Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            <i class="fas fa-user-edit mr-2 text-blue-600"></i>
            Update Profile
        </h3>
        
        <form method="POST" action="{{ route('adminpanel.profile.update') }}" class="space-y-6">
            @csrf
            
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2"></i>Full Name
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ session('admin_name', 'Admin User') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Enter your full name"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2"></i>Email Address
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ session('admin_email', 'godhanipnj131@gmail.com') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Enter your email address"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Role Field (Read-only) -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-shield-alt mr-2"></i>Role
                </label>
                <input 
                    type="text" 
                    id="role" 
                    value="Administrator"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                    readonly
                >
                <p class="mt-1 text-sm text-gray-500">Your role cannot be changed</p>
            </div>
            
            <!-- Last Login -->
            <div>
                <label for="last_login" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-clock mr-2"></i>Last Login
                </label>
                <input 
                    type="text" 
                    id="last_login" 
                    value="{{ now()->format('M d, Y H:i:s') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                    readonly
                >
            </div>
            
            <!-- Submit Button -->
            <div class="flex items-center space-x-4">
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition-colors flex items-center"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update Profile
                </button>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </form>
    </div>
    
    <!-- Account Information -->
    <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            <i class="fas fa-info-circle mr-2 text-green-600"></i>
            Account Information
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Account Type</span>
                    <span class="font-semibold text-gray-800">Administrator</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Access Level</span>
                    <span class="font-semibold text-gray-800">Full Access</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Account Status</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Active
                    </span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Created</span>
                    <span class="font-semibold text-gray-800">{{ now()->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Last Updated</span>
                    <span class="font-semibold text-gray-800">{{ now()->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Login Count</span>
                    <span class="font-semibold text-gray-800">Multiple</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Security Information -->
    <div class="mt-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-2">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Security Status
                </h3>
                <p class="text-blue-100">Your account is secure and protected</p>
            </div>
            <div class="text-right">
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold">✓</p>
                        <p class="text-blue-200 text-sm">Secure</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold">Admin</p>
                        <p class="text-blue-200 text-sm">Role</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold">Active</p>
                        <p class="text-blue-200 text-sm">Status</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
