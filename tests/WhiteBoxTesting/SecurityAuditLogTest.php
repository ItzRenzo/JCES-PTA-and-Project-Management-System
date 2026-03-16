<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\SecurityAuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityAuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_fields_correctly()
    {
        $log = SecurityAuditLog::factory()->make([
            'timestamp' => '2026-02-19 00:00:00',
        ]);
        $this->assertInstanceOf(\Carbon\Carbon::class, $log->timestamp);
    }

    public function test_has_user_relationship()
    {
        $log = SecurityAuditLog::factory()->make();
        $this->assertTrue(method_exists($log, 'user'));
    }
}
