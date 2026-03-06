<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ExpandedSampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = $this->getUserIdByEmail('admin@jcses.edu.ph');
        $principalId = $this->getUserIdByEmail('principal@jcses.edu.ph');
        $teacherId = $this->getUserIdByEmail('teacher@jcses.edu.ph');

        $parentIds = $this->seedParents();
        $studentIds = $this->seedStudents($parentIds);
        $projectIds = $this->seedProjects($principalId ?: $adminId ?: $teacherId);

        $this->seedProjectUpdates($projectIds, $principalId ?: $adminId ?: $teacherId);
        $this->seedContributionsAndPayments($parentIds, $projectIds, $teacherId ?: $principalId ?: $adminId);
        $this->refreshProjectTotals($projectIds);

        $this->command->info('Expanded sample data seeded successfully.');
        $this->command->info('Users: ' . DB::table('users')->count());
        $this->command->info('Parents: ' . DB::table('parents')->count());
        $this->command->info('Students: ' . DB::table('students')->count());
        $this->command->info('Projects: ' . DB::table('projects')->count());
        $this->command->info('Project contributions: ' . DB::table('project_contributions')->count());
        $this->command->info('Payment transactions: ' . DB::table('payment_transactions')->count());
        $this->command->info('Parent-student relationships: ' . DB::table('parent_student_relationships')->count());
        $this->command->info('Project updates: ' . DB::table('project_updates')->count());
        $this->command->info('Domain records total: ' . (
            DB::table('parents')->count()
            + DB::table('students')->count()
            + DB::table('projects')->count()
            + DB::table('project_contributions')->count()
            + DB::table('payment_transactions')->count()
            + DB::table('parent_student_relationships')->count()
            + DB::table('project_updates')->count()
        ));
    }

    private function seedParents(): array
    {
        $parentProfiles = [
            ['Liza', 'Reyes', 'Quezon City', 'Bagumbayan'],
            ['Mario', 'Fernandez', 'Manila', 'Sampaloc'],
            ['Rina', 'Mendoza', 'Makati', 'Poblacion'],
            ['Joel', 'Garcia', 'Pasig', 'Kapitolyo'],
            ['Mylene', 'Torres', 'Taguig', 'Lower Bicutan'],
            ['Noel', 'Ramos', 'Paranaque', 'San Antonio'],
            ['Grace', 'Aquino', 'Caloocan', 'Bagong Silang'],
            ['Paolo', 'Santos', 'Marikina', 'Concepcion Uno'],
            ['Ivy', 'Navarro', 'Las Pinas', 'Pamplona Tres'],
            ['Dennis', 'Castro', 'Muntinlupa', 'Tunasan'],
            ['Ana', 'Villanueva', 'Mandaluyong', 'Plainview'],
            ['Rogelio', 'Dela Cruz', 'San Juan', 'Ermitaño'],
            ['Celia', 'Morales', 'Malabon', 'Tonsuya'],
            ['Victor', 'Bautista', 'Navotas', 'Sipac-Almacen'],
            ['Marjorie', 'Domingo', 'Pasay', 'Barangay 76'],
            ['Edwin', 'Jimenez', 'Valenzuela', 'Marulas'],
            ['Shiela', 'Ocampo', 'Antipolo', 'Cupang'],
            ['Ramon', 'Pascual', 'Bacoor', 'Talaba'],
            ['Theresa', 'Salazar', 'Imus', 'Malagasang'],
            ['Alvin', 'Tan', 'Dasmarinas', 'Paliparan'],
            ['Jessa', 'Uy', 'San Pedro', 'Pacita'],
            ['Roman', 'Vera', 'Binan', 'Canlalay'],
            ['Patricia', 'Yap', 'Sta. Rosa', 'Tagapo'],
            ['Harold', 'Zamora', 'Taytay', 'Dolores'],
            ['Nina', 'Abad', 'Cainta', 'San Andres'],
        ];

        $parentIds = [];

        foreach ($parentProfiles as $index => [$firstName, $lastName, $city, $barangay]) {
            $sequence = $index + 1;
            $email = sprintf('parent%02d@jcses.edu.ph', $sequence);
            $username = sprintf('parent%02d', $sequence);
            $phone = sprintf('09%09d', 170000000 + $sequence);

            $userId = $this->upsertUser([
                'username' => $username,
                'email' => $email,
                'password_hash' => Hash::make('parent123'),
                'plain_password' => 'parent123',
                'user_type' => 'parent',
                'phone' => $phone,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'is_active' => true,
                'is_archived' => false,
                'failed_login_attempts' => 0,
            ]);

            $parentIds[] = $this->upsertParent([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'street_address' => sprintf('%d Mabini Street', 100 + $sequence),
                'city' => $city,
                'barangay' => $barangay,
                'zipcode' => (string) (1100 + $sequence),
                'password_hash' => Hash::make('parent123'),
                'account_status' => 'active',
                'userID' => $userId,
                'emergency_contact_name' => 'Emergency Contact ' . $sequence,
                'emergency_contact_phone' => sprintf('09%09d', 180000000 + $sequence),
            ]);
        }

        return $parentIds;
    }

    private function seedStudents(array $parentIds): array
    {
        $firstNames = ['Alyssa', 'Brent', 'Cheska', 'Darren', 'Ella', 'Francis', 'Gia', 'Harvey', 'Isabel', 'Joshua'];
        $lastNames = ['Alonzo', 'Borja', 'Castillo', 'Diaz', 'Espiritu', 'Flores', 'Gutierrez', 'Hernandez', 'Ignacio', 'Lopez'];
        $sections = ['Rizal', 'Bonifacio', 'Mabini', 'Luna', 'Jacinto', 'Del Pilar'];
        $grades = ['K', '1', '2', '3', '4', '5', '6'];
        $studentIds = [];

        for ($index = 0; $index < 50; $index++) {
            $firstName = $firstNames[$index % count($firstNames)];
            $lastName = $lastNames[intdiv($index, count($firstNames)) % count($lastNames)];
            $studentName = $firstName . ' ' . $lastName;
            $gradeLevel = $grades[$index % count($grades)];
            $section = $sections[$index % count($sections)];

            $existing = DB::table('students')
                ->where('student_name', $studentName)
                ->where('academic_year', '2025-2026')
                ->first();

            $studentPayload = [
                'student_name' => $studentName,
                'grade_level' => $gradeLevel,
                'section' => $section,
                'enrollment_status' => 'active',
                'academic_year' => '2025-2026',
                'enrollment_date' => now()->subDays(120 - $index),
                'birth_date' => now()->subYears(7 + ($index % 6))->subDays($index + 10),
                'gender' => $index % 2 === 0 ? 'female' : 'male',
                'is_archived' => false,
                'created_date' => now(),
                'updated_date' => now(),
            ];

            $studentPayload = $this->filterColumns('students', $studentPayload);

            if ($existing) {
                DB::table('students')
                    ->where('studentID', $existing->studentID)
                    ->update($studentPayload);

                $studentId = $existing->studentID;
            } else {
                $studentId = DB::table('students')->insertGetId($studentPayload);
            }

            $studentIds[] = $studentId;

            $primaryParentId = $parentIds[$index % count($parentIds)];
            $secondaryParentId = $parentIds[($index + 7) % count($parentIds)];

            $this->upsertParentStudentRelationship($primaryParentId, $studentId, $index % 2 === 0 ? 'mother' : 'father', true);

            if ($index % 4 === 0) {
                $this->upsertParentStudentRelationship($secondaryParentId, $studentId, 'guardian', false);
            }
        }

        return $studentIds;
    }

    private function seedProjects(?int $createdBy): array
    {
        $projects = [
            ['School Library Renovation', 'Renovate reading areas and replace shelves.', 'Create a more functional and welcoming library.', 120000, 'active'],
            ['Computer Laboratory Upgrade', 'Add desktops, networking, and learning software.', 'Improve digital literacy and access to technology.', 180000, 'active'],
            ['Sports Equipment Fund', 'Purchase new PE and intramurals equipment.', 'Support student athletics and wellness.', 65000, 'active'],
            ['Classroom Ventilation Improvement', 'Install additional fans and repair windows.', 'Improve classroom comfort and air circulation.', 90000, 'in_progress'],
            ['Science Corner Enhancement', 'Buy lab kits and display materials.', 'Support hands-on science activities.', 55000, 'active'],
            ['Water Station Refill Program', 'Provide safe drinking water stations campus-wide.', 'Improve daily student hydration access.', 40000, 'created'],
            ['Reading Nook per Grade Level', 'Set up mini reading corners in classrooms.', 'Promote daily reading habits among students.', 70000, 'in_progress'],
            ['Campus Greening Project', 'Plant trees and maintain garden spaces.', 'Build a cleaner and greener school environment.', 35000, 'active'],
        ];

        $projectIds = [];

        foreach ($projects as $offset => [$name, $description, $goals, $budget, $status]) {
            $existing = DB::table('projects')->where('project_name', $name)->first();
            $payload = [
                'project_name' => $name,
                'description' => $description,
                'goals' => $goals,
                'target_budget' => $budget,
                'current_amount' => 0,
                'start_date' => now()->subDays(30 + ($offset * 7))->toDateString(),
                'target_completion_date' => now()->addDays(90 + ($offset * 15))->toDateString(),
                'project_status' => $status,
                'created_by' => $createdBy,
                'created_date' => now(),
                'updated_date' => now(),
            ];

            $payload = $this->filterColumns('projects', $payload);

            if ($existing) {
                DB::table('projects')->where('projectID', $existing->projectID)->update($payload);
                $projectIds[] = $existing->projectID;
            } else {
                $projectIds[] = DB::table('projects')->insertGetId($payload);
            }
        }

        return $projectIds;
    }

    private function seedProjectUpdates(array $projectIds, ?int $updatedBy): void
    {
        foreach ($projectIds as $index => $projectId) {
            for ($step = 1; $step <= 2; $step++) {
                $title = sprintf('Project %d Progress Update %d', $index + 1, $step);
                $existing = DB::table('project_updates')
                    ->where('projectID', $projectId)
                    ->where('update_title', $title)
                    ->first();

                $payload = [
                    'projectID' => $projectId,
                    'update_title' => $title,
                    'update_description' => 'Milestone review and implementation update for project activities.',
                    'milestone_achieved' => $step === 1 ? 'Planning approved' : 'Procurement started',
                    'progress_percentage' => min(100, 15 + ($index * 7) + ($step * 20)),
                    'update_date' => now()->subDays(20 - ($index + $step)),
                    'updated_by' => $updatedBy,
                ];

                $payload = $this->filterColumns('project_updates', $payload);

                if ($existing) {
                    DB::table('project_updates')->where('updateID', $existing->updateID)->update($payload);
                } else {
                    DB::table('project_updates')->insert($payload);
                }
            }
        }
    }

    private function seedContributionsAndPayments(array $parentIds, array $projectIds, ?int $processedBy): void
    {
        $statuses = ['completed', 'completed', 'pending', 'completed', 'failed'];
        $methods = ['cash', 'check', 'bank_transfer'];

        for ($index = 1; $index <= 75; $index++) {
            $parentId = $parentIds[($index - 1) % count($parentIds)];
            $projectId = $projectIds[($index - 1) % count($projectIds)];
            $status = $statuses[($index - 1) % count($statuses)];
            $method = $methods[($index - 1) % count($methods)];
            $amount = $status === 'failed' ? 0 : (500 + (($index % 8) * 150));
            $transactionDate = now()->subDays(($index * 2) % 180)->subMinutes($index * 3);
            $receiptNumber = sprintf('REC-SAMPLE-%04d', $index);
            $referenceNumber = sprintf('REF-SAMPLE-%04d', $index);

            $contribution = DB::table('project_contributions')
                ->where('receipt_number', $receiptNumber)
                ->first();

            $contributionPayload = [
                'projectID' => $projectId,
                'parentID' => $parentId,
                'contribution_amount' => $amount,
                'payment_method' => $method,
                'payment_status' => $status === 'failed' ? 'pending' : $status,
                'contribution_date' => $transactionDate,
                'receipt_number' => $receiptNumber,
                'notes' => $status === 'completed'
                    ? 'Seeded sample payment received.'
                    : ($status === 'pending' ? 'Seeded sample payment awaiting confirmation.' : 'Seeded sample payment failed and needs follow-up.'),
                'processed_by' => $processedBy,
            ];

            $contributionPayload = $this->filterColumns('project_contributions', $contributionPayload);

            if ($contribution) {
                DB::table('project_contributions')
                    ->where('contributionID', $contribution->contributionID)
                    ->update($contributionPayload);

                $contributionId = $contribution->contributionID;
            } else {
                $contributionId = DB::table('project_contributions')->insertGetId($contributionPayload);
            }

            DB::table('payment_transactions')->updateOrInsert(
                ['receipt_number' => $receiptNumber],
                $this->filterColumns('payment_transactions', [
                    'parentID' => $parentId,
                    'projectID' => $projectId,
                    'contributionID' => $contributionId,
                    'amount' => $amount,
                    'payment_method' => $method,
                    'transaction_status' => $status,
                    'transaction_date' => $transactionDate,
                    'receipt_number' => $receiptNumber,
                    'reference_number' => $referenceNumber,
                    'processed_by' => $processedBy,
                    'notes' => 'Generated by ExpandedSampleDataSeeder.',
                ])
            );
        }
    }

    private function refreshProjectTotals(array $projectIds): void
    {
        foreach ($projectIds as $projectId) {
            $completedAmount = (float) DB::table('project_contributions')
                ->where('projectID', $projectId)
                ->where('payment_status', 'completed')
                ->sum('contribution_amount');

            DB::table('projects')
                ->where('projectID', $projectId)
                ->update([
                    'current_amount' => $completedAmount,
                    'updated_date' => now(),
                ]);
        }
    }

    private function upsertParentStudentRelationship(int $parentId, int $studentId, string $relationshipType, bool $isPrimaryContact): void
    {
        $existing = DB::table('parent_student_relationships')
            ->where('parentID', $parentId)
            ->where('studentID', $studentId)
            ->first();

        $payload = [
            'relationship_type' => $relationshipType,
            'is_primary_contact' => $isPrimaryContact,
            'created_date' => now(),
        ];

        $payload = $this->filterColumns('parent_student_relationships', $payload);

        if ($existing) {
            DB::table('parent_student_relationships')
                ->where('relationshipID', $existing->relationshipID)
                ->update($payload);

            return;
        }

        DB::table('parent_student_relationships')->insert($this->filterColumns('parent_student_relationships', [
            'parentID' => $parentId,
            'studentID' => $studentId,
            'relationship_type' => $relationshipType,
            'is_primary_contact' => $isPrimaryContact,
            'created_date' => now(),
        ]));
    }

    private function upsertUser(array $payload): int
    {
        $payload = $this->filterColumns('users', $payload);
        $existing = DB::table('users')->where('email', $payload['email'])->first();

        if ($existing) {
            DB::table('users')
                ->where('userID', $existing->userID)
                ->update($payload);

            return $existing->userID;
        }

        return DB::table('users')->insertGetId($payload);
    }

    private function upsertParent(array $payload): int
    {
        $payload = $this->filterColumns('parents', $payload);
        $existing = DB::table('parents')->where('email', $payload['email'])->first();

        if ($existing) {
            DB::table('parents')
                ->where('parentID', $existing->parentID)
                ->update($payload);

            return $existing->parentID;
        }

        return DB::table('parents')->insertGetId($this->filterColumns('parents', array_merge($payload, [
            'created_date' => now(),
        ])));
    }

    private function getUserIdByEmail(string $email): ?int
    {
        return DB::table('users')->where('email', $email)->value('userID');
    }

    private function filterColumns(string $table, array $payload): array
    {
        $columns = array_flip(Schema::getColumnListing($table));

        return array_intersect_key($payload, $columns);
    }
}
