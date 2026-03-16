<?php

namespace Tests\BlackBoxTesting;

use Illuminate\Support\Facades\Notification;

class EmailFlowsTest extends BlackBoxTestCase
{
    public function test_password_reset_email_is_dispatched()
    {
        Notification::fake();

        $user = $this->createUser('parent');

        // Trigger password reset notification
        $this->post('/forgot-password', ['email' => $user->email]);
        Notification::assertSentTo([$user], \Illuminate\Auth\Notifications\ResetPassword::class);
    }
}
