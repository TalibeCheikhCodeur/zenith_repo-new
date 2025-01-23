<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
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
        $minValidate = 'nullable|string|max:100';
        return [
            '*.nom' => $minValidate,
            '*.nom_client' => $minValidate,
            '*.code_client' => $minValidate,
            '*.prenom' => $minValidate,
            '*.telephone' => $minValidate,
            '*.role' => 'required|in:consultant,DG,COT,DPT,client',
            '*.email' => 'nullable|string|email|max:255',
            '*.password' => 'required|string|min:8|confirmed',
        ];
    }
}
