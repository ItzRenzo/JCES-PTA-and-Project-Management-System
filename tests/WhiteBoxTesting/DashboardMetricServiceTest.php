<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Services\DashboardMetricService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardMetricServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_kpis_array()
    {
        $service = new DashboardMetricService();
        $result = $service->getKpis();
        $this->assertIsArray($result);
        $expectedKeys = [
            'totalStudents',
            'activeStudents',
            'activeRate',
            'totalParents',
            'participationRate',
            'totalContributions',
            'totalPayments',
            'activeProjects',
            'projectCompletionRate',
        ];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $result);
        }
    }
}
