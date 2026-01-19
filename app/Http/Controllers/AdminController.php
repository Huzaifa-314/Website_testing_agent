<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\Report;
use App\Models\Setting;
use App\Models\TestDefinition;
use App\Models\TestRun;
use App\Models\User;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request)
    {
        // Date range filtering
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        
        $dateFromCarbon = Carbon::parse($dateFrom)->startOfDay();
        $dateToCarbon = Carbon::parse($dateTo)->endOfDay();

        // Base query for test runs with date filter
        $testRunsQuery = TestRun::whereBetween('executed_at', [$dateFromCarbon, $dateToCarbon]);
        
        // Overall stats (all time)
        $totalTestRuns = TestRun::count();
        $successfulRuns = TestRun::where('result', 'pass')->count();
        $successRate = $totalTestRuns > 0 ? round(($successfulRuns / $totalTestRuns) * 100, 1) : 0;

        // Filtered stats for date range
        $filteredTestRuns = (clone $testRunsQuery)->count();
        $filteredSuccessfulRuns = (clone $testRunsQuery)->where('result', 'pass')->count();
        $filteredSuccessRate = $filteredTestRuns > 0 ? round(($filteredSuccessfulRuns / $filteredTestRuns) * 100, 1) : 0;

        // Chart data - Success rate over time (last 30 days or selected range)
        $daysDiff = $dateFromCarbon->diffInDays($dateToCarbon);
        $chartInterval = $daysDiff > 90 ? 'week' : ($daysDiff > 30 ? 'day' : 'day');
        
        $trendData = $this->getTrendData($dateFromCarbon, $dateToCarbon, $chartInterval);
        
        // Success/Failure distribution for pie chart
        $distributionData = [
            'pass' => (clone $testRunsQuery)->where('result', 'pass')->count(),
            'fail' => (clone $testRunsQuery)->where('result', 'fail')->count(),
        ];

        // Paginated recent runs
        $recentRuns = TestRun::with(['testCase.testDefinition.website.user'])
            ->whereBetween('executed_at', [$dateFromCarbon, $dateToCarbon])
            ->orderBy('executed_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $defaultDateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $defaultDateTo = Carbon::now()->format('Y-m-d');
        
        $stats = [
            'users' => User::count(),
            'websites' => Website::count(),
            'test_definitions' => TestDefinition::count(),
            'test_runs' => $totalTestRuns,
            'success_rate' => $successRate,
            'filtered_test_runs' => $filteredTestRuns,
            'filtered_success_rate' => $filteredSuccessRate,
            'recent_runs' => $recentRuns,
            'trend_data' => $trendData,
            'distribution_data' => $distributionData,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'default_date_from' => $defaultDateFrom,
            'default_date_to' => $defaultDateTo,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Get trend data for charts.
     */
    private function getTrendData($dateFrom, $dateTo, $interval = 'day')
    {
        $data = [];
        $current = $dateFrom->copy();
        
        while ($current <= $dateTo) {
            $periodEnd = $interval === 'week' 
                ? $current->copy()->endOfWeek() 
                : $current->copy()->endOfDay();
            
            if ($periodEnd > $dateTo) {
                $periodEnd = $dateTo->copy();
            }
            
            $periodRuns = TestRun::whereBetween('executed_at', [$current, $periodEnd])->get();
            $periodTotal = $periodRuns->count();
            $periodPass = $periodRuns->where('result', 'pass')->count();
            $periodSuccessRate = $periodTotal > 0 ? round(($periodPass / $periodTotal) * 100, 1) : 0;
            
            $label = $interval === 'week' 
                ? $current->format('M d') . ' - ' . $periodEnd->format('M d')
                : $current->format('M d');
            
            $data[] = [
                'label' => $label,
                'date' => $current->format('Y-m-d'),
                'total' => $periodTotal,
                'pass' => $periodPass,
                'fail' => $periodTotal - $periodPass,
                'success_rate' => $periodSuccessRate,
            ];
            
            $current = $interval === 'week' 
                ? $current->copy()->addWeek()->startOfWeek()
                : $current->copy()->addDay();
        }
        
        return $data;
    }

    /**
     * Export dashboard data to CSV.
     */
    public function exportDashboard(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        
        $dateFromCarbon = Carbon::parse($dateFrom)->startOfDay();
        $dateToCarbon = Carbon::parse($dateTo)->endOfDay();

        $testRuns = TestRun::with(['testCase.testDefinition.website.user'])
            ->whereBetween('executed_at', [$dateFromCarbon, $dateToCarbon])
            ->orderBy('executed_at', 'desc')
            ->get();

        $filename = 'dashboard_export_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($testRuns) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['Date', 'Time', 'Website', 'User', 'Result', 'Test Definition']);

            // Add data rows
            foreach ($testRuns as $run) {
                fputcsv($file, [
                    $run->executed_at->format('Y-m-d'),
                    $run->executed_at->format('H:i:s'),
                    $run->testCase->testDefinition->website->url,
                    $run->testCase->testDefinition->website->user->name,
                    strtoupper($run->result),
                    $run->testCase->testDefinition->name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a listing of users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function userCreate()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function userStore(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        // Log user creation
        ActivityLog::log(
            $request->user()->id,
            'create_user',
            "Created user: {$user->name} ({$user->email})",
            ['created_user_id' => $user->id, 'role' => $user->role]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function userShow(User $user)
    {
        $user->load(['websites', 'websites.testDefinitions.testCases.testRuns']);
        
        // Get user statistics
        $stats = [
            'websites_count' => $user->websites->count(),
            'test_definitions_count' => $user->websites->sum(fn($website) => $website->testDefinitions->count()),
            'test_runs_count' => $user->getTestRunsCount(),
            'last_login' => $user->last_login_at,
            'email_verified' => $user->isEmailVerified(),
            'is_active' => $user->isActive(),
        ];
        
        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function userEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function userUpdate(UpdateUserRequest $request, User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === $request->user()->id && $request->role !== 'admin') {
            return redirect()->back()
                ->withErrors(['role' => 'You cannot change your own role from admin.']);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Prevent admin from deactivating themselves
        if ($user->id === $request->user()->id) {
            $data['is_active'] = true;
        } else {
            $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : ($user->is_active ?? true);
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Log user update
        ActivityLog::log(
            $request->user()->id,
            'update_user',
            "Updated user: {$user->name} ({$user->email})",
            ['updated_user_id' => $user->id, 'role' => $user->role]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function userDestroy(Request $request, User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === $request->user()->id) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $userName = $user->name;
        $userEmail = $user->email;
        
        $user->delete();

        // Log user deletion
        ActivityLog::log(
            $request->user()->id,
            'delete_user',
            "Deleted user: {$userName} ({$userEmail})",
            ['deleted_user_email' => $userEmail]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk activate users.
     */
    public function usersBulkActivate(Request $request)
    {
        $ids = is_string($request->ids) ? json_decode($request->ids, true) : $request->ids;
        
        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        // Prevent admin from deactivating themselves
        $ids = array_filter($ids, function($id) use ($request) {
            return $id != $request->user()->id;
        });

        $count = User::whereIn('id', $ids)->update(['is_active' => true]);

        // Log bulk activation
        ActivityLog::log(
            $request->user()->id,
            'bulk_activate_users',
            "Bulk activated {$count} users",
            ['count' => $count, 'ids' => $ids]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Successfully activated {$count} user(s).");
    }

    /**
     * Bulk deactivate users.
     */
    public function usersBulkDeactivate(Request $request)
    {
        $ids = is_string($request->ids) ? json_decode($request->ids, true) : $request->ids;
        
        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        // Prevent admin from deactivating themselves
        $ids = array_filter($ids, function($id) use ($request) {
            return $id != $request->user()->id;
        });

        $count = User::whereIn('id', $ids)->update(['is_active' => false]);

        // Log bulk deactivation
        ActivityLog::log(
            $request->user()->id,
            'bulk_deactivate_users',
            "Bulk deactivated {$count} users",
            ['count' => $count, 'ids' => $ids]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Successfully deactivated {$count} user(s).");
    }

    /**
     * Bulk delete users.
     */
    public function usersBulkDelete(Request $request)
    {
        $ids = is_string($request->ids) ? json_decode($request->ids, true) : $request->ids;
        
        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        // Prevent admin from deleting themselves
        $ids = array_filter($ids, function($id) use ($request) {
            return $id != $request->user()->id;
        });

        $count = User::whereIn('id', $ids)->delete();

        // Log bulk deletion
        ActivityLog::log(
            $request->user()->id,
            'bulk_delete_users',
            "Bulk deleted {$count} users",
            ['count' => $count, 'ids' => $ids]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Successfully deleted {$count} user(s).");
    }

    /**
     * Bulk change user roles.
     */
    public function usersBulkChangeRole(Request $request)
    {
        $ids = is_string($request->ids) ? json_decode($request->ids, true) : $request->ids;
        
        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
            'role' => 'required|string|in:admin,user',
        ]);

        // Prevent admin from changing their own role
        $ids = array_filter($ids, function($id) use ($request) {
            return $id != $request->user()->id;
        });

        $count = User::whereIn('id', $ids)->update(['role' => $request->role]);

        // Log bulk role change
        ActivityLog::log(
            $request->user()->id,
            'bulk_change_user_roles',
            "Bulk changed role to {$request->role} for {$count} users",
            ['count' => $count, 'role' => $request->role, 'ids' => $ids]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Successfully changed role for {$count} user(s).");
    }

    /**
     * Export users to CSV.
     */
    public function usersExport(Request $request)
    {
        $query = User::query();

        // Apply same filters as index
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $filename = 'users_export_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Email Verified', 'Active', 'Last Login', 'Created At']);

            // Add data rows
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->isEmailVerified() ? 'Yes' : 'No',
                    $user->isActive() ? 'Yes' : 'No',
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        // Log export
        ActivityLog::log(
            $request->user()->id,
            'export_users',
            'Exported users to CSV',
            ['count' => $users->count()]
        );

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display activity logs.
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action type
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Export functionality
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportActivityLogs($query->get());
        }

        $logs = $query->paginate(50);
        $users = User::orderBy('name')->get();
        $actions = ActivityLog::distinct()->pluck('action')->sort();

        return view('admin.activity-logs', compact('logs', 'users', 'actions'));
    }

    /**
     * Export activity logs to CSV.
     */
    private function exportActivityLogs($logs)
    {
        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['Time', 'User', 'Email', 'Action', 'Description', 'IP Address', 'User Agent']);

            // Add data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'System',
                    $log->user ? $log->user->email : 'N/A',
                    ucfirst(str_replace('_', ' ', $log->action)),
                    $log->description,
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display system settings page.
     */
    public function settings()
    {
        $settings = [
            'site_name' => Setting::get('site_name', config('app.name')),
            'site_url' => Setting::get('site_url', config('app.url')),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'email_notifications_enabled' => Setting::get('email_notifications_enabled', config('mail.enabled', true)),
            'email_from_address' => Setting::get('email_from_address', config('mail.from.address')),
            'email_from_name' => Setting::get('email_from_name', config('mail.from.name')),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'sometimes|string|max:255',
            'site_url' => 'sometimes|url|max:255',
            'email_notifications_enabled' => 'sometimes|boolean',
            'email_from_address' => 'sometimes|email|max:255',
            'email_from_name' => 'sometimes|string|max:255',
        ]);

        // Handle checkbox - if not present, it's false
        $emailNotificationsEnabled = $request->has('email_notifications_enabled') 
            ? (bool) $request->input('email_notifications_enabled')
            : false;

        // Save settings to database
        if (isset($validated['site_name'])) {
            Setting::set('site_name', $validated['site_name']);
        }
        if (isset($validated['site_url'])) {
            Setting::set('site_url', $validated['site_url']);
        }
        // Always save checkbox value (even if unchecked)
        Setting::set('email_notifications_enabled', $emailNotificationsEnabled, 'boolean');
        if (isset($validated['email_from_address'])) {
            Setting::set('email_from_address', $validated['email_from_address']);
        }
        if (isset($validated['email_from_name'])) {
            Setting::set('email_from_name', $validated['email_from_name']);
        }

        // Log settings update
        ActivityLog::log(
            $request->user()->id,
            'update_settings',
            'Updated system settings',
            ['updated_keys' => array_keys($validated)]
        );

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Display email notification settings.
     */
    public function emailSettings()
    {
        $emailSettings = [
            'notify_on_test_completion' => Setting::get('notify_on_test_completion', true),
            'notify_on_test_failure' => Setting::get('notify_on_test_failure', true),
            'notify_on_user_registration' => Setting::get('notify_on_user_registration', true),
            'notify_on_website_added' => Setting::get('notify_on_website_added', false),
            'notify_daily_summary' => Setting::get('notify_daily_summary', false),
        ];

        return view('admin.email-settings', compact('emailSettings'));
    }

    /**
     * Update email notification settings.
     */
    public function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'notify_on_test_completion' => 'sometimes|boolean',
            'notify_on_test_failure' => 'sometimes|boolean',
            'notify_on_user_registration' => 'sometimes|boolean',
            'notify_on_website_added' => 'sometimes|boolean',
            'notify_daily_summary' => 'sometimes|boolean',
        ]);

        // Handle checkboxes - if not present in request, they're false
        $emailSettings = [
            'notify_on_test_completion' => $request->has('notify_on_test_completion'),
            'notify_on_test_failure' => $request->has('notify_on_test_failure'),
            'notify_on_user_registration' => $request->has('notify_on_user_registration'),
            'notify_on_website_added' => $request->has('notify_on_website_added'),
            'notify_daily_summary' => $request->has('notify_daily_summary'),
        ];

        // Save email settings to database
        $settingsToSave = [];
        foreach ($emailSettings as $key => $value) {
            $settingsToSave[$key] = ['value' => $value, 'type' => 'boolean'];
        }
        Setting::setMany($settingsToSave);

        // Log email settings update
        ActivityLog::log(
            $request->user()->id,
            'update_email_settings',
            'Updated email notification settings',
            ['updated_keys' => array_keys($emailSettings)]
        );

        // Save email settings to database
        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'boolean');
        }

        // Log email settings update
        ActivityLog::log(
            $request->user()->id,
            'update_email_settings',
            'Updated email notification settings',
            ['updated_keys' => array_keys($validated)]
        );

        return redirect()->route('admin.email-settings')
            ->with('success', 'Email notification settings updated successfully.');
    }

    /**
     * Display a listing of all websites.
     */
    public function websites(Request $request)
    {
        $query = Website::with(['user', 'testDefinitions']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $websites = $query->orderBy('created_at', 'desc')->paginate(15);
        $users = User::orderBy('name')->get();

        return view('admin.websites.index', compact('websites', 'users'));
    }

    /**
     * Display the specified website.
     */
    public function websiteShow(Website $website)
    {
        $website->load(['user', 'testDefinitions.testCases.testRuns']);
        
        // Get statistics
        $stats = [
            'test_definitions_count' => $website->testDefinitions->count(),
            'test_runs_count' => $website->getTestRuns()->count(),
            'successful_runs' => $website->getTestRuns()->where('result', 'pass')->count(),
            'failed_runs' => $website->getTestRuns()->where('result', 'fail')->count(),
        ];

        return view('admin.websites.show', compact('website', 'stats'));
    }

    /**
     * Remove the specified website from storage.
     */
    public function websiteDestroy(Request $request, Website $website)
    {
        $websiteUrl = $website->url;
        $userId = $website->user_id;
        
        $website->delete();

        // Log website deletion
        ActivityLog::log(
            $request->user()->id,
            'delete_website',
            "Deleted website: {$websiteUrl}",
            ['website_url' => $websiteUrl, 'user_id' => $userId]
        );

        return redirect()->route('admin.websites.index')
            ->with('success', 'Website deleted successfully.');
    }

    /**
     * Bulk delete websites.
     */
    public function websitesBulkDelete(Request $request)
    {
        $ids = is_string($request->ids) ? json_decode($request->ids, true) : $request->ids;
        
        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:websites,id',
        ]);

        $count = Website::whereIn('id', $ids)->delete();

        // Log bulk deletion
        ActivityLog::log(
            $request->user()->id,
            'bulk_delete_websites',
            "Bulk deleted {$count} websites",
            ['count' => $count, 'ids' => $ids]
        );

        return redirect()->route('admin.websites.index')
            ->with('success', "Successfully deleted {$count} website(s).");
    }

    /**
     * Display a listing of all test definitions.
     */
    public function testDefinitions(Request $request)
    {
        $query = TestDefinition::with(['website.user', 'testCases']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('website', function ($websiteQuery) use ($search) {
                      $websiteQuery->where('url', 'like', "%{$search}%")
                                   ->orWhereHas('user', function ($userQuery) use ($search) {
                                       $userQuery->where('name', 'like', "%{$search}%")
                                                 ->orWhere('email', 'like', "%{$search}%");
                                   });
                  });
            });
        }

        // Filter by website
        if ($request->has('website_id') && $request->website_id) {
            $query->where('website_id', $request->website_id);
        }

        $testDefinitions = $query->orderBy('created_at', 'desc')->paginate(15);
        $websites = Website::with('user')->orderBy('url')->get();

        return view('admin.test-definitions.index', compact('testDefinitions', 'websites'));
    }

    /**
     * Display the specified test definition.
     */
    public function testDefinitionShow(TestDefinition $testDefinition)
    {
        $testDefinition->load(['website.user', 'testCases.testRuns']);
        
        // Get statistics
        $stats = [
            'test_cases_count' => $testDefinition->testCases->count(),
            'test_runs_count' => $testDefinition->testCases->sum(function ($testCase) {
                return $testCase->testRuns->count();
            }),
            'successful_runs' => $testDefinition->testCases->sum(function ($testCase) {
                return $testCase->testRuns->where('result', 'pass')->count();
            }),
            'failed_runs' => $testDefinition->testCases->sum(function ($testCase) {
                return $testCase->testRuns->where('result', 'fail')->count();
            }),
        ];

        return view('admin.test-definitions.show', compact('testDefinition', 'stats'));
    }

    /**
     * Remove the specified test definition from storage.
     */
    public function testDefinitionDestroy(Request $request, TestDefinition $testDefinition)
    {
        $description = $testDefinition->description;
        $websiteId = $testDefinition->website_id;
        
        $testDefinition->delete();

        // Log test definition deletion
        ActivityLog::log(
            $request->user()->id,
            'delete_test_definition',
            "Deleted test definition: {$description}",
            ['test_definition_id' => $testDefinition->id, 'website_id' => $websiteId]
        );

        return redirect()->route('admin.test-definitions.index')
            ->with('success', 'Test definition deleted successfully.');
    }

    /**
     * Bulk delete test definitions.
     */
    public function testDefinitionsBulkDelete(Request $request)
    {
        $ids = is_string($request->ids) ? json_decode($request->ids, true) : $request->ids;
        
        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:test_definitions,id',
        ]);

        $count = TestDefinition::whereIn('id', $ids)->delete();

        // Log bulk deletion
        ActivityLog::log(
            $request->user()->id,
            'bulk_delete_test_definitions',
            "Bulk deleted {$count} test definitions",
            ['count' => $count, 'ids' => $ids]
        );

        return redirect()->route('admin.test-definitions.index')
            ->with('success', "Successfully deleted {$count} test definition(s).");
    }

    /**
     * Display a listing of all test runs.
     */
    public function testRuns(Request $request)
    {
        $query = TestRun::with(['testCase.testDefinition.website.user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('testCase.testDefinition.website', function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by result
        if ($request->has('result') && $request->result) {
            $query->where('result', $request->result);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by website
        if ($request->has('website_id') && $request->website_id) {
            $query->whereHas('testCase.testDefinition', function ($q) use ($request) {
                $q->where('website_id', $request->website_id);
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('executed_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('executed_at', '<=', $request->date_to);
        }

        $testRuns = $query->orderBy('executed_at', 'desc')->paginate(20);
        $websites = Website::with('user')->orderBy('url')->get();

        return view('admin.test-runs.index', compact('testRuns', 'websites'));
    }

    /**
     * Display the specified test run.
     */
    public function testRunShow(TestRun $testRun)
    {
        $testRun->load(['testCase.testDefinition.website.user']);
        
        return view('admin.test-runs.show', compact('testRun'));
    }

    /**
     * Remove the specified test run from storage.
     */
    public function testRunDestroy(Request $request, TestRun $testRun)
    {
        $testRunId = $testRun->id;
        $result = $testRun->result;
        
        $testRun->delete();

        // Log test run deletion
        ActivityLog::log(
            $request->user()->id,
            'delete_test_run',
            "Deleted test run (Result: {$result})",
            ['test_run_id' => $testRunId, 'result' => $result]
        );

        return redirect()->route('admin.test-runs.index')
            ->with('success', 'Test run deleted successfully.');
    }

    /**
     * Bulk delete test runs.
     */
    public function testRunsBulkDelete(Request $request)
    {
        $ids = is_string($request->ids) ? json_decode($request->ids, true) : $request->ids;
        
        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:test_runs,id',
        ]);

        $count = TestRun::whereIn('id', $ids)->delete();

        // Log bulk deletion
        ActivityLog::log(
            $request->user()->id,
            'bulk_delete_test_runs',
            "Bulk deleted {$count} test runs",
            ['count' => $count, 'ids' => $ids]
        );

        return redirect()->route('admin.test-runs.index')
            ->with('success', "Successfully deleted {$count} test run(s).");
    }

    /**
     * Display a listing of all reports.
     */
    public function reports(Request $request)
    {
        $query = Report::with(['user', 'website']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('summary', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('website', function ($websiteQuery) use ($search) {
                      $websiteQuery->where('url', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by website
        if ($request->has('website_id') && $request->website_id) {
            $query->where('website_id', $request->website_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);
        $users = User::orderBy('name')->get();
        $websites = Website::with('user')->orderBy('url')->get();

        return view('admin.reports.index', compact('reports', 'users', 'websites'));
    }

    /**
     * Display the specified report.
     */
    public function reportShow(Report $report)
    {
        $report->load(['user', 'website']);
        
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Remove the specified report from storage.
     */
    public function reportDestroy(Request $request, Report $report)
    {
        $reportId = $report->id;
        
        $report->delete();

        // Log report deletion
        ActivityLog::log(
            $request->user()->id,
            'delete_report',
            "Deleted report #{$reportId}",
            ['report_id' => $reportId]
        );

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    /**
     * Bulk delete reports.
     */
    public function reportsBulkDelete(Request $request)
    {
        $ids = is_string($request->ids) ? json_decode($request->ids, true) : $request->ids;
        
        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reports,id',
        ]);

        $count = Report::whereIn('id', $ids)->delete();

        // Log bulk deletion
        ActivityLog::log(
            $request->user()->id,
            'bulk_delete_reports',
            "Bulk deleted {$count} reports",
            ['count' => $count, 'ids' => $ids]
        );

        return redirect()->route('admin.reports.index')
            ->with('success', "Successfully deleted {$count} report(s).");
    }

    /**
     * Export report as JSON.
     */
    public function reportExportJson(Report $report)
    {
        $report->load(['user', 'website']);
        
        return response()->json([
            'id' => $report->id,
            'user' => [
                'name' => $report->user->name,
                'email' => $report->user->email,
            ],
            'website' => [
                'url' => $report->website->url,
            ],
            'summary' => $report->summary,
            'created_at' => $report->created_at->toISOString(),
        ], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Export report as CSV.
     */
    public function reportExportCsv(Report $report)
    {
        $report->load(['user', 'website']);
        
        $filename = 'report-' . $report->id . '-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($report) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Email', 'Website', 'Summary', 'Created At']);
            fputcsv($file, [
                $report->id,
                $report->user->name,
                $report->user->email,
                $report->website->url,
                $report->summary,
                $report->created_at->format('Y-m-d H:i:s'),
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
