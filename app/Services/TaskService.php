<?php

namespace App\Services;

use App\Models\Task;
use App\Jobs\SendTaskAssignedEmail;
use App\Models\User;
use App\Events\TaskCompleted;

class TaskService
{
    public function create(array $data, User $user): Task
    {
        $data['created_by'] = $user->id;
        return Task::create($data);
    }

    public function assign(Task $task, int $userId): Task
    {
        $task->update(['assigned_to' => $userId, 'status' => 'pending']);
        SendTaskAssignedEmail::dispatch($task);
        return $task;
    }

    public function complete(Task $task): Task
    {
        $task->update(['status' => 'completed']);

        TaskCompleted::dispatch($task);

        return $task;
    }

    public function list(array $filters)
    {
        return Task::with('assignee', 'creator')
            ->when($filters['status'] ?? null, fn($q, $s) => $q->where('status', $s))
            ->when($filters['assigned_to'] ?? null, fn($q, $u) => $q->where('assigned_to', $u))
            ->get();
    }
}
