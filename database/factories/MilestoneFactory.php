<?php

namespace Database\Factories;

use App\Models\Milestone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Milestone>
 */
class MilestoneFactory extends Factory
{
    protected $model = Milestone::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'target_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'projectID' => 1,
            'is_completed' => $this->faker->boolean,
        ];
    }
}
