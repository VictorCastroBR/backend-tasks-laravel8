<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Export;

class ExportPolicy
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

    public function view(User $user, Export $export): bool
    {
        return $user->company_id === $export->company_id;
    }

    public function download(User $user, Export $export): bool
    {
        return $user->company_id === $export->company_id;
    }
}
