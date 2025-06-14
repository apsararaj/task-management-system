<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $user->createToken('user-token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::where('email', $data['email'])->first();
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'error' => 'Invalid credentials',
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('user-token')->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Login failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->user()->currentAccessToken();
            if ($token) {
                $token->delete();
                return response()->json(['message' => 'Logged out successfully'], Response::HTTP_OK);
            }
            return response()->json(['error' => 'No active token found'], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Logout failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
