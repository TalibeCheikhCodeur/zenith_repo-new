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
    public function show(string $id)
    {
        //
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

    public function insert(Request $request, FicheDesc $ficheDesc)
    {
        if ($ficheDesc) {
            $description = $ficheDesc->description;
            if ($description != null) {
                $ficheDesc->update($request->all());
                return $this->response(Response::HTTP_OK, "liste de tous les modules", []);
            } else {
                FicheDesc::create([
                    "description" => $request->description
                ]);
                return $this->response(Response::HTTP_OK, "liste de tous les modules", []);
            }
        }
    }
}
