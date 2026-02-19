<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\ProjectContribution;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectContributionTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_amount_and_date_correctly()
    {
        $contribution = ProjectContribution::factory()->make([
            'contribution_amount' => 100.50,
            'contribution_date' => '2026-02-19 12:00:00',
        ]);
        // Check cast for amount (decimal:2)
        $this->assertIsFloat((float)$contribution->contribution_amount);
        // Check cast for date (datetime)
        $this->assertInstanceOf(\Carbon\Carbon::class, $contribution->contribution_date);
    }

    public function test_has_project_relationship()
    {
        $contribution = ProjectContribution::factory()->make();
        $this->assertTrue(method_exists($contribution, 'project'));
        $this->assertInstanceOf(\App\Models\Project::class, $contribution->project()->getRelated());
    }

    public function test_has_parent_relationship()
    {
        $contribution = ProjectContribution::factory()->make();
        $this->assertTrue(method_exists($contribution, 'parent'));
        $this->assertInstanceOf(\App\Models\ParentProfile::class, $contribution->parent()->getRelated());
    }

    /** @test */
    public function it_has_processed_by_relationship()
    {
        $contribution = ProjectContribution::factory()->make();
        $this->assertTrue(method_exists($contribution, 'processedBy'));
        $this->assertInstanceOf(\App\Models\User::class, $contribution->processedBy()->getRelated());
    }

    /** @test */
    public function it_has_transaction_relationship()
    {
        $contribution = ProjectContribution::factory()->make();
        $this->assertTrue(method_exists($contribution, 'transaction'));
        $this->assertInstanceOf(\App\Models\PaymentTransaction::class, $contribution->transaction()->getRelated());
    }
}
