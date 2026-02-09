<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Milestone;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function create(Milestone $milestone): View
    {
        $this->authorize('create', Task::class);
        $this->authorize('view', $milestone);
        $milestone->load('project');
        $users = \App\Models\User::with('roles')->orderBy('name')->get();
        return view('tasks.create', compact('milestone', 'users'));
    }

    public function store(StoreTaskRequest $request, Milestone $milestone): RedirectResponse
    {
        $this->authorize('view', $milestone);
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $task = $this->taskService->create($milestone, $data);
        return redirect()->route('projects.show', $milestone->project)
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);
        $task->load(['milestone.project', 'assignee', 'creator']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);
        $task->load('milestone.project');
        $users = \App\Models\User::with('roles')->orderBy('name')->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        $this->taskService->update($task, $request->validated());
        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $project = $task->milestone->project;
        $this->taskService->delete($task);
        return redirect()->route('projects.show', $project)
            ->with('success', 'Task deleted successfully.');
    }

    public function status(UpdateTaskStatusRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('updateStatus', $task);
        $this->taskService->updateStatus($task, $request->validated('status'));
        $redirect = $request->input('redirect', route('tasks.show', $task));
        return redirect()->to($redirect)
            ->with('success', 'Task status updated.');
    }
}
