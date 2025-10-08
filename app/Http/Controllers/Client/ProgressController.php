<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectProgress;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get user's projects with progress data
        $projects = Project::where('user_id', $user->id)
            ->with(['assignedDeveloper', 'progress.developer', 'files' => function($query) {
                $query->where('is_public', true);
            }])
            ->latest()
            ->get();

        // Calculate overall statistics
        $totalHoursPurchased = $user->purchases()->where('status', 'paid')->sum('days') * 8; // 8 hours per day
        $totalHoursUsed = $projects->sum('total_hours_worked');
        $totalHoursRemaining = max(0, $totalHoursPurchased - $totalHoursUsed);
        $totalProjects = $projects->count();
        $activeProjects = $projects->whereIn('status', ['in_progress', 'pending'])->count();

        $stats = [
            'total_hours_purchased' => $totalHoursPurchased,
            'total_hours_used' => $totalHoursUsed,
            'total_hours_remaining' => $totalHoursRemaining,
            'total_projects' => $totalProjects,
            'active_projects' => $activeProjects,
        ];

        return view('client.progress.index', compact('projects', 'stats'));
    }

    public function show(Project $project)
    {
        // Verify this is the user's project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $progress = $project->progress()
            ->with(['developer'])
            ->latest('work_date')
            ->get();

        $publicFiles = $project->files()
            ->where('is_public', true)
            ->with('uploader')
            ->latest()
            ->get();

        // Calculate project-specific statistics
        $stats = [
            'total_hours_worked' => $project->total_hours_worked,
            'overall_progress' => $project->overall_progress,
            'total_files' => $publicFiles->count(),
            'recent_activity' => $project->last_activity_at,
        ];

        return view('client.progress.show', compact('project', 'progress', 'publicFiles', 'stats'));
    }

    public function downloadFile(Project $project, ProjectFile $file)
    {
        // Verify this is the user's project and file is public
        if ($project->user_id !== Auth::id() || !$file->is_public) {
            abort(403, 'Unauthorized access.');
        }

        if ($file->project_id !== $project->id) {
            abort(404, 'File not found.');
        }

        return response()->download(storage_path('app/' . $file->file_path), $file->original_name);
    }
}