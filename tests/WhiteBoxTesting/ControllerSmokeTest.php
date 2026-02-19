<?php

namespace Tests\WhiteBoxTesting;

use Tests\TestCase;

class ControllerSmokeTest extends TestCase
{
    public function test_all_controllers_load()
    {
        $controllers = [
            \App\Http\Controllers\MilestoneController::class,
            \App\Http\Controllers\ParentContributionController::class,
            \App\Http\Controllers\Controller::class,
            \App\Http\Controllers\ContributionController::class,
            \App\Http\Controllers\TeacherController::class,
            \App\Http\Controllers\ScheduleController::class,
            \App\Http\Controllers\ReportsController::class,
            \App\Http\Controllers\ProjectUpdateController::class,
            \App\Http\Controllers\ProjectController::class,
            \App\Http\Controllers\ProfileController::class,
            \App\Http\Controllers\PrincipalController::class,
            \App\Http\Controllers\ParentProjectController::class,
            \App\Http\Controllers\ParentController::class,
            \App\Http\Controllers\AnnouncementController::class,
        ];
        foreach ($controllers as $controller) {
            $this->assertTrue(class_exists($controller));
        }
    }
}
