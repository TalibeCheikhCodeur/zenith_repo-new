<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModulesClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            'client' => new ClientResource($this->user),

            // 'module_id' => new ModuleResource($this->module),
            // 'numero_serie' => $this->numero_serie,
            // 'version' => $this->version,
            // 'code_annuel' => $this->code_annuel,
            // 'code_activation' => $this->code_activation,
            // 'nbre_users' => $this->nbre_users,
            // 'nbre_salariés' => $this->nbre_salariés
        ];
    }
}
