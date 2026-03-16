<?php

namespace Database\Factories;

use App\Models\ProjectUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectUpdate>
 */
class ProjectUpdateFactory extends Factory
{
    protected $model = ProjectUpdate::class;

    public function definition()
    {
        return [
            'projectID' => 1,
            'update_title' => $this->faker->sentence,
            'update_description' => $this->faker->paragraph,
            'progress_percentage' => $this->faker->randomFloat(2, 0, 100),
            'update_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_by' => 1,
        ];
    }
}
