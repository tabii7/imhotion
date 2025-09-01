<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ClientProjectController extends Controller
{
    public function create()
    {
        return view('client.create-project');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:150'],
            'topic'        => ['nullable','string','max:150'],
            'brief'        => ['nullable','string','max:5000'],
            'desired_date' => ['nullable','date'],
        ]);

        Project::create([
            'user_id'       => $request->user()->id,
            'name'          => $data['name'],
            'title'         => $data['name'], // legacy column on your DB
            'topic'         => $data['topic'] ?? null,
            'status'        => 'on_hold',     // new requests start on hold
            'delivery_date' => $data['desired_date'] ?? null,
            'progress'      => 0,
        ]);

        // (optional) notify admin here

        return redirect('/client')->with('ok', 'Project request submitted.');
    }
}
