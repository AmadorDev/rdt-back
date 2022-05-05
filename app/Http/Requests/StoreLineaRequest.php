<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class StoreLineaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'  => 'required|unique:lineas|max:255',
            'email' => 'required',
        ];

    }

    public function messages()
    {
        return [
            'name.required'  => 'A title is required',
            'email.required' => 'es requerido',
        ];
    }

    protected function failedValidation(validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }

}
