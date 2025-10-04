<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index()
    {
        $specializations = Specialization::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('team.index', compact('specializations'));
    }

    public function showRegistration()
    {
        $specializations = Specialization::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        return view('team.register', compact('specializations'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'specialization_id' => 'required|exists:specializations,id',
            'skills' => 'nullable|string',
            'experience_level' => 'required|in:junior,mid,senior',
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
            'working_hours' => 'nullable|string|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:1000',
            'terms_accepted' => 'required|accepted',
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

        $developer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'developer',
            'specialization_id' => $request->specialization_id,
            'skills' => $skills,
            'experience_level' => $request->experience_level,
            'is_available' => true, // New developers are available by default
            'working_hours' => $request->working_hours,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'portfolio_url' => $request->portfolio_url,
            'linkedin_url' => $request->linkedin_url,
            'github_url' => $request->github_url,
            'bio' => $request->bio,
        ]);

        // Auto-login the new developer
        Auth::login($developer);

        return redirect()->route('developer.dashboard')
            ->with('success', 'Welcome to the team! Your account has been created successfully.');
    }

    public function show($specialization)
    {
        $specialization = Specialization::where('slug', $specialization)
            ->where('is_active', true)
            ->firstOrFail();

        $developers = User::where('role', 'developer')
            ->where('specialization_id', $specialization->id)
            ->where('is_available', true)
            ->with(['assignedProjects'])
            ->paginate(12);

        return view('team.specialization', compact('specialization', 'developers'));
    }

    public function search(Request $request)
    {
        $query = User::where('role', 'developer')
            ->where('is_available', true)
            ->with(['specialization', 'assignedProjects']);

        if ($request->filled('specialization')) {
            $query->where('specialization_id', $request->specialization);
        }

        if ($request->filled('experience')) {
            $query->where('experience_level', $request->experience);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('skills', 'like', "%{$searchTerm}%")
                  ->orWhere('bio', 'like', "%{$searchTerm}%");
            });
        }

        $developers = $query->paginate(12);
        $specializations = Specialization::active()->orderBy('category')->orderBy('sort_order')->get();

        return view('team.search', compact('developers', 'specializations'));
    }
}