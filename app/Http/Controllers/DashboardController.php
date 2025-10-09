<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\PricingItem;
use App\Models\Purchase;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with cart and menu options.
     */
    public function index(Request $request, $section = null): View
    {
        $user = Auth::user();
        $pricingItems = PricingItem::with('category')->get();
        $userPurchases = Purchase::where('user_id', $user->id)->with('pricingItem')->get();

        // Get current user's projects with proper categorization
        $activeStatuses = ['pending', 'in_progress', 'completed', 'on_hold'];
        $finalizedStatuses = ['cancelled', 'finalized'];

        $active = Project::where('user_id', Auth::id())
            ->whereIn('status', $activeStatuses)
            ->with(['assignedDeveloper', 'progress.developer', 'files' => function($query) {
                $query->where('is_public', true);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $finalized = Project::where('user_id', Auth::id())
            ->whereIn('status', $finalizedStatuses)
            ->with(['assignedDeveloper', 'progress.developer', 'files' => function($query) {
                $query->where('is_public', true);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate days balance: sum of purchased days minus used days
        $purchasedDays = $user->purchases()->where('status', 'paid')->sum('days');
        $usedDays = Project::where('user_id', Auth::id())->sum('days_used');

        // Calculate hours tracking
        $totalHoursPurchased = $purchasedDays * 8; // Assuming 8 hours per day
        $totalHoursUsed = $active->sum('total_hours_worked') + $finalized->sum('total_hours_worked');
        $totalHoursRemaining = max(0, $totalHoursPurchased - $totalHoursUsed);

        // Calculate counts for stats
        $counts = [
            'active' => $active->count(),
            'balance' => max(0, $purchasedDays - $usedDays), // Total purchased days - used days
            'finalized' => $finalized->count(),
            'total_hours_purchased' => $totalHoursPurchased,
            'total_hours_used' => $totalHoursUsed,
            'total_hours_remaining' => $totalHoursRemaining,
        ];

    return view('dashboard', compact('pricingItems', 'userPurchases', 'user', 'active', 'finalized', 'counts'))->with('initialSection', $section);
    }

    /**
     * Display the services page
     */
    public function services(): View
    {
        $user = Auth::user();
        $pricingItems = PricingItem::with('category')->get();

        return view('dashboard.services-page', compact('pricingItems', 'user'));
    }

    /**
     * Display the transactions page
     */
    public function transactions(): View
    {
        $user = Auth::user();
        $userPurchases = Purchase::where('user_id', $user->id)->with('pricingItem')->get();

        // If no real transactions, add some sample data for demonstration
        if ($userPurchases->isEmpty()) {
            $userPurchases = collect([
                (object) [
                    'id' => 1,
                    'amount' => 1200.00,
                    'days' => 20,
                    'status' => 'completed',
                    'created_at' => now()->subDays(5),
                    'pricingItem' => (object) [
                        'title' => 'Basic Development Package',
                        'category' => (object) ['title' => 'Development Hours']
                    ]
                ],
                (object) [
                    'id' => 2,
                    'amount' => 2500.00,
                    'days' => 50,
                    'status' => 'completed',
                    'created_at' => now()->subDays(15),
                    'pricingItem' => (object) [
                        'title' => 'Standard Development Package',
                        'category' => (object) ['title' => 'Development Hours']
                    ]
                ],
                (object) [
                    'id' => 3,
                    'amount' => 800.00,
                    'days' => 10,
                    'status' => 'completed',
                    'created_at' => now()->subDays(30),
                    'pricingItem' => (object) [
                        'title' => 'Quick Fix Package',
                        'category' => (object) ['title' => 'Development Hours']
                    ]
                ],
                (object) [
                    'id' => 4,
                    'amount' => 4000.00,
                    'days' => 100,
                    'status' => 'pending',
                    'created_at' => now()->subDays(2),
                    'pricingItem' => (object) [
                        'title' => 'Premium Development Package',
                        'category' => (object) ['title' => 'Development Hours']
                    ]
                ]
            ]);
        }

        return view('dashboard.transactions-page', compact('userPurchases', 'user'));
    }

    /**
     * Display the profile page
     */
    public function profile(): View
    {
        $user = Auth::user();
        $projects = Project::where('user_id', $user->id)->latest()->get();

        $activeStatuses = ['new', 'pending', 'in_progress', 'completed'];
        $finalizedStatuses = ['cancelled', 'finalized'];

        $active = Project::where('user_id', Auth::id())
            ->whereIn('status', $activeStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        $finalized = Project::where('user_id', Auth::id())
            ->whereIn('status', $finalizedStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        $counts = [
            'active' => $active->count(),
            'finalized' => $finalized->count(),
        ];

        return view('dashboard.profile-page', compact('user', 'active', 'finalized', 'counts'));
    }

    /**
     * Download project files
     */
    public function downloadFiles(Project $project)
    {
        // Check if user owns this project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to project files.');
        }

        // Get project documents (only visible ones)
        $documents = $project->documents()->where('is_hidden', false)->get();
        
        if ($documents->isEmpty()) {
            return redirect()->back()->with('error', 'No files available for download.');
        }

        // If only one file, download directly
        if ($documents->count() === 1) {
            $document = $documents->first();
            $filePath = storage_path('app/public/' . $document->path);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File not found.');
            }
            
            return response()->download($filePath, $document->filename);
        }

        // Multiple files - create zip
        $zip = new \ZipArchive();
        $zipFileName = 'project_' . $project->id . '_files.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->with('error', 'Cannot create zip file.');
        }
        
        foreach ($documents as $document) {
            $filePath = storage_path('app/public/' . $document->path);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $document->filename);
            }
        }
        
        $zip->close();
        
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Display the projects page
     */
    public function projects(): View
    {
        $user = Auth::user();
        $projects = Project::where('user_id', $user->id)
            ->with(['assignedDeveloper', 'progress.developer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.projects-page', compact('projects', 'user'));
    }

    /**
     * Show the form for creating a new project
     */
    public function createProject(): View
    {
        return view('dashboard.projects.create');
    }

    /**
     * Store a newly created project
     */
    public function storeProject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_hours' => 'required|numeric|min:1',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date|after:today',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'estimated_hours' => $request->estimated_hours,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'user_id' => Auth::id(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('dashboard.projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified project
     */
    public function showProject(Project $project): View
    {
        // Check if user owns this project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to project.');
        }

        $project->load(['assignedDeveloper', 'progress.developer', 'files' => function($query) {
            $query->where('is_public', true);
        }]);

        return view('dashboard.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function editProject(Project $project): View
    {
        // Check if user owns this project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to project.');
        }

        return view('dashboard.projects.edit', compact('project'));
    }

    /**
     * Update the specified project
     */
    public function updateProject(Request $request, Project $project)
    {
        // Check if user owns this project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to project.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_hours' => 'required|numeric|min:1',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date|after:today',
        ]);

        $project->update($request->only(['name', 'description', 'estimated_hours', 'priority', 'deadline']));

        return redirect()->route('dashboard.projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified project
     */
    public function destroyProject(Project $project)
    {
        // Check if user owns this project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to project.');
        }

        $project->delete();

        return redirect()->route('dashboard.projects')
            ->with('success', 'Project deleted successfully!');
    }

}
