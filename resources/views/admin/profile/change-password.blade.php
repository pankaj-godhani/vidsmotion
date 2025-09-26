@extends('admin.layout')

@section('title', 'Change Password')
@section('page-title', 'Change Password')
@section('page-description', 'Update your admin password')

@section('content')
<div class="max-w-md mx-auto">
    <!-- Change Password Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            <i class="fas fa-key mr-2 text-blue-600"></i>
            Change Password
        </h3>
        
        <form method="POST" action="{{ route('adminpanel.password.update') }}" class="space-y-6">
            @csrf
            
            <!-- Current Password Field -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i>Current Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors pr-12"
                        placeholder="Enter current password"
                        required
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('current_password', 'current_password_icon')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i id="current_password_icon" class="fas fa-eye"></i>
                    </button>
                </div>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="err_current_password" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
            
            <!-- New Password Field -->
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-key mr-2"></i>New Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors pr-12"
                        placeholder="Enter new password"
                        required
                        minlength="6"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('new_password', 'new_password_icon')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i id="new_password_icon" class="fas fa-eye"></i>
                    </button>
                </div>
                @error('new_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="err_new_password" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
            
            <!-- Confirm Password Field -->
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-check-circle mr-2"></i>Confirm New Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="new_password_confirmation" 
                        name="new_password_confirmation" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors pr-12"
                        placeholder="Confirm new password"
                        required
                        minlength="6"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('new_password_confirmation', 'confirm_password_icon')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i id="confirm_password_icon" class="fas fa-eye"></i>
                    </button>
                </div>
                @error('new_password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="err_new_password_confirmation" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
            
            <!-- Password Requirements -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Password Requirements
                </h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Minimum 6 characters</li>
                    <li>• Must be different from current password</li>
                    <li>• Use a combination of letters and numbers</li>
                </ul>
            </div>
            
            <!-- Submit Button -->
            <div class="flex items-center space-x-4">
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition-colors flex items-center"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update Password
                </button>
                <a href="{{ route('adminpanel.profile') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Profile
                </a>
            </div>
        </form>
    </div>
    
    <!-- Security Tips -->
    <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            <i class="fas fa-shield-alt mr-2 text-green-600"></i>
            Security Tips
        </h3>
        
        <div class="space-y-4">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-green-600 text-sm"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Use Strong Passwords</h4>
                    <p class="text-sm text-gray-600">Include uppercase, lowercase, numbers, and special characters</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-sync text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Change Regularly</h4>
                    <p class="text-sm text-gray-600">Update your password every 3-6 months for better security</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-secret text-purple-600 text-sm"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Keep It Private</h4>
                    <p class="text-sm text-gray-600">Never share your password with anyone or write it down</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Current Password Info -->
    <div class="mt-8 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Current Password
                </h3>
                <p class="text-yellow-100">For demo purposes, current password is: <strong>123123123</strong></p>
            </div>
            <div class="text-right">
                <div class="text-center">
                    <p class="text-2xl font-bold">Demo</p>
                    <p class="text-yellow-200 text-sm">Mode</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const passwordIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }
    
    // Inline form validation helpers
    function setError(inputId, errId, message) {
        const input = document.getElementById(inputId);
        const err = document.getElementById(errId);
        if (input && err) {
            input.classList.add('border-red-500');
            err.textContent = message || '';
            err.classList.toggle('hidden', !message);
        }
    }

    function clearError(inputId, errId) {
        const input = document.getElementById(inputId);
        const err = document.getElementById(errId);
        if (input) input.classList.remove('border-red-500');
        if (err) { err.textContent = ''; err.classList.add('hidden'); }
    }

    function validateField(inputId, errId) {
        const current = document.getElementById('current_password').value;
        const pass = document.getElementById('new_password').value;
        const confirm = document.getElementById('new_password_confirmation').value;
        if (inputId === 'current_password') {
            if (!current) { setError('current_password', 'err_current_password', 'Please enter current password'); return false; }
            clearError('current_password', 'err_current_password');
        }
        if (inputId === 'new_password') {
            if (!pass) { setError('new_password', 'err_new_password', 'Please enter new password'); return false; }
            if (pass.length < 6) { setError('new_password', 'err_new_password', 'Password must be at least 6 characters'); return false; }
            if (current && pass === current) { setError('new_password', 'err_new_password', 'New password must be different from current'); return false; }
            clearError('new_password', 'err_new_password');
        }
        if (inputId === 'new_password_confirmation') {
            if (!confirm) { setError('new_password_confirmation', 'err_new_password_confirmation', 'Please confirm new password'); return false; }
            if (pass !== confirm) { setError('new_password_confirmation', 'err_new_password_confirmation', 'Passwords do not match'); return false; }
            clearError('new_password_confirmation', 'err_new_password_confirmation');
        }
        return true;
    }

    // Attach realtime validation
    document.getElementById('current_password').addEventListener('input', () => validateField('current_password'));
    document.getElementById('new_password').addEventListener('input', () => validateField('new_password'));
    document.getElementById('new_password_confirmation').addEventListener('input', () => validateField('new_password_confirmation'));

    // Form validation submit
    document.querySelector('form').addEventListener('submit', function(e) {
        let ok = true;
        if (!validateField('current_password')) ok = false;
        if (!validateField('new_password')) ok = false;
        if (!validateField('new_password_confirmation')) ok = false;
        if (!ok) { e.preventDefault(); return false; }
    });
    
    // Keep confirm error in sync
    document.getElementById('new_password').addEventListener('input', function() {
        validateField('new_password');
        validateField('new_password_confirmation');
    });
</script>
@endsection
