<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'types_produit' => 'required',
            'nom_produit' => 'required',
            'gamme' => 'required',
            'numero_serie' => 'required',
            'code_annuel' => 'required',
            'user_id' => 'required'
        ];
    }
}
