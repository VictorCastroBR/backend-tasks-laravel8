<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class TaskRepository implements TaskRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $q = Task::query()->with('user:id,name');

        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        if (!empty($filter['priority'])) {
            $q->where('priority', $filters['priority']);
        }

        if (!empty($filters['search'])) {
            $q->where(function ($w) use ($filters) {
                $w->where('title', 'like', '%'.$filters['search'].'%')
                    ->orWhere('description', 'like', '%'.$filters['search'].'%');
            });
        }

        $q->orderByDesc('id');

        return $q->paginate($perPage);
    }

    public function find(int $id): Task
    {
        return Task::with('user:id,name')->find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function baseQueryForExport(array $filters, int $companyId): Builder
    {
        $q = Task::query()
        ->where('company_id', $companyId) // <-- força o tenant aqui
        ->with('user:id,name');

        if (!empty($filters['status']))   $q->where('status', $filters['status']);
        if (!empty($filters['priority'])) $q->where('priority', $filters['priority']);
        if (!empty($filters['search'])) {
            $q->where(function ($w) use ($filters) {
                $w->where('title','like','%'.$filters['search'].'%')
                ->orWhere('description','like','%'.$filters['search'].'%');
            });
        }

        return $q->orderBy('id');
    }
}