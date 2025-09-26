@extends('admin.layout')

@section('title', 'Users Management')
@section('page-title', 'Users Management')
@section('page-description', 'Manage user accounts and subscriptions')

@section('content')
<!-- Users Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-users mr-2 text-blue-600"></i>
                @if(($filter ?? 'all') === 'active')
                    Active Users ({{ $users->total() }})
                @elseif(($filter ?? 'all') === 'inactive')
                    Inactive Users ({{ $users->total() }})
                @else
                    All Users ({{ $users->total() }})
                @endif
            </h3>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Search users..." 
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
                        <i class="fas fa-user mr-1"></i>User
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-envelope mr-1"></i>Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-coins mr-1"></i>Credits
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-calendar mr-1"></i>Joined
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-toggle-on mr-1"></i>Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-cog mr-1"></i>Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        <div class="text-xs {{ $user->credits >= 50 ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas {{ $user->credits >= 50 ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $user->credits >= 50 ? 'Active (50+ credits)' : 'Inactive (<50 credits)' }}
                        </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ number_format($user->credits) }}</div>
                            <div class="text-xs text-gray-500">credits</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->credits >= 50 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                {{ $user->credits >= 50 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="btn-view text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" data-user-id="{{ $user->id }}" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" data-user-id="{{ $user->id }}" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-pass text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition-colors" data-user-id="{{ $user->id }}" title="Change Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button class="btn-delete text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors" data-user-id="{{ $user->id }}" title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p class="text-lg">No users found</p>
                            <p class="text-sm">Users will appear here once they register</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- User Statistics -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Users</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($users->total()) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Active Users</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($users->where('credits', '>=', 50)->count()) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-check text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Verified Users</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($users->whereNotNull('email_verified_at')->count()) }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-purple-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">New This Month</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($users->where('created_at', '>=', now()->startOfMonth())->count()) }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-plus text-orange-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="userModalBackdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden z-40"></div>

<!-- View User Modal -->
<div id="viewUserModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-user mr-2 text-blue-600"></i>
                User Details
            </h3>
            <button onclick="closeModals()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
        </div>
        <div id="viewUserBody" class="space-y-2 text-gray-700 text-sm">
            Loading...
        </div>
    </div>
    
    <!-- Toast -->
    <div id="toast" class="fixed bottom-6 right-6 hidden bg-gray-900 text-white px-4 py-2 rounded shadow"></div>
    
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-user-edit mr-2 text-green-600"></i>
                Edit User
            </h3>
            <button onclick="closeModals()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
        </div>
        <form id="editUserForm" onsubmit="return submitEditUser(event)">
            <input type="hidden" id="edit_user_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Name</label>
                    <input id="edit_user_name" type="text" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Email</label>
                    <input id="edit_user_email" type="email" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Credits</label>
                    <input id="edit_user_credits" type="number" min="0" class="w-full border rounded-lg px-3 py-2" required>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteUserModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-trash mr-2 text-red-600"></i>
                Delete User
            </h3>
            <button onclick="closeModals()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
        </div>
        <p class="text-gray-700">Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="mt-6 flex items-center justify-end space-x-3">
            <button type="button" onclick="closeModals()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
            <button type="button" onclick="confirmDeleteUser()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Delete</button>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordUserModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-key mr-2 text-indigo-600"></i>
                Change User Password
            </h3>
            <button onclick="closeModals()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
        </div>
        <form id="passwordUserForm" onsubmit="return submitChangePassword(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">New Password</label>
                    <input id="new_password" type="password" minlength="6" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Confirm New Password</label>
                    <input id="new_password_confirmation" type="password" minlength="6" class="w-full border rounded-lg px-3 py-2" required>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Update</button>
            </div>
        </form>
    </div>
    
    <!-- Toast -->
    <div id="toast" class="fixed bottom-6 right-6 hidden bg-gray-900 text-white px-4 py-2 rounded shadow"></div>
    
</div>

<script>
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
    let selectedUserId = null;

    function showToast(message) {
        const t = document.getElementById('toast');
        if (!t) return;
        t.textContent = message;
        t.classList.remove('hidden');
        setTimeout(() => t.classList.add('hidden'), 2500);
    }

    function openBackdrop() { document.getElementById('userModalBackdrop').classList.remove('hidden'); }
    function closeBackdrop() { document.getElementById('userModalBackdrop').classList.add('hidden'); }
    function closeModals() {
        ['viewUserModal','editUserModal','deleteUserModal'].forEach(id => document.getElementById(id).classList.add('hidden'));
        closeBackdrop();
    }

    async function fetchUser(id) {
        const res = await fetch(`/admin/users-json/${id}`, { headers: { 'Accept': 'application/json' }});
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Failed to load');
        return data.data;
    }

    async function openViewUser(id) {
        selectedUserId = id;
        openBackdrop();
        document.getElementById('viewUserModal').classList.remove('hidden');
        const body = document.getElementById('viewUserBody');
        body.textContent = 'Loading...';
        try {
            const user = await fetchUser(id);
            const initial = (user.name || '?').trim().charAt(0).toUpperCase();
            const active = user.credits >= 50;
            const createdAt = user.created_at ? new Date(user.created_at) : null;
            const joined = createdAt ? createdAt.toLocaleString() : '—';

            body.innerHTML = `
                <div class="space-y-5">
                    <!-- Profile header -->
                    <div class="flex items-start space-x-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-r ${active ? 'from-green-500 to-emerald-600' : 'from-gray-400 to-gray-500'} flex items-center justify-center text-white text-xl font-semibold shadow">
                            ${initial}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center flex-wrap gap-2">
                                <h4 class="text-lg font-semibold text-gray-900">${user.name || 'Unnamed User'}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    <i class="fas fa-circle mr-1 text-[8px]"></i>
                                    ${active ? 'Active' : 'Inactive'}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${user.verified ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700'}">
                                    <i class="fas ${user.verified ? 'fa-check-circle' : 'fa-minus-circle'} mr-1"></i>
                                    ${user.verified ? 'Verified' : 'Unverified'}
                                </span>
                            </div>
                            <div class="mt-1 text-sm text-gray-600 flex items-center">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                <span class="font-medium">${user.email || '—'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="text-xs text-gray-500">User ID</div>
                            <div class="text-sm font-semibold text-gray-800">${user.id}</div>
                        </div>
                        <div class="p-3 rounded-lg ${active ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100'}">
                            <div class="text-xs ${active ? 'text-green-700' : 'text-red-700'}">Credits</div>
                            <div class="text-sm font-semibold ${active ? 'text-green-800' : 'text-red-800'}">${user.credits}</div>
                        </div>
                        <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="text-xs text-gray-500">Joined</div>
                            <div class="text-sm font-semibold text-gray-800">${joined}</div>
                        </div>
                    </div>

                    <!-- Quick actions removed as requested -->
                </div>`;
        } catch (e) {
            body.textContent = 'Failed to load user.';
        }
    }

    async function openEditUser(id) {
        selectedUserId = id;
        openBackdrop();
        document.getElementById('editUserModal').classList.remove('hidden');
        try {
            const user = await fetchUser(id);
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_user_name').value = user.name;
            document.getElementById('edit_user_email').value = user.email;
            document.getElementById('edit_user_credits').value = user.credits;
        } catch (e) {
            closeModals();
            showToast('Failed to load user');
        }
    }

    async function submitEditUser(e) {
        e.preventDefault();
        const form = e.target;
        const id = document.getElementById('edit_user_id').value;
        const name = document.getElementById('edit_user_name').value.trim();
        const email = document.getElementById('edit_user_email').value.trim();
        const creditsVal = document.getElementById('edit_user_credits').value;
        const credits = parseInt(creditsVal || '0', 10);

        // Client-side validation
        const errors = [];
        if (!name || name.length < 2) errors.push('Name must be at least 2 characters.');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) errors.push('Enter a valid email address.');
        if (Number.isNaN(credits) || credits < 0) errors.push('Credits must be a non-negative number.');
        if (errors.length) { showToast(errors[0]); return false; }

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) { submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); }

        try {
            const res = await fetch(`/admin/users-update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name, email, credits })
            });

            if (res.status === 401) {
                showToast('Session expired. Please login again.');
                return false;
            }

            if (res.status === 422) {
                const data = await res.json();
                const serverErrors = data.errors || {};
                const firstErr = Object.values(serverErrors).flat()[0] || data.message || 'Validation failed';
                showToast(firstErr);
                return false;
            }

            const data = await res.json();
            if (!data.success) {
                showToast(data.message || 'Update failed');
                return false;
            }
            showToast('User updated');
            closeModals();
            window.location.reload();
        } catch (err) {
            showToast(err.message || 'Failed to update');
        } finally {
            if (submitBtn) { submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); }
        }
        return false;
    }

    function openDeleteUser(id) {
        selectedUserId = id;
        openBackdrop();
        document.getElementById('deleteUserModal').classList.remove('hidden');
    }

    function openChangePassword(id) {
        selectedUserId = id;
        openBackdrop();
        document.getElementById('passwordUserModal').classList.remove('hidden');
        document.getElementById('new_password').value = '';
        document.getElementById('new_password_confirmation').value = '';
    }

    async function submitChangePassword(e) {
        e.preventDefault();
        const form = e.target;
        const pass = document.getElementById('new_password').value;
        const pass2 = document.getElementById('new_password_confirmation').value;

        // Client validation
        if (!pass || pass.length < 6) { showToast('Password must be at least 6 characters'); return false; }
        if (pass !== pass2) { showToast('Passwords do not match'); return false; }

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) { submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); }

        try {
            const res = await fetch(`/admin/users-change-password/${selectedUserId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ new_password: pass, new_password_confirmation: pass2 })
            });

            if (res.status === 401) { showToast('Session expired. Please login again.'); return false; }
            if (res.status === 422) {
                const data = await res.json();
                const serverErrors = data.errors || {};
                const firstErr = Object.values(serverErrors).flat()[0] || data.message || 'Validation failed';
                showToast(firstErr);
                return false;
            }

            const data = await res.json();
            if (!data.success) { showToast(data.message || 'Update failed'); return false; }
            showToast('Password updated');
            closeModals();
        } catch (err) {
            showToast(err.message || 'Failed to update');
        } finally {
            if (submitBtn) { submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); }
        }
        return false;
    }

    async function confirmDeleteUser() {
        if (!selectedUserId) return;
        try {
            const res = await fetch(`/admin/users-delete/${selectedUserId}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.message || 'Delete failed');
            showToast('User deleted');
            closeModals();
            window.location.reload();
        } catch (err) {
            showToast(err.message || 'Failed to delete');
        }
    }
    // Delegate click events for action buttons
    document.addEventListener('click', function(e) {
        const viewBtn = e.target.closest('.btn-view');
        const editBtn = e.target.closest('.btn-edit');
        const deleteBtn = e.target.closest('.btn-delete');
        if (viewBtn) {
            const id = viewBtn.getAttribute('data-user-id');
            openViewUser(id);
        } else if (editBtn) {
            const id = editBtn.getAttribute('data-user-id');
            openEditUser(id);
        } else if (deleteBtn) {
            const id = deleteBtn.getAttribute('data-user-id');
            openDeleteUser(id);
        } else if (e.target.closest('.btn-pass')) {
            const id = e.target.closest('.btn-pass').getAttribute('data-user-id');
            openChangePassword(id);
        }
    });

</script>
@endsection
