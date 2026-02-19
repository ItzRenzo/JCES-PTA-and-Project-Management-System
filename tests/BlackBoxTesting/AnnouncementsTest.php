<?php

namespace Tests\BlackBoxTesting;

class AnnouncementsTest extends BlackBoxTestCase
{
    public function test_announcements_list_and_creation()
    {
        $admin = $this->createUser('administrator');
        $this->actingAs($admin)->get('/administrator/announcements')->assertStatus(200);

        $response = $this->actingAs($admin)->post('/administrator/announcements', [
            'title' => 'BB Announcement',
            'content' => 'This is a black-box test announcement',
            'category' => 'notice',
            'audience' => 'everyone',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('announcements', ['title' => 'BB Announcement']);
    }
}
