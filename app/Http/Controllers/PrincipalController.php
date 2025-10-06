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
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($request->has('role') && $request->role) {
            $query->where('user_type', $request->role);
        }

        // Apply status filter (assuming you have a status field)
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }

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
}
