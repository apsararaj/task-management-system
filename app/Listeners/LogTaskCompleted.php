<?php
namespace App\Listeners;

use App\Events\TaskCompleted;
use Illuminate\Support\Facades\Log;

class LogTaskCompleted
{
    public function handle(TaskCompleted $event): void
    {
        $task = $event->task;
        Log::channel('tasks')->info("Task completed: [ID: {$task->id}] '{$task->title}' by user_id: {$task->assigned_to}");
    }
}
