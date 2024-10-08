<?php

namespace App\Http\Controllers;

use App\Http\Resources\FicheDescResource;
use App\Models\FicheDesc;
use App\Models\Intervention;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FicheDescController extends Controller
{
    use FormatResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->response(Response::HTTP_OK, "Toutes les fiches", ["Fiches" => FicheDescResource::collection(FicheDesc::all())]);
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
    public function destroy(FicheDesc $ficheDesc)
    {
        $ficheDesc->delete();
        return $this->response(Response::HTTP_OK, "Suppression réussie !", []);
    }

    public function insertDesc(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);
        if ($intervention) {
            $ficheDesc = FicheDesc::where("intervention_id", $id)->first();
            if ($ficheDesc)
         {  
             FicheDesc::update([
                    "description" => $request->trableShooting
                ]);
                return $this->response(Response::HTTP_OK, "Description mis à jour", []);
            } else
            {
                FicheDesc::create([
                    "description" => $request->trableShooting,
                    "intervention_id" => $id
                ]);
                return $this->response(Response::HTTP_OK, "Description ajoutée", []);
            }
        } else {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, "Intervention non trouvée !", []);
        }
    }
}
