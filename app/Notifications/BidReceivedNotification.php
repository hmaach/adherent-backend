<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $bid;

    public function __construct(\App\Models\Bid $bid)
    {
        $this->bid = $bid;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Add 'mail' later if needed
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'bid_received',
            'job_id' => $this->bid->job_id,
            'job_title' => $this->bid->job->title,
            'bid_id' => $this->bid->id,
            'adherent_name' => $this->bid->adherent->user->nom . ' ' . $this->bid->adherent->user->prenom,
            'price_quote' => $this->bid->price_quote,
            'message' => 'Vous avez reçu une nouvelle offre pour votre annonce: ' . $this->bid->job->title,
        ];
    }
}
