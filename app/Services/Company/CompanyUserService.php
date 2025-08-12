<?php

namespace App\Services\Company;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\UserRepositoryInterface;

class CompanyUserService
{
    private $users;

    public function __construct(UserRepositoryInterface $users) {
        $this->users = $users;
    }

    public function list(array $filters, int $perPage = 10)
    {   
        $companyId = Auth::user()->company_id;
        return $this->users->paginate($companyId, $filters, $perPage);
    }

    public function createUserInMyCompany(array $payload): User
    {
        $companyId = Auth::user()->company_id;

        $payload['company_id'] = $companyId;
        $payload['is_admin'] = (bool)($payload['is_admin'] ?? false);

        return $this->users->create($payload);
    }
}