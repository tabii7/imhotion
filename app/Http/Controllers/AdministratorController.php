<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\ProjectRequirement;
use App\Models\ProjectActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdministratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdministrator() && !Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = [
            'total_projects' => Project::count(),
            'pending_projects' => Project::where('status', 'in_progress')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'pending_requirements' => ProjectRequirement::where('status', 'pending')->count(),
            'available_developers' => User::where('role', 'developer')
                ->where('is_available', true)
                ->count(),
        ];

        // Get recent projects
        $recentProjects = Project::with(['user', 'assignedDeveloper', 'assignedAdministrator'])
            ->latest()
            ->limit(10)
            ->get();

        // Get pending requirements
        $pendingRequirements = ProjectRequirement::with(['project', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('administrator.dashboard', compact('stats', 'recentProjects', 'pendingRequirements'));
    }

    public function projects()
    {
        $projects = Project::with(['user', 'assignedDeveloper', 'assignedAdministrator'])
            ->latest()
            ->paginate(20);

        return view('administrator.projects', compact('projects'));
    }

    public function showProject(Project $project)
    {
        $project->load(['user', 'assignedDeveloper', 'assignedAdministrator', 'requirements', 'activities', 'documents']);
        
        $availableDevelopers = User::where('role', 'developer')
            ->where('is_available', true)
            ->get();

        return view('administrator.projects.show', compact('project', 'availableDevelopers'));
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

    public function reviewRequirement(Request $request, ProjectRequirement $requirement)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $requirement->update([
            'status' => $request->status,
            'administrator_feedback' => $request->feedback,
            'reviewed_at' => now(),
        ]);

        // Log activity
        ProjectActivity::create([
            'project_id' => $requirement->project_id,
            'user_id' => Auth::id(),
            'type' => 'requirement_reviewed',
            'description' => "Requirement '{$requirement->title}' {$request->status}",
            'metadata' => [
                'requirement_id' => $requirement->id,
                'status' => $request->status,
                'feedback' => $request->feedback,
            ],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Requirement reviewed successfully.');
    }

    public function updateProjectStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed,on_hold,canceled',
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

    public function developers()
    {
        $developers = User::where('role', 'developer')
            ->with(['assignedProjects', 'managedProjects'])
            ->paginate(20);

        return view('administrator.developers', compact('developers'));
    }

    public function reports()
    {
        // Generate reports for administrators
        $reports = [
            'project_status_distribution' => Project::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'monthly_project_completion' => Project::where('status', 'completed')
                ->selectRaw('DATE_FORMAT(completed_at, "%Y-%m") as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'developer_workload' => User::where('role', 'developer')
                ->withCount('assignedProjects')
                ->orderBy('assigned_projects_count', 'desc')
                ->get(),
        ];

        return view('administrator.reports', compact('reports'));
    }
}