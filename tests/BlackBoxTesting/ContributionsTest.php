<?php

namespace Tests\BlackBoxTesting;

class ContributionsTest extends BlackBoxTestCase
{
    public function test_contributions_index_and_parent_bills()
    {
        $principal = $this->createUser('principal');
        $this->actingAs($principal)->get('/principal/contributions')->assertStatus(200);

        $parent = $this->createUser('parent');
        $parentProfile = \App\Models\ParentProfile::create([
            'first_name' => $parent->first_name ?? 'Parent',
            'last_name' => $parent->last_name ?? 'User',
            'email' => $parent->email ?? 'parent'.rand(1,9999).'@example.test',
            'phone' => '+10000000000',
            'password_hash' => bcrypt('password'),
            'userID' => $parent->userID,
        ]);
        $this->actingAs($principal)->get('/principal/contributions/parent-bills/'.$parentProfile->parentID)->assertStatus(200);
    }

    public function test_submit_manual_payment_and_receipt()
    {
        $principal = $this->createUser('principal');
        $project = \App\Models\Project::factory()->create(['goals' => 'Test goals']);
        $parentUser = $this->createUser('parent');
        $parentProfile = \App\Models\ParentProfile::create([
            'first_name' => $parentUser->first_name ?? 'Parent',
            'last_name' => $parentUser->last_name ?? 'User',
            'email' => $parentUser->email ?? 'parent'.rand(1,9999).'@example.test',
            'phone' => '+10000000000',
            'password_hash' => bcrypt('password'),
            'userID' => $parentUser->userID,
        ]);
        $contribution = \App\Models\ProjectContribution::factory()->create([
            'projectID' => $project->projectID,
            'parentID' => $parentProfile->parentID,
            'payment_method' => 'cash',
        ]);

        $this->actingAs($principal)->post('/principal/contributions/submit-manual', [
            'contribution_id' => $contribution->contributionID,
            'amount' => 100,
        ])->assertStatus(302);

        $this->actingAs($principal)->get('/principal/contributions/'.$contribution->contributionID.'/receipt')->assertStatus(200);
    }
}
