<?php

namespace App\Models;
use DB;
use App\Helpers\BatvHelper;
use App\Models\AdhocSalaryAssessment;
class Salary 
{
   
    public static function listDay(){
    	return DB::table('working_day_setting')->get();
    }

    public static function settingParameters(){
    	return DB::table('hrm_setting')->get();
    }

	public static function insertParameters($arr){
	  return DB::table('parameters')->insert($arr);
	}

	public static function checkTitleConfig($table='',$title,$id=''){ 
	    return DB::table($table)
	          ->select('id','title')
	          ->where('title','=',$title)
	          ->where('status','=',1)
	          ->where(function ($query) use ($id) {
	             if (!empty($id)) {
	                        $query->where('id','<>',$id);
	             }
	          })
	          ->first();
	}

	public static function infoParameters($id=''){
	     return DB::table('parameters')
	          ->select('id','title','is_fixed','status','value','description')
	          ->where('status','=',1)
	          ->where(function ($query) use ($id) {
	             if (!empty($id)) {
	                        $query->where('id','=',$id);
	             }
	          })
	          ->orderby('id', 'DESC')
	          ->get();
	}
	public static function listParametersConfig($request=''){
	     return DB::table('parameters')
	          ->select('id','title','is_fixed','status','value','value_org')
	          ->where('status','=',1)
	          ->where(function ($query) use ($request) {
	             if (!empty($request->title)) {
	                        $query->where('title','like','%'.trim($request->title).'%');
	             }
	          })
	          ->paginate(10);
	}

	public static function checkGroupPersonalConfig($title,$id=''){
	    return DB::table('listGroupPersonalConfig')
	          ->select('id','title')
	          ->where('title','=',$title)
	          ->where(function ($query) use ($id) {
	             if (!empty($id)) {
	                        $query->where('id','<>',$id);
	             }
	          })
	          ->first();
	}

	public static function listGroupPersonalConfig($request=''){
	     return DB::table('personnel_groups')
	          ->where('status','=',1)
	          ->where(function ($query) use ($request) {
	             if (!empty($request->title)) {
	                        $query->where('title','like','%'.trim($request->title).'%');
	             }
	          })
	          ->orderBy('id', 'DESC')
	          ->paginate(10);
	}

	public static function listRecipeConfig( $request='' ){
	     return DB::table('income_config')
	          ->where('status','=',1)
	          ->where(function ($query) use ($request) {
	             if (!empty($request->title)) {
	                        $query->where('title','like','%'.trim($request->title).'%');
	             }
	          })
	          ->orderBy('id', 'DESC')
	          ->paginate(10);
	}

	public static function infoGroupPersonal(){
	     return DB::table('personnel_groups')
	          ->select('id','title')
	          ->where('status','=',1)
	          ->get();
	}

	public static function infoRecipeConfig( $id ){
	     return DB::table('income_config')
     		  ->leftJoin('income_config_group', 'income_config_group.income_config_id', '=', 'income_config.id')
	          ->where('id','=',$id)
	          ->first();
	}

	public static function getGroupPersonalConfig($id){
	     return DB::table('personnel_groups')
	     	  ->leftJoin('personnel_group_detail', 'personnel_group_detail.personnel_group_id', '=', 'personnel_groups.id')
	          ->select('personnel_groups.id','personnel_groups.title','personnel_groups.description','personnel_group_detail.type','personnel_group_detail.personnel_id')
	          ->where('personnel_groups.id','=',$id)
	          ->get();
	}

	public static function checkParameters($id){
	    return DB::table('income_config')
	          ->where('value_id','like','%'.$id.'%')
	          ->first();
	}

	public static function updateParameters($arr,$id){
	    return DB::table('parameters')
	            ->where('id', '=', $id)
	            ->update($arr);
	}

	public static function updateRecipeConfig($arr,$id){
	    return DB::table('income_config')
	            ->where('id', '=', $id)
	            ->update($arr);
	}

	public static function updateGroupPersonal($arr,$id){
	    return DB::table('personnel_groups')
	            ->where('id', '=', $id)
	            ->update($arr);
	}

	public static function deleteGroupPersonalDetail($id){
		return DB::table('personnel_group_detail')->where('personnel_group_id', '=', $id)->delete();
	}
	public static function deleteIncomeConfigGroup($id){
		return DB::table('income_config_group')->where('income_config_id', '=', $id)->delete();
	}

	public static function insertGroupPersonal($arr){
	    return DB::table('personnel_groups')->insert($arr);
	}

	public static function insertGroupPersonalDetail($arr){
		return DB::table('personnel_group_detail')->insert($arr);
	}

	public static function insertIncomeConfig($arr){
		return DB::table('income_config')->insert($arr);
	}

	public static function insertPersonnelIncomeOther($arr){
		return DB::table('personnel_income_other')->insert($arr);
	}

