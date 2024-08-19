<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
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
        $ValuesTitles = ["pas satisfait", "satisfait", "très satisfait", "super satisfait"];

        return [
            'title' => ['required', Rule::in($ValuesTitles)],
            'points' => 'required|integer',
            'commentaire' => 'required|string',
            'intervention_id' => 'required|exists:interventions,id',
        ];
    }
}
