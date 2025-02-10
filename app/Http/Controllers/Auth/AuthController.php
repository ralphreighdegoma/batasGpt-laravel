<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createToken(
                'token-name',
                ['*'],
                now()->addWeek()
            )->plainTextToken;


            //send email verification
            $user->sendEmailVerificationNotification();

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //check if user is verfied
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !$user->email_verified_at) {
            return response()->json([
                'status' => false,
                'message' => 'User not verified, check your email for verification.',
            ], 401);
        }

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = $request->user();
        $avatar = $request->file('avatar');

        $path = $avatar->store('avatars', 'public');
        //add storage url to path
        $path = asset('storage/' . $path);
        $user->avatar = $path;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'avatar' => $path
            ]
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'bio' => 'required|string|max:255',
            ]);


            $user = $request->user();
            $user->name = $request->name;
            $user->title = $request->title;
            $user->bio = $request->bio;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
