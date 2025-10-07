@extends('layouts.pr-sidebar')

@section('title', 'Users')

@section('content')
<div class="space-y-6">
    <!-- Hidden CSRF Token for JavaScript -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('principal.users') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">All Roles</option>
                <option value="parent" {{ request('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="administrator" {{ request('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                <option value="principal" {{ request('role') == 'principal' ? 'selected' : '' }}>Principal</option>
            </select>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-12 px-4 py-3 text-left">
                            <input type="checkbox" class="rounded border-gray-300">
                        </th>
                        <th class="w-2/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <input type="checkbox" class="rounded border-gray-300">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-green-600 font-semibold text-sm">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name ?? 'User')[1] ?? explode(' ', $user->name ?? 'User')[0], 0, 1)) }}
                                    </span>
                                </div>
                                <div class="ml-3 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 truncate">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->user_type == 'parent') bg-blue-100 text-blue-800
                                @elseif($user->user_type == 'teacher') bg-green-100 text-green-800
                                @elseif($user->user_type == 'administrator') bg-purple-100 text-purple-800
                                @elseif($user->user_type == 'principal') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($user->user_type ?? 'Unknown') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->created_date ? $user->created_date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-1">
                                <button onclick="openEditModal({{ json_encode($user) }})" 
                                        class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors duration-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="openViewModal({{ json_encode($user) }})" 
                                        class="p-1 text-green-600 hover:text-green-900 hover:bg-green-50 rounded transition-colors duration-200" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteUser({{ $user->userID }})" 
                                        class="p-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition-colors duration-200" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <button class="p-1 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors duration-200" title="More options">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-lg font-medium">No users found</p>
                                <p class="text-sm text-gray-400 mt-1">Try adjusting your search filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
                <a href="{{ $users->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $users->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-full px-4 py-6">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Edit User Details</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="editUserForm" class="mt-6" onsubmit="submitEditForm(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="user_id" value="">
                <div class="space-y-4">
                    <!-- Common Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="editFullName" name="full_name" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="editEmail" name="email" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" id="editPhone" name="phone" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="editRole" name="role" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="parent">Parent</option>
                            <option value="teacher">Teacher</option>
                            <option value="administrator">Administrator</option>
                            <option value="principal">Principal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="editStatus" name="status" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Role-specific fields -->
                    <div id="parentFields" class="hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea id="editAddress" name="address" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>
                    </div>

                    <div id="teacherFields" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" id="editSubject" name="subject" 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" id="editDepartment" name="department" 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div id="adminFields" class="hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Level</label>
                            <select id="editAdminLevel" name="admin_level" 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="junior">Junior Admin</option>
                                <option value="senior">Senior Admin</option>
                                <option value="super">Super Admin</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password (optional)</label>
                        <input type="password" id="editPassword" name="password" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="Leave blank to keep current">
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-200">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- View User Details Modal -->
<div id="viewUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
  <div class="flex items-center justify-center min-h-full px-4 py-6">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl p-6">
      <div class="flex items-center justify-between pb-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">User Details</h3>
        <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
          <div id="viewFullName" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <div id="viewEmail" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
          <div id="viewPhone" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
          <div id="viewRole" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <div id="viewStatus" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Created Date</label>
          <div id="viewCreatedDate" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
        </div>

        <!-- Role-specific display fields (span full width when present) -->
        <div id="viewParentInfo" class="hidden md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
          <div id="viewAddress" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
        </div>

        <div id="viewTeacherInfo" class="hidden md:col-span-2 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
            <div id="viewSubject" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
            <div id="viewDepartment" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
          </div>
        </div>

        <div id="viewAdminInfo" class="hidden md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">Admin Level</label>
          <div id="viewAdminLevel" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-900"></div>
        </div>
      </div>

      <div class="flex items-center justify-center pt-6 border-t mt-6">
        <button type="button" onclick="closeViewModal()"
                class="px-6 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-200">
          Close
        </button>
      </div>
    </div>
  </div>
