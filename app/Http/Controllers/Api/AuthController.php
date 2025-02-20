<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user
        ], 201);
    }

    // Inicio de sesión
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken', ['*'], now()->addMinutes(60))->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    // Cierre de sesión
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    // Obtener usuario autenticado
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function checkTokenValidity(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Token inválido o expirado'], 401);
        }

        // Obtener la fecha de expiración del token desde el campo "expires_at" (ajusta según tu implementación)
        $tokenExpiration = $user->tokens()->first()->expires_at ?? null;

        if ($tokenExpiration && now()->greaterThan($tokenExpiration)) {
            return response()->json(['message' => 'Token expirado'], 401);
        }

        return response()->json(['message' => 'Token válido'], 200);
    }
}
