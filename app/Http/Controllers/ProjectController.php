<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectContribution;
use App\Models\ProjectUpdate;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private function resolveProjectsView(string $view): string
    {
        $user = auth()->user();

        if ($user && $user->user_type === 'administrator') {
            return "administrator.projects.$view";
        }

        return "principal.projects.$view";
    }

    private function resolveProjectsRoute(string $route): string
    {
        $user = auth()->user();

        if ($user && $user->user_type === 'administrator') {
            return "administrator.projects.$route";
        }

        return "principal.projects.$route";
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $projectsQuery = Project::query();

        if (!empty($status)) {
            $projectsQuery->where('project_status', $status);
        }

        if (!empty($search)) {
            $projectsQuery->where('project_name', 'like', '%' . $search . '%');
        }

        $projects = $projectsQuery
            ->orderBy('created_date', 'desc')
            ->paginate(15)
            ->appends($request->all());

        $statusOptions = ['created', 'active', 'in_progress', 'completed', 'archived', 'cancelled'];

        return view($this->resolveProjectsView('index'), compact('projects', 'status', 'search', 'statusOptions'));
    }

    public function create()
    {
        $statusOptions = ['created', 'active', 'in_progress', 'completed', 'archived', 'cancelled'];

        return view($this->resolveProjectsView('create'), compact('statusOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'goals' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:200'],
            'venue' => ['nullable', 'string', 'max:200'],
            'time' => ['nullable', 'string', 'max:100'],
            'objective' => ['nullable', 'string'],
            'project_photo' => ['nullable', 'image', 'max:2048'],
            'target_budget' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'target_completion_date' => ['required', 'date', 'after_or_equal:start_date'],
            'project_status' => ['nullable', 'in:created,active,in_progress,completed,archived,cancelled'],
        ]);

        $photoPath = null;
        if ($request->hasFile('project_photo')) {
            $filename = 'project_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $request->file('project_photo')->getClientOriginalExtension();
            $request->file('project_photo')->move(public_path('images/projects'), $filename);
            $photoPath = '/images/projects/' . $filename;
        }

        $description = $validated['description'] ?? null;
        if (empty($description)) {
            $parts = [];
            if (!empty($validated['title'])) {
                $parts[] = 'Title: ' . $validated['title'];
            }
            if (!empty($validated['venue'])) {
                $parts[] = 'Venue: ' . $validated['venue'];
            }
            if (!empty($validated['time'])) {
                $parts[] = 'Time: ' . $validated['time'];
            }
            if (!empty($photoPath)) {
                $parts[] = 'Photo: ' . $photoPath;
            }
            $description = !empty($parts) ? implode("\n", $parts) : 'Project details pending.';
        }

        $goals = $validated['goals'] ?? null;
        if (empty($goals)) {
            $goals = $validated['objective'] ?? 'Objectives pending.';
        }

        $project = Project::create([
            'project_name' => $validated['project_name'],
            'description' => $description,
            'goals' => $goals,
            'target_budget' => $validated['target_budget'],
            'current_amount' => 0,
            'start_date' => $validated['start_date'],
            'target_completion_date' => $validated['target_completion_date'],
            'project_status' => $validated['project_status'] ?? 'created',
            'created_by' => auth()->user()->userID,
            'created_date' => now(),
            'updated_date' => now(),
        ]);

        return redirect()->route($this->resolveProjectsRoute('show'), $project->projectID);
    }

    public function show(int $projectID)
    {
        $project = Project::with(['contributions.parent'])
            ->where('projectID', $projectID)
            ->firstOrFail();

        $updates = ProjectUpdate::where('projectID', $projectID)
            ->orderBy('update_date', 'desc')
            ->get();

        $contributions = ProjectContribution::with(['parent', 'processedBy'])
            ->where('projectID', $projectID)
            ->orderBy('contribution_date', 'desc')
            ->paginate(10);

        return view($this->resolveProjectsView('show'), compact('project', 'updates', 'contributions'));
    }

    public function edit(int $projectID)
    {
        $project = Project::where('projectID', $projectID)->firstOrFail();
        $statusOptions = ['created', 'active', 'in_progress', 'completed', 'archived', 'cancelled'];

        return view($this->resolveProjectsView('edit'), compact('project', 'statusOptions'));
    }

    public function update(Request $request, int $projectID)
    {
        $validated = $request->validate([
            'project_name' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'goals' => ['required', 'string'],
            'target_budget' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'target_completion_date' => ['required', 'date', 'after_or_equal:start_date'],
            'project_status' => ['required', 'in:created,active,in_progress,completed,archived,cancelled'],
            'actual_completion_date' => ['nullable', 'date'],
        ]);

        $project = Project::where('projectID', $projectID)->firstOrFail();

        $project->fill([
            'project_name' => $validated['project_name'],
            'description' => $validated['description'],
            'goals' => $validated['goals'],
            'target_budget' => $validated['target_budget'],
            'start_date' => $validated['start_date'],
            'target_completion_date' => $validated['target_completion_date'],
            'project_status' => $validated['project_status'],
            'actual_completion_date' => $validated['actual_completion_date'],
            'updated_date' => now(),
        ]);

        if ($project->project_status === 'completed' && empty($project->actual_completion_date)) {
            $project->actual_completion_date = now()->toDateString();
        }

        $project->save();

        return redirect()->route($this->resolveProjectsRoute('show'), $project->projectID);
    }

    public function destroy(int $projectID)
    {
        $project = Project::where('projectID', $projectID)->firstOrFail();
        $project->project_status = 'archived';
        $project->updated_date = now();
        $project->actual_completion_date = $project->actual_completion_date ?? now()->toDateString();
        $project->save();

        return redirect()->route($this->resolveProjectsRoute('index'));
    }
}
