<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTransactionRequest extends FormRequest{
    public function authorize(): bool
    {
        // l'auth apikey doit être vérifiée par middleware ; ici on renvoie true
        return true;
    }


     public function rules(): array
    {
        $merchant = $this->user(); // Merchant via middleware
        

        return [
            "description"=> "required|string",
            "amount" => "required|integer",
            // "currency" => "required|array",
            // 'currency.iso' => 'required_without:currency.id|nullable|string|size:3||exists:currencies,code',
            // 'currency.id'  => 'required_without:currency.iso|nullable|integer|exists:currencies,id',

            "callback_url" => "nullable|string",
            "custom_data" => "nullable|array",
            // 'customer' => 'nullable|array',

            // 'customer.id'    => 'sometimes|required_without_all:customer.email|integer|exists:customers,id',
            // 'customer.email' => 'sometimes|required_without_all:customer.id|email',
            // 'customer.firstname' => 'nullable|string',
            // 'customer.lastname'  => 'nullable|string',
            // 'customer.phone'     => 'nullable|string',

        ];
    }

    public function messages(): array
    {
        return [
            // 'name.required' => 'Nom requis.',
        ];
    }

    public function wantsJson()
    {
        return true;
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