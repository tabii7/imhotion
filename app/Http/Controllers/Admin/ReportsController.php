<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\Team;
use App\Models\User;
use App\Models\ProjectTimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin() && !Auth::user()->isAdministrator()) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $reports = ProjectReport::with('creator')
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function create()
    {
        return view('admin.reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:project_status,team_performance,time_tracking,budget_analysis,custom',
            'filters' => 'nullable|array',
        ]);

        $report = ProjectReport::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'filters' => $request->filters,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Report created successfully.');
    }

    public function show(ProjectReport $report)
    {
        $report->load('creator');

        // Generate report data if not already generated
        if (!$report->isGenerated()) {
            $data = $this->generateReportData($report);
            $report->update([
                'data' => $data,
                'generated_at' => now(),
            ]);
        }

        return view('admin.reports.show', compact('report'));
    }

    public function generate(ProjectReport $report)
    {
        $data = $this->generateReportData($report);
        
        $report->update([
            'data' => $data,
            'generated_at' => now(),
        ]);

        return back()->with('success', 'Report generated successfully.');
    }

    public function destroy(ProjectReport $report)
    {
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    public function dashboard()
    {
        // Get comprehensive dashboard data
        $stats = $this->getDashboardStats();
        $recentProjects = Project::with(['user', 'assignedDeveloper'])
            ->latest()
            ->limit(10)
            ->get();

        $teamPerformance = $this->getTeamPerformanceData();
        $projectStatusDistribution = $this->getProjectStatusDistribution();
        $monthlyTrends = $this->getMonthlyTrends();

        return view('admin.dashboard', compact(
            'stats', 
            'recentProjects', 
            'teamPerformance', 
            'projectStatusDistribution',
            'monthlyTrends'
        ));
    }

    public function export(Request $request, ProjectReport $report)
    {
        $format = $request->get('format', 'pdf');
        
        // This would typically generate and download the report
        // For now, we'll just return a success message
        return back()->with('success', "Report exported as {$format} successfully.");
    }

    private function generateReportData(ProjectReport $report): array
    {
        switch ($report->type) {
            case 'project_status':
                return $this->generateProjectStatusReport($report->filters ?? []);
            case 'team_performance':
                return $this->generateTeamPerformanceReport($report->filters ?? []);
            case 'time_tracking':
                return $this->generateTimeTrackingReport($report->filters ?? []);
            case 'budget_analysis':
                return $this->generateBudgetAnalysisReport($report->filters ?? []);
            default:
                return [];
        }
    }

    private function generateProjectStatusReport(array $filters): array
    {
        $query = Project::query();

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        // Team filter removed - teams relationship not implemented
        // if (isset($filters['team_id'])) {
        //     $query->whereHas('teams', function ($q) use ($filters) {
        //         $q->where('teams.id', $filters['team_id']);
        //     });
        // }

        $projects = $query->with(['user', 'assignedDeveloper'])->get();

        return [
            'total_projects' => $projects->count(),
            'by_status' => $projects->groupBy('status')->map->count(),
            // 'by_team' => $projects->flatMap->teams->groupBy('name')->map->count(), // Teams relationship not implemented
            'completion_rate' => $projects->where('status', 'completed')->count() / max($projects->count(), 1) * 100,
            'average_duration' => $projects->whereNotNull('completed_at')->avg(function ($project) {
                return $project->created_at->diffInDays($project->completed_at);
            }),
        ];
    }

    private function generateTeamPerformanceReport(array $filters): array
    {
        // Teams relationship not implemented - return empty array
        return [];
        
        // $teams = Team::with(['members', 'projects'])->get();

        // return $teams->map(function ($team) {
        //     $completedProjects = $team->projects()->where('status', 'completed')->count();
        //     $totalProjects = $team->projects()->count();
            
        //     return [
        //         'team_id' => $team->id,
        //         'team_name' => $team->name,
        //         'member_count' => $team->members()->count(),
        //         'total_projects' => $totalProjects,
        //         'completed_projects' => $completedProjects,
        //         'completion_rate' => $totalProjects > 0 ? ($completedProjects / $totalProjects) * 100 : 0,
        //         'active_members' => $team->members()->where('is_available', true)->count(),
        //     ];
        // })->toArray();
    }

    private function generateTimeTrackingReport(array $filters): array
    {
        $query = ProjectTimeLog::query();

        if (isset($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        $timeLogs = $query->with(['user', 'project'])->get();

        return [
            'total_hours' => $timeLogs->sum('hours_spent'),
            'by_user' => $timeLogs->groupBy('user.name')->map->sum('hours_spent'),
            'by_project' => $timeLogs->groupBy('project.title')->map->sum('hours_spent'),
            'average_daily_hours' => $timeLogs->groupBy('date')->map->sum('hours_spent')->avg(),
        ];
    }

    private function generateBudgetAnalysisReport(array $filters): array
    {
        $projects = Project::with(['user', 'timeLogs'])->get();

        return [
            'total_budget' => $projects->sum('day_budget'),
            'used_budget' => $projects->sum(function ($project) {
                return $project->timeLogs->sum('hours_spent') * ($project->day_budget / 8);
            }),
            'remaining_budget' => $projects->sum('day_budget') - $projects->sum(function ($project) {
                return $project->timeLogs->sum('hours_spent') * ($project->day_budget / 8);
            }),
            'by_project' => $projects->map(function ($project) {
                $used = $project->timeLogs->sum('hours_spent') * ($project->day_budget / 8);
                return [
                    'project_id' => $project->id,
                    'project_title' => $project->title,
                    'budget' => $project->day_budget,
                    'used' => $used,
                    'remaining' => $project->day_budget - $used,
                ];
            }),
        ];
    }

    private function getDashboardStats(): array
    {
        return [
            'total_projects' => Project::count(),
            'active_projects' => Project::whereIn('status', ['in_progress', 'pending'])->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'total_teams' => 0, // Teams relationship not implemented
            'active_developers' => User::where('role', 'developer')->where('is_available', true)->count(),
            'total_hours_logged' => ProjectTimeLog::sum('hours_spent'),
            'overdue_projects' => Project::where('delivery_date', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
        ];
    }

    private function getTeamPerformanceData(): array
    {
        // Teams relationship not implemented - return empty array
        return [];
        
        // return Team::with(['members', 'projects'])
        //     ->get()
        //     ->map(function ($team) {
        //         return [
        //             'name' => $team->name,
        //             'member_count' => $team->members()->count(),
        //             'active_projects' => $team->projects()->whereIn('status', ['in_progress', 'pending'])->count(),
        //             'completed_projects' => $team->projects()->where('status', 'completed')->count(),
        //         ];
        //     })->toArray();
    }

    private function getProjectStatusDistribution(): array
    {
        return Project::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    private function getMonthlyTrends(): array
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'projects_created' => Project::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'projects_completed' => Project::where('status', 'completed')
                    ->whereYear('completed_at', $date->year)
                    ->whereMonth('completed_at', $date->month)
                    ->count(),
            ];
        }

        return $months;
    }
}


