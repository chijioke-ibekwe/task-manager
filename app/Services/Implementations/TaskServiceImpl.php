<?php

namespace App\Services\Implementations;

use App\Models\Task;
use App\Services\TaskService;

class TaskServiceImpl implements TaskService
{

    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}