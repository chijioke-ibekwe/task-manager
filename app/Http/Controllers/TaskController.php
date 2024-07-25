<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Fetch all tasks belonging to me.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $response = $this->taskService->fetchMyTasks();

        return $this->response(null, $response);
    }

    /**
     * Fetch a single task belonging to me.
     *
     * @return JsonResponse
     */
    public function show(string $taskId): JsonResponse
    {
        try {
            $response = $this->taskService->fetchMyTask($taskId);

            return $this->response(null, $response);
        } catch (ModelNotFoundException $e) {
            return $this->response('Task not found', 404);
        }
    }

    /**
     * Create a task.
     *
     * @return JsonResponse
     */
    public function store(CreateTaskRequest $request): JsonResponse
    {
        $response = $this->taskService->createTask($request);

        return $this->response('Task created successfully', $response, 201);
    }

    /**
     * Update a task belonging to me.
     *
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, string $taskId): JsonResponse
    {
        try {
            $response = $this->taskService->updateMyTask($request, $taskId);

            return $this->response('Task updated successfully', $response);
        } catch (ModelNotFoundException $e) {
            return $this->response('Task not found', 404);
        }
    }

    /**
     * Mark a task belonging to me as complete.
     *
     * @return JsonResponse
     */
    public function complete(string $taskId): JsonResponse
    {
        try {
            $response = $this->taskService->markTaskAsComplete($taskId);

            return $this->response('Task completed successfully', $response);
        } catch (ModelNotFoundException $e) {
            return $this->response('Task not found', 404);
        }
    }

    /**
     * Delete a task belonging to me.
     *
     * @return JsonResponse
     */
    public function destroy(string $taskId): JsonResponse
    {
        try {
            $this->taskService->deleteMyTask($taskId);

            return $this->response('Task deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->response('Task not found', 404);
        }
    }
}
