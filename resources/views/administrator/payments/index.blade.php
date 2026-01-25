@extends('layouts.ad-sidebar')

@section('title', 'Payments')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <form method="GET" class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="relative w-full md:flex-1">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search parent or project"
                    class="w-full pl-9 pr-3 py-2 text-sm rounded-md border border-gray-200 focus:ring-2 focus:ring-green-200 focus:border-green-400"
                />
            </div>
            <div class="w-full md:w-52">
                <select name="status" class="w-full px-4 py-2 text-sm font-semibold bg-green-600 text-white rounded-md focus:ring-2 focus:ring-green-200 focus:outline-none">
                    <option value="" class="text-gray-900">Filter</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }} class="text-gray-900">Paid</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }} class="text-gray-900">Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }} class="text-gray-900">Unpaid</option>
                </select>
            </div>            
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Parent</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($contributions as $contribution)
                        @php
                            $status = $contribution->payment_status;
                            $statusLabel = $status === 'completed' ? 'Paid' : ($status === 'pending' ? 'Pending' : 'Unpaid');
                        @endphp
                        <tr>
                            <td class="px-6 py-3 text-sm text-gray-700">
                                {{ $contribution->parent ? $contribution->parent->first_name . ' ' . $contribution->parent->last_name : 'Unknown Parent' }}
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-900">₱{{ number_format($contribution->contribution_amount, 2) }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ $contribution->project?->project_name ?? 'Unknown Project' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ optional($contribution->contribution_date)->format('m-d-Y') }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ $statusLabel }}</td>
                            <td class="px-6 py-3 text-right">
                                <button type="button" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $contributions->links() }}
        </div>
    </div>
</div>
@endsection
