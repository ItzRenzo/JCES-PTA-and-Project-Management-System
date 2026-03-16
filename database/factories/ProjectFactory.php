<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'project_name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'target_budget' => $this->faker->randomFloat(2, 100, 100000),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'target_completion_date' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
            'created_by' => User::factory(),
            'current_amount' => $this->faker->randomFloat(2, 0, 50000),
        ];
    }
}
