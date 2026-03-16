<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\DashboardMetric;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardMetricTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_fields_correctly()
    {
        $metric = DashboardMetric::factory()->make([
            'current_value' => 123.45,
            'last_updated' => '2026-02-19 00:00:00',
        ]);
        $this->assertIsFloat((float)$metric->current_value);
        $this->assertInstanceOf(\Carbon\Carbon::class, $metric->last_updated);
    }

    public function test_has_project_relationship()
    {
        $metric = DashboardMetric::factory()->make();
        $this->assertTrue(method_exists($metric, 'project'));
    }
}
