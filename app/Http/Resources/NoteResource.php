<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
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
            "titre" => $this->title,
            "point" => $this->points,
            "commentaire" => $this->commentaire,
            "intervention" => new InterventionResource($this->intervention)
        ];
    }
}
