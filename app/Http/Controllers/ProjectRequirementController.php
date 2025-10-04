<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectRequirement;
use App\Models\ProjectActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectRequirementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Project $project)
    {
        // Only clients can submit requirements for their projects
        if ($project->user_id !== Auth::id() && !Auth::user()->isAdministrator() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type' => 'required|in:feature,bug_fix,enhancement,integration,other',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $requirement = ProjectRequirement::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Log activity
        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'type' => 'requirement_submitted',
            'description' => "New requirement submitted: {$requirement->title}",
            'metadata' => [
                'requirement_id' => $requirement->id,
                'type' => $request->type,
                'priority' => $request->priority,
            ],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Requirement submitted successfully.');
    }

    public function show(ProjectRequirement $requirement)
    {
        // Ensure user can view this requirement
        if ($requirement->user_id !== Auth::id() && 
            !Auth::user()->isAdministrator() && 
            !Auth::user()->isAdmin() &&
            $requirement->project->assigned_developer_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $requirement->load(['project', 'user']);

        return view('requirements.show', compact('requirement'));
    }

    public function update(Request $request, ProjectRequirement $requirement)
    {
        // Only the submitter or administrators can update
        if ($requirement->user_id !== Auth::id() && 
            !Auth::user()->isAdministrator() && 
            !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type' => 'required|in:feature,bug_fix,enhancement,integration,other',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $requirement->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
        ]);

        // Log activity
        ProjectActivity::create([
            'project_id' => $requirement->project_id,
            'user_id' => Auth::id(),
            'type' => 'requirement_updated',
            'description' => "Requirement updated: {$requirement->title}",
            'metadata' => [
                'requirement_id' => $requirement->id,
                'changes' => $request->only(['title', 'description', 'type', 'priority']),
            ],
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Requirement updated successfully.');
    }

    public function destroy(ProjectRequirement $requirement)
    {
        // Only the submitter or administrators can delete
        if ($requirement->user_id !== Auth::id() && 
            !Auth::user()->isAdministrator() && 
            !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Log activity before deletion
        ProjectActivity::create([
            'project_id' => $requirement->project_id,
            'user_id' => Auth::id(),
            'type' => 'requirement_deleted',
            'description' => "Requirement deleted: {$requirement->title}",
            'metadata' => [
                'requirement_id' => $requirement->id,
                'title' => $requirement->title,
            ],
            'performed_at' => now(),
        ]);

        $requirement->delete();

        return back()->with('success', 'Requirement deleted successfully.');
    }
}