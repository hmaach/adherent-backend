<?php

namespace App\Http\Controllers;

use App\Models\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "hi";
    }

    public function downloadPDF(Request $request)
    {
        $pdfPath = $request->input('pdf_path');
        $filePath = storage_path('app/' . $pdfPath);
        $fileName = basename($filePath);
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return response()->file($filePath, $headers);
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
    public function show(PDF $pDF)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PDF $pDF)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    function update(Request $request, PDF $pdf)
    {
        $user = Auth::user();
        if ($user) {
            $pdf->libelle = $request->input('libelle');
            $pdf->save();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'not logged in']);
        }
    }

    function removeCategory(Request $request, PDF $pdf)
    {
        $user = Auth::user();
        if ($user) {
            $pdf->pdf_categorie_id = null;
            $pdf->save();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'not logged in']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PDF $pDF)
    {
        //
    }
}
