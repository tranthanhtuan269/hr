<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function roles () {
        return $this->belongsTo('App\Models\Roles','role_id');
    }

    public static function  getUserList( $request ){
       return DB::table('users')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->selectRaw("users.name,users.email,users.id,users.status,GROUP_CONCAT(roles.roles_name SEPARATOR '\n') as roles_name")
            ->where('users.status',1)
            ->where(function ($query) use ($request){
                if ($request->name != '') {
                    $query->where('users.name', 'like', '%' .trim( $request->name ). '%');
                }     
                if ($request->email != '') {
                    $query->where('users.email', 'like', '%' .trim( $request->email ). '%');
                }     
            })
            ->groupBy('users.id')
            ->orderBy('users.id', 'desc')
            ->paginate(10);
   }

    public static function updateUser($arr,$id){
        return DB::table('users')
                ->where('id', '=', $id)
                ->update($arr);
    }

    public static function getNewsList($arr,$id){
        return DB::table('news')->get();
    }

    public static function check_User($id){
        return DB::table('users')->select('id')->where('id', '=', $id)->first();
   }
   // Laays ra quan tri vien ( tru Roles la Nhan vien )
    public static function getListManager(){
        return DB::table('users')->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')->selectRaw("users.name,users.id,role_user.role_id")->where('role_user.role_id', '!=', 2)->groupBy('users.id')->get();
    }

    public static function  detailRoleUser( $user_id ){
       return DB::table('users')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->selectRaw("users.*,GROUP_CONCAT(role_user.role_id) as roles_id")
            ->where('users.status',1)
            ->where('users.id',$user_id)
            ->groupBy('users.id')
            ->first();
   }

}
