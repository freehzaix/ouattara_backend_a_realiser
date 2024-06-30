<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuideRequest extends FormRequest
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
            'nom_fichier' => 'required',
            'fichier_scanner' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'nom_fichier.required' => 'Le nom du fichier est requis.',
            'fichier_scanner.required' => 'Le fichier est requis.',
        ];
    }

}
