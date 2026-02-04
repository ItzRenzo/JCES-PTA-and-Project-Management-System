<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        return view('teacher.dashboard');
    }

    /**
     * Show the create account form.
     */
    public function createAccount()
    {
        return view('teacher.create-account');
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
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_type' => $request->role,
            'phone' => $request->phone,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'plain_password' => $request->password,
            'is_active' => true,
        ]);

        return redirect()->route('teacher.create-account')->with('success', 'Account created successfully! Username: ' . $username);
    }

    /**
     * Display the users page.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Only show parent accounts for teachers
        $query->where('user_type', 'parent');

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

        return view('teacher.users', compact('users'));
    }

    /**
     * Update user information.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Teachers can only edit parent accounts
        if ($user->user_type !== 'parent') {
            return response()->json(['success' => false, 'message' => 'You can only edit parent accounts'], 403);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users')->ignore($user->userID, 'userID')],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $updateData['password_hash'] = Hash::make($request->password);
            $updateData['plain_password'] = $request->password;
        }

        $user->update($updateData);

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }
}
