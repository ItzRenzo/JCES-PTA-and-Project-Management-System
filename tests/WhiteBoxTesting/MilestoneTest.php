<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\Milestone;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MilestoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_fields_correctly()
    {
        $milestone = Milestone::factory()->make([
            'target_date' => '2026-02-19',
        ]);
        $this->assertInstanceOf(\Carbon\Carbon::class, $milestone->target_date);
    }

    public function test_has_project_relationship()
    {
        $milestone = Milestone::factory()->make();
        $this->assertTrue(method_exists($milestone, 'project'));
    }
}
