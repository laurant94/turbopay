<?php

namespace Tests\Unit;

use App\Models\Fee;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TransactionObserverTest extends TestCase
{
    use RefreshDatabase;

    private $merchant;
    private $fee;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and a merchant
        $user = User::factory()->create();
        $this->merchant = Merchant::factory()->create(['user_id' => $user->id]);

        // Create a fee rule for the merchant
        $this->fee = Fee::create([
            'merchant_id' => $this->merchant->id,
            'currency' => 'XOF',
            'scope' => 'sandbox',
            'percent' => 2.0, // 2%
            'fixed' => 50,    // 50 XOF
            'min_amount' => 100,
            'max_amount' => 100000,
        ]);
    }

    /**
     * Test fee calculation when the merchant pays the fees.
     */
    public function test_merchant_pays_fees(): void
    {
        // Set setting for the merchant to pay fees
        $this->merchant->settings()->set('merchant.fees.payer', 'merchant');

        // Create a transaction
        $transaction = Transaction::create([
            'merchant_id' => $this->merchant->id,
            'amount' => 10000, // 10,000 XOF
            'currency' => 'XOF',
            'scope' => 'sandbox',
            'reference' => Str::orderedUuid(),
        ]);

        // Fee calculation: (10000 * 2%) + 50 = 200 + 50 = 250
        $expectedFees = 250;

        $this->assertEquals($expectedFees, $transaction->fees);
        $this->assertEquals(10000, $transaction->amount); // Amount paid by customer is unchanged
        $this->assertEquals(10000 - $expectedFees, $transaction->net_amount); // Merchant gets amount - fees
    }

    /**
     * Test fee calculation when the customer pays the fees.
     */
    public function test_customer_pays_fees(): void
    {
        // Set setting for the customer to pay fees
        $this->merchant->settings()->set('merchant.fees.payer', 'customer');

        // Create a transaction with a base amount
        $baseAmount = 10000;
        $transaction = Transaction::create([
            'merchant_id' => $this->merchant->id,
            'amount' => $baseAmount,
            'currency' => 'XOF',
            'scope' => 'sandbox',
            'reference' => Str::orderedUuid(),
        ]);

        // Fee calculation: (10000 * 2%) + 50 = 200 + 50 = 250
        $expectedFees = 250;

        // Refresh the model to get the updated values from the observer
        $transaction->refresh();

        $this->assertEquals($expectedFees, $transaction->fees);
        $this->assertEquals($baseAmount + $expectedFees, $transaction->amount); // Total amount charged to customer
        $this->assertEquals($baseAmount, $transaction->net_amount); // Merchant gets the base amount
    }
}