	public static function updatePersonnelIncomeOther($arr,$id,$income_key){
	    return DB::table('personnel_income_other')
	            ->where('id', '=', $id)
	            ->where('income_key', '=', $income_key)
	            ->update($arr);
	}

	public static function deletePersonnelIncomeOther($id){
		return DB::table('personnel_income_other')->where('personnel_income_id', '=', $id)->delete();
	}

	public static function infoPersonnelIncomeOther($id){
	     return DB::table('personnel_income_other')
	          ->where('personnel_income_id','=',$id)
	          ->first();
	}

	public static function updateIncomeConfig($arr,$id){
	    return DB::table('income_config')
	            ->where('id', '=', $id)
	            ->update($arr);
	}

	public static function updateIncomeConfigGroup( $arr,$id ){
	    return DB::table('income_config_group')
	            ->where('income_config_id', '=', $id)
	            ->update($arr);
	}

	public static function getPersonnelGroupDetail( $id,$type ){
	     return DB::table('personnel_group_detail')
	          ->where('personnel_id','=',$id)
	          ->where('type','=',$type)
	          ->get();
	}

	public static function getPersonnelGroupDetailMuch( $id,$type ){
	     return DB::table('personnel_group_detail')
	          ->where('personnel_id','=',$id)
	          ->whereIn('type',$type)
	          ->orderby('type','desc')
	          ->get();
	}
	public static function getIncomeConfig($id,$dateCurrent,$type){
	     return DB::table('income_config')
	          ->where('id','=',$id)
	          ->where('status','=',1)
	          ->where('valid_from','<=',$dateCurrent)
	          ->where('valid_to','>=',$dateCurrent)
	          ->whereIn('type',$type)
	          ->get();
	}

	public static function getIncomeConfigSalary($id,$type,$dateCurrent){
	     return DB::table('income_config')
	          ->where('id','=',$id)
	          ->where('status','=',1)
	          ->where('type','=',$type)
	          ->where('valid_from','<=',$dateCurrent)
	          ->where('valid_to','>=',$dateCurrent)
	          ->get();
	}

	public static function getIncomeConfigbyType($type){
	     return DB::table('income_config')
	          ->where('type','=',$type)
	          ->first();
	}

	public static function listSalary($request,$personnel_id=''){
	     return DB::table('personnel')
				->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->select('personnel.fullname','personnel_salary.*','personnel_income.status')
				->where(function ($query) use ($request) {
					if (!empty($request->selectMonth) && !empty($request->selectYear)) {

						$query->where('personnel_income.month', '=', $request->selectMonth);
						$query->where('personnel_income.year', '=', $request->selectYear);
					}else{
						$query->where('personnel_income.month', '=', date('m'));
						$query->where('personnel_income.year', '=', date('Y'));
					}
				})
				->where(function ($query_2) use ($personnel_id) {

					if (!empty($personnel_id)) {
						$query_2->where('personnel.id', '=', $personnel_id);
					}
				})
	           ->get();
	}

	public static function listSalary2($request,$personnel_id=''){
		return DB::table('personnel')
			   ->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
			   ->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
			   ->select('personnel.fullname','personnel_salary.*','personnel_income.status')
			   ->where(function ($query) use ($request) {
				   if (!empty($request->selectMonth) && !empty($request->selectYear)) {

					   $query->where('personnel_income.month', '=', $request->selectMonth);
					   $query->where('personnel_income.year', '=', $request->selectYear);
				   }else{
					   $query->where('personnel_income.month', '=', date('m'));
					   $query->where('personnel_income.year', '=', date('Y'));
				   }
			   })
			   ->where(function ($query_2) use ($personnel_id) {

				   if (!empty($personnel_id)) {
					   $query_2->where('personnel.id', '=', $personnel_id);
				   }
			   })
			  ->get();
   }

	public static function listBonus($request,$personnel_id=''){
	     return DB::table('personnel')
				->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_bonus', 'personnel_bonus.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->select('personnel.fullname','personnel.date_out','personnel_bonus.*','personnel_income.status','personnel_income.status_bonus','personnel_income.ki','personnel_income.ki_seniority','personnel_income.ki_rules','personnel_income.ki_performance','personnel_salary.salary_trial_default','personnel_salary.salary_official_default')
				->where(function ($query) use ($request) {
					if (!empty($request->selectMonth) && !empty($request->selectYear)) {
						$query->where('personnel_income.month', '=', $request->selectMonth);
						$query->where('personnel_income.year', '=', $request->selectYear);
					}else{
						$query->where('personnel_income.month', '=', date('m'));
						$query->where('personnel_income.year', '=', date('Y'));
					}
				})
				->where(function ($query_2) use ($personnel_id) {
					if (!empty($personnel_id)) {
						$query_2->where('personnel.id', '=', $personnel_id);
					}
				})
	           ->get();
	}

