<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends Controller
{
    public function __construct(protected TaskService $service)
    {
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date'
            ]);

            $task = $this->service->create($validated, $request->user());
            return response()->json($task, 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Logout failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function assign(Request $request, $id)
    {
        try {
            $request->validate([
                'assigned_to' => 'required|exists:users,id'
            ]);

            $task = Task::findOrFail($id);
            return response()->json($this->service->assign($task, $request->assigned_to));

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Logout failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function complete($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($this->service->complete($task));
    }

    public function index(Request $request)
    {
        return response()->json($this->service->list($request->only(['status', 'assigned_to'])));
    }
}
