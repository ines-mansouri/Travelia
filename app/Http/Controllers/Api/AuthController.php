<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
            'nationality' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'role' => 'employee',
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully.',
            'data' => [
                'user' => $user->only('id', 'name', 'email', 'role'),
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($validated['device_name'] ?? 'mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
            'data' => [
                'user' => $user->only('id', 'name', 'email', 'role', 'avatar_path'),
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function tokens(Request $request)
    {
        $tokens = $request->user()->tokens()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $tokens->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'last_used_at' => $t->last_used_at?->diffForHumans(),
                'created_at' => $t->created_at?->toIso8601String(),
            ]),
        ]);
    }

    public function destroyToken(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revoked.',
        ]);
    }
}
