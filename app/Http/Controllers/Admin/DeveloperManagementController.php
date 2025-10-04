<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DeveloperManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Only admins can manage developers.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $developers = User::where('role', 'developer')
            ->with(['assignedProjects', 'managedProjects', 'specialization'])
            ->paginate(20);

        $specializations = Specialization::active()->orderBy('category')->orderBy('sort_order')->get();

        return view('admin.developers.index', compact('developers', 'specializations'));
    }

    public function create()
    {
        $specializations = Specialization::active()->orderBy('category')->orderBy('sort_order')->get();
        return view('admin.developers.create', compact('specializations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'specialization_id' => 'required|exists:specializations,id',
            'skills' => 'nullable|string|max:1000',
            'experience_level' => 'required|in:junior,mid,senior',
            'is_available' => 'boolean',
            'working_hours' => 'nullable|array',
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
        ]);

        $developer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'developer',
            'specialization_id' => $request->specialization_id,
            'skills' => $request->skills,
            'experience_level' => $request->experience_level,
            'is_available' => $request->has('is_available'),
            'working_hours' => $request->working_hours ?? [],
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
        ]);

        return redirect()->route('admin.developers.index')
            ->with('success', 'Developer created successfully.');
    }

    public function show(User $developer)
    {
        if ($developer->role !== 'developer') {
            abort(404, 'User is not a developer.');
        }

        $developer->load(['assignedProjects.user', 'assignedProjects.assignedAdministrator']);
        
        $stats = [
            'total_projects' => $developer->assignedProjects->count(),
            'in_progress' => $developer->assignedProjects->where('status', 'in_progress')->count(),
            'completed' => $developer->assignedProjects->where('status', 'completed')->count(),
            'hours_logged_this_month' => $developer->timeLogs()
                ->whereMonth('work_date', now()->month)
                ->sum('hours_spent'),
        ];

        return view('admin.developers.show', compact('developer', 'stats'));
    }

    public function edit(User $developer)
    {
        if ($developer->role !== 'developer') {
            abort(404, 'User is not a developer.');
        }

        $specializations = Specialization::active()->orderBy('category')->orderBy('sort_order')->get();
        return view('admin.developers.edit', compact('developer', 'specializations'));
    }

    public function update(Request $request, User $developer)
    {
        if ($developer->role !== 'developer') {
            abort(404, 'User is not a developer.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($developer->id)],
            'specialization_id' => 'required|exists:specializations,id',
            'skills' => 'nullable|string|max:1000',
            'experience_level' => 'required|in:junior,mid,senior',
            'is_available' => 'boolean',
            'working_hours' => 'nullable|array',
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
        ]);

        $developer->update([
            'name' => $request->name,
            'email' => $request->email,
            'specialization_id' => $request->specialization_id,
            'skills' => $request->skills,
            'experience_level' => $request->experience_level,
            'is_available' => $request->has('is_available'),
            'working_hours' => $request->working_hours ?? [],
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
        ]);

        return redirect()->route('admin.developers.index')
            ->with('success', 'Developer updated successfully.');
    }

    public function destroy(User $developer)
    {
        if ($developer->role !== 'developer') {
            abort(404, 'User is not a developer.');
        }

        // Check if developer has active projects
        if ($developer->assignedProjects()->where('status', 'in_progress')->exists()) {
            return back()->with('error', 'Cannot delete developer with active projects.');
        }

        $developer->delete();

        return redirect()->route('admin.developers.index')
            ->with('success', 'Developer deleted successfully.');
    }

    public function toggleAvailability(User $developer)
    {
        if ($developer->role !== 'developer') {
            abort(404, 'User is not a developer.');
        }

        $developer->update([
            'is_available' => !$developer->is_available
        ]);

        return back()->with('success', 'Developer availability updated.');
    }
}