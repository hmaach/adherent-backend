<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateAdherentProfilRequest;
use App\Models\Adherent;
use App\Http\Requests\StoreAdherentRequest;
use App\Http\Requests\UpdateAdherentRequest;
use App\Models\Announce;
use App\Models\Rating;
use App\Models\Secteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdherentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $adherent = Adherent::where('user_id', $id)->with(['secteur', 'user'])->first();

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
            $user = \App\Models\User::find($id);
            if ($user) {
                return response()->json([
                    'is_only_user' => true,
                    'user_id' => $user->id,
                    'user' => $user
                ], 200);
            }
            return response()->json(['message' => 'Adherent not found'], 404);
        }
    }

    public function adherentsIndex(Request $request)
    {
        $query = Adherent::with('user');

        $cities = $request->input('cities');
        if ($cities) {
            if (is_string($cities)) {
                $cities = explode(',', $cities);
            }
            if (is_array($cities)) {
                $query->whereIn('ville', $cities);
            }
        }

        $secteurId = $request->input('secteur_id');
        if ($secteurId) {
//            $sectuer = Secteur::find($secteurId);
            $query->where('secteur_id', $secteurId);
        }

        $search = $request->input('search');
        if ($search != "") {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                    ->orWhere('profession', 'like', "%$search%")
                    ->orWhere('ville', 'like', "%$search%")
                    ->orWhere('propos', 'like', "%$search%");
            });
        }

        $sort = $request->input('sort');
        if ($sort === "recent") {
            $query->orderBy("created_at");
        } elseif ($sort === "ancien") {
            $query->orderBy("created_at", "desc");
        } elseif ($sort === "rating") {
            $query->orderByDesc(DB::raw('(SELECT AVG(value) FROM ratings WHERE adherent_id = adherents.id)'));
        }

        $adherents = $query->inRandomOrder()->paginate(7);

        foreach ($adherents as $adherent) {
            $ratings = $adherent->rating;

            if ($ratings->isNotEmpty()) {
                $averageRating = round($ratings->avg('value'), 1);
                $adherent->rating = $averageRating;
            } else {
                $adherent->rating = 0.0;
            }
            $adherent->unsetRelation('rating');
        }


        return $adherents;
    }


