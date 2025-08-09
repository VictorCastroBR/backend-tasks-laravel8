<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Services\Task\TaskService;
use App\Http\Resources\TaskResource;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Requests\Task\TaskStoreRequest;


class TaskController extends Controller
{
    protected $service;

    public function __construct(TaskService $service)
    {
        $this->middleware('auth:api');
        $this->service = $service;
    }

    public function index(TaskIndexRequest $request)
    {
        $perPage = (int)($request->input('per_page', 10));
        $data = $this->service->list($request->validated(), $perPage);
    
        return TaskResource::collection($data);
    }

    public function store(TaskStoreRequest $request)
    {
        $task = $this->service->create($request->validated());
        return (new TaskResource($task->load('user')))
            ->response()->setStatusCode(201);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($task->load('user'));
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $this->service->update($task, $request->validated());
        return new TaskResource($task->load('user'));
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $this->service->delete($task);
        return response()->json(['message' => 'Task deleted']);
    }

    public function complete(Task $task)
    {
        $this->authorize('complete', $task);
        $task = $this->service->complete($task);
        return new TaskResource($task->load('user'));
    }
}
