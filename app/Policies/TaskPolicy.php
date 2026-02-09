<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        $project = $task->milestone->project;
        return $project->created_by === $user->id
            || $project->members->contains($user)
            || $task->assigned_to === $user->id
            || $task->created_by === $user->id
            || $user->can('view_all_projects');
    }

    public function create(User $user): bool
    {
        return $user->can('create_task');
    }

    public function update(User $user, Task $task): bool
    {
        if (! $user->can('edit_task')) {
            return false;
        }
        $project = $task->milestone->project;
        return $project->created_by === $user->id
            || $project->members->contains($user)
            || $task->assigned_to === $user->id
            || $task->created_by === $user->id
            || $user->can('view_all_projects');
    }

    public function updateStatus(User $user, Task $task): bool
    {
        if (! $user->can('update_task_status')) {
            return false;
        }
        return $task->assigned_to === $user->id
            || $task->created_by === $user->id
            || $task->milestone->project->created_by === $user->id
            || $task->milestone->project->members->contains($user)
            || $user->can('view_all_projects');
    }

    public function delete(User $user, Task $task): bool
    {
        if (! $user->can('delete_task')) {
            return false;
        }
        $project = $task->milestone->project;
        return $project->created_by === $user->id
            || $project->members->contains($user)
            || $user->can('view_all_projects');
    }
}
