<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{

    public function __construct()
    {
        $this->user = new User;
    }

    public function register(Request $request)
    {
    
        $this->validate($request, [
            'name'=> 'required',
            'username' => 'required',
            'password' => 'required',
            'email' => 'required|unique:users,email'
        ]);


         $this->user->create([
            'name' => $request['name'], 
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'store_manager_approved' => 0,
            'is_active' => 1
        ]);
    

        return $this->respondWithData([
            'message' => 'User created successfully'
        ]);

    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token
        ]);
    }


}
