<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateAdherentProfilRequest;
use App\Models\Adherent;
use App\Http\Requests\StoreAdherentRequest;
use App\Http\Requests\UpdateAdherentRequest;
use App\Models\Announce;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdherentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $adherent = Adherent::where('user_id', $id)->with('secteur')->first();

        if ($adherent) {
            $ratings = $adherent->rating;
            $user = Auth::user();

            if ($ratings->isNotEmpty()) {
                $averageRating = round($ratings->avg('value'), 1);
                $adherent->rating = $averageRating;
            } else {
                $adherent->average_rating = 0.0;
            }
            $adherent->unsetRelation('rating');

            if ($user) {
                $myRating = Rating::where('user_id', $user->id)
                    ->where('adherent_id', $adherent->id)
                    ->first();
                if ($myRating) {
                    $adherent->myRating = intval($myRating->value);
                } else {
                    $adherent->myRating = 0;
                }
            }

            return $adherent;
        } else {
            return response()->json(['message' => 'Adherent not found'], 404);
        }
    }

    public function rate(StoreRatingRequest $request, string $id)
    {
        $user = Auth::user();
        $adherent = Adherent::where('user_id', $id)->first();

        if ($user && $adherent) {
            if ($user->id == $adherent->user_id) {
                return response()->json(['message' => "Vous ne pouvez pas donner votre avis sur vous-même"]);
            }

            $rating = Rating::where('user_id', $user->id)
                ->where('adherent_id', $adherent->id)
                ->first();

            if ($rating) {
                $rating->value = $request->input('value'); // Update the rating value
                $rating->save();
            } else {
                $newRating = new Rating();
                $newRating->user_id = $user->id;
                $newRating->adherent_id = $adherent->id;
                $newRating->value = $request->input('value');
                $newRating->save();
            }

            return response()->json(['message' => "success"]);
        }

        return response()->json(['message' => "User or Adherent not found"], 404);
    }

    public function randomFouradherent()
    {
        $adherent = Adherent::inRandomOrder()
            ->limit(4)
            ->get();
        return $adherent;
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
    public function store(StoreAdherentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Adherent $adherent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adherent $adherent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdherentRequest $request, string $id)
    {
        try {
            $adherent = Adherent::where('user_id', $id)->first();
            $user = Auth::user();

            if (!$adherent) {
                return response()->json(['message' => 'Adherent not found'], 404);
            }

            if ($user) {
                if ($user->id === $adherent->user_id || $user->role === "admin") {
                    $adherent->update($request->only(
                        [
                            'secteur_id',
                            'propos',
                            'profession',
                            'ville',
                        ]
                    ));
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update failed'], 500);
        }
    }


    public function updateImage(UpdateAdherentProfilRequest $request, string $id)
    {
//        return $request->hasFile('img');
        $adherent = Adherent::where('user_id', $id)->first();
        $user = Auth::user();

        if (!$adherent) {
            return response()->json(['message' => 'Adherent not found'], 404);
        }

        if ($user && ($user->id === $adherent->user_id || $user->role === "admin")) {
            if ($request->hasFile('img')) {
                $image = $request->file('img');

                if ($adherent->img_path) {
                    Storage::delete($adherent->img_path);
                }

                $imageName = $image->store('public/adherents');
                $adherent->img_path = substr($imageName, 7);
                $adherent->save();

                return response()->json(['message' => 'success']);
            } else {
                return response()->json(['message' => 'No image file found in the request'], 422);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function updateProfilAdherent(Request $request, string $id)
    {
        $adherent = Adherent::where('user_id', $id)->first();
        $user = Auth::user();

        if (!$adherent) {
            return response()->json(['message' => 'Adherent not found'], 404);
        }
        if ($user->id === $adherent->user_id) {
            if ($request->hasFile('img')) {
                $image = $request->file('img');

                if ($adherent->img_path) {
                    Storage::delete($adherent->img_path);
                }

                $imageName = $image->store('public/adherents');
                $adherent->img_path = substr($imageName, 7);
                $adherent->save();

                return response()->json(['message' => 'success', 'img_path' => $adherent->img_path]);
            } else {
                return response()->json(['message' => 'No image file found in the request'], 422);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }


    public function removeProfilAdherent(string $id)
    {
        $user = Auth::user();
        $adherent = Adherent::where('user_id', $user->id)->first();

        if (!$adherent) {
            return response()->json(['message' => 'Adherent not found'], 404);
        }

        if ($user->id === $adherent->user_id) {
            if ($adherent->img_path) {
                Storage::delete($adherent->img_path);
            }
            $adherent->img_path = null;
            $adherent->save();

            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public
    function removeImage(Request $request, string $id)
    {
        $adherent = Adherent::where('user_id', $id)->first();
        $user = Auth::user();

        if (!$adherent) {
            return response()->json(['message' => 'Adherent not found'], 404);
        }

        if ($user->id === $adherent->user_id) {

            if ($adherent->img_path) {
                Storage::delete($adherent->img_path);
            }
            $adherent->img_path = null;
            $adherent->save();

            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(Adherent $adherent)
    {
        //
    }
}
