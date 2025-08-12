<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(int $companyId, array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $q = User::forCompany($companyId);

        if (!empty($filters['search'])) {
            $q->where(function ($w) use ($filters) {
                $w->where('name', 'like', '%'.$filters['search'].'%')
                    ->orWhere('email', 'like', '%'.$filters['search'].'%');
            });
        }

        $q->orderByDesc('id');

        return $q->paginate($perPage);
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
}