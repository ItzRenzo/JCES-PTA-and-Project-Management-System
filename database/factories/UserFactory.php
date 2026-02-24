<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first = $this->faker->firstName;
        $last = $this->faker->lastName;
        return [
            'first_name' => $first,
            'last_name' => $last,
            'username' => Str::slug(strtolower($first . '.' . $last)) . rand(1, 999),
            'email' => fake()->unique()->safeEmail(),
            'user_type' => $this->faker->randomElement(['parent','administrator','teacher','principal']),
            'is_active' => true,
            'password_hash' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
