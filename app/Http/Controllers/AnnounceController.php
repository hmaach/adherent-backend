<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\Announce;
use App\Http\Requests\StoreAnnounceRequest;
use App\Http\Requests\UpdateAnnounceRequest;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnounceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $user = Auth::user();
        if ($user && $user->id == $id) {
            $announces = Announce::where('user_id', $id)->get();
        } else {
            $announces = Announce::where('user_id', $id)->where('approved', 1)->get();
        }
        return $announces;
    }

    public function getAnnounces()
    {
        $announces = Announce::where('approved', 1)->latest('created_at')->get();
        $evenements = Evenement::latest('created_at')->get();

        $mergedData = new Collection([...$announces->toArray(), ...$evenements->toArray()]);

        $mergedData->transform(function ($item) {
            $item['type'] = isset($item['type']) ? $item['type'] : (isset($item['evenement_type']) ? 'evenement' : 'announce');
            return $item;
        });

        $sortedData = $mergedData->sortByDesc('created_at')->values();

        $perPage = 7;
        $currentPage = request()->input('page', 1);
        $paginatedData = new LengthAwarePaginator(
            $sortedData->forPage($currentPage, $perPage),
            $sortedData->count(),
            $perPage,
            $currentPage
        );

        return $paginatedData;
    }


    public function searchAnnounces($q)
    {
        $announces = Announce::where('approved', 1)
            ->where(function ($query) use ($q) {
                $query->where('desc', 'like', "%$q%");
            })
            ->latest('created_at')
            ->get();

        $evenements = Evenement::where(function ($query) use ($q) {
            $query->where('titre', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%");
        })
            ->latest('created_at')
            ->get();

        $mergedData = new Collection([...$announces->toArray(), ...$evenements->toArray()]);

        $mergedData->transform(function ($item) {
            $item['type'] = isset($item['type']) ? $item['type'] : (isset($item['evenement_type']) ? 'evenement' : 'announce');
            return $item;
        });

        $sortedData = $mergedData->sortByDesc('created_at')->values();

        $perPage = 7;
        $currentPage = request()->input('page', 1);
        $paginatedData = new LengthAwarePaginator(
            $sortedData->forPage($currentPage, $perPage),
            $sortedData->count(),
            $perPage,
            $currentPage
        );

        return $paginatedData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnnounceRequest $request)
    {
        $user = Auth::user();

        if ($user && $user->role === "adherent") {
            try {
                $announce = new Announce();
                $announce->user_id = $user->id;
                $announce->order = $request->order;
                $announce->desc = $request->desc;
                $announce->debut = $request->debut;
                $announce->fin = $request->fin;

                if ($request->hasFile('img')) {
                    $image = $request->file('img');
                    $imageName = $image->store('public/announces');
                    $announce->img = substr($imageName, 7);
                } else {
                    $announce->img = null;
                }

                $announce->save();

                return response()->json(['message' => 'success', 'path' => $announce->img]);
            } catch (\Exception $e) {
                dd($e);
                return response()->json(['message' => 'Échec de la création'], 500);
            }
        } else {
            return response()->json(['message' => "Vous n'avez pas le droit de publier"], 401);
        }
    }

    public function approve(string $id)
    {
        try {
            $announce = Announce::find($id);
            if ($announce) {
                $announce->approved = true;
                $announce->save();

                return response()->json(['message' => "success"]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => "Échec de l'approuvement"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Announce $announce)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $data = $request->all();

        if (isset($data['announces'])) {
            foreach ($data['announces'] as $announceData) {
                $announce = Announce::where('id', $announceData['id'])->first();
                if ($announce) {
                    $announce->update($announceData);
                }
            }
        }

        if (isset($data['deletedAnnounce'])) {
            foreach ($data['deletedAnnounce'] as $deletedAnnounceId) {
                Announce::where('id', $deletedAnnounceId)->delete();
            }
        }

        return response()->json(['message' => 'success']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnnounceRequest $request, Announce $announce)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $announce = Announce::find($id);

        if ($user) {
            if ($user->role === "admin" || $user->id === $announce->user_id) {
                try {
                    if ($announce) {
                        if ($announce->img) {
                            Storage::delete($announce->img);
                        }
                        $announce->delete();
                        return response()->json(['message' => "Announce deleted successfully"]);
                    } else {
                        return response()->json(['message' => "Announce not found"], 404);
                    }
                } catch (\Exception $e) {
                    return response()->json(['message' => "An error occurred while deleting the announce"], 500);
                }
            } else {
                return response()->json(['message' => "Vous n'avez pas le droit de supprimer une annonce"], 401);
            }
        } else {
            return response()->json(['message' => "Unauthorized"], 401);
        }
    }
}
