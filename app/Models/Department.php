<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = "departments";

	public function children() 
	{ 
		return $this->hasMany('App\Models\Department', 'parent_id', 'id'); 
	}
}
