<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\ProjectUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_progress_and_date_correctly()
    {
        $update = ProjectUpdate::factory()->make([
            'progress_percentage' => 75.25,
            'update_date' => '2026-02-19 10:00:00',
        ]);
        $this->assertIsFloat((float)$update->progress_percentage);
        $this->assertInstanceOf(\Carbon\Carbon::class, $update->update_date);
    }

    public function test_has_project_relationship()
    {
        $update = ProjectUpdate::factory()->make();
        $this->assertTrue(method_exists($update, 'project'));
        $this->assertInstanceOf(\App\Models\Project::class, $update->project()->getRelated());
    }

    public function test_has_updater_relationship()
    {
        $update = ProjectUpdate::factory()->make();
        $this->assertTrue(method_exists($update, 'updater'));
        $this->assertInstanceOf(\App\Models\User::class, $update->updater()->getRelated());
    }
}
