<?php

namespace App\Services;

use App\Models\Milestone;
use App\Models\Task;

class TaskService
{
    public function create(Milestone $milestone, array $data): Task
    {
        $data['milestone_id'] = $milestone->id;
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    public function updateStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);
        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
