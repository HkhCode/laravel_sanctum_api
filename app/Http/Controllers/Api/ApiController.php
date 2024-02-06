<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    //
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'user registered successfully'
        ]);
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email' , $validated['email'])->first();

        if(!empty($user)) {
            //
            if(Hash::check($validated['password'], $user->password))
            {
                $token = $user->createToken('myToken')->plainTextToken;

                return response()->json([
                    'status' => 200 ,
                    'message' => 'login successful',
                    'token' => $token
                ]);
            }
        }

        return response()->json([
            'status' => 404,
            'message' => 'Wrong Credentials'
        ]);
    }

    public function profile(Request $request)
    {
        $data = auth()->user();
        return response()->json([
            'status' => 200 ,
            'message' => 'profile data' ,
            'profile' => $data
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 200 ,
            'message' => 'logged out successfully'
        ]);
    }
}
