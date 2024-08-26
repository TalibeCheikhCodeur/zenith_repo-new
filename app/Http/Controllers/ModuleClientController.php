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
    public function update(Request $request, ModuleClient $moduleClient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModuleClient $moduleClient)
    {
        //
    }
}
