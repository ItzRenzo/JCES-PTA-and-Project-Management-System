<?php

namespace Database\Factories;

use App\Models\ParentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ParentProfile>
 */
class ParentProfileFactory extends Factory
{
    protected $model = ParentProfile::class;

    public function definition()
    {
        return [
            'userID' => 1,
            'phone' => $this->faker->phoneNumber,
            'street_address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'barangay' => $this->faker->word,
            'zipcode' => $this->faker->postcode,
            'created_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
