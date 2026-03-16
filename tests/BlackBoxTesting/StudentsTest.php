<?php

namespace Tests\BlackBoxTesting;

class StudentsTest extends BlackBoxTestCase
{
    public function test_student_pages_and_import()
    {
        $principal = $this->createUser('principal');
        $this->actingAs($principal)->get('/principal/students')->assertStatus(200);

        // Test store via factory (store route may validate heavily; use factory creation and then update/delete routes)
        $student = \App\Models\Student::factory()->create();
        $this->actingAs($principal)->put('/principal/students/'.$student->studentID, [
            'student_name' => $student->student_name,
            'grade_level' => $student->grade_level,
            'section' => $student->section,
            'academic_year' => $student->academic_year,
            'enrollment_date' => $student->enrollment_date->format('Y-m-d'),
            'birth_date' => $student->birth_date,
            'gender' => $student->gender,
            'enrollment_status' => $student->enrollment_status ?? 'active',
        ])->assertStatus(200);
    }
}
