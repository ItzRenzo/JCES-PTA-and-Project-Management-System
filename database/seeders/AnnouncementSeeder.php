<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUserId = DB::table('users')->where('role', 'administrator')->first()?->userID ?? 1;

        DB::table('announcements')->insert([
            [
                'title' => 'Parent-Teacher Conference Schedule',
                'content' => 'The annual parent-teacher conference will be held on March 15-17, 2024. Please schedule your appointments through the school portal.',
                'category' => 'important',
                'audience' => 'parents',
                'created_by' => $adminUserId,
                'published_at' => Carbon::now()->subDays(2),
                'expires_at' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'School Closure Notice',
                'content' => 'The school will be closed on March 8, 2024 due to a scheduled maintenance. Classes will resume on March 11, 2024.',
                'category' => 'notice',
                'audience' => 'everyone',
                'created_by' => $adminUserId,
                'published_at' => Carbon::now()->subWeek(),
                'expires_at' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->subWeek(),
                'updated_at' => Carbon::now()->subWeek(),
            ],
            [
                'title' => 'New Learning Management System',
                'content' => 'We are excited to announce the launch of our new learning management system. Training sessions for parents will begin next week.',
                'category' => 'update',
                'audience' => 'everyone',
                'created_by' => $adminUserId,
                'published_at' => Carbon::now()->subWeeks(2),
                'expires_at' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->subWeeks(2),
                'updated_at' => Carbon::now()->subWeeks(2),
            ],
        ]);
    }
}
