<?php

namespace App\Services\Implementations;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;

class TaskServiceImpl implements TaskService
{

    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function fetchMyTasks()
    {

    }

    public function fetchMyTask(string $taskId)
    {

    }

    public function createTask(CreateTaskRequest $request)
    {

    }

    public function updateMyTask(UpdateTaskRequest $request, string $taskId)
    {

    }

    public function markTaskAsCompleted(string $taskId)
    {

    }

    public function deleteMyTask(string $taskId)
    {

    }
}