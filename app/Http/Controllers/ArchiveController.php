<?php

namespace App\Http\Controllers;

use App\Models\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response([
                'message' => "not logged in",
            ]);
        }
        $pdfCategories = $user->pdfCategories()->with('pdfs')->get();
        $pdfCategoriesWithUser = $pdfCategories->map(function ($category) use ($user) {
            $category['user_name'] = $user->prenom.' '.$user->nom; // Add user name to each category
            return $category;
        });

        return response([
            'pdfCategories'=>$pdfCategoriesWithUser
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
