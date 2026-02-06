<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\ParentProfile;
use App\Models\SecurityAuditLog;
use App\Models\Schedule;
use App\Models\Announcement;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PrincipalController extends Controller
{
    /**
     * Display the principal dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get recent announcements (prioritize important, then 3 most recent)
        $recentAnnouncements = Announcement::with('creator')
            ->active()
            ->published()
            ->orderByRaw("CASE WHEN category = 'important' THEN 0 ELSE 1 END")
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Get upcoming schedules (exclusive to user role)
        $upcomingSchedules = Schedule::active()
            ->upcoming()
            ->forRole($user->user_type)
            ->orderBy('scheduled_date', 'asc')
            ->limit(3)
            ->get();

        return view('principal.dashboard', compact('recentAnnouncements', 'upcomingSchedules'));
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

        $user = User::create([
            'username' => $username,
            'password_hash' => Hash::make($request->password),
            'plain_password' => $request->password,
            'user_type' => $request->role,
            'email' => $request->email,
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_active' => true,
        ]);

        // Log the account creation activity
        SecurityAuditLog::logActivity(
            Auth::user()->userID,
            'create_user_account',
            'users',
            $user->userID,
            null,
            [
                'created_user' => $user->first_name . ' ' . $user->last_name,
                'username' => $username,
                'email' => $user->email,
                'user_type' => $user->user_type
            ],
            true,
            null
        );

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
        $user = Auth::user();

        // Get recent announcements (visible to everyone or matching user role)
        $recentAnnouncements = Announcement::with('creator')
            ->active()
            ->published()
            ->orderByRaw("CASE WHEN category = 'important' THEN 0 ELSE 1 END")
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Get upcoming schedules (exclusive to user role)
        $upcomingSchedules = Schedule::active()
            ->upcoming()
            ->forRole($user->user_type)
            ->orderBy('scheduled_date', 'asc')
            ->limit(3)
            ->get();

        // Dashboard statistics
        $stats = [
            'proposedProjects' => \App\Models\Project::where('project_status', 'created')
                ->whereMonth('created_date', now()->month)
                ->whereYear('created_date', now()->year)
                ->count(),
            'activeParents' => User::where('user_type', 'parent')
                ->where('account_status', 'active')
                ->count(),
            'newParentsThisMonth' => User::where('user_type', 'parent')
                ->where('account_status', 'active')
                ->whereMonth('registration_date', now()->month)
                ->whereYear('registration_date', now()->year)
                ->count(),
            'upcomingEvents' => Schedule::active()
                ->upcoming()
                ->whereMonth('scheduled_date', now()->month)
                ->whereYear('scheduled_date', now()->year)
                ->count(),
            'activeProjects' => \App\Models\Project::whereIn('project_status', ['active', 'in_progress'])
                ->count(),
            'completedProjectsThisMonth' => \App\Models\Project::where('project_status', 'completed')
                ->whereMonth('actual_completion_date', now()->month)
                ->whereYear('actual_completion_date', now()->year)
                ->count(),
        ];

        return view('administrator.dashboard', compact('recentAnnouncements', 'upcomingSchedules', 'stats'));
    }

    /**
     * Display the users page.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Apply search filter - searches across name, email, phone, and address (for parents)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search in users table
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  // Search in parent profile for address fields
                  ->orWhereHas('parentProfile', function($pq) use ($search) {
                      $pq->where('street_address', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('barangay', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
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

        // Get per page value for users (default 10)
        $usersPerPage = $request->input('users_per_page', 10);
        $usersPerPage = in_array($usersPerPage, [10, 25, 50, 100]) ? $usersPerPage : 10;

        $users = $query->paginate($usersPerPage);

        // Students listing mirrors administrator experience
        $studentQuery = Student::with(['parents.user']);

        if ($request->has('student_search') && $request->student_search) {
            $search = $request->student_search;
            $studentQuery->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('grade_level', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%");
            });
        }

        if ($request->has('grade_level') && $request->grade_level) {
            $studentQuery->where('grade_level', $request->grade_level);
        }

        if ($request->has('academic_year') && $request->academic_year) {
            $studentQuery->where('academic_year', $request->academic_year);
        }

        if ($request->has('enrollment_status') && $request->enrollment_status) {
            $studentQuery->where('enrollment_status', $request->enrollment_status);
        }

        $studentQuery->orderBy('created_date', 'desc');

        $studentsPerPage = $request->input('students_per_page', 10);
        $studentsPerPage = in_array($studentsPerPage, [10, 25, 50, 100]) ? $studentsPerPage : 10;

        $students = $studentQuery->paginate($studentsPerPage, ['*'], 'student_page');

        $academicYears = Student::distinct()->pluck('academic_year')->filter()->sort()->reverse()->values();

        $parentsList = ParentProfile::with('user')->get()->map(function($parent) {
            $name = $parent->first_name . ' ' . $parent->last_name;
            if ($parent->user) {
                $name = $parent->user->first_name . ' ' . $parent->user->last_name;
            }
            return [
                'id' => $parent->parentID,
                'name' => $name,
                'email' => $parent->email,
            ];
        })->sortBy('name')->values();

        return view('administrator.users', [
            'users' => $users,
            'students' => $students,
            'academicYears' => $academicYears,
            'parentsList' => $parentsList,
            'routePrefix' => 'principal',
            'layout' => 'layouts.pr-sidebar'
        ]);
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

        $user = User::create([
            'username' => $username,
            'password_hash' => Hash::make($request->password),
            'plain_password' => $request->password,
            'user_type' => $request->role,
            'email' => $request->email,
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_active' => true,
        ]);

        // Log the account creation activity
        SecurityAuditLog::logActivity(
            Auth::user()->userID,
            'create_user_account',
            'users',
            $user->userID,
            null,
            [
                'created_user' => $user->first_name . ' ' . $user->last_name,
                'username' => $username,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'created_by_admin' => true
            ],
            true,
            null
        );

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

            // Store old values for logging
            $oldValues = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_type' => $user->user_type,
                'is_active' => $user->is_active
            ];

            // Update user data
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->user_type = $request->user_type;
            $user->is_active = $request->is_active;

            $passwordChanged = false;
            // Update password if provided
            if ($request->filled('password')) {
                $user->password_hash = Hash::make($request->password);
                $user->plain_password = $request->password;
                $passwordChanged = true;
            }

            $user->save();

            // Log the user update activity
            $newValues = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_type' => $user->user_type,
                'is_active' => $user->is_active,
                'password_changed' => $passwordChanged
            ];

            SecurityAuditLog::logActivity(
                Auth::user()->userID,
                'update_user_account',
                'users',
                $user->userID,
                $oldValues,
                $newValues,
                true,
                null
            );

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
     * Delete a user.
     */
    public function deleteUser($id)
    {
        try {
            // Find user by userID (our custom primary key)
            $user = User::where('userID', $id)->firstOrFail();

            // Prevent deletion of the currently logged-in user
            if ($user->userID === Auth::user()->userID) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account.'
                ], 422);
            }

            // Store user data for logging before deletion
            $userData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_type' => $user->user_type,
                'is_active' => $user->is_active
            ];

            // Delete the user
            $user->delete();

            // Log the user deletion activity
            SecurityAuditLog::logActivity(
                Auth::user()->userID,
                'delete_user_account',
                'users',
                $id,
                $userData,
                null,
                true,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the users page for administrators.
     */
    public function adminUsers(Request $request)
    {
        $query = User::query();

        // Apply search filter - searches across name, email, phone, and address (for parents)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search in users table
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  // Search in parent profile for address fields
                  ->orWhereHas('parentProfile', function($pq) use ($search) {
                      $pq->where('street_address', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('barangay', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
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

        // Get per page value for users (default 10)
        $usersPerPage = $request->input('users_per_page', 10);
        $usersPerPage = in_array($usersPerPage, [10, 25, 50, 100]) ? $usersPerPage : 10;

        $users = $query->paginate($usersPerPage);

        // Query students
        $studentQuery = Student::with(['parents.user']);

        // Apply student search filter
        if ($request->has('student_search') && $request->student_search) {
            $search = $request->student_search;
            $studentQuery->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('grade_level', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%");
            });
        }

        // Apply grade level filter
        if ($request->has('grade_level') && $request->grade_level) {
            $studentQuery->where('grade_level', $request->grade_level);
        }

        // Apply academic year filter
        if ($request->has('academic_year') && $request->academic_year) {
            $studentQuery->where('academic_year', $request->academic_year);
        }

        // Apply enrollment status filter
        if ($request->has('enrollment_status') && $request->enrollment_status) {
            $studentQuery->where('enrollment_status', $request->enrollment_status);
        }

        // Order by created_date (newest first)
        $studentQuery->orderBy('created_date', 'desc');

        // Get per page value for students (default 10)
        $studentsPerPage = $request->input('students_per_page', 10);
        $studentsPerPage = in_array($studentsPerPage, [10, 25, 50, 100]) ? $studentsPerPage : 10;

        $students = $studentQuery->paginate($studentsPerPage, ['*'], 'student_page');

        // Get distinct academic years for filter dropdown
        $academicYears = Student::distinct()->pluck('academic_year')->filter()->sort()->reverse()->values();

        // Get parents for dropdown (sorted alphabetically)
        $parentsList = ParentProfile::with('user')->get()->map(function($parent) {
            $name = $parent->first_name . ' ' . $parent->last_name;
            if ($parent->user) {
                $name = $parent->user->first_name . ' ' . $parent->user->last_name;
            }
            return [
                'id' => $parent->parentID,
                'name' => $name,
                'email' => $parent->email,
            ];
        })->sortBy('name')->values();

        return view('administrator.users', compact('users', 'students', 'academicYears', 'parentsList'));
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

            // Store old values for logging
            $oldValues = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_type' => $user->user_type,
                'is_active' => $user->is_active
            ];

            // Update user data
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->user_type = $request->user_type;
            $user->is_active = $request->is_active;

            $passwordChanged = false;
            // Update password if provided
            if ($request->filled('password')) {
                $user->password_hash = Hash::make($request->password);
                $user->plain_password = $request->password;
                $passwordChanged = true;
            }

            $user->save();

            // Log the user update activity
            $newValues = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_type' => $user->user_type,
                'is_active' => $user->is_active,
                'password_changed' => $passwordChanged,
                'updated_by_admin' => true
            ];

            SecurityAuditLog::logActivity(
                Auth::user()->userID,
                'admin_update_user_account',
                'users',
                $user->userID,
                $oldValues,
                $newValues,
                true,
                null
            );

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
     * Delete a user (Admin function).
     */
    public function adminDeleteUser($id)
    {
        try {
            // Find user by userID (our custom primary key)
            $user = User::where('userID', $id)->firstOrFail();

            // Prevent deletion of the currently logged-in user
            if ($user->userID === Auth::user()->userID) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account.'
                ], 422);
            }

            // Store user data for logging before deletion
            $userData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_type' => $user->user_type,
                'is_active' => $user->is_active
            ];

            // Delete the user
            $user->delete();

            // Log the user deletion activity
            SecurityAuditLog::logActivity(
                Auth::user()->userID,
                'admin_delete_user_account',
                'users',
                $id,
                $userData,
                null,
                true,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get students list (JSON) for administrator.
     */
    public function adminStudents(Request $request)
    {
        $query = Student::with(['parents.user']);

        // Apply search filter
        if ($request->has('student_search') && $request->student_search) {
            $search = $request->student_search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('grade_level', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%");
            });
        }

        // Apply grade level filter
        if ($request->has('grade_level') && $request->grade_level) {
            $query->where('grade_level', $request->grade_level);
        }

        // Apply academic year filter
        if ($request->has('academic_year') && $request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        // Apply enrollment status filter
        if ($request->has('enrollment_status') && $request->enrollment_status) {
            $query->where('enrollment_status', $request->enrollment_status);
        }

        // Order by created_date (newest first)
        $query->orderBy('created_date', 'desc');

        $students = $query->paginate(10, ['*'], 'student_page');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        }

        return response()->json([
            'success' => true,
            'students' => $students
        ]);
    }

    /**
     * Store a new student (Admin function).
     */
    public function adminStoreStudent(Request $request)
    {
        try {
            $request->validate([
                'student_name' => ['required', 'string', 'max:150'],
                'grade_level' => ['required', 'string', 'max:20'],
                'section' => ['nullable', 'string', 'max:50'],
                'academic_year' => ['required', 'string', 'max:20'],
                'enrollment_date' => ['required', 'date'],
                'birth_date' => ['nullable', 'date'],
                'gender' => ['required', 'in:male,female'],
                'enrollment_status' => ['required', 'in:active,transferred,graduated,dropped'],
                'parent_id' => ['nullable', 'exists:parents,parentID'],
                'relationship_type' => ['nullable', 'in:mother,father,guardian,grandparent,sibling,other'],
                'is_primary_contact' => ['nullable', 'boolean'],
            ]);

            DB::beginTransaction();

            $student = Student::create([
                'student_name' => $request->student_name,
                'grade_level' => $request->grade_level,
                'section' => $request->section,
                'academic_year' => $request->academic_year,
                'enrollment_date' => $request->enrollment_date,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'enrollment_status' => $request->enrollment_status,
            ]);

            // Link to parent if provided
            if ($request->parent_id) {
                DB::table('parent_student_relationships')->insert([
                    'parentID' => $request->parent_id,
                    'studentID' => $student->studentID,
                    'relationship_type' => $request->relationship_type ?? 'guardian',
                    'is_primary_contact' => $request->is_primary_contact ?? true,
                    'created_date' => now(),
                ]);
            }

            DB::commit();

            // Log the student creation
            SecurityAuditLog::logActivity(
                Auth::user()->userID,
                'admin_create_student',
                'students',
                $student->studentID,
                null,
                $student->toArray(),
                true,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully!',
                'student' => $student->load('parents.user')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing student (Admin function).
     */
    public function adminUpdateStudent(Request $request, $id)
    {
        try {
            $student = Student::where('studentID', $id)->firstOrFail();

            $request->validate([
                'student_name' => ['required', 'string', 'max:150'],
                'grade_level' => ['required', 'string', 'max:20'],
                'section' => ['nullable', 'string', 'max:50'],
                'academic_year' => ['required', 'string', 'max:20'],
                'enrollment_date' => ['required', 'date'],
                'birth_date' => ['nullable', 'date'],
                'gender' => ['required', 'in:male,female'],
                'enrollment_status' => ['required', 'in:active,transferred,graduated,dropped'],
                'parent_id' => ['nullable', 'exists:parents,parentID'],
                'relationship_type' => ['nullable', 'in:mother,father,guardian,grandparent,sibling,other'],
                'is_primary_contact' => ['nullable', 'boolean'],
            ]);

            $oldData = $student->toArray();

            DB::beginTransaction();

            $student->update([
                'student_name' => $request->student_name,
                'grade_level' => $request->grade_level,
                'section' => $request->section,
                'academic_year' => $request->academic_year,
                'enrollment_date' => $request->enrollment_date,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'enrollment_status' => $request->enrollment_status,
            ]);

            // Update parent relationship if provided
            if ($request->has('parent_id') && $request->parent_id) {
                // Check if relationship exists
                $existingRelation = DB::table('parent_student_relationships')
                    ->where('studentID', $student->studentID)
                    ->where('parentID', $request->parent_id)
                    ->first();

                if (!$existingRelation) {
                    DB::table('parent_student_relationships')->insert([
                        'parentID' => $request->parent_id,
                        'studentID' => $student->studentID,
                        'relationship_type' => $request->relationship_type ?? 'guardian',
                        'is_primary_contact' => $request->is_primary_contact ?? false,
                        'created_date' => now(),
                    ]);
                } else {
                    DB::table('parent_student_relationships')
                        ->where('studentID', $student->studentID)
                        ->where('parentID', $request->parent_id)
                        ->update([
                            'relationship_type' => $request->relationship_type ?? $existingRelation->relationship_type,
                            'is_primary_contact' => $request->is_primary_contact ?? $existingRelation->is_primary_contact,
                        ]);
                }
            }

            DB::commit();

            // Log the student update
            SecurityAuditLog::logActivity(
                Auth::user()->userID,
                'admin_update_student',
                'students',
                $student->studentID,
                $oldData,
                $student->fresh()->toArray(),
                true,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully!',
                'student' => $student->fresh()->load('parents.user')
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a student (Admin function).
     */
    public function adminDeleteStudent($id)
    {
        try {
            $student = Student::where('studentID', $id)->firstOrFail();

            $studentData = $student->toArray();

            DB::beginTransaction();

            // Delete parent-student relationships first
            DB::table('parent_student_relationships')->where('studentID', $id)->delete();

            // Delete the student
            $student->delete();

            DB::commit();

            // Log the student deletion
            SecurityAuditLog::logActivity(
                Auth::user()->userID,
                'admin_delete_student',
                'students',
                $id,
                $studentData,
                null,
                true,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully!'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transfer a student to a new academic year (Admin function).
     */
    public function adminTransferStudent(Request $request, $id)
    {
        try {
            $student = Student::where('studentID', $id)->firstOrFail();

            $request->validate([
                'new_academic_year' => ['required', 'string', 'max:20'],
                'new_grade_level' => ['required', 'string', 'max:20'],
                'new_section' => ['nullable', 'string', 'max:50'],
                'new_enrollment_status' => ['nullable', 'in:active,transferred,graduated,dropped'],
            ]);

            $oldData = $student->toArray();

            $student->update([
                'academic_year' => $request->new_academic_year,
                'grade_level' => $request->new_grade_level,
                'section' => $request->new_section,
                'enrollment_status' => $request->new_enrollment_status ?? 'active',
            ]);

            // Log the student transfer
            SecurityAuditLog::logActivity(
                Auth::user()->userID,
                'admin_transfer_student',
                'students',
                $student->studentID,
                $oldData,
                $student->fresh()->toArray(),
                true,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'Student transferred successfully!',
                'student' => $student->fresh()->load('parents.user')
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error transferring student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk transfer students to a new academic year (Admin function).
     */
    public function adminBulkTransferStudents(Request $request)
    {
        try {
            $request->validate([
                'student_ids' => ['required', 'array', 'min:1'],
                'student_ids.*' => ['required', 'exists:students,studentID'],
                'new_academic_year' => ['required', 'string', 'max:20'],
                'promote_grade' => ['nullable', 'boolean'],
            ]);

            $gradeMapping = [
                'Kinder' => 'Grade 1',
                'Grade 1' => 'Grade 2',
                'Grade 2' => 'Grade 3',
                'Grade 3' => 'Grade 4',
                'Grade 4' => 'Grade 5',
                'Grade 5' => 'Grade 6',
                'Grade 6' => 'Graduated',
            ];

            DB::beginTransaction();

            $transferredCount = 0;
            $graduatedCount = 0;

            foreach ($request->student_ids as $studentId) {
                $student = Student::where('studentID', $studentId)->first();
                if ($student) {
                    $oldData = $student->toArray();
                    $newGradeLevel = $student->grade_level;

                    if ($request->promote_grade && isset($gradeMapping[$student->grade_level])) {
                        $newGradeLevel = $gradeMapping[$student->grade_level];
                    }

                    $newStatus = 'active';
                    if ($newGradeLevel === 'Graduated') {
                        $newStatus = 'graduated';
                        $graduatedCount++;
                    }

                    $student->update([
                        'academic_year' => $request->new_academic_year,
                        'grade_level' => $newGradeLevel === 'Graduated' ? 'Grade 6' : $newGradeLevel,
                        'enrollment_status' => $newStatus,
                    ]);

                    // Log each transfer
                    SecurityAuditLog::logActivity(
                        Auth::user()->userID,
                        'admin_bulk_transfer_student',
                        'students',
                        $student->studentID,
                        $oldData,
                        $student->fresh()->toArray(),
                        true,
                        null
                    );

                    $transferredCount++;
                }
            }

            DB::commit();

            $message = "Successfully transferred {$transferredCount} student(s).";
            if ($graduatedCount > 0) {
                $message .= " {$graduatedCount} student(s) marked as graduated.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'transferred_count' => $transferredCount,
                'graduated_count' => $graduatedCount
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error transferring students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of parents for dropdown (Admin function).
     */
    public function adminGetParentsList(Request $request)
    {
        $query = ParentProfile::with('user');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $parents = $query->limit(50)->get()->map(function($parent) {
            $name = $parent->first_name . ' ' . $parent->last_name;
            if ($parent->user) {
                $name = $parent->user->first_name . ' ' . $parent->user->last_name;
            }
            return [
                'id' => $parent->parentID,
                'name' => $name,
                'email' => $parent->email,
            ];
        });

        return response()->json([
            'success' => true,
            'parents' => $parents
        ]);
    }
}
