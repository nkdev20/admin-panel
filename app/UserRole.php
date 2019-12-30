<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    
    protected $guarded = [];
    protected $hidden = ['pivot'];


    public function role()
    {
        $this->belongsTo('App\Role', 'role_id');
    }

}
