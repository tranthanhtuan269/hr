<?php

namespace App\Models;
use DB;
use DateTime;
use App\Helpers\BatvHelper;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{ 
    protected $table = "personnel_attendance";
    public static function checkDayAttendance($attendance_day,$attendance_month,$attendance_year){
        return DB::table('personnel_attendance')
                    ->select('id')
                    ->where([
                        ['attendance_day', '=', $attendance_day],
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                    ])
                    ->count();
    }

    public static function checkAttendanceDaybyPer($attendance_day,$attendance_month,$attendance_year,$personnel_id){
        return DB::table('personnel_attendance')
                    ->select('id')
                    ->where([
                        ['attendance_day', '=', $attendance_day],
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->count();
    }

    public static function checkCurrentDayAttendance($request='',$created_at_format){
         return DB::table('personnel')
              ->leftJoin('personnel_attendance', 'personnel.id', '=', 'personnel_attendance.personnel_ID')
              ->select('personnel.id', 'personnel.fullname','personnel.email','personnel_attendance.*')
              ->where('personnel_attendance.created_at', '=', $created_at_format)
              ->where(function ($query) use ($request) {
                    if (!empty($request->selectDepart)) {
                       $query->where('personnel.department_id', '=', $request->selectDepart);
                     }
                })
              ->get();
    }

    public static function countPersonnel($date_in){
        return DB::table('personnel')->select('id')->where('status', '=', 1)->where('date_in', '<=', $date_in)->get();
    }

    public static function listAttendanceHistory( $attendance_day,$attendance_month,$attendance_year,$request='',$created_at_format='',$ids=array() ){
        if( $created_at_format!='' ){
         return DB::table('personnel')
              ->leftJoin('personnel_attendance', 'personnel.id', '=', 'personnel_attendance.personnel_ID')
              ->select('personnel.id as personnel_id', 'personnel.fullname','personnel.email','personnel_attendance.*')
              ->where([
                  ['personnel.status', '=', 1],
                  // ['personnel.date_in', '=', $created_at_format],
                  ['personnel_attendance.attendance_day', '=', $attendance_day],
                  ['personnel_attendance.attendance_month', '=', $attendance_month],
                  ['personnel_attendance.attendance_year', '=', $attendance_year],
              ])
              ->where(function ($query) use ($ids) {
                  if ( !empty($ids) ) {
                     $query->whereIn('personnel.department_id', $ids);
                   }
              })
              ->get();
        }
    }

    public static function listAttendance($ids='',$date_in){
       $data =  DB::table('personnel')
            ->select('id', 'fullname','email','department_id')
            ->where('status', '=', 1)
            ->where('date_in', '<=', $date_in)
            ->where(function ($query) use ($date_in) {
                $query->where('date_out', '=', NULL)
                      ->orWhere('date_out', '>', $date_in);
            })
            ->where(function ($query) use ($ids) {
                if ( !empty($ids) ) {
                   $query->whereIn('department_id', $ids);
                 }
            })
            ->get();
        if( $data ){
          // Danh sách nghỉ phép dài hạn( thường nghỉ theo tháng nhưng ko phải nghỉ hẳn )
          $convert = explode("-",$date_in);
          $param = $convert[0].'-'.$convert[1];
          // Danh sách miễn chấm công
          $item_1 = BatvHelper::infoUserIdExceptionalAttendance($param);
          $item_2 = DB::table('setting_absent_attendance')
                    ->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$param)
                    ->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$param)
                    ->pluck('personnel_id');
          $info = array_merge($item_1,$item_2);
          if( $info ){
            foreach ($data as $key => $value) {
              if( in_array($value->id, $info) ){
                unset($data[$key]);
              }
            }
          }
        }
        return $data;
    }

    public static function listAttendanceTrue($request=''){
       return DB::table('personnel')
            ->leftJoin('personnel_attendance', 'personnel.id', '=', 'personnel_attendance.personnels_ID')
            ->select('personnel.id', 'personnel.fullname','personnel.email','personnel_attendance.*')
            ->where(function ($query) use ($request) {
                if (!empty($request->date)) {
                   $query->where('personnel_attendance.created_at', '=', $request->date);
                 }
            })
            ->get();
    }

    public static function listAttendanceType( $work_type ){
      return DB::table('attendance_type')
           ->select('id','title','symbol')
           ->where('work_type',$work_type)
           ->where('status',1)
           ->get();
    }

    public static function listAttendanceType_2( $work_type ){
      return DB::table('attendance_type')
           ->select('id','title','symbol')
           ->where('work_type','<>',$work_type)
           ->get();
    }
    public static function listAttendanceInfo(){
      return DB::table('attendance_type')
           ->select('id','title','symbol','type')
           ->where('status',1)
           ->get();
    }

    public static function insertAttendance($arr){
        return DB::table('personnel_attendance')->insert($arr);
    }

    public static function updateAttendanceAll($attendance_day,$attendance_month,$attendance_year,$personnel_attendance_id,$arr){
        return DB::table('personnel_attendance')
            ->where([
                ['id', '=', $personnel_attendance_id],
                ['attendance_day', '=', $attendance_day],
                ['attendance_month', '=', $attendance_month],
                ['attendance_year', '=', $attendance_year],
                // ['param_check', '=', $param_check],
            ])
            ->update($arr);
    }

    public static function updateItemAttendance($personnel_id,$created_at,$arr){
        return DB::table('personnel_attendance')
            ->where('personnel_id',$personnel_id)
            ->where('created_at', '=', $created_at)
            ->update($arr);
    }

    public static function checkDayExits($date,$check_work){
       /* $check = 1;// được insert
        $check = 2;// đã chấm công
        $check = 3;// Phải chấm công đi làm trước*/
        $check = 1;
        $data3 =  DB::table('personnel_attendance')
            ->select('id')
            ->where('created_at',$date)
            ->where('check_work',$check_work)
            ->first();
        if(!empty($data3)){
            $check = 2;
        }
       
        return $check;
    }


    public static function listDepartment(){
       // DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        return DB::table('departments')->select('id','title','parent_id')->get();
        
    }

    public static function listInfoWork( $request,$ids=array() ){
      $month = date('m');
      if (!empty($request->selectMonth)) {
          $month = $request->selectMonth;
      }
      $year = date("Y");
      if (!empty($request->selectYear)) {
          $year = $request->selectYear;
      }
   
      $data = DB::table('personnel')
            ->leftJoin('personnel_attendance','personnel.id', '=', 'personnel_attendance.personnel_id')
            ->leftJoin('departments','personnel.department_id', '=', 'departments.id')
            ->leftJoin('maternity_leave', 'maternity_leave.personnel_id', '=', 'personnel.id')
            ->join('attendance_type','personnel_attendance.attendance_type_id','=','attendance_type.id')
            ->selectRaw("maternity_leave.join_insurance,maternity_leave.apply_from,maternity_leave.apply_to,personnel.fullname,personnel_attendance.*,GROUP_CONCAT(personnel_attendance.attendance_day SEPARATOR ',') as workday,GROUP_CONCAT( attendance_type.symbol SEPARATOR ',') as type,GROUP_CONCAT( personnel_attendance.attendance_type_id SEPARATOR ',') as attendance_type_id,GROUP_CONCAT( personnel_attendance.unit_date SEPARATOR ',') as unit_date")
            ->where('personnel_attendance.attendance_month', '=', $month)
            ->where('personnel_attendance.attendance_year', '=', $year)
            ->where(function ($query_2) use ($ids){
                if ( !empty($ids) ) {
                    $query_2->whereIn('personnel.department_id',$ids );
                }            
            })
            ->where(function ($query) use ($request) {
                 if (!empty($request->selectPersonnel) && $request->selectPersonnel != 0) {
                   $query->where('personnel.id', '=', $request->selectPersonnel);
                 }
            })
            ->groupBy('personnel.id')
            ->get();
            // echo "<pre>";
            // print_r($data);die;
            $listHolidays = DB::table('holiday_setting')->where('status',1)->where('month',$month)->whereIn('year', ['*', $year])->pluck('day');
            $newArrs = [];
            if( $listHolidays ){
              foreach ($listHolidays as $key => $value) {
                $newArrs[(int)$value] = 'Nl';
              }
            }

            if (!empty($data)) {
               foreach ($data as $key => $value) {
                  $search  = array('O', 'M');
                  $replace = array('X', 'X');
                  $str = str_replace($search, $replace, $value->type);
                  //str = str_replace('M', 'x', $value->type);
                  $unit_date =  explode(',', $value->unit_date);
                  $workday =  explode(',', $value->workday);
                  $type = explode(',', $str);

                  foreach ($workday as $k_workday => $v_workday) {
                      if( !isset($arr[$v_workday]) ){
                        if( $unit_date[$k_workday] == 0.5 ){
                          $arr[$v_workday] = [0=>"0.5".$type[$k_workday],];
                        }else{
                          $arr[$v_workday] = [0=>$type[$k_workday],];
                        }
                        
                      }else{
                        if( $unit_date[$k_workday] == 0.5 ){
                          $arr[$v_workday][] = "0.5".$type[$k_workday];
                        }else{
                          $arr[$v_workday][] = $type[$k_workday];
                        }
                        
                      }

                  }
                  $key_check  = array_keys($arr);
                  foreach ( $newArrs as $k_newArr => $v_newArr ) {
                    if( !in_array($k_newArr, $key_check ) ){
                      $arr[$k_newArr] = [0=>"Nl"];
                    }
                  }
                  ksort($arr);
                  $value->type = $arr;
                  unset($arr);
               }

            }

              // echo "<pre>";
              // print_r($data);die;
            return $data;

    }
    public static function listInfoWorkLate( $request,$ids=array() ){
      $month = date('m');
      if (!empty($request->selectMonth)) {
          $month = $request->selectMonth;
      }
      $year = date("Y");
      if (!empty($request->selectYear)) {
          $year = $request->selectYear;
      }
      $data = DB::table('personnel')
            ->leftJoin('personnel_attendance','personnel.id', '=', 'personnel_attendance.personnel_id')
            ->leftJoin('departments','personnel.department_id', '=', 'departments.id')
            ->selectRaw("personnel.fullname,personnel_attendance.*,GROUP_CONCAT(personnel_attendance.attendance_day SEPARATOR ',') as workday,GROUP_CONCAT( personnel_attendance.time_late SEPARATOR ',') as time_late,GROUP_CONCAT( personnel_attendance.attendance_type_id SEPARATOR ',') as attendance_type_id")
            ->where('personnel_attendance.attendance_month', '=', $month)
            ->where('personnel_attendance.attendance_year', '=', $year)
            ->where(function ($query_2) use ($ids){
                if ( !empty($ids) ) {
                    $query_2->whereIn('personnel.department_id',$ids );
                }            
            })
            ->where(function ($query) use ($request) {
                 if (!empty($request->selectPersonnel) && $request->selectPersonnel != 0) {
                   $query->where('personnel.id', '=', $request->selectPersonnel);
                 }
            })
            ->groupBy('personnel.id')
            ->get();
            if (!empty($data)) {
               foreach ($data as $key => $value) {
                  $time_late =  explode(',', $value->time_late);
                  $workday =  explode(',', $value->workday);
                  $attendance_type_id =  explode(',', $value->attendance_type_id);
                  $arr = array();
                  foreach ($workday as $k_workday => $v_workday) {
                      if( !isset($arr[$v_workday]) && $attendance_type_id[$k_workday] == 12 ){
                        $arr[$v_workday] = $time_late[$k_workday];
                      }
                  }

                  ksort($arr);
                  $value->time_late = $arr;
                  unset($arr);
               }

            }
            // echo "<pre>";
            // print_r($data);die;
        return $data;
    }

    // public static function listInfoWorkLate( $request,$ids=array() ){
    //   $month = date('m');
    //   if (!empty($request->selectMonth)) {
    //       $month = $request->selectMonth;
    //   }
    //   $year = date("Y");
    //   if (!empty($request->selectYear)) {
    //       $year = $request->selectYear;
    //   }
    //   $data = DB::table('personnel')
    //         ->leftJoin('personnel_attendance','personnel.id', '=', 'personnel_attendance.personnel_id')
    //         ->selectRaw("personnel.fullname,personnel_attendance.*,GROUP_CONCAT(EXTRACT(DAY FROM personnel_attendance.created_at) SEPARATOR ',') as mdate,GROUP_CONCAT( personnel_attendance.attendance_type_id SEPARATOR ',') as type")
    //         ->where("personnel_attendance.attendance_month", $month)
    //         ->where("personnel_attendance.attendance_year", $year)
    //         ->where(function ($query) use ($request) {
    //              if (!empty($request->selectPersonnel) && $request->selectPersonnel != 0) {
    //                $query->where('personnel.id', '=', $request->selectPersonnel);
    //              }
    //         })
    //         ->where(function ($query_2) use ($ids){
    //             if ( !empty($ids) ) {
    //                 $query_2->whereIn('personnel.department_id',$ids );
    //             }            
    //         })
    //         ->groupBy('personnel.id')
    //         ->get();
    //    if (!empty($data)) {
    //        foreach ($data as $key => $value) {
    //            $value->mdate = explode(',', $value->mdate);
    //            $value->type = explode(',', $value->type);
    //            $arr = array_combine($value->mdate,$value->type);
    //            ksort($arr);
    //            $value->type = $arr;
    //        }
    //     }
    //     return $data;
    // }

    //Thông tin nghỉ phép
    public static function getInfoWorkLeave( $request,$ids=array() ){

         $data = DB::table('personnel')
                ->join('personnel_attendance','personnel_attendance.personnel_id', '=','personnel.id' )
                ->selectRaw("personnel.id as personnel_id,personnel.fullname,personnel_attendance.*")
                ->where(function ($query) use ($request) {
                    if (!empty($request->selectYear)) {
                       $query->whereRaw("EXTRACT(YEAR FROM personnel_attendance.created_at) = ?", array($request->selectYear));
                      //$query->whereYear('personnel_attendance.created_at','=',$request->selectYear);

                     }else{
                       //$query->whereRaw("EXTRACT(YEAR FROM personnel_attendance.created_at) = ?", array(date('Y')));
                       $query->whereYear('personnel_attendance.created_at','=',date('Y'));
                     }
                })
                ->where(function ($query_2) use ($ids){
                    if ( !empty($ids) ) {
                        $query_2->whereIn('personnel.department_id',$ids );
                    }            
                })
                ->groupBy('personnel_attendance.personnel_id')
                ->get();
          return $data;
    }

    public static function getPersonnelFromDepart($id,$request,$ids=''){

       $data =  Personnel::getAllPersonnelCurrent();
       $result = [];

       foreach ($data as $key => $value) {
          if( $ids =='' ){
               $result[$value->id] = $value->fullname;
          }else{
            if( in_array($value->department_id, $ids) ){
              $result[$value->id] =  $value->fullname;
            }
          }
       }
       return $result;
    }


    public static function getPersonnelFromDepartAjax($id,$request,$ids=''){

       $data =  Personnel::getAllPersonnelCurrent();
       $result = '<option value="0">--Chọn nhân sự--</option>';

       foreach ($data as $key => $value) {
          if( $ids =='' ){

               if (!empty($request->selectPersonnel) && $request->selectPersonnel == $value->id) {
                   $result .= '<option value='.$value->id.' selected="selected" >'.$value->fullname.'</option>';
               }else{
                  $result .= '<option value='.$value->id.'>'.$value->fullname.'</option>';
               }
          }else{
            if( in_array($value->department_id, $ids) ){
               if (!empty($request->selectPersonnel) && $request->selectPersonnel == $value->id) {
                   $result .= '<option value='.$value->id.' selected="selected" >'.$value->fullname.'</option>';
               }else{
                  $result .= '<option value='.$value->id.'>'.$value->fullname.'</option>';
               }
            }
          }
       }
       return $result;
    }

    public static function  getInfoWorking( $request,$user_id,$ids=array() ){
      // echo $request->startDate;die;
          $personnel = DB::table('personnel')->select('id','fullname')->where('id','=',$user_id)->first();
          $data = '';
          if ($personnel != null) {
              $data = DB::table('personnel')
                  ->leftJoin('personnel_attendance','personnel.id', '=', 'personnel_attendance.personnel_id')
                  ->selectRaw("personnel.fullname,personnel_attendance.*")
                  ->where(function ($query) use ($request,$personnel) {
                      if (isset($request->selectPersonnel)) {
                         if ($request->selectPersonnel != 0) {
                            $query->where('personnel_attendance.personnel_id', '=', $request->selectPersonnel);  
                         }
                     }else{
                         $query->where('personnel_attendance.personnel_id','=',$personnel->id);
                     }
                     if (!empty($request->startDate)) {
                         $startDate = DateTime::createFromFormat('d/m/yy', $request->startDate);
                     }else{
                        $startDate = date('Y')."-".date('m')."-1";  
                     }

                     if (!empty($request->endDate)) {
                        $endDate = DateTime::createFromFormat('d/m/yy', $request->endDate);
                     }else{
                        $endDate = date('Y-m-d');
                     }

                     $query->whereBetween('personnel_attendance.created_at',[$startDate,$endDate]);
   
                  })
                  ->where(function ($query_2) use ($ids){
                      if ( !empty($ids) ) {
                          $query_2->whereIn('personnel.department_id',$ids );
                      }            
                  })
                  ->groupBy('personnel.id')
                  ->get();
                  // ->paginate(2);
                    //dd($data);
          }

           // echo "<pre>";
           // print_r($data);die;

          return $data;
    }

    public static function infoAttendancebyMonthYear( $personnel_id='',$month,$year,$type) {
        $data =  DB::table('personnel_attendance')
                    ->select(DB::raw('count(*) as count_infoAttendancebyMonthYear, status,personnel_id'))
                    ->where('status', '=', 1)
                    ->where('attendance_month', '=', $month)
                    ->where('attendance_year', '=', $year)
                    ->where('attendance_type_id', '=', $type)
                    ->where('personnel_id', '=', $personnel_id)
                    ->groupBy('personnel_id')
                    ->first();
        if( $data ){
          return $data->count_infoAttendancebyMonthYear;
        }else{
          return '';
        }
        
    }

    public static function infoAttendanceAll( $personnel_id='',$startDate,$endDate,$attendance_type_id,$type ) {
        $result = DB::table('personnel_attendance')
                    ->where('status', '=', 1)
                    ->whereIn('attendance_type_id', $attendance_type_id)
                    ->where('type', '=', $type)
                    ->whereBetween('date_value', [$startDate, $endDate])
                    ->where('personnel_id', '=', $personnel_id)
                    // ->groupBy('personnel_id')
                    ->get();
        $tmp = 0;
        if( count($result) >0  ){
            foreach ($result as $key => $value) {
                // Nếu đi làm 1 ngày 
                if( $value->unit_date == 1 ){
                    $tmp++; 
                }elseif( $value->unit_date == 0.5 ){
                    $tmp = $tmp+0.5;
                }
            
            }
        }
        return $tmp;
    }

    public static function dayWorkLeave( $personnel_id='',$startDate,$endDate,$attendance_type_id,$type='' ) {
        $result =  DB::table('personnel_attendance')
                    ->where('status', '=', 1)
                    ->whereNotIn('attendance_type_id', $attendance_type_id)
                    ->whereBetween('date_value', [$startDate, $endDate])
                    ->where('personnel_id', '=', $personnel_id)
                    ->where(function ($query) use ($type){
                        if ( !empty($type) ) {
                            $query->where('type',$type );
                        }            
                    })
                    // ->groupBy('personnel_id')
                    ->get();
        $tmp = 0;
        if( count($result) >0  ){
            foreach ($result as $key => $value) {
                // Nếu đi làm 1 ngày 
                if( $value->unit_date == 1 ){
                    $tmp++; 
                }elseif( $value->unit_date == 0.5 ){
                    $tmp = $tmp+0.5;
                }
            
            }
        }
        return $tmp;
    }

    public static function getAllPersonnelId($date_in) {
        return DB::table('personnel')
                  ->select('id')
                  ->where('status', '=', 1)
                  ->where('date_in', '<=', $date_in)
                  ->where(function ($query) use ($date_in) {
                      $query->where('date_out', '=', NULL)
                            ->orWhere('date_out', '>', $date_in);
                  })
                  ->get();
    }

    public static function listHolidays( $param_month ){
        return DB::table('holiday_setting')->select('id','day','month','year')->where('month', '=', $param_month)->where('status', '=',1)->get();
    }

    public static function checkDayWorkLeave( $month,$year ){
      return  DB::table('personnel_attendance')
         ->select('id')
         ->where('attendance_month','=',$month)
         ->where('attendance_year','=',$year)
         ->where('attendance_type_id','=',11)
         ->count();
    }

    public static function insertAttendanceSymbol($arr){
        return DB::table('attendance_type')->insert($arr);
    }

    public static function updateAttendanceSymbol($arr,$id){
        return DB::table('attendance_type')
            ->where('id',$id)
            ->update($arr);
    }
    public static function infoAttendanceType($id){
      return  DB::table('attendance_type')
         ->select('type','symbol')
         ->where('id','=',$id)
         ->first();
    }

    public static function infoAttendanceSymbol( $id ){
        return DB::table('attendance_type')
                    ->select('title','symbol')
                    ->where([
                        ['id', '=', $id],
                    ])
                    ->first();
    }

    public static function infoDepartmentsAttendance( $manage_id ){
        return DB::table('departments_attendance')
                    ->where([
                        ['manage_id', '=', $manage_id],
                    ])
                    // ->orderBy('departments_id', 'asc')
                    ->get();
    }

    public static function delAttendanceItem( $attendance_day,$attendance_month,$attendance_year,$id ){
        return DB::table('personnel_attendance')
                    ->where([
                        ['id', '=', $id],
                        ['attendance_day', '=', $attendance_day],
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                    ])
                    ->delete();
    }

    public static function checkAttendanceStatus($month,$year){
        $check =  DB::table('personnel_income')
                    ->select('id')
                    ->where([
                        ['month', '=', $month],
                        ['year', '=', $year],
                    ])
                    ->count();
        if( $check >0 ){
          return  DB::table('personnel_income')
                    ->select('id')
                    ->where([
                        ['month', '=', $month],
                        ['year', '=', $year],
                        ['status', '=', 1],
                    ])
                    ->count();
        }else{
          return 1;
        }
    }

}
