<?php 
namespace App\Jobs;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskAssignedEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Task $task) {}

    public function handle(): void {
        if ($this->task->assignee) {
            $this->task->assignee->notify(new TaskAssignedNotification($this->task));
        }
    }
}
