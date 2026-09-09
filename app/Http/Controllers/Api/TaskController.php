<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskManager $taskManager
    ) {}

    public function index(Request $request, int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $completed = $request->has('completed')
            ? filter_var($request->completed, FILTER_VALIDATE_BOOLEAN)
            : null;

        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');

        $tasks = $this->taskManager->listUserTasks(
            $user,
            $completed,
            $orderBy,
            $orderDirection
        );

        return response()->json([
            'success' => true,
            'data' => $tasks,
        ]);
    }

    public function store(StoreTaskRequest $request, int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $task = $this->taskManager->createTask(
            $user,
            $request->title,
            $request->description ?? ''
        );

        return response()->json([
            'success' => true,
            'message' => 'Tarea creada exitosamente.',
            'data' => $task,
        ], 201);
    }

    public function update(UpdateTaskRequest $request, int $userId, Task $task): JsonResponse
    {
        $task = $this->taskManager->updateTask($task, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada exitosamente.',
            'data' => $task,
        ]);
    }

    public function complete(int $userId, Task $task): JsonResponse
    {
        $task = $this->taskManager->completeTask($task);

        return response()->json([
            'success' => true,
            'message' => 'Tarea marcada como completada.',
            'data' => $task,
        ]);
    }

    public function destroy(int $userId, Task $task): JsonResponse
    {
        $this->taskManager->deleteTask($task);

        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada exitosamente.',
        ]);
    }
}