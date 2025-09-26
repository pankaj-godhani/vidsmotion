<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    /**
     * Show admin login page
     */
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (Session::has('admin_logged_in')) {
            return redirect('/admin/panel');
        }
        
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Fixed admin credentials
        $adminEmail = 'godhanipnj131@gmail.com';
        $adminPassword = '123123123';

        if ($request->email === $adminEmail && $request->password === $adminPassword) {
            // Set admin session
            Session::put('admin_logged_in', true);
            Session::put('admin_email', $adminEmail);
            Session::put('admin_name', 'Admin User');
            
            return redirect('/admin/panel')->with('success', 'Welcome to Admin Panel!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        Session::forget(['admin_logged_in', 'admin_email', 'admin_name']);
        return redirect('/admin/login')->with('success', 'Logged out successfully!');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Check if admin is logged in
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            'total_users' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('credits', '>=', 50)->count(),
            'total_payments' => \App\Models\Payment::count(),
            'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            'total_videos' => \App\Models\Upload::where('status', 'completed')->count(),
            'pending_videos' => \App\Models\Upload::where('status', 'processing')->count(),
            'recent_users' => \App\Models\User::latest()->take(5)->get(),
            'recent_payments' => \App\Models\Payment::latest()->take(5)->get(),
        ];
    }

    /**
     * Show users management page
     */
    public function users()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $users = \App\Models\User::with('payments')->latest()->paginate(20);
        $filter = 'all';
        return view('admin.users.index', compact('users', 'filter'));
    }

    /**
     * Show only active (verified) users
     */
    public function activeUsers()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $users = \App\Models\User::with('payments')
            ->where('credits', '>=', 50)
            ->latest()
            ->paginate(20);

        $filter = 'active';
        return view('admin.users.index', compact('users', 'filter'));
    }

    /**
     * Show only inactive (unverified) users
     */
    public function inactiveUsers()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $users = \App\Models\User::with('payments')
            ->where('credits', '<', 50)
            ->latest()
            ->paginate(20);

        $filter = 'inactive';
        return view('admin.users.index', compact('users', 'filter'));
    }

    /**
     * Show plans management page
     */
    public function plans()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $plans = [
            [
                'name' => 'Standard',
                'price' => 99,
                'duration' => '7 days',
                'credits' => 50,
                'subscription_id' => 'sub_RLmN8cDJVph0DQ'
            ],
            [
                'name' => 'Pro Monthly',
                'price' => 299,
                'duration' => '30 days',
                'credits' => 200,
                'subscription_id' => 'sub_RLmMs3kifmc6qk'
            ],
            [
                'name' => 'Premier Yearly',
                'price' => 3999,
                'duration' => '365 days',
                'credits' => 3000,
                'subscription_id' => 'sub_RLmMUPcT4AxFFV'
            ]
        ];

        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show payments management page
     */
    public function payments()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $payments = \App\Models\Payment::with('user')->latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Return user JSON for modals
     */
    public function getUserJson($id)
    {
        if (!Session::has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = \App\Models\User::select('id', 'name', 'email', 'credits', 'created_at', 'email_verified_at')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'credits' => (int) $user->credits,
                'created_at' => $user->created_at,
                'verified' => (bool) $user->email_verified_at,
                'status' => $user->credits >= 50 ? 'active' : 'inactive',
            ]
        ]);
    }

    /**
     * Update user (name, email, credits)
     */
    public function updateUser(Request $request, $id)
    {
        if (!Session::has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'credits' => 'required|integer|min:0',
        ]);

        $user = \App\Models\User::findOrFail($id);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->credits = $validated['credits'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
        ]);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        if (!Session::has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    /**
     * Change user password (admin action)
     */
    public function changeUserPassword(Request $request, $id)
    {
        if (!Session::has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->password = \Illuminate\Support\Facades\Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
        ]);
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        return view('admin.profile.index');
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Update session data
        Session::put('admin_name', $request->name);
        Session::put('admin_email', $request->email);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password page
     */
    public function changePassword()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        return view('admin.profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Check current password
        if ($request->current_password !== '123123123') {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        // For demo purposes, we'll just show success
        // In a real app, you'd update the password in database
        return back()->with('success', 'Password updated successfully!');
    }
}
