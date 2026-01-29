<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class AdhocSalaryAssessment extends Model
{
    
    protected $table = "adhoc_salary_assessment";
    public $timestamps = false;

    public static function listPersonnelSalaryAssessmentUnexpected($turns,$year){
        return  AdhocSalaryAssessment::whereDate('time_send_mail', '>', '0000-00-00')->where('turns',$turns)->where('year',$year)->lists('personnel_id')->toArray();
    }

    public static function listPersonnelSalaryRemove($turns, $year)
    {
        return  AdhocSalaryAssessment::where('turns', $turns)->where('year', $year)->where('disable', 1)->lists('personnel_id')->toArray();
    }
}
