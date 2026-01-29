<?php

namespace App\Models;
use DB;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
  protected $table = "personnel_evaluation";

  public static function infoDepartment( $id ){
      return DB::table('departments')->where('id',$id)->value('manager_id');
      
  }
  public static function insertEvaluationSupport($arr){
      return DB::table('evaluation_default_criteria')->insert($arr);
  }

  public static function insertEvaluationCriteria($arr){
      return DB::table('evaluation_criteria')->insert($arr);
  }

	public static function insertEvaluationStage($arr){
		return DB::table('evaluation_stage')->insert($arr);
	}

  public static function updateEvaluationStage($arr,$id){
    return DB::table('evaluation_stage')
        ->where('id', '=', $id)
        ->update($arr);
  }

	public static function insertEvaluationStageDetail($arr){
		return DB::table('evaluation_stage_detail')->insert($arr);
	}

  public static function deleteEvaluationStageDetail($id){
    return DB::table('evaluation_stage_detail')->where('stage_id', '=', $id)->delete();;
  }


  public static function getInfoEvaluationSupport($id=0){
    return DB::table('evaluation_criteria')
                 ->where('id','=',$id)->first();
  }

  public static function updateEvaluationSupport($arr,$id){
    return DB::table('evaluation_criteria')
            ->where('id', '=', $id)
            ->update($arr);
  }

  public static function listCriteria($request=''){
    	return DB::table('evaluation_criteria')->select('id','criteria_content')
                        ->where('id','>',0)
												->where(function ($query) use ($request) {
													 if (!empty($request->criteria_content)) {
									                    $query->where('criteria_content','like','%'.trim($request->criteria_content).'%');
													 }
												})
										    	->orderBy('ID', 'ASC')->get();
    }

  public static function listDepartmentCriteria($request=''){
     return DB::table('evaluation_stage')
          ->leftJoin('evaluation_stage_detail', 'evaluation_stage_detail.stage_id', '=', 'evaluation_stage.id')
          ->leftJoin('evaluation_criteria', 'evaluation_criteria.id', '=', 'evaluation_stage_detail.criteria_id')
          ->select('evaluation_stage.id','evaluation_stage.date_start','evaluation_stage.date_end','evaluation_stage.title','evaluation_criteria.criteria_content')
          ->where(function ($query) use ($request) {
             if (!empty($request->title)) {
                        $query->where('evaluation_stage.title','like','%'.trim($request->title).'%');
             }
          })->get();
  }

  public static function checkEvaluationStagebyTime($request){
      $check = DB::table('evaluation_stage')->where('type','=',$request->type)->count();
      if( $check == 0){
        return 1;
      }else{
        $startdate = DateTime::createFromFormat('d/m/yy', $request->startdate); 
        $enddate = DateTime::createFromFormat('d/m/yy', $request->enddate); 
        return DB::table('evaluation_stage')
                ->selectRaw('max(date_end),min(date_start)')
                ->where('type','=',$request->type)
                // ->whereNotBetween('date_start', array( $startdate->format('Y-m-d'),$enddate->format('Y-m-d') ) )
                // ->whereNotBetween('date_end', array($startdate->format('Y-m-d'),$enddate->format('Y-m-d')) )
                //->where('date_end','<',$startdate->format('Y-m-d') )
                //->where('date_start','>',$enddate->format('Y-m-d') )
                ->whereRaw(' ( date_start >'.'"'.$enddate->format('Y-m-d').'"'.' OR date_end <'.'"'.$startdate->format('Y-m-d').'"'. ')' )
                ->count();
      }
  }

  public static function checkEvaluationStagebyTimeEdit($request,$id=''){
      $check = DB::table('evaluation_stage')->where('type','=',$request->type)->count();
      if( $check == 1 || $check == 0 ) {
        return 1;
      }
      if ( $check >1 ) {
        $startdate = DateTime::createFromFormat('d/m/yy', $request->startdate); 
        $enddate = DateTime::createFromFormat('d/m/yy', $request->enddate); 
        
        return DB::table('evaluation_stage')
                ->where('type','=',$request->type)
                ->where(function ($query) use ($id) {  
                   if (!empty($id)) {
                       $query->where('id','<>',$id);
                   }
                })
                ->whereRaw(' ( date_start >'.'"'.$enddate->format('Y-m-d').'"'.' OR date_end <'.'"'.$startdate->format('Y-m-d').'"'. ')' )
                ->count();
      }

  }

  public static function checkManager($personnel_id){
       return DB::table('departments')
            ->leftJoin('personnel', 'personnel.department_id', '=', 'departments.id')
            ->select('departments.*')
            ->where('personnel.id', '=', $personnel_id )
            ->first();
  }

  public static function checkEvaluationCriteriabyTime($type){
       return DB::table('evaluation_stage')
            ->leftJoin('evaluation_stage_detail', 'evaluation_stage.id', '=', 'evaluation_stage_detail.stage_id')
            ->rightJoin('evaluation_criteria', 'evaluation_stage_detail.criteria_id', '=', 'evaluation_criteria.id')
            ->select('evaluation_criteria.id', 'evaluation_criteria.criteria_content')
            ->where('evaluation_stage.type', '=', $type )
            ->where('evaluation_stage.date_start', '<=', date('Y-m-d', strtotime("-1 month", strtotime( date('Y-m-d') ))) )
            ->where('evaluation_stage.date_end', '>=', date('Y-m-d', strtotime("-1 month", strtotime( date('Y-m-d') ))))
            ->groupBy('evaluation_criteria.criteria_content')
            ->orderBy('evaluation_criteria.id', 'ASC')
            ->get();
  }

  public static function checkPersonnelEvaluationDetail($id,$year,$type,$turns=''){
       return DB::table('personnel_evaluation_details')
            ->leftJoin('personnel_evaluation', 'personnel_evaluation.id', '=', 'personnel_evaluation_details.personnel_evaluation_id')
            ->select('personnel_evaluation_details.*','personnel_evaluation.*')
            ->where('personnel_evaluation.personnel_id', '=', $id)
            ->where(function ($query) use ($turns) {
               if (!empty($turns)) {
                    $query->where('personnel_evaluation.turns','=',$turns);
               }
            })
            ->where('personnel_evaluation_details.date', '=', $year)
            ->where('personnel_evaluation_details.type', '=', $type)
            ->get();
  }

  public static function checkPersonnelEvaluationManagerDetail($p_id,$id,$year,$type){
       return DB::table('personnel_evaluation_details')
            ->where('personnel_evaluation_id', '=', $id)
            ->where('p_id', '=', $p_id)
            ->where('date', '=', $year)
            ->where('type', '=', $type)
            ->get();
  }

  public static function pointPersonnelEvaluationDetail($id,$beforerCurrentDate,$type){
          return  DB::table('personnel_evaluation_details')
                ->where('personnel_evaluation_id', $id)
                ->where('date', $beforerCurrentDate)
                ->where('type',$type)
                ->sum('point');
  }
  public static function pointPersonnelEvaluationManagerDetail($p_id,$id,$beforerCurrentDate,$type){
          return  DB::table('personnel_evaluation_details')
                ->where('personnel_evaluation_id', $id)
                ->where('date', $beforerCurrentDate)
                ->where('type',$type)
                ->where('p_id', '=', $p_id)
                ->sum('point');
  }

  public static function deleteEvaluationCriteria($id){
       return DB::table('evaluation_criteria')->where('id', '=', $id)->delete();
  }

  public static function deleteDepartmentCriteria($id){
       return DB::table('evaluation_stage')->where('id', '=', $id)->delete();
  }

	public static function getInfoEvaluationCriteria($id){
       	 return DB::table('evaluation_criteria')
                 ->where('id','=',$id)->first();
	}

  public static function getInfoEvaluationbyPersonnel($id,$year,$type,$turns=''){
         return DB::table('personnel_evaluation')
              ->leftJoin('personnel_evaluation_details', 'personnel_evaluation_details.personnel_evaluation_id', '=', 'personnel_evaluation.id')
              ->leftJoin('personnel', 'personnel.id', '=', 'personnel_evaluation.personnel_id')
              ->leftJoin('evaluation_criteria', 'evaluation_criteria.id', '=', 'personnel_evaluation_details.criteria_id')
              ->select('personnel.fullname','personnel_evaluation.personnel_id', 'personnel_evaluation.management_allowance', 'personnel_evaluation.management_allowance_old','personnel_evaluation.comment','personnel_evaluation.comment_manager','personnel_evaluation.ratio_propose', 'personnel_evaluation_details.*','evaluation_criteria.criteria_content','evaluation_criteria.criteria_weight','evaluation_criteria.criteria_group_id')
              ->where('personnel_evaluation.personnel_id', $id)
              ->where('personnel_evaluation.date', $year)
              ->where(function ($query) use ($turns) {
                 if (!empty($turns)) {
                      $query->where('personnel_evaluation.turns','=',$turns);
                 }
              })
              ->where('personnel_evaluation_details.type', $type)
              ->orderBy('evaluation_criteria.id', 'ASC')
              ->get();
  }

  public static function getInfoEvaluationManagerbyPersonnel($p_id,$id,$beforerCurrentDate,$type,$turns=''){
         return DB::table('personnel_evaluation')
              ->leftJoin('personnel_evaluation_details', 'personnel_evaluation_details.personnel_evaluation_id', '=', 'personnel_evaluation.id')
              ->leftJoin('evaluation_criteria', 'evaluation_criteria.id', '=', 'personnel_evaluation_details.criteria_id')
              ->select('personnel_evaluation.personnel_id', 'personnel_evaluation_details.*','evaluation_criteria.criteria_content')
              ->where('personnel_evaluation_details.p_id', $p_id)
              ->where('personnel_evaluation.personnel_id', $id)
              ->where('personnel_evaluation.date', $beforerCurrentDate)
              ->where(function ($query) use ($turns) {
                 if (!empty($turns)) {
                      $query->where('personnel_evaluation.turns','=',$turns);
                 }
              })
              ->where('personnel_evaluation_details.type', $type)
              ->get();
  }

  public static function getInfoDepartmentCriteria($id=''){
         return DB::table('evaluation_stage')
              ->leftJoin('evaluation_stage_detail', 'evaluation_stage.id', '=', 'evaluation_stage_detail.stage_id')
              ->rightJoin('evaluation_criteria', 'evaluation_stage_detail.criteria_id', '=', 'evaluation_criteria.id')
              ->select('evaluation_criteria.id as evaluation_criteria_id ', 'evaluation_criteria.criteria_content','evaluation_stage.id','evaluation_stage.title','evaluation_stage.date_start','evaluation_stage.date_end','evaluation_stage.type')
              ->where(function ($query) use ($id) {
                 if (!empty($id)) {
                      $query->where('evaluation_stage.id','=',$id);
                 }
              })
              ->get();
  }


  public static function checkInfoDepartmentCriteria($id=''){
         return DB::table('evaluation_stage')
              ->leftJoin('evaluation_stage_detail', 'evaluation_stage.id', '=', 'evaluation_stage_detail.stage_id')
              ->leftJoin('evaluation_criteria', 'evaluation_stage_detail.criteria_id', '=', 'evaluation_criteria.id')
              ->select('evaluation_criteria.id as evaluation_criteria_id ', 'evaluation_criteria.criteria_content','evaluation_stage.id','evaluation_stage.title','evaluation_stage.date_start','evaluation_stage.date_end')
              ->where(function ($query) use ($id) {
                 if (!empty($id)) {
                      $query->where('evaluation_stage.id','=',$id);
                 }
              })
              ->get();
  }
	public static function updateInfoCriteria($arr,$id){
        return DB::table('evaluation_criteria')
            ->where('id', '=', $id)
            ->update($arr);
	}

	public static function checkDepartmentbyManager($id){
     return  DB::table('departments')
            ->where('manager_id', '=', $id)
            ->get();
	}

  public static function listPersonnelbyManager($department_id){
     return  DB::table('personnel')
            ->where('status', '=', 1)
            ->where('id', '<>', \Auth::user()->id)
            ->whereIn('department_id', $department_id)
            ->where('date_out', '=', NULL)
            ->orWhere('date_out', '>', date('Y-m-d') )
            ->orderBy('last_name', 'ASC')->get();
  }

  // public static function listPersonnelAdhocSalaryAssessment($personnel_id,$year){
  //    return  DB::table('personnel_evaluation')
  //           ->where([
  //               ['personnel_id', '=', $personnel_id],
  //               ['date', '=', $year],
  //               ['type', '=', 1],
  //           ])
  //           ->get();
  // }

	public static function  checkPersonnelEvaluation($id,$beforerCurrentDate,$type,$turns=''){
         return DB::table('personnel_evaluation')
              ->select('id','count','total_point','personnel_id','type','comment_manager', 'management_allowance','options')
              ->where('personnel_id', '=', $id)
              ->where('date', '=', $beforerCurrentDate)
              ->where('type', '=', $type)
              ->where(function ($query) use ($turns) {
                 if (!empty($turns)) {
                      $query->where('turns','=',$turns);
                 }
              })
              ->first();
	}
	public static function  insertPersonnelEvaluationDetail($arr){
		return DB::table('personnel_evaluation_details')->insert($arr);
	}

  public static function  deletePersonnelEvaluationDetail($beforerCurrentDate,$personnel_evaluation_id,$type){
       return DB::table('personnel_evaluation_details')
            ->where('personnel_evaluation_id', '=', $personnel_evaluation_id)
            ->where('type', '=', $type)
            ->where('date', '=', $beforerCurrentDate)
            ->delete();
  }

  public static function  deletePersonnelEvaluationManagerDetail($p_id,$beforerCurrentDate,$personnel_evaluation_id,$type){
       return DB::table('personnel_evaluation_details')
            ->where('personnel_evaluation_id', '=', $personnel_evaluation_id)
            ->where('type', '=', $type)
            ->where('p_id', '=', $p_id)
            ->where('date', '=', $beforerCurrentDate)
            ->delete();
  }

  public static function  insertPersonnelEvaluation($arr){
    return DB::table('personnel_evaluation')->insert($arr);
  }
  public static function  infoPersonnelEvaluation($id,$year,$turns=''){
    return DB::table('personnel_evaluation')
            ->where('date', '=', $year)
            ->where('personnel_id', '=', $id)
            ->where(function ($query) use ($turns) {
               if (!empty($turns)) {
                    $query->where('turns','=',$turns);
               }
            })
            ->first();
  }
  public static function  updatePersonnelEvaluation($id,$beforerCurrentDate,$type,$arr,$turns=''){
    return DB::table('personnel_evaluation')
            ->where('date', '=', $beforerCurrentDate)
            ->where('personnel_id', '=', $id)
            ->where('type', '=', $type)
            ->where(function ($query) use ($turns) {
               if (!empty($turns)) {
                    $query->where('turns','=',$turns);
               }
            })
            ->update($arr);
  }

  public static function checkTitleEvaluationCriteria($criteria_content,$id=''){ 
      return DB::table('evaluation_criteria')
            ->select('id','criteria_content')
            ->where('criteria_content','=',$criteria_content)
            // ->where('status','=',1)
            ->where(function ($query) use ($id) {
               if (!empty($id)) {
                          $query->where('id','<>',$id);
               }
            })
            ->count();
  }

  public static function listResultPointCriteria($arr_personnel_id,$turns,$year, $ids = array()){ 
      return DB::table('personnel')
            ->leftJoin('users','personnel.user_id', '=', 'users.id')
            ->leftJoin('personnel_evaluation', 'personnel_evaluation.personnel_id', '=', 'personnel.id')
            ->leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
            ->where('personnel_evaluation.turns','=',$turns)
            ->where('personnel_evaluation.date','=',$year )
            ->where(function ($query) use ($ids){
               if ( !empty($ids) ) {
                   $query->whereIn('personnel.department_id',$ids );
               }            
           })
            ->whereIn('personnel_evaluation.personnel_id', $arr_personnel_id)
            ->get();
  }

}
