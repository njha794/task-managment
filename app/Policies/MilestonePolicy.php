<?php

namespace App\Policies;

use App\Models\Milestone;
use App\Models\User;

class MilestonePolicy
{
    public function view(User $user, Milestone $milestone): bool
    {
        $project = $milestone->project;
        return $project->created_by === $user->id
            || $project->members->contains($user)
            || $user->can('view_all_projects');
    }

    public function create(User $user): bool
    {
        return $user->can('create_milestone');
    }

    public function update(User $user, Milestone $milestone): bool
    {
        if (! $user->can('edit_milestone')) {
            return false;
        }
        $project = $milestone->project;
        return $project->created_by === $user->id
            || $project->members->contains($user)
            || $user->can('view_all_projects');
    }

    public function delete(User $user, Milestone $milestone): bool
    {
        if (! $user->can('delete_milestone')) {
            return false;
        }
        $project = $milestone->project;
        return $project->created_by === $user->id
            || $project->members->contains($user)
            || $user->can('view_all_projects');
    }
}
