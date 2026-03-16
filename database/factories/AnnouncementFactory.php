<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(['important', 'notice', 'update', 'event']),
            'audience' => $this->faker->randomElement(['parents', 'teachers', 'everyone']),
            'created_by' => 1, // You may want to create a user and use its ID
            'published_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'expires_at' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'is_active' => $this->faker->boolean,
        ];
    }
}
