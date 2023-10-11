<?php

namespace App\Http\Controllers;

use App\Models\React;
use Illuminate\Http\Request;

class ReactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $react = new React();
        $react->id_user = $request->input('user_id');
        $react->id_poste = $request->input('id_poste');
        $react->save();
        return redirect()->back();
//        dd($request->input('user_id'));
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
        $react = new React();
        return 'hhhh';

    }

    /**
     * Display the specified resource.
     */
    public function show(React $react)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(React $react)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, React $react)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(React $react)
    {
        //
    }
}
