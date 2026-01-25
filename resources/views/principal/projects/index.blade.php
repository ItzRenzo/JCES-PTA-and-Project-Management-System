@extends('layouts.pr-sidebar')

@section('title', 'Projects')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="w-full md:w-56">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">All Statuses</option>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}" {{ $status === $option ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $option)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:flex-1">
                <label class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Project name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filter</button>
                <button type="button" id="openCreateProjectModal" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Create</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Target Budget</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Current Amount</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($projects as $project)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $project->project_name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $project->project_status)) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($project->start_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">₱{{ number_format($project->target_budget, 2) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">₱{{ number_format($project->current_amount, 2) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 text-right space-x-2">
                                <a href="{{ route('principal.projects.show', $project->projectID) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                <a href="{{ route('principal.projects.edit', $project->projectID) }}" class="text-green-600 hover:text-green-800">Edit</a>
                                <form method="POST" action="{{ route('principal.projects.destroy', $project->projectID) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Archive this project?')">Archive</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-sm text-gray-500 text-center">No projects found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>

    <!-- Create Project Modal -->
    <div id="createProjectModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Create Project</h2>
                <button type="button" id="closeCreateProjectModal" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>
            <form method="POST" action="{{ route('principal.projects.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Add Photo</label>
                        <input type="file" name="project_photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Project Name</label>
                        <input type="text" name="project_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Time</label>
                        <input type="time" name="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="target_completion_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Budget</label>
                        <input type="number" step="0.01" name="target_budget" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Venue</label>
                        <input type="text" name="venue" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Objective</label>
                    <textarea name="objective" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" id="cancelCreateProjectModal" class="px-4 py-2 text-gray-700 border rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Save Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const openCreateProjectModal = document.getElementById('openCreateProjectModal');
    const closeCreateProjectModal = document.getElementById('closeCreateProjectModal');
    const cancelCreateProjectModal = document.getElementById('cancelCreateProjectModal');
    const createProjectModal = document.getElementById('createProjectModal');

    const openModal = () => {
        createProjectModal.classList.remove('hidden');
        createProjectModal.classList.add('flex');
    };

    const closeModal = () => {
        createProjectModal.classList.add('hidden');
        createProjectModal.classList.remove('flex');
    };

    openCreateProjectModal.addEventListener('click', openModal);
    closeCreateProjectModal.addEventListener('click', closeModal);
    cancelCreateProjectModal.addEventListener('click', closeModal);

    createProjectModal.addEventListener('click', (event) => {
        if (event.target === createProjectModal) {
            closeModal();
        }
    });
</script>
@endsection
