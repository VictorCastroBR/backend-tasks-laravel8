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

    protected function sameCompany(User $u, Task $t): bool
    {
        return (int) $u->company_id === (int) $t->company_id;
    }

    public function viewAny(User $u): bool
    {
        return !is_null($u->company_id);
    }

    public function create(User $u): bool
    {
        return !is_null($u->company_id);
    }

     public function view(User $u, Task $t): bool
    {
        return $this->sameCompany($u, $t);
    }
    
    public function update(User $u, Task $t): bool
    {
        return $this->sameCompany($u, $t);
    }

    public function delete(User $u, Task $t): bool
    {
        return $this->sameCompany($u, $t);
    }

    public function complete(User $u, Task $t): bool
    {
        return $this->sameCompany($u, $t);
    }
}
