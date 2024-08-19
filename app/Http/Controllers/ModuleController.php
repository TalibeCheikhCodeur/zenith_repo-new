<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use App\Traits\FormatResponse;
use Illuminate\Http\Response;

class ModuleController extends Controller
{
    use FormatResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = Module::all();
        // return  response()->json(ModuleResource::collection($modules));
        return $this->response(response::HTTP_OK,"liste de tous les modules",["modules"=>ModuleResource::collection($modules)]);
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
    public function store(StoreModuleRequest $request)
    {
        // dd($request->all());
        $module = Module::create($request->all());
        // return response()->json(['message' =>'module ajouté avec  succés', 'data'=>$module],Response::HTTP_OK);
        return $this->response(Response::HTTP_OK,"Module ajouté avec succès",[ "module" => new ModuleResource($module)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   

     public function update(UpdateModuleRequest $request, Module $module)
    {
        $module->update($request->all());
        return $this->response(Response::HTTP_OK, "Module mis à jour avec succès", ["module" => new ModuleResource($module)]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
       $module->delete();
       // recuperer toute les modules
       $modules = Module::all();
       // Retourne une réponse JSON confirmant la suppression
       return $this->response(Response::HTTP_OK, "Module supprimé avec succès",["modules"=>ModuleResource::collection($modules)]);
    }
}
