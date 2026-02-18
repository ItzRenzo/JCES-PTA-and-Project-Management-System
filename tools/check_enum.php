<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check announcements audience enum
$result = DB::select("SHOW COLUMNS FROM announcements WHERE Field = 'audience'");
echo "Announcements audience enum:\n";
print_r($result);

// Check schedules visibility enum
$result = DB::select("SHOW COLUMNS FROM schedules WHERE Field = 'visibility'");
echo "\n\nSchedules visibility enum:\n";
print_r($result);

// Test creating an announcement with supporting_staff audience
echo "\n\nTesting announcement creation with supporting_staff audience...\n";
try {
    $announcement = \App\Models\Announcement::create([
        'title' => 'Test Announcement',
        'content' => 'This is a test announcement for supporting staff',
        'category' => 'notice',
        'audience' => 'supporting_staff',
        'created_by' => 1,
        'published_at' => now(),
        'is_active' => true,
    ]);
    echo "✓ Successfully created announcement with ID: " . $announcement->announcementID . "\n";

    // Clean up - delete the test announcement
    $announcement->delete();
    echo "✓ Test announcement deleted\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
