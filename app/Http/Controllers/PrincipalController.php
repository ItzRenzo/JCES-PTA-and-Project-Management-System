<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PrincipalController extends Controller
{
    /**
     * Display the principal dashboard.
     */
    public function index()
    {
        return view('principal.dashboard');
    }

    /**
     * Show the create account form.
     */
    public function createAccount()
    {
        return view('principal.create-account');
    }

    /**
     * Store a newly created account.
     */
    public function storeAccount(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'role' => ['required', 'in:parent,teacher,administrator,principal'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Generate username from first name + last name + random number
        $baseUsername = strtolower($request->first_name . $request->last_name);
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        User::create([
            'username' => $username,
            'password_hash' => Hash::make($request->password),
            'user_type' => $request->role,
            'email' => $request->email,
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_active' => true,
        ]);

        return redirect()->route('principal.create-account')
            ->with('success', 'Account created successfully! Username: ' . $username);
    }

    /**
     * Administrator methods (same functionality, different layout)
     */
    
    /**
     * Display the administrator dashboard.
     */
    public function adminIndex()
    {
        return view('administrator.dashboard');
    }

    /**
     * Display the users page.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($request->has('role') && $request->role) {
            $query->where('user_type', $request->role);
        }

        // Apply status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Order by created_date (newest first)
        $query->orderBy('created_date', 'desc');

        $users = $query->paginate(10);

        return view('principal.users', compact('users'));
    }

    /**
     * Show the create account form for administrators.
     */
    public function adminCreateAccount()
    {
        return view('administrator.create-account');
    }

    /**
     * Store a newly created account (administrator version).
     */
    public function adminStoreAccount(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'role' => ['required', 'in:parent,teacher,administrator,principal'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Generate username from first name + last name + random number
        $baseUsername = strtolower($request->first_name . $request->last_name);
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        User::create([
            'username' => $username,
            'password_hash' => Hash::make($request->password),
            'user_type' => $request->role,
            'email' => $request->email,
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_active' => true,
        ]);

        return redirect()->route('administrator.create-account')
            ->with('success', 'Account created successfully! Username: ' . $username);
    }

    /**
     * Update an existing user.
     */
    public function updateUser(Request $request, $id)
    {
        try {
            // Find user by userID (our custom primary key)
            $user = User::where('userID', $id)->firstOrFail();

            $request->validate([
                'first_name' => ['required', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $user->userID . ',userID'],
                'phone' => ['nullable', 'string', 'max:20'],
                'user_type' => ['required', 'in:parent,teacher,administrator,principal'],
                'is_active' => ['required', 'boolean'],
                'password' => ['nullable', 'string', 'min:8'],
            ]);

            // Update user data
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->user_type = $request->user_type;
            $user->is_active = $request->is_active;

            // Update password if provided
            if ($request->filled('password')) {
                $user->password_hash = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the users page for administrators.
     */
    public function adminUsers(Request $request)
    {
        $query = User::query();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($request->has('role') && $request->role) {
            $query->where('user_type', $request->role);
        }

        // Apply status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Order by created_date (newest first)
        $query->orderBy('created_date', 'desc');

        $users = $query->paginate(10);

        return view('administrator.users', compact('users'));
    }

    /**
     * Update an existing user (administrator version).
     */
    public function adminUpdateUser(Request $request, $id)
    {
        try {
            // Find user by userID (our custom primary key)
            $user = User::where('userID', $id)->firstOrFail();

            $request->validate([
                'first_name' => ['required', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $user->userID . ',userID'],
                'phone' => ['nullable', 'string', 'max:20'],
                'user_type' => ['required', 'in:parent,teacher,administrator,principal'],
                'is_active' => ['required', 'boolean'],
                'password' => ['nullable', 'string', 'min:8'],
            ]);

            // Update user data
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->user_type = $request->user_type;
            $user->is_active = $request->is_active;

            // Update password if provided
            if ($request->filled('password')) {
                $user->password_hash = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }
}
