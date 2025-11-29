<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // l'auth apikey doit être vérifiée par middleware ; ici on renvoie true
        return true;
    }

    public function rules(): array
    {
        $merchant = $this->user(); // Merchant via middleware
        

        return [
            'email' => ['required','email', function($attr, $value, $fail) use ($merchant) {
                // unique per merchant
                $exists = \App\Models\Customer::where('merchant_id', $merchant->id)
                    ->where('email', $value)->exists();
                if ($exists) $fail('Cet email est déjà utilisé');
            }],
            'firstname' => ['nullable','string','max:191'],
            'firstname' => ['nullable','string','max:191'],
            'phone' => ['nullable','string','max:40'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email requis.',
            'email.email' => 'Email invalide.',
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