	public static function listTaxInsurrance($request,$personnel_id=''){
	     return DB::table('personnel')
				->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_tax_insurance', 'personnel_tax_insurance.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->select('personnel.fullname','personnel.date_out','personnel_tax_insurance.*','personnel_income.status','personnel_salary.salary_trial_default','personnel_salary.salary_official_default')
				->where(function ($query) use ($request) {
					if (!empty($request->selectMonth) && !empty($request->selectYear)) {
						$query->where('personnel_income.month', '=', $request->selectMonth);
						$query->where('personnel_income.year', '=', $request->selectYear);
					}else{
						$query->where('personnel_income.month', '=', date('m'));
						$query->where('personnel_income.year', '=', date('Y'));
					}
				})
				->where(function ($query_2) use ($personnel_id) {
					if (!empty($personnel_id)) {
						$query_2->where('personnel.id', '=', $personnel_id);
					}
				})
	           ->get();
	}

	public static function listSalaryOther($request,$personnel_id=''){
	     return DB::table('personnel')
				->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_income_other', 'personnel_income_other.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->select('personnel.fullname','personnel.date_out','personnel.id as personnel_id','personnel_income_other.*','personnel_income.status','personnel_salary.salary_trial_default','personnel_salary.salary_official_default')
				->where(function ($query) use ($request) {
					if (!empty($request->selectMonth) && !empty($request->selectYear)) {
						$query->where('personnel_income.month', '=', $request->selectMonth);
						$query->where('personnel_income.year', '=', $request->selectYear);
					}else{
						$query->where('personnel_income.month', '=', date('m'));
						$query->where('personnel_income.year', '=', date('Y'));
					}
				})
				->where(function ($query_2) use ($personnel_id) {
					if (!empty($personnel_id)) {
						$query_2->where('personnel.id', '=', $personnel_id);
					}
				})
	           ->get();
	}

