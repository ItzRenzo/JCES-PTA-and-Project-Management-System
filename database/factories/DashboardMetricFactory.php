<?php

namespace Database\Factories;

use App\Models\DashboardMetric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DashboardMetric>
 */
class DashboardMetricFactory extends Factory
{
    protected $model = DashboardMetric::class;

    public function definition()
    {
        return [
            'metric_name' => $this->faker->word,
            'current_value' => $this->faker->randomFloat(2, 0, 1000),
            'last_updated' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'projectID' => null,
        ];
    }
}
