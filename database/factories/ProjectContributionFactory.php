<?php

namespace Database\Factories;

use App\Models\ProjectContribution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectContribution>
 */
class ProjectContributionFactory extends Factory
{
    protected $model = ProjectContribution::class;

    public function definition()
    {
        return [
            'projectID' => 1,
            'parentID' => 1,
            'contribution_amount' => $this->faker->randomFloat(2, 1, 1000),
            'contribution_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'processed_by' => 1,
        ];
    }
}
