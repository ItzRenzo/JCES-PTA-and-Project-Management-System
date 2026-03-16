<?php

namespace Tests\BlackBoxTesting;

class PaymentsTest extends BlackBoxTestCase
{
    public function test_payment_transaction_and_receipt_flow()
    {
        $parent = $this->createUser('parent');
        $parentProfile = \App\Models\ParentProfile::create([
            'first_name' => $parent->first_name ?? 'Parent',
            'last_name' => $parent->last_name ?? 'User',
            'email' => $parent->email ?? 'parent'.rand(1,9999).'@example.test',
            'phone' => '+10000000000',
            'password_hash' => bcrypt('password'),
            'userID' => $parent->userID,
        ]);
        $project = \App\Models\Project::factory()->create(['goals' => 'Test goals']);
        $contribution = \App\Models\ProjectContribution::factory()->create([
            'projectID' => $project->projectID,
            'parentID' => $parentProfile->parentID,
            'payment_method' => 'cash',
        ]);

        $transaction = \App\Models\PaymentTransaction::factory()->create([
            'parentID' => $parentProfile->parentID,
            'projectID' => $project->projectID,
            'contributionID' => $contribution->contributionID,
            'payment_method' => 'cash',
            'processed_by' => $parent->userID,
            'receipt_number' => 'RCPT'.strval(rand(1000,9999)),
        ]);

        $this->actingAs($parent)->get('/parent')->assertStatus(200);
        $this->actingAs($parent)->get('/parent/projects')->assertStatus(200);

        $this->assertDatabaseHas('payment_transactions', ['paymentID' => $transaction->paymentID]);
    }
}