	public static function listAllSalary($request,$personnel_id='',$ids=array()){
		if (!empty($request->selectMonth)) {
			$month = $request->selectMonth;
			$year = $request->selectYear;
		} else {
			$month = date('m');
			$year = date('Y');
		}
		
		 return DB::table('personnel')
				->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
				->leftJoin('loan_capital', 'loan_capital.personnel_id', '=', 'personnel.id')
				->leftJoin('history_pay_loan_capital', function($join) use ($month, $year) {
					$join->on('loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
						->where('history_pay_loan_capital.month', '>', 0)
						->where('loan_capital.pay','=', 1)
						->where('history_pay_loan_capital.final_settlement','=', 0)
						->where(\DB::raw('MONTH(history_pay_loan_capital.repayment_period)'),'=', $month)
						->where(\DB::raw('YEAR(history_pay_loan_capital.repayment_period)'),'=', $year);
				})
				->leftJoin('maternity_leave', 'maternity_leave.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_bonus', 'personnel_bonus.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_tax_insurance', 'personnel_tax_insurance.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->selectRaw('maternity_leave.join_insurance,maternity_leave.apply_from,maternity_leave.apply_to,history_pay_loan_capital.loan_capital_id,history_pay_loan_capital.month as history_pay_loan_capital_month,history_pay_loan_capital.principal,history_pay_loan_capital.interest,history_pay_loan_capital.interest_incurred,history_pay_loan_capital.wanting_month_prev_money,history_pay_loan_capital.redundancy_month_prev_money,personnel.fullname,personnel.email,personnel_income.*,personnel_bonus.*,personnel_tax_insurance.*,personnel_salary.*,personnel.date_out')
				->where(function ($query) use ($month, $year) {
					$query->where('personnel_income.month', '=', $month);
					$query->where('personnel_income.year', '=', $year);
				})
	            ->where(function ($query) use ($ids){
	                if ( !empty($ids) ) {
	                    $query->whereIn('personnel.department_id',$ids );
	                }            
	            })
				->where(function ($query_2) use ($personnel_id) {
					if (!empty($personnel_id)) {
						$query_2->where('personnel.id', '=', $personnel_id);
					}
				})
			   ->groupBy('personnel_income.personnel_id')
			   ->get();

	}

	public static function listAllSalaryPayMonthLoanCapital($request){
		if (!empty($request->selectMonth)) {
			$month = $request->selectMonth;
			$year = $request->selectYear;
		} else {
			$month = date('m');
			$year = date('Y');
		}
		
		return DB::table('personnel')
				->leftJoin('loan_capital', 'loan_capital.personnel_id', '=', 'personnel.id')
				->leftJoin('history_pay_loan_capital', 'history_pay_loan_capital.loan_capital_id', '=', 'loan_capital.id')
				->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_bonus', 'personnel_bonus.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_tax_insurance', 'personnel_tax_insurance.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->select('history_pay_loan_capital.loan_capital_id','history_pay_loan_capital.month as history_pay_loan_capital_month','history_pay_loan_capital.principal','history_pay_loan_capital.interest','history_pay_loan_capital.interest_incurred','history_pay_loan_capital.wanting_month_prev_money','history_pay_loan_capital.redundancy_month_prev_money','personnel.fullname','personnel.email','personnel_income.*','personnel_bonus.*','personnel_tax_insurance.*','personnel_salary.*')
				->where(function ($query) use ($month, $year) {
					$query->where('personnel_income.month', '=', $month);
					$query->where('personnel_income.year', '=', $year);
				})
				->where('loan_capital.pay', 1)
				->where('history_pay_loan_capital.month', '>', 0)
				->where('history_pay_loan_capital.status', '=', 0)
				->whereMonth('history_pay_loan_capital.repayment_period', '=', $month)
				->whereYear('history_pay_loan_capital.repayment_period', '=', $year)
			    ->groupBy('personnel_income.personnel_id')
	            ->get();
	}

	public static function listAllSalaryPayMonthLoanCapitalRecalCulation($request){
		if (!empty($request->selectMonth)) {
			$month = $request->selectMonth;
			$year = $request->selectYear;
		} else {
			$month = date('m');
			$year = date('Y');
		}
		
		return DB::table('personnel')
				->leftJoin('loan_capital', 'loan_capital.personnel_id', '=', 'personnel.id')
				->leftJoin('history_pay_loan_capital', 'history_pay_loan_capital.loan_capital_id', '=', 'loan_capital.id')
				->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_bonus', 'personnel_bonus.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_tax_insurance', 'personnel_tax_insurance.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->select('history_pay_loan_capital.loan_capital_id','history_pay_loan_capital.month as history_pay_loan_capital_month','history_pay_loan_capital.principal','history_pay_loan_capital.interest','history_pay_loan_capital.interest_incurred','history_pay_loan_capital.wanting_month_prev_money','history_pay_loan_capital.redundancy_month_prev_money','personnel.fullname','personnel.email','personnel_income.*','personnel_bonus.*','personnel_tax_insurance.*','personnel_salary.*')
				->where(function ($query) use ($month, $year) {
					$query->where('personnel_income.month', '=', $month);
					$query->where('personnel_income.year', '=', $year);
				})
				->where('loan_capital.pay', 1)
				->where('history_pay_loan_capital.month', '>', 0)
				->where('history_pay_loan_capital.status', '=', 1)
				->whereMonth('history_pay_loan_capital.repayment_period', '=', $month)
				->whereYear('history_pay_loan_capital.repayment_period', '=', $year)
			    ->groupBy('personnel_income.personnel_id')
	            ->get();
	}

	public static function listEmailSalary(){
	     return DB::table('personnel')
				->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_bonus', 'personnel_bonus.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_tax_insurance', 'personnel_tax_insurance.personnel_income_id', '=', 'personnel_income.id')
				->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
				->select('personnel.fullname','personnel.email','personnel_income.*','personnel_bonus.*','personnel_tax_insurance.*','personnel_salary.*')
				->where('personnel_income.month', '=', date('m'))
				->where('personnel_income.year', '=', date('Y'))
	           ->get();
	}


	public static function listSalaryIncreaseCriterio( $request='',$ids=array(),$dateCurrent ){
	     return DB::table('personnel')
				->leftJoin('contract_personnel', 'contract_personnel.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_job_ratio', 'personnel_job_ratio.personnel_ID', '=', 'personnel.id')
				->selectRaw('personnel_job_ratio.id, MAX(personnel_job_ratio.apply_from) as date_nlgn,personnel.fullname,personnel.salary_frequency,personnel.id as personnel_id,contract_personnel.apply_from')
				->where('contract_personnel.contract_id', '=', 2)
	            ->where(function ($query) use ($ids){
	                if ( !empty($ids) ) {
	                    $query->whereIn('personnel.department_id',$ids );
	                }            
	            })
	            ->where(function ($query) use ($dateCurrent) {
	                $query->where('personnel.date_out', '=', NULL)
	                      ->orWhere('personnel.date_out', '>', $dateCurrent);
	            })
	            ->where(function ($query) use ($request){
	                if ( !empty($request->salary_frequency) ) {
	                    $query->where('personnel.salary_frequency',$request->salary_frequency );
	                }            
	            })
				->groupBy('personnel_job_ratio.personnel_ID')
				->get();
	}

	public static function listSalaryIncreaseCriterioTL($ids=array(),$apply_from,$year,$turns){
		// Loại bỏ những ông tăng đột xuất. Vì tăng đặt xuất ko có truy lĩnh
		$arr_AdhocSalaryAssessment_Id = AdhocSalaryAssessment::where('type',2)->where('turns',$turns)->where('year',$year)->lists('personnel_id')->toArray();
	    return DB::table('personnel')
				->leftJoin('contract_personnel', 'contract_personnel.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_evaluation', 'personnel_evaluation.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_job_ratio', 'personnel_job_ratio.personnel_ID', '=', 'personnel.id')
				->selectRaw('personnel_job_ratio.id, MAX(personnel_job_ratio.apply_from) as date_nlgn,MAX(personnel_job_ratio.ratio),personnel.fullname,personnel.salary_frequency,personnel.id as personnel_id,contract_personnel.apply_from')
				->whereNotIn('personnel.id',$arr_AdhocSalaryAssessment_Id)
				->where('personnel_job_ratio.status',1)
				->where('personnel_evaluation.date',$year)
				->where('personnel_evaluation.turns',$turns)
				->where('personnel_job_ratio.apply_from',$apply_from)
	            ->where(function ($query) use ($ids){
	                if ( !empty($ids) ) {
	                    $query->whereIn('personnel.department_id',$ids );
	                }            
	            })
				->groupBy('personnel_job_ratio.personnel_ID')
				->get();
	}

	public static function listSalaryIncreaseCriterioTLbyId( $dateCurrent,$personnel_id ){
	    return DB::table('personnel')
				->leftJoin('contract_personnel', 'contract_personnel.personnel_id', '=', 'personnel.id')
				->leftJoin('personnel_job_ratio', 'personnel_job_ratio.personnel_ID', '=', 'personnel.id')
				->selectRaw('personnel_job_ratio.id, MAX(personnel_job_ratio.apply_from) as date_nlgn,personnel.fullname,personnel.salary_frequency,personnel.id as personnel_id,contract_personnel.apply_from')
				->where('contract_personnel.contract_id', '=', 2)
				->where('personnel_job_ratio.status', '=', 1)
				->where('personnel.id', '=', $personnel_id)
	            ->where(function ($query) use ($dateCurrent) {
	                $query->where('personnel.date_out', '=', NULL)
	                      ->orWhere('personnel.date_out', '>', $dateCurrent);
	            })
				->groupBy('personnel_job_ratio.personnel_ID')
				->first();
	}

	//Tính số tháng truy lĩnh
	public static function checkSalaryIncreaseCriterioTL( $personnel_id,$apply_from ){
        $id = \DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->whereRaw('apply_to = ( select max(apply_to) from personnel_job_ratio where personnel_id="'.$personnel_id.'")')
                    ->value('id');
	    return DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->whereNotIn('apply_from', [$apply_from])
                    ->whereRaw('apply_from = ( select max(apply_from) from personnel_job_ratio where personnel_id="'.$personnel_id.'" and apply_from not in ("'.$apply_from.'") )')
                    ->first();
	}
	public static function getIncomeConfigGroup($id){
	     return DB::table('income_config_group')
	          ->where('personnel_group_id','=',$id)
	          ->get();
	}

	public static function insertIncomeConfigGroup($arr){
		return DB::table('income_config_group')->insert($arr);
	}

	public static function  getInfoParametersConfig($id){
    	return DB::table('parameters')
                 ->where('id','=',$id)->first();	
	}

	public static function  insertPersonnelIncome($arr){
     	return DB::table('personnel_income')->insert($arr);	
	}

	public static function updatePersonnelIncome($arr,$id){
	    return DB::table('personnel_income')
	            ->where('id', '=', $id)
	            ->update($arr);
	}

	public static function updateStatusPersonnelIncome($arr,$month,$year){
	    return DB::table('personnel_income')
                ->where([
                    ['month', '=', $month],
                    ['year', '=', $year],
                ])
	            ->update($arr);
	}

	public static function insertPersonnelSalary($arr){
		return DB::table('personnel_salary')->insert($arr);	
	}

	public static function updatePersonnelSalary($arr,$id){

// $arr =['salary_official_work' => 314176.0916667];
// echo "<pre>";
// print_r($check);
// echo "</pre>";die;

	    return DB::table('personnel_salary')
	            ->where('personnel_income_id',$id)
	            ->update($arr);
	}

	public static function insertPersonnelTaxInsurance($arr){
		return DB::table('personnel_tax_insurance')->insert($arr);	
	}

	public static function updatePersonnelTaxInsurance($arr,$id){
	    return DB::table('personnel_tax_insurance')
	            ->where('personnel_income_id', '=', $id)
	            ->update($arr);
	}

	public static function deletePersonnelTaxInsurance($id){
		return DB::table('personnel_tax_insurance')->where('personnel_income_id', '=', $id)->delete();
	}
	
	public static function  checkPersonnelTaxInsurance($id){
    	return DB::table('personnel_tax_insurance')
                 ->where('personnel_income_id','=',$id)->first();	
	}

	public static function insertPersonnelBonus($arr){
		return DB::table('personnel_bonus')->insert($arr);	
	}

	public static function updatePersonnelBonus($arr,$id){
	    return DB::table('personnel_bonus')
	            ->where('personnel_income_id', '=', $id)
	            ->update($arr);
	}
	public static function deletePersonnelBonus($id){
		return DB::table('personnel_bonus')->where('personnel_income_id', '=', $id)->delete();
	}

	public static function  checkPersonnelBonus($id){
    	return DB::table('personnel_bonus')
                 ->where('personnel_income_id','=',$id)->first();	
	}

	public static function  checkPersonnelSalary($id){
    	return DB::table('personnel_salary')
                 ->where('personnel_income_id','=',$id)->first();	
	}

	// public static function insertPersonnelSalary($arr){
	// 	return DB::table('personnel_salary')->insert($arr);	
	// }

	public static function insertLeaveSettingDetail($arr){
		return DB::table('days_leave_setting_detail')->insert($arr);	
	}

	public static function insertLeaveSetting($arr){
		return DB::table('days_leave_setting')->insert($arr);	
	}

	public static function updateLeaveSetting( $arr,$id ){
		return DB::table('days_leave_setting')->where('id', '=', $id)->update($arr);	
	}

	public static function deleteLeaveSettingDetail($id){
		return DB::table('days_leave_setting_detail')->where('id_days_leave_setting', '=', $id)->delete();
	}

	public static function listLeaveSetting($request=''){
	     return DB::table('days_leave_setting')
	          ->where('status','=',1)
	          ->where(function ($query) use ($request) {
	             if (!empty($request->title)) {
	                        $query->where('title','like','%'.trim($request->title).'%');
	             }
	          })
	          ->paginate(10);
	}

	public static function  getInfoLeaveSetting($id){
    	return DB::table('days_leave_setting')
                 ->where('id','=',$id)->first();	
	}

	public static function  getInfoLeaveSettingDetail($id){
    	return DB::table('days_leave_setting_detail')
                 ->where('id_days_leave_setting','=',$id)->get();	
	}

	public static function insertHolidaySetting($arr){
		return DB::table('holiday_setting')->insert($arr);	
	}

	public static function updateHolidaySetting($arr,$id){
	    return DB::table('holiday_setting')
	            ->where('id', '=', $id)
	            ->update($arr);
	}

	public static function listHolidaySetting($request=''){
	     return DB::table('holiday_setting')
	          ->where('status','=',1)
	          ->where(function ($query) use ($request) {
	             if (!empty($request->title)) {
	                        $query->where('title','like','%'.trim($request->title).'%');
	             }
	          })
	          ->orderby('id','DESC')
	          ->paginate(10);
	}

	public static function  getInfoHolidaySetting($id){
    	return DB::table('holiday_setting')
                 ->where('id','=',$id)->first();	
	}

	public static function checkTimeApply($dateCurrent,$selectMonth,$type){
    	$check =  DB::table('income_config')
                 ->where('valid_from','<=',$dateCurrent)
                 ->where('valid_to','>=',$dateCurrent)
                 ->where('type','=',$type)
                 ->where('status','=',1)
				 ->where('applied_month', '=',0)
                 ->count();	
         if( $check >0 ){
         	return 1;
         }else{
	    	return DB::table('income_config')
	                 ->where('valid_from','<=',$dateCurrent)
	                 ->where('valid_to','>=',$dateCurrent)
	                 ->where('type','=',$type)
	                 ->where('status','=',1)
					 ->where('applied_month', '=', $selectMonth)
	                 ->count();
         }
	
	}

	public static function  checkTimeApplyMuch($dateCurrent,$type){
    	return DB::table('income_config')
    			 ->whereIn('type', $type)
                 ->where('valid_from','<=',$dateCurrent)
                 ->where('valid_to','>=',$dateCurrent)
                 ->get();	
	}
	
	public static function checkPersonnelIncome($month,$year,$type='',$personnel_id=''){
    	return DB::table('personnel_income')
				->where('month','=',$month)
				->where('year','=',$year)
				->where(function ($query) use ($type) {
					if (!empty($type) && $type=='check_salary') {
						$query->where('check_salary','>',0);
					}
					if (!empty($type) && $type=='check_bonus') {
						$query->where('check_bonus','>',0);
					}
					if (!empty($type) && $type=='check_tax_insurance') {
						$query->where('check_tax_insurance','>',0);
					}
					if (!empty($type) && $type=='check_other') {
						$query->where('check_other','>',0);
					}
				})
				->where(function ($query_2) use ($personnel_id) {
					if (!empty($personnel_id)) {
						$query_2->where('personnel_id','=',$personnel_id);
					}
				})
				->where('status','=',1)
				->first();	
	}

	public static function checkPersonnelIncomeSpecial($month,$year,$type='',$personnel_id=''){
    	return DB::table('personnel_income')
				->where('month','=',$month)
				->where('year','=',$year)
				->where(function ($query_2) use ($personnel_id) {
					if (!empty($personnel_id)) {
						$query_2->where('personnel_id','=',$personnel_id);
					}
				})
				->first();	
	}

	public static function checkDaysLeaveSetting($year,$id=''){
	    $data =  DB::table('days_leave_setting')
				->leftJoin('days_leave_setting_detail', 'days_leave_setting_detail.id_days_leave_setting', '=', 'days_leave_setting.id')
				->where(function ($query_2) use ($id) {
					if (!empty($id)) {
						$query_2->where('days_leave_setting.id','<>',$id);
					}
				})
				->where('days_leave_setting.status','=',1)
				->where('days_leave_setting.year','=',$year)
				->get();
		$arr = array();
		if( $data ){
			
			foreach ( $data as $key => $value ) {
			   $arr[]  = $value->month;
			} 
		}
	    return $arr;
	}
	public static function checkIncomeConfigGroup( $id ){
 		return  DB::table('income_config_group')
			->where('income_config_id', '=',$id)
			->first();
	}

	public static function checkIncomeConfigbyGroupPersonnel( $id ){
 		return  DB::table('income_config_group')
			->where('personnel_group_id', '=',$id)
			->first();
	}
	// Dành cho HĐ 100% chính thức hoặc thử việc hoặc parttime
	public static function getCountLateAttendance( $month,$year,$personnel_id ){
	    return  DB::table('personnel_attendance')
			   ->select('*')
               ->where([
                    ['attendance_month', '=', $month],
                    ['attendance_year', '=', $year],
                    ['personnel_id', '=', $personnel_id],
                    ['attendance_type_id', '=',12],
                    ['time_late', '<=', \Config::get('app.time_late')],
                ])
	           ->get();
	}

	// Dành cho HĐ 50/50 chính thức hoặc thử việc hoặc parttime
	public static function getCountLateAttendanceSpeical( $month,$year,$day_1,$day_2,$personnel_id ){
	    return  DB::table('personnel_attendance')
			   ->select('*')
               ->where([
                    ['attendance_month', '=', $month],
                    ['attendance_year', '=', $year],
                    ['personnel_id', '=', $personnel_id],
                    ['attendance_day', '>=', $day_1],
                    ['attendance_day', '<=',$day_2],
                    ['attendance_type_id', '=',12],
                    ['time_late', '<=', \Config::get('app.time_late')],

                ])
	           ->get();
	}

	public static function  checkOfficialWorkDetail($type,$personnel_id,$param=''){
	    return  DB::table('contract_personnel')
			   ->select('id')
			   ->where('contract_id','=',$type)
			   ->where('apply_from','<=',$param)
			   ->where('personnel_id','=',$personnel_id)
	           ->count();
	}

	public static function  checkTrialWorkDetail($type,$personnel_id,$param=''){
	    return  DB::table('contract_personnel')
			   ->select('id')
			   ->where('contract_id','=',$type)
			   ->where(function ($query) use ($param) {
					if ( $param !=NULL ) {
						$query->where('apply_from','<=',$param);
						$query->where('apply_to','>=',$param);
					}
				})
			   ->where('personnel_id','=',$personnel_id)
	           ->count();
	}

	public static function infosettingTax(){
		return DB::table('setting_tax')
						->select('money_tax','percent_tax','money_minus')
		                ->where('status',1)
		                ->get();
	}

    public static function countAttendance_vM($attendance_month,$attendance_year,$personnel_id,$attendance_day_1,$attendance_day_2){
        return DB::table('personnel_attendance')
                    ->select('attendance_type_id','attendance_day','attendance_month','attendance_year','time_late','unit_date')
                    ->where([
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                        ['personnel_id', '=', $personnel_id],
                        ['attendance_day', '>=', $attendance_day_1],
                        ['attendance_day', '<=',$attendance_day_2],
                        ['attendance_type_id', '=',10],
                    ])
                    ->get();
    }

    public static function infoContractPersonnel($personnel_id,$contract_id){
        return DB::table('contract_personnel')->select('apply_to','apply_from')->where('personnel_id',$personnel_id)->where('contract_id',$contract_id)->first();
    }

	public static function  checkContract($personnel_id,$param=''){
	    return  DB::table('contract_personnel')
			   ->where(function ($query) use ($param) {
					if ( $param !=NULL ) {
						$query->where('apply_from','<=',$param);
						$query->where('apply_to','>=',$param);
					}
				})
			   ->where('personnel_id','=',$personnel_id)
	           ->first();
	}

	public static function  checkContractSpecial($personnel_id){
	    return  DB::table('contract_personnel')
				   ->select(DB::raw('id,contract_id, MAX(apply_to) as apply_to'))
				   ->where('personnel_id','=',$personnel_id)
		           ->first();
	}


    //Check xem nhân viên đã từng được tăng lương hoặc giảm lương chưa, nếu chưa thì có nghĩa là thời điểm được xét tăng lương tính từ lúc ký HDCT
	public static function  checkPersonnelRatioType($personnel_id){
	    return  DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_ID', '=', $personnel_id],
                    ])
                    ->whereIn('type',[1,2])
		           ->count();
	}


