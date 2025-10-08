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
        $projects = Project::with([
            'user', 
            'assignedDeveloper', 
            'assignedAdministrator',
            'requirements',
            'documents' => function($query) {
                $query->latest()->limit(5);
            },
            'activities' => function($query) {
                $query->latest()->limit(10);
            },
            'timeLogs' => function($query) {
                $query->latest()->limit(10);
            }
        ])
        ->latest()
        ->paginate(20);

        $availableDevelopers = User::where('role', 'developer')
            ->where('is_available', true)
            ->select('id', 'name', 'email', 'experience_level')
            ->get();

        return view('administrator.projects', compact('projects', 'availableDevelopers'));
    }

    public function showProject(Project $project)
    {
        $project->load([
            'user', 
            'assignedDeveloper', 
            'assignedAdministrator', 
            'requirements', 
            'activities', 
            'documents' => function($query) {
                $query->with('uploadedBy')->latest()->limit(10);
            },
            'progress' => function($query) {
                $query->latest()->limit(10);
            }
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

        return view('administrator.projects.show', compact('project', 'availableDevelopers', 'recentUpdates'));
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
            ->withCount('assignedProjects')
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

    public function downloadDocument(\App\Models\ProjectDocument $document)
    {
        // Administrators can download any project document
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

}