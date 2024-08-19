<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterventionRequest extends FormRequest
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
            'types_intervention' => 'nullable',
            'description' => 'required',
            'date_intervention' => 'nullable',
            'debut_intervention' => 'nullable',
            'fin_intervention' => 'nullable',
            'user_id' => 'nullable',
            'caractere_intervention' => 'nullable'
        ];
    }
}
