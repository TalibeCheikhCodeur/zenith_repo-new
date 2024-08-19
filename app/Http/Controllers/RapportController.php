<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRapportRequest;
use App\Http\Requests\UpdateRapportRequest;
use App\Models\Rapport;
use App\Traits\FormatResponse;
use Illuminate\Http\Response;

class RapportController extends Controller
{
    use FormatResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rapports = Rapport::all();
        return $this->response(Response::HTTP_OK, "Liste des rapports récupérée avec succès", ["rapports" => $rapports]);
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
        return $this->response(Response::HTTP_OK, "Le rapport a été créé avec succès", ['data' => $rapport]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rapport $rapport)
    {

        if (!$rapport) {
            return $this->response(Response::HTTP_NOT_FOUND, "Le rapport n'existe pas",['rapport'=>[]]);
        }

        return $this->response(Response::HTTP_OK, "Rapport récupéré avec succès", ["rapport" => $rapport]);
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


        if (!$rapport) {
            return $this->response(Response::HTTP_NOT_FOUND, "Le rapport n'existe pas",['rapport'=>[]]);
        }

        $rapport->update([
            'rapport' => $request->rapport,
            'intervention_id' => $request->intervention_id,
            'user_id' => $request->user_id,
            'date' => $request->date,
        ]);

        return $this->response(Response::HTTP_OK, "Le rapport a été mis à jour avec succès", ["rapport" => $rapport]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rapport $rapport)
    {
        
        if (!$rapport) {
            return $this->response(Response::HTTP_NOT_FOUND, "Le rapport n'existe pas",['rapport'=>[]]);
        }

        $rapport->delete();

        return $this->response(Response::HTTP_OK, "Le rapport a été supprimé avec succès",['rapport'=>$rapport]);
    }
}
