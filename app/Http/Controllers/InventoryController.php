<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {

    }

    public function create(Request $request)
    {
        $user = auth()->user();


        $this->validate($request, [
            'name' =>  'required|max:50',
            'vendor' => 'required',
            'MRP' => 'required',
            'batch_no' => 'required',
            'quantity' => 'required'
        ]);

        //check user role
        dd($user->role);



    }
}
