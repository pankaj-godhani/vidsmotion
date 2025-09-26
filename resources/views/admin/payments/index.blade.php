@extends('admin.layout')

@section('title', 'Payments Management')
@section('page-title', 'Payments Management')
@section('page-description', 'Monitor payment transactions and revenue')

@section('content')
<!-- Payment Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Payments</p>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($payments->total()) }}</p>
                <p class="text-green-600 text-sm mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>
                    All time
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-credit-card text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-800">₹{{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}</p>
                <p class="text-green-600 text-sm mt-1">
                    <i class="fas fa-rupee-sign mr-1"></i>
                    Completed
                </p>
            </div>
            <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Success Rate</p>
                <p class="text-3xl font-bold text-gray-800">{{ round(($payments->where('status', 'completed')->count() / max($payments->count(), 1)) * 100, 1) }}%</p>
                <p class="text-blue-600 text-sm mt-1">
                    <i class="fas fa-check-circle mr-1"></i>
                    Completed
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-percentage text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Pending</p>
                <p class="text-3xl font-bold text-gray-800">{{ $payments->where('status', 'pending')->count() }}</p>
                <p class="text-yellow-600 text-sm mt-1">
                    <i class="fas fa-clock mr-1"></i>
                    Awaiting
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-credit-card mr-2 text-green-600"></i>
                Payment Transactions
            </h3>
            <div class="flex items-center space-x-4">
                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Search payments..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-user mr-1"></i>Customer
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-crown mr-1"></i>Plan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-rupee-sign mr-1"></i>Amount
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-toggle-on mr-1"></i>Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-calendar mr-1"></i>Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-cog mr-1"></i>Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr($payment->user_name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $payment->user_name ?? 'Unknown User' }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->user_email ?? 'No email' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $payment->plan_name }}</div>
                            <div class="text-sm text-gray-500">{{ $payment->plan_type }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">₹{{ number_format($payment->amount, 2) }}</div>
                            <div class="text-sm text-gray-500">{{ $payment->currency }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $payment->payment_date ? $payment->payment_date->diffForHumans() : 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" title="Refund">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors" title="Cancel">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-credit-card text-4xl mb-4"></i>
                            <p class="text-lg">No payments found</p>
                            <p class="text-sm">Payment transactions will appear here</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
    @endif
</div>

<!-- Revenue Chart Placeholder -->
<div class="mt-8 bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        <i class="fas fa-chart-area mr-2 text-blue-600"></i>
        Revenue Overview
    </h3>
    
    <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
        <div class="text-center text-gray-500">
            <i class="fas fa-chart-area text-4xl mb-4"></i>
            <p class="text-lg">Revenue Chart</p>
            <p class="text-sm">Chart visualization would be implemented here</p>
        </div>
    </div>
</div>

<!-- Payment Methods -->
<div class="mt-8 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold mb-2">
                <i class="fas fa-credit-card mr-2"></i>
                Payment Methods
            </h3>
            <p class="text-green-100">Razorpay integration active</p>
        </div>
        <div class="text-right">
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <p class="text-2xl font-bold">UPI</p>
                    <p class="text-green-200 text-sm">Available</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold">Cards</p>
                    <p class="text-green-200 text-sm">Available</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold">Net Banking</p>
                    <p class="text-green-200 text-sm">Available</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
