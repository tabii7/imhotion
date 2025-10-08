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
            ->with([
                'user', 
                'assignedAdministrator', 
                'documents' => function($query) {
                    $query->latest()->limit(5);
                },
                'progress' => function($query) {
                    $query->latest()->limit(10);
                }
            ])
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
        try {
            // Debug logging
            \Log::info('Update project status request received', [
                'project_id' => $project->id,
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'has_file' => $request->hasFile('file')
            ]);
            
            // Ensure developer can only update their assigned projects
            if ($project->assigned_developer_id !== Auth::id()) {
                abort(403, 'You can only update your assigned projects.');
            }

            $request->validate([
                'status' => 'required|in:in_progress,completed,on_hold,finalized,cancelled',
                'progress' => 'nullable|integer|min:0|max:100',
                'developer_notes' => 'nullable|string|max:1000',
                'file' => 'nullable|file|max:10240', // 10MB max
                'document_description' => 'nullable|string|max:500',
            ]);
            
            \Log::info('Validation passed, proceeding with update');

        $oldStatus = $project->status;
            
            // Update project
        $project->update([
            'status' => $request->status,
            'progress' => $request->progress ?? $project->progress,
            'developer_notes' => $request->developer_notes,
            'last_activity_at' => now(),
        ]);
        
        \Log::info('Project updated successfully', ['project_id' => $project->id, 'new_status' => $request->status]);

            $document = null;
            $progress = null;

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('project-documents', $filename, 'public');

            $document = ProjectDocument::create([
                'project_id' => $project->id,
                'name' => $request->document_description ?? $file->getClientOriginalName(),
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => Auth::id(),
            ]);
            
            \Log::info('Document created successfully', ['document_id' => $document->id, 'filename' => $filename]);
        }

            // Create a progress entry for this update
            $tasksCompleted = [];
            if ($request->developer_notes) {
                $tasksCompleted[] = "Status update: {$request->status}";
            }
            if ($document) {
                $tasksCompleted[] = "Document uploaded: {$document->name}";
            }

        $progress = \App\Models\ProjectProgress::create([
            'project_id' => $project->id,
            'developer_id' => Auth::id(),
            'work_date' => now()->toDateString(),
            'hours_worked' => 0, // Status updates don't count as hours
            'description' => $request->developer_notes ?: "Project status updated to {$request->status}",
            'progress_percentage' => $request->progress ?? 0,
            'tasks_completed' => $tasksCompleted,
            'challenges_faced' => [],
            'next_steps' => [],
            'status' => $request->status,
        ]);
        
        \Log::info('Progress entry created successfully', ['progress_id' => $progress->id]);

        // Log activity
        $activityDescription = "Project status updated from {$oldStatus} to {$request->status}";
        if ($document) {
            $activityDescription .= " and document uploaded: {$document->name}";
        }

        \Log::info('Creating activity log', ['description' => $activityDescription]);

        try {
            ProjectActivity::create([
                'project_id' => $project->id,
                'user_id' => Auth::id(),
                'type' => 'status_update',
                'description' => $activityDescription,
                'metadata' => [
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                    'progress' => $request->progress,
                    'notes' => $request->developer_notes,
                    'document_id' => $document?->id,
                    'progress_id' => $progress->id,
                ],
                'performed_at' => now(),
            ]);

            \Log::info('Activity log created successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to create activity log: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't fail the entire request if activity logging fails
        }

        // Always return JSON response for this endpoint
        \Log::info('About to check for AJAX request', [
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'header_x_requested_with' => $request->header('X-Requested-With'),
            'header_accept' => $request->header('Accept'),
            'content_type' => $request->header('Content-Type')
        ]);
        
        \Log::info('Returning JSON response');
        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully.'
        ]);

    } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Project update error: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating project: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error updating project: ' . $e->getMessage());
        }
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
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => Auth::id(),
        ]);

        // Create a progress entry for this document upload
        $progress = \App\Models\ProjectProgress::create([
            'project_id' => $project->id,
            'developer_id' => Auth::id(),
            'work_date' => now()->toDateString(),
            'hours_worked' => 0, // Document upload doesn't count as hours
            'description' => "Uploaded document: {$document->name}",
            'progress_percentage' => 0,
            'tasks_completed' => ["Document upload: {$document->name}"],
            'challenges_faced' => [],
            'next_steps' => [],
            'status' => 'completed',
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
                'progress_id' => $progress->id,
                'filename' => $filename,
                'size' => $file->getSize(),
            ],
            'performed_at' => now(),
        ]);

        \Log::info('Activity log created successfully');

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            \Log::info('Returning JSON response', ['success' => true]);
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully.',
                'document' => [
                    'id' => $document->id,
                    'name' => $document->name,
                    'size' => $document->size,
                    'uploaded_at' => $document->created_at->format('M d, Y H:i'),
                ],
                'progress' => [
                    'id' => $progress->id,
                    'description' => $progress->description,
                    'created_at' => $progress->created_at->format('M d, Y H:i'),
                ]
            ]);
        }

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function downloadDocument(ProjectDocument $document)
    {
        // Ensure developer can only download documents from their assigned projects
        if ($document->project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You can only download documents from your assigned projects.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($document->path)) {
            abort(404, 'File not found.');
        }

        // Get the full file path
        $filePath = storage_path('app/public/' . $document->path);
        
        // Ensure the file exists
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