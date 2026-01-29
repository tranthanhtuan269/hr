<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class SettingAbsentAttendance extends Model
{
    
    protected $table = "setting_absent_attendance";
    public $timestamps = false;

    public static function listAbsentAttendance(){
        return  SettingAbsentAttendance::selectRaw("GROUP_CONCAT(apply_from) as apply_from,GROUP_CONCAT(apply_to) as apply_to,personnel_id,id")->groupBy('personnel_id')->get();

    }

}
