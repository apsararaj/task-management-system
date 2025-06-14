<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends Notification {
    use Queueable;

    public function __construct(public Task $task) {}

    public function via($notifiable): array {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage {
        return (new MailMessage)
            ->subject('New Task Assigned')
            ->line("Task: {$this->task->title}")
            ->line("Description: {$this->task->description}")
            ->line("Due Date: {$this->task->due_date}");
    }
}
