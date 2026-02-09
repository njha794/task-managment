<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_all_projects') || $user->can('create_project');
    }

    public function view(User $user, Project $project): bool
    {
        if ($user->can('view_all_projects')) {
            return true;
        }
        return $project->created_by === $user->id || $project->members->contains($user);
    }

    public function create(User $user): bool
    {
        return $user->can('create_project');
    }

    public function update(User $user, Project $project): bool
    {
        if (! $user->can('edit_project')) {
            return false;
        }
        if ($user->can('view_all_projects')) {
            return true;
        }
        return $project->created_by === $user->id || $project->members->contains($user);
    }

    public function delete(User $user, Project $project): bool
    {
        if (! $user->can('delete_project')) {
            return false;
        }
        if ($user->can('view_all_projects')) {
            return true;
        }
        return $project->created_by === $user->id;
    }
}
