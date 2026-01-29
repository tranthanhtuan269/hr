<?php

namespace App\Models;
use DB;
use DateTime;
use PDO;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BatvHelper;
use App\Models\MaternityLeave;
class Personnel extends Model
{
    protected $table = "personnel";
    private static $personnel = 'personnel';

    public function loanCapital()
    {
    	return $this->hasOne('App\Models\LoanCapital', 'personnel_id');
    }

    public static function getAllPersonnel(){
        return  Personnel::select('id','fullname','date_in', 'date_out')
                ->where('status','=',1)
                ->get();
    }

    public static function checkDateOutCalAllowance($personnel_id, $month, $year){
        return Personnel::where('id', $personnel_id)->whereDay('date_out' ,'<=', 15)->whereMonth('date_out', '=', $month)->whereYear('date_out', '=', $year)->count();
    }

    public static function getAllPersonnelCurrent(){
        return  DB::table('personnel')->where('status', '=', 1)->where('date_out', '=', NULL)->orWhere('date_out', '>', date('Y-m-d') )->orderBy('date_in', 'ASC')->get();
    }

    public static function getAllPersonnelCurrentbyManager($ids=array(),$field=''){
        $result = Personnel::leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
                    ->select('personnel.fullname','personnel.id')
                    ->where(function ($query) use ($ids){
                        if ( !empty($ids) ) {
                            $query->whereIn('personnel.department_id',$ids );
                        }            
                    })
                    ->where('personnel.status', '=', 1)
                    ->where('personnel.date_out', '=', NULL)
                    ->orWhere('personnel.date_out', '>', date('Y-m-d') )
                    ->orderBy('personnel.last_name', 'ASC');
        if( $field == 'personnel_id' ){
            return $result->lists('personnel.id')->toArray();
        }else{
            return $result->get();
        }
    }

    public static function getAllAdhocSalaryAssessmentbyYear( $turns='',$year ){
        return  DB::table('adhoc_salary_assessment')
                ->select('personnel.*')
                ->leftJoin('personnel', 'personnel.id', '=', 'adhoc_salary_assessment.personnel_id')
                ->where('adhoc_salary_assessment.turns','=',$turns)
                ->where('adhoc_salary_assessment.year','=',$year)
                ->where('adhoc_salary_assessment.type','=',2)
                ->groupBy('adhoc_salary_assessment.personnel_id')
                ->get();
    }

    public static function deleteAdhocSalaryAssessmentbyYear($personnel_id){
         return DB::table('adhoc_salary_assessment')->where('personnel_id', '=', $personnel_id)->where('year','=',date('Y') )->delete();
    }

