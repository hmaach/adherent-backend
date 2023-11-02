<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnounceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
//            'order' => "integer | required",
//            'desc' => "string | required",
//            'debut' => "date | required",
//            'fin' => "date| required|after:debut",
//            'img' => "image"
        ];
    }

    public function messages(): array
    {
        return [
//            'order.integer' => 'Le champ "order" doit être un entier.',
//            'order.required' => 'Le champ "order" est requis.',
//            'desc.string' => 'Le champ "desc" doit être une chaîne de caractères.',
//            'desc.required' => 'Le champ "desc" est requis.',
//            'debut.date' => 'Le champ "debut" doit être une date valide.',
//            'debut.required' => 'Le champ "debut" est requis.',
//            'fin.date' => 'Le champ "fin" doit être une date valide.',
//            'fin.required' => 'Le champ "fin" est requis.',
//            'fin.after' => 'Le champ "fin" doit être postérieur au champ "debut".',
//            'img.image' => 'Le champ "img" doit être une image valide.',
        ];
    }
}
