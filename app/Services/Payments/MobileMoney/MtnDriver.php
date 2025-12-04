<?php

namespace App\Services\Payments\MobileMoney;

use App\Models\Transaction;
use App\Services\Payments\PaymentDriverInterface;
use App\Services\Payments\Exceptions\PaymentFailedException;
use Illuminate\Support\Facades\Http;

class MtnDriver implements PaymentDriverInterface
{
    public function initiate(Transaction $transaction, array $data = []): array
    {
        // Simule un appel externe de mobile money
        if (!$transaction->customer?->phone) {
            throw new PaymentFailedException("Phone number is required for mobile money payment.");
        }

        // Simule OTP obligatoire
        $response = $this->createPayment($transaction);
        return $response;
    }

    public function confirm(Transaction $transaction): array
    {
        $response = $this->getPaymentStatus($transaction);

        return $response;
    }


    protected function createPayment(Transaction $transaction): array{
        $response = Http::withoutVerifying()
        ->withHeaders([
            "Content-Type"=> "application/json",
            "X-Target-Environment" => env("MTN_ENV"),
            "Ocp-Apim-Subscription-Key" => env("MTN_COLLECTION_KEY"),
            "X-Reference-Id" => $transaction->reference,
            "Authorization" => "Bearer " . env('MTN_TOKEN')
        ])
        ->post(env("MTN_BASE_URL").'/collection/v1_0/requesttopay', [
            "amount" => $transaction->amount,
            "currency" => $transaction->currency,
            "externalId" => $transaction->id,
            "payer" => [
                "partyIdType" => "MSISDN",
                "partyId" => $transaction->customer->phone,
            ],
            "payerMessage" => $transaction->description,
            "payeeNote" => $transaction->description
        ]);

        return [
            'success' => $response->status() === 202 || 
                ($response->json("code") && $response->json('code') === 'RESOURCE_ALREADY_EXIST' ),
            'statusCode' => $response->status(),
            'reason' => $response->json()
        ];
    }

    protected function getPaymentStatus(Transaction $transaction): array{
        $response = Http::withoutVerifying()
        ->withHeaders([
            "Content-Type"=> "application/json",
            "Authorization" => "Bearer " . env('MTN_TOKEN'),
            "X-Target-Environment" => env("MTN_ENV"),
            "Ocp-Apim-Subscription-Key" => env("MTN_COLLECTION_KEY"),
        ])
        ->get(env("MTN_BASE_URL").'/collection/v2_0/payment/'. $transaction->reference);

        return array_merge( [
            'success' => $response->status() === 200  
                && $response->json('status') && $response->json('status') === 'SUCCESSFUL',
            'statusCode' => $response->status(),
        ], $response->json() ?? [] );
    }

}