</div>



<script>
function openAddUserModal() {
    // For now, this is a placeholder function
    // You can implement a modal or redirect to a create user page
    alert('Add User functionality will be implemented here');
    // Example: window.location.href = '/principal/users/create';
    // Or open a modal dialog
}

// Edit User Modal Functions
let currentEditingUserId = null;

function openEditModal(user) {
    currentEditingUserId = user.userID;
    
    // Set the hidden user ID field
    document.getElementById('editUserId').value = user.userID;
    
    // Populate the common form fields - combine first_name and last_name
    const fullName = `${user.first_name || ''} ${user.last_name || ''}`.trim();
    document.getElementById('editFullName').value = fullName;
    document.getElementById('editEmail').value = user.email || '';
    document.getElementById('editPhone').value = user.phone || '';
    document.getElementById('editRole').value = user.user_type || '';
    document.getElementById('editStatus').value = user.is_active ? '1' : '0';
    
    // Clear password field
    document.getElementById('editPassword').value = '';
    
    // Show/hide role-specific fields
    showRoleSpecificFields(user.user_type || '', 'edit');
    
    // Show the modal
    document.getElementById('editUserModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editUserModal').classList.add('hidden');
    currentEditingUserId = null;
}

// Form submission function
function submitEditForm(event) {
    event.preventDefault();
    
    const formData = new FormData();
    const userId = document.getElementById('editUserId').value;
    
    // Get form values
    const fullName = document.getElementById('editFullName').value.trim();
    const nameParts = fullName.split(' ');
    const firstName = nameParts[0] || '';
    const lastName = nameParts.slice(1).join(' ') || '';
    
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('_method', 'PUT');
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('email', document.getElementById('editEmail').value);
    formData.append('phone', document.getElementById('editPhone').value);
    formData.append('user_type', document.getElementById('editRole').value);
    formData.append('is_active', document.getElementById('editStatus').value);
    
    const password = document.getElementById('editPassword').value;
    if (password) {
        formData.append('password', password);
    }
    
    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;
    
    // Submit the form
    fetch(`/principal/users/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            alert('User updated successfully!');
            // Close modal
            closeEditModal();
            // Reload the page to show updated data
            window.location.reload();
        } else {
            alert('Error updating user: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMessage = 'Error updating user. Please try again.';
        
        if (error.errors) {
            // Show validation errors
            const errors = Object.values(error.errors).flat();
            errorMessage = 'Validation errors:\n' + errors.join('\n');
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        alert(errorMessage);
    })
    .finally(() => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// View User Modal Functions
function openViewModal(user) {
    // Populate the view with user data - combine first_name and last_name
    const fullName = `${user.first_name || ''} ${user.last_name || ''}`.trim() || 'N/A';
    document.getElementById('viewFullName').textContent = fullName;
    document.getElementById('viewEmail').textContent = user.email || 'N/A';
    document.getElementById('viewPhone').textContent = user.phone || 'N/A';
    document.getElementById('viewRole').textContent = (user.user_type || '').charAt(0).toUpperCase() + (user.user_type || '').slice(1);
    document.getElementById('viewStatus').textContent = user.is_active ? 'Active' : 'Inactive';
    document.getElementById('viewCreatedDate').textContent = user.created_date ? new Date(user.created_date).toLocaleDateString() : 'N/A';
    
    // Show/hide role-specific fields
    showRoleSpecificFields(user.user_type || '', 'view');
    
    // Show the modal
    document.getElementById('viewUserModal').classList.remove('hidden');
}

function closeViewModal() {
    document.getElementById('viewUserModal').classList.add('hidden');
}

// Role-specific fields management
function showRoleSpecificFields(role, mode) {
    const prefix = mode === 'edit' ? 'edit' : 'view';
    
    // Hide all role-specific sections
    const roleFields = ['parentFields', 'teacherFields', 'adminFields'];
    const viewRoleFields = ['viewParentInfo', 'viewTeacherInfo', 'viewAdminInfo'];
    
    if (mode === 'edit') {
        roleFields.forEach(field => {
            document.getElementById(field).classList.add('hidden');
        });
    } else {
        viewRoleFields.forEach(field => {
            document.getElementById(field).classList.add('hidden');
        });
    }
    
    // Show relevant fields based on role
    switch(role) {
        case 'parent':
            if (mode === 'edit') {
                document.getElementById('parentFields').classList.remove('hidden');
            } else {
                document.getElementById('viewParentInfo').classList.remove('hidden');
                document.getElementById('viewAddress').textContent = 'N/A'; // Placeholder
            }
            break;
        case 'teacher':
            if (mode === 'edit') {
                document.getElementById('teacherFields').classList.remove('hidden');
            } else {
                document.getElementById('viewTeacherInfo').classList.remove('hidden');
                document.getElementById('viewSubject').textContent = 'N/A'; // Placeholder
                document.getElementById('viewDepartment').textContent = 'N/A'; // Placeholder
            }
            break;
        case 'administrator':
        case 'principal':
            if (mode === 'edit') {
                document.getElementById('adminFields').classList.remove('hidden');
            } else {
                document.getElementById('viewAdminInfo').classList.remove('hidden');
                document.getElementById('viewAdminLevel').textContent = role === 'principal' ? 'Principal' : 'Administrator';
            }
            break;
    }
}

// Role change handler for edit modal
document.getElementById('editRole').addEventListener('change', function() {
    showRoleSpecificFields(this.value, 'edit');
});

// Delete User Function
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;

        // Find the delete button that was clicked
        const deleteButtons = document.querySelectorAll(`button[onclick="deleteUser(${userId})"]`);
        const deleteBtn = deleteButtons[0];
        
        if (deleteBtn) {
            // Show loading state
            const originalHtml = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
            deleteBtn.disabled = true;
        }

        // Send DELETE request to backend
        fetch(`/principal/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert(data.message || 'User deleted successfully!');
                // Reload the page to show updated list
                location.reload();
            } else {
                // Show error message
                alert(data.message || 'Error deleting user. Please try again.');
                
                // Reset button state
                if (deleteBtn) {
                    deleteBtn.innerHTML = originalHtml;
                    deleteBtn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the user. Please try again.');
            
            // Reset button state
            if (deleteBtn) {
                deleteBtn.innerHTML = originalHtml;
                deleteBtn.disabled = false;
            }
        });
    }
}

