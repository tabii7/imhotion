<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $projects = Project::where('user_id', $user->id)
            ->latest()
            ->get();

        // Active = in_process, completed, on_hold (per your spec)
        $active = $projects->filter(fn($p) => in_array($p->status, ['in_progress', 'completed', 'on_hold']));
        // Finalized list
        $finalized = $projects->filter(fn($p) => in_array($p->status, ['finalized', 'cancelled']));

        $counts = [
            'active'    => $active->count(),
            'finalized' => $finalized->count(),
            'balance'   => (int) ($user->balance_days ?? 0),
        ];

        return view('client.index', compact('user', 'active', 'finalized', 'counts'));
    }
}
