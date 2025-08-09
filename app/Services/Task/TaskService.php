<?php

namespace App\Services\Task;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Carbon\Carbon;
use App\Events\TaskCompleted;
use App\Events\TaskCreated;

class TaskService
{
    protected $tasks;

    public function __construct(TaskRepositoryInterface $tasks)
    {
        $this->tasks = $tasks;
    }

    public function list(array $filters, int $perPage = 10)
    {
        return $this->tasks->paginate($filters, $perPage);
    }

    public function create(array $data): Task
    {
        $user = Auth::user();
        $data['company_id'] = $user->company_id;
        $data['user_id'] = $user->id;

        $task = $this->tasks->create($data);

        event(new TaskCreated($task));

        return $task;
    }

    public function show(int $id): ?Task
    {
        return $this->tasks->find($id);
    }

    public function update(Task $task, array $data): Task
    {
        unset($data['company_id'], $data['user_id']);
        return $this->tasks->update($task, $data);
    }

    public function delete(Task $task): void
    {
        $this->tasks->delete($task);
    }

    public function complete(Task $task): Task
    {
        $payload = [
            'status' => 'done',
            'completed_at' => Carbon::now()
        ];

        $task = $this->update($task, $payload);

        event(new TaskCompleted($task));

        return $task;
    }
}