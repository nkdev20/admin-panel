<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'=> 'required',
            'username' => 'required',
            'password' => 'required'
        ]);


        $this->create([
            'name' => $request['name'], 
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
            'store_manager_approved' => 0,
            'is_active' => 1
        ]);

        return $this->respondWithData([
            'message' => 'User created successfully'
        ]);


    }
}
