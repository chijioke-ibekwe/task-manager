<?php

namespace App\Services;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskService
{
    public function fetchMyTasks(): LengthAwarePaginator;

    public function fetchMyTask(string $taskId): Task;

    public function createTask(CreateTaskRequest $request): Task;

    public function updateMyTask(UpdateTaskRequest $request, string $taskId): Task;

    public function markTaskAsComplete(string $taskId): Task;

    public function deleteMyTask(string $taskId): void;
}