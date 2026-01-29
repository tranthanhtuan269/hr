<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    //
    protected $table = "departments";

	public static function getInfoDepartment($personnel_id,$field){
        return Departments::where([
                        ['status',1],
                        ['manager_id',$personnel_id],
                    ])->value($field);
	}

}
