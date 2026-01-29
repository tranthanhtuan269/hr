<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class ExceptionalAttendance extends Model
{
    
    protected $table = "exceptional_attendance";
    public $timestamps = false;
    
    public static function listExceptionalAttendance(){
        return  DB::table('exceptional_attendance')
                ->select('personnel.fullname','exceptional_attendance.id')
                ->leftJoin('personnel', 'personnel.id', '=', 'exceptional_attendance.personnel_id')
                ->where('exceptional_attendance.status','=',1)
                ->get();
     }
}
