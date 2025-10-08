<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Models\ProjectActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectManagementController extends Controller
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

    public function index(Request $request)
    {
        $query = Project::with([
            'user', 
            'assignedDeveloper', 
            'assignedAdministrator', 
            'documents' => function($query) {
                $query->latest()->limit(5);
            },
            'activities' => function($query) {
                $query->latest()->limit(10);
            },
            'timeLogs' => function($query) {
                $query->latest()->limit(10);
            },
            'requirements'
        ]);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Team filter removed - teams relationship not implemented

        if ($request->filled('developer')) {
            $query->where('assigned_developer_id', $request->developer);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('topic', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $projects = $query->latest()->paginate(20);

        // Get filter options
        $developers = User::where('role', 'developer')->where('is_available', true)->get();
        $statuses = ['in_progress', 'completed', 'on_hold', 'finalized', 'cancelled'];

        // Get statistics
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::whereIn('status', ['in_progress', 'pending'])->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
            'finalized' => Project::where('status', 'finalized')->count(),
            'cancelled' => Project::where('status', 'cancelled')->count(),
            'overdue' => Project::where('delivery_date', '<', now())->whereNotIn('status', ['completed', 'cancelled', 'finalized'])->count(),
        ];

        return view('admin.projects.index', compact('projects', 'developers', 'statuses', 'stats'));
    }

    public function show(Project $project)
    {
        $project->load([
            'user', 
            'assignedDeveloper', 
            'assignedAdministrator', 
            'requirements', 
            'activities.user', 
            'documents' => function($query) {
                $query->with('uploadedBy')->latest()->limit(10);
            },
            'progress' => function($query) {
                $query->latest()->limit(10);
            },
            'timeLogs.user'
        ]);

        $availableDevelopers = User::where('role', 'developer')
            ->where('is_available', true)
            ->get();

        // Get recent updates from project progress
        $recentUpdates = \App\Models\ProjectProgress::where('project_id', $project->id)
            ->with('project:id,name')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($progress) {
                $progress->project_name = $progress->project->name;
                return $progress;
            });

        return view('admin.projects.show', compact('project', 'availableDevelopers', 'recentUpdates'));
    }

    // Team methods removed - teams relationship not implemented

    public function assignDeveloper(Request $request, Project $project)
    {
        $request->validate([
            'developer_id' => 'required|exists:users,id',
        ]);

        $developer = User::findOrFail($request->developer_id);
        
        if ($developer->role !== 'developer') {
            return back()->with('error', 'Selected user is not a developer.');
        }

        $project->assignDeveloper($developer);
        $project->update(['assigned_administrator_id' => Auth::id()]);

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => 'developer_assigned',
            'description' => "Developer {$developer->name} assigned to project",
            'metadata' => ['developer_id' => $developer->id],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Developer assigned successfully.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed,on_hold,pending,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $project->status;
        $project->update([
            'status' => $request->status,
            'administrator_notes' => $request->notes,
        ]);

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => 'status_change',
            'description' => "Project status changed from {$oldStatus} to {$request->status}",
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'notes' => $request->notes,
            ],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Project status updated successfully.');
    }

    public function getProgressData(Project $project)
    {
        $progressData = [
            'project' => [
                'id' => $project->id,
                'title' => $project->title,
                'status' => $project->status,
                'progress' => $project->progress ?? 0,
                'delivery_date' => $project->delivery_date,
            ],
            'team_progress' => [],
            'time_logs' => $project->timeLogs()->with('user')->get(),
            'activities' => $project->activities()->with('user')->latest()->limit(10)->get(),
        ];

        // Team progress removed - teams relationship not implemented
        // if ($project->teams()->exists()) {
        //     foreach ($project->teams as $team) {
        //         $progressData['team_progress'][] = [
        //             'team_id' => $team->id,
        //             'team_name' => $team->name,
        //             'member_count' => $team->members()->count(),
        //             'active_members' => $team->members()->where('is_available', true)->count(),
        //         ];
        //     }
        // }

        return response()->json($progressData);
    }

    public function downloadDocument(\App\Models\ProjectDocument $document)
    {
        // Admins can download any project document
        // Check if file exists in storage
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($document->path)) {
            abort(404, 'File not found.');
        }

        // Get the full file path
        $filePath = storage_path('app/public/' . $document->path);
        
        // Ensure the file exists on disk
        if (!file_exists($filePath)) {
            abort(404, 'File not found on disk.');
        }

        // Use the original filename for download, preserving the extension
        $downloadName = $document->original_filename;
        if (empty($downloadName)) {
            // Fallback to the stored filename if original_filename is not available
            $downloadName = $document->filename;
        }

        return response()->download($filePath, $downloadName);
    }

    public function reports()
    {
        // Get comprehensive project statistics
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::whereIn('status', ['in_progress', 'pending'])->count(),
            'completed_projects' => Project::whereIn('status', ['completed', 'finalized'])->count(),
            'on_hold_projects' => Project::where('status', 'on_hold')->count(),
            'cancelled_projects' => Project::where('status', 'cancelled')->count(),
            'overdue_projects' => Project::where('delivery_date', '<', now())->whereNotIn('status', ['completed', 'cancelled', 'finalized'])->count(),
        ];

        // Get projects with full details
        $projects = Project::with([
            'user', 
            'assignedDeveloper', 
            'assignedAdministrator', 
            'documents', 
            'activities', 
            'timeLogs', 
            'requirements'
        ])->latest()->get();

        // Get project performance metrics
        $performanceMetrics = [
            'avg_completion_time' => $this->getAverageCompletionTime(),
            'projects_by_status' => $this->getProjectsByStatus(),
            'projects_by_priority' => $this->getProjectsByPriority(),
            'developer_performance' => $this->getDeveloperPerformance(),
            'monthly_progress' => $this->getMonthlyProgress(),
        ];

        return view('admin.projects.reports', compact('stats', 'projects', 'performanceMetrics'));
    }

    public function projectReport(Project $project)
    {
        // Load all project data
        $project->load([
            'user', 
            'assignedDeveloper', 
            'assignedAdministrator', 
            'documents' => function($query) {
                $query->with('uploadedBy')->latest();
            },
            'activities' => function($query) {
                $query->with('user')->latest();
            },
            'timeLogs' => function($query) {
                $query->with('user')->latest();
            },
            'requirements',
            'progress' => function($query) {
                $query->latest();
            }
        ]);

        // Get project timeline
        $timeline = $this->getProjectTimeline($project);

        // Get project metrics
        $metrics = [
            'total_hours' => $project->timeLogs->sum('hours_spent'),
            'total_documents' => $project->documents->count(),
            'total_activities' => $project->activities->count(),
            'completion_rate' => $project->progress ?? 0,
            'days_since_start' => $project->start_date ? now()->diffInDays($project->start_date) : 0,
            'days_until_deadline' => $project->delivery_date ? now()->diffInDays($project->delivery_date, false) : null,
        ];

        return view('admin.projects.project-report', compact('project', 'timeline', 'metrics'));
    }

    private function getAverageCompletionTime()
    {
        $completedProjects = Project::whereIn('status', ['completed', 'finalized'])
            ->whereNotNull('start_date')
            ->whereNotNull('completed_at')
            ->get();

        if ($completedProjects->isEmpty()) {
            return 0;
        }

        $totalDays = $completedProjects->sum(function ($project) {
            return $project->start_date->diffInDays($project->completed_at);
        });

        return round($totalDays / $completedProjects->count(), 1);
    }

    private function getProjectsByStatus()
    {
        return Project::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    private function getProjectsByPriority()
    {
        return Project::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
    }

    private function getDeveloperPerformance()
    {
        return Project::with('assignedDeveloper')
            ->whereNotNull('assigned_developer_id')
            ->get()
            ->groupBy('assigned_developer_id')
            ->map(function ($projects, $developerId) {
                $developer = $projects->first()->assignedDeveloper;
                return [
                    'developer' => $developer->name,
                    'total_projects' => $projects->count(),
                    'completed_projects' => $projects->whereIn('status', ['completed', 'finalized'])->count(),
                    'completion_rate' => $projects->count() > 0 ? round(($projects->whereIn('status', ['completed', 'finalized'])->count() / $projects->count()) * 100, 1) : 0,
                ];
            })
            ->values()
            ->toArray();
    }

    private function getMonthlyProgress()
    {
        return Project::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }

    private function getProjectTimeline(Project $project)
    {
        $timeline = collect();

        // Add project creation
        $timeline->push([
            'date' => $project->created_at,
            'type' => 'created',
            'title' => 'Project Created',
            'description' => 'Project was created',
            'user' => $project->user->name,
        ]);

        // Add developer assignment
        if ($project->assigned_at) {
            $timeline->push([
                'date' => $project->assigned_at,
                'type' => 'assigned',
                'title' => 'Developer Assigned',
                'description' => 'Developer was assigned to project',
                'user' => $project->assignedDeveloper->name ?? 'Unknown',
            ]);
        }

        // Add activities
        foreach ($project->activities as $activity) {
            $timeline->push([
                'date' => $activity->performed_at ?? $activity->created_at,
                'type' => 'activity',
                'title' => ucfirst(str_replace('_', ' ', $activity->type)),
                'description' => $activity->description,
                'user' => $activity->user->name,
            ]);
        }

        // Add progress entries
        foreach ($project->progress as $progress) {
            $timeline->push([
                'date' => $progress->created_at,
                'type' => 'progress',
                'title' => 'Progress Update',
                'description' => $progress->description,
                'user' => $progress->developer->name ?? 'Unknown',
            ]);
        }

        return $timeline->sortBy('date')->values();
    }
}


