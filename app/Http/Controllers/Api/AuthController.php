<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $result = $this->authService->login($data);

            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Invalid credentials',
                'message' => 'The provided credentials are incorrect.',
            ], 422);
        }
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            $result = $this->authService->register($data);

            return response()->json($result, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Registration failed',
                'message' => 'User with this email already exists.',
            ], 422);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
