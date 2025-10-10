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
            'files.*' => ['file'],
        ]);

        $saved = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if (!$file) {
                    continue;
                }

                $original   = $file->getClientOriginalName();
                $baseName   = pathinfo($original, PATHINFO_FILENAME);
                $ext        = strtolower(pathinfo($original, PATHINFO_EXTENSION));
                $dir        = "project-docs/{$project->id}";
                $storedName = now()->format('YmdHis') . '_' . $original;

                $path = Storage::disk('public')->putFileAs($dir, $file, $storedName);

                $doc = $project->documents()->create([
                    'name'     => $baseName,
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

    public function download(Project $project, ProjectDocument $document)
    {
        abort_unless($document->project_id === $project->id, 404);

        $filePath = storage_path('app/public/' . $document->path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        return response()->download($filePath, $document->filename);
    }
}
