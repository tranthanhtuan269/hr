<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BatvHelper;

class CateDevice extends Model
{
    
    protected $table = "category_device";
    public $timestamps = false;
	public static function getDeviceList(){
		$param = BatvHelper::getPagePaging();
	    return Device::where('status', 1)->paginate($param);
	}

	public static function getCateDeviceList(){
        return DB::table(self::$table_cat_device)->where('status',1)->get();
	}

    public static function listCateDevice(){
        return DB::table(self::$table_cat_device)->get();
        
    }

	public static function insertCateDevice($arr){
	  return DB::table(self::$table_cat_device)->insert($arr);
	}   

    // public function device()
    // {
    //     return $this->belongsTo('App\Device');
    // }
}
