# Laravel 12 Task Management API

A modern Task Management API built with **Laravel 12**, including user authentication with **Sanctum**, task creation, assignment, completion, queued notifications, scheduled task expiry, and performance logging middleware.

---

## Features

- User Authentication with Sanctum
- Task CRUD, Assignment & Completion
- Email notifications when tasks are assigned (queued)
- Expire overdue tasks automatically using Laravel Scheduler
- TaskService for business logic via dependency injection 
- Custom Middleware to log API execution time
- Event & Listener to log task completion

---

## Installation

```bash
git clone https://github.com/apsararaj/task-management-system.git
cd task-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan vendor:publish --tag=sanctum-config
```
## Mail Configuration

In your .env file

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="Task Manager"
```

## Queue Configuration

In your .env file

```bash
QUEUE_CONNECTION=database
```

Then Run

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

## Scheduler Setup (Expire Overdue Tasks)

To Run the scheduler locally

```bash
php artisan schedule:work
```

## Authentication Instructions (for Postman)

1. First, register a user via POST /api/register

2. Log in via POST /api/login

3. Use the returned token for authenticated routes as Bearer

## Auth Routes

| Method | Endpoint      | Description            |
| ------ | ------------- | ---------------------- |
| POST   | /api/register | Register user          |
| POST   | /api/login    | Login & get token      |
| POST   | /api/logout   | Logout (Authenticated) |

## Task Routes (Authenticated)

| Method | Endpoint                 | Description             |
| ------ | ------------------------ | ----------------------- |
| POST   | /api/tasks               | Create task             |
| PUT    | /api/tasks/{id}/assign   | Assign to user          |
| PUT    | /api/tasks/{id}/complete | Mark as completed       |
| GET    | /api/tasks               | List tasks with filters |

Supported Filters for /api/tasks (GET):

- status=pending|completed|expired

- assigned_to={user_id}

## Additional

- Check storage/logs/laravel.log for execution time logs
- Check storage/logs/ for Task Expiry log files




