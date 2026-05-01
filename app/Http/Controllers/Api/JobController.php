<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Job::with(['client', 'secteur']);

        if ($request->boolean('my_jobs')) {
            // Fetch jobs posted by the authenticated user (including closed/in_progress)
            $query->where('client_id', \Illuminate\Support\Facades\Auth::id())
                  ->with('bids.adherent.user'); // Include bids
        } else {
            // Default marketplace feed: only open jobs
            $query->where('status', 'open');
        }

        // Allow filtering
        if ($request->filled('secteur_id')) {
            $query->where('secteur_id', $request->secteur_id);
        }
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => ['required', 'string', 'min:10', app(\App\Rules\NoContactInfoRule::class)],
            'secteur_id' => 'required|exists:secteurs,id',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|gt:budget_min',
            'city' => 'required|string|max:100',
        ]);

        $validated['client_id'] = \Illuminate\Support\Facades\Auth::id();
        $validated['expires_at'] = now()->addDays(7); // Default expiration

        $job = \App\Models\Job::create($validated);

        return response()->json(['message' => 'Job posted successfully', 'job' => $job], 201);
    }

    public function show(\App\Models\Job $job)
    {
        $job->load(['client', 'secteur']);
        
        // Only load bids if the user is the client who posted the job
        if (\Illuminate\Support\Facades\Auth::id() === $job->client_id) {
            $job->load('bids.adherent.user');
        }

        return response()->json($job);
    }

    public function close(\App\Models\Job $job)
    {
        if (\Illuminate\Support\Facades\Auth::id() !== $job->client_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->update([
            'status' => 'closed',
            'closed_at' => now()
        ]);

        return response()->json(['message' => 'Job closed successfully']);
    }
}
