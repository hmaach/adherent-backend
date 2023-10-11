<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $filieres = [];

        if ($user->role === "admin" || $user->role === "formateur") {
            $filieres = Filiere::select(
                "id",
                "libelle",
                "extention",
                "niveau"
            )->get();
        }

        return response([
            'filieres' => $filieres
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
    public function show(Filiere $filiere)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filiere $filiere)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filiere $filiere)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filiere $filiere)
    {
        //
    }
}
