<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActiviteRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Vous devez donnez obligatoirement un nom.',
            'lieu.required' => 'Vous devez donnez obligatoirement un lieu.',
        ];
    }
    
}
