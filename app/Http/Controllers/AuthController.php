<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller 
{

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);


        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
        ], 201);
    }



    public function login(Request $request){
        $credentials = $request->only('email','password');
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 200);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['data' => $user], 200);
    }
}
