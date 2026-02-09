<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignProjectMembersRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $projectService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Project::with(['creator', 'milestones']);

        if (! $user->can('view_all_projects')) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhereHas('members', fn ($m) => $m->where('user_id', $user->id));
            });
        }

        $projects = $query->latest()->paginate(10);

        if ($user->hasRole('Super Admin')) {
            return view('projects.index-admin', compact('projects'));
        }

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $this->projectService->create($request->validated(), $request->user());
        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Request $request, Project $project): View|RedirectResponse
    {
        $this->authorize('view', $project);
        $project->load(['milestones.tasks.assignee', 'creator', 'members']);

        if ($request->user()->hasRole('Super Admin')) {
            return view('projects.show-admin', compact('project'));
        }

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);
        $this->projectService->update($project, $request->validated());
        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        $this->projectService->delete($project);
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function members(Project $project): View
    {
        $this->authorize('update', $project);
        $project->load('members');
        $users = User::with('roles')->orderBy('name')->get();
        return view('projects.members', compact('project', 'users'));
    }

    public function assignMembers(AssignProjectMembersRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);
        $sync = [];
        foreach ($request->input('members', []) as $item) {
            if (! empty($item['user_id'])) {
                $sync[(int) $item['user_id']] = $item['role'] ?? null;
            }
        }
        $this->projectService->assignMembers($project, $sync);
        return redirect()->route('projects.show', $project)
            ->with('success', 'Project members updated.');
    }
}
