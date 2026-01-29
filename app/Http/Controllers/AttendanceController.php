<?php

namespace App\Http\Controllers;

use Auth;
use DateTime;
use Validator;
use Mail; 
use App\Http\Requests;
use App\Models\Attendance;
use App\Models\Personnel;
use App\Models\ExceptionalAttendance;
use App\Mylibs\Myfunction;
use Illuminate\Http\Request;
use App\Helpers\BatvHelper;
use Illuminate\Support\Collection;
use App\Models\EmailConfig;
use App\Models\AgentLog;

class AttendanceController extends Controller
{
    public function cronjobManual(Request $request){
        $date = '2026-01-14';
        $data = AgentLog::infobyTime(strtotime($date));
        // print_r($data);die;
        if( $data ){    
            $getAllPersonnelCurrent = Attendance::listAttendance( $ids='',$date );
            $arr_listPersonalID = array();
            if( $getAllPersonnelCurrent ){
                foreach ($getAllPersonnelCurrent as $key => $value) {
                    $arr_listPersonalID[$value->id] = $value->id;
                }
            }

            foreach ($data as $key => $value) {
                $personnel_id = (int)$value->UserID;
                $time_attendance_machine = BatvHelper::infoPersonnelSpecial($personnel_id,'time_attendance_machine');
                $time_late = 0;
                $attendance_type_id = 1;
                $attendance_day = BatvHelper::formatDate($value->minDate,'Y-m-d H:i:s',$formatDate='d',$timeFormat='H:i:s',$time=false);
                $attendance_month = BatvHelper::formatDate($value->minDate,'Y-m-d H:i:s',$formatDate='m',$timeFormat='H:i:s',$time=false);
                $attendance_year = BatvHelper::formatDate($value->minDate,'Y-m-d H:i:s',$formatDate='Y',$timeFormat='H:i:s',$time=false);
                $date_value = BatvHelper::formatDate($value->minDate,'Y-m-d H:i:s',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $timestamp = BatvHelper::formatDate($value->minDate,'Y-m-d H:i:s',$formatDate='H:i:s',$timeFormat='H:i:s',$time=false);

                if( BatvHelper::handlingTime( $timestamp ) <= BatvHelper::handlingTime( '12:00:00' ) ){
                    if( BatvHelper::handlingTime( $timestamp ) > BatvHelper::handlingTime( $time_attendance_machine ) ){
                        $time_late = round( ( strtotime( $timestamp ) - strtotime( $time_attendance_machine ) )/60 );
                        $attendance_type_id = (  $time_late >= 1)?12:2;
                    }

                    $item = new Attendance;
                    $item->time_late = $time_late;
                    $item->unit_date = 1;
                    $item->attendance_type_id = $attendance_type_id;
                    $item->type = 1;
                    $item->personnel_id = $personnel_id;
                    $item->attendance_day = $attendance_day;
                    $item->attendance_month = $attendance_month;
                    $item->attendance_year = $attendance_year;
                    $item->param_check = 0;
                    $item->date_value = $date_value;
                    $item->status =  1;
                    $item->save();
                }else{
                    if( BatvHelper::handlingTime( $timestamp ) > BatvHelper::handlingTime( '13:20:00' ) ){
                        $time_late = round( ( strtotime( $timestamp ) - strtotime( '13:20:00' ) )/60 );
                        $attendance_type_id = (  $time_late >= 1)?12:2;
                    }

                    $item = new Attendance;
                    $item->time_late = $time_late;
                    $item->unit_date = 0.5;
                    $item->attendance_type_id = $attendance_type_id;
                    $item->type = 1;
                    $item->personnel_id = $personnel_id;
                    $item->attendance_day = $attendance_day;
                    $item->attendance_month = $attendance_month;
                    $item->attendance_year = $attendance_year;
                    $item->param_check = 0;
                    $item->date_value = $date_value;
                    $item->status =  1;
                    $item->save();

                    $item_2 = new Attendance;
                    $item_2->unit_date = 0.5;
                    $item_2->attendance_type_id = 4;
                    $item_2->type = 0;
                    $item_2->personnel_id = $personnel_id;
                    $item_2->attendance_day = $attendance_day;
                    $item_2->attendance_month = $attendance_month;
                    $item_2->attendance_year = $attendance_year;
                    $item_2->param_check = 1;
                    $item_2->date_value = $date_value;
                    $item_2->status =  1;
                    $item_2->save();
                }
                unset($arr_listPersonalID[$personnel_id]);
            }
            if( count( $arr_listPersonalID ) >0 ){
                foreach ($arr_listPersonalID as $key => $value) {
                    $item_3 = new Attendance;
                    $item_3->unit_date = 1;
                    $item_3->attendance_type_id = 4;
                    $item_3->type = 0;
                    $item_3->personnel_id = $key;
                    $item_3->attendance_day = $attendance_day;
                    $item_3->attendance_month = $attendance_month;
                    $item_3->attendance_year = $attendance_year;
                    $item_3->param_check = 0;
                    $item_3->date_value = $date_value;
                    $item_3->status =  1;
                    $item_3->save();
                }
            }
        }
    }

    public function insertManual(Request $request){
        $url = 'https://toh.dahahi.vn/api/facereg/checkinhis';

        $data = [
            "FromTimeStr" => "15/01/2026 00:00:00",
            "ToTimeStr"   => "15/01/2026 23:59:59",
            "pageIndex"   => 1,
            "PageSize"    => 100
        ];

        $headers = [
            'AppKey: k77hcPwEXB4LTdJv96mz',
            'SecretKey: gvehhoyhpshtqwshcqxn',
            'Content-Type: application/json',
            // 'Cookie: ARRAffinity=c11d8c0ebf3e5d8d9ecc886bc5d6dcef904832e66f02997c838a28090cc05cab; ASP.NET_SessionId=ezthfslutr4s1ajrjm244mpy'
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            // echo $response; // JSON response
            // print_r($response);die;
            $responseArr = json_decode($response, true);

            // kiểm tra response hợp lệ
            if (
                empty($responseArr) ||
                $responseArr['ErrorCode'] !== '000000' ||
                empty($responseArr['Data'])
            ) {
                return;
            }

            // print_r($responseArr['Data']);die;

            foreach ($responseArr['Data'] as $item) {

                // parse thời gian: 12/01/2026 07:20:24
                $dt = DateTime::createFromFormat('d/m/Y H:i:s', $item['CheckinTime1Str']);
                if (!$dt) {
                    // sai format → bỏ qua bản ghi
                    continue;
                }

                $checkinTime = $dt->format('Y-m-d H:i:s');
                $userId = $item['FacePersonId'];

                // kiểm tra trùng
                $exists = AgentLog::where('UserID', $userId)
                    ->where('Timestamp', $checkinTime)
                    ->where('EventTrigger', 'IN')
                    ->count();

                if ($exists > 0) {
                    continue;
                }

                // thêm bản ghi
                $agentLog = new AgentLog();
                $agentLog->Timestamp         = $checkinTime;
                $agentLog->Timestamp_Server  = $checkinTime;
                $agentLog->Timestamp_ACTAtek = $checkinTime;
                $agentLog->EventTrigger      = 'IN';
                $agentLog->OriginalSN        = '00111DB02CAE';
                $agentLog->SenderSN          = '00111DB02CAE';
                $agentLog->Remark            = '#FP#';
                $agentLog->AccessMethod      = 'FP';
                $agentLog->PhotoPath         = !empty($item['LiveImageUrl'])
                                                ? $item['LiveImageUrl']
                                                : 'no_image.gif';
                $agentLog->UserID            = $userId;

                $agentLog->save();
            }
        }

        curl_close($ch);
    }

    public function insertAttendance(Request $request){

        $filename = 'abc.txt';
        $content  = "--------------------------------------------------" . PHP_EOL;
        $content .= "CheckinTime: {$request->CheckinTime}" . PHP_EOL;
        $content .= "FacePersionId: {$request->FacePersonId}" . PHP_EOL;
        $content .= "EmployeeCode: {$request->EmployeeCode}" . PHP_EOL;
        $content .= "--------------------------------------------------" . PHP_EOL;
        // $content .= json_encode($request->all(), JSON_PRETTY_PRINT) . PHP_EOL;

        // Ghi thêm vào file
        file_put_contents($filename, $content, FILE_APPEND);

        // thêm bản ghi vào
        $agentLog = new AgentLog;
        $agentLog->Timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $request->CheckinTime)->format('Y-m-d H:i:s');
        $agentLog->Timestamp_Server = DateTime::createFromFormat('d/m/Y H:i:s', $request->CheckinTime)->format('Y-m-d H:i:s');
        $agentLog->Timestamp_ACTAtek = DateTime::createFromFormat('d/m/Y H:i:s', $request->CheckinTime)->format('Y-m-d H:i:s');
        $agentLog->EventTrigger = 'IN';
        $agentLog->OriginalSN = '00111DB02CAE';
        $agentLog->SenderSN = '00111DB02CAE';
        $agentLog->Remark = '#FP#';
        $agentLog->AccessMethod = 'FP';
        $agentLog->PhotoPath = 'no_image.gif';
        $agentLog->UserID = $request->FacePersonId;
        // $agentLog->UserID = 190; // test với user tuantt
        $agentLog->save();

    }
    
