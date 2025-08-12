<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyUserRequest;
use App\Http\Requests\IndexCompanyUserRequest;
use App\Services\Company\CompanyUserService;
use App\Http\Resources\UserCompanyResource;
use App\Models\User;

class CompanyUserController extends Controller
{   
    protected $service;

    public function __construct(CompanyUserService $service)
    {
        $this->service = $service;
    }

    public function index(IndexCompanyUserRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $perPage = (int)($request->input('per_page', 10));
        $members = $this->service->list($request->validated(), $perPage);

        return UserCompanyResource::collection($members);
    }

    public function store(StoreCompanyUserRequest $request) 
    {
        $user = $this->service->createUserInMyCompany($request->validated());

        return response()->json([
            'message' => 'User created successfully in your company',
            'user' => $user->load('company')
        ], 201);
    }
}
