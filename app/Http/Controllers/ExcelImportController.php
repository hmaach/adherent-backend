<?php

namespace App\Http\Controllers;

use App\Imports\StagiaresImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stagiaire;

class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        try {
            Excel::import(new StagiaresImport, $request->file('file'));
            return response()->json(['message' => 'Import successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Import failed: ' . $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $stagiaires = Stagiaire::inRandomOrder()->limit(10)->get();
        return response()->json($stagiaires);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Stagiaire::where('Nom', 'like', "%$query%")
            ->orWhere('Prenom', 'like', "%$query%")
            ->orWhere('MatriculeEtudiant', 'like', "%$query%")
            ->orWhere('CIN', 'like', "%$query%")
            ->orWhere('NTelelephone', 'like', "%$query%")
            ->orWhere('NTel_du_Tuteur', 'like', "%$query%")
            ->orWhere('CodeDiplome', 'like', "%$query%")
            ->orWhere('id_inscriptionsessionprogramme', 'like', "%$query%")
            ->orWhere('MatriculeEtudiant', 'like', "%$query%")
            ->orWhere('CIN', 'like', "%$query%")
            ->orWhere('NTelelephone', 'like', "%$query%")
            ->get();

        return response()->json($results);
    }

    public function filterByYear(Request $request)
    {
        $year = $request->input('year');

        $results = Stagiaire::where('anneeEtude', $year)->get();

        return response()->json($results);
    }
}
