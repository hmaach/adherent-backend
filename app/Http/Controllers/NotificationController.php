<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifs = DB::table('notifications')
            ->leftJoin('postes', 'postes.id', '=', 'notifications.poste_id')
            ->leftJoin('evenements', 'evenements.id', '=', 'notifications.evenement_id')
            ->join('users', 'users.id', '=', 'postes.user_id')
            ->select(
                'notifications.id as id',
                'users.id as user_id',
                'evenements.titre as event',
                'postes.libelle as poste',
                'users.nom',
                'users.prenom',
                'notifications.dateNotif',
            )
            ->groupBy('notifications.id')
            ->orderBy('dateNotif', 'desc')
            ->limit(3)
            ->get();

        return response([
            'notifs' => $notifs
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