//    public function adherentsIndex()
//    {
//        $adherents = Adherent::inRandomOrder()->paginate(7);
//
//        foreach ($adherents as $adherent) {
//            if ($adherent) {
//                $ratings = $adherent->rating;
//
//                if ($ratings->isNotEmpty()) {
//                    $averageRating = round($ratings->avg('value'), 1);
//                    $adherent->rating = $averageRating;
//                } else {
//                    $adherent->average_rating = 0.0;
//                }
//                $adherent->unsetRelation('rating');
//            }
//        }
//        return $adherents;
//    }


    public function rate(StoreRatingRequest $request, string $id)
    {
        $user = Auth::user();
        $adherent = Adherent::where('user_id', $id)->first();

        if ($user && $adherent) {
            if ($user->id == $adherent->user_id) {
                return response()->json(['message' => "Vous ne pouvez pas donner votre avis sur vous-même"], 403);
            }

            $hasAcceptedJobWithAdherent = \App\Models\Bid::where('adherent_id', $adherent->id)
                ->where('status', 'accepted')
                ->whereHas('job', function ($query) use ($user) {
                    $query->where('client_id', $user->id)
                        ->whereIn('status', ['in_progress', 'closed']);
                })
                ->exists();

            if (!$hasAcceptedJobWithAdherent) {
                return response()->json([
                    'message' => "Vous pouvez noter uniquement un adhérent avec qui vous avez une demande acceptée."
                ], 403);
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
        $adherent = Adherent::with('user')->inRandomOrder()
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
    public function destroy(Adherent $adherent)
    {
        //
    }

    public function adminAbonnementsIndex(Request $request)
    {
        $query = Adherent::with('user', 'secteur');

        $search = $request->input('search');
        if ($search != "") {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })->orWhere('profession', 'like', "%$search%");
        }

        $status = $request->input('status');
        if ($status && $status !== 'all') {
            $query->where('subscription_status', $status);
        }

        $perPage = $request->input('per_page', 25);
        $adherents = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($adherents);
    }

    public function updateAbonnement(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['active', 'expired', 'pending', 'cancelled'])],
            'end_date' => 'nullable|date',
            'payment_reference' => 'nullable|string|max:120',
            'payment_admin_notes' => 'nullable|string|max:2000',
        ]);

        $adherent = Adherent::with('user')->find($id);
        if (!$adherent) {
            return response()->json(['message' => 'Adherent not found'], 404);
        }

        $oldStatus = $adherent->subscription_status;
        $adherent->subscription_status = $validated['status'] ?? $adherent->subscription_status;
        $adherent->subscription_end_date = $validated['end_date'] ?? $adherent->subscription_end_date;
        $adherent->payment_reference = $validated['payment_reference'] ?? $adherent->payment_reference;
        $adherent->payment_admin_notes = $validated['payment_admin_notes'] ?? $adherent->payment_admin_notes;

        if ($adherent->subscription_status === 'active') {
            $adherent->paid_at = $adherent->paid_at ?? now();
            $adherent->subscription_end_date = $adherent->subscription_end_date ?? now()->addYear()->toDateString();
        } elseif ($oldStatus === 'active' && $adherent->subscription_status !== 'active') {
            $adherent->paid_at = null;
        }

        $adherent->save();

        if ($adherent->user) {
            if ($adherent->subscription_status === 'active') {
                $adherent->user->role = 'adherent';
            } else {
                $adherent->user->role = 'user';
            }
            $adherent->user->save();
        }

        return response()->json(['message' => 'success', 'adherent' => $adherent]);
    }

    public function contactAdherent(Request $request, string $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $adherent = Adherent::with('user')->find($id);
        if (!$adherent || !$adherent->user) {
            return response()->json(['message' => 'Adherent not found'], 404);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        \Illuminate\Support\Facades\Mail::to($adherent->user->email)->send(
            new \App\Mail\ContactAdherentMail($user->nom . ' ' . $user->prenom, $user->email, $request->message)
        );

        return response()->json(['message' => 'Message sent successfully']);
    }

    public function upgrade(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role === 'adherent') {
            return response()->json(['message' => 'Vous êtes déjà un adhérent.'], 400);
        }

        $validated = $request->validate([
            'secteur_id' => 'required|exists:secteurs,id',
            'profession' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'propos' => 'nullable|string|max:1000',
            'payment_method' => ['required', Rule::in(['bank_transfer', 'cash', 'other'])],
            'payment_reference' => 'nullable|string|max:120',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('public/adherent-payments');
            $paymentProofPath = substr($paymentProofPath, 7);
        }

        $adherent = Adherent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'secteur_id' => $validated['secteur_id'],
                'profession' => $validated['profession'],
                'ville' => $validated['ville'],
                'propos' => $validated['propos'] ?? null,
                'subscription_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'payment_proof_path' => $paymentProofPath,
            ]
        );

        // Update fields if it already existed but was inactive
        if (!$adherent->wasRecentlyCreated) {
            if ($paymentProofPath && $adherent->payment_proof_path) {
                Storage::delete('public/' . $adherent->payment_proof_path);
            }

            $adherent->update([
                'secteur_id' => $validated['secteur_id'],
                'profession' => $validated['profession'],
                'ville' => $validated['ville'],
                'propos' => $validated['propos'] ?? $adherent->propos,
                'subscription_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? $adherent->payment_reference,
                'payment_proof_path' => $paymentProofPath ?? $adherent->payment_proof_path,
                'paid_at' => null,
            ]);
        }

        return response()->json([
            'message' => 'Demande d\'adhésion soumise. En attente de paiement et de validation.',
            'adherent' => $adherent
        ]);
    }
}
