<?php

namespace Tests\BlackBoxTesting;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlackBoxTestCase extends TestCase
{
    use RefreshDatabase;

    /**
        * Create a user with a given type.
     */
    protected function createUser(string $type = 'parent', array $attributes = [])
    {
        return \App\Models\User::factory()->create(array_merge([
            'user_type' => $type,
        ], $attributes));
    }
}