	public static function checkEditGroupPersonalConfigAjax($type,$personnel_id,$id){
	    $data =  DB::table('personnel_groups')
			   ->select('id','status')
			   ->where('status','=',1)
			   ->where('id','<>',$id)
	           ->get();

       if( $data ){
       		$tmp = 0;
       		foreach ($data as $key => $value) {
		    	$result = DB::table('personnel_group_detail')
		                 ->where('personnel_group_id','=',$value->id)
		                 ->where('personnel_id','=',$personnel_id)
			   			 ->where('type','=',$type)
		                 ->get();
                 if( count($result )>0 ){
                 	$tmp++;
                 }
       		}
       		return $tmp;
       }else{
       		return 0;
       }
	}

	public static function checkGroupPersonalConfigAjax($type,$personnel_id){
	     $data =  DB::table('personnel_groups')
			   ->select('id','status')
			   ->where('status','=',1)
	           ->get();
       if( $data ){
       		$tmp = 0;
       		foreach ($data as $key => $value) {
		    	$result = DB::table('personnel_group_detail')
		                 ->where('personnel_group_id','=',$value->id)
		                 ->where('personnel_id','=',$personnel_id)
		                 ->where('type','=',$type)
		                 ->get();
                 if( count($result )>0 ){
                 	$tmp++;
                 }
       		}
       		return $tmp;
       }
	}
	
