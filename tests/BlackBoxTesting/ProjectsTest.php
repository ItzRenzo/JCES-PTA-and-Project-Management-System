<?php

namespace Tests\BlackBoxTesting;

class ProjectsTest extends BlackBoxTestCase
{
    public function test_project_index_and_show_pages()
    {
        $principal = $this->createUser('principal');
        $project = \App\Models\Project::factory()->create(['goals' => 'Test goals']);

        $this->actingAs($principal)->get('/principal/projects')->assertStatus(200);
        $this->actingAs($principal)->get('/principal/projects/'.$project->id)->assertStatus(200);
    }

    public function test_admin_can_view_and_update_project()
    {
        $admin = $this->createUser('administrator');
        $project = \App\Models\Project::factory()->create(['goals' => 'Test goals']);

        $this->actingAs($admin)->get('/administrator/projects/'.$project->projectID)->assertStatus(200);

        $updatePayload = [
            'project_name' => $project->project_name . ' updated',
            'description' => $project->description,
            'goals' => $project->goals,
            'target_budget' => $project->target_budget,
            'start_date' => $project->start_date->format('Y-m-d'),
            'target_completion_date' => $project->target_completion_date->format('Y-m-d'),
            'project_status' => $project->project_status ?? 'created',
            'actual_completion_date' => $project->actual_completion_date ?? null,
        ];

        $response = $this->actingAs($admin)->put('/administrator/projects/'.$project->projectID, $updatePayload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('projects', ['projectID' => $project->projectID, 'project_name' => $updatePayload['project_name']]);
    }
}
