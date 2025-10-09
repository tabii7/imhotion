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
    public function index()
    {
        $projects = Project::with(['user', 'assignedDeveloper', 'assignedAdministrator'])
            ->latest()
            ->paginate(10);
        
        return view('admin.projects.index', compact('projects'));
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
        $project->load(['user', 'assignedDeveloper', 'assignedAdministrator']);
        
        return view('admin.projects.show', compact('project'));
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
}
