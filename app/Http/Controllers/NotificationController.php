<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with(['poste.user', 'evenement.user'])
            ->orderBy('dateNotif', 'desc')
            ->take(5)
            ->get();

        $formattedNotifs = $notifications->map(function ($notif) {
            if ($notif->poste) {
                return [
                    'id' => $notif->id,
                    'dateNotif' => $notif->dateNotif,
                    'poste' => $notif->poste->libelle,
                    'nom' => $notif->poste->user->nom ?? '',
                    'prenom' => $notif->poste->user->prenom ?? '',
                ];
            } else if ($notif->evenement) {
                return [
                    'id' => $notif->id,
                    'dateNotif' => $notif->dateNotif,
                    'event' => $notif->evenement->titre,
                    'nom' => $notif->evenement->user->nom ?? '',
                    'prenom' => $notif->evenement->user->prenom ?? '',
                ];
            }
            return null;
        })->filter()->values();

        return response()->json(['notifs' => $formattedNotifs], 200);
    }
}
