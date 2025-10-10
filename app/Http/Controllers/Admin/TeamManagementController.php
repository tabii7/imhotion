<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamManagementController extends Controller
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

    public function index()
    {
        $teams = Team::with(['teamLead', 'members', 'projects'])
            ->withCount(['members', 'projects'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_teams' => Team::count(),
            'active_teams' => Team::where('status', 'active')->count(),
            'total_members' => User::whereHas('teams')->count(),
            'unassigned_developers' => User::where('role', 'developer')
                ->where('is_available', true)
                ->whereDoesntHave('teams')
                ->count(),
        ];

        return view('admin.teams.index', compact('teams', 'stats'));
    }

    public function create()
    {
        $developers = User::where('role', 'developer')
            ->where('is_available', true)
            ->with('specialization')
            ->get();

        $specializations = Specialization::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        return view('admin.teams.create', compact('developers', 'specializations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'team_lead_id' => 'nullable|exists:users,id',
            'specializations' => 'nullable|array',
            'specializations.*' => 'exists:specializations,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'team_lead_id' => $request->team_lead_id,
            'specializations' => $request->specializations,
            'status' => 'active',
        ]);

        // Add team members
        if ($request->members) {
            $members = [];
            foreach ($request->members as $memberId) {
                $members[$memberId] = [
                    'role' => $memberId == $request->team_lead_id ? 'lead' : 'member',
                    'joined_at' => now(),
                ];
            }
            $team->members()->attach($members);
        }

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        $team->load(['teamLead', 'members.specialization', 'projects.user']);
        
        $availableProjects = Project::whereDoesntHave('teams', function ($query) use ($team) {
            $query->where('team_id', $team->id);
        })->with('user')->get();

        $availableDevelopers = User::where('role', 'developer')
            ->where('is_available', true)
            ->whereDoesntHave('teams', function ($query) use ($team) {
                $query->where('team_id', $team->id);
            })
            ->with('specialization')
            ->get();

        return view('admin.teams.show', compact('team', 'availableProjects', 'availableDevelopers'));
    }

    public function edit(Team $team)
    {
        $team->load(['members', 'teamLead']);
        
        $developers = User::where('role', 'developer')
            ->where('is_available', true)
            ->with('specialization')
            ->get();

        $specializations = Specialization::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        return view('admin.teams.edit', compact('team', 'developers', 'specializations'));
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'team_lead_id' => 'nullable|exists:users,id',
            'specializations' => 'nullable|array',
            'specializations.*' => 'exists:specializations,id',
            'status' => 'required|in:active,inactive',
        ]);

        $team->update([
            'name' => $request->name,
            'description' => $request->description,
            'team_lead_id' => $request->team_lead_id,
            'specializations' => $request->specializations,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        // Check if team has active projects
        if ($team->projects()->whereIn('status', ['in_progress', 'pending'])->exists()) {
            return back()->with('error', 'Cannot delete team with active projects.');
        }

        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team deleted successfully.');
    }

    public function addMember(Request $request, Team $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,lead,admin',
        ]);

        $user = User::findOrFail($request->user_id);
        
        if ($user->role !== 'developer') {
            return back()->with('error', 'Only developers can be added to teams.');
        }

        if ($team->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this team.');
        }

        $team->members()->attach($user->id, [
            'role' => $request->role,
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Member added to team successfully.');
    }

    public function removeMember(Team $team, User $user)
    {
        $team->members()->detach($user->id);

        return back()->with('success', 'Member removed from team successfully.');
    }

    public function assignProject(Request $request, Team $team)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::findOrFail($request->project_id);

        if ($team->projects()->where('project_id', $project->id)->exists()) {
            return back()->with('error', 'Project is already assigned to this team.');
        }

        $team->projects()->attach($project->id, [
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Project assigned to team successfully.');
    }

    public function unassignProject(Team $team, Project $project)
    {
        $team->projects()->detach($project->id);

        return back()->with('success', 'Project unassigned from team successfully.');
    }
}
