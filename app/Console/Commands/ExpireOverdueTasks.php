<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Illuminate\Support\Carbon;

class ExpireOverdueTasks extends Command
{
    protected $signature = 'tasks:expire-overdue';
    protected $description = 'Expire overdue pending tasks';

    public function handle()
    {
        $expired = Task::where('status', 'pending')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now())
            ->update(['status' => 'expired']);

        $this->info("Expired {$expired} tasks.");
    }
}
