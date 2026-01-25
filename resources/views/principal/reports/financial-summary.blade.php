@extends('layouts.pr-sidebar')

@section('title', 'Financial Summary')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Financial Summary</h1>
                <p class="text-gray-600 mt-1">Review transactions by date range and payment method.</p>
            </div>
            <div>
                <a href="{{ route('principal.reports.financial-export', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Export CSV</a>
            </div>
        </div>

        <form method="GET" class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                <select name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">All Methods</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method }}" {{ $paymentMethod === $method ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $method)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Apply Filters</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Total Transactions</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalTransactions) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Total Amount</p>
            <p class="text-3xl font-bold text-green-600">₱{{ number_format($totalAmount, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Totals by Payment Method</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($methodTotals as $row)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $row->payment_method)) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right">₱{{ number_format($row->total, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($row->count) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-sm text-gray-500 text-center">No payment data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Totals by Project</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($projectTotals as $row)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $row->project?->project_name ?? 'Unknown Project' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right">₱{{ number_format($row->total, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($row->count) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-sm text-gray-500 text-center">No project totals available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($transaction->transaction_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                {{ $transaction->parent ? $transaction->parent->first_name . ' ' . $transaction->parent->last_name : 'Unknown Parent' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $transaction->project?->project_name ?? 'Unknown Project' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">₱{{ number_format($transaction->amount, 2) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $transaction->receipt_number }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-sm text-gray-500 text-center">No transactions found for the selected period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
