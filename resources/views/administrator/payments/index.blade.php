@extends('layouts.ad-sidebar')

@section('title', 'Payments')

@section('content')
<div class="space-y-6" x-data="paymentsManager()">
    <div class="bg-white rounded-lg shadow">
        <form method="GET" id="filterForm" class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="relative w-full md:flex-1">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    id="searchInput"
                    value="{{ request('search') }}"
                    placeholder="Search parent or project"
                    class="w-full pl-9 pr-3 py-2 text-sm rounded-md border border-gray-200 focus:ring-2 focus:ring-green-200 focus:border-green-400"
                />
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <!-- Status Filter -->
                <div class="relative">
                    <select name="status" id="statusFilter" onchange="document.getElementById('filterForm').submit()" class="appearance-none px-4 py-2 pr-8 text-sm font-semibold bg-green-600 text-white rounded-md focus:ring-2 focus:ring-green-200 focus:outline-none cursor-pointer min-w-[120px]">
                        <option value="" class="bg-white text-gray-900">Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }} class="bg-white text-gray-900">Paid</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }} class="bg-white text-gray-900">Pending</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }} class="bg-white text-gray-900">Unpaid</option>
                    </select>
                    <span class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
                <!-- Date Range Filter -->
                <div class="relative">
                    <select name="date_range" id="dateRangeFilter" onchange="document.getElementById('filterForm').submit()" class="appearance-none px-4 py-2 pr-8 text-sm font-semibold bg-green-600 text-white rounded-md focus:ring-2 focus:ring-green-200 focus:outline-none cursor-pointer min-w-[130px]">
                        <option value="" class="bg-white text-gray-900">Date Range</option>
                        <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }} class="bg-white text-gray-900">Today</option>
                        <option value="this_week" {{ request('date_range') === 'this_week' ? 'selected' : '' }} class="bg-white text-gray-900">This Week</option>
                        <option value="this_month" {{ request('date_range') === 'this_month' ? 'selected' : '' }} class="bg-white text-gray-900">This Month</option>
                        <option value="this_year" {{ request('date_range') === 'this_year' ? 'selected' : '' }} class="bg-white text-gray-900">This Year</option>
                    </select>
                    <span class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
                <!-- School Year Filter -->
                <div class="relative">
                    <select name="school_year" id="schoolYearFilter" onchange="document.getElementById('filterForm').submit()" class="appearance-none px-4 py-2 pr-8 text-sm font-semibold bg-green-600 text-white rounded-md focus:ring-2 focus:ring-green-200 focus:outline-none cursor-pointer min-w-[140px]">
                        <option value="" class="bg-white text-gray-900">All Years</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy }}" {{ request('school_year') === $sy ? 'selected' : '' }} class="bg-white text-gray-900">S.Y {{ $sy }}</option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
                @if(request()->hasAny(['search', 'status', 'date_range', 'school_year']))
                    <a href="{{ route('administrator.payments.index') }}" class="px-4 py-2 text-sm font-semibold bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        Clear
                    </a>
                @endif
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
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        // Sample data for demonstration (30 entries)
                        $samplePayments = [
                            ['name' => 'Anna Garcia', 'phone' => '09456789012', 'address' => 'Davao City', 'project' => 'Fun Run for a Cause', 'required' => 500, 'paid' => 500, 'date' => '2026-01-27 01:59:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340233'],
                            ['name' => 'Maria Santos', 'phone' => '09123456789', 'address' => 'Manila City', 'project' => 'Fundraising Projects', 'required' => 300, 'paid' => 300, 'date' => '2026-01-27 09:30:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340234'],
                            ['name' => 'Jose Reyes', 'phone' => '09234567890', 'address' => 'Quezon City', 'project' => 'Community and Parent Involvement', 'required' => 200, 'paid' => 200, 'date' => '2026-01-26 14:20:00', 'status' => 'completed', 'method' => 'Cash', 'txn' => 'TXN9450340235'],
                            ['name' => 'Elena Cruz', 'phone' => '09345678901', 'address' => 'Cebu City', 'project' => 'School Supplies Drive', 'required' => 150, 'paid' => 150, 'date' => '2026-01-26 10:15:00', 'status' => 'completed', 'method' => 'Bank Transfer', 'txn' => 'TXN9450340236'],
                            ['name' => 'Roberto Dela Cruz', 'phone' => '09456789012', 'address' => 'Makati City', 'project' => 'Fun Run for a Cause', 'required' => 500, 'paid' => 0, 'date' => '2026-01-25 16:45:00', 'status' => 'pending', 'method' => 'Pending', 'txn' => 'TXN9450340237'],
                            ['name' => 'Carmen Fernandez', 'phone' => '09567890123', 'address' => 'Pasig City', 'project' => 'Fundraising Projects', 'required' => 300, 'paid' => 300, 'date' => '2026-01-25 11:30:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340238'],
                            ['name' => 'Pedro Mendoza', 'phone' => '09678901234', 'address' => 'Taguig City', 'project' => 'Community and Parent Involvement', 'required' => 200, 'paid' => 100, 'date' => '2026-01-24 09:00:00', 'status' => 'pending', 'method' => 'Cash', 'txn' => 'TXN9450340239'],
                            ['name' => 'Rosa Villanueva', 'phone' => '09789012345', 'address' => 'Paranaque City', 'project' => 'School Supplies Drive', 'required' => 150, 'paid' => 150, 'date' => '2026-01-24 14:00:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340240'],
                            ['name' => 'Antonio Ramos', 'phone' => '09890123456', 'address' => 'Las Pinas City', 'project' => 'Fun Run for a Cause', 'required' => 500, 'paid' => 500, 'date' => '2026-01-23 08:30:00', 'status' => 'completed', 'method' => 'Bank Transfer', 'txn' => 'TXN9450340241'],
                            ['name' => 'Lucia Torres', 'phone' => '09901234567', 'address' => 'Muntinlupa City', 'project' => 'Fundraising Projects', 'required' => 300, 'paid' => 0, 'date' => '2026-01-23 15:45:00', 'status' => 'failed', 'method' => 'Failed', 'txn' => 'TXN9450340242'],
                            ['name' => 'Miguel Gonzales', 'phone' => '09112345678', 'address' => 'Caloocan City', 'project' => 'Community and Parent Involvement', 'required' => 200, 'paid' => 200, 'date' => '2026-01-22 10:00:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340243'],
                            ['name' => 'Teresa Aquino', 'phone' => '09223456789', 'address' => 'Valenzuela City', 'project' => 'School Supplies Drive', 'required' => 150, 'paid' => 150, 'date' => '2026-01-22 13:30:00', 'status' => 'completed', 'method' => 'Cash', 'txn' => 'TXN9450340244'],
                            ['name' => 'Ricardo Bautista', 'phone' => '09334567890', 'address' => 'Malabon City', 'project' => 'Fun Run for a Cause', 'required' => 500, 'paid' => 250, 'date' => '2026-01-21 09:15:00', 'status' => 'pending', 'method' => 'GCASH', 'txn' => 'TXN9450340245'],
                            ['name' => 'Sofia Castillo', 'phone' => '09445678901', 'address' => 'Navotas City', 'project' => 'Fundraising Projects', 'required' => 300, 'paid' => 300, 'date' => '2026-01-21 16:00:00', 'status' => 'completed', 'method' => 'Bank Transfer', 'txn' => 'TXN9450340246'],
                            ['name' => 'Fernando Jimenez', 'phone' => '09556789012', 'address' => 'San Juan City', 'project' => 'Community and Parent Involvement', 'required' => 200, 'paid' => 200, 'date' => '2026-01-20 11:45:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340247'],
                            ['name' => 'Isabel Morales', 'phone' => '09667890123', 'address' => 'Mandaluyong City', 'project' => 'School Supplies Drive', 'required' => 150, 'paid' => 0, 'date' => '2026-01-20 08:00:00', 'status' => 'failed', 'method' => 'Failed', 'txn' => 'TXN9450340248'],
                            ['name' => 'Carlos Navarro', 'phone' => '09778901234', 'address' => 'Marikina City', 'project' => 'Fun Run for a Cause', 'required' => 500, 'paid' => 500, 'date' => '2026-01-19 14:30:00', 'status' => 'completed', 'method' => 'Cash', 'txn' => 'TXN9450340249'],
                            ['name' => 'Patricia Ocampo', 'phone' => '09889012345', 'address' => 'Pasay City', 'project' => 'Fundraising Projects', 'required' => 300, 'paid' => 300, 'date' => '2026-01-19 10:20:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340250'],
                            ['name' => 'Daniel Pascual', 'phone' => '09990123456', 'address' => 'Batangas City', 'project' => 'Community and Parent Involvement', 'required' => 200, 'paid' => 100, 'date' => '2026-01-18 15:00:00', 'status' => 'pending', 'method' => 'Bank Transfer', 'txn' => 'TXN9450340251'],
                            ['name' => 'Gloria Quizon', 'phone' => '09101234567', 'address' => 'Laguna', 'project' => 'School Supplies Drive', 'required' => 150, 'paid' => 150, 'date' => '2026-01-18 09:45:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340252'],
                            ['name' => 'Manuel Rivera', 'phone' => '09212345678', 'address' => 'Cavite City', 'project' => 'Fun Run for a Cause', 'required' => 500, 'paid' => 500, 'date' => '2026-01-17 12:30:00', 'status' => 'completed', 'method' => 'Cash', 'txn' => 'TXN9450340253'],
                            ['name' => 'Angelica Salazar', 'phone' => '09323456789', 'address' => 'Bulacan', 'project' => 'Fundraising Projects', 'required' => 300, 'paid' => 0, 'date' => '2026-01-17 16:15:00', 'status' => 'pending', 'method' => 'Pending', 'txn' => 'TXN9450340254'],
                            ['name' => 'Eduardo Tan', 'phone' => '09434567890', 'address' => 'Pampanga', 'project' => 'Community and Parent Involvement', 'required' => 200, 'paid' => 200, 'date' => '2026-01-16 10:00:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340255'],
                            ['name' => 'Victoria Uy', 'phone' => '09545678901', 'address' => 'Tarlac City', 'project' => 'School Supplies Drive', 'required' => 150, 'paid' => 150, 'date' => '2026-01-16 14:45:00', 'status' => 'completed', 'method' => 'Bank Transfer', 'txn' => 'TXN9450340256'],
                            ['name' => 'Benjamin Vera', 'phone' => '09656789012', 'address' => 'Zambales', 'project' => 'Fun Run for a Cause', 'required' => 500, 'paid' => 500, 'date' => '2026-01-15 08:30:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340257'],
                            ['name' => 'Cristina Wong', 'phone' => '09767890123', 'address' => 'Bataan', 'project' => 'Fundraising Projects', 'required' => 300, 'paid' => 150, 'date' => '2026-01-15 11:00:00', 'status' => 'pending', 'method' => 'Cash', 'txn' => 'TXN9450340258'],
                            ['name' => 'Andres Yap', 'phone' => '09878901234', 'address' => 'Nueva Ecija', 'project' => 'Community and Parent Involvement', 'required' => 200, 'paid' => 200, 'date' => '2026-01-14 15:30:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340259'],
                            ['name' => 'Maricel Zamora', 'phone' => '09989012345', 'address' => 'Pangasinan', 'project' => 'School Supplies Drive', 'required' => 150, 'paid' => 0, 'date' => '2026-01-14 09:00:00', 'status' => 'failed', 'method' => 'Failed', 'txn' => 'TXN9450340260'],
                            ['name' => 'Lorenzo Abella', 'phone' => '09190123456', 'address' => 'La Union', 'project' => 'Fun Run for a Cause', 'required' => 500, 'paid' => 500, 'date' => '2026-01-13 13:15:00', 'status' => 'completed', 'method' => 'Bank Transfer', 'txn' => 'TXN9450340261'],
                            ['name' => 'Beatriz Borja', 'phone' => '09291234567', 'address' => 'Ilocos Norte', 'project' => 'Fundraising Projects', 'required' => 300, 'paid' => 300, 'date' => '2026-01-13 10:45:00', 'status' => 'completed', 'method' => 'GCASH', 'txn' => 'TXN9450340262'],
                        ];
                    @endphp

                    @forelse ($contributions as $index => $contribution)
                        @php
                            $status = $contribution->payment_status;
                            $statusLabel = $status === 'completed' ? 'Paid' : ($status === 'pending' ? 'Pending' : 'Unpaid');
                            $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : ($status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            $requiredPayment = isset($projectPayments[$contribution->projectID]) ? $projectPayments[$contribution->projectID] : 0;
                            $sampleIndex = $index % count($samplePayments);
                            $sample = $samplePayments[$sampleIndex];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <input type="checkbox" name="selected[]" value="{{ $contribution->contributionID }}" class="row-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer" />
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-700">
                                {{ $contribution->parent ? $contribution->parent->first_name . ' ' . $contribution->parent->last_name : 'Unknown Parent' }}
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-500">₱{{ number_format($requiredPayment, 2) }}</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">₱{{ number_format($contribution->contribution_amount, 2) }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ $contribution->project?->project_name ?? 'Unknown Project' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ optional($contribution->contribution_date)->format('m-d-Y') }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ $statusLabel }}</td>
                            <td class="px-6 py-3 text-right">
                                @if($contribution->receipt_number)
                                    <a href="{{ route('administrator.payments.receipt', $contribution->contributionID) }}" target="_blank" class="text-green-600 hover:text-green-700 text-sm font-medium">
                                        Print
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <!-- Show sample data when no contributions exist -->
                        @foreach($samplePayments as $index => $payment)
                            @php
                                $status = $payment['status'];
                                $statusLabel = $status === 'completed' ? 'Paid' : ($status === 'pending' ? 'Pending' : 'Unpaid');
                                $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : ($status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <input type="checkbox" name="selected[]" value="{{ $index + 1 }}" class="row-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer" />
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-700">{{ $payment['name'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-500">₱{{ number_format($payment['required'], 2) }}</td>
                                <td class="px-6 py-3 text-sm font-medium text-gray-900">₱{{ number_format($payment['paid'], 2) }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700">{{ $payment['project'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($payment['date'])->format('m-d-Y') }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit Button -->
                                        <button
                                            @click="openEditModal({{ $index + 1 }}, '{{ $status }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-all duration-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <!-- Receipt Button -->
                                        <button
                                            @click="openReceiptModal({
                                                txn: '{{ $payment['txn'] }}',
                                                date: '{{ \Carbon\Carbon::parse($payment['date'])->format('F d, Y \a\t h:i A') }}',
                                                method: '{{ $payment['method'] }}',
                                                name: '{{ $payment['name'] }}',
                                                phone: '{{ $payment['phone'] }}',
                                                address: '{{ $payment['address'] }}',
                                                project: '{{ $payment['project'] }}',
                                                amount: {{ $payment['paid'] }}
                                            })"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-100 hover:text-green-700 transition-all duration-200"
                                            title="Receipt">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </button>
                                        <!-- Archive Button -->
                                        <button
                                            @click="openArchiveModal({{ $index + 1 }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 transition-all duration-200"
                                            title="Archive">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600">
                    @if($contributions->count() > 0)
                        Showing <span class="font-medium text-green-600">{{ $contributions->firstItem() ?? 0 }}</span> to <span class="font-medium text-green-600">{{ $contributions->lastItem() ?? 0 }}</span> of <span class="font-medium text-green-600">{{ $contributions->total() }}</span> results
                    @else
                        Showing <span class="font-medium text-green-600">1</span> to <span class="font-medium text-green-600">30</span> of <span class="font-medium text-green-600">30</span> results
                    @endif
                </div>
                <div class="flex items-center gap-1">
                    @if($contributions->count() > 0)
                        @if ($contributions->onFirstPage())
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">&lt;</span>
                        @else
                            <a href="{{ $contributions->previousPageUrl() }}" class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">&lt;</a>
                        @endif
                        @php
                            $currentPage = $contributions->currentPage();
                            $lastPage = $contributions->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp
                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $currentPage)
                                <span class="px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $contributions->url($page) }}" class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endfor
                        @if ($contributions->hasMorePages())
                            <a href="{{ $contributions->nextPageUrl() }}" class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">&gt;</a>
                        @else
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">&gt;</span>
                        @endif
                    @else
                        <span class="px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">&lt;</span>
                        <span class="px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-md">1</span>
                        <span class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md">2</span>
                        <span class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md">3</span>
                        <span class="px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">&gt;</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Status Modal -->
    <div x-show="showEditModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.self="showEditModal = false"
         style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Edit Payment Status</h3>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form :action="'/administrator/payments/' + editContributionId" method="POST" class="px-6 py-5">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="editStatus === 'completed' ? 'border-green-500 bg-green-50' : 'border-gray-200'">
                            <input type="radio" name="payment_status" value="completed" x-model="editStatus" class="w-4 h-4 text-green-600 focus:ring-green-500">
                            <span class="ml-3 flex items-center gap-2">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                <span class="text-sm text-gray-600">Payment has been received</span>
                            </span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="editStatus === 'pending' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200'">
                            <input type="radio" name="payment_status" value="pending" x-model="editStatus" class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                            <span class="ml-3 flex items-center gap-2">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                <span class="text-sm text-gray-600">Awaiting payment</span>
                            </span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="editStatus === 'failed' ? 'border-red-500 bg-red-50' : 'border-gray-200'">
                            <input type="radio" name="payment_status" value="failed" x-model="editStatus" class="w-4 h-4 text-red-600 focus:ring-red-500">
                            <span class="ml-3 flex items-center gap-2">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Unpaid</span>
                                <span class="text-sm text-gray-600">Payment failed or cancelled</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Archive Modal - Step 1 -->
    <div x-show="showArchiveModal && !showArchiveConfirm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.self="showArchiveModal = false"
         style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Archive</h3>
                <p class="text-sm text-gray-600 text-center mb-6">Are you sure you want to Archive this post?</p>
                <div class="flex gap-3">
                    <button type="button" @click="showArchiveModal = false" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" @click="showArchiveConfirm = true" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Archive
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Modal - Step 2 (Confirmation) -->
    <div x-show="showArchiveModal && showArchiveConfirm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.self="closeArchiveModal()"
         style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600 text-center mb-4">Enter "Archive" to Confirm</p>
                <input
                    type="text"
                    x-model="archiveConfirmText"
                    placeholder="Archive"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400 text-center mb-4"
                />
                <div class="flex gap-3">
                    <button type="button" @click="closeArchiveModal()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="confirmArchive()"
                        :disabled="archiveConfirmText !== 'Archive'"
                        :class="archiveConfirmText === 'Archive' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-300 cursor-not-allowed'"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white rounded-lg">
                        Archive
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div x-show="showReceiptModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.self="showReceiptModal = false"
         style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 transform transition-all max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div id="receiptContent" class="p-6">
                <div class="text-center mb-4">
                    <p class="text-xs text-gray-500 tracking-widest">*** OFFICIAL RECEIPT ***</p>
                    <h2 class="text-xl font-bold text-gray-900 mt-2">JCES PTA</h2>
                    <p class="text-sm text-gray-600">J. Cruz Sr. Elementary School</p>
                    <p class="text-sm text-gray-600">Parent-Teacher Association</p>
                </div>
                <div class="border-t-2 border-dashed border-gray-300 my-4"></div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">TXN #:</span>
                        <span class="font-medium text-gray-900" x-text="receiptData.txn"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date:</span>
                        <span class="font-medium text-gray-900" x-text="receiptData.date"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Payment:</span>
                        <span class="font-medium text-gray-900" x-text="receiptData.method"></span>
                    </div>
                </div>
                <div class="border-t-2 border-dashed border-gray-300 my-4"></div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">PAID BY:</p>
                    <p class="font-semibold text-gray-900" x-text="receiptData.name"></p>
                    <p class="text-sm text-gray-600" x-text="receiptData.phone"></p>
                    <p class="text-sm text-gray-600" x-text="receiptData.address"></p>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">ITEMS:</p>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-900" x-text="receiptData.project"></span>
                        <span class="font-semibold text-gray-900">₱<span x-text="formatCurrency(receiptData.amount)"></span></span>
                    </div>
                </div>
                <div class="border-t-2 border-dashed border-gray-300 my-4"></div>
                <div class="flex justify-between items-center py-2">
                    <span class="font-bold text-gray-900">TOTAL</span>
                    <span class="font-bold text-lg text-gray-900">₱<span x-text="formatCurrency(receiptData.amount)"></span></span>
                </div>
                <div class="text-center mt-6">
                    <p class="text-sm text-green-600">Thank you for your contribution!</p>
                    <div class="border-t-2 border-dashed border-gray-300 my-4"></div>
                    <p class="text-xs text-gray-500">This serves as your Official Receipt</p>
                    <p class="text-xs text-gray-500">Please keep for your records</p>
                </div>
            </div>
            <div class="px-6 pb-6 space-y-3">
                <button @click="downloadReceipt()" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Receipt
                </button>
                <button @click="showReceiptModal = false" class="w-full px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function paymentsManager() {
        return {
            showEditModal: false,
            showArchiveModal: false,
            showArchiveConfirm: false,
            showReceiptModal: false,
            editContributionId: null,
            editStatus: 'pending',
            archiveContributionId: null,
            archiveConfirmText: '',
            receiptData: { txn: '', date: '', method: '', name: '', phone: '', address: '', project: '', amount: 0 },

            openEditModal(id, status) {
                this.editContributionId = id;
                this.editStatus = status;
                this.showEditModal = true;
            },
            openArchiveModal(id) {
                this.archiveContributionId = id;
                this.archiveConfirmText = '';
                this.showArchiveConfirm = false;
                this.showArchiveModal = true;
            },
            closeArchiveModal() {
                this.showArchiveModal = false;
                this.showArchiveConfirm = false;
                this.archiveConfirmText = '';
            },
            confirmArchive() {
                if (this.archiveConfirmText === 'Archive') {
                    alert('Payment archived successfully!');
                    this.closeArchiveModal();
                }
            },
            openReceiptModal(data) {
                this.receiptData = data;
                this.showReceiptModal = true;
            },
            formatCurrency(amount) {
                return new Intl.NumberFormat('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(amount);
            },
            downloadReceipt() {
                const content = document.getElementById('receiptContent').innerHTML;
                const printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Receipt - ' + this.receiptData.txn + '</title><style>body{font-family:Arial,sans-serif;padding:20px;max-width:400px;margin:0 auto}.text-center{text-align:center}.text-xs{font-size:12px}.text-sm{font-size:14px}.text-lg{font-size:18px}.text-xl{font-size:20px}.font-bold{font-weight:bold}.font-semibold{font-weight:600}.font-medium{font-weight:500}.text-gray-500{color:#6b7280}.text-gray-600{color:#4b5563}.text-gray-900{color:#111827}.text-green-600{color:#059669}.border-dashed{border-style:dashed}.border-gray-300{border-color:#d1d5db}.border-t-2{border-top-width:2px}.my-4{margin:16px 0}.mb-4{margin-bottom:16px}.mt-2{margin-top:8px}.mt-6{margin-top:24px}.py-2{padding:8px 0}.space-y-2>*+*{margin-top:8px}.flex{display:flex}.justify-between{justify-content:space-between}.items-center{align-items:center}.tracking-widest{letter-spacing:.1em}.uppercase{text-transform:uppercase}</style></head><body>' + content + '</body></html>');
                printWindow.document.close();
                printWindow.print();
            }
        }
    }
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
    });
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const total = document.querySelectorAll('.row-checkbox').length;
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            document.getElementById('selectAll').checked = total === checked;
            document.getElementById('selectAll').indeterminate = checked > 0 && checked < total;
        });
    });

    // Search input with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });

    // Submit on Enter key
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            document.getElementById('filterForm').submit();
        }
    });
</script>
@endsection
