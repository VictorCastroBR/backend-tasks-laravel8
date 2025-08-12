<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreCompanyUserRequest;
use App\Http\Requests\IndexCompanyUserRequest;
use App\Http\Requests\User\UpdateUserPasswordRequest;
use App\Services\Company\CompanyUserService;
use App\Http\Resources\UserResource;
use App\Models\User;

class CompanyUserController extends Controller
{   
    protected $service;

    public function __construct(CompanyUserService $service)
    {
        $this->service = $service;
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    public function index(IndexCompanyUserRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $perPage = (int)($request->input('per_page', 10));
        $members = $this->service->list($request->validated(), $perPage);

        return UserResource::collection($members);
    }

    public function store(StoreCompanyUserRequest $request) 
    {
        $user = $this->service->createUserInMyCompany($request->validated());

        return response()->json([
            'message' => 'User created successfully in your company',
            'user' => $user->load('company')
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'is_admin' => $data['is_admin'] ?? false,
        ]);

        return (new UserResource($user))->additional([
            'message' => 'User updated successfully.'
        ]);
    }

    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update([
            'password' => Hash::make($request->validated()['password'])
        ]);

        return response()->json(['message' => 'Password updated successfully.']);
    }
}
