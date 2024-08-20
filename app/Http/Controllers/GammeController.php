<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreGammeRequest;
use Illuminate\Http\Response;

use App\Models\Gamme;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;

class GammeController extends Controller
{
    use FormatResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gammes = Gamme::all();
        return $this->response(Response::HTTP_OK, "Liste des gammes récupérée avec succès", ["gammes" => $gammes]);
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
        return $this->response(Response::HTTP_OK, "La gamme a été ajoutée avec succès", ["gamme" => $gamme]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gamme $gamme)
    {
        if (!$gamme) {
            return $this->response(Response::HTTP_NOT_FOUND, "La gamme n'existe pas", ['gamme' => []]);
        }

        return $this->response(Response::HTTP_OK, "gamme récupérée avec succès", ["gamme" => $gamme]); //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreGammeRequest $request, Gamme $gamme)
    {
        if (!$gamme) {
            return $this->response(Response::HTTP_NOT_FOUND, "La gamme n'existe pas", ['gamme' => []]);
        }

        $gamme->update([
            'libelle' => $request->libelle,
            'description' => $request->description,

        ]);

        return $this->response(Response::HTTP_OK, "La gamme a été mise à jour avec succès", ["gamme" => $gamme]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gamme $gamme)
    {
        if (!$gamme) {
            return $this->response(Response::HTTP_NOT_FOUND, "La gamme n'existe pas", ['gamme' => []]);
        }

        $gamme->delete();

        return $this->response(Response::HTTP_OK, "La gamme a été supprimée avec succès", ['gamme' => $gamme]);
    }
}
