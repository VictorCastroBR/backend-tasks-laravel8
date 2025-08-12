<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    protected function sameCompany(User $auth, User $target): bool
    {
        return (int) $auth->company_id === (int) $target->company_id;
    }

    public function view(User $auth, User $target)
    {
        return $auth->id === $target->id
            || ($auth->is_admin && $this->sameCompany($auth, $target));
    }

    public function update(User $auth, User $target): bool
    {
        return $auth->id === $target->id
            || ($auth->is_admin && $this->sameCompany($auth, $target));
    }

    public function viewAny(User $authUser): bool
    {
        return (bool) $authUser->is_admin;
    }
}
