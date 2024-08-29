<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterventionFicheResource extends JsonResource
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
            'types_intervention' => $this->types_intervention,
            'description' => $this->description,
            'date_intervention' => $this->date_intervention,
            'debut_intervention' => $this->debut_intervention,
            'fin_intervention' => $this->fin_intervention,
            'caractere_intervention' => $this->caractere_intervention,
            'user' => new UserResource($this->user),
            'modules' => ModuleInterventionResource::collection($this->moduleIntervention),
            'assigner' => $this->isAssigned,
            'cloturer' => $this->isClotured,
            'duree' => $this->durée,
            "trableShooting" => $this->trableShooting,
            "observation" => new InterventionNoteResource($this->notes->first()),
            "date_demande" => $this->created_at
        ];
    }
}
