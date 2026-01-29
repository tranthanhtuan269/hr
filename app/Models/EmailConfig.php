<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class EmailConfig
{
    public static function insertEmailConfig($arr){
        return DB::table('email_config')->insert($arr);
    }

    public static function getInfoEmailConfig($type){
        return DB::table('email_config')
                 ->where('type','=',$type)->first();
    }

    public static function getInfoEmailConfigbyId($id){
        return DB::table('email_config')
                 ->where('id','=',$id)->first();
    }

    public static function getListEmailbyidPersonnel($personnel_id){
        return $data =  DB::table('personnel')
                ->select('email','id')
                ->whereIn('id',$personnel_id)->get();
    }

    public static function getSettingEmail( $request ){
        return DB::table('email_config')
                ->select('id','title','description')
                ->where(function ($query) use ($request) {
                     if (!empty($request->title)) {
                        $query->where('title','like','%'.$request->title.'%');
                     }
                })
                ->paginate(10);
    }


    public static function getSettingSalaryBasic( $request ){
        return DB::table('setting_salary_basic')
                ->where('status',1)
                ->where(function ($query) use ($request) {
                     if (!empty($request->title)) {
                        $query->where('title','like','%'.$request->title.'%');
                     }
                })
                ->paginate(10);
    }

    public static function insertSettingSalaryBasic($arr){
        return DB::table('setting_salary_basic')->insert($arr);
    }

    public static function updateSettingSalaryBasic( $arr,$id  ){
        return DB::table('setting_salary_basic')
            ->where('id', '=', $id)
            ->update($arr);
    }

    public static function getSettingTax( $request ){
        return DB::table('setting_tax')
                ->where('status',1)
                ->where(function ($query) use ($request) {
                     if (!empty($request->title)) {
                        $query->where('title','like','%'.$request->title.'%');
                     }
                })
                ->paginate(10);
    }

    public static function insertSettingTax($arr){
        return DB::table('setting_tax')->insert($arr);
    }

    public static function updateSettingTax( $arr,$id  ){
        return DB::table('setting_tax')
            ->where('id', '=', $id)
            ->update($arr);
    }

    public static function updateEmailConfig( $arr,$id ){
        return DB::table('email_config')
            ->where('id', '=', $id)
            ->update($arr);
    }

    public static function deleteSettingEmail($id){
        return DB::table('email_config')->where('id', '=', $id)->delete();
    }

    public static function getOthers( $request ){
        return DB::table('setting_others')
                ->where('status',1)
                ->where(function ($query) use ($request) {
                     if (!empty($request->title)) {
                        $query->where('title','like','%'.$request->title.'%');
                     }
                })
                ->paginate(10);
    }

    public static function insertOthersConfig($arr){
        return DB::table('setting_others')->insert($arr);
    }

    public static function getInfoOthersConfigbyId($id){
        return DB::table('setting_others')
                 ->where('id','=',$id)->first();
    }

    public static function updateOthersConfig( $arr,$id ){
        return DB::table('setting_others')
            ->where('id', '=', $id)
            ->update($arr);
    }

}
