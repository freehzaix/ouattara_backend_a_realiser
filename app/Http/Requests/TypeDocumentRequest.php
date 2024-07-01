<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeDocumentRequest extends FormRequest
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
            'nom_fichier' => 'required|string|max:255',
            'fichier_scanner' => 'required|file|mimes:pdf',
        ];
    }

    public function messages(): array
    {
        return [
            'nom_fichier.required' => 'Vous devez donnez obligatoirement un nom au document.',
            'fichier_scanner.required' => 'Vous devez donnez obligatoirement charger un document.',
        ];
    }

}
