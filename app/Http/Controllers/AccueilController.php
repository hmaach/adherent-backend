<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Poste;
use \App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccueilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notification = Notification::orderBy('dateNotif', 'desc')->limit(3)->get();
        $stagiaires = User::whereNotNull('id_groupe')->inRandomOrder()->limit(4)->get();
        $postes = Poste::orderBy('created_at', 'desc')->simplePaginate(4);
        //dd($notification);
        return view('index',[
            'notifications'=>$notification,
            'stagiaires'=>$stagiaires,
            'postes'=>$postes,
        ]);
    }
/*    public function rechercher(Request $request){
        $user = new User();
        $resultat = $user->search($request->query);
        return $resultat;
    }*/
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
