<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_casts_dates_and_booleans_correctly()
    {
        $user = User::factory()->make([
            'created_date' => '2026-02-19 12:00:00',
            'last_login' => '2026-02-19 13:00:00',
            'password_changed_date' => '2026-02-19 14:00:00',
            'account_locked_until' => '2026-02-19 15:00:00',
            'is_active' => true,
            'is_archived' => false,
        ]);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->created_date);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->last_login);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->password_changed_date);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->account_locked_until);
        $this->assertIsBool($user->is_active);
        $this->assertIsBool($user->is_archived);
    }

    /** @test */
    public function it_has_relationship_methods()
    {
        $user = User::factory()->make();
        $this->assertTrue(method_exists($user, 'parentProfile'));
        $this->assertTrue(method_exists($user, 'roleAssignments'));
        $this->assertTrue(method_exists($user, 'auditLogs'));
        $this->assertTrue(method_exists($user, 'sessions'));
        $this->assertInstanceOf(\App\Models\ParentProfile::class, $user->parentProfile()->getRelated());
        $this->assertInstanceOf(\App\Models\SecurityAuditLog::class, $user->auditLogs()->getRelated());
    }

    /** @test */
    public function it_returns_full_name_or_username()
    {
        $user = User::factory()->make([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
        ]);
        $this->assertEquals('John Doe', $user->name);

        $user = User::factory()->make([
            'first_name' => '',
            'last_name' => '',
            'username' => 'johndoe',
        ]);
        $this->assertEquals('johndoe', $user->name);
    }

    /** @test */
    public function it_hashes_password_when_set()
    {
        $user = User::factory()->make();
        $user->password = 'secret123';
        $this->assertTrue(password_verify('secret123', $user->password_hash));
    }

    /** @test */
    public function it_identifies_user_roles()
    {
        $admin = User::factory()->make(['user_type' => 'administrator']);
        $this->assertTrue($admin->isAdministrator());

        $principal = User::factory()->make(['user_type' => 'principal']);
        $this->assertTrue($principal->isPrincipal());

        $teacher = User::factory()->make(['user_type' => 'teacher']);
        $this->assertTrue($teacher->isTeacher());

        $parent = User::factory()->make(['user_type' => 'parent']);
        $this->assertTrue($parent->isParent());
    }

    /** @test */
    public function it_locks_account_after_failed_logins()
    {
        $user = User::factory()->create(['failed_login_attempts' => 4]);
        $user->incrementFailedLoginAttempts();
        $user->refresh();
        $this->assertNotNull($user->account_locked_until);
        $this->assertTrue($user->isLocked());
    }

    /** @test */
    public function it_resets_lock_after_time_passed()
    {
        $user = User::factory()->create([
            'account_locked_until' => now()->subMinutes(31),
            'failed_login_attempts' => 5
        ]);
        $this->assertFalse($user->isLocked());
        $user->refresh();
        $this->assertNull($user->account_locked_until);
        $this->assertEquals(0, $user->failed_login_attempts);
    }
}
