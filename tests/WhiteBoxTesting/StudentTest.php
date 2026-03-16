<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_excludes_archived_students_from_scope()
    {
        $active = Student::factory()->create(['is_archived' => false]);
        $archived = Student::factory()->create(['is_archived' => true]);
        $students = Student::notArchived()->get();
        $this->assertTrue($students->contains($active));
        $this->assertFalse($students->contains($archived));
    }

    /** @test */
    public function it_casts_dates_and_booleans_correctly()
    {
        $student = Student::factory()->make([
            'enrollment_date' => '2026-02-19',
            'birth_date' => '2010-05-01',
            'is_archived' => true,
        ]);
        $this->assertInstanceOf(\Carbon\Carbon::class, $student->enrollment_date);
        $this->assertInstanceOf(\Carbon\Carbon::class, $student->birth_date);
        $this->assertIsBool($student->is_archived);
    }

    /** @test */
    public function it_has_parents_relationship()
    {
        $student = Student::factory()->create();
        $this->assertTrue(method_exists($student, 'parents'));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $student->parents());
    }
}
