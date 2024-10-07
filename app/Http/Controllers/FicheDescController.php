<?php

namespace App\Http\Controllers;

use App\Http\Resources\FicheDescResource;
use App\Models\FicheDesc;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FicheDescController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function show(FicheDesc $ficheDesc)
    {
        return $this->response(Response::HTTP_OK, "Description bien récupérée !", ["Desc" => new FicheDescResource($ficheDesc)]);
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

    public function insert(Request $request, $id)
    {
        $intervention = Intervention::find($id);
        if ($intervention) {
            $ficheDesc = FicheDesc::where("intervention_id", $id)->firstOrFail();
            if ($ficheDesc) {
                FicheDesc::update([
                    "description" => $request->description
                ]);
                return $this->response(Response::HTTP_OK, "Description mis à jour", []);
            } else {
                FicheDesc::create([
                    "description" => $request->description
                ]);
                return $this->response(Response::HTTP_OK, "Description mis ajoutée", []);
            }
        } else {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, "Intervention non trouvée !", []);
        }
    }
}
