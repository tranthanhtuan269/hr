<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class MaternityLeave extends Model
{
    
    protected $table = "maternity_leave";
    public $timestamps = false;

    public static function getLastMaternityLeave($personnel_id){
        return MaternityLeave::where('personnel_id', $personnel_id)->orderBy('id', 'desc')->first();
    }

}
