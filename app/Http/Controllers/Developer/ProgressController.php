<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectProgress;
use App\Models\ProjectFile;
use App\Models\TimeTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgressController extends Controller
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

    public function index(Project $project)
    {
        // Verify developer is assigned to this project
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this project.');
        }

        $progress = $project->progressUpdates()->with('files')->latest('work_date')->get();
        $timeTracking = $project->timeTracking()->latest('tracking_date')->get();
        $files = $project->files()->latest()->get();

        return view('developer.progress.index', compact('project', 'progress', 'timeTracking', 'files'));
    }

    public function create(Project $project)
    {
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this project.');
        }

        return view('developer.progress.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this project.');
        }

        $request->validate([
            'work_date' => 'required|date',
            'hours_worked' => 'required|numeric|min:0.1|max:24',
            'description' => 'required|string|max:2000',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'tasks_completed' => 'nullable|array',
            'challenges_faced' => 'nullable|array',
            'next_steps' => 'nullable|array',
            'status' => 'required|in:in_progress,completed,blocked,on_hold',
            'files' => 'nullable|array',
            'files.*' => 'file|max:10240', // 10MB max per file
        ]);

        $progress = ProjectProgress::create([
            'project_id' => $project->id,
            'developer_id' => Auth::id(),
            'work_date' => $request->work_date,
            'hours_worked' => $request->hours_worked,
            'description' => $request->description,
            'progress_percentage' => $request->progress_percentage,
            'tasks_completed' => $request->tasks_completed ?? [],
            'challenges_faced' => $request->challenges_faced ?? [],
            'next_steps' => $request->next_steps ?? [],
            'status' => $request->status,
        ]);

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $this->handleFileUpload($file, $project, $progress, $request);
            }
        }

        // Update project's last activity
        $project->updateActivity();

        return redirect()->route('developer.progress.index', $project)
            ->with('success', 'Progress updated successfully!');
    }

    public function show(Project $project, ProjectProgress $progress)
    {
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this project.');
        }

        if ($progress->project_id !== $project->id) {
            abort(404, 'Progress not found for this project.');
        }

        $progress->load('files');
        return view('developer.progress.show', compact('project', 'progress'));
    }

    public function edit(Project $project, ProjectProgress $progress)
    {
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this project.');
        }

        if ($progress->project_id !== $project->id || $progress->developer_id !== Auth::id()) {
            abort(404, 'Progress not found.');
        }

        return view('developer.progress.edit', compact('project', 'progress'));
    }

    public function update(Request $request, Project $project, ProjectProgress $progress)
    {
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this project.');
        }

        if ($progress->project_id !== $project->id || $progress->developer_id !== Auth::id()) {
            abort(404, 'Progress not found.');
        }

        $request->validate([
            'work_date' => 'required|date',
            'hours_worked' => 'required|numeric|min:0.1|max:24',
            'description' => 'required|string|max:2000',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'tasks_completed' => 'nullable|array',
            'challenges_faced' => 'nullable|array',
            'next_steps' => 'nullable|array',
            'status' => 'required|in:in_progress,completed,blocked,on_hold',
        ]);

        $progress->update($request->all());

        return redirect()->route('developer.progress.index', $project)
            ->with('success', 'Progress updated successfully!');
    }

    public function destroy(Project $project, ProjectProgress $progress)
    {
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this project.');
        }

        if ($progress->project_id !== $project->id || $progress->developer_id !== Auth::id()) {
            abort(404, 'Progress not found.');
        }

        // Delete associated files
        foreach ($progress->files as $file) {
            Storage::delete($file->file_path);
            $file->delete();
        }

        $progress->delete();

        return redirect()->route('developer.progress.index', $project)
            ->with('success', 'Progress entry deleted successfully!');
    }

    public function uploadFile(Request $request, Project $project)
    {
        if ($project->assigned_developer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this project.');
        }

        $request->validate([
            'file' => 'required|file|max:10240',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean',
            'progress_id' => 'nullable|exists:project_progress,id',
        ]);

        $file = $request->file('file');
        $uploadedFile = $this->handleFileUpload($file, $project, null, $request);

        return response()->json([
            'success' => true,
            'file' => $uploadedFile,
            'message' => 'File uploaded successfully!'
        ]);
    }

    private function handleFileUpload($file, Project $project, $progress = null, Request $request = null)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $filePath = 'projects/' . $project->id . '/files/' . $fileName;

        // Store the file locally first
        Storage::putFileAs('projects/' . $project->id . '/files', $file, $fileName);

        // Upload to Google Drive
        $googleDriveService = app(\App\Services\GoogleDriveService::class);
        $driveResult = $googleDriveService->uploadFromStorage(
            $filePath,
            $originalName,
            $file->getMimeType(),
            $request ? $request->description : null
        );

        // Determine file type
        $fileType = $this->getFileType($extension);

        return ProjectFile::create([
            'project_id' => $project->id,
            'uploaded_by' => Auth::id(),
            'progress_id' => $progress ? $progress->id : null,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request ? $request->description : null,
            'is_public' => $request ? $request->is_public : false,
            'google_drive_file_id' => $driveResult['success'] ? $driveResult['file_id'] : null,
            'google_drive_url' => $driveResult['success'] ? $driveResult['web_view_link'] : null,
        ]);
    }

    private function getFileType($extension)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $documentExtensions = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
        $codeExtensions = ['php', 'js', 'css', 'html', 'py', 'java', 'cpp', 'c', 'sql'];

        if (in_array(strtolower($extension), $imageExtensions)) {
            return 'image';
        } elseif (in_array(strtolower($extension), $documentExtensions)) {
            return 'document';
        } elseif (in_array(strtolower($extension), $codeExtensions)) {
            return 'code';
        } else {
            return 'other';
        }
    }
}