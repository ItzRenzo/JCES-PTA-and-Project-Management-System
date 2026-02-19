<?php

namespace Tests\BlackBoxTesting;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class EmailFlowsTest extends BlackBoxTestCase
{
    public function test_password_reset_and_verification_emails_are_dispatched()
    {
        Notification::fake();

        $user = $this->createUser('parent', ['email_verified_at' => null]);

        // Trigger password reset notification
        $this->post('/forgot-password', ['email' => $user->email]);
        Notification::assertSentTo([$user], \Illuminate\Auth\Notifications\ResetPassword::class);

        // Trigger email verification resend (requires auth)
        $this->actingAs($user)->post('/email/verification-notification');
        Notification::assertSentTo([$user], \Illuminate\Auth\Notifications\VerifyEmail::class);
    }
}
