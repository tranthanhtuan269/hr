<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BatvHelper;

class Device extends Model
{
    
    protected $table = "device";
    public $timestamps = false;
    private static $table_cat_device = 'category_device';
    private static $table_device = 'device';
    private static $table_device_personnel = 'device_personnel';

	public static function getDeviceList($request,$ids=array(),$personnel_id=''){
		$param = BatvHelper::getPagePaging();
	    return  Device::leftJoin(self::$table_cat_device,self::$table_cat_device.'.id', '=', self::$table_device.'.c_id')	
	    		->select(self::$table_device.'.*',self::$table_cat_device.'.title as c_title')
	    		->where(self::$table_device.'.status',1)
	            ->where(function ($query) use ($request){
	                if ($request->text_search != '') {
	                    $query->where(self::$table_device.'.description', 'LIKE', '%'.trim($request->text_search).'%');
	                }
	            })
	            ->where(function ($query) use ($ids){
	                if ( !empty($ids) ) {
	                    $query->whereIn(self::$table_device.'.c_id',$ids );
	                }            
	            })
	            ->paginate($param);
	}

	public static function getTakeDevice($request,$ids=array(),$personnel_id=''){
		$param = BatvHelper::getPagePaging();
	    return  Device::leftJoin(self::$table_cat_device,self::$table_cat_device.'.id', '=', self::$table_device.'.c_id')
	    		->leftJoin(self::$table_device_personnel,self::$table_device_personnel.'.device_id', '=', self::$table_device.'.id')	
	    		->select(self::$table_device.'.*',self::$table_cat_device.'.title as c_title',self::$table_device_personnel.'.personnel_id',self::$table_device_personnel.'.number',self::$table_device_personnel.'.id as id_device_personnel',self::$table_device_personnel.'.date_in as tdp_dateIn',self::$table_device_personnel.'.options as tdp_options')
	    		->where(self::$table_device.'.status',1)
	    		->where(self::$table_device_personnel.'.status',1)
	            ->where(function ($query) use ($request){
	                if ($request->text_search != '') {
	                    $query->where(self::$table_device.'.description', 'LIKE', '%'.trim($request->text_search).'%');
	                }
	                if ($request->fillter_date != '') {
						$date_in = BatvHelper::formatDate($request->fillter_date,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
	                    $query->where(self::$table_device_personnel.'.date_in', $date_in);
	                }
	            })
	            ->where(function ($query) use ($ids){
	                if ( !empty($ids) ) {
	                    $query->whereIn(self::$table_device.'.c_id',$ids );
	                }            
	            })
	            ->where(function ($query) use ($personnel_id){
	                if ( !empty($personnel_id) ) {
	                    $query->where(self::$table_device_personnel.'.personnel_id',$personnel_id );
	                }            
	            })
	            ->paginate($param);
	}
	public static function getDeviceByID($id){
	    return  Device::leftJoin(self::$table_cat_device,self::$table_cat_device.'.id', '=', self::$table_device.'.c_id')	
	    		->select(self::$table_device.'.*',self::$table_cat_device.'.title as c_title')
	            ->where(self::$table_device.'.id',$id)
	            ->first();
	}
	public static function getCateDeviceList(){
        return DB::table(self::$table_cat_device)->where('status',1)->get();
	}

    public static function listCateDevice(){
        return DB::table(self::$table_cat_device)->where('status',1)->get();
        
    }

	public static function insertCateDevice($arr){
	  return DB::table(self::$table_cat_device)->insert($arr);
	}   

	public static function insertDevicePersonnel($arr){
	  return DB::table(self::$table_device_personnel)->insert($arr);
	}  

	public static function getDevicePersonnelByID($id){
	    return  DB::table(self::$table_device_personnel)->where('id',$id)
	            ->first();
	}

    public static function updateDevicePersonnel($arr,$id){
        return DB::table(self::$table_device_personnel)
            ->where('id', $id)
            ->update($arr);

    }

    public static function numberDevicePersonnelCurrent($device_id,$not_id=''){
        return DB::table(self::$table_device_personnel)
            ->where('device_id', $device_id)
            ->where('status', 1)
            ->where(function ($query) use ($not_id){
                if ( !empty($not_id) ) {
                    $query->whereNotIn('id',$not_id );
                }            
            })
            ->sum('number');
    }

    public static function numberDeviceCurrent($id){
        return DB::table(self::$table_device)
            ->where('id', $id)
            ->value('number');

    }
}
