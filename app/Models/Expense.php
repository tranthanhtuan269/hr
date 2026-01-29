<?php

namespace App\Models;
use DB;
use DateTime;
use App\Helpers\BatvHelper;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{

  protected $table = "expense";
  public static function getFundsList($request=''){
     return DB::table('funds')
          ->select('id','title','type')
          ->where('status','=',1)
          ->where(function ($query) use ($request) {
             if (!empty($request->title)) {
                        $query->where('title','like','%'.trim($request->title).'%');
             }
          })
          ->paginate(10);
  }

  public static function checkFundsListPersonnel($personnel_id,$type){
     return DB::table('funds_personnel')
          ->select('personnel_id')
          ->where([
                ['personnel_id', '=', $personnel_id],
                ['status', '=', 1],
                ['type', '=', $type],
            ])
          ->count();
  }


  public static function updateFundsPersonnel($arr){
      return DB::table('funds_personnel')
            ->where('type', 1)
            ->update($arr);
  }


  public static function getIdFundsDefault(){
     return DB::table('funds')
          ->select('id')
          ->where('status','=',1)
          ->where('type','=',1)
          ->value('id');
  }

  public static function detailFundsbyPersonnel($id){
     return DB::table('funds_personnel')
              ->leftJoin('funds', 'funds.id', '=', 'funds_personnel.funds_id')
              ->select('funds.*','funds.id as ids','funds_personnel.*')
              ->where('funds_personnel.personnel_id', $id)
              ->where('funds_personnel.status', 1)
              ->orderBy('funds_personnel.apply_to','ASC')
              ->get();
  }

  public static function infoFundsbyPersonnel($id){
     return DB::table('funds_personnel')
              ->leftJoin('funds', 'funds.id', '=', 'funds_personnel.funds_id')
              ->select('funds.*','funds.id as ids','funds_personnel.*')
              ->where('funds_personnel.personnel_id', $id)
              ->where('funds_personnel.status', 1)
              ->orderBy('funds_personnel.apply_to','ASC')
              ->get();
  }

  public static function percentFundsbyPersonnel($time){
     return DB::table('funds')
              ->leftJoin('funds_personnel', 'funds_personnel.funds_id', '=', 'funds.id')
              ->leftJoin('personnel', 'personnel.id', '=', 'funds_personnel.personnel_id')
              ->where([
                      ['funds_personnel.status', 1],
                      ['funds_personnel.apply_from', '<=', $time],
                      ['funds_personnel.apply_to', '>=', $time],
                  ])
              ->where(function ($query) use ($time) {
                  $query->where('personnel.date_out', '=', NULL)
                        ->orWhere('personnel.date_out', '>', $time);
              })
              ->get();
  }

  public static function insertFunds($arr){
      return DB::table('funds')->insert($arr);
  }         

  public static function infoFunds($id){
      return DB::table('funds')->where('id','=',$id)->where('status','=',1)->first();
  }

  public static function infoFundsAll(){
      return DB::table('funds')->where('status','=',1)->get();
  }

  public static function updateFunds($arr,$id){
      return DB::table('funds')
            ->where('id', $id)
            ->update($arr);
  }

  public static function updateFundsSpecial($arr){
      return DB::table('funds')
            ->update($arr);
  }

  public static function getExpenseList($request=''){
     return DB::table('expense')
          ->where('status','=',1)
          ->where(function ($query) use ($request) {
             if (!empty($request->title)) {
                $query->where('title','like','%'.trim($request->title).'%');
             }
          })
          ->paginate(10);
  }

  public static function insertExpense($arr){
      return DB::table('expense')->insert($arr);
  }     

  public static function updateExpense($arr,$id){
    return DB::table('expense')
        ->where('id', $id)
        ->update($arr);

  }
  
  public static function insertFundsExpense($arr){
      return DB::table('funds_expense')->insert($arr);
  }  

  public static function deleteFundsExpense($id){
    return DB::table('funds_expense')->where('expense_id', '=', $id)->delete();
  }  

  public static function getExpenseGeneral( $day_from,$day_to,$request='' ){
     return DB::table('expense')
        ->select('expense.*','funds.title as funds_title','funds_expense.*')
        ->leftJoin('funds_expense', 'funds_expense.expense_id', '=', 'expense.id')
        ->leftJoin('funds', 'funds.id', '=', 'funds_expense.funds_id')
        ->where('expense.status','=',1)
        ->where([
                ['expense.valid_from', '<=', $day_from],
                ['expense.valid_from', '<=', $day_to],
                ['expense.valid_to', '>=', $day_from],
                ['expense.valid_to', '>=',$day_to],
                ['expense.status', '=',1],
            ])
        ->where(function ($query) use ($request) {
            if ( !empty($request->personnel) ) {
              $query->where('expense.created_by', '=', $request->personnel);
            }
            if ( ($request->type) != '' ) {
              $query->where('expense.type', '=',$request->type);
            }
            if ( !empty($request->funds) ) {
              $query->where('funds_expense.funds_id', '=',$request->funds);
            }
        })
        ->orderBy('expense.valid_from', 'ASC')
        ->get();
  }

  public static function getExpenseGeneral_v2( $day_from,$day_to,$request='' ){
     return DB::table('expense')
        ->select('expense.*','funds.title as funds_title','funds_expense.*')
        ->leftJoin('funds_expense', 'funds_expense.expense_id', '=', 'expense.id')
        ->leftJoin('funds', 'funds.id', '=', 'funds_expense.funds_id')
        ->where('expense.status','=',1)
        ->where([
                ['expense.valid_from', '<=', $day_from],
                ['expense.valid_from', '<', $day_to],
                ['expense.valid_to', '>', $day_from],
                ['expense.valid_to', '<',$day_to],
                ['expense.status', '=',1],
            ])
        ->where(function ($query) use ($request) {
            if ( !empty($request->personnel) ) {
              $query->where('expense.created_by', '=', $request->personnel);
            }
            if ( ($request->type) != '' ) {
              $query->where('expense.type', '=',$request->type);
            }
            if ( !empty($request->funds) ) {
              $query->where('funds_expense.funds_id', '=',$request->funds);
            }
        })
        ->orderBy('expense.valid_from', 'ASC')
        ->get();
  }


  public static function getExpenseGeneral_v3( $day_from,$day_to,$request='' ){
     return DB::table('expense')
        ->select('expense.*','funds.title as funds_title','funds_expense.*')
        ->leftJoin('funds_expense', 'funds_expense.expense_id', '=', 'expense.id')
        ->leftJoin('funds', 'funds.id', '=', 'funds_expense.funds_id')
        ->where('expense.status','=',1)
        ->where([
                ['expense.valid_from', '>', $day_from],
                ['expense.valid_from', '<=', $day_to],
                ['expense.valid_to', '>', $day_from],
                ['expense.valid_to', '>=',$day_to],
                ['expense.status', '=',1],
            ])
        ->where(function ($query) use ($request) {
            if ( !empty($request->personnel) ) {
              $query->where('expense.created_by', '=', $request->personnel);
            }
            if ( ($request->type) != '' ) {
              $query->where('expense.type', '=',$request->type);
            }
            if ( !empty($request->funds) ) {
              $query->where('funds_expense.funds_id', '=',$request->funds);
            }
        })
        ->orderBy('expense.valid_from', 'ASC')
        ->get();
  }

  public static function getExpenseGeneral_v4( $day_from,$day_to,$request='' ){
     return DB::table('expense')
        ->select('expense.*','funds.title as funds_title','funds_expense.*')
        ->leftJoin('funds_expense', 'funds_expense.expense_id', '=', 'expense.id')
        ->leftJoin('funds', 'funds.id', '=', 'funds_expense.funds_id')
        ->where('expense.status','=',1)
        ->where([
                ['expense.valid_from', '>', $day_from],
                ['expense.valid_from', '<', $day_to],
                ['expense.valid_to', '>', $day_from],
                ['expense.valid_to', '<',$day_to],
                ['expense.status', '=',1],
            ])
        ->where(function ($query) use ($request) {
            if ( !empty($request->personnel) ) {
              $query->where('expense.created_by', '=', $request->personnel);
            }
            if ( ($request->type) != '' ) {
              $query->where('expense.type', '=',$request->type);
            }
            if ( !empty($request->funds) ) {
              $query->where('funds_expense.funds_id', '=',$request->funds);
            }
        })
        ->orderBy('expense.valid_from', 'ASC')
        ->get();
  }

  public static function getFundsPersonnel( $request ){
     return DB::table('funds_personnel')
        ->where('status','=',1)
        ->where(function ($query) use ($request) {
            if ( !empty($request->funds) ) {
              $query->where('funds_id', '=', $request->funds);
            }

        })
        ->where(function ($query) use ($request) {
          if (!empty($request->valid_from) && !empty($request->valid_to)) {
            $day_from = BatvHelper::formatDate($request->valid_from,"d/m/Y", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
            $day_to  = BatvHelper::formatDate($request->valid_to,"d/m/Y", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
          }else{
            $day_from = date('Y')."-".date('m')."-01";
            $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
            $day_to = date('Y')."-".date('m')."-".$numberDay;
          }
           $query->where([
                ['apply_from', '<=', $day_from],
                ['apply_from', '<=', $day_to],
                ['apply_to', '>=', $day_from],
                ['apply_to', '>=',$day_to],
            ]);


        })
        ->get();
  }

  public static function getFundsPersonnel_v2( $request ){
     return DB::table('funds_personnel')
        ->where('status','=',1)
        ->where(function ($query) use ($request) {
            if ( !empty($request->funds) ) {
              $query->where('funds_id', '=', $request->funds);
            }

        })
        ->where(function ($query) use ($request) {
          if (!empty($request->valid_from) && !empty($request->valid_to)) {
            $day_from = BatvHelper::formatDate($request->valid_from,"d/m/Y", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
            $day_to  = BatvHelper::formatDate($request->valid_to,"d/m/Y", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
          }else{
            $day_from = date('Y')."-".date('m')."-01";
            $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
            $day_to = date('Y')."-".date('m')."-".$numberDay;
          }
           $query->where([
                ['apply_from', '<=', $day_from],
                ['apply_from', '<', $day_to],
                ['apply_to', '>', $day_from],
                ['apply_to', '<',$day_to],
            ]);


        })
        ->get();
  }

  public static function getFundsPersonnel_v3( $request ){
     return DB::table('funds_personnel')
        ->where('status','=',1)
        ->where(function ($query) use ($request) {
            if ( !empty($request->funds) ) {
              $query->where('funds_id', '=', $request->funds);
            }

        })
        ->where(function ($query) use ($request) {
          if (!empty($request->valid_from) && !empty($request->valid_to)) {
            $day_from = BatvHelper::formatDate($request->valid_from,"d/m/Y", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
            $day_to  = BatvHelper::formatDate($request->valid_to,"d/m/Y", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
          }else{
            $day_from = date('Y')."-".date('m')."-01";
            $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
            $day_to = date('Y')."-".date('m')."-".$numberDay;
          }
           $query->where([
                ['apply_from', '>', $day_from],
                ['apply_from', '<=', $day_to],
                ['apply_to', '>', $day_from],
                ['apply_to', '>=',$day_to],
            ]);


        })
        ->get();
  }

  public static function getFundsPersonnel_v4( $request ){
     return DB::table('funds_personnel')
        ->where('status','=',1)
        ->where(function ($query) use ($request) {
            if ( !empty($request->funds) ) {
              $query->where('funds_id', '=', $request->funds);
            }

        })
        ->where(function ($query) use ($request) {
          if (!empty($request->valid_from) && !empty($request->valid_to)) {
            $day_from = BatvHelper::formatDate($request->valid_from,"d/m/Y",$formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
            $day_to  = BatvHelper::formatDate($request->valid_to,"d/m/Y",$formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
          }else{
            $day_from = date('Y')."-".date('m')."-01";
            $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
            $day_to = date('Y')."-".date('m')."-".$numberDay;
          }
           $query->where([
                ['apply_from', '>', $day_from],
                ['apply_from', '<', $day_to],
                ['apply_to', '>', $day_from],
                ['apply_to', '<',$day_to],
            ]);


        })
        ->get();
  }
  public static function listAllSalary( $personnel_id,$funds_id,$valid_from,$valid_to ){

       return DB::table('personnel')
        // ->leftJoin('loan_capital', 'loan_capital.personnel_id', '=', 'personnel.id')
        // ->leftJoin('history_pay_loan_capital', function($join) use ($month, $year) {
        //   $join->on('loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
        //     ->where('history_pay_loan_capital.month', '>', 0)
        //     ->where('loan_capital.pay','=', 1)
        //     ->where(\DB::raw('MONTH(history_pay_loan_capital.repayment_period)'),'=', $month)
        //     ->where(\DB::raw('YEAR(history_pay_loan_capital.repayment_period)'),'=', $year);
        // })
        ->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
        ->leftJoin('personnel_bonus', 'personnel_bonus.personnel_income_id', '=', 'personnel_income.id')
        ->leftJoin('personnel_tax_insurance', 'personnel_tax_insurance.personnel_income_id', '=', 'personnel_income.id')
        ->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
        // ->select('personnel.fullname','personnel.email','personnel_income.*','personnel_bonus.*','personnel_tax_insurance.*','personnel_salary.*')
        ->selectRaw('personnel.fullname,
                    personnel.id as personnel_id,
                    personnel.email,
                    sum(salary_overtime) as salary_overtime,                           
                    sum(salary_trial_work) as salary_trial_work,                              
                    sum(salary_official_work) as salary_official_work,                                 
                    sum(salary_trainee_work) as salary_trainee_work,                                 
                    sum(salary_trainee_parttime_work) as salary_trainee_parttime_work,                                         
                    sum(salary_parttime_work) as salary_parttime_work,                                 
                    sum(management_allowance) as management_allowance,                                  
                    sum(work_bonus) as work_bonus,                        
                    sum(insurance) as insurance,                       
                    sum(money_work_late) as money_work_late,                            
                    sum(personnel_salary.welfare_fund) as welfare_fund,                         
                    sum(parking_fee_allowance) as parking_fee_allowance,                                  
                    sum(other_tax_allowance) as other_tax_allowance,                                
                    sum(laptop_allowance) as laptop_allowance,                             
                    sum(mulct_money_awol) as mulct_money_awol,                             
                    sum(holiday_bonus) as holiday_bonus,                          
                    sum(party_fee) as party_fee,                      
                    sum(lunch_allowance) as lunch_allowance,                            
                    sum(travel_allowance) as travel_allowance,                             
                    sum(phone_allowance) as phone_allowance,                            
                    sum(movement_allowance) as movement_allowance,                               
                    sum(insurance_by_company) as insurance_by_company
                    ')
        ->where(function ($query) use ($valid_from,$valid_to) {
            if (!empty($valid_from) && !empty($valid_to)) {
              $month_from =  BatvHelper::formatDate($valid_from,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",$time=false);
              $month_to =  BatvHelper::formatDate($valid_to,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",$time=false);
              $year_from =  BatvHelper::formatDate($valid_from,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",$time=false);
              $year_to =  BatvHelper::formatDate($valid_to,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",$time=false);
              $query->whereBetween('personnel_income.date_value', [$year_from . '-' . $month_from . '-01', $year_to . '-' . $month_to . '-01']);
                  // dd($year_from . '-' . $month_from . '-01'. '==========' . $year_to . '-' . $month_to . '-01');
              // $query->whereBetween('personnel_income.year', [$year_from, $year_to]);
            }

        })
        ->where(function ($query_3) use ($personnel_id) {
            if (!empty($personnel_id)) {
              $query_3->where('personnel.id', '=', $personnel_id);
            }

        })
        ->groupBy('personnel_income.personnel_id')
        ->get();
  }
  public static function listSalaryOther(  $personnel_id='',$funds_id='',$valid_from,$valid_to  ){
       return DB::table('personnel')
        ->leftJoin('funds_personnel', 'funds_personnel.personnel_id', '=', 'personnel.id')
        ->leftJoin('personnel_income', 'personnel_income.personnel_id', '=', 'personnel.id')
        ->leftJoin('personnel_income_other', 'personnel_income_other.personnel_income_id', '=', 'personnel_income.id')
        ->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
        ->select('personnel.fullname','personnel.id as personnel_id','personnel_income_other.*','personnel_income.status','personnel_salary.salary_trial_default','personnel_salary.salary_official_default')
        ->where('funds_personnel.status', 1)
        ->where(function ($query) use ($valid_from,$valid_to) {
            if (!empty($valid_from) && !empty($valid_to)) {
              $month_from =  BatvHelper::formatDate($valid_from,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",$time=false);
              $month_to =  BatvHelper::formatDate($valid_to,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",$time=false);
              $year_from =  BatvHelper::formatDate($valid_from,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",$time=false);
              $year_to =  BatvHelper::formatDate($valid_to,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",$time=false);
              $query->whereBetween('personnel_income.month', [$month_from, $month_to]);
              $query->whereBetween('personnel_income.year', [$year_from, $year_to]);
            }

        })
        ->where(function ($query_2) use ($funds_id) {
            if (!empty($funds_id)) {
              $query_2->where('funds_personnel.funds_id', '=', $funds_id);
            }

        })
        ->where(function ($query_3) use ($personnel_id) {
            if (!empty($personnel_id)) {
              $query_3->where('personnel.id', '=', $personnel_id);
            }

        })
        // ->groupBy('personnel_income.id')
        ->get();
  }

  public static function infoExpense($id){
      return DB::table('expense')->where('id','=',$id)->first();
  }

  public static function listFundsExpense($id){
      return DB::table('expense')
              ->join('funds_expense', 'funds_expense.expense_id', '=', 'expense.id')
              ->select('expense.*','funds_expense.*')
              ->where('expense.id', $id)
              ->get();
  }


}
