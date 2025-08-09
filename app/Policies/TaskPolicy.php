<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Task $task): bool
    {
        return $user->company_id === $task->company_id;
    }
    public function update(User $user, Task $task): bool
    {
        return $user->company_id === $task->comapny_id;
    }
    public function delete(User $user, Task $task): bool
    {
        return $user->company_id === $task->comapny_id;
    }
    public function complete(User $user, Task $task): bool
    {
        return $user->company_id === $task->company_id;
    }
}
