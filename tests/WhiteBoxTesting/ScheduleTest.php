<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_fields_correctly()
    {
        $schedule = Schedule::factory()->make([
            'scheduled_date' => '2026-02-19 00:00:00',
        ]);
        $this->assertInstanceOf(\Carbon\Carbon::class, $schedule->scheduled_date);
    }

    public function test_has_creator_relationship()
    {
        $schedule = Schedule::factory()->make();
        $this->assertTrue(method_exists($schedule, 'creator'));
    }
}
