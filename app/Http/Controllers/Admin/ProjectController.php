<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $query = Project::with(['user', 'assignedDeveloper', 'assignedAdministrator']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('topic', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client')) {
            $query->where('user_id', $request->client);
        }

        $projects = $query->latest()->paginate(10);

        // Calculate project statistics
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::whereIn('status', ['pending', 'in_progress'])->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
            'cancelled' => Project::whereIn('status', ['cancelled', 'canceled'])->count(),
            'finalized' => Project::where('status', 'finalized')->count(),
        ];
        
        return view('admin.projects.index', compact('projects', 'stats'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        $clients = User::where('role', 'client')->get();
        $developers = User::where('role', 'developer')->get();
        
        return view('admin.projects.create', compact('clients', 'developers'));
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'topic' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'delivery_date' => 'nullable|date',
            'total_days' => 'nullable|integer|min:1',
            'estimated_hours' => 'nullable|integer|min:1',
            'client_requirements' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = Project::create($request->all());

        return redirect()->route('admin.projects')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $project->load([
            'user', 
            'assignedDeveloper', 
            'assignedAdministrator',
            'documents' => function($query) {
                $query->with('uploadedBy')->latest();
            },
            'progressUpdates' => function($query) {
                $query->with('user')->latest();
            },
            'timeLogs' => function($query) {
                $query->with('user')->latest();
            },
            'activities' => function($query) {
                $query->with('user')->latest();
            },
            'requirements' => function($query) {
                $query->latest();
            },
            'files' => function($query) {
                $query->with('uploadedBy')->latest();
            }
        ]);
        
        // Calculate project statistics
        $stats = [
            'total_hours_worked' => $project->timeLogs->sum('hours_spent') ?? 0,
            'total_days_used' => $project->days_used ?? 0,
            'estimated_hours' => $project->estimated_hours ?? 0,
            'progress_percentage' => $project->progress ?? 0,
            'documents_count' => $project->documents->count(),
            'updates_count' => $project->progressUpdates->count(),
            'files_count' => $project->files->count(),
        ];
        
        return view('admin.projects.show', compact('project', 'stats'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Project $project)
    {
        $project->load(['user', 'assignedDeveloper', 'assignedAdministrator']);
        $clients = User::where('role', 'client')->get();
        $developers = User::where('role', 'developer')->get();
        
        return view('admin.projects.edit', compact('project', 'clients', 'developers'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'topic' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'delivery_date' => 'nullable|date',
            'total_days' => 'nullable|integer|min:1',
            'estimated_hours' => 'nullable|integer|min:1',
            'client_requirements' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project->update($request->all());

        return redirect()->route('admin.projects')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for assigning a developer
     */
    public function assignForm(Project $project)
    {
        $project->load(['user', 'assignedDeveloper']);
        $developers = User::where('role', 'developer')->get();
        
        return view('admin.projects.assign', compact('project', 'developers'));
    }

    /**
     * Assign a developer to the project
     */
    public function assignDeveloper(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'assigned_developer_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $project->update([
            'assigned_developer_id' => $request->assigned_developer_id,
            'assigned_at' => now(),
        ]);

        return redirect()->route('admin.projects')
            ->with('success', 'Developer assigned successfully!');
    }

    /**
     * Download a project document
     */
    public function downloadDocument($documentId)
    {
        $document = \App\Models\ProjectDocument::findOrFail($documentId);
        
        $filePath = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        return response()->download($filePath, $document->name);
    }
}
