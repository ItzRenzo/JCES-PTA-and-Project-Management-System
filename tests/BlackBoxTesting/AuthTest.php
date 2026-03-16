<?php

namespace Tests\BlackBoxTesting;

class AuthTest extends BlackBoxTestCase
{
    public function test_register_and_login_flow()
    {
        $email = 'bb_user@example.test';
        $password = 'password123';

        $response = $this->post('/register', [
            'name' => 'BB User',
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('users', ['email' => $email]);

        auth()->guard()->logout();

        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertAuthenticated();
    }
}
