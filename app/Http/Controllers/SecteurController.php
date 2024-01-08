<?php

namespace App\Http\Controllers;

use App\Models\Secteur;
use App\Http\Requests\StoreSecteurRequest;
use App\Http\Requests\UpdateSecteurRequest;
use Illuminate\Http\Request;

class SecteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $secteurs = Secteur::all();
        return $secteurs;
    }




    public function searchSecteurs($q)
    {
        $secteurs = Secteur::where(function ($query) use ($q) {
            $query->where('lib', 'like', "%$q%");
        })
            ->latest('created_at')
            ->get();
        return $secteurs;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $secteur = new Secteur();
            $secteur->lib = $request->lib;
            $secteur->save();

            return response()->json(['message' => 'success', 'newRow' => $secteur]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['message' => 'Échec de la création'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $secteur = Secteur::find($id);
            $secteur->lib = $request->lib;
            $secteur->update();

            return response()->json(['message' => 'success', 'updatedRow' => $secteur]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['message' => 'Échec de la création'], 500);
        }
    }



    public function destroy(string $id)
    {
        $secteur = Secteur::find($id);

        try {
            if ($secteur) {

                $secteur->delete();
                return response()->json(['message' => "success"]);
            } else {
                return response()->json(['message' => "Secteur not found"], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => "An error occurred while deleting the announce"], 500);
        }
    }
}
