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
            'id' => $this->id,
            'types_intervention' => $this->types_intervention,
            'description' => $this->description,
            'date_intervention' => $this->date_intervention,
            'debut_intervention' => $this->debut_intervention,
            'fin_intervention' => $this->fin_intervention,
            'caractere_intervention' => $this->caractere_intervention,
            // 'user' => new UserResource($this->user),
            'image'  => $this->image,
            'path_image' => $this->path_image,
            'consultantId' => $this->user_id,
            'modules' => Module_interventionResource::collection($this->moduleIntervention),
            'assigner' => $this->isAssigned,
            'cloturer' => $this->isClotured,
            'duree' => $this->durée,
            "trableShooting" => $this->trableShooting
        ];
    }
}
