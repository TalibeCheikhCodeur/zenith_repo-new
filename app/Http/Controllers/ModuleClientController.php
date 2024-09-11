<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModulesClientResource;
use App\Models\ModuleClient;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ModuleClientController extends Controller
{
    use FormatResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modulClient = ModuleClient::all();
        $allModuleClient = $modulClient->unique("user_id")->values();

        return $this->response(Response::HTTP_OK, "liste de tous les modulesClient", ["modulesClient" => ModulesClientResource::collection($allModuleClient)]);
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
    public function show(ModuleClient $moduleClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Trouver le moduleClient par ID
        $moduleClient = ModuleClient::findOrFail($id);

        // Mettre à jour l'état
        $moduleClient->etat = $request->etat;
        $moduleClient->resilié = 1;

        $moduleClient->save();

        // Réponse JSON
        return response()->json([
            'message' => 'État mis à jour avec succès.',
            'modulesClient' => [$moduleClient]
        ], Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModuleClient $moduleClient)
    {
        //
    }

    public function updateEtat(Request $request, $id)
    {
        $moduleClient = ModuleClient::findOrFail($id);
        $moduleClient->etat = $request->etat;
        $moduleClient->save();

        return $this->response(Response::HTTP_OK, "État mis à jour avec succès.", ["modulesClient" => [$moduleClient]]);
    }
}
