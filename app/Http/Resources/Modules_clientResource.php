<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Modules_clientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'modules' => ModuleResource::collection($this->moduleClient),
            'client' => ClientResource::collection($this->moduleClient)
        ];
    }
}