    public function getAttendanceList(Request $request){
        $depart = Attendance::listDepartment();
        $myfunc =  new Myfunction();
        $id = Auth::user()->id;
        if( $id != 1){
            $arr_departments_id = \DB::table('departments_attendance')->where('manage_id', $id)->pluck('departments_id');
            $select_depart = $myfunc->callProcessSelectAttendance($depart,0,'',0,$arr_departments_id);
            $select = min($arr_departments_id);

            if ($request->input('selectDepart') !='') {
                $select = $request->input('selectDepart');
            }
        }else{
            $select = 0;
            if ($request->input('selectDepart') !='') {
                $select = $request->input('selectDepart');
            }
            $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        }

        //KTra xem ngày hiện tại hoặc ngày tìm kiếm đã có trong bảng chấm công chưa
        if( $request->date != '' ){
            $date_field = DateTime::createFromFormat('d/m/yy', $request->date); 
            $param = $date_field->format('Y-m-d');

            $getDate = $request->date;
            $getDate = explode("/",$getDate);
            $yearCurrent = $getDate[2];
            $monthCurrent = $getDate[1];
            $dayCurrent = $getDate[0];
        }else{
            $param = date('Y-m-d');

            $yearCurrent = date('Y');
            $monthCurrent = date('m');
            $dayCurrent = date('d');
        }
        $date_in = $yearCurrent.'-'.$monthCurrent.'-'.$dayCurrent;
        // Nếu tháng đó chốt lương rồi thì ko được cập nhật lại Chấm công
        $checkAttendanceStatus = Attendance::checkAttendanceStatus( (int)$monthCurrent,(int)$yearCurrent );
        if( $checkAttendanceStatus >0 ){
            $paramAttendanceStatus = 1;// được cập nhật
        }else{
            $paramAttendanceStatus = 0;// không được cập nhật
        }
        
        //Kiểm tra xem chấm công  có phải ngày thứ 7 không
        $workdays_Saturday = BatvHelper::getSaturday($monthCurrent, $yearCurrent);

        if( in_array($dayCurrent, $workdays_Saturday) ){
            $check_saturday = 0;
        }else{
            $check_saturday = 1;
        }

        $check = Attendance::checkDayAttendance( $dayCurrent,$monthCurrent,$yearCurrent );
        //$countPersonnel = Attendance::countPersonnel($date_in);
        if( $check >0 ){

            if( isset($_GET['search']) ){ 
                $rules = [
                    'date' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
                ];
                $messages = [
                    'date.required'=>'Bạn chưa nhập ngày',
                    'date.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
                    'date.regex'=> 'Định dạng ngày phải là dd/mm/yyyy'
                ];
                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                } else {
                    $date_field = DateTime::createFromFormat('d/m/yy', $request->date); 
                    $created_at_format = $date_field->format('Y-m-d');
                    
                    if( $request->selectDepart !=0 ){
                        $myfunc =  new Myfunction();
                        // $tmp = $myfunc->categoryChild($request->selectDepart);
                        $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
                        if( count($tmp)==0 ){
                            $ids = array($request->selectDepart);
                        }else{
                            $ids =  BatvHelper::array_keys_multi($tmp);
                        }

                        $arr = Attendance::listAttendanceHistory($dayCurrent,$monthCurrent,$yearCurrent,$request,$created_at_format,$ids);
                        $listPersonnelNew = Attendance::listAttendance($ids,$date_in);
                    }else{
                        $arr = Attendance::listAttendanceHistory($dayCurrent,$monthCurrent,$yearCurrent,$request,$created_at_format);
                        $listPersonnelNew = Attendance::listAttendance($ids='',$date_in);
                    }

                    $check_attendance  = array();
                    $data = array();
                 
                    foreach ($arr as $key => $value) {
                       if( !isset(  $data[$value->personnel_id] ) ){
                            $data[$value->personnel_id] = array(
                                                            'id'=>$value->personnel_id,
                                                            'created_at'=>$value->created_at,
                                                            'fullname'=> $value->fullname,
                                                            'email'=> $value->email,
                                                            'personnel_attendance_id'=> array(
                                                                                        0=>$value->id,
                                                                                    ),
                                                            'time_late'=> array(
                                                                                    0=>$value->time_late,
                                                                                ),
                                                            'unit_date'=>  array(
                                                                                    0=>$value->unit_date,
                                                                                ),
                                                            'attendance_type_id'=> array(
                                                                                        0=>$value->attendance_type_id,
                                                                                    )
                                                        );
                            $check_attendance[] = $value->personnel_id;
                       }else{
                            $data[$value->personnel_id] ['personnel_attendance_id'][] = $value->id;
                            $data[$value->personnel_id] ['time_late'][] = $value->time_late;
                            $data[$value->personnel_id] ['unit_date'][] = $value->unit_date;
                            $data[$value->personnel_id] ['attendance_type_id'][] = $value->attendance_type_id;
                       }
                       ksort($data);
                    }
                    // echo '<pre>';
                    // print_r($data);die;
                    // Lấy ra những nhân viên chưa được chấm công 
                    foreach ($listPersonnelNew as $key => $value) {
                        if( in_array($value->id, $check_attendance) ){
                            unset($listPersonnelNew[$key]);
                        }
                    }

                    $result = $data;

                    $info =  Attendance::listAttendanceInfo();  
                    //Chấm công đi làm
                    $listType_1 = Attendance::listAttendanceType( $work_type=0 ); 
                    //Chấm công đi muộn
                    $listType_3 = Attendance::listAttendanceType( $work_type=1 );
                    return view('layouts.chamcong.history',['data'=>$result,'listType_1'=>$listType_1,'listType_3'=>$listType_3,'info'=>$info,'department'=>$select_depart,'check_saturday'=>$check_saturday,'paramAttendanceStatus'=>$paramAttendanceStatus,'listPersonnelNew'=>$listPersonnelNew  ]);
                }


            }else{

                $created_at_format = $param ;
                if( $id != 1){
                    $myfunc =  new Myfunction();
                    $tmp[$select] = $myfunc->categoryChild($select,'departments');
                    if( count($tmp)==0 ){
                        $ids = array($select);
                    }else{
                        $ids =  BatvHelper::array_keys_multi($tmp);
                    }
                    $arr = Attendance::listAttendanceHistory($dayCurrent,$monthCurrent,$yearCurrent,$request,$created_at_format,$ids);
                    $listPersonnelNew = Attendance::listAttendance($ids,$date_in);

                }else{
                    $arr = Attendance::listAttendanceHistory($dayCurrent,$monthCurrent,$yearCurrent,$request,$created_at_format);
                    $listPersonnelNew = Attendance::listAttendance($ids='',$date_in);
                }

                $check_attendance  = array();
                $data = array();
                foreach ($arr as $key => $value) {
                   if( !isset(  $data[$value->personnel_id] ) ){
                        $data[$value->personnel_id] = array(
                                                        'id'=>$value->personnel_id,
                                                        'created_at'=>$value->created_at,
                                                        'fullname'=> $value->fullname,
                                                        'email'=> $value->email,
                                                        'personnel_attendance_id'=> array(
                                                                                    0=>$value->id,
                                                                                ),
                                                        'time_late'=> array(
                                                                                0=>$value->time_late,
                                                                            ),
                                                        'unit_date'=>  array(
                                                                                0=>$value->unit_date,
                                                                            ),
                                                        'attendance_type_id'=> array(
                                                                                    0=>$value->attendance_type_id,
                                                                                )
                                                    );
                        $check_attendance[] = $value->personnel_id;
                   }else{
                        $data[$value->personnel_id] ['personnel_attendance_id'][] = $value->id;
                        $data[$value->personnel_id] ['time_late'][] = $value->time_late;
                        $data[$value->personnel_id] ['unit_date'][] = $value->unit_date;
                        $data[$value->personnel_id] ['attendance_type_id'][] = $value->attendance_type_id;
                   }
                   ksort($data);
                }
                // Lấy ra những nhân viên chưa được chấm công 
                foreach ($listPersonnelNew as $key => $value) {
                    if( in_array($value->id, $check_attendance) ){
                        unset($listPersonnelNew[$key]);
                    }
                }
                // echo "<pre>";
                // print_r($listPersonnelNew);die;
                $result = BatvHelper::PagingDataSpecial($data);

                $info =  Attendance::listAttendanceInfo();  
                //Chấm công đi làm
                $listType_1 = Attendance::listAttendanceType( $work_type=0 ); 
                //Chấm công đi muộn
                $listType_3 = Attendance::listAttendanceType( $work_type=1 );
                // echo "<pre>";
                // print_r($data);die;
                return view('layouts.chamcong.history',['data'=>$result,'listType_1'=>$listType_1,'listType_3'=>$listType_3,'info'=>$info,'department'=>$select_depart,'check_saturday'=>$check_saturday,'paramAttendanceStatus'=>$paramAttendanceStatus,'listPersonnelNew'=>$listPersonnelNew ]);
            }
        }else{
            if( isset($_GET['search']) ){
            // echo 1;die;

                $date_field = DateTime::createFromFormat('d/m/yy', $request->date); 
                $created_at_format = $date_field->format('Y-m-d');
                $check = Attendance::checkDayAttendance( $dayCurrent,$monthCurrent,$yearCurrent );
                //$checkCurrentAtt = Attendance::checkCurrentDayAttendance($request,$created_at_format);
                //echo  count($checkCurrentAtt);die;
                if( $check >0 ){
                    $arr = Attendance::listAttendanceHistory($dayCurrent,$monthCurrent,$yearCurrent,$request,$created_at_format);
                    $data = array();
                    foreach ($arr as $key => $value) {
                       if( !isset(  $data[$value->personnel_id] ) ){
                            $data[$value->personnel_id] = array(
                                                            'id'=>$value->personnel_id,
                                                            'created_at'=>$value->created_at,
                                                            'fullname'=> $value->fullname,
                                                            'email'=> $value->email,
                                                            'time_late'=> $value->time_late,
                                                            'unit_date'=> $value->unit_date,
                                                            'attendance_type_id'=> array(
                                                                                        'type'=>$value->attendance_type_id,
                                                                                    )
                                                        );
                       }
                       ksort($data);
                    }

                    $result = BatvHelper::PagingDataSpecial($data);
                    // echo "<pre>";
                    // print_r($result);die;
                    $info =  Attendance::listAttendanceInfo();  
                    //Chấm công đi làm
                    $listType_1 = Attendance::listAttendanceType( $work_type=0 ); 
                    //Chấm công đi muộn
                    $listType_3 = Attendance::listAttendanceType( $work_type=1 );
                    // echo "<pre>";
                    // print_r($data);die;
                    return view('layouts.chamcong.history',['data'=>$result,'listType_1'=>$listType_1,'listType_3'=>$listType_3,'info'=>$info,'department'=>$select_depart,'check_saturday'=>$check_saturday,'paramAttendanceStatus'=>$paramAttendanceStatus ]);
                }else{

                    if( $request->selectDepart !=0 ){
                        //echo 1;die;
                        $myfunc =  new Myfunction();
                        $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
                        if( count($tmp)==0 ){
                            $ids = array($request->selectDepart);
                        }else{
                            $ids =  BatvHelper::array_keys_multi($tmp);
                        }
                        
                        $data = Attendance::listAttendance($ids,$date_in);
                    }else{
                        $data = Attendance::listAttendance($ids='',$date_in);
                    }

                    $info =  Attendance::listAttendanceInfo();  
                    //Chấm công đi làm
                    $listType_1 = Attendance::listAttendanceType( $work_type=0 ); 
                    //Chấm công đi muộn
                    $listType_3 = Attendance::listAttendanceType( $work_type=1 );
                    return view('layouts.chamcong.index',['data'=>$data,'listType_1'=>$listType_1,'listType_3'=>$listType_3,'info'=>$info,'department'=>$select_depart,'check_saturday'=>$check_saturday,'paramAttendanceStatus'=>$paramAttendanceStatus ]); 
                }
            }else{
                // echo 1;die;
                if( $id != 1){
                    $myfunc =  new Myfunction();
                    $tmp[$select] = $myfunc->categoryChild($select,'departments');

                    if( count($tmp)==0 ){
                        $ids = array($request->selectDepart);
                    }else{
                        $ids =  BatvHelper::array_keys_multi($tmp);
                    }
                    $data = Attendance::listAttendance($ids,$date_in);
                    
                }else{
                    $data = Attendance::listAttendance($ids='',$date_in);
                }
                
                $info =  Attendance::listAttendanceInfo();  
                //Chấm công đi làm
                $listType_1 = Attendance::listAttendanceType( $work_type=0 ); 
                //Chấm công đi muộn
                $listType_3 = Attendance::listAttendanceType( $work_type=1 );
                return view('layouts.chamcong.index',[ 'data'=>$data,'listType_1'=>$listType_1,'listType_3'=>$listType_3,'info'=>$info,'department'=>$select_depart,'check_saturday'=>$check_saturday,'paramAttendanceStatus'=>$paramAttendanceStatus ] );            
            }

        }
    }

    public function postAttendanceList(Request $request){
        if( $request->date !='' ){
            $date_field = DateTime::createFromFormat('d/m/yy', $request->date); 
            $created_at_format = $date_field->format('Y-m-d');
            $getDate = $request->date;
            $getDate = explode("/",$getDate);
            $yearCurrent = $getDate[2];
            $monthCurrent = $getDate[1];
            $dayCurrent = $getDate[0];
        }else{
            $created_at_format = date('Y-m-d');
            $yearCurrent = date('Y');
            $monthCurrent = date('m');
            $dayCurrent = date('d');
        }

        $param_convert = explode("-",$created_at_format);
        $param_year = $param_convert[0];
        $param_month = $param_convert[1];
        $param_day = $param_convert[2];
        $date_in =  $yearCurrent.'-'.$monthCurrent.'-'.$dayCurrent;
        $listHolidays = Attendance::listHolidays($param_month);

        $listDayAttendanceTrue = array();
        if( count($listHolidays) >0 ){
            foreach ($listHolidays as $key => $value) {
                if( $value->year =="*" ){
                    $listDayAttendanceTrue[] = $param_year.'-'.$value->month.'-'.$value->day;
                }else{
                    $listDayAttendanceTrue[] = $value->year.'-'.$value->month.'-'.$value->day;
                }
            }
        }

        $numberDay = cal_days_in_month(CAL_GREGORIAN,$param_month,$param_year);
        $begin  = new DateTime($param_year.'-'.$param_month.'-'.'01');
        $end    = new DateTime($param_year.'-'.$param_month.'-'.$numberDay);
        while ($begin <= $end) 
        {
            if($begin->format("D") == "Sun") 
            {
                $listDayAttendanceTrue[] = $begin->format("Y-m-d");
            }
            $begin->modify('+1 day');
        }
        // Nếu chấm công vào ngày nghỉ hoặc sau ngày hiện tại thì báo lỗi
        if( in_array( $created_at_format, $listDayAttendanceTrue) || $created_at_format > date('Y-m-d') ){
            return redirect()->back()->with(['flash_message_err' => 'Bạn không thể chấm công cho ngày nghỉ hoặc sau ngày hiện tại']);
        }else{

            //$checkCurrentAtt = Attendance::checkCurrentDayAttendance($request,$created_at_format);

            $check = Attendance::checkDayAttendance( $dayCurrent,$monthCurrent,$yearCurrent );

            // Kiểm tra xem quản lý chấm công đó có quản lý phòng ban đó không
            $check_manager_department = TRUE;
            $user_id = Auth::user()->id;

            if( $user_id != 1){

                if ($request->input('selectDepart') != '') {
                    $arr_departments_id = \DB::table('departments_attendance')->where('manage_id', $user_id)->pluck('departments_id');

                    if (!in_array($request->input('selectDepart'), $arr_departments_id)) {
                        $check_manager_department = FALSE;
                    }

                }

            }

            if ($check_manager_department == TRUE) {
                if( $check>0 ){
                 
                    if( $request->typeAttendance_1 !='' ){
                        if( $request->typeAttendance_1_add_case !='' ){
                            $arr_1_1 = $request->typeAttendance_1_add_case;
                            $arr_2_1 = $request->typeAttendance_2_add_case;
                            $arr_3_1 = $request->unit_date_add_case;
                            $arr_4_1 = $request->time_late_add_case;

                            foreach ($arr_1_1 as $key => $value) {
                                if( isset($arr_1_1[$key]) ){
                                    $arr_1_1[$key] = array(
                                                        '0'=>$value,
                                                        '1'=>$arr_2_1[$key],
                                                        '2'=>$arr_3_1[$key],
                                                        '3'=>$arr_4_1[$key],
                                                    );
                                }
                            }

                            $arr_version_2 = array();
                            $j = 1; 
                            foreach ($arr_1_1 as $key => $value) {
                                // Nếu user đi làm (1)
                                if( $arr_1_1[$key][0] == 1){
                                    $arr_version_2[$j]['attendance_type_id'] = $arr_1_1[$key][1];
                                    $arr_version_2[$j]['time_late'] = $arr_1_1[$key][3];
                                }else{
                                    $arr_version_2[$j]['attendance_type_id'] = $arr_1_1[$key][0];
                                    $arr_version_2[$j]['time_late'] = 0;
                                }

                                $attendanceType  = Attendance::infoAttendanceType($arr_version_2[$j]['attendance_type_id']);

                                $arr_version_2[$j]['type'] = $attendanceType->type;
                                $arr_version_2[$j]['unit_date'] = $arr_1_1[$key][2];
                                $arr_version_2[$j]['personnel_attendance_id'] = $key;
                                $arr_version_2[$j]['created_at'] = $created_at_format;
                                // Gửi Email nếu nhân viên đi muộn
                                if( isset($_POST['sendemail']) ){
                                    if( $arr_1_1[$key][0] == 1){
                                        if( $arr_version_2[$j]['attendance_type_id'] == 12 && $arr_version_2[$j]['unit_date'] >0 ){
                                            $result = EmailConfig::getListEmailbyidPersonnel( array(0=>$key) );
                                            $email = $result[0]->email;
                                            $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 1 );
                                            $subject = $infoConfigMail->mail_subject;
                                            $content_mail = array(
                                                                'time_late' =>  $arr_1_1[$key][3],
                                                                'date' =>  $dayCurrent."/".$monthCurrent."/".$yearCurrent,
                                                                'email'=>$email, 
                                                                'subject'=>$subject
                                                            );
                                            \Mail::send('emails.notification_workLate',$content_mail, function ($message) use ($email, $subject)
                                            {
                                                //echo $email;die;
                                                $message->from('nhansu@tohsoft.com', 'TOH');
                                                $message->to($email)->subject($subject);

                                            });
                                            
                                        }
                                    }
                                }

                                $j++;
                            }
                            // echo '<pre>';
                            // print_r($arr_version_2);die;
                            foreach ($arr_version_2 as $key => $value) {
                                $postArray_version_2 = [
                                            'attendance_type_id' => $value['attendance_type_id'], 
                                            'type' => $value['type'], 
                                            'unit_date' => $value['unit_date'], 
                                            'time_late' => $value['time_late'], 
                                            'date_value'=> $date_in,
                                            'updated_at'=>date('Y-m-d'),
                                            'updated_by'=>Auth::user()->id,
                                        ];
                                Attendance::updateAttendanceAll($dayCurrent,$monthCurrent,$yearCurrent,$value['personnel_attendance_id'],$postArray_version_2);
                            }
                        }
                        
                        $arr_1 = $request->typeAttendance_1;
                        $arr_2 = $request->typeAttendance_2;
                        $arr_3 = $request->unit_date;
                        $arr_4 = $request->time_late;
                        // echo '<pre>';
                        // print_r($arr_3);die;
                        foreach ($arr_1 as $key => $value) {
                            if( isset($arr_1[$key]) ){
                                $arr_1[$key] = array(
                                                    '0'=>$value,
                                                    '1'=>$arr_2[$key],
                                                    '2'=>$arr_3[$key],
                                                    '3'=>$arr_4[$key],
                                                );
                            }
                        }

                        // echo "<pre>";
                        // print_r($arr_3_1);die;
                        $arr = array();
                        $i = 1; 
                        foreach ($arr_1 as $key => $value) {
                            // Nếu user đi làm (1)
                            if( $arr_1[$key][0] == 1){
                                $arr[$i]['attendance_type_id'] = $arr_1[$key][1];
                                $arr[$i]['time_late'] = $arr_1[$key][3];
                            }else{
                                $arr[$i]['attendance_type_id'] = $arr_1[$key][0];
                                $arr[$i]['time_late'] = 0;
                            }

                            $attendanceType  = Attendance::infoAttendanceType($arr[$i]['attendance_type_id']);

                            $arr[$i]['type'] = $attendanceType->type;
                            $arr[$i]['unit_date'] = $arr_1[$key][2];
                            $arr[$i]['personnel_attendance_id'] = $key;
                            $arr[$i]['created_at'] = $created_at_format;
                            // Gửi Email nếu nhân viên đi muộn
                            if( isset($_POST['sendemail']) ){
                                if( $arr_1[$key][0] == 1){
                                    if( $arr[$i]['attendance_type_id'] == 12 && $arr[$i]['unit_date'] >0 ){
                                        $result = EmailConfig::getListEmailbyidPersonnel( array(0=>$key) );
                                        $email = $result[0]->email;
                                        $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 1 );
                                        $subject = $infoConfigMail->mail_subject;
                                        $content_mail = array(
                                                            'time_late' =>  $arr_1[$key][3],
                                                            'date' =>  $dayCurrent."/".$monthCurrent."/".$yearCurrent,
                                                            'email'=>$email, 
                                                            'subject'=>$subject
                                                        );
                                        \Mail::send('emails.notification_workLate',$content_mail, function ($message) use ($email, $subject)
                                        {
                                            //echo $email;die;
                                            $message->from('nhansu@tohsoft.com', 'TOH');
                                            $message->to($email)->subject($subject);

                                        });
                                        
                                    }
                                }
                            }
                            $i++;
                        }
                        // echo '<pre>';
                        // print_r($arr);die;
                        foreach ($arr as $key => $value) {
                            $postArray = [
                                        'attendance_type_id' => $value['attendance_type_id'], 
                                        'type' => $value['type'], 
                                        'unit_date' => $value['unit_date'], 
                                        'time_late' => $value['time_late'], 
                                        'date_value'=> $date_in,
                                        'updated_at'=>date('Y-m-d'),
                                        'updated_by'=>Auth::user()->id,
                                    ];
                            Attendance::updateAttendanceAll($dayCurrent,$monthCurrent,$yearCurrent,$value['personnel_attendance_id'],$postArray);
                        }
                        if( isset($_POST['sendemail']) ){
                            return redirect()->back()->with(['flash_message_succ' => 'Bạn đã gửi Email báo đi muộn cho nhân viên thành công']);
                        }else{
                            return redirect()->back()->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
                        }
                    } else {
                        return redirect()->back()->with(['flash_message_err' => 'Thao tác không thành công']);
                    }
                }else{

                    if( $request->typeAttendance_1 !='' ){
                        $arr_1 = $request->typeAttendance_1;
                        $arr_2 = $request->typeAttendance_2;
                        $arr_3 = $request->unit_date;
                        $arr_4 = $request->time_late;

                        foreach ($arr_1 as $key => $value) {
                            if( isset($arr_1[$key]) ){
                                $arr_1[$key] = array(
                                                    '0'=>$value,
                                                    '1'=>$arr_2[$key],
                                                    '2'=>$arr_3[$key],
                                                    '3'=>$arr_4[$key],
                                                );
                            }
                        }
                        $listPersonalID = Attendance::listAttendance($ids='',$date_in);
                        // Attendance::listAttendance($ids='',$date_in);
                        $arr_listPersonalID = array();
                        if( $listPersonalID ){
                            foreach ($listPersonalID as $key => $value) {
                                $arr_listPersonalID[$value->id] = $value->id;
                            }
                        }
                        $arr = array();
                        $i = 1; 
                        foreach ($arr_1 as $key => $value) {
                            if( $arr_1[$key][0] == 1){
                                $arr[$i]['attendance_type_id'] = $arr_1[$key][1];
                                $arr[$i]['time_late'] = $arr_1[$key][3];
                            }else{
                                $arr[$i]['attendance_type_id'] = $arr_1[$key][0];
                                $arr[$i]['time_late'] = 0;
                            }

                            $attendanceType  = Attendance::infoAttendanceType($arr[$i]['attendance_type_id']);

                            $arr[$i]['type'] = $attendanceType->type;
                            $arr[$i]['unit_date'] = $arr_1[$key][2];
                            $arr[$i]['personnel_id'] = $key;
                            $arr[$i]['attendance_day'] = $dayCurrent;
                            $arr[$i]['attendance_month'] = $monthCurrent;
                            $arr[$i]['attendance_year'] = $yearCurrent;
                            $arr[$i]['date_value'] = $date_in;
                            $arr[$i]['created_by'] = Auth::user()->id;
                            $arr[$i]['created_at'] = $created_at_format;
                            $arr[$i]['status'] = 1;
                            $i++;
                            unset($arr_listPersonalID[$key]);
                        }
                        if( count( $arr_listPersonalID ) >0 ){
                            foreach ($arr_listPersonalID as $key => $value) {
                                $arr[$i]['attendance_type_id'] = 2;
                                $arr[$i]['personnel_id'] = $key;
                                if( $request->check_saturday == 1){
                                    $arr[$i]['unit_date'] = 1;
                                }else{
                                    $arr[$i]['unit_date'] = 0.5;
                                }
                                $arr[$i]['type'] = 1;
                                $arr[$i]['time_late'] = 0;
                                $arr[$i]['attendance_day'] = $dayCurrent;
                                $arr[$i]['attendance_month'] = $monthCurrent;
                                $arr[$i]['attendance_year'] = $yearCurrent;
                                $arr[$i]['date_value'] = $date_in;
                                $arr[$i]['created_by'] = Auth::user()->id;
                                $arr[$i]['created_at'] = $created_at_format;
                                $arr[$i]['status'] = 1;
                                $i++;
                            }
                        }

                        Attendance::insertAttendance($arr);
                        return redirect()->back()->with(['flash_message_succ' => 'Chấm công thành công']);
                    } else {
                        return redirect()->back()->with(['flash_message_err' => 'Thao tác không thành công']);
                    }
                }
            } else {
                return redirect()->back()->with(['flash_message_err' => 'Thao tác không thành công']);
            }
            
        }


    }

    public function editItemAttendanceAjax(Request $request){
        if ($request->ajax()) {
            if( empty($request->created_at) ){
                $res=array('Response'=>"Error","Error"=>"Bạn chưa chọn ngày chấm công" );
            }else{
                $date_field = DateTime::createFromFormat('d/m/yy', $request->created_at); 
                $created_at_format = $date_field->format('Y-m-d');
                //Kiểm tra xem ngày chấm công có trong CSDL hay không
                $check = Attendance::checkDayAttendance($created_at_format);
                if( count($check) >0 ){
                    if( $request->typeAttendance_1 == 1 ){
                        $attendance_type_id = $request->typeAttendance_2;
                    }else{
                        $attendance_type_id = $request->typeAttendance_1;
                    }
                    
                    $arr = [
                                'attendance_type_id' => $attendance_type_id, 
                                'updated_at'=>date('Y-m-d'),
                                'updated_by'=>Auth::user()->id,
                            ];
                    Attendance::updateItemAttendance($request->personnel_id,$created_at_format,$arr);
                    $res=array('Response'=>"Success","Message"=>"Bạn đã cập nhật thành công" );
                }else{
                    $res=array('Response'=>"Error","Error"=>"Ngày chấm công không tôn tại" );
                }
            }

            echo json_encode($res);
        }
    }

    public function getAttendanceTotal(Request $request){
       // echo $request->startDate;die;
        // echo 1;die;
        if( $request->startDate !='' ){
            $startDate = DateTime::createFromFormat('d/m/yy', $request->startDate);
            $startDate =$startDate->format('Y-m-d');
        }else{
            $startDate = date('Y')."-".date('m')."-01";
        }

        if( $request->endDate !='' ){
            $endDate = DateTime::createFromFormat('d/m/yy', $request->endDate);
            $endDate =$endDate->format('Y-m-d');
        }else{
            $endDate = date('Y-m-d');
        }
        // if( $startDate < $endDate ){
            $depart = Attendance::listDepartment();
            $ids = array();
            if( $request->selectDepart !=0 ){
                $myfunc =  new Myfunction();
                $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
                if( count($tmp)==0 ){
                    $ids = array($request->selectDepart);
                }else{
                    $ids =  BatvHelper::array_keys_multi($tmp);
                }

                $data = Attendance::getInfoWorking($request,Auth::user()->id,$ids);
            }else{
                $data = Attendance::getInfoWorking($request,Auth::user()->id);
            }

            $data = json_decode(json_encode($data), true);

            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $data[$key]['list'] = array(
                                                        'dayWork' => Attendance::infoAttendanceAll($value['personnel_id'],$startDate,$endDate,$attendance_type_id=[12,2],$type=1),
                                                        'dayWorkLate' => Attendance::infoAttendanceAll($value['personnel_id'],$startDate,$endDate,$attendance_type_id=[12],$type=1),
                                                        'dayWorkLeave' => Attendance::dayWorkLeave($value['personnel_id'],$startDate,$endDate,$attendance_type_id=[1,2,12]),
                                                        // 'dayWorkLeaveSalaried' =>Attendance::dayWorkLeave($value['personnel_id'],$startDate,$endDate,$attendance_type_id=[1,2,12],$type=1),
                                                    );
                }
            }

            $myfunc =  new Myfunction();
            $select = 0;
            if ($request->input('selectDepart') != '') {
                $select = $request->input('selectDepart');
            }
            $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);

            $listPersonal = '';
            if ($request->selectDepart != null) {
                $listPersonal = Attendance::getPersonnelFromDepart($request->selectDepart,$request,$ids);
            }
            return view('layouts.chamcong.tonghop',['result'=> $data ,'department'=>$select_depart,'listPersonal'=>$listPersonal]);
        // }else{
        //     return view( 'layouts.chamcong.tonghop',['message_err'=> 'Trường từ ngày không được lớn hơn trường đến ngày'] );
        // }

    }

    public function getAttendanceWork(Request $request){
    	$depart = Attendance::listDepartment();
        $ids = array();
        if( $request->selectDepart !=0 ){
            $myfunc =  new Myfunction();
            $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
            if( count($tmp)==0 ){
                $ids = array($request->selectDepart);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
            $data = Attendance::listInfoWork($request,$ids);
        }else{
            $data = Attendance::listInfoWork($request);
        }

        $myfunc =  new Myfunction();
        $select = 0;
        if ($request->input('selectDepart') != '') {
            $select = $request->input('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        $listPersonal = '';
        if ($request->selectDepart != null) {
            $listPersonal = Attendance::getPersonnelFromDepart($request->selectDepart,$request,$ids);
        }

        if( !empty($request->selectMonth) ){
            $month =  $request->selectMonth;
            $year  =  $request->selectYear;
            
        }else{
            $month = date('m');
            $year  = date('Y');
        }
        $p_setting = BatvHelper::infoDaysLevea($month,$year);
        $numberDate = $year."-".$month."-".cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $note = Attendance::listAttendanceInfo();
        if( $data ){
            if( $request->filter >= 0 ){
                $param = 0;
                $total_holiday = BatvHelper::infoHoliday(1,$numberDate, $year."-".$month."-01",$numberDate);

                foreach ($data as $key => $value) {
                    $countAttendance_CP = BatvHelper::countAttendance_CP($month,$year,1,$numberDate,$value->personnel_id) ;// số ngày nghỉ có phép 
                    $countAttendance_KP = BatvHelper::countAttendance_KP($month,$year,1,$numberDate,$value->personnel_id);// số ngày nghỉ ko phép
                    $param = $countAttendance_CP + $countAttendance_KP;

                    if ($value->join_insurance == 1) {
                        $param += $total_holiday - BatvHelper::infoHoliday($value->personnel_id,$numberDate, $year."-".$month."-01",$numberDate);
                    }

                    if( $request->filter == 1 ){
                        if( $param >= $p_setting ){
                            unset($data[$key]);
                        }else{
                            $data[$key]->param = $param;
                        }
                    }elseif ($request->filter == 2) {
                        if( $param != $p_setting ){
                            unset($data[$key]);
                        }else{
                            $data[$key]->param = $param;
                        }
                    }elseif ($request->filter == 3){
                        if( $param <= $p_setting ){
                            unset($data[$key]);
                        }else{
                            $data[$key]->param = $param;
                        }
                    }else{
                        $data[$key]->param = $param;
                    }
                }
            }
        }

    	return view('layouts.chamcong.dilam',['data'=>$data,'department'=>$select_depart,'listPersonal'=>$listPersonal,'note'=>$note]);
    }
     public function getAttendanceWorkLate(Request $request){
       // echo 1;die;
    	$depart = Attendance::listDepartment();
        $ids = array();
        if( $request->selectDepart !=0 ){
            $myfunc =  new Myfunction();
            $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
            if( count($tmp)==0 ){
                $ids = array($request->selectDepart);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
            $data = Attendance::listInfoWorkLate($request,$ids);
        }else{
            $data = Attendance::listInfoWorkLate($request);
        }

        $myfunc =  new Myfunction();
        $select = 0;
        if ($request->input('selectDepart') != '') {
            $select = $request->input('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        $listPersonal = '';
        if ($request->selectDepart != null) {
            $listPersonal = Attendance::getPersonnelFromDepart($request->selectDepart,$request,$ids);
        }
        // echo "<pre>";
        // print_r($request->selectPersonnel);
        // echo "</pre>";die;
        if( $data ){

            if( $request->filter > 0 ){
                foreach ($data as $key => $value) {
                    if( $request->filter == 1 ){
                        if( count($value->time_late) != 0 ){
                            unset($data[$key]);
                        }
                    }elseif ($request->filter == 2) {
                        if( count($value->time_late) == 0 ){
                            unset($data[$key]);
                        }
                    }else{
                        if( count($value->time_late) <= 3 ){
                            unset($data[$key]);
                        }
                    }
                }
            }
        }
    	return view('layouts.chamcong.dimuon',['data'=>$data,'department'=>$select_depart,'listPersonal'=>$listPersonal]);
    }
    public function getAttendanceWorkHoliday(Request $request){
        if( $request->selectYear !='' ){
            $year = $request->selectYear;
        }else{
            $year = date('Y');
        }
        $ids = array();
        if( $request->selectDepart !=0 ){
            $myfunc =  new Myfunction();
            $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
            if( count($tmp)==0 ){
                $ids = array($request->selectDepart);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
            $data = Attendance::getInfoWorkLeave($request,$ids);
        }else{
            $data = Attendance::getInfoWorkLeave($request);
        }

        $listPersonnel = Personnel::listPersonnel($request);
        // echo "<pre>";
        // print_r($data);die;
        $data = json_decode(json_encode($data), true);
        foreach ($data as $key => $value) {
            $data[$key]['dayWorkLeave'] = array(
                                                '1' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'01',$year,$type=11),
                                                '2' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'02',$year,$type=11),
                                                '3' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'03',$year,$type=11),
                                                '4' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'04',$year,$type=11),
                                                '5' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'05',$year,$type=11),
                                                '6' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'06',$year,$type=11),
                                                '7' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'07',$year,$type=11),
                                                '8' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'08',$year,$type=11),
                                                '9' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'09',$year,$type=11),
                                                '10' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'10',$year,$type=11),
                                                '11' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'11',$year,$type=11),
                                                '12' => Attendance::infoAttendancebyMonthYear($value['personnel_id'],'12',$year,$type=11),
                                            );
        }

    	$depart = Attendance::listDepartment();
        $myfunc =  new Myfunction();
        $select = 0;
        if ($request->input('selectDepart') !='') {
            $select = $request->input('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
    	return view('layouts.chamcong.ngayphep',['department'=>$select_depart,'data'=>$data]);
    }

     public function getAttendancePersonalAjax(Request $request){
    		if ($request->ajax()) {
    			if ($request->input('department_id') != null && $request->input('department_id') != 0) {
                    $myfunc =  new Myfunction();
                    $tmp[ $request->input('department_id') ] = $myfunc->categoryChild($request->input('department_id'),'departments');
                    if( count($tmp)==0 ){
                        $ids = array($request->input('department_id'));
                    }else{
                        $ids =  BatvHelper::array_keys_multi($tmp);
                    }

    				return Attendance::getPersonnelFromDepartAjax($request->input('department_id'),$request,$ids);
    				//return response()->json(['data'=>$request->input('department_id')]);
    			}		
    		}else{
    			return json_encode(['data'=>'You can not permit access here']);
    		}
    		
    }

    public function addAttendanceSymbolAjax(Request $request){
        if ($request->ajax()) {
            $rules = [
                'symbol' =>'unique:attendance_type,symbol',
            ];
            $messages = [
                'symbol.unique' => 'Ký hiệu không được giống ký hiệu đã tạo trước đó',
            ];
            $validator = Validator::make(\Input::all(), $rules, $messages);
            if ($validator->fails()) {
                // echo 1;die;
                $res=array('Response'=>"Error","Error"=>$validator->errors()->toArray() );
            }else{
                $arr = [
                            'type' => $request->type, 
                            'work_type' => 0, 
                            'title' => $request->title, 
                            'symbol'=>$request->symbol,
                            'status' => 1, 
                            'created_at'=>date('Y-m-d'),
                            'created_by'=>Auth::user()->id,
                        ];
                Attendance::insertAttendanceSymbol($arr);
                $res=array('Response'=>"Success","Message"=>"Bạn đã thêm mới thành công" );
            }

            echo json_encode($res);
        }
    }

    public function editAttendanceSymbolAjax( Request $request ){
        if ($request->ajax()) {

            $rules = [
                'symbol' =>'required|unique:attendance_type,symbol,'.$request->id,
                'title' =>'required',
            ];
            $messages = [
                'symbol.required' => 'Ký hiệu không được để trống',
                'symbol.unique' => 'Ký hiệu không được giống ký hiệu đã tạo trước đó',
                'title.required' => 'Chú thích không được để trống',
            ];
            $validator = Validator::make(\Input::all(), $rules, $messages);
            if ($validator->fails()) {
                // echo 1;die;
                $res=array('Response'=>"Error","Error"=>$validator->errors()->toArray() );
            }else{
                $arr = [
                            'type' => $request->type, 
                            'title' => $request->title, 
                            'symbol'=>$request->symbol,
                            'updated_at'=>date('Y-m-d'),
                            'updated_by'=>Auth::user()->id,
                        ];
                Attendance::updateAttendanceSymbol($arr,$request->id);
                $res=array('Response'=>"Success","Message"=>"Bạn đã cập nhật thành công" );
            }

            echo json_encode($res);
        }

    }

    public function addAttendanceSpecialAjax( Request $request ){
        if ($request->ajax()) {

            $getDate = explode("/",$request->date);
            $yearCurrent = $getDate[2];
            $monthCurrent = $getDate[1];
            $dayCurrent = $getDate[0];
            $date_field = DateTime::createFromFormat('d/m/yy', $request->date); 
            $created_at_format = $date_field->format('Y-m-d');

            $arr_1 = $request->typeAttendance_1;
            $arr_2 = $request->typeAttendance_2;
            $arr_3 = $request->unit_date;
            $arr_4 = $request->time_late;
            $personnel_id = $request->personnel_id;

            foreach ($arr_1 as $key => $value) {
                if( isset($arr_1[$key]) ){
                    $arr_1[$key] = array(
                                        '0'=>$value,
                                        '1'=>$arr_2[$key],
                                        '2'=>$arr_3[$key],
                                        '3'=>$arr_4[$key],
                                    );
                }
            }

            $arr = array();
            $i = 1; 
            foreach ($arr_1 as $key => $value) {
                if( $arr_1[$key][0] == 1){
                    $arr[$i]['attendance_type_id'] = $arr_1[$key][1];
                    $arr[$i]['time_late'] = $arr_1[$key][3];
                }else{
                    $arr[$i]['attendance_type_id'] = $arr_1[$key][0];
                    $arr[$i]['time_late'] = 0;
                }

                $attendanceType  = Attendance::infoAttendanceType($arr[$i]['attendance_type_id']);

                $arr[$i]['type'] = $attendanceType->type;
                $arr[$i]['unit_date'] = $arr_1[$key][2];
                $arr[$i]['personnel_id'] = $personnel_id[$key];
                $arr[$i]['attendance_day'] = $dayCurrent;
                $arr[$i]['attendance_month'] = $monthCurrent;
                $arr[$i]['attendance_year'] = $yearCurrent;
                $arr[$i]['date_value'] = $yearCurrent.'-'.$monthCurrent.'-'.$dayCurrent;
                $arr[$i]['created_by'] = Auth::user()->id;
                $arr[$i]['created_at'] = $created_at_format;
                $arr[$i]['status'] = 1;
                $i++;
            }
            // echo "<pre>";
            // print_r($arr);die;

            Attendance::insertAttendance($arr);
            $res=array('Response'=>"Success","Message"=>"Bạn đã thực hiện  thành công" );
            echo json_encode($res);
        }

    }

    public function deleteAttendanceSymbolAjax(Request $request){
        if ($request->ajax()) {
            if( $request->id == 1 || $request->id == 2 || $request->id == 10 || $request->id == 11 || $request->id == 12){
                $res=array('Response'=>"Error","Error"=>"Bạn không thể xóa vì đây là kiểu cố định" );
            }else{
                $arr = [
                            'status' => 0, 
                        ];
                Attendance::updateAttendanceSymbol($arr,$request->id);
                $res=array('Response'=>"Success","Message"=>"Bạn đã xóa thành công" );
            }
            
            echo json_encode($res);
        }
    }
    public function getAttendanceItemAjax(Request $request){
        if ($request->ajax()) {

            $getDate = explode("/",$request->date);
            $yearCurrent = $getDate[2];
            $monthCurrent = $getDate[1];
            $dayCurrent = $getDate[0];

            // Nếu user đi làm (1)
            if( $request->typeAttendance_1_1 == 1){
                $attendance_type_id = $request->typeAttendance_2_1;
                $time_late = $request->time_late_2;
            }else{
                $attendance_type_id = $request->typeAttendance_1_1;
                $time_late = 0;
            }
            $rules = [
            ];
            $messages = [
            ];
            $validator = Validator::make(\Input::all(), $rules, $messages);
            if ($validator->fails()) {
                // echo 1;die;
                $res=array('Response'=>"Error","Error"=>$validator->errors()->toArray() );
            }else{
                $attendanceType  = Attendance::infoAttendanceType($request->typeAttendance_1_1);
                $arr = [
                            'type' => $attendanceType->type,
                            'attendance_type_id'=>$attendance_type_id,
                            'unit_date' => $request->unit_date_2, 
                            'time_late'=>$time_late,
                            'personnel_id' => $request->id, 
                            'attendance_day'=>$dayCurrent,
                            'attendance_month'=>$monthCurrent,
                            'attendance_year'=>$yearCurrent,
                            'date_value' => $yearCurrent.'-'.$monthCurrent.'-'.$dayCurrent,
                            'param_check'=>0,
                            'status' => 0, 
                            'created_at'=>date('Y-m-d'),
                            'created_by'=>Auth::user()->id,
                        ];
                $check = Attendance::checkAttendanceDaybyPer( $dayCurrent,$monthCurrent,$yearCurrent, $request->id);
                if( $check == 1 ){
                    Attendance::insertAttendance($arr);
                }
                $res=array('Response'=>"Success","Message"=>"Bạn đã thực hiện thành công" );
            }

            echo json_encode($res);
        }
    }

    public function delAttendanceItemAjax(Request $request){
        if ($request->ajax()) {
            $getDate = explode("/",$request->date);
            $yearCurrent = $getDate[2];
            $monthCurrent = $getDate[1];
            $dayCurrent = $getDate[0];
            // echo $dayCurrent.'-----'.$monthCurrent.'-----'.$yearCurrent.'-----'.$request->id;die;
            $check = Attendance::delAttendanceItem($dayCurrent,$monthCurrent,$yearCurrent,$request->id);
            if ($check) {
                $res=array('Response'=>"Success","Message"=>"Bạn đã thực hiện thành công" );
            } else {
                $res=array('Response'=>"Error","Message"=>"Lương tháng ". $monthCurrent ."/". $yearCurrent ." đã chốt, không thể xóa được" );
            }
        
            echo json_encode($res);
        }
    }
    
}