    public static function getPersonnelSalary($month,$year,$date_out){
        $convert = explode("-",$date_out);
        $param = $convert[0].'-'.$convert[1];
        $ids = DB::table('setting_absent_attendance')
                ->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$param)
                ->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$param)
                ->pluck('personnel_id');

        $obj1 =  \DB::table('exceptional_attendance')
                ->where('status', '=', 1)
                ->where(function ($query) use ($param) {
                    $query->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$param);
                    $query->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$param);
                })
                ->lists('personnel_id');

        $obj2 =  DB::table('personnel')
                ->leftJoin('personnel_attendance', 'personnel_attendance.personnel_id', '=', 'personnel.id')
                ->select('personnel.id')
                ->where('personnel.status','=',1) 
                ->where('personnel_attendance.attendance_month','=',$month) 
                ->where('personnel_attendance.attendance_year','=',$year) 
                ->whereNotIn('personnel.id', $ids)
                ->groupBy('personnel_attendance.personnel_id')
                ->lists('personnel.id');
                
        $obj3 =  DB::table('personnel_income')
                    ->where([
                        ['month', '=', $convert[1]],
                        ['year', '=', $convert[0]],
                    ])
                ->lists('personnel_id');

        $result = array_map("unserialize", array_unique(array_map("serialize", array_merge($obj1,$obj2,$obj3))));
        return (object) $result;
    }

    public static function getCurrentInfo($id){
        $personnel_ID = DB::table('personnel')->select('id')->where('id','=',$id)->first();
        $data = '';
        if (!empty($personnel_ID)) {
            $data = DB::table('personnel')
                 ->leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
                 // ->leftJoin('job_titles', 'personnel.job_title_id', '=', 'job_titles.id')
                 //->leftJoin('personnel_job_ratio','personnel.id','=','personnel_job_ratio.personnel_ID')
                 ->leftJoin(DB::raw('(SELECT * FROM personnel_job_ratio WHERE personnel_job_ratio.personnel_ID = '.$personnel_ID->id .' ORDER BY ABS( DATEDIFF( apply_from, NOW() ) ) LIMIT 1 ) AS c'),function($query){
                        $query->on( 'c.personnel_ID', '=', 'personnel.id' );
                 })   
                 ->select('personnel.*','departments.title','departments.manager_id','c.ratio') 
                 ->where('personnel.id','=',$personnel_ID->id)->first();
        }
        if (!empty($data->birthday)) {
            $myDateTime = DateTime::createFromFormat('Y-m-d', $data->birthday);
            $data->birthday = $myDateTime->format('d/m/Y');
        }
        return $data;

    }
    public static function getInfo($id){
        $data =  DB::table('personnel')
                 ->where('id','=',$id)->first();
        if (!empty($data->birthday)) {
            $myDateTime = DateTime::createFromFormat('Y-m-d', $data->birthday);
            $data->birthday = $myDateTime->format('d/m/Y');
        }
        return $data;
    }

    public static function getCurrentId($id){
        return DB::table('personnel')->select('id')->where('user_id','=',$id)->first();
    }
    public static function insertInfo($arr){
        return DB::table('personnel')->insert($arr);
    }
    public static function listPersonnel($request=array(),$ids=array()){
         //return DB::table('Personnel')->select('name', 'email as user_email')->get();
        //return DB::table('Personnel')->get();
        $data = DB::table('personnel')
            ->leftJoin('users','personnel.user_id', '=', 'users.id')
            ->leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
            ->select('personnel.*','departments.title','users.email as tk') 
            ->where(function ($query_2) use ($ids){
                if ( !empty($ids) ) {
                    $query_2->whereIn('personnel.department_id',$ids );
                }            
            })
            ->where(function ($query) use ($request){
                if ($request->hoten != '') {
                    $query->where('personnel.fullname', 'LIKE', '%'.trim($request->hoten).'%');
                }
                if ($request->email != '') {
                    $query->where('personnel.email','LIKE', '%'.trim($request->email).'%');
                }
                if ($request->phone != '') {
                    $query->where('personnel.phone_number','LIKE', '%'.trim($request->phone).'%');
                }    
            })
            ->where('personnel.status','=',1) 
            ->orderBy('id', 'desc')
            ->paginate(10);
        // if (!empty($data)) {
        //     foreach ($data as $value) {
        //         $birthday = DateTime::createFromFormat('Y-m-d', $value->birthday);
        //         $value->birthday = $birthday->format('d/m/Y');
        //     }            
        // }
        return $data;
    }

    public static function listPersonnelHidden($request=array(),$ids=array()){
       $data = DB::table('personnel')
           ->leftJoin('users','personnel.user_id', '=', 'users.id')
           ->leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
           ->select('personnel.*','departments.title','users.email as tk') 
           ->where(function ($query_2) use ($ids){
               if ( !empty($ids) ) {
                   $query_2->whereIn('personnel.department_id',$ids );
               }            
           })
           ->where(function ($query) use ($request){
               if ($request->hoten != '') {
                   $query->where('personnel.fullname', 'LIKE', '%'.trim($request->hoten).'%');
               }
               if ($request->email != '') {
                   $query->where('personnel.email','LIKE', '%'.trim($request->email).'%');
               }
               if ($request->phone != '') {
                   $query->where('personnel.phone_number','LIKE', '%'.trim($request->phone).'%');
               }    
           })
           ->where(function ($query_3){
                $query_3->where('date_out', '=', NULL)->orWhere('date_out', '>', date('Y-m-d'));            
            })
           ->where('personnel.status','=',1) 
           ->orderBy('id', 'desc')
           ->paginate(10);

       return $data;
   }


    public static function updateOwnedInfo($arr,$id){
        return DB::table('personnel')
            ->where('id', $id)
            ->update($arr);

    }
    public static function getCurrentWork($id){
        $data =  DB::table('personnel_working_histories')
                ->leftJoin('personnel','personnel_working_histories.personnels_ID', '=', 'personnel.id')
                ->leftJoin('departments', 'personnel_working_histories.department_id', '=', 'departments.id')
                ->leftJoin('job_titles', 'personnel_working_histories.job_id', '=', 'job_titles.id')
                ->select('personnel_working_histories.*','personnel.fullname','departments.title','job_titles.title as job')
                ->where('personnels_ID', $id)
                ->get();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $date_start = DateTime::createFromFormat('Y-m-d', $value->date_start);
                $date_end = DateTime::createFromFormat('Y-m-d', $value->date_end);
                $value->date_start = $date_start->format('d/m/Y');
                $value->date_end = $date_end->format('d/m/Y');
            }         
        }
        return $data;
    }
    public static function listRatio($id){
        $data = DB::table('personnel_job_ratio')
                ->select('personnel_job_ratio.*')
                ->where('personnel_ID', $id)
                ->get();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $apply_from = DateTime::createFromFormat('Y-m-d', $value->apply_from);
                $apply_to = DateTime::createFromFormat('Y-m-d', $value->apply_to);
                $value->apply_from = $apply_from->format('d/m/Y');
                $value->apply_to = $apply_to->format('d/m/Y');
            }         
        }
        return $data;  
    }
    public static function updateInfo($arr,$id){
        return DB::table('personnel')
            ->where('id',$id)
            ->update($arr);
    }

    //
    public static function listDepartment(){
       // DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        return DB::table('departments')->select('id','title','parent_id')->get();
        
    }

    public static function getDepartment($request){
       // DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        return DB::table('departments') ->select('id','title','parent_id','manager_id')
                                        ->where(function ($query) use ($request){
                                            if ($request->title != '') {
                                                $query->where('title', 'LIKE', '%'.trim($request->title).'%');
                                            }     
                                        })
                                        ->orderBy('id', 'desc')
                                        ->paginate(10);
        
    }

    public static function deletePersonnel($id){
         return DB::table('personnel')->where('id', '=', $id)->delete();
    }

    public static function updatePersonnel($arr,$id){
        return DB::table('personnel')
            ->where('id', $id)
            ->update($arr);
    }

    public static function listContracts( $personnel_id ){
        return DB::table('contract_personnel')->where('personnel_id',$personnel_id)->get();
    }

    public static function listFunds(){
        return DB::table('funds')->select('id','title')->where('status',1)->get();
    }

    public static function listJobs(){
        return DB::table('job_titles')->select('id','title')->where('status',1)->get();
    }

    public static function listJobsbyPersonnel($id){
        return DB::table('job_titles')
                ->join('personnel_title', 'personnel_title.job_title_id', '=', 'job_titles.id')
                ->join('personnel','personnel.id', '=', 'personnel_title.personnel_id')
                ->select('job_titles.id')
                ->where('personnel_title.personnel_id', $id)
                ->get();
    }
    public static function infoJobsbyPersonnel($id){
        $data =  DB::table('job_titles')
                ->join('personnel_title', 'personnel_title.job_title_id', '=', 'job_titles.id')
                ->select('job_titles.*')
                ->where('personnel_title.personnel_id', $id)
                ->get();

        if( $data ){
            $string = "";
            foreach ($data as $key => $value) {
                $string .= "- ". $value->title."</br>";
            }
            return $string;
        }else{
            return "";
        }
    }
    public static function listJobs2($select=0,$select2=0){
        $data = DB::table('job_titles')->select('id','title')->where('status',1)->get();

        $result = '<select name="selectJobs" class="form-control">
                        <option value=""> -- Chức danh -- </option>';
        foreach ($data as $value) {
            if ($select != 0 && $value->id == $select && $select2 == 0) {
                 $result .= '<option value="'.$value->id.'" selected="selected" >'.$value->title.'</option>' ;
            }else if($select2 != 0 && $value->id == $select2){
                $result .= '<option value="'.$value->id.'" selected="selected" >'.$value->title.'</option>';  
            }else{
                $result .= '<option value="'.$value->id.'">'.$value->title.'</option>'; 
            }
          
        }
        $result .='</select>';
        return $result;
    }

    public static function getPersonnelName($id){
        $name = '';
        $personnel_name = DB::table('personnel')->select('fullname')->where('id','=',$id)->first();
          if (!empty($personnel_name)) {
              $name = $personnel_name->fullname;
          }
        return $name;
    }

    public static function insertDepartment($arr){
        return DB::table('departments')->insert($arr);
    }

    public static function updateDepartment($arr,$id){
        return DB::table('departments')
            ->where('id', $id)
            ->update($arr);
    }

    public static function insertDepartmentsAttendance($arr){
        return DB::table('departments_attendance')->insert($arr);
    }

    public static function insertAdhocSalaryAssessment($arr){
        return DB::table('adhoc_salary_assessment')->insert($arr);
    }

    public static function deleteAdhocSalaryAssessment($year,$turns,$type){
        return DB::table('adhoc_salary_assessment')->where('year', '=', $year)->where('turns', '=', $turns)->where('type', '=', $type)->delete();
    }

    public static function deleteDepartmentsAttendance($departments_id){
        return DB::table('departments_attendance')->where('departments_id', '=', $departments_id)->delete();
    }

    public static function getInfoDepartment($id){
        return DB::table('departments')
                ->leftJoin('departments_attendance', 'departments_attendance.departments_id', '=', 'departments.id')
                ->select('departments.id','departments.title','departments.manager_id','departments.parent_id','departments_attendance.manage_id as manage_id_attendance')
                ->where('departments.id','=',$id)->get();
    
    }

    public static function checkPersonnelDepartment($id){
        return DB::table('personnel')->select('id','department_id')->where('department_id','=',$id)->first();
    }

    public static function checkChildrenDepartment($id){
        return DB::table('departments')->select('id','parent_id')->where('parent_id','=',$id)->first();
    }

    public static function deleteDepartment($id){
         return DB::table('departments')->where('id', '=', $id)->delete();
    }

    public static function getJobTitles($request){
       // DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        return DB::table('job_titles') ->select('id','title')
                                        ->where('status',1)
                                        ->where(function ($query) use ($request){
                                            if ($request->title != '') {
                                                $query->where('title', 'LIKE', '%'.trim($request->title).'%');
                                            }     
                                        })
                                        ->orderBy('id', 'desc')
                                        ->paginate(10);
        
    }


    public static function insertJobTitles($arr){
        return DB::table('job_titles')->insert($arr);
    }

    public static function updateJobTitles($arr,$id){
        return DB::table('job_titles')
            ->where('id', $id)
            ->update($arr);
    }

    public static function getInfoJobTitles($id){
        return DB::table('job_titles')->select('id','title')->where('id','=',$id)->first();
    
    }

    public static function deleteJobTitles($id){
         return DB::table('job_titles')->where('id', '=', $id)->delete();
    }

    public static function getContract($request){
        return DB::table('contract')    ->where('status',1)
                                        ->where(function ($query) use ($request){
                                            if ($request->title != '') {
                                                $query->where('title', 'LIKE', '%'.trim($request->title).'%');
                                            }     
                                        })
                                        ->orderBy('id', 'desc')
                                        ->paginate(10);
        
    }

    public static function getInfoContract($id){
        return DB::table('contract')->select('id','title','description','duration')->where('id','=',$id)->first();
    
    }

    public static function insertContract($arr){
        return DB::table('contract')->insert($arr);
    }

    public static function updateContract($arr,$id){
        return DB::table('contract')
            ->where('id', $id)
            ->update($arr);
    }

    public static function insertPersonnelTitle($arr){
        return DB::table('personnel_title')->insert($arr);
    }

    public static function deletePersonnelTitle($id){
         return DB::table('personnel_title')->where('personnel_id', '=', $id)->delete();
    }

    public static function insertContractPersonnel($arr){
        return DB::table('contract_personnel')->insert($arr);
    }

    public static function deleteContractPersonnel($id){
         return DB::table('contract_personnel')->where('personnel_id', '=', $id)->delete();
    }

    public static function insertPersonnelFunds($arr){
        return DB::table('funds_personnel')->insert($arr);
    }

    public static function updatePersonnelFunds($arr,$id){
        return DB::table('funds_personnel')
            ->where('id', $id)
            ->update($arr);
    }

    public static function deletePersonnelFunds($id){
         return DB::table('funds_personnel')->where('personnel_id', '=', $id)->delete();
    }

    public static function listFundsbyPersonnel($id){
        return DB::table('funds')
                ->join('funds_personnel', 'funds_personnel.funds_id', '=', 'funds.id')
                ->join('personnel','personnel.id', '=', 'funds_personnel.personnel_id')
                ->select('funds.id','funds_personnel.*')
                ->where('funds_personnel.personnel_id', $id)
                ->get();
    }

    public static function checkMaternityLeave($id,$personnel_id,$apply_from,$apply_to){
        $result = MaternityLeave::where(function ($query) use ($id){
                    if ($id != '') {
                        $query->whereNotIn('id', [$id]);
                    }     
                })
                ->where('personnel_id', $personnel_id)
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


    public static function checkFundsPersonnel($id,$personnel_id,$apply_from,$apply_to){
        $result = DB::table('funds_personnel')
                ->where(function ($query) use ($id){
                    if ($id != '') {
                        $query->whereNotIn('id', [$id]);
                    }     
                })
                ->where('status', 1)
                ->where('personnel_id', $personnel_id)
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

    public static function infoNumberLcbInYear($personnel_id,$year){
        return  \DB::table('personnel_job_ratio')
                ->where('personnel_ID',$personnel_id)
                ->where(function ($query) use ($year) {
                    $query->orwhere(\DB::raw("(DATE_FORMAT(apply_from,'%Y'))"),"=",$year);
                    $query->orwhere(\DB::raw("(DATE_FORMAT(apply_to,'%Y'))"),"=",$year);
                })
                ->orderBy('apply_from', 'asc')
                ->get();
    }


}
