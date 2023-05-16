<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ]);




        $username = '';
        $unique = false;

        while (!$unique) {
            $username = $request->lastName . rand(1000, 9999); // Generate a unique username
            $unique = !User::where('username', $username)->exists(); // Check if the username already exists in the database
        }

        $user = new User([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'username' => $username, // Set the generated username
            'password' => Hash::make($request->password),
        ]);

        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;

        // $user->sendEmailVerificationNotification();
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function getUser()
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user,
        ]);
    }

    public function uploadPhoto(Request $request)
    {
        $validatedData = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get the user ID from the authenticated user
        $userId = auth()->user()->id;
        $previusPhoto =  auth()->user()->img;
        // Generate a unique name for the image file
        $imageName = time() . '.' . $request->photo->extension();

        // Store the image file in the public directory
        $path = $request->photo->store('profiles', ['disk' => 'profiles'], $imageName);

        // Update the user's img_url field with the image file path
        $user = User::find($userId);
        $user->img = $path;
        $user->save();
        //remove photo
        if (file_exists(public_path(($previusPhoto)))) {
            unlink(public_path(($previusPhoto)));
            // Storage::delete($previusPhoto);
            return response()->json(['img' => $path]);
        } else {
            return response()->json(['success' => ' not Your profile has been updated successfully.']);
        }
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        $user->fill($request->only([
            'firstName',
            'lastName',
            'username',
            'img',
            'email',
        ]));

        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function new_password(Request $request)
    {
        $user = User::firstWhere("email", $request->email);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'ok']);
    }
}
