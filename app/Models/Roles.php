<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //
    protected $table = "roles";
    public function user () {
        return $this->hasMany('App\Models\Roles','role_id');
    }

  	public static function listRoles($request=''){
    	return DB::table('roles')->where(function ($query) use ($request) {
													 if (!empty($request->roles_name)) {
									                    $query->where('roles_name','=',$request->roles_name);
													 }
												})
										    	->orderBy('ID', 'ASC')->get();
    }

}
