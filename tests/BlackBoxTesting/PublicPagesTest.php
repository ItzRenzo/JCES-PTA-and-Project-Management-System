<?php

namespace Tests\BlackBoxTesting;

class PublicPagesTest extends BlackBoxTestCase
{
    public function test_root_redirects_to_login()
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_login_and_register_pages_available()
    {
        $this->get(route('login'))->assertStatus(200);
        $this->get(route('register'))->assertStatus(200);
    }
}
