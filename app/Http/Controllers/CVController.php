<?php

namespace App\Http\Controllers;

use App\Models\CV;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class CVController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function generate(string $id)
    {
        $stagiaire = User::with('cv', 'interets', 'groupe', 'competences', 'experiences', 'formations', 'groupe.filiere')
            ->where('id', $id)
            ->where('role', 'stagiaire')
            ->first();

        if ($stagiaire) {
            if ($stagiaire->cv) {
                $birthDate = $stagiaire->cv->dateNais;
                $now = \Carbon\Carbon::now();
                $age = $now->diffInYears($birthDate) ;
                $stagiaire->age = $age;
                $nom = $stagiaire->nom;
                $email = $stagiaire->email;
                $tel = $stagiaire->tel;
                $cv = $stagiaire->cv;
                $formations = $stagiaire->formations;
                $experiences = $stagiaire->experiences;
                $competences = $stagiaire->competences;
                $interets= $stagiaire->interets;
                $prenom = $stagiaire->prenom;
                $fullName = $nom." ".$prenom;
                $stagiaire->fullName = $fullName;
                $data = $stagiaire->toArray();
                if (view()->exists('documents.cv')) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.cv', compact('fullName', 'email', 'tel', 'age', 'cv', 'formations', 'experiences', 'competences','interets'));
                    return $pdf->download($nom . "_" . $prenom.'.pdf');
                } else {
                    return response()->json([
                        "message" => "View 'documents.cv' not found"
                    ], 404);
                }
            }
        }

        return response()->json([
            "message" => "404"
        ], 404);
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
    public function show(CV $cV)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CV $cV)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CV $cV)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CV $cV)
    {
        //
    }
}
