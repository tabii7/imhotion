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
        $query = Project::with(['user', 'assignedDeveloper', 'assignedAdministrator', 'teams']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('team')) {
            $query->whereHas('teams', function ($q) use ($request) {
                $q->where('teams.id', $request->team);
            });
        }

        if ($request->filled('developer')) {
            $query->where('assigned_developer_id', $request->developer);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('topic', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $projects = $query->latest()->paginate(20);

        // Get filter options
        $teams = Team::where('status', 'active')->get();
        $developers = User::where('role', 'developer')->where('is_available', true)->get();
        $statuses = ['in_progress', 'completed', 'on_hold', 'pending', 'cancelled'];

        // Get statistics
        $stats = [
            'total_projects' => Project::count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
            'pending' => Project::where('status', 'pending')->count(),
            'overdue' => Project::where('delivery_date', '<', now())->whereNotIn('status', ['completed', 'cancelled'])->count(),
        ];

        return view('admin.projects.index', compact('projects', 'teams', 'developers', 'statuses', 'stats'));
    }

    public function show(Project $project)
    {
        $project->load([
            'user', 
            'assignedDeveloper', 
            'assignedAdministrator', 
            'teams.members', 
            'requirements', 
            'activities.user', 
            'documents',
            'timeLogs.user'
        ]);

        $availableTeams = Team::where('status', 'active')
            ->whereDoesntHave('projects', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->with('members')
            ->get();

        $availableDevelopers = User::where('role', 'developer')
            ->where('is_available', true)
            ->get();

        return view('admin.projects.show', compact('project', 'availableTeams', 'availableDevelopers'));
    }

    public function assignTeam(Request $request, Project $project)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        $team = Team::findOrFail($request->team_id);

        if ($project->teams()->where('team_id', $team->id)->exists()) {
            return back()->with('error', 'Project is already assigned to this team.');
        }

        $project->teams()->attach($team->id, [
            'assigned_at' => now(),
        ]);

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => 'team_assigned',
            'description' => "Team '{$team->name}' assigned to project",
            'metadata' => ['team_id' => $team->id],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Team assigned to project successfully.');
    }

    public function unassignTeam(Project $project, Team $team)
    {
        $project->teams()->detach($team->id);

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => 'team_unassigned',
            'description' => "Team '{$team->name}' unassigned from project",
            'metadata' => ['team_id' => $team->id],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Team unassigned from project successfully.');
    }

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

        // Get team progress if project has teams
        if ($project->teams()->exists()) {
            foreach ($project->teams as $team) {
                $progressData['team_progress'][] = [
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                    'member_count' => $team->members()->count(),
                    'active_members' => $team->members()->where('is_available', true)->count(),
                ];
            }
        }

        return response()->json($progressData);
    }
}
