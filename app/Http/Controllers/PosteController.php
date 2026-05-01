<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosteRequest;
use App\Models\Filiere;
use App\Models\PDF;
use App\Models\PdfCategorie;
use App\Models\Photo;
use App\Models\Poste;
use App\Models\Poste_Photo;
use App\Models\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Scalar\String_;

class PosteController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 6;
        $type = $request->input('type');
        $q = $request->input('q');
        $filieres = [];

        $query = Poste::select(
            'postes.id as id',
            'users.id as user_id',
            'users.nom',
            'users.prenom',
            'users.role',
            'postes.libelle',
            'postes.type',
            'postes.audience',
            'postes.created_at',
            'filieres.extention as filiere_extention',
            DB::raw('coalesce(count(reacts.poste_id), 0) as reacts'),
            'reacts.user_id as liked',
            'p_d_f_s.path as pdf_path'
        )
            ->join('users', 'users.id', '=', 'postes.user_id')
            ->leftJoin('reacts', 'reacts.poste_id', '=', 'postes.id')
            ->leftJoin('filieres', 'filieres.id', '=', 'postes.audience_id')
            ->leftJoin('p_d_f_s', 'p_d_f_s.poste_id', '=', 'postes.id')
            ->groupBy('postes.id', 'reacts.user_id', 'p_d_f_s.path')
            ->orderBy('created_at', 'desc');

        if ($type === 'own' && Auth::check()) {
            $user = Auth::user();
            $query->where('postes.user_id', $user->id);
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

        if ($type != "all") {
            $query->where('postes.type', $type);
        }

//        if ($type === 'own' && isset($user)) {
//            $query->where('postes.user_id', $user->id);
//        }

        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->whereHas('user', function ($subquery) use ($q) {
                    $subquery->where('nom', 'LIKE', "%$q%")
                        ->orWhere('prenom', 'LIKE', "%$q%");
                })
                    ->orWhere('postes.libelle', 'LIKE', "%$q%");
            });
        }

        $postes = $query->get();
        foreach ($postes as $post) {
            if (isset($user)) {
                $post->liked = React::where('poste_id', $post->id)
                    ->where('user_id', $user->id)
                    ->exists();
            }
            $post->images = Photo::where('poste_id', $post->id)
                ->pluck('path')
                ->map(function ($path) {
                    return asset('storage/' . $path);
                })
                ->toArray();
        }

        $page = $request->query('page', 1);
        $postes = $postes->forPage($page, $perPage)->values();
        $hasMore = $postes->count() > $perPage;

        return response([
            'postes' => $postes,
            'filieres' => $filieres,
            'hasMore' => $hasMore,
        ]);
    }



    public
    function likePost(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Poste::find($postId);
        $react = new React();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($request->action === 'like') {
            $react->user_id = $user->id;
            $react->poste_id = $postId;
            $react->save();
        } else if ($request->action === 'dislike') {
            $reactDis = DB::table('reacts')
                ->where('user_id', $user->id)
                ->where('poste_id', $postId)
                ->first();
            if ($reactDis) {
                DB::table('reacts')
                    ->where('user_id', '=', $user->id)
                    ->where('poste_id', '=', $postId)
                    ->delete();
                return response()->json(['message' => 'React deleted successfully']);
            } else {
                return response()->json(['error' => 'React not found'], 404);
            }
        }
        return response()->json(['message' => 'Post successfully liked/disliked']);
    }

    public function store(PosteRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response([
                'message' => "Vous n'avez pas le droit de publier",
            ]);
        }

        $poste = new Poste();
        $poste->user_id = $user->id;
        $poste->libelle = $request->libelle;
        $poste->type = $request->type;
        $poste->audience = $request->audience;
        $poste->audience_id = $request->audience_id;
        $poste->save();

        $imageData = [];
        $imgs = $request->file('imgs');
        $imageData = [];

        if (is_array($imgs)) {
            foreach ($imgs as $img) {



                $imagePath = $img->store('public/imgs');
                $imageData[] = $imagePath;
                $photo = new Photo();
                $photo->poste_id = $poste->id;
                $photo->path = str_replace("public/", "", $imagePath);
                $photo->save();



            }
        }

        $pdfPath = '';
        if ($request->hasFile('pdf')) {
            $pdfFile = $request->file('pdf');
            $pdfPath = $pdfFile->store('pdfs');

            $pdf = new PDF();
            $pdf->path = $pdfPath;
            $pdf->libelle = $request->input('libelle_pdf');

            if ($request->pdfCategorieId) {
                $pdf->pdf_categorie_id = $request->pdfCategorieId;
            }

            $poste->pdf()->save($pdf);
        }

        return response([
            'message' => "success",
            'post_id' => $poste->id,
            'pdf_path' => $pdfPath,
            'image_data' => $imageData,
        ]);
    }


    public
    function show(Poste $poste)
    {
        //
    }

    public
    function update(PosteRequest $request)
    {
        $user = Auth::user();
        if ($user) {
            $id = $request->input('id');
            $poste = Poste::find($id);
            if ($poste) {
                $poste->user_id = $request->user_id;
                $poste->libelle = $request->libelle;
                $poste->type = $request->type;
                $poste->audience = $request->audience;
                if ($user->id == $poste->user_id) {
                    $poste->save();
                    return response([
                        'message' => "success"
                    ]);
                } else {
                    return response([
                        'message' => "Vous n'avez pas le droit de toucher ce poste"
                    ]);
                }
            } else {
                return response([
                    'message' => "Poste not found"
                ]);
            }
        } else {
            return response([
                'message' => "not logged in"
            ]);
        }
    }


    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $id = $request->input('id');
            $poste = Poste::find($id);
            $pdf = PDF::where('poste_id', $id)->first();
            $imgs = Photo::where('poste_id', $id)->get();

            if ($pdf) {
                $pdfPath = $pdf->path;
            }

            if ($poste) {
                if ($user->id === $poste->user_id || $user->role === "admin") {
                    if (!empty($pdfPath)) {
                        // Delete the PDF file
                        if (Storage::exists($pdfPath)) {
                            Storage::delete($pdfPath);
                        }
                    }

                    // Delete the images
                    foreach ($imgs as $img) {
                        $imgPath = $img->path;
                        if (Storage::exists($imgPath)) {
                            Storage::delete($imgPath);
                        }
                        $img->delete();
                    }

                    $poste->delete();
                    return response([
                        'message' => "success"
                    ]);
                } else {
                    return response([
                        'message' => "Vous n'avez pas le droit d'effacer ce poste"
                    ]);
                }
            } else {
                return response([
                    'message' => "Poste not found"
                ]);
            }
        } else {
            return response([
                'message' => "not logged in"
            ]);
        }
    }


    /**
     * Returns all posts created by adherents (for post agent moderation).
     */
    public function adherentPostesIndex(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page    = $request->input('page', 1);
        $q       = $request->input('q', '');

        $query = Poste::select(
                'postes.id as id',
                'users.id as user_id',
                'users.nom',
                'users.prenom',
                'users.role',
                'postes.libelle',
                'postes.type',
                'postes.audience',
                'postes.created_at',
                DB::raw('coalesce(count(reacts.poste_id), 0) as reacts')
            )
            ->join('users', 'users.id', '=', 'postes.user_id')
            ->leftJoin('reacts', 'reacts.poste_id', '=', 'postes.id')
            ->where('users.role', 'adherent')
            ->groupBy('postes.id', 'users.id', 'users.nom', 'users.prenom', 'users.role',
                      'postes.libelle', 'postes.type', 'postes.audience', 'postes.created_at')
            ->orderBy('postes.created_at', 'desc');

        $type     = $request->input('type', '');
        $audience = $request->input('audience', '');

        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('postes.libelle', 'LIKE', "%$q%")
                   ->orWhere('users.nom', 'LIKE', "%$q%")
                   ->orWhere('users.prenom', 'LIKE', "%$q%");
            });
        }

        if ($type) {
            $query->where('postes.type', $type);
        }

        if ($audience) {
            $query->where('postes.audience', $audience);
        }

        $postes = $query->paginate($perPage, ['*'], 'page', $page);

        // Attach images
        foreach ($postes as $post) {
            $post->images = Photo::where('poste_id', $post->id)
                ->pluck('path')
                ->map(fn($path) => asset('storage/' . $path))
                ->toArray();
        }

        return response()->json($postes);
    }

    /**
     * Allows a post_agent (or admin) to delete any adherent post.
     */
    public function destroyByPostAgent(Request $request, $id)
    {
        $user  = Auth::user();
        $poste = Poste::find($id);

        if (!$poste) {
            return response()->json(['message' => 'Poste not found'], 404);
        }

        // Make sure only adherent posts can be deleted via this route
        $author = \App\Models\User::find($poste->user_id);
        if (!$author || $author->role !== 'adherent') {
            return response()->json(['message' => 'Ce poste ne peut pas être géré par un agent.'], 403);
        }

        $pdf  = PDF::where('poste_id', $id)->first();
        $imgs = Photo::where('poste_id', $id)->get();

        if ($pdf && Storage::exists($pdf->path)) {
            Storage::delete($pdf->path);
            $pdf->delete();
        }

        foreach ($imgs as $img) {
            if (Storage::exists($img->path)) {
                Storage::delete($img->path);
            }
            $img->delete();
        }

        $poste->delete();

        return response()->json(['message' => 'success']);
    }

    /**
     * Bulk-delete multiple adherent posts (post agent).
     */
    public function bulkDestroyByPostAgent(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['message' => 'No IDs provided'], 422);
        }

        $deleted = 0;
        foreach ($ids as $id) {
            $poste  = Poste::find($id);
            if (!$poste) continue;

            $author = \App\Models\User::find($poste->user_id);
            if (!$author || $author->role !== 'adherent') continue;

            $pdf  = PDF::where('poste_id', $id)->first();
            $imgs = Photo::where('poste_id', $id)->get();

            if ($pdf && Storage::exists($pdf->path)) { Storage::delete($pdf->path); $pdf->delete(); }
            foreach ($imgs as $img) {
                if (Storage::exists($img->path)) Storage::delete($img->path);
                $img->delete();
            }

            $poste->delete();
            $deleted++;
        }

        return response()->json(['message' => 'success', 'deleted' => $deleted]);
    }

    /**
     * Returns post counts per type and per audience for the stats dashboard.
     */
    public function adherentPostesStats()
    {
        $base = Poste::join('users', 'users.id', '=', 'postes.user_id')
                     ->where('users.role', 'adherent');

        $total = (clone $base)->count();

        $byType = (clone $base)
            ->select('postes.type', DB::raw('count(*) as total'))
            ->groupBy('postes.type')
            ->pluck('total', 'postes.type');

        $byAudience = (clone $base)
            ->select('postes.audience', DB::raw('count(*) as total'))
            ->groupBy('postes.audience')
            ->pluck('total', 'postes.audience');

        // Posts in the last 7 days
        $recentCount = (clone $base)
            ->where('postes.created_at', '>=', now()->subDays(7))
            ->count();

        return response()->json([
            'total'       => $total,
            'recent'      => $recentCount,
            'by_type'     => $byType,
            'by_audience' => $byAudience,
        ]);
    }

}
