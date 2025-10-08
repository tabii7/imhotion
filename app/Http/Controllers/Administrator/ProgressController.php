<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectProgress;
use App\Models\ProjectFile;
use App\Models\TimeTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
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

    public function index(Request $request)
    {
        $query = Project::with(['user', 'assignedDeveloper', 'progress.developer', 'files']);

        // Filter by project status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by developer
        if ($request->filled('developer')) {
            $query->where('assigned_developer_id', $request->developer);
        }

        // Search projects
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
        $developers = \App\Models\User::where('role', 'developer')->get();
        $statuses = ['in_progress', 'completed', 'on_hold', 'pending', 'cancelled'];

        return view('administrator.progress.index', compact('projects', 'developers', 'statuses'));
    }

    public function show(Project $project)
    {
        $progress = $project->progress()->with(['developer', 'files'])->latest('work_date')->get();
        $timeTracking = $project->timeTracking()->with('developer')->latest('tracking_date')->get();
        $files = $project->files()->with('uploader')->latest()->get();

        // Calculate statistics
        $stats = [
            'total_hours_worked' => $project->total_hours_worked,
            'total_hours_purchased' => $project->total_hours_purchased,
            'hours_remaining' => $project->hours_remaining,
            'overall_progress' => $project->overall_progress,
            'total_files' => $project->total_files,
            'recent_activity' => $project->last_activity_at,
        ];

        return view('administrator.progress.show', compact('project', 'progress', 'timeTracking', 'files', 'stats'));
    }

    public function downloadFile(Project $project, ProjectFile $file)
    {
        if ($file->project_id !== $project->id) {
            abort(404, 'File not found.');
        }

        return response()->download(storage_path('app/' . $file->file_path), $file->original_name);
    }

    public function exportProgress(Project $project)
    {
        $progress = $project->progress()->with(['developer', 'files'])->latest('work_date')->get();
        
        $csvData = [];
        $csvData[] = ['Date', 'Developer', 'Hours Worked', 'Progress %', 'Status', 'Description', 'Tasks Completed', 'Challenges', 'Next Steps'];
        
        foreach ($progress as $entry) {
            $csvData[] = [
                $entry->work_date->format('Y-m-d'),
                $entry->developer->name,
                $entry->hours_worked,
                $entry->progress_percentage,
                $entry->status,
                $entry->description,
                implode('; ', $entry->tasks_completed ?? []),
                implode('; ', $entry->challenges_faced ?? []),
                implode('; ', $entry->next_steps ?? []),
            ];
        }

        $filename = 'project_' . $project->id . '_progress_' . date('Y-m-d') . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}