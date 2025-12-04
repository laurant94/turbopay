<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $merchant = $this->user();
        $customerId = $this->route('id');

        return [
            // 'email' => ['required','email', function($attr, $value, $fail) use ($merchant, $customerId) {
            //     $exists = \App\Models\Customer::where('merchant_id', $merchant->id)
            //         ->where('email', $value)
            //         ->where('id', '!=', $customerId)
            //         ->exists();
            //     if ($exists) $fail('Cet email est déjà utilisé pour ce marchand.');
            // }],
            'firstname' => ['nullable','string','max:191'],
            'lastname' => ['nullable','string','max:191'],
            'phone' => ['nullable','string','max:40'],
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
