<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Register
    public function register(Request $request)
    {
        $user = $this->authService->register($request->all());

        return response()->json([
            'message' => 'User Registered',
            'data' => $user
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $data = $this->authService->login($request->all());

        if (!$data) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        return response()->json($data);
    }
}