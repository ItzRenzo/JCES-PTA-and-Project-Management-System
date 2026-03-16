<?php

namespace Database\Seeders;

use Database\Seeders\ExpandedSampleDataSeeder;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JcsesPtaSystemSeeder::class,
            UserSeeder::class,
            ExpandedSampleDataSeeder::class,
            AnnouncementSeeder::class,
            ScheduleSeeder::class,
        ]);
    }
}
