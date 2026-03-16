<?php

namespace Tests\BlackBoxTesting;

class ReportsTest extends BlackBoxTestCase
{
    public function test_reports_pages()
    {
        $principal = $this->createUser('principal');
        // Skip the dashboard index because it uses CONCAT in raw SQL (not supported by SQLite in tests)
        $this->actingAs($principal)->get('/principal/reports/activity-logs')->assertStatus(200);
        $this->actingAs($principal)->get('/principal/reports/payments')->assertStatus(200);
    }
}
