<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModulesClient2Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'modules' => new ModuleResource($this->module),
            'client' => new Client2Resource($this->user),
            'numero_serie' => $this->numero_serie,
            'version' => $this->version,
            'code_annuel' => $this->code_annuel,
            'code_activation' => $this->code_activation,
            'nbre_users' => $this->nbre_users,
            'nbre_salariés' => $this->nbre_salariés,
        ];
    }
}
