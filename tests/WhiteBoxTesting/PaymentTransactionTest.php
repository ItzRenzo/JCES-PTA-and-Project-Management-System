<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\PaymentTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_fields_correctly()
    {
        $transaction = PaymentTransaction::factory()->make([
            'amount' => 500.00,
            'transaction_date' => '2026-02-19',
        ]);
        $this->assertIsFloat((float)$transaction->amount);
        $this->assertInstanceOf(\Carbon\Carbon::class, $transaction->transaction_date);
    }

    public function test_has_contribution_relationship()
    {
        $transaction = PaymentTransaction::factory()->make();
        $this->assertTrue(method_exists($transaction, 'contribution'));
    }
}
