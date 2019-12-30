<?php

namespace App\Http\Controllers;

use App\Inventory;
use Illuminate\Http\Request;

class InventoryController extends ApiController
{
    public function __construct()
    {
        $this->inventory = new Inventory;
    }

    public function index()
    {
        $inventory = $this->inventory->all(['id', 'name', 'vendor', 'MRP', 'batch_no', 'batch_date', 'quantity'])->toArray();

        return $this->respondWithData([
            'data' => $inventory
        ]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' =>  'required|max:50',
            'vendor' => 'required',
            'MRP' => 'required',
            'batch_no' => 'required',
            'batch_date' => 'required|date_format:Y-m-d H:i:s',
            'quantity' => 'required'
        ]);

        $this->inventory->create([
            'name' => $request['name'],
            'vendor' => $request['vendor'],
            'MRP' => $request['MRP'],
            'batch_no' => $request['batch_no'],
            'batch_date' => $request['batch_date'],
            'quantity' =>  $request['quantity'],
            'is_active' =>  1,
        ]);

        return $this->respondWithData([
            'message' => 'Inventory record created successfully'
        ]);
        

    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' =>  'required|max:50',
            'vendor' => 'required',
            'MRP' => 'required',
            'batch_no' => 'required',
            'batch_date' => 'required|date_format:Y-m-d H:i:s',
            'quantity' => 'required'
        ]);

        $this->inventory->update([
            'name' => $request['name'],
            'vendor' => $request['vendor'],
            'MRP' => $request['MRP'],
            'batch_no' => $request['batch_no'],
            'batch_date' => $request['batch_date'],
            'quantity' =>  $request['quantity'],
            'is_active' =>  1,
        ])->where('id', $id);

        return $this->respondWithData([
            'message' => 'Inventory record updated successfully'
        ]);
    }

    public function delete($id)
    {
        if(empty($id)){
            return $this->responseBadRequest([
                'message' => 'Inventory id required'
            ]);
        }

        $this->update(['is_active', 0])->where('id', $id);

        return $this->respondWithData([
            'message' => 'Record deleted succesfully'
        ]);
    }
}
