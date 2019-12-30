<?php

namespace App\Http\Controllers;


use App\User;
use App\UserToken;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{

    public function __construct()
    {
        $this->user = new User;
        $this->userToken = new UserToken;
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


    public function index()
    {

        $users = $this->user->with( ['role' => function($q) {
                               $q->select('roles.id', 'role'); 
                            }])
                            ->where('id', '<>', auth()->user()->id)
                            ->get(['users.id', 'email', 'name', 'is_active'])->toArray();

        return $this->respondWithData([
            'data' => $users
        ]);
    }

    public function approveUser($userId)
    {
        if(empty($userId)){
            return $this->responseValidationBadRequest([
                'message' => 'user id is required'
            ]);
        }

        $this->user->update('store_manager_approved', 1)->where('user_id', $userId);

        return $this->respondWithData([
            'message' => 'user is approved'
        ]);

    }

    public function logout()
    {
        $user  = auth()->user();

        $this->userToken->where('user_id', $user->id)->update(['is_active' =>  0]);

        JWTAuth::invalidate(JWTAuth::getToken());

        return $this->respondWithData([
            'message' => 'Logged out successfuly'
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

        $user = auth()->user();
        $userRole = $user->role()->get()->pluck('role')->toArray();

        $this->userToken->create([
            'user_id' => $user->id,
            'token' => $token,
            'is_active' => 1
        ]);
     
        $data = [
            'username' => $user->name,
            'token' => $token,
            'is_approved' => $user->store_manager_approved,
            'roles' => $userRole
        ];

        return $this->respondWithData([
            'data' => $data 
        ]);
    }


}
