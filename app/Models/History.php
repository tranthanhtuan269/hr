<?php

namespace App\Models;
use DB;
use DateTime;
class History
{
    public static function listHistory($request,$ids=array()){
       return DB::table('personnel')
        ->leftJoin('users','personnel.user_id', '=', 'users.id')
        ->leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
        ->select('personnel.id', 'departments.title','personnel.fullname','personnel.email')
        ->where('personnel.status','=', 1)
        ->where(function ($query_2) use ($ids){
            if ( !empty($ids) ) {
                $query_2->whereIn('personnel.department_id',$ids );
            }            
        })
        ->where(function ($query) use ($request){
            if ($request->hoten != '') {
                $query->where('personnel.fullname', 'LIKE', '%'.trim($request->hoten).'%');
            }  
        })
        ->paginate(10);
    }
    public static function listDepartment(){
       // DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        return DB::table('departments')->select('id','title','parent_id')->get();
        
    }
    public static function detailHistory($id){
    	$data = DB::table('personnel_working_histories')
    	        ->join('departments', 'personnel_working_histories.department_id', '=', 'departments.id')
    	        ->join('job_titles', 'personnel_working_histories.job_id', '=', 'job_titles.id')
    	        ->select('personnel_working_histories.*','departments.title','job_titles.title as job')
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
    public static function getNamePersonnel($id){
        $data =  DB::table('personnel')->select('fullname')->where('id',$id)->first();
        if (!empty($data)) {
           return $data->fullname;
        }else{
            return '';
        }

    }
    public static function listJobs(){
        return DB::table('job_titles')->select('id','title')->where('status',1)->get();
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

    public static function insertHistory($arr){
        return DB::table('personnel_working_histories')->insert($arr);
    }

    public static function getHistoryFromId($id){
        $data =  DB::table('personnel_working_histories')->where('status',1)->where('id',$id)->first();
        if (!empty($data)) {
            $date_start = DateTime::createFromFormat('Y-m-d', $data->date_start);
            $date_end = DateTime::createFromFormat('Y-m-d', $data->date_end);
            $data->date_start = $date_start->format('d/m/Y');
            $data->date_end = $date_end->format('d/m/Y');
        }
        return $data;
    }
    public static function updateWorkHistory($arr,$id){
        return DB::table('personnel_working_histories')
            ->where('id',$id)
            ->update($arr);
    }

    public static function deleteWorkHistory($id){
         return DB::table('personnel_working_histories')->where('id', '=', $id)->delete();
    }
    public static function listRation($id){
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

    public static function insertRatio($arr){
        return DB::table('personnel_job_ratio')->insert($arr);
    }

    public static function checkinsertRatio($date='',$id){
        return DB::table('personnel_job_ratio')
            ->where('personnel_ID','=',$id)
            ->where('status','=',1)
            ->where(function ($query) use ($date){
                if ($date != '') {
                    $query->where('apply_to','<',$date);
                }
            })
            ->first();
    }

    public static function checkUpdateRatio($startDate='',$endDate='',$id='',$personnel_ID=''){
        return DB::table('personnel_job_ratio')
                    ->whereNotBetween('apply_from', array($startDate,$endDate))
                    ->whereNotBetween('apply_to', array($startDate,$endDate))
                    ->where('id','<>',$id)
                    ->where('personnel_ID','=',$personnel_ID)
                    ->get();
    }

    public static function getRatioFromId($id){
        $data =  DB::table('personnel_job_ratio')->where('status',1)->where('id',$id)->first();
        if (!empty($data)) {
            $apply_from = DateTime::createFromFormat('Y-m-d', $data->apply_from);
            $apply_to = DateTime::createFromFormat('Y-m-d', $data->apply_to);
            $data->apply_from = $apply_from->format('d/m/Y');
            $data->apply_to = $apply_to->format('d/m/Y');
        }
        return $data;
    }
    public static function updateRatio($arr,$id){
        return DB::table('personnel_job_ratio')
            ->where('id',$id)
            ->update($arr);
    }

    public static function deleteRatio($id){
         return DB::table('personnel_job_ratio')->where('id', '=', $id)->delete();
    }


}
