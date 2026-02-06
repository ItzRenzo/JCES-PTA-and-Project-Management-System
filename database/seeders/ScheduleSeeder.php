<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUserId = DB::table('users')->where('user_type', 'administrator')->first()?->userID ?? 1;

        // Recent/Past Schedules (for Recent Announcements section)
        DB::table('schedules')->insert([
            [
                'title' => 'Parent–Teacher Conference',
                'description' => 'Scheduled for March 15–17, 2024. Please confirm your attendance.',
                'scheduled_date' => Carbon::now()->subHours(2),
                'start_time' => null,
                'end_time' => null,
                'category' => 'meeting',
                'priority' => 'high',
                'visibility' => 'everyone',
                'created_by' => $adminUserId,
                'is_active' => true,
                'is_completed' => true,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'title' => 'Science Fair Winners',
                'description' => 'Congratulations to all participants in the annual science fair.',
                'scheduled_date' => Carbon::now()->subDay(),
                'start_time' => null,
                'end_time' => null,
                'category' => 'event',
                'priority' => 'medium',
                'visibility' => 'everyone',
                'created_by' => $adminUserId,
                'is_active' => true,
                'is_completed' => true,
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'title' => 'School Maintenance',
                'description' => 'Building maintenance scheduled for this weekend.',
                'scheduled_date' => Carbon::now()->subDays(3),
                'start_time' => null,
                'end_time' => null,
                'category' => 'maintenance',
                'priority' => 'low',
                'visibility' => 'administrator',
                'created_by' => $adminUserId,
                'is_active' => true,
                'is_completed' => true,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
        ]);

        // Upcoming Schedules
        DB::table('schedules')->insert([
            [
                'title' => 'PTA Coordination Meeting',
                'description' => 'Finalize school fundraising and improvement plans.',
                'scheduled_date' => Carbon::now()->addDays(5),
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'category' => 'meeting',
                'priority' => 'high',
                'visibility' => 'administrator',
                'created_by' => $adminUserId,
                'is_active' => true,
                'is_completed' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Quarterly Academic Review',
                'description' => 'Evaluate student performance and upcoming exam preparations.',
                'scheduled_date' => Carbon::now()->addDays(10),
                'start_time' => '13:30:00',
                'end_time' => '14:00:00',
                'category' => 'academic',
                'priority' => 'medium',
                'visibility' => 'administrator',
                'created_by' => $adminUserId,
                'is_active' => true,
                'is_completed' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'School Safety Audit',
                'description' => 'Inspect facilities to ensure a safe learning environment.',
                'scheduled_date' => Carbon::now()->addDays(15),
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'category' => 'review',
                'priority' => 'low',
                'visibility' => 'administrator',
                'created_by' => $adminUserId,
                'is_active' => true,
                'is_completed' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
