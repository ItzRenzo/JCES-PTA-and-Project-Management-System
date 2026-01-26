<?php

namespace App\Http\Controllers;

use App\Models\ParentProfile;
use App\Models\PaymentTransaction;
use App\Models\Project;
use App\Models\ProjectContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContributionController extends Controller
{
    private function resolveContributionsView(string $view): string
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user && $user->user_type === 'administrator') {
            return "administrator.payments.$view";
        }

        return "principal.contributions.$view";
    }

    public function index(Request $request)
    {
        $filters = $request->only(['project_id', 'date_from', 'date_to', 'payment_method', 'payment_status', 'search', 'status', 'school_year', 'date_range']);

        $contributionsQuery = ProjectContribution::with(['project', 'parent', 'processedBy']);

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $contributionsQuery->where(function($q) use ($search) {
                $q->whereHas('parent', function($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhereHas('project', function($prq) use ($search) {
                    $prq->where('project_name', 'like', "%{$search}%");
                });
            });
        }

        // Status filter (from dropdown)
        if (!empty($filters['status'])) {
            $contributionsQuery->where('payment_status', $filters['status']);
        }

        // Date range filter (Today, This Week, This Month, This Year)
        if (!empty($filters['date_range'])) {
            $now = now();
            switch ($filters['date_range']) {
                case 'today':
                    $contributionsQuery->whereDate('contribution_date', $now->toDateString());
                    break;
                case 'this_week':
                    $contributionsQuery->whereBetween('contribution_date', [
                        $now->startOfWeek()->toDateString(),
                        $now->copy()->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'this_month':
                    $contributionsQuery->whereMonth('contribution_date', $now->month)
                                       ->whereYear('contribution_date', $now->year);
                    break;
                case 'this_year':
                    $contributionsQuery->whereYear('contribution_date', $now->year);
                    break;
            }
        }

        // School year filter
        if (!empty($filters['school_year'])) {
            $years = explode('-', $filters['school_year']);
            if (count($years) === 2) {
                $startYear = (int)$years[0];
                $endYear = (int)$years[1];
                $contributionsQuery->where(function($q) use ($startYear, $endYear) {
                    $q->whereYear('contribution_date', $startYear)
                      ->whereMonth('contribution_date', '>=', 6) // June onwards
                      ->orWhere(function($q2) use ($endYear) {
                          $q2->whereYear('contribution_date', $endYear)
                             ->whereMonth('contribution_date', '<=', 5); // Until May
                      });
                });
            }
        }

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
            ->paginate(10)
            ->appends($request->all());

        $projects = Project::orderBy('project_name')->get();
        $parents = ParentProfile::orderBy('last_name')->get();
        $paymentMethods = ['cash', 'check', 'bank_transfer'];
        $paymentStatuses = ['pending', 'completed', 'refunded'];

        // Calculate payment per parent for each project
        $totalParents = ParentProfile::count();
        $projectPayments = [];
        foreach ($projects as $project) {
            $paymentPerParent = $totalParents > 0 ? $project->target_budget / $totalParents : 0;
            $projectPayments[$project->projectID] = $paymentPerParent;
        }

        // Generate school year options (current and past 3 years)
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        // If we're in June or later, current school year starts this year
        // If we're before June, current school year started last year
        $startSchoolYear = $currentMonth >= 6 ? $currentYear : $currentYear - 1;
        $schoolYears = [];
        for ($i = 0; $i < 4; $i++) {
            $sy = ($startSchoolYear - $i) . '-' . ($startSchoolYear - $i + 1);
            $schoolYears[] = $sy;
        }

        return view($this->resolveContributionsView('index'), compact(
            'contributions',
            'projects',
            'parents',
            'paymentMethods',
            'paymentStatuses',
            'filters',
            'totalAmount',
            'totalCount',
            'totalParents',
            'projectPayments',
            'schoolYears'
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
            'processed_by' => Auth::user()->userID,
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
            'processed_by' => Auth::user()->userID,
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

    public function receipt(int $contributionID)
    {
        $contribution = ProjectContribution::with(['project', 'parent', 'processedBy'])
            ->where('contributionID', $contributionID)
            ->firstOrFail();

        return view('receipts.print', compact('contribution'));
    }
}
