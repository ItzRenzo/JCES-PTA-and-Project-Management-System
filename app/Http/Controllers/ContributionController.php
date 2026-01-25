<?php

namespace App\Http\Controllers;

use App\Models\ParentProfile;
use App\Models\PaymentTransaction;
use App\Models\Project;
use App\Models\ProjectContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContributionController extends Controller
{
    private function resolveContributionsView(string $view): string
    {
        $user = auth()->user();

        if ($user && $user->user_type === 'administrator') {
            return "administrator.payments.$view";
        }

        return "principal.contributions.$view";
    }

    public function index(Request $request)
    {
        $filters = $request->only(['project_id', 'date_from', 'date_to', 'payment_method', 'payment_status']);

        $contributionsQuery = ProjectContribution::with(['project', 'parent', 'processedBy']);

        if (!empty($filters['project_id'])) {
            $contributionsQuery->where('projectID', $filters['project_id']);
        }

        if (!empty($filters['date_from'])) {
            $contributionsQuery->whereDate('contribution_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $contributionsQuery->whereDate('contribution_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['payment_method'])) {
            $contributionsQuery->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['payment_status'])) {
            $contributionsQuery->where('payment_status', $filters['payment_status']);
        }

        $totalAmount = (clone $contributionsQuery)->sum('contribution_amount');
        $totalCount = (clone $contributionsQuery)->count();

        $contributions = $contributionsQuery
            ->orderBy('contribution_date', 'desc')
            ->paginate(15)
            ->appends($request->all());

        $projects = Project::orderBy('project_name')->get();
        $parents = ParentProfile::orderBy('last_name')->get();
        $paymentMethods = ['cash', 'check', 'bank_transfer'];
        $paymentStatuses = ['pending', 'completed', 'refunded'];

        return view($this->resolveContributionsView('index'), compact(
            'contributions',
            'projects',
            'parents',
            'paymentMethods',
            'paymentStatuses',
            'filters',
            'totalAmount',
            'totalCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => ['required', 'integer'],
            'project_id' => ['required', 'integer'],
            'contribution_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,check,bank_transfer'],
            'contribution_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $receiptNumber = 'RCPT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        $contributionDate = $validated['contribution_date'] ?? now();

        $contribution = ProjectContribution::create([
            'projectID' => $validated['project_id'],
            'parentID' => $validated['parent_id'],
            'contribution_amount' => $validated['contribution_amount'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'completed',
            'contribution_date' => $contributionDate,
            'receipt_number' => $receiptNumber,
            'notes' => $validated['notes'] ?? null,
            'processed_by' => auth()->user()->userID,
        ]);

        PaymentTransaction::create([
            'parentID' => $validated['parent_id'],
            'projectID' => $validated['project_id'],
            'contributionID' => $contribution->contributionID,
            'amount' => $validated['contribution_amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_status' => 'completed',
            'transaction_date' => $contributionDate,
            'receipt_number' => $receiptNumber,
            'reference_number' => null,
            'processed_by' => auth()->user()->userID,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back();
    }

    public function update(Request $request, int $contributionID)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,completed,refunded'],
        ]);

        $contribution = ProjectContribution::where('contributionID', $contributionID)->firstOrFail();
        $contribution->payment_status = $validated['payment_status'];

        if ($validated['payment_status'] === 'completed' && empty($contribution->receipt_number)) {
            $contribution->receipt_number = 'RCPT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        }

        $contribution->save();

        $transaction = PaymentTransaction::where('contributionID', $contributionID)->first();
        if ($transaction) {
            $transaction->transaction_status = $validated['payment_status'] === 'completed' ? 'completed' : $validated['payment_status'];
            $transaction->save();
        }

        return redirect()->back();
    }
}
