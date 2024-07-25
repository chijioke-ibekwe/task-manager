<?php

namespace App\Services\Implementations;

use App\Auth\src\Users\src\Enums\TaskStatus;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskServiceImpl implements TaskService
{

    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function fetchMyTasks(): LengthAwarePaginator
    {
        return QueryBuilder::for(get_class($this->task))
            ->latest()
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('due_date')
            ])
            ->where('user_id', auth()->user()->id)
            ->paginate(request()->get('per_page', 10))
            ->appends(request()->query());
    }

    public function fetchMyTask(string $taskId): Task
    {
        return $this->task->where('user_id', auth()->user()->id)->where('id', $taskId)->firstOrFail();
    }

    public function createTask(CreateTaskRequest $request): Task
    {
        $validated = $request->validated();

        return $this->task->create(array_merge($validated, [
            'status' => TaskStatus::INCOMPLETE,
            'user_id' => auth()->user()->id
        ]));
    }

    public function updateMyTask(UpdateTaskRequest $request, string $taskId): Task
    {
        $task = $this->fetchMyTask($taskId);
        $validated = $request->validated();

        $task->update($validated);

        return $task->fresh();
    }

    public function markTaskAsComplete(string $taskId): Task
    {
        $task = $this->fetchMyTask($taskId);

        $task->update([
            'status' => TaskStatus::COMPLETE
        ]);

        return $task->fresh();
    }

    public function deleteMyTask(string $taskId): void
    {
        $task = $this->fetchMyTask($taskId);

        $task->delete();
    }
}