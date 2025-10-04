<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\ProjectTimeLog;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DeveloperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isDeveloper()) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get developer's assigned projects
        $assignedProjects = $user->assignedProjects()
            ->with(['user', 'assignedAdministrator'])
            ->latest()
            ->get();

        // Get project statistics
        $stats = [
            'total_assigned' => $assignedProjects->count(),
            'in_progress' => $assignedProjects->where('status', 'in_progress')->count(),
            'completed' => $assignedProjects->where('status', 'completed')->count(),
            'hours_logged_this_month' => ProjectTimeLog::where('developer_id', $user->id)
                ->whereMonth('work_date', now()->month)
                ->sum('hours_spent'),
        ];

        // Get recent activities
        $recentActivities = ProjectActivity::whereHas('project', function ($query) use ($user) {
            $query->where('assigned_developer_id', $user->id);
        })
        ->with(['project', 'user'])
        ->latest()
        ->limit(10)
        ->get();

        return view('developer.dashboard', compact('assignedProjects', 'stats', 'recentActivities'));
    }

    public function projects()
    {
        $projects = Auth::user()->assignedProjects()
            ->with(['user', 'assignedAdministrator'])
            ->latest()
            ->paginate(20);

        return view('developer.projects', compact('projects'));
    }

    public function showProject(Project $project)
    {
        // Ensure developer can only see their assigned projects
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You can only view your assigned projects.');
        }

        $project->load(['user', 'assignedAdministrator', 'requirements', 'activities', 'documents', 'timeLogs']);
        
        return view('developer.project-details', compact('project'));
    }

    public function updateProjectStatus(Request $request, Project $project)
    {
        // Ensure developer can only update their assigned projects
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You can only update your assigned projects.');
        }

        $request->validate([
            'status' => 'required|in:in_progress,completed,on_hold',
            'progress' => 'nullable|integer|min:0|max:100',
            'developer_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $project->status;
        $project->update([
            'status' => $request->status,
            'progress' => $request->progress ?? $project->progress,
            'developer_notes' => $request->developer_notes,
            'last_activity_at' => now(),
        ]);

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => 'status_update',
            'description' => "Project status updated from {$oldStatus} to {$request->status}",
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'progress' => $request->progress,
                'notes' => $request->developer_notes,
            ],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Project status updated successfully.');
    }

    public function logTime(Request $request, Project $project)
    {
        // Ensure developer can only log time for their assigned projects
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You can only log time for your assigned projects.');
        }

        $request->validate([
            'description' => 'required|string|max:500',
            'hours_spent' => 'required|numeric|min:0.5|max:24',
            'work_date' => 'required|date|before_or_equal:today',
        ]);

        ProjectTimeLog::create([
            'project_id' => $project->id,
            'developer_id' => Auth::id(),
            'description' => $request->description,
            'hours_spent' => $request->hours_spent,
            'work_date' => $request->work_date,
            'logged_at' => now(),
        ]);

        // Update project's last activity
        $project->updateActivity();

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => 'time_logged',
            'description' => "Logged {$request->hours_spent} hours: {$request->description}",
            'metadata' => [
                'hours_spent' => $request->hours_spent,
                'work_date' => $request->work_date,
                'description' => $request->description,
            ],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Time logged successfully.');
    }

    public function uploadDocument(Request $request, Project $project)
    {
        // Ensure developer can only upload to their assigned projects
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You can only upload documents to your assigned projects.');
        }

        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('project-documents', $filename, 'public');

        $document = ProjectDocument::create([
            'project_id' => $project->id,
            'name' => $request->description ?? $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => Auth::id(),
        ]);

        // Update project's last activity
        $project->updateActivity();

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => 'file_uploaded',
            'description' => "Uploaded document: {$document->name}",
            'metadata' => [
                'document_id' => $document->id,
                'filename' => $filename,
                'size' => $file->getSize(),
            ],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function timeLogs()
    {
        $timeLogs = ProjectTimeLog::where('developer_id', Auth::id())
            ->with(['project'])
            ->latest('work_date')
            ->paginate(20);

        return view('developer.time-logs', compact('timeLogs'));
    }

    public function updateAvailability(Request $request)
    {
        $request->validate([
            'is_available' => 'required|boolean',
        ]);

        Auth::user()->update([
            'is_available' => $request->is_available,
        ]);

        return back()->with('success', 'Availability updated successfully.');
    }

    public function profile()
    {
        $user = Auth::user();
        $specializations = \App\Models\Specialization::active()->orderBy('category')->orderBy('sort_order')->get();
        
        return view('developer.profile', compact('user', 'specializations'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'specialization_id' => 'required|exists:specializations,id',
            'skills' => 'nullable|string',
            'experience_level' => 'required|in:junior,mid,senior',
            'is_available' => 'boolean',
            'working_hours' => 'nullable|array',
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
            'portfolio_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:2000',
        ]);

        // Parse skills JSON
        $skills = [];
        if ($request->skills) {
            try {
                $skills = json_decode($request->skills, true);
                if (!is_array($skills)) {
                    $skills = [];
                }
            } catch (\Exception $e) {
                $skills = [];
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'specialization_id' => $request->specialization_id,
            'skills' => $skills,
            'experience_level' => $request->experience_level,
            'is_available' => $request->has('is_available'),
            'working_hours' => $request->working_hours ?? [],
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'portfolio_url' => $request->portfolio_url,
            'linkedin_url' => $request->linkedin_url,
            'github_url' => $request->github_url,
            'bio' => $request->bio,
        ]);

        return redirect()->route('developer.profile')->with('success', 'Profile updated successfully.');
    }
}