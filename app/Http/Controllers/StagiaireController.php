<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\CV;

use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Interet;







use App\Models\Photo;

class StagiaireController extends Controller
{

    public function randomFourStagiaires()
    {

//        $stagiaires = User::with("groupe.filiere")
//            ->whereNotNull('groupe_id')
//            ->inRandomOrder()
//            ->limit(4)
//            ->get();

        $stagiaires = User::with("groupe.filiere")
            ->whereIn('id', [1, 2, 3, 4])
            ->get();


        return response([
            "stagiaires" => $stagiaires
        ]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        $stagiaire = User::with('cv')
            ->find($id);

        if (!$stagiaire) {
            return response([
                "message" => "Stagiaire not found"
            ], 404);
        }

        $stagiaire->load('interets', 'groupe', 'competences', 'experiences', 'formations', 'groupe.filiere');


        $age = null;
        if ($stagiaire->cv) {
            $birthDate = $stagiaire->cv->dateNais;
            $now = \Carbon\Carbon::now();
            $age = $now->diffInYears($birthDate);
        }

        return response([
            "stagiaire" => $stagiaire,
            "age" => $age
        ]);
    }

    public function show($id)
    {

        $stagiaire = User::with('cv', 'interets', 'groupe', 'competences', 'experiences', 'formations', 'groupe.filiere')
            ->find($id);

        if (!$stagiaire) {
            return response()->json([
                'message' => 'Stagiaire not found'
            ], 404);
        }

        // Pass the retrieved stagiaire data to a view and return the generated CV page
        return view('cv.show', ['stagiaire' => $stagiaire]);
    }




    public function update(Request $request, $id)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $cv = $stagiaire->cv;

            if ($cv) {
                $cv->propos = $request->input('propos');
                $cv->update();

                return response()->json([
                    'message' => 'CV propos updated successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'CV not found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ]);
        }
    }

    public function updateCompetences(Request $request, $id, $competenceId)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $competence = Competence::find($competenceId);

            if ($competence) {
                $competence->categorie = $request->input('categorie');
                $competence->desc = $request->input('desc');
                $competence->save();

                return response()->json([
                    'message' => 'Competence updated successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'Competence not found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ]);
        }
    }

    public function addCompetence(Request $request, $id)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $competence = new Competence;
            $competence->categorie = $request->input('categorie');
            $competence->desc = $request->input('desc');
            $stagiaire->competences()->save($competence);

            return response()->json([
                'message' => 'Competence added successfully',
                'competence' => $competence
            ]);
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ], 404);
        }
    }
    public function addExperience(Request $request, $id)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $experience = new Experience;
            $experience->titre = $request->input('titre');
            $experience->dateDeb = $request->input('dateDeb');
            $experience->dateFin = $request->input('dateFin');
            $experience->mission = $request->input('mission');
            $stagiaire->experiences()->save($experience);

            return response()->json([
                'message' => 'Experience added successfully',
                'experience' => $experience
            ]);
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ], 404);
        }
    }

    public function updateExperience(Request $request, $id, $experienceId)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $experience = $stagiaire->experiences()->find($experienceId);

            if ($experience) {
                $experience->titre = $request->input('titre');
                $experience->dateDeb = $request->input('dateDeb');
                $experience->dateFin = $request->input('dateFin');
                $experience->mission = $request->input('mission');
                $experience->save();

                return response()->json([
                    'message' => 'Experience updated successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'Experience not found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ]);
        }
    }





    public function addPropos(Request $request, $id)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $propos = $request->input('propos');

            // Create a new CV propos
            $cv = new CV();
            $cv->propos = $propos;

            // Save the CV propos
            $stagiaire->cv()->save($cv);

            return response()->json([
                'message' => 'CV propos added successfully',
                'cv' => $cv
            ]);
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ], 404);
        }
    }
    public function addFormation(Request $request, $id)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $formation = new Formation;
            $formation->titre = $request->input('titre');
            $formation->institut = $request->input('institut');
            $formation->dateFin = $request->input('dateFin');
            $stagiaire->formations()->save($formation);

            return response()->json([
                'message' => 'Formation added successfully',
                'formation' => $formation
            ]);
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ], 404);
        }
    }

    public function updateFormation(Request $request, $id, $formationId)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $formation = $stagiaire->formations()->find($formationId);

            if ($formation) {
                $formation->titre = $request->input('titre');
                $formation->institut = $request->input('institut');
                $formation->dateFin = $request->input('dateFin');
                $formation->save();

                return response()->json([
                    'message' => 'Formation updated successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'Formation not found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ]);
        }
    }

    public function addInteret(Request $request, $id)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $interet = new Interet;
            $interet->libelle = $request->input('libelle');
            $stagiaire->interets()->save($interet);

            return response()->json([
                'message' => 'Interet added successfully',
                'interet' => $interet
            ]);
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ], 404);
        }
    }

    public function updateInteret(Request $request, $id, $interetId)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $interet = $stagiaire->interets()->find($interetId);

            if ($interet) {
                $interet->libelle = $request->input('libelle');
                $interet->save();

                return response()->json([
                    'message' => 'Interet updated successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'Interet not found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ]);
        }
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




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function handleSaveProfilePicture(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user) {
            // Check if a file was uploaded
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');

                // Generate a unique filename
                $filename = time() . '_' . $file->getClientOriginalName();

                // Store the file in the storage/app/public/profile_pictures directory
                $path = $file->storeAs('public/profile_pictures', $filename);

                // Create a new photo instance
                $photo = new Photo();
                $photo->user_id = $user->id;
                $photo->path = str_replace("public/", "", $path);
                $photo->save();

                return response()->json([
                    'message' => 'Profile picture saved successfully',
                    'path' => $photo->path, // Return the saved profile picture path
                    'user_id' => $photo->user_id, // Return the user ID associated with the photo
                ]);
            } else {
                return response()->json([
                    'message' => 'No file uploaded',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

}
