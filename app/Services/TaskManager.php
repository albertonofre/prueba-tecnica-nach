<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TaskManager
{
    public function createTask(User $user, string $title, string $description = ''): Task
    {
        return DB::transaction(function () use ($user, $title, $description) {
            return $user->tasks()->create([
                'title' => $title,
                'description' => $description,
                'completed' => false,
            ]);
        });
    }

    public function completeTask(Task $task): Task
    {
        return DB::transaction(function () use ($task) {
            $task->completed = true;
            $task->save();
            return $task->fresh();
        });
    }

    public function listUserTasks(
        User $user,
        ?bool $completed = null,
        string $orderBy = 'created_at',
        string $orderDirection = 'desc'
    ): Collection {
        $query = $user->tasks();

        if ($completed !== null) {
            $query->where('completed', $completed);
        }

        $allowedOrderFields = ['title', 'created_at', 'updated_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($orderBy, $allowedOrderFields)) {
            $orderBy = 'created_at';
        }

        if (!in_array(strtolower($orderDirection), $allowedDirections)) {
            $orderDirection = 'desc';
        }

        return $query->orderBy($orderBy, $orderDirection)->get();
    }

    public function deleteTask(Task $task): bool
    {
        return DB::transaction(function () use ($task) {
            return $task->delete();
        });
    }

    public function updateTask(Task $task, array $data): Task
    {
        return DB::transaction(function () use ($task, $data) {
            $task->update($data);
            return $task->fresh();
        });
    }
}