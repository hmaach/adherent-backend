<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BidController extends Controller
{
    protected $bidService;

    public function __construct(\App\Services\BidService $bidService)
    {
        $this->bidService = $bidService;
    }

    public function store(Request $request, \App\Models\Job $job)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        if (!$user || !$user->adherent) {
            return response()->json(['message' => 'Only Adhérents can bid on jobs.'], 403);
        }

        if ($job->status !== 'open') {
            return response()->json(['message' => 'This job is not open for bidding.'], 400);
        }

        if ($job->client_id === $user->id) {
            return response()->json(['message' => 'Vous ne pouvez pas faire une offre sur votre propre demande.'], 403);
        }

        $validated = $request->validate([
            'price_quote' => 'required|numeric|min:1',
            'estimated_days' => 'required|integer|min:1',
            'message' => ['required', 'string', 'min:10', app(\App\Rules\NoContactInfoRule::class)],
        ]);

        $validated['job_id'] = $job->id;
        $validated['adherent_id'] = $user->adherent->id;

        try {
            $bid = \App\Models\Bid::create($validated);
            
            // Dispatch notification to client
            \App\Models\MarketplaceNotification::create([
                'user_id' => $job->client_id,
                'type' => 'new_bid',
                'title' => 'Nouvelle offre reçue',
                'message' => "Vous avez reçu une nouvelle offre pour votre demande '{$job->title}'.",
                'related_type' => 'job',
                'related_id' => $job->id
            ]);

            return response()->json(['message' => 'Bid submitted successfully', 'bid' => $bid], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['message' => 'You have already placed a bid on this job.'], 409);
            }
            throw $e;
        }
    }

    public function accept(Request $request, \App\Models\Bid $bid)
    {
        $job = $bid->job;

        if (\Illuminate\Support\Facades\Auth::id() !== $job->client_id) {
            return response()->json(['message' => 'Unauthorized. Only the job poster can accept bids.'], 403);
        }

        try {
            $acceptedBid = $this->bidService->acceptBid($job, $bid);
            return response()->json(['message' => 'Bid accepted successfully', 'bid' => $acceptedBid]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
