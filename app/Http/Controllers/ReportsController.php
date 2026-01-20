<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        // Get recent activity stats
        $recentActivityCount = SecurityAuditLog::where('timestamp', '>=', now()->subDays(7))->count();
        $failedActionsCount = SecurityAuditLog::where('timestamp', '>=', now()->subDays(7))
            ->where('success', false)->count();
        $uniqueUsersCount = SecurityAuditLog::where('timestamp', '>=', now()->subDays(7))
            ->distinct('userID')->count();
        $topActions = SecurityAuditLog::getActionStats(7);

        return view('principal.reports.index', compact(
            'recentActivityCount',
            'failedActionsCount', 
            'uniqueUsersCount',
            'topActions'
        ));
    }

    /**
     * Display user activity logs.
     */
    public function activityLogs(Request $request)
    {
        $filters = $request->only(['user_id', 'action', 'date_from', 'date_to', 'ip_address', 'success']);
        $logs = SecurityAuditLog::getActivityLogs($filters, 20);
        
        // Get all users for filter dropdown
        $users = User::select('userID', 'first_name', 'last_name', 'email')
            ->orderBy('first_name')
            ->get();

        return view('principal.reports.activity-logs', compact('logs', 'users', 'filters'));
    }

    /**
     * Display security logs.
     */
    public function securityLogs(Request $request)
    {
        $filters = $request->only(['user_id', 'action', 'date_from', 'date_to', 'ip_address']);
        
        // Security-specific filters - focus on authentication and authorization events
        $securityActions = ['login', 'logout', 'failed_login', 'password_change', 'account_locked', 'permission_denied'];
        
        $query = SecurityAuditLog::with('user')
            ->whereIn('action', $securityActions)
            ->selectRaw('MAX(logID) as logID, userID, action, ip_address, session_id, timestamp, success, error_message')
            ->groupBy('userID', 'action', 'ip_address', 'session_id', 'timestamp', 'success', 'error_message')
            ->orderBy('timestamp', 'desc');

        // Apply filters
        if (!empty($filters['user_id'])) {
            $query->where('userID', $filters['user_id']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', 'like', '%' . $filters['action'] . '%');
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('timestamp', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('timestamp', '<=', $filters['date_to']);
        }

        if (!empty($filters['ip_address'])) {
            $query->where('ip_address', $filters['ip_address']);
        }

        $logs = $query->paginate(20);
        
        // Get all users for filter dropdown
        $users = User::select('userID', 'first_name', 'last_name', 'email')
            ->orderBy('first_name')
            ->get();

        return view('principal.reports.security-logs', compact('logs', 'users', 'filters'));
    }

    /**
     * Display user activity statistics.
     */
    public function userActivity(Request $request)
    {
        $days = $request->get('days', 30);
        
        // Get user activity statistics
        $userStats = SecurityAuditLog::getUserActivityStats($days);
        $actionStats = SecurityAuditLog::getActionStats($days);
        
        // Get daily activity data for chart
        $dailyActivity = SecurityAuditLog::where('timestamp', '>=', now()->subDays($days))
            ->selectRaw('DATE(timestamp) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get login patterns
        $loginPatterns = SecurityAuditLog::where('action', 'login')
            ->where('timestamp', '>=', now()->subDays($days))
            ->selectRaw('HOUR(timestamp) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('principal.reports.user-activity', compact(
            'userStats',
            'actionStats', 
            'dailyActivity',
            'loginPatterns',
            'days'
        ));
    }

    /**
     * Export activity logs.
     */
    public function exportLogs(Request $request)
    {
        $filters = $request->only(['user_id', 'action', 'date_from', 'date_to', 'ip_address', 'success']);
        
        $logs = SecurityAuditLog::with('user')
            ->when(!empty($filters['user_id']), function ($query) use ($filters) {
                return $query->where('userID', $filters['user_id']);
            })
            ->when(!empty($filters['action']), function ($query) use ($filters) {
                return $query->where('action', 'like', '%' . $filters['action'] . '%');
            })
            ->when(!empty($filters['date_from']), function ($query) use ($filters) {
                return $query->whereDate('timestamp', '>=', $filters['date_from']);
            })
            ->when(!empty($filters['date_to']), function ($query) use ($filters) {
                return $query->whereDate('timestamp', '<=', $filters['date_to']);
            })
            ->when(!empty($filters['ip_address']), function ($query) use ($filters) {
                return $query->where('ip_address', $filters['ip_address']);
            })
            ->when(!empty($filters['success']), function ($query) use ($filters) {
                return $query->where('success', $filters['success'] === 'true');
            })
            ->orderBy('timestamp', 'desc')
            ->limit(10000) // Limit for performance
            ->get();

        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Timestamp',
                'User',
                'Action', 
                'Table Affected',
                'Record ID',
                'IP Address',
                'User Agent',
                'Success',
                'Error Message'
            ]);

            // Add data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->timestamp->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'Unknown User',
                    $log->action,
                    $log->table_affected,
                    $log->record_id,
                    $log->ip_address,
                    $log->user_agent,
                    $log->success ? 'Yes' : 'No',
                    $log->error_message
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
