@extends('layouts.pr-sidebar')

@section('title', 'Project Details')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $project->project_name }}</h1>
                <p class="text-gray-600 mt-1">Status: {{ $project->project_status === 'created' ? 'Not Started' : ucfirst(str_replace('_', ' ', $project->project_status)) }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if($project->project_status === 'completed')
                    <form method="POST" action="{{ route('principal.projects.approve-closure', $project->projectID) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700" onclick="return confirm('Approve closure and archive this project?')">
                            Approve Closure
                        </button>
                    </form>
                @endif
                <a href="{{ route('principal.projects.edit', $project->projectID) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit Project</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Overview</h2>
            <p class="text-gray-700 mb-4">{{ $project->description }}</p>
            <h3 class="text-sm font-semibold text-gray-700">Goals</h3>
            <p class="text-gray-700 mt-1">{{ $project->goals }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Key Metrics</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Target Budget</p>
                    <p class="text-lg font-semibold text-gray-900">₱{{ number_format($project->target_budget, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Current Amount</p>
                    <p class="text-lg font-semibold text-green-600">₱{{ number_format($project->current_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Start Date</p>
                    <p class="text-sm text-gray-700">{{ optional($project->start_date)->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Target Completion</p>
                    <p class="text-sm text-gray-700">{{ optional($project->target_completion_date)->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Actual Completion</p>
                    <p class="text-sm text-gray-700">{{ optional($project->actual_completion_date)->format('Y-m-d') ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Add Project Update</h2>
        <form method="POST" action="{{ route('principal.projects.updates.store', $project->projectID) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Update Title</label>
                <input type="text" name="update_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Milestone</label>
                <input type="text" name="milestone_achieved" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Progress (%)</label>
                <input type="number" step="0.01" name="progress_percentage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700">Update Description</label>
                <textarea name="update_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
            </div>
            <div class="md:col-span-4 flex items-center justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Post Update</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Updates</h2>
        <div class="space-y-4">
            @forelse ($updates as $update)
                <div class="border border-gray-200 rounded-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">{{ $update->update_title }}</h3>
                            <p class="text-xs text-gray-500">{{ optional($update->update_date)->format('Y-m-d H:i') }}</p>
                        </div>
                        <form method="POST" action="{{ route('principal.projects.updates.destroy', [$project->projectID, $update->updateID]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="return confirm('Delete this update?')">Delete</button>
                        </form>
                    </div>
                    <p class="text-sm text-gray-700 mt-2">{{ $update->update_description }}</p>
                    <div class="mt-2 text-xs text-gray-500">
                        <span>Milestone: {{ $update->milestone_achieved ?? 'N/A' }}</span>
                        <span class="ml-4">Progress: {{ number_format($update->progress_percentage, 2) }}%</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No updates posted yet.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Contributions</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($contributions as $contribution)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($contribution->contribution_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                {{ $contribution->parent ? $contribution->parent->first_name . ' ' . $contribution->parent->last_name : 'Unknown Parent' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">₱{{ number_format($contribution->contribution_amount, 2) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                @if($contribution->receipt_number)
                                    <a href="{{ route('principal.contributions.receipt', $contribution->contributionID) }}" target="_blank" class="text-green-600 hover:text-green-700 text-sm font-medium">
                                        Print
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-sm text-gray-500 text-center">No contributions recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $contributions->links() }}
        </div>
    </div>
</div>
@endsection
