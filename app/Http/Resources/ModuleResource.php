<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
            'types_produit' => $this->types_produit,
            'nom_produit' =>$this->nom_produit ,
            'gamme' => $this->gamme,
            'numero_serie' => $this->numero_serie,
            'code_annuel' => $this->code_annuel,
            'client' => new Client2Resource($this->user),
        ];
    }
}