    public static function checkRecipeConfig($type,$valid_from,$valid_to,$id=''){
        $result = DB::table('income_config')
                ->where(function ($query) use ($id){
                    if ($id != '') {
                        $query->whereNotIn('id', [$id]);
                    }     
                })
                ->where('type', $type)
                ->get();
        $tmp = 0;
        if( $result ){
            foreach ($result as $key => $value) {
                if( BatvHelper::handlingTime( $valid_from ) >= BatvHelper::handlingTime( $value->valid_from ) &&   BatvHelper::handlingTime( $valid_from ) <= BatvHelper::handlingTime( $value->valid_to ) ){
                    $tmp++;
                }elseif( BatvHelper::handlingTime( $valid_to ) >= BatvHelper::handlingTime( $value->valid_from ) &&   BatvHelper::handlingTime( $valid_to ) <= BatvHelper::handlingTime( $value->valid_to )  ){
                    $tmp++;
                }elseif ( BatvHelper::handlingTime( $valid_from ) < BatvHelper::handlingTime( $value->valid_from ) && BatvHelper::handlingTime( $valid_to ) > BatvHelper::handlingTime( $value->valid_to ) ) {
                    $tmp++;
                }
            }
        }
        return $tmp;
    }

	public static function listConfigKiPerformanceInYear( $year,$ids=array(),$dateCurrent ){
	     return DB::table('ki_performance')
				->leftJoin('personnel', 'personnel.id', '=', 'ki_performance.personnel_id')
				->leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
				->select('ki_performance.*','personnel.fullname', 'departments.title')
				->where('ki_performance.year',$year )
	            ->where(function ($query) use ($ids){
	                if ( !empty($ids) ) {
	                    $query->whereIn('personnel.department_id',$ids );
	                }            
	            })
	            ->where(function ($query) use ($dateCurrent) {
	                $query->where('personnel.date_out', '=', NULL)
	                      ->orWhere('personnel.date_out', '>', $dateCurrent);
	            })
				->get();
	}

	public static function listConfigKiRulesInYear( $year,$ids=array(),$dateCurrent ){
	     return DB::table('ki_rules')
				->leftJoin('personnel', 'personnel.id', '=', 'ki_rules.personnel_id')
				->leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
				->select('ki_rules.*','personnel.fullname', 'departments.title')
				->where('ki_rules.year',$year )
	            ->where(function ($query) use ($ids){
	                if ( !empty($ids) ) {
	                    $query->whereIn('personnel.department_id',$ids );
	                }            
	            })
	            ->where(function ($query) use ($dateCurrent) {
	                $query->where('personnel.date_out', '=', NULL)
	                      ->orWhere('personnel.date_out', '>', $dateCurrent);
	            })
				->get();
	}
}
