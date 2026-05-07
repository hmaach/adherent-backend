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
        $validated = $request->validate([
            'pdf_id' => 'nullable|integer|exists:p_d_f_s,id',
            'pdf_path' => 'nullable|string|max:255',
        ]);

        if (empty($validated['pdf_id']) && empty($validated['pdf_path'])) {
            return response()->json(['message' => 'A PDF identifier is required.'], 422);
        }

        $pdf = isset($validated['pdf_id'])
            ? PDF::find($validated['pdf_id'])
            : PDF::where('path', $validated['pdf_path'])->first();

        if (!$pdf || str_contains($pdf->path, '..') || str_contains($pdf->path, '\\')) {
            return response()->json(['message' => 'PDF not found.'], 404);
        }

        $filePath = storage_path('app/' . ltrim($pdf->path, '/'));
        $storageRoot = realpath(storage_path('app'));
        $realFilePath = realpath($filePath);

        if (!$realFilePath || !$storageRoot || !str_starts_with($realFilePath, $storageRoot) || !is_file($realFilePath)) {
            return response()->json(['message' => 'PDF file not found.'], 404);
        }

        return response()->download($realFilePath, basename($realFilePath), [
            'Content-Type' => 'application/pdf',
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
