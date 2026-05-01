<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Bid;
use Illuminate\Support\Facades\DB;
use Exception;

class BidService
{
    /**
     * Accept a bid safely using DB Transactions.
     */
    public function acceptBid(Job $job, Bid $winningBid)
    {
        if ($job->status !== 'open') {
            throw new Exception("This job is no longer open.");
        }

        if ($winningBid->job_id !== $job->id) {
            throw new Exception("This bid does not belong to this job.");
        }

        DB::transaction(function () use ($job, $winningBid) {
            // 1. Mark winning bid
            $winningBid->update([
                'status' => 'accepted',
                'accepted_at' => now()
            ]);

            // 2. Mark job in progress
            $job->update([
                'status' => 'in_progress',
                'closed_at' => now()
            ]);

            // 3. Reject other bids
            Bid::where('job_id', $job->id)
                ->where('id', '!=', $winningBid->id)
                ->update(['status' => 'rejected']);
                
        });

        // 4. Queue notification to winner outside transaction
        if ($winningBid->adherent && $winningBid->adherent->user_id) {
            \App\Models\MarketplaceNotification::create([
                'user_id' => $winningBid->adherent->user_id,
                'type' => 'bid_accepted',
                'title' => '🎉 Offre acceptée !',
                'message' => "Votre offre pour '{$job->title}' a été acceptée par le client.",
                'related_type' => 'bid',
                'related_id' => $winningBid->id
            ]);
        }

        return $winningBid;
    }
}
