<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_fields_correctly()
    {
        $project = Project::factory()->make([
            'target_budget' => 10000.00,
            'start_date' => '2026-02-19',
            'target_completion_date' => '2026-03-19',
        ]);
        $this->assertIsFloat((float)$project->target_budget);
        $this->assertInstanceOf(\Carbon\Carbon::class, $project->start_date);
        $this->assertInstanceOf(\Carbon\Carbon::class, $project->target_completion_date);
    }

    public function test_has_relationships()
    {
        $project = Project::factory()->make();
        $this->assertTrue(method_exists($project, 'milestones'));
        $this->assertTrue(method_exists($project, 'contributions'));
        $this->assertTrue(method_exists($project, 'updates'));
    }
}
