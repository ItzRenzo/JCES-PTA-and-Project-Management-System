<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;


    public function test_casts_published_at_correctly()
    {
        $announcement = Announcement::factory()->make([
            'published_at' => '2026-02-19 10:00:00',
        ]);
        $this->assertInstanceOf(\Carbon\Carbon::class, $announcement->published_at);
    }

    public function test_has_creator_relationship()
    {
        $announcement = Announcement::factory()->make();
        $this->assertTrue(method_exists($announcement, 'creator'));
    }
}
