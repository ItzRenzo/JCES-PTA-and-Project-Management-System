<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Announcement and Schedule Filtering ===\n\n";

// Test 1: Create announcement with supporting_staff audience
echo "1. Creating announcement with 'supporting_staff' audience...\n";
$announcement = \App\Models\Announcement::create([
    'title' => 'Supporting Staff Meeting',
    'content' => 'Important meeting for all supporting staff members',
    'category' => 'important',
    'audience' => 'supporting_staff',
    'created_by' => 1,
    'published_at' => now(),
    'is_active' => true,
]);
echo "   ✓ Created announcement ID: {$announcement->announcementID}\n\n";

// Test 2: Check if principal can see it
echo "2. Testing if principal can see supporting_staff announcements...\n";
$principalAnnouncements = \App\Models\Announcement::active()
    ->published()
    ->forAudience('principal')
    ->where('announcementID', $announcement->announcementID)
    ->count();
echo "   " . ($principalAnnouncements > 0 ? '✓' : '✗') . " Principal sees {$principalAnnouncements} matching announcement(s)\n\n";

// Test 3: Check if administrator can see it
echo "3. Testing if administrator can see supporting_staff announcements...\n";
$adminAnnouncements = \App\Models\Announcement::active()
    ->published()
    ->forAudience('administrator')
    ->where('announcementID', $announcement->announcementID)
    ->count();
echo "   " . ($adminAnnouncements > 0 ? '✓' : '✗') . " Administrator sees {$adminAnnouncements} matching announcement(s)\n\n";

// Test 4: Check if parent CANNOT see it
echo "4. Testing if parent CANNOT see supporting_staff announcements...\n";
$parentAnnouncements = \App\Models\Announcement::active()
    ->published()
    ->forAudience('parents')
    ->where('announcementID', $announcement->announcementID)
    ->count();
echo "   " . ($parentAnnouncements === 0 ? '✓' : '✗') . " Parent sees {$parentAnnouncements} matching announcement(s)\n\n";

// Test 5: Create schedule with faculty visibility
echo "5. Creating schedule with 'faculty' visibility...\n";
$schedule = \App\Models\Schedule::create([
    'title' => 'Faculty Meeting',
    'description' => 'Meeting for all faculty members',
    'scheduled_date' => now()->addDays(1),
    'category' => 'meeting',
    'priority' => 'high',
    'visibility' => 'faculty',
    'created_by' => 1,
    'is_active' => true,
]);
echo "   ✓ Created schedule ID: {$schedule->scheduleID}\n\n";

// Test 6: Check if teacher can see it
echo "6. Testing if teacher can see faculty schedules...\n";
$teacherSchedules = \App\Models\Schedule::active()
    ->forRole('teacher')
    ->where('scheduleID', $schedule->scheduleID)
    ->count();
echo "   " . ($teacherSchedules > 0 ? '✓' : '✗') . " Teacher sees {$teacherSchedules} matching schedule(s)\n\n";

// Test 7: Check if administrator can see it
echo "7. Testing if administrator can see faculty schedules...\n";
$adminSchedules = \App\Models\Schedule::active()
    ->forRole('administrator')
    ->where('scheduleID', $schedule->scheduleID)
    ->count();
echo "   " . ($adminSchedules > 0 ? '✓' : '✗') . " Administrator sees {$adminSchedules} matching schedule(s)\n\n";

// Test 8: Check if parent CANNOT see it
echo "8. Testing if parent CANNOT see faculty schedules...\n";
$parentSchedules = \App\Models\Schedule::active()
    ->forRole('parent')
    ->where('scheduleID', $schedule->scheduleID)
    ->count();
echo "   " . ($parentSchedules === 0 ? '✓' : '✗') . " Parent sees {$parentSchedules} matching schedule(s)\n\n";

// Clean up
echo "9. Cleaning up test data...\n";
$announcement->delete();
$schedule->delete();
echo "   ✓ Test data deleted\n\n";

echo "=== All Tests Completed ===\n";
