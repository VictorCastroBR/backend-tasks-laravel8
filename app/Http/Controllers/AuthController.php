<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:150'],
            'company_slug' => ['required', 'string', 'max:150', 'alpha_dash', 'unique:companies,slug'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string', 'min:6']
        ]);

        $company = Company::create([
            'name' => $data['company_name'],
            'slug' => $data['company_slug']
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $company->id
        ]);
        
        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Registered',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user->load('company')
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);

        if (!$token = auth('api')->attempt($data)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Logged in',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()->load('company')
        ]);
    }

    public function me()
    {
        return response()->json(auth('api')->user()->load('company'));
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logged out']);
    }
}