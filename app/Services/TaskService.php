<?php

namespace App\Services;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

interface TaskService
{
    public function fetchMyTasks();

    public function fetchMyTask(string $taskId);

    public function createTask(CreateTaskRequest $request);

    public function updateMyTask(UpdateTaskRequest $request, string $taskId);

    public function deleteMyTask(string $taskId);
}