<?php

namespace Database\Factories;

use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentTransaction>
 */
class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition()
    {
        return [
            'contributionID' => 1,
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'transaction_status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
        ];
    }
}
