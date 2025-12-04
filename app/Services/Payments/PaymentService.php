<?php

namespace App\Services\Payments;

use App\Models\Transaction;
use App\Http\Enums\ProviderEnum;
use App\Services\Fees\FeeService;
use App\Http\Trait\ResolveCustomer;
use App\Http\Enums\TransactionStatusEnum;
use App\Services\Webhooks\WebhookService;
use App\Services\AntiFraud\AntiFraudService;
use App\Services\Payments\PaymentDriverInterface;
use App\Services\Payments\Exceptions\InvalidPaymentModeException;
use App\Services\Payments\Exceptions\InvalidTransactionException;
use App\Services\Payments\MobileMoney\MtnDriver;

class PaymentService
{
    use ResolveCustomer;

    public function __construct(
        protected FeeService $feeService,
        protected AntiFraudService $fraudService,
        protected WebhookService $webhookService
    ) {}


    public function processMobilePayment(string $token, array|int|string $customerData, string $provider): array
    {
        // 1. Charger transaction par token
        $transaction = Transaction::where('payment_token', $token)
            ->with(['customer'])
            ->where('payment_token_expires_at', '>', now())
            ->first();

        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'Invalid or expired token'
            ];
        }

        if ($transaction->status !== TransactionStatusEnum::Pending) {
            return [
                'success' => false,
                'message' => 'Transaction already processed'
            ];
        }

        if(!$transaction->customer || !$transaction?->customer?->phone){
            if(!isset($customerData['phone'])){
                return [
                    'success' => false,
                    'message' => 'Invalid customer phone',
                ];
            }

            $transaction->customer()->associate( $this->resolveCustomer($customerData) );
            $transaction->save();
        }

        $transaction->provider = $provider;
        $transaction->save();

        

        // ANTI FRAUD VERIFICATION
        // $result = $this->fraudService->evaluate($transaction);

        // if ($result['status'] === 'blocked') {
        //     return $this->markAsFailed($transaction, $result['reason']);
        // }

        // if ($result['status'] === 'review') {
        //     $transaction->status = 'manual_review';
        //     $transaction->save();

        //     return [
        //         'success' => false,
        //         'manual_review' => true,
        //         'message' => $result['reason']
        //     ];
        // }
        // FIN ANTI FRAUD


        // 2. Appel provider mobile-money (mock)
        $providerResponse = $this->mockMobilePayment($transaction, $provider);

        return $providerResponse;
        
    }

    public function verifyPayment(string|Transaction $transaction){

        if(! $transaction instanceof Transaction){
            $transaction = Transaction::findOrFail($transaction);
        }

        if(!$transaction->provider){
            return [
                'success'=> false,
                'message'=> "No provider found" 
            ];
        }

        $driver = $this->resolveDriver($transaction->provider);

        $confirmation = $driver->confirm($transaction); 

        // 3. Si provider OK → succès
        if (isset($confirmation['status']) && strtolower($confirmation['status']) === TransactionStatusEnum::Successful->value) {
            return $this->markAsSuccessful($transaction);
        }
        elseif(isset($confirmation['status']) && strtolower($confirmation['status']) !== TransactionStatusEnum::Pending->value){
            // 4. Sinon → échec
            return $this->markAsFailed($transaction, $confirmation['message']);
        }


        return $confirmation;
    }

    private function mockMobilePayment(Transaction $transaction, string $provider)
    {
        $driver = $this->resolveDriver($provider);
        
        return $driver->initiate($transaction, );
    }


    private function markAsSuccessful(Transaction $transaction): array
    {
        $transaction->status = TransactionStatusEnum::Successful;
        $transaction->paid_at = now();
        $transaction->save();

        // TODO: webhook + balance

        return [
            'success' => true,
            'message' => 'Payment completed',
            'data' => $transaction
        ];
    }

    private function markAsFailed(Transaction $transaction, string $reason): array
    {
        $transaction->status = 'failed';
        $transaction->save();

        return [
            'success' => false,
            'message' => $reason
        ];
    }


    /**
     * Retourne le driver correspondant au mode choisi.
     */
    public function resolveDriver(string $mode): PaymentDriverInterface
    {
        return match ($mode) {
            ProviderEnum::MTN->value => new MtnDriver(),
            //'card'   => new CardDriver(),
            default  => throw new InvalidPaymentModeException("Invalid payment mode: $mode")
        };
    }


}
