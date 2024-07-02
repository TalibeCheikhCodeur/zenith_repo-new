<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRapportRequest;
use App\Http\Requests\UpdateRapportRequest;
use App\Models\Rapport;
use Illuminate\Http\Response;

class RapportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rapport = Rapport::all();
        return response()->json($rapport, Response::HTTP_OK);
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
    public function store(StoreRapportRequest $request)
    {
        $rapport = Rapport::create($request->validated());
        return  response()->json(['message' => 'Le rapport a été créé avec succès', 'data' => $rapport], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rapport $rapport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rapport $rapport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRapportRequest $request, Rapport $rapport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rapport $rapport)
    {
        //
    }
}
