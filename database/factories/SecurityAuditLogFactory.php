<?php

namespace Database\Factories;

use App\Models\SecurityAuditLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SecurityAuditLog>
 */
class SecurityAuditLogFactory extends Factory
{
    protected $model = SecurityAuditLog::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'event' => $this->faker->sentence,
            'event_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
