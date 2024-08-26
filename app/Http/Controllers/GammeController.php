<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreGammeRequest;
use App\Http\Resources\GammeResource;
use Illuminate\Http\Response;

use App\Models\Gamme;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;

class GammeController extends Controller
{
    use FormatResponse;

    const NOT_EXIST_GAMME = "La gamme n'existe pas";

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gammes = Gamme::all();
        return $this->response(Response::HTTP_OK, "Liste des gammes récupérée avec succès", ["gammes" => GammeResource::collection($gammes)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGammeRequest $request)
    {
        $gamme = Gamme::create([
            'libelle' => $request->libelle,
            'description' => $request->description,
        ]);
        return $this->response(Response::HTTP_OK, "La gamme a été ajoutée avec succès", ["gamme" => new GammeResource($gamme)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gamme $gamme)
    {
        if (!$gamme) {
            return $this->response(Response::HTTP_NOT_FOUND, GammeController::NOT_EXIST_GAMME, ['gamme' => []]);
        }

        return $this->response(Response::HTTP_OK, "Gamme récupérée avec succès", ["gamme" => new GammeResource($gamme)]); //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreGammeRequest $request, Gamme $gamme)
    {
        if (!$gamme) {
            return $this->response(Response::HTTP_NOT_FOUND, GammeController::NOT_EXIST_GAMME, ['gamme' => []]);
        }
        $gamme->update([
            'libelle' => $request->libelle,
            'description' => $request->description,
        ]);
        return $this->response(Response::HTTP_OK, "La gamme a été mise à jour avec succès", ["gamme" => new GammeResource($gamme)]);
        return $this->response(Response::HTTP_OK, "La gamme a été mise à jour avec succès", ["gamme" => new GammeResource($gamme)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gamme $gamme)
    {
        if (!$gamme) {
            return $this->response(Response::HTTP_NOT_FOUND, GammeController::NOT_EXIST_GAMME, ['gamme' => []]);
        }

        $gamme->delete();

        $gammes = Gamme::all();

        return $this->response(Response::HTTP_OK, "La gamme a été supprimée avec succès", ["gamme" => GammeResource::collection($gammes)]);
    }
}
