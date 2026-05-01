<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $bid;

    public function __construct(\App\Models\Bid $bid)
    {
        $this->bid = $bid;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $clientPhone = $this->bid->job->client->tel ?? 'Non spécifié';
        return (new MailMessage)
            ->subject('Félicitations ! Votre offre a été acceptée.')
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Le client a accepté votre offre pour le projet : ' . $this->bid->job->title)
            ->line('Vous pouvez maintenant contacter le client pour commencer le travail.')
            ->line('Numéro de téléphone du client : ' . $clientPhone)
            ->action('Voir les détails du projet', url('/stagiaire'))
            ->line('Merci de faire partie de notre réseau professionnel !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'bid_accepted',
            'job_id' => $this->bid->job_id,
            'job_title' => $this->bid->job->title,
            'client_phone' => $this->bid->job->client->tel,
            'message' => 'Félicitations ! Votre offre a été acceptée pour: ' . $this->bid->job->title,
        ];
    }
}
