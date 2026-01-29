<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class WelfareFunds extends Model
{
    
    protected $table = "welfare_funds";
    public $timestamps = false;

    // Thông tin tổng tiền quỹ phúc lợi
	public static function infoTotalPriceWelfareFunds($month_check,$year_check){
	    return DB::table('personnel_income')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->select('personnel_salary.welfare_fund')
				->where('personnel_income.month', '>', $month_check)
				->where('personnel_income.year', '>=', $year_check)
				->sum('personnel_salary.welfare_fund');
	}

    // Thông tin tổng tiền quỹ phúc lợi đã chi
	public static function infoSpendMoneyWelfareFunds($valid_from='', $valid_to=''){
	    return DB::table('welfare_funds')
	                ->where(function ($query) use ($valid_from,$valid_to) {
	                	if( !empty($valid_from) && !empty($valid_to) ){
	                    	$query->whereBetween('apply_from', [$valid_from, $valid_to]);
	                	}
	                })
				    ->where('status',1)
				    ->whereNotIn('id', [0])
				    ->sum('value');
	}
}
