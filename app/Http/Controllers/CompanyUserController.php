<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyUserRequest;
use App\Services\Company\CompanyUserService;

class CompanyUserController extends Controller
{   
    protected $service;

    public function __construct(CompanyUserService $service)
    {
        $this->service = $service;
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
