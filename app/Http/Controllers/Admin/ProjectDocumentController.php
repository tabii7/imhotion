<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProjectDocumentController extends Controller
{
    public function rename(Request $request, Project $project, ProjectDocument $document)
    {
        abort_unless($document->project_id === $project->id, 404);

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'filename' => ['required', 'string', 'max:255'],
        ]);

        $document->update([
            'name'     => $data['name'],
            'filename' => $data['filename'],
        ]);

        return response()->json([
            'ok'       => true,
            'id'       => $document->id,
            'name'     => $document->name,
            'filename' => $document->filename,
        ]);
    }

    public function hide(Project $project, ProjectDocument $document)
    {
        abort_unless($document->project_id === $project->id, 404);

        $document->update(['is_hidden' => true]);

        return response()->json(['ok' => true, 'id' => $document->id]);
    }

    public function upload(Request $request, Project $project)
    {
        $request->validate([
            'files.*' => ['file', 'max:5120'], // max 5MB per file
        ]);

        $saved = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if (!$file) {
                    continue;
                }

                $original = $file->getClientOriginalName();
                $baseName = pathinfo($original, PATHINFO_FILENAME);
                $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

                // Whitelist extensions
                $allowed = ['jpg','jpeg','png','gif','webp','pdf','zip','mp4','mov','ogg'];
                if (!in_array($ext, $allowed, true)) {
                    // skip files with disallowed extensions
                    \Log::warning('Upload blocked due to disallowed extension', ['file' => $original, 'ext' => $ext]);
                    continue;
                }

                // Sanitize base name and build stored name
                $safeBase = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', mb_substr($baseName, 0, 120));
                $dir = "project-docs/{$project->id}";
                $storedName = now()->format('YmdHis') . '_' . $safeBase . '.' . $ext;

                $path = Storage::disk('public')->putFileAs($dir, $file, $storedName);

                $doc = $project->documents()->create([
                    'name'     => $safeBase,
                    'filename' => $original,
                    'path'     => $path,
                    'size'     => $file->getSize(),
                    'is_hidden'=> false,
                ]);

                $saved[] = [
                    'id'       => $doc->id,
                    'name'     => $doc->name,
                    'filename' => $doc->filename,
                    'size'     => (int) $doc->size,
                    'ext'      => $ext ?: '—',
                    'date'     => optional($doc->created_at)->format('d-m-y H:i'),
                    'url'      => (Storage::disk('public')->exists($doc->path) ? Storage::url($doc->path) : null),
                ];
            }
        }

        return response()->json([
            'ok'    => true,
            'files' => $saved,
        ], Response::HTTP_CREATED);
    }
}
