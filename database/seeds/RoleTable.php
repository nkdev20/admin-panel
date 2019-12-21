<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 1,
            'role' => 'Store Manager',
            'is_active' => 1 
        ]);
        Role::create([
            'role' => 'Store Assistant',
            'is_active' => 1 
        ]);
    }
}
