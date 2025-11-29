<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => ['required','email', function($attr, $value, $fail) use ($merchant, $customerId) {
                $exists = \App\Models\Customer::where('merchant_id', $merchant->id)
                    ->where('email', $value)
                    ->where('id', '!=', $customerId)
                    ->exists();
                if ($exists) $fail('Cet email est déjà utilisé pour ce marchand.');
            }],
            'name' => ['required','string','max:191'],
            'phone' => ['nullable','string','max:40'],
        ];
    }
}
