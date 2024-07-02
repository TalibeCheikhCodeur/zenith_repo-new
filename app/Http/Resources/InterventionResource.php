<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterventionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'types_intervention' => $this->types_intervention,
            'description' => $this->description,
            'date_intervention' => $this->date_intervention,
            'debut_intervention' => $this->debut_intervention,
            'fin_intervention' => $this->fin_intervention,
            'module' => new ModuleResource($this->module),
            'caractere_intervention' => $this->caractere_intervention
        ];
    }
}
