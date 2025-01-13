<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use SebastianBergmann\Type\MixedType;

class AuthController extends Controller
{

    /**
     * Register a newly created user in storage.
     */
    public function register(Request $request)
    {
        $user = $request->validate([
            'email' => ['email', 'required'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'name' => ['required', 'max:55', "min:3"]
        ]);

        User::create($user);

        return response()->json(['message' => 'User registered']);
    }

    /**
     * Authenticate user & generate session token
     */
    public function login(Request $request): MixedType|JsonResponse
    {
        $validatedUser = $request->validate([
            'email' => ['email', 'required', 'exists:users,email'],
            'password' => ['required']
        ]);

        $user = User::where('email',  $request->email)->first();
        $user->tokens()->delete();

        if(Auth::attempt($validatedUser)){
            return  response()->json([
                'message' => 'Correct!',
                'name' => $user->name,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ]);
        }
        
        return response()->json([
            'message' => 'not correct!',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User Logged out succesfully'
        ]);
    }
}
