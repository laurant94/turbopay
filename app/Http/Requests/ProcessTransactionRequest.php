<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProcessTransactionRequest extends FormRequest{

    public function rules(): array{
        return [
            'provider'=> "required|string",
            'token'=> "required|string",

            'customer' => 'nullable|array',

            'customer.id'    => 'sometimes|required_without_all:customer.email|integer|exists:customers,id',
            'customer.email' => 'sometimes|required_without_all:customer.id|email',
            'customer.firstname' => 'nullable|string',
            'customer.lastname'  => 'nullable|string',
            'customer.phone'     => 'nullable|string',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()
        ], 422));
    }
}