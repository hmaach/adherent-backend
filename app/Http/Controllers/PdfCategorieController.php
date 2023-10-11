<?php

namespace App\Http\Controllers;

use App\Models\PdfCategorie;
use App\Models\Poste;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $category = new PdfCategorie();
        $filter = $request->input('filter');
        $limit = $request->input('limit', 7);
        $page = $request->input('page', 1);
        $q = $request->input('q');

        $users = null;
        $userWithCat = null;
        if ((int)$page === 1) {
            $users = User::whereHas('pdfCategories.pdfs')->get();
            $userWithCat = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'user_name' => $user->prenom . ' ' . $user->nom,
                ];
            });
        }

        $categories = PdfCategorie::has('pdfs')
            ->with(['pdfs' => function ($query) {
                $query->orderBy('updated_at', 'desc');
            }])
            ->withCount('pdfs')
            ->orderByDesc(function ($query) {
                $query->select('updated_at')
                    ->from('p_d_f_s')
                    ->whereColumn('pdf_categorie_id', 'pdf_categories.id')
                    ->orderByDesc('updated_at')
                    ->limit(1);
            });

        if ($q) {
            $categories = $categories->where(function ($queryBuilder) use ($q) {
                $queryBuilder->whereHas('user', function ($query) use ($q) {
                    $query->where(function ($query) use ($q) {
                        $query->where('nom', 'like', '%' . $q . '%')
                            ->orWhere('prenom', 'like', '%' . $q . '%');
                    });
                })
                    ->orWhere('label', 'like', '%' . $q . '%')
                    ->orWhereHas('pdfs', function ($query) use ($q) {
                        $query->where('libelle', 'like', '%' . $q . '%');
                    });
            });
        }

        if ($filter === "own") {
            $categories = $categories->whereHas('pdfs', function ($query) {
                $query->where('user_id', '=', Auth::user()->id);
            });
        }

        $totalCategories = $categories->count();
        $categories = $categories->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $CategoriesWithUser = $categories->map(function ($category) {
            $category['user_name'] = $category->user->prenom . ' ' . $category->user->nom;

            $category['pdfs'] = $category->pdfs->map(function ($pdf) {
                $pdf['user_id'] = Poste::where('id', $pdf->poste_id)->value('user_id');
                return $pdf;
            });

            unset($category->user);
            return $category;
        });

        return response([
            'users' => $userWithCat,
            'pdfCategories' => $CategoriesWithUser,
            'totalCategories' => $totalCategories,
            'currentPage' => $page,
            'perPage' => $limit
        ]);
    }



//        } elseif ($filter === "own") {
//            $categories = $user->pdfCategories()->with('pdfs')->get();
//            $CategoriesWithUser = $categories->map(function ($category) use ($user) {
//                $category['user_name'] = $user->prenom . ' ' . $user->nom;
//                return $category;
//            });
//            return response([
//                'pdfCategories' => $CategoriesWithUser
//            ]);
//        }

//        if (!$user) {
//            return response([
//                'message' => "not logged in",
//            ]);
//        }
//        $pdfCategories = $user->pdfCategories()->with('pdfs')->get();
//        $pdfCategoriesWithUser = $pdfCategories->map(function ($category) use ($user) {
//            $category['user_name'] = $user->prenom . ' ' . $user->nom;
//            return $category;
//        });
//
//        return response([
//            'pdfCategories' => $pdfCategoriesWithUser
//        ]);
//    }

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
                'message' => "Vous n'avez pas le droit de publier",
            ]);
        }

        $category = new PdfCategorie();
        $category->user_id = $user->id;
        $category->label = $request->label;
        if ($category->save()) {
            return response(['message' => "success"]);
        } else {
            return response(['message' => "failed"]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

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
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $categorie = PdfCategorie::find($id);

        if (!$user) {
            return response([
                'message' => "Vous n'avez pas le droit",
            ]);
        }

        if ($user) {
            $categorie->label = $request->input('label');
            if ($categorie->save()) {
                return response()->json(['message' => "success"]);
            }

        } else {
            return response()->json(['message' => 'not logged in']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $categorie = PdfCategorie::find($id);

        if (!$user) {
            return response([
                'message' => "Vous n'avez pas le droit",
            ]);
        }

        if ($user) {
            if ($categorie->delete()) {
                return response()->json(['message' => "success"]);
            }

        } else {
            return response()->json(['message' => 'not logged in']);
        }

    }
}
