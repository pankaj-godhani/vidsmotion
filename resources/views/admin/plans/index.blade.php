@extends('admin.layout')

@section('title', 'Plans Management')
@section('page-title', 'Plans Management')
@section('page-description', 'Configure subscription plans and pricing')

@section('content')
<!-- Plans Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    @foreach($plans as $plan)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale transition-transform">
            <!-- Plan Header -->
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold">{{ $plan['name'] }}</h3>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        @if($plan['name'] === 'Standard')
                            <i class="fas fa-star text-white"></i>
                        @elseif($plan['name'] === 'Pro Monthly')
                            <i class="fas fa-crown text-white"></i>
                        @else
                            <i class="fas fa-gem text-white"></i>
                        @endif
                    </div>
                </div>
                <div class="text-3xl font-bold mb-2">₹{{ number_format($plan['price']) }}</div>
                <div class="text-blue-200">{{ $plan['duration'] }}</div>
            </div>
            
            <!-- Plan Details -->
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Credits</span>
                        <span class="font-semibold text-gray-800">{{ number_format($plan['credits']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Duration</span>
                        <span class="font-semibold text-gray-800">{{ $plan['duration'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Price</span>
                        <span class="font-semibold text-gray-800">₹{{ number_format($plan['price']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Subscription ID</span>
                        <span class="font-mono text-xs text-gray-500">{{ $plan['subscription_id'] }}</span>
                    </div>
                </div>
                
                <!-- Plan Actions -->
                <div class="mt-6 space-y-2">
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Plan
                    </button>
                    <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>View Analytics
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Plan Statistics -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
        Plan Statistics
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-star text-white"></i>
            </div>
            <h4 class="font-semibold text-gray-800">Standard Plan</h4>
            <p class="text-2xl font-bold text-blue-600">₹99</p>
            <p class="text-sm text-gray-600">7 days • 50 credits</p>
        </div>
        
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-crown text-white"></i>
            </div>
            <h4 class="font-semibold text-gray-800">Pro Monthly</h4>
            <p class="text-2xl font-bold text-purple-600">₹299</p>
            <p class="text-sm text-gray-600">30 days • 200 credits</p>
        </div>
        
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-gem text-white"></i>
            </div>
            <h4 class="font-semibold text-gray-800">Premier Yearly</h4>
            <p class="text-2xl font-bold text-green-600">₹3,999</p>
            <p class="text-sm text-gray-600">365 days • 3000 credits</p>
        </div>
        
        <div class="text-center p-4 bg-orange-50 rounded-lg">
            <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-chart-line text-white"></i>
            </div>
            <h4 class="font-semibold text-gray-800">Total Revenue</h4>
            <p class="text-2xl font-bold text-orange-600">₹4,397</p>
            <p class="text-sm text-gray-600">All plans combined</p>
        </div>
    </div>
</div>

<!-- Plan Configuration -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        <i class="fas fa-cog mr-2 text-gray-600"></i>
        Plan Configuration
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Pricing Settings -->
        <div>
            <h4 class="font-semibold text-gray-800 mb-4">Pricing Settings</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Standard Plan Price</span>
                    <span class="font-semibold text-gray-800">₹99</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Pro Monthly Price</span>
                    <span class="font-semibold text-gray-800">₹299</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Premier Yearly Price</span>
                    <span class="font-semibold text-gray-800">₹3,999</span>
                </div>
            </div>
        </div>
        
        <!-- Credit Settings -->
        <div>
            <h4 class="font-semibold text-gray-800 mb-4">Credit Settings</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Standard Credits</span>
                    <span class="font-semibold text-gray-800">50</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Pro Monthly Credits</span>
                    <span class="font-semibold text-gray-800">200</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Premier Yearly Credits</span>
                    <span class="font-semibold text-gray-800">3,000</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-8 flex items-center space-x-4">
        <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition-colors">
            <i class="fas fa-save mr-2"></i>Save Changes
        </button>
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-6 rounded-lg transition-colors">
            <i class="fas fa-undo mr-2"></i>Reset to Default
        </button>
        <button class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Add New Plan
        </button>
    </div>
</div>

<!-- Razorpay Integration Status -->
<div class="mt-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold mb-2">
                <i class="fas fa-credit-card mr-2"></i>
                Razorpay Integration
            </h3>
            <p class="text-blue-100">Payment gateway is active and configured</p>
        </div>
        <div class="text-right">
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <p class="text-2xl font-bold">3</p>
                    <p class="text-blue-200 text-sm">Active Plans</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold">100%</p>
                    <p class="text-blue-200 text-sm">Uptime</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold">Live</p>
                    <p class="text-blue-200 text-sm">Status</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
