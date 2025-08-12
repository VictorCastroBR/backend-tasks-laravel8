<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;

interface UserRepositoryInterface
{
    public function paginate(int $companyId, array $filters, int $perPage = 10): LengthAwarePaginator;
    public function create(array $data): User;
}