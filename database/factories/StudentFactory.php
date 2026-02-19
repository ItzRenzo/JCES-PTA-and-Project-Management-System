<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        $first = $this->faker->firstName;
        $last = $this->faker->lastName;
        return [
            'student_name' => trim($first . ' ' . $last),
            'grade_level' => $this->faker->randomElement(['K', '1', '2', '3', '4', '5', '6']),
            'section' => null,
            'academic_year' => date('Y') . '-' . (date('Y') + 1),
            'enrollment_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'birth_date' => $this->faker->date('Y-m-d', '2015-01-01'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'is_archived' => false,
        ];
    }
}
