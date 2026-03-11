<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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


        // $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            // 'access_token' => $token,
            'token_type' => 'bearer',
        ], 201);
    }



    public function login(Request $request){
        $credentials = $request->only('email','password');
        if(!$token = auth('api')->attempt($credentials)){
            return response()->json(['error' => 'not a valid credential']);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expire_at' => auth('api')->factory()->getTTL()*60
            ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie'],201);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
