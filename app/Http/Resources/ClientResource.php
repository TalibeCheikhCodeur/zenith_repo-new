<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "nom" => $this->nom,
            "prenom" => $this->prenom,
            "role" => $this->role,
            'nom_client' => $this->nom_client,
            'code_client' => $this->code_client,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'adresse' => $this->adresse,
            'modules_client' => $this->modules->map(function ($module) {
                return [
                    "id" => $module->pivot->id,
                    "module"=> new ModuleResource($module),
                    "numero_serie" => $module->pivot->numero_serie,
                    "version" => $module->pivot->version,
                    "code_annuel" => $module->pivot->code_annuel,
                    "code_activation" => $module->pivot->code_activation,
                    "nbre_users" => $module->pivot->nbre_users,
                    "nbre_salariés" => $module->pivot->nbre_salariés,
                    "etat"=>$module->pivot->etat,
                    "resilié"=>$module->pivot->resilié,

                ];
            })
        ];
    }
}
