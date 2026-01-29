<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BatvHelper;
class SettingCurrency extends Model
{
    protected $table = "setting_currency";
    public $timestamps = false;

	public static function checkSettingCurrency($id='',$apply_from,$apply_to){
        $result = SettingCurrency::where(function ($query) use ($id){
                    if ($id != '') {
                        $query->whereNotIn('id', [$id]);
                    }     
                })
                ->where('status', 1)
                ->get();
        $tmp = 0;
        if( $result ){
            foreach ($result as $key => $value) {
                if( BatvHelper::handlingTime( $apply_from ) >= BatvHelper::handlingTime( $value->apply_from ) &&   BatvHelper::handlingTime( $apply_from ) <= BatvHelper::handlingTime( $value->apply_to ) ){
                    $tmp++;
                }elseif( BatvHelper::handlingTime( $apply_to ) >= BatvHelper::handlingTime( $value->apply_from ) &&   BatvHelper::handlingTime( $apply_to ) <= BatvHelper::handlingTime( $value->apply_to )  ){
                    $tmp++;
                }elseif ( BatvHelper::handlingTime( $apply_from ) < BatvHelper::handlingTime( $value->apply_from ) && BatvHelper::handlingTime( $apply_to ) > BatvHelper::handlingTime( $value->apply_to ) ) {
                    $tmp++;
                }
            }
        }
        return $tmp;
	}
}
