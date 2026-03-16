<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\ParentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParentProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_fields_correctly()
    {
        $parent = ParentProfile::factory()->make([
            'created_date' => '2026-02-19 00:00:00',
        ]);
        $this->assertInstanceOf(\Carbon\Carbon::class, $parent->created_date);
    }

    public function test_has_user_relationship()
    {
        $parent = ParentProfile::factory()->make();
        $this->assertTrue(method_exists($parent, 'user'));
    }
}
