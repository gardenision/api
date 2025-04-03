<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            event(new Registered($user));

            DB::commit();
    
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->away(config('frontend.url'));
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email verification link sent successfully',
        ]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('api')->logout();

        $request->user()->tokens()->delete();
 
        return response()->json([
            'message' => 'Logout successful',
        ]);
    }

}
