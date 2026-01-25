@extends('layouts.ad-sidebar')

@section('title', 'Edit Project')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Project</h1>
        <p class="text-gray-600 mt-1">Update project details and status.</p>
    </div>

    <form method="POST" action="{{ route('administrator.projects.update', $project->projectID) }}" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700">Project Name</label>
            <input type="text" name="project_name" value="{{ old('project_name', $project->project_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description', $project->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Goals</label>
            <textarea name="goals" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('goals', $project->goals) }}</textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Target Budget</label>
                <input type="number" step="0.01" name="target_budget" value="{{ old('target_budget', $project->target_budget) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Target Completion</label>
                <input type="date" name="target_completion_date" value="{{ old('target_completion_date', optional($project->target_completion_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Project Status</label>
                <select name="project_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}" {{ old('project_status', $project->project_status) === $option ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $option)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Actual Completion Date</label>
                <input type="date" name="actual_completion_date" value="{{ old('actual_completion_date', optional($project->actual_completion_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('administrator.projects.show', $project->projectID) }}" class="px-4 py-2 text-gray-700 border rounded-md">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Save Changes</button>
        </div>
    </form>
</div>
@endsection
