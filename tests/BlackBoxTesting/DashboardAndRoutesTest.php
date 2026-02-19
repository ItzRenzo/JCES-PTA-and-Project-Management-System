<?php

namespace Tests\BlackBoxTesting;

class DashboardAndRoutesTest extends BlackBoxTestCase
{
    public function test_dashboard_redirects_based_on_user_type()
    {
        $parent = $this->createUser('parent');
        $this->actingAs($parent)->get('/dashboard')->assertRedirect('/parent');

        $admin = $this->createUser('administrator');
        $this->actingAs($admin)->get('/dashboard')->assertRedirect('/administrator');
    }

    public function test_administrator_pages_load_for_admin()
    {
        $admin = $this->createUser('administrator');
        $this->actingAs($admin);

        $this->get('/administrator')->assertStatus(200);
        $this->get('/administrator/projects')->assertStatus(200);
        $this->post('/administrator/announcements', ['title' => 'BB', 'body' => 'Test'])->assertStatus(302);
    }

    public function test_parent_pages_load_for_parent()
    {
        $parent = $this->createUser('parent');
        $this->actingAs($parent);

        $this->get('/parent')->assertStatus(200);
        $this->get('/parent/announcements')->assertStatus(200);
        $this->get('/parent/projects')->assertStatus(200);
    }
}
