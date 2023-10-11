<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->input('type');
        $filieres = [];

        $query = Evenement::select(
            'evenements.id as id',
            'evenements.titre as title',
            'evenements.dateDeb as start',
            'evenements.dateFin as end',
            'evenements.color',
        )
            ->orderBy('created_at', 'desc');

        if ($type === 'own' && Auth::check()) {
            $user = Auth::user();
            $query->where('evenements.user_id', $user->id);
        } else {
            if (Auth::check()) {
                $user = Auth::user();
                $filieres = [];

                if ($user->role === "admin") {
                    $filieres = Filiere::all(['id', 'libelle']);
                    $query->where(function ($query) {
                    });
                } elseif ($user->role === "formateur") {
                    $filieres = Filiere::all(['id', 'libelle']);
                    $query->where(function ($query) {
                        $query->where('audience', 'public')
                            ->orWhere('audience', 'etablissement')
                            ->orWhere('audience', 'formateurs');
                    });
                } else {
                    $query->where(function ($query) use ($user) {
                        $query->where('audience', 'public')
                            ->orWhere('audience', 'etablissement')
                            ->orWhere(function ($subquery) use ($user) {
                                $subquery->where('audience', 'filiere')
                                    ->whereIn('audience_id', function ($subsubquery) use ($user) {
                                        $subsubquery->select('groupes.filiere_id')
                                            ->from('groupes')
                                            ->where('groupes.id', '=', $user->groupe_id);
                                    });
                            });
                    });
                }
            } else {
                $query->where('audience', 'public');
            }
        }
        $evenements = $query->get();
        return response([
            'evenements' => $evenements,
            'filieres' => $filieres,
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
        $user = Auth::user();
        if (!$user) {
            return response([
                'message' => "Vous n'avez pas le droit d'ajouter une événement",
            ]);
        }
        $event = new Evenement();
        $event->user_id = $user->id;
        $event->titre = $request->titre;
        $event->description = $request->description;
        $event->dateDeb = $request->dateDeb;
        $event->dateFin = $request->dateFin;
        $event->audience = $request->audience;
        $event->color = $request->color;
        $event->audience_id = $request->audience_id;
        $event->save();
        return response([
            'message' => "success",
            'event_id' => $event->id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Evenement $evenement)
    {
        return response([
            "evenement" => $evenement
        ]);
    }

    public function showEvents(Request $request)
    {
        $ids = $request->input('ids');
        $events = Evenement::whereIn('evenements.id', $ids)
            ->select(
                'evenements.id as id',
                'evenements.titre as title',
//                'evenements.description',
                'evenements.dateDeb as start',
//                'evenements.dateFin as end',
//                'evenements.color',
//                'users.id as user_id',
//                'users.nom',
//                'users.prenom',
//                'users.role',
//                'filieres.extention as filiere_extention'
            )
            ->join('users', 'users.id', '=', 'evenements.user_id')
            ->leftJoin('filieres', 'filieres.id', '=', 'evenements.audience_id')
            ->orderBy('evenements.created_at', 'desc')
            ->get();


        return response([
            "events" => $events
        ]);


    }

    public function thisMonthEvents()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $events = Evenement::whereMonth('dateDeb', $currentMonth)
            ->whereYear('dateDeb', $currentYear)
            ->select('id', 'dateDeb as start')
            ->get();

        return response([
            "events" => $events
        ]);
    }

    public function getByDay(Request $request)
    {
        $dateTime = $request->input("datetime");

        $events = Evenement::where('dateDeb', '>=', $dateTime)
            ->where('dateFin', '<=', $dateTime)
            ->get();

        return response([
            "events" => $events
        ]);
    }

    public function cancelEvent(Evenement $evenement)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->id === $evenement->user_id || $user->role === "admin") {
                $evenement->oldColor = $evenement->color;
                $evenement->color = "red";
                $evenement->save();
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'success']);
    }

    public function restoreEventColor(Evenement $evenement)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->id === $evenement->user_id || $user->role === "admin") {
                $evenement->color = $evenement->oldColor;
                $evenement->save();
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'success']);
    }

    public function update(Request $request, string $id)
    {

        $evenement = Evenement::find($id);
        $user = Auth::user();
        if ($user) {
            if ($user->id === $evenement->user_id || $user->role === "admin") {
                $evenement->update($request->only(
                    [
                        'titre',
                        'description',
                        'color',
                        'dateDeb',
                        'dateFin',
                        'audience',
                        'audience_id'
                    ]
                ));
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'success']);
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        $evenement = Evenement::find($id);
        if ($user) {
            if ($user->id === $evenement->user_id || $user->role === "admin") {
                $evenement->delete();
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'success']);
    }
}
