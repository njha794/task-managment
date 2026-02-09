<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function create(array $data, User $creator): Project
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['created_by'] = $creator->id;
            return Project::create($data);
        });
    }

    public function update(Project $project, array $data): Project
    {
        $project->update($data);
        return $project->fresh();
    }

    public function delete(Project $project): bool
    {
        return $project->delete();
    }

    /** @param array<int, string|null> $userIdsWithRole userId => role */
    public function assignMembers(Project $project, array $userIdsWithRole = []): void
    {
        $sync = [];
        foreach ($userIdsWithRole as $userId => $role) {
            $sync[$userId] = ['role' => $role];
        }
        $project->members()->sync($sync);
    }
}