// Form submission handler
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentEditingUserId) {
        alert('Error: No user selected for editing');
        return;
    }
    
    // Get form data
    const formData = new FormData(this);
    formData.append('userID', currentEditingUserId);
    
    // Convert FormData to regular object for display
    const formDataObj = Object.fromEntries(formData);
    
    // Here you would typically send the data to your backend
    console.log('Form data for user ID', currentEditingUserId, ':', formDataObj);
    
    // Example of how you would implement the actual update:
    // fetch(`/principal/users/${currentEditingUserId}`, {
    //     method: 'PUT',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //     },
    //     body: JSON.stringify(formDataObj)
    // })
    // .then(response => response.json())
    // .then(data => {
    //     if (data.success) {
    //         closeEditModal();
    //         location.reload(); // Reload to show updated data
    //     } else {
    //         alert('Error updating user: ' + data.message);
    //     }
    // })
    // .catch(error => {
    //     console.error('Error:', error);
    //     alert('An error occurred while updating the user');
    // });
    
    // Close the modal for now
    closeEditModal();
});

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const editModal = document.getElementById('editUserModal');
    const viewModal = document.getElementById('viewUserModal');
    
    if (event.target === editModal) {
        closeEditModal();
    }
    if (event.target === viewModal) {
        closeViewModal();
    }
});
</script>
@endsection
