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
            "id"=> $this->id,
            'nom_client' => $this->nom_client,
            'code_client' => $this->code_client,
            'telephone' =>$this->telephone,
            'email' => $this->email,
            'modules' => $this->modules
        ];
    }
}
