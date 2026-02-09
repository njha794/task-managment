<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMilestoneRequest;
use App\Http\Requests\UpdateMilestoneRequest;
use App\Models\Milestone;
use App\Models\Project;
use App\Services\MilestoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    public function __construct(
        private MilestoneService $milestoneService
    ) {}

    public function create(Project $project): View
    {
        $this->authorize('create', Milestone::class);
        $this->authorize('view', $project);
        return view('milestones.create', compact('project'));
    }

    public function store(StoreMilestoneRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('view', $project);
        $milestone = $this->milestoneService->create($project, $request->validated());
        return redirect()->route('projects.show', $project)
            ->with('success', 'Milestone created successfully.');
    }

    public function edit(Milestone $milestone): View
    {
        $this->authorize('update', $milestone);
        $milestone->load('project');
        return view('milestones.edit', compact('milestone'));
    }

    public function update(UpdateMilestoneRequest $request, Milestone $milestone): RedirectResponse
    {
        $this->authorize('update', $milestone);
        $this->milestoneService->update($milestone, $request->validated());
        return redirect()->route('projects.show', $milestone->project)
            ->with('success', 'Milestone updated successfully.');
    }

    public function destroy(Milestone $milestone): RedirectResponse
    {
        $this->authorize('delete', $milestone);
        $project = $milestone->project;
        $this->milestoneService->delete($milestone);
        return redirect()->route('projects.show', $project)
            ->with('success', 'Milestone deleted successfully.');
    }
}
