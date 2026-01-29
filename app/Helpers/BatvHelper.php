<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use App\Models\Salary;
use App\Models\KiNoiQuy;
use App\Models\Evaluation;
use App\Models\Role_User;
use App\Models\Roles;
use App\Models\Privilegs;
use App\Models\KiRules;
use App\Models\MaternityLeave;
use App\Models\Personnel;
use App\Mylibs\Myfunction;
use DateTime;
use Auth;

class BatvHelper
{
    public static function url_exists($url) {
        $headers = @get_headers($url);

        if(isset($headers[0]) && strpos($headers[0],'200') !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function listRolesByUser() {
        $user_id = Auth::user()->id;

        if ($user_id == 1) {
            $arr_route = Privilegs::pluck('router')->toArray();
        } else {
            $roles_id = Role_User::where('user_id', $user_id)->pluck('role_id');
            $data =  Roles::whereIn('id', $roles_id)->get();

            $str_privileges = '';
    
            foreach ($data as $key => $value) {
                $str_privileges .= $value->privileges_id;
                $str_privileges .= (count($data) > 0 && $key < (count($data) - 1)) ? "," : "";
            }
    
            $arr = explode(',', $str_privileges);
    
            $arr = array_unique($arr);
            $privileg = new Privilegs();
            $data2 = $privileg::find($arr);
            $arr_route = [];
    
            foreach ($data2 as $key => $value) {
               $arr_route[] = trim($value->router);
            }
            
        }

        return $arr_route;
    }
    // Hàm tính số ngày nghỉ phép dựa vào NCTT
    public static function nnp ($nctt) {
        if( $nctt < 10 ){
            $nnp = 0;
        }elseif( $nctt >=10 && $nctt < 20 ){
            // Được hưởng 1/2 ngày phép
            $nnp = 0.5;
        }else{
            // Được hưởng 1 ngày phép
            $nnp = 1;
        }
        return $nnp;
    }

    // Hàm check danh sách nhân viên trực thuộc quản lý của sếp X
    public static function listPesonnelByManager() {
        $myfunc =  new Myfunction();
        $check = Evaluation::checkDepartmentbyManager(Auth::user()->id);
        foreach ($check as $key => $value) {
            $tmp[$value->id] =  $myfunc->categoryChild($value->id,'departments');
        }

        $department_id =  BatvHelper::array_keys_multi($tmp);
        $listPersonnel = Evaluation::listPersonnelbyManager($department_id);
        return $listPersonnel;
    }

    public static function handlingTime( $date ){
        $dt = new \DateTime($date);
        return $dt->format('U');
    }

    public static function formatDate($date, $orgDateFormat = "d/m/Y", $formatDate="d-m-Y",$timeFormat="H:i:s",$time=true)
    {
        if($time)
        {
            $formatDateTime=$formatDate.' '.$timeFormat;
        }else{
            $formatDateTime=$formatDate;
        }
        $valid_date = date_format(date_create_from_format($orgDateFormat, $date), $formatDateTime);    
        return $valid_date;
    }

    public static function formatPriceSpecial($price)
    {
        if ((int) $price == $price) {
            return  number_format($price);  
        }else{
            // return number_format((float)$price, 2, '.', ',');
            return number_format((float)$price, 2, '.', ',');
        }
    }

    public static function formatPrice($price)
    {
        return  number_format($price);  

    }

    public static function CreateKey()
    {
        return  substr(md5(microtime()),rand(0,26),15); 
    }

    public static function getTypeIncomeConfigs($param)
    {
        switch ($param) {
            case 0:
                $txt = 'Lương';
                break;
            case 1:
                $txt = 'Thưởng ngày lễ';
                break;
            case 2:
                $txt = 'Thưởng dự án';
                break;
            case 3:
                $txt = 'Phụ cấp ăn trưa';
                break;
            case 4:
                $txt = 'Phụ cấp xăng xe';
                break;
            case 5:
                $txt = 'Phụ cấp điện thoại';
                break;
            case 6:
                $txt = 'Phụ cấp trách nhiệm';
                break;
            case 7:
                $txt = 'Lương mặc định';
                break;
            case 8:
                $txt = 'Thuế';
                break;
            case 9:
                $txt = 'Bảo hiểm (nhân viên phải đóng)';
                break;
            case 10:
                $txt = 'Đi làm muộn';
                break;
            case 11:
                $txt = 'Tiền nghỉ phép';
                break;
            case 12:
                $txt = 'Chi phí khác';
                break;
            case 13:
                $txt = 'Phụ cấp tiền gửi xe';
                break;
            case 14:
                $txt = 'Bảo hiểm (công ty phải đóng)';
                break;
            case 15:
                $txt = 'Phụ cấp khác( P/c nếu không đóng bảo hiểm )';
                break;
            case 16:
                $txt = 'Sử dụng Laptop cá nhân';
                break;
            case 17:
                $txt = 'Tiền liên hoan';
                break;
            case 18:
                $txt = 'Phụ cập nhà ở';
                break;
            case 19:
                $txt = 'Phụ cấp phong trào';
                break;
            default:
                $txt = '-';
                break;
        }
        return $txt;
    }

    public static function getTypeParameters($param)
    {
        if($param==0)
        {
            $txt = 'Reference';
        }else{
            $txt = 'Fixed';
        }
        return $txt;
    }

    //Tính xem nhân viên được hưởng 1 hay 1/2 ngày nghỉ phép đc cấu hình trong tháng
    public static function countNNP( $param_id ,$personnel_id,$time='',$type='',$time_late='',$option='',$convert_ratio='' ){
        $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($time);
        if( in_array($personnel_id, $infoUserIdExceptionalAttendance) ){
            $param = 0;
        }else{
            $convert = explode("-",$time);
            $p_setting =  BatvHelper::infoDaysLevea($convert[1],$convert[0]);
            $year = $month = '';

            //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
            if( $option == 1 ){
                if( $type == 1){ // Thử việc
                    $toh_check = false;
                }elseif ( $type == 2 ) {// Chính thức
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                    $from = $convert[0].'-'.$convert[1].'-01';
                    $to = $convert[0].'-'.$convert[1].'-'.$numberDay;

                    if( count($convert) == 3 ){
                        $numberDay = (int)$convert[2];
                    }
                    $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,1,$numberDay );
                    $toh_check = true;
                    $month = $convert[1];
                    $year = $convert[0];
                    $toh_check = true;
                    $date_out = BatvHelper::infoPersonnelSpecial($personnel_id,'date_out');
                    if( $date_out != ''){
                        if( BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='m',$timeFormat='H:i:s',false) == $convert[1] && BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='Y',$timeFormat='H:i:s',false) == $convert[0] ){
                           $to = $date_out;
                        }
                    }
                    // $flag = BatvHelper::checkPersonnelOut($personnel_id,$convert[1],$convert[0]);
                    // $toh_check = ($flag)?false:true;
                }elseif ( $type == 3 ) {// Thực tập fulltime
                    $toh_check = false;
                }elseif ( $type == 5) {// Thực tập parttime
                    $toh_check = false;
                }else{// Part time
                    $toh_check = false;
                }
            }else{// HĐ nửa này nửa kia
                if( $option == 2 ){
                    if( $type == 1){ // Thử việc
                        $toh_check = false;
                    }elseif ( $type == 2 ) {// Chính thức
                        
                        $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                        $from = $convert[0].'-'.$convert[1].'-'.$convert[2];
                        $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
                        $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,$convert[2],$numberDay );
                        $toh_check = true;
                        $month = $convert[1];
                        $year = $convert[0]; 
                    }elseif ( $type == 3 ) {// Thực tập fulltime
                        $toh_check = false;
                    }elseif ( $type == 5 ) {// Thực tập parttime
                        $toh_check = false;
                    }else{// Part time
                        $toh_check = false;
                    }
                }else{
                    if( $type == 1){ // Thử việc
                        $toh_check = false;
                    }elseif ( $type == 2 ) {// Chính thức
                        $from = $convert[0].'-'.$convert[1].'-01';
                        $to = $convert[0].'-'.$convert[1].'-'.$convert[2];
                        $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,1,$convert[2] );
                        $toh_check = true;
                        $month = $convert[1];
                        $year = $convert[0];
                        
                    }elseif ( $type == 3 ) {// Thực tập fulltime
                        $toh_check = false;
                    }elseif ( $type == 5 ) {// Thực tập parttime
                        $toh_check = false;
                    }else{// Part time
                        $toh_check = false;
                    }
                }

            }

            if( $toh_check == true ){
                //--- TÍNH NGÀY CÔNG THỰC TÊ---

                // Lấy số ngày nghỉ lễ
                $tmp = BatvHelper::infoHoliday( $personnel_id,$time,$from,$to );
                $special = 0;
                if( $result ){
                    foreach ($result as $key => $value) {
                       
                        if( $value->attendance_type_id == 12){
                            // Nếu đi muộn > số phút setting thì coi như  bị trừ nửa ngày công
                            if( $value->time_late > \Config::get('app.time_late') ){
                                //$tmp = $tmp-0.5;
                                $special = $special+0.5;
                            }
                        }
                        // Nếu đi làm 1 ngày 
                        if( $value->unit_date == 1 ){
                            $tmp++; 
                        }elseif( $value->unit_date == 0.5 ){
                            $tmp = $tmp+0.5;
                        }
                    
                    }
                }
                $nctt = $tmp-$special;
                // TÍNH NGÀY CÔNG TIÊU CHUẨN
                $nctc =  BatvHelper::count_working_days($from,$to);
                // echo $nctt;die;

                // - Nếu số ngày làm việc thực tế >= 10 ngày thì dc 0.5 phép
                // - Nếu số ngày làm việc thực tế >= 20 ngày thì dc 1 phép
                if( $nctt < 10 ){
                    $p_setting = 0;
                }elseif( $nctt >=10 && $nctt < 20 ){
                    // Được hưởng 1/2 ngày phép
                    $p_setting = $p_setting/2;
                }else{
                    // Được hưởng 1 ngày phép
                    $p_setting = $p_setting;
                }
                if( $p_setting==1 ){
                    if( $nctc - $nctt ==0 ){
                        $param =  $p_setting;
                    }elseif( $nctc - $nctt == 0.5 ){
                        $param =  $p_setting/2;
                    }else{
                        $param = 0;
                    }
                }else{
                    if( $nctc - $nctt ==0 ){
                        $param =  $p_setting;
                    }else{
                        $param = 0;
                    }
                }
            }else{
                $param = 0;
            }
        }
        return $param;

    }

    public static function getNamePersonnelbyId( $id ){
        $data =  \DB::table('personnel')->select('fullname')->where('id',$id)->value('fullname');
        return $result = (empty($data))?'':$data;
    }

    public static function getNameDepartmentbyId( $parent_id ){
        if( $parent_id==0 ){
            return '---';
        }else{
            $data =  \DB::table('departments')->select('id','title')->where('id',$parent_id)->first();
            return $data->title;
        }
    }


    public static function count_working_days($from, $to) {
        $workingDays = [1, 2, 3, 4, 5 ,6]; # date format = N (1 = Monday,...)

        $from = new \DateTime($from);
        $to = new \DateTime($to);
        $to->modify('+1 day');
        $interval = new \DateInterval('P1D');
        $periods = new \DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $workingDays)) continue;
            if( $period->format('N') == 6 ){
                $days = $days + 0.5;
            }else{
                $days++;
            }
        }
        return $days;
    }

    public static function salary_basic($personnel_id='',$time='',$type='') {
        $convert = explode("-",$time);
        // echo "<pre>";
        // print_r($convert);
        
        $data = \DB::table('personnel_income')
                    ->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')
                    ->where([
                        ['personnel_income.month', '=', $convert[1]],
                        ['personnel_income.year', '=', $convert[0]],
                        ['personnel_income.personnel_id', '=', $personnel_id],
                    ])
                   ->first();
        $result = ['lcb'=>0,'ltt'=>0];
        if ($data) {
            if( $type == 1 ){
                $ltt = $data->salary_trial_default;
            }elseif ( $type == 2 ) {
                $ltt = $data->salary_official_default;
            }elseif ( $type == 3 ) {
                $ltt = $data->salary_trainee_default;
            }elseif ( $type == 5 ) {
                $ltt = $data->salary_trainee_parttime_default;
            }else{
                $ltt = $data->salary_parttime_default;
            }

            $settingSalaryBasic =  \DB::table('setting_salary_basic')->select('value','salary_basic','welfare_fund')->get();
            $i=0;
            foreach ($settingSalaryBasic as $key => $value) {
                if( $i==0 ){
                    if( $ltt<$settingSalaryBasic[0]->value ){
                        $lcb = $settingSalaryBasic[0]->salary_basic;
                        break;
                    }
                }
                if( $i>=1 && $i<count($settingSalaryBasic) ){
                    if( $ltt>=$settingSalaryBasic[$i-1]->value && $ltt<$settingSalaryBasic[$i]->value){
                        $lcb = $settingSalaryBasic[$i]->salary_basic;
                        break;
                    }
                }
                if( $i==count($settingSalaryBasic)-1 ){
                    $lcb = $settingSalaryBasic[count($settingSalaryBasic)-1]->salary_basic;
                    break;
                }

                $i++;
            }
            $result = ['lcb'=>$lcb,'ltt'=>$ltt];
        }

        return $result;
    }

    public static function salary_basic_inline( $salary_default ) {
        $settingSalaryBasic = \DB::table('setting_salary_basic')->select('value','salary_basic','welfare_fund')->get();
        $i=0;
        foreach ($settingSalaryBasic as $key => $value) {
            if( $i==0 ){
                if( $salary_default<$settingSalaryBasic[0]->value ){
                    $lcb = $settingSalaryBasic[0]->salary_basic;
                    $qpl = $settingSalaryBasic[0]->welfare_fund;
                    break;
                }
            }
            if( $i>=1 && $i<count($settingSalaryBasic) ){
                if( $salary_default>=$settingSalaryBasic[$i-1]->value && $salary_default<$settingSalaryBasic[$i]->value){
                    $lcb = $settingSalaryBasic[$i]->salary_basic;
                    $qpl = $settingSalaryBasic[$i]->welfare_fund;
                    break;
                }
            }
            if( $i==count($settingSalaryBasic)-1 ){
                $lcb = $settingSalaryBasic[count($settingSalaryBasic)-1]->salary_basic;
                $qpl = $settingSalaryBasic[count($settingSalaryBasic)-1]->welfare_fund;
                break;
            }

            $i++;
        }
        $result = ['lcb'=>$lcb,'qpl'=>$qpl];
        return $result;
    }

    public static function ltt($formula ,$personnel_id='',$time='',$type='',$time_late='',$option='',$convert_ratio='',$dayLatches='') {
        $convert = explode("-",$time);
        if( count($convert) <3 ){
            $time = $convert[0].'-'.$convert[1].'-'.cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
        }
        //Lấy ra công thức tính Lương mặc đinh
        $default_config =  \DB::table('income_config')
                            ->select('value_id')
                            ->where([
                                ['valid_from', '<=', $time],
                                ['valid_to', '>=', $time],
                                ['type', '=', 7],
                            ])
                            ->value('value_id');
        $tokens = explode(";", $default_config);
        for ($i = 0; $i < count($tokens); $i++) {
            if (is_numeric($tokens[$i])) {
                $tokens[$i] = BatvHelper::fake_data($tokens[$i],$personnel_id,$time,$type,$time_late,$option,$convert_ratio,$dayLatches='');
            } else {
                // do nothing
            }   
        }
        $formula_processed = "";

        for ($i = 0; $i < count($tokens); $i++) {
            $formula_processed = $formula_processed.' '.$tokens[$i];
        }

        $ltt = eval('return '.$formula_processed.';');

        return $ltt;
    }

    public static function lttTet($formula ,$personnel_id='',$time='',$type='',$time_late='',$option='',$convert_ratio='',$dayLatches='') {
        $convert = explode("-",$time);
        if( count($convert) <3 ){
            $time = $convert[0].'-'.$convert[1].'-'.cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
        }

        $ratio = \DB::table('personnel_job_ratio')
                        ->select('ratio')
                        ->where([
                            ['apply_from', '<=', $time],
                            ['apply_to', '>=', $time],
                            ['personnel_id', '=', $personnel_id],
                            ['status', '=', 1],
                        ])
                        ->value('ratio');
        $lcs = \DB::table('parameters')->where('title', 'Lcs')->value('value');
        $Hv = \DB::table('parameters')->where('title', 'Hv')->value('value');

        return 2*$ratio*$lcs*$Hv;
    }
    // Tính tiền nghỉ phép
    public static function salary_leave($formula ,$personnel_id='',$time='',$type='',$time_late='',$option='',$convert_ratio='',$dayLatches='') {
        $convert = explode("-",$time);
        if( count($convert) <3 ){
            $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
            $from = $convert[0].'-'.$convert[1].'-01';
            $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
            $time = $time."-".$numberDay;
        }
        //Lấy ra công thức tính Lương mặc đinh
        $default_config =  \DB::table('income_config')
                            ->select('value_id')
                            ->where([
                                ['valid_from', '<=', $time],
                                ['valid_to', '>=', $time],
                                ['type', '=', 11],
                            ])
                            ->value('value_id');
        $tokens = explode(";", $default_config);
        for ($i = 0; $i < count($tokens); $i++) {
            if (is_numeric($tokens[$i])) {
                $tokens[$i] = BatvHelper::fake_data($tokens[$i],$personnel_id,$time,$type,$time_late,$option,$convert_ratio,$dayLatches='');
            } else {
                // do nothing
            }   
        }
        $formula_processed = "";
        for ($i = 0; $i < count($tokens); $i++) {
            $formula_processed = $formula_processed.' '.$tokens[$i];
        }
        $salary_leave = eval('return '.$formula_processed.';');
        return $salary_leave;
    }

    public static function countAttendance($attendance_month,$attendance_year,$personnel_id,$attendance_day_1,$attendance_day_2){
        return \DB::table('personnel_attendance')
                    ->select('attendance_type_id','attendance_day','attendance_month','attendance_year','time_late','unit_date','type')
                    ->where([
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                        ['personnel_id', '=', $personnel_id],
                        ['attendance_day', '>=', $attendance_day_1],
                        ['attendance_day', '<=',$attendance_day_2],
                    ])
                    ->where(function($query){
                        //$query->where('attendance_type_id',2)->orWhere('attendance_type_id', 12);  
                        $query->where('type',1);   
                    })
                    ->get();
    }

    // Đếm số ngày làm việc
    public static function countAttendanceNormal($attendance_month,$attendance_year,$personnel_id,$attendance_day_1,$attendance_day_2){
        $result = \DB::table('personnel_attendance')
                    ->select('attendance_type_id','attendance_day','time_late','unit_date','type')
                    ->where([
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                        ['personnel_id', '=', $personnel_id],
                        ['attendance_day', '>=', $attendance_day_1],
                        ['attendance_day', '<=',$attendance_day_2],
                    ])
                    ->where(function($query){ 
                        $query->where('type',1);   
                    })
                    ->get();
        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";die;
        if ($result) {
            //Tính số ngày nghỉ lễ
            $from = $attendance_year.'-'.$attendance_month.'-'.$attendance_day_1;
            $to = $attendance_year.'-'.$attendance_month.'-'.$attendance_day_2;
            $time = $attendance_year.'-'.$attendance_month;
            $nctt = BatvHelper::infoHoliday( $personnel_id,$time,$from,$to );
            $special = 0;

            foreach ($result as $key => $value) {
                if( $value->attendance_type_id == 12){
                    // Nếu đi muộn > số phút setting thì coi như  bị trừ nửa ngày công
                    if( $value->time_late > \Config::get('app.time_late') ){
                        $special = $special+0.5;
                    }
                }

                // Nếu đi làm 1 ngày 
                if( $value->unit_date == 1 ){
                    $nctt++; 
                }elseif( $value->unit_date == 0.5 ){
                    $nctt = $nctt+0.5;
                }
            
            }
            $special += BatvHelper::countAttendance_KP($attendance_month,$attendance_year,$attendance_day_1,$attendance_day_2,$personnel_id);
            return $nctt - $special;
        }
    }

    public static function countAttendanceSpecial($attendance_month,$attendance_year,$personnel_id,$attendance_day_1,$attendance_day_2){
        return \DB::table('personnel_attendance')
                    ->leftJoin('attendance_type', 'attendance_type.id', '=', 'personnel_attendance.attendance_type_id')
                    ->select('personnel_attendance.attendance_type_id','personnel_attendance.attendance_day','personnel_attendance.attendance_month','attendance_year','time_late','unit_date','attendance_type.type')
                    ->where([
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                        ['personnel_id', '=', $personnel_id],
                        ['attendance_day', '>=', $attendance_day_1],
                        ['attendance_day', '<=',$attendance_day_2],
                    ])
                    ->get();
    }

    public static function getCountLateAttendance( $day='',$month,$year,$personnel_id ){
        $data =   \DB::table('personnel_attendance')
               ->select('id')
               ->where('attendance_month','=',$month)
               ->where('attendance_year','=',$year)
               ->where('attendance_type_id','=',12)
               ->where('personnel_id','=',$personnel_id)
               ->where('time_late','<=', \Config::get('app.time_late'))
               ->get();
        if( $day !='' ){
            foreach ($data as $key => $value) {
                if( $value->attendance_day > (int)$day ){
                    unset($data[$key]);
                }
            }
            return count($data);
        }else{
            return count($data);
        }
    }

    public static function infoDaysLevea($month,$year){
        $infoDaysLevea =  \DB::table('days_leave_setting')->leftJoin('days_leave_setting_detail', 'days_leave_setting_detail.id_days_leave_setting', '=', 'days_leave_setting.id')->select('days_leave_setting.number_days')->where('days_leave_setting.year',$year)->where('days_leave_setting_detail.month',$month)->value('number_days');

        if( $infoDaysLevea ){
            $p_setting =  $infoDaysLevea;
        }else{
            $p_setting =  1;
        }
        return $p_setting;
    }

    public static function calculate( $formula ,$personnel_id='',$time='',$type='',$time_late='',$option='',$convert_ratio='',$dayLatches='') {
        $tokens = explode(";", $formula);
        for ($i = 0; $i < count($tokens); $i++) {
            if (is_numeric($tokens[$i])) {
                $tokens[$i] = BatvHelper::fake_data($tokens[$i],$personnel_id,$time,$type,$time_late,$option,$convert_ratio,$dayLatches);
            } else {
                // do nothing
            }   
        }
        $formula_processed = "";
        for ($i = 0; $i < count($tokens); $i++) {
            $formula_processed = $formula_processed.' '.$tokens[$i];
        }

        // echo $formula_processed."<br>";die;

        $result = eval('return '.$formula_processed.';');

        return $result;
    }

    public static function fake_data( $param_id ,$personnel_id,$time='',$type='',$time_late='',$option='',$convert_ratio='',$dayLatches='') {
        $data =  \DB::table('parameters')->where('id',$param_id)->first();
        $convert = explode("-",$time);
        if( $data->is_fixed == 1 ){
            return $data->value;
            
        }else{
            $sql = $data->value;
            switch ($data->value_org) {
                case 'N()':
                    if( $type == 100 ){
                        return BatvHelper::getCountLateAttendance( $convert[2],$convert[1],$convert[0],$personnel_id);
                    }else{
                        return BatvHelper::getCountLateAttendance( '',$convert[1],$convert[0],$personnel_id);
                    }
                    break;
                case 'p_late()':
                    return  $time_late;
                    break;
                case 'hscd()':
                    if( $convert_ratio == '' || $convert_ratio == 'special' ){
                        if( count($convert) <3 ){
                            $time = $convert[0].'-'.$convert[1].'-'.cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                        }
                        $result = \DB::table('personnel_job_ratio')
                                    ->select('ratio')
                                    ->where([
                                        ['apply_from', '<=', $time],
                                        ['apply_to', '>=', $time],
                                        ['personnel_id', '=', $personnel_id],
                                        ['status', '=', 1],
                                    ])
                                    ->value('ratio');
                    }else{
                        $result = $convert_ratio;
                    }


                    return $result == '' ? 0 : $result;
                    break;

                case 'bh_npt()':
                    return  \DB::table('personnel')
                                ->select('number_dependent_person')
                                ->where([
                                    ['id', '=', $personnel_id],
                                ])
                                ->value('number_dependent_person');
                    break;
                case 'kpi':
                    // Chưa hoàn thiện Module tính KPI nên để mặc định bằng 1
                    // $result = \DB::table('personnel_evaluation')
                    //             ->select('kpi')
                    //             ->where([
                    //                 ['type', '=', 0],
                    //                 ['date', '=', $time],
                    //                 ['personnel_id', '=', $personnel_id],
                    //             ])
                    //             ->first();
                    // if( $result ){
                    //     return $result->kpi;
                    // }else{
                    //     return 1;
                    // }
                    return 1;
                    break;

                case 'Lcb()':
                    if (!empty($dayLatches)) {
                        $lcb = BatvHelper::salary_basic($personnel_id,$dayLatches,$type);
                    }else{
                        $lcb = BatvHelper::salary_basic($personnel_id,$time,$type);
                    }
                    
                    return $lcb['lcb'];
                    break;

                case 'nnp()': // TÍNH SỐ NGÀY PHÉP ĐƯỢC SETTING TRONG THÁNG ĐÓ
                    $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($time);
                    if( in_array($personnel_id, $infoUserIdExceptionalAttendance) ){
                        $param = 0;
                    }else{
                        $convert = explode("-",$time);
                        $p_setting =  BatvHelper::infoDaysLevea($convert[1],$convert[0]);
                        $year = $month = '';

                        //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                        if( $option == 1 ){
                            if( $type == 1){ // Thử việc
                                $toh_check = false;
                            }elseif ( $type == 2 ) {// Chính thức
                                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                                $from = $convert[0].'-'.$convert[1].'-01';
                                $to = $convert[0].'-'.$convert[1].'-'.$numberDay;

                                if( count($convert) == 3 ){
                                    $numberDay = (int)$convert[2];
                                }
                                $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,1,$numberDay );
                                $toh_check = true;
                                $month = $convert[1];
                                $year = $convert[0];

                                $toh_check = true;
                                $date_out = BatvHelper::infoPersonnelSpecial($personnel_id,'date_out');
                                if( $date_out != ''){
                                    if( BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='m',$timeFormat='H:i:s',false) == $convert[1] && BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='Y',$timeFormat='H:i:s',false) == $convert[0] ){
                                       $to = $date_out;
                                    }
                                }


                                // $flag = BatvHelper::checkPersonnelOut($personnel_id,$convert[1],$convert[0]);
                                // $toh_check = ($flag)?false:true;
                            }elseif ( $type == 3 ) {// Thực tập fulltime
                                $toh_check = false;
                            }elseif ( $type == 5) {// Thực tập parttime
                                $toh_check = false;
                            }else{// Part time
                                $toh_check = false;
                            }
                        }else{// HĐ nửa này nửa kia
                            if( $option == 2 ){
                                if( $type == 1){ // Thử việc
                                    $toh_check = false;
                                }elseif ( $type == 2 ) {// Chính thức
                                    
                                    $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                                    $from = $convert[0].'-'.$convert[1].'-'.$convert[2];
                                    $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
                                    $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,$convert[2],$numberDay );
                                    $toh_check = true;
                                    $month = $convert[1];
                                    $year = $convert[0]; 
                                }elseif ( $type == 3 ) {// Thực tập fulltime
                                    $toh_check = false;
                                }elseif ( $type == 5 ) {// Thực tập parttime
                                    $toh_check = false;
                                }else{// Part time
                                    $toh_check = false;
                                }
                            }else{
                                if( $type == 1){ // Thử việc
                                    $toh_check = false;
                                }elseif ( $type == 2 ) {// Chính thức
                                    $from = $convert[0].'-'.$convert[1].'-01';
                                    $to = $convert[0].'-'.$convert[1].'-'.$convert[2];
                                    $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,1,$convert[2] );
                                    $toh_check = true;
                                    $month = $convert[1];
                                    $year = $convert[0];
                                    
                                }elseif ( $type == 3 ) {// Thực tập fulltime
                                    $toh_check = false;
                                }elseif ( $type == 5 ) {// Thực tập parttime
                                    $toh_check = false;
                                }else{// Part time
                                    $toh_check = false;
                                }
                            }

                        }

                        if( $toh_check == true ){
                            //--- TÍNH NGÀY CÔNG THỰC TÊ---

                            // Lấy số ngày nghỉ lễ
                            $tmp = BatvHelper::infoHoliday( $personnel_id,$time,$from,$to );
                            $special = 0;
                            if( $result ){
                                foreach ($result as $key => $value) {
                                   
                                    if( $value->attendance_type_id == 12){
                                        // Nếu đi muộn > số phút setting thì coi như  bị trừ nửa ngày công
                                        if( $value->time_late > \Config::get('app.time_late') ){
                                            //$tmp = $tmp-0.5;
                                            $special = $special+0.5;
                                        }
                                    }
                                    // Nếu đi làm 1 ngày 
                                    if( $value->unit_date == 1 ){
                                        $tmp++; 
                                    }elseif( $value->unit_date == 0.5 ){
                                        $tmp = $tmp+0.5;
                                    }
                                
                                }
                            }
                            $nctt = $tmp-$special;
                            // TÍNH NGÀY CÔNG TIÊU CHUẨN
                            $nctc =  BatvHelper::count_working_days($from,$to);
                            // echo $nctt;die;

                            // - Nếu số ngày làm việc thực tế >= 10 ngày thì dc 0.5 phép
                            // - Nếu số ngày làm việc thực tế >= 20 ngày thì dc 1 phép
                            if( $nctt >= 10 && $nctt < 20 ){
                                // Được hưởng 1/2 ngày phép
                                $p_setting = $p_setting/2;
                            }elseif( $nctt >= 20 ){
                                // Được hưởng 1 ngày phép
                                $p_setting = $p_setting;
                            }else{
                                $p_setting = 0;
                            }

                            if( $p_setting == 1 ){
                                if( $nctc - $nctt ==0 ){
                                    $param =  $p_setting;
                                }elseif( $nctc - $nctt == 0.5 ){
                                    $param =  $p_setting/2;
                                }else{
                                    $param = 0;
                                }
                            }else{
                                if( $nctc - $nctt ==0 ){
                                    $param =  $p_setting;
                                }else{
                                    $param = 0;
                                }
                            }
                        }else{
                            $param = 0;
                        }
                    }
                    
                    return $param;
                    break;

                case 'hstn()': // TÍNH HỆ SỐ THÂM NIÊN\
                    // - Tính từ thời điểm vào công ty, nếu ngày này rơi vào một ngày nào đó trong tháng thì trước (hoặc trong) ngày 15 tính 1 tháng, sau ngày 15 là 0.5
                    // - hệ số thâm niên tính theo tháng, x tháng thì sẽ là x/12
                    if($convert_ratio == 'special'){
                        $hstn = 0;
                    }else{
                        $data = BatvHelper::infoPersonnelDetail($personnel_id);
                        if( $data ){
                            $date_in = BatvHelper::formatDate($data,'Y-m-d',$formatDate='Y-m',$timeFormat='H:i:s',false);
                            $date = new DateTime($convert[0].'-'.$convert[1]);
                            $interval = new \DateInterval('P1M');
                            $date->add($interval);
                            $date_special = $date->format('Y-m');

                            $ts1 = strtotime($date_in);
                            $ts2 = strtotime($date_special);

                            $year1 = date('Y', $ts1);
                            $year2 = date('Y', $ts2);

                            $month1 = date('m', $ts1);
                            $month2 = date('m', $ts2);

                            $tmp = (($year2 - $year1) * 12) + ($month2 - $month1);
                            $date_in_number = BatvHelper::formatDate($data,'Y-m-d',$formatDate='d',$timeFormat='H:i:s',false);
                            if( (int)$date_in_number > 15 ){
                                $tmp = $tmp - 0.5;
                            }
                            $tmp = $tmp/12;

                        }else{
                            $tmp=0;
                        }
                        $hstn = 0.01 * $tmp;
                    }

                    return $hstn;
                    break;

                case 'nctt()':  // TINH SỐ NGÀY CÔNG THỰC TẾ
                        // Tính số ngày phép được setting trong tháng đó
                        $convert = explode("-",$time);
                        $p_setting =  BatvHelper::infoDaysLevea($convert[1],$convert[0]);
                        //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                        if( $option == 1 ){
                            $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                            $from = $convert[0].'-'.$convert[1].'-01';
                            $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
                            // if( count($convert) == 3 ){
                            //      $numberDay = $convert[2];
                            // }

                            $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,1,$numberDay );
                            if( $type == 1){ // Thử việc
                                $toh_check = false;
                            }elseif ( $type == 2 ) {// Chính thức
                                $toh_check = true;
                                $date_out = BatvHelper::infoPersonnelSpecial($personnel_id,'date_out');
                                if( $date_out != ''){
                                    if( BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='m',$timeFormat='H:i:s',false) == $convert[1] && BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='Y',$timeFormat='H:i:s',false) == $convert[0] ){
                                       $to = $date_out;
                                    }
                                }
                                // $flag = BatvHelper::checkPersonnelOut($personnel_id,$convert[1],$convert[0]);
                                // $toh_check = ($flag)?false:true;
                            }elseif ( $type == 3 ) {// Thực tập fulltime
                                $toh_check = false;
                            }elseif ( $type == 5 ) {// Thực tập parttime
                                $toh_check = false;
                            }else{// Part time
                                $toh_check = false;
                            }
                        }else{// HĐ nửa này nửa kia
                            if( $option == 2 ){
                                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                                $from = $convert[0].'-'.$convert[1].'-'.$convert[2];
                                $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
                                $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,$convert[2],$numberDay );
                                if( $type == 1){ // Thử việc
                                    $toh_check = false;
                                }elseif ( $type == 2 ) {// Chính thức
                                    if( $convert[2] <=10){
                                        $toh_check = true;
                                        $p_setting = $p_setting;
                                    }elseif( $convert[2] >10 && $convert[2] <= 20  ){
                                        $toh_check = true;
                                        $p_setting = $p_setting/2;
                                    }else{
                                        $toh_check = false;
                                    }
                                }elseif ( $type == 3 ) {// Thực tập fulltime
                                    $toh_check = false;
                                }elseif ( $type == 5 ) {// Thực tập parttime
                                    $toh_check = false;
                                }else{// Part time
                                    $toh_check = false;
                                }
                            }else{
                                $from = $convert[0].'-'.$convert[1].'-01';
                                $to = $convert[0].'-'.$convert[1].'-'.$convert[2];
                                $result  = BatvHelper::countAttendance( $convert[1],$convert[0],$personnel_id,1,$convert[2] );
                                if( $type == 1){ // Thử việc
                                    $toh_check = false;
                                }elseif ( $type == 2 ) {// Chính thức
                                    //Nếu là nhân viên 1. Thời điểm ký HDCT trước/ trong ngày 10: 1 phép
                                    // 2. Sau ngày 10 trước/trong ngày 20: 0.5 phép
                                    // 3. Sau ngày 20: 0 phép.

                                    if( $convert[2] <=10){
                                        $toh_check = true;
                                        $p_setting = $p_setting;
                                    }elseif( $convert[2] >10 && $convert[2] <= 20  ){
                                        $toh_check = true;
                                        $p_setting = $p_setting/2;
                                    }else{
                                        $toh_check = false;
                                    }
                                }elseif ( $type == 3 ) {// Thực tập fulltime
                                    $toh_check = false;
                                }elseif ( $type == 5 ) {// Thực tập parttime
                                    $toh_check = false;
                                }else{// Part time
                                    $toh_check = false;
                                }
                            }

                        }

                        // Tinh số ngày NCTT
                        if( isset($from) && isset($to) ){
                            $nctc =  BatvHelper::count_working_days($from,$to);
                            $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($time);
                            if( in_array($personnel_id, $infoUserIdExceptionalAttendance) ){
                                $infoPersonnelExceptionalAttendance = BatvHelper::infoPersonnelExceptionalAttendance($time,$personnel_id);
                                if( BatvHelper::handlingTime( $infoPersonnelExceptionalAttendance->apply_from ) >= BatvHelper::handlingTime( $from ) && BatvHelper::handlingTime( $infoPersonnelExceptionalAttendance->apply_from ) <= BatvHelper::handlingTime( $to ) ){
                                    if( BatvHelper::handlingTime( date('Y-m-d') ) <= BatvHelper::handlingTime( $to ) ){
                                        $nctt =  BatvHelper::count_working_days($infoPersonnelExceptionalAttendance->apply_from,date('Y-m-d'));
                                    }else{
                                        $nctt =  BatvHelper::count_working_days($infoPersonnelExceptionalAttendance->apply_from,$to);
                                    }

                                }else{
                                    if( BatvHelper::handlingTime( date('Y-m-d') ) <= BatvHelper::handlingTime( $to ) ){
                                        $nctt =  BatvHelper::count_working_days($from,date('Y-m-d'));
                                    }else{
                                        $nctt =  BatvHelper::count_working_days($from,$to);
                                    }
                                    
                                }
                            }else{
                                //Tính số ngày nghỉ lễ
                                $tmp = BatvHelper::infoHoliday( $personnel_id,$time,$from,$to );
                                $special = 0;
                                if( $result ){
                                    foreach ($result as $key => $value) {
                                        if( $value->attendance_type_id == 12){
                                            // Nếu đi muộn > số phút setting thì coi như  bị trừ nửa ngày công
                                            if( $value->time_late > \Config::get('app.time_late') ){
                                                $special = $special+0.5;
                                            }
                                        }

                                        // Nếu đi làm 1 ngày 
                                        if( $value->unit_date == 1 ){
                                            $tmp++; 
                                        }elseif( $value->unit_date == 0.5 ){
                                            $tmp = $tmp+0.5;
                                        }
                                    
                                    }
                                }
                                // echo "<pre>";
                                // print_r($result);
                                // echo "</pre>";die;

                                $nctt = $tmp- $special;
                                if( $toh_check ==true ){
                                    // - Nếu số ngày làm việc thực tế >= 10 ngày thì dc 0.5 phép
                                    // - Nếu số ngày làm việc thực tế >= 20 ngày thì dc 1 phép
                                    if( $nctt < 10 ){
                                        $p_setting = 0;
                                    }elseif( $nctt >=10 && $nctt < 20 ){
                                        // Được hưởng 1/2 ngày phép
                                        $p_setting = $p_setting/2;
                                    }else{
                                        // Được hưởng 1 ngày phép
                                        $p_setting = $p_setting;
                                    }

                                    if( $p_setting==1 ){
                                        if( $nctc - $nctt ==0 ){
                                            // not bad
                                        }elseif( $nctc - $nctt == 0.5 ){
                                            $nctt = $nctt+$p_setting/2;
                                        }else{
                                            $nctt = $nctt+$p_setting;
                                        }
   
                                    }else{
                                        if( $nctc - $nctt ==0 ){
                                            // not bad
                                        }else {
                                            $nctt =  $nctt+$p_setting;
                                        }

                                    }
                                }
                                $nctt  = $nctt;
                            }

                        }else{
                            $nctt = 1;
                        }
                        // echo $nctt;die;
                        return $nctt;
                break;

                case 'he_so_tham_nien_thuong_tet()': // NHẬP THÊM TRƯỜNG "Ngày chốt lương thưởng Tết" Ở TRANG QUẢN TRỊ VÀO
                    if( !empty($dayLatches) ){

                        $dayLatches = explode("-",$dayLatches);
                        $day = (int)$dayLatches[2];
                        $month = (int)$dayLatches[1];
                        $year = (int)$dayLatches[0];

                        // Cơ chế tính: hs=1 nếu làm đủ 12 tháng, dưới 12 tháng hệ số n/12
                        $data = BatvHelper::infoPersonnelDetail($personnel_id); 
                        $check = 0;
                        if( strtotime($data) <= strtotime( $year.'-01-01' ) ){
                            $date_start = $year.'-01-01';
                        }else{
                            $date_start = $data;
                            $day_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
                            $check = 1;
                        }

                        $month_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
                        $date_end = date("Y-m-t", strtotime( $year.'-12-01' ));

                        $month_EX = 0;

                        // Nếu là nhân viên được miễn chấm công thì mặc định sẽ dc trọn vẹn $month_EX = 12
                        $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($time);
                        if( in_array($personnel_id, $infoUserIdExceptionalAttendance) ){
                            $month_EX = 12;
                        }else{
                            // echo $dayLatches;die;
                            for ($i=(int)$month_start; $i <= 12 ; $i++) { 
                                $nctt = 0;
                                $day_end = 31;
                                
                                if( $i == (int)$month ){
                                    $day_end = $day - 1;
                                    $nctt = $nctt + BatvHelper::count_working_days(  $year.'-'.$i.'-'.$day,$year.'-'.$i.'-' .cal_days_in_month(CAL_GREGORIAN,$month,$year) );
                                }

                                if( $check == 1 && $i == (int)$month_start ){ 

                                    $i = ( $i < 10 )?'0'.$i:$i;
                                    // $day_start = ( (int)$day_start < 10 )?'0'.$day_start:$day_start;

                                    $numberDay = cal_days_in_month(CAL_GREGORIAN,$i,$year);
                                    $from = $year.'-'.$i.'-'.$day_start;
                                    $to = $year.'-'.$i.'-'.$numberDay;

                                    $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,(int)$day_start,$day_end );
                                }else{
                                    $i = ( $i < 10 )?'0'.$i:$i;
                                    $from = $year.'-'.$i.'-01';
                                    $numberDay = cal_days_in_month(CAL_GREGORIAN,$i,$year);
                                    $to = $year.'-'.$i.'-'.$numberDay;

                                    $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$day_end );
                                }

                                $nctt = $nctt + BatvHelper::infoHoliday( $personnel_id,$year.'-'.$i,$from,$to );

                                if( $result ){
                                    foreach ($result as $key => $value) {
                                        // Nếu đi làm 1 ngày 
                                        if( $value->unit_date == 1 ){
                                            $nctt++; 
                                        }elseif( $value->unit_date == 0.5 ){
                                            $nctt = $nctt+0.5;
                                        }
                                    }
                                }
                                
                                if( $nctt < 10 ){

                                }elseif ( $nctt >= 10 && $nctt < 20 ) {
                                    $month_EX = $month_EX + 0.5;
                                    $kaka = 0.5;
                                }else{
                                    $month_EX = $month_EX + 1;
                                    $kaka = 1;
                                }

                                // echo $kaka."</br>";
                                unset($nctt);
                            }
                        }
                        // echo $month_EX."</br>";die;
                        return ( $month_EX == 12 )?1:$month_EX/12;
                    }

                break;

                // SỐ THÁNG LÀM VIỆC TRƯỚC NĂM HIỆN TẠI
                case 'numberMonthBeforeYearCurrent()':
                    $data = BatvHelper::infoPersonnelDetail($personnel_id); 
                    $result = 0;
                    if( strtotime($data) <= strtotime( $convert[0].'-01-01' ) ){
                        $date_in = $data;
                        $date_last = date("Y-m-t", strtotime( date('Y',strtotime($convert[0].' -1 year')).'-12-01' ));
                        $date1 = new DateTime( $date_in );
                        $date2 = new DateTime( $date_last );
                        $diff = $date1->diff($date2);
                        if( $diff ){
                            $year = $diff->y;
                            $month = $diff->m;  
                            $tmp_day = $diff->d;
                            $day = ( $tmp_day >0 )?1:0;
                        }

                        $result = 12*$year + $month + $day;
                        
                    }
                    // echo $result;die;
                    return $result;
                    break;

                case 'count_working_days()':  //NGÀY CÔNG TIÊU CHUẨN
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                    $from = $convert[0].'-'.$convert[1].'-01';
                    $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
                    $result = BatvHelper::count_working_days($from,$to);

                    // Nếu là Parttime thì NCTC = nctc/2
                    if( $type == 4 ){
                        $result = $result/2;
                    }
                    return $result;
                    break;

                case 'ttn()':
                    $info =  \DB::table('personnel_income')->leftJoin('personnel_tax_insurance', 'personnel_tax_insurance.personnel_income_id', '=', 'personnel_income.id')->leftJoin('personnel_salary', 'personnel_salary.personnel_income_id', '=', 'personnel_income.id')->leftJoin('personnel_bonus', 'personnel_bonus.personnel_income_id', '=', 'personnel_income.id')->leftJoin('personnel_income_other', 'personnel_income_other.personnel_income_id', '=', 'personnel_income.id')->select('personnel_tax_insurance.insurance','personnel_salary.salary_official_work','personnel_salary.salary_leave','personnel_salary.money_work_late','personnel_bonus.holiday_bonus','personnel_bonus.work_bonus','personnel_bonus.management_allowance', \DB::raw('SUM(personnel_income_other.income_value) as total_income_other'))->where('personnel_income.personnel_id',$personnel_id)->where('personnel_income.year',$convert[0])->where('personnel_income.month',$convert[1])->groupBy('personnel_income_other.personnel_income_id')->first();
                    return $info->salary_official_work+$info->salary_leave-$info->money_work_late+$info->holiday_bonus+$info->work_bonus+$info->management_allowance-$info->insurance+$info->total_income_other;
                    break;
                // case 'tbh()':
                //     $info =  \DB::table('personnel_income')->leftJoin('personnel_tax_insurance', 'personnel_tax_insurance.personnel_income_id', '=', 'personnel_income.id')->select('personnel_tax_insurance.insurance')->where('personnel_income.year',$convert[0])->where('personnel_income.month',$convert[1])->first();
                //     return $info->insurance;
                //     break;
                default:
                    # code...
                    break;
            }

        }
    }

    public static function fake_data_special( $param_id ,$personnel_id,$time='',$type='',$time_late='',$option='') {
        $data =  \DB::table('parameters')->where('id',$param_id)->first();
        $convert = explode("-",$time);
        if( $data->is_fixed == 1 ){
            return $data->value;
            
        }else{

            $sql = $data->value;
            switch ($data->value_org) {
                case 'nctt()':  // TINH SỐ NGÀY CÔNG THỰC TẾ
                    // Tính số ngày phép được setting trong tháng đó
                    $convert = explode("-",$time);
                    $p_setting =  BatvHelper::infoDaysLevea($convert[1],$convert[0]);
                    //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                    if( $option == 1 ){
                        $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                        $from = $convert[0].'-'.$convert[1].'-01';
                        $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
                        if( count($convert) == 3 ){
                             $numberDay = $convert[2];
                        }
                        $result  = BatvHelper::countAttendanceSpecial( $convert[1],$convert[0],$personnel_id,1,$numberDay );
                        if( $type == 1){ // Thử việc
                            $toh_check = false;
                        }elseif ( $type == 2 ) {// Chính thức
                            $toh_check = true;
                            $date_out = BatvHelper::infoPersonnelSpecial($personnel_id,'date_out');
                            if( $date_out != ''){
                                if( BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='m',$timeFormat='H:i:s',false) == $convert[1] && BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='Y',$timeFormat='H:i:s',false) == $convert[0] ){
                                   $to = $date_out;
                                }
                            }
                            // $flag = BatvHelper::checkPersonnelOut($personnel_id,$convert[1],$convert[0]);
                            // $toh_check = ($flag)?false:true;
                        }elseif ( $type == 3 ) {// Thực tập fulltime
                            $toh_check = false;
                        }elseif ( $type == 5 ) {// Thực tập parttime
                            $toh_check = false;
                        }else{// Part time
                            $toh_check = false;
                        }
                    }else{// HĐ nửa này nửa kia
                        if( $option == 2 ){
                            $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                            $from = $convert[0].'-'.$convert[1].'-'.$convert[2];
                            $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
                            $result  = BatvHelper::countAttendanceSpecial( $convert[1],$convert[0],$personnel_id,$convert[2],$numberDay );
                            if( $type == 1){ // Thử việc
                                $toh_check = false;
                            }elseif ( $type == 2 ) {// Chính thức
                                if( $convert[2] <=10){
                                    $toh_check = true;
                                    $p_setting = $p_setting;
                                }elseif( $convert[2] >10 && $convert[2] <= 20  ){
                                    $toh_check = true;
                                    $p_setting = $p_setting/2;
                                }else{
                                    $toh_check = false;
                                }
                            }elseif ( $type == 3 ) {// Thực tập fulltime
                                $toh_check = false;
                            }elseif ( $type == 5 ) {// Thực tập parttime
                                $toh_check = false;
                            }else{// Part time
                                $toh_check = false;
                            }
                        }else{
                            $from = $convert[0].'-'.$convert[1].'-01';
                            $to = $convert[0].'-'.$convert[1].'-'.$convert[2];
                            $result  = BatvHelper::countAttendanceSpecial( $convert[1],$convert[0],$personnel_id,1,$convert[2] );
                            if( $type == 1){ // Thử việc
                                $toh_check = false;
                            }elseif ( $type == 2 ) {// Chính thức
                                //Nếu là nhân viên 1. Thời điểm ký HDCT trước/ trong ngày 10: 1 phép
                                // 2. Sau ngày 10 trước/trong ngày 20: 0.5 phép
                                // 3. Sau ngày 20: 0 phép.

                                if( $convert[2] <=10){
                                    $toh_check = true;
                                    $p_setting = $p_setting;
                                }elseif( $convert[2] >10 && $convert[2] <= 20  ){
                                    $toh_check = true;
                                    $p_setting = $p_setting/2;
                                }else{
                                    $toh_check = false;
                                }
                            }elseif ( $type == 3 ) {// Thực tập fulltime
                                $toh_check = false;
                            }elseif ( $type == 5 ) {// Thực tập parttime
                                $toh_check = false;
                            }else{// Part time
                                $toh_check = false;
                            }
                        }

                    }

                    // Tinh số ngày NCTT
                    if( isset($from) && isset($to) ){
                        $nctc =  BatvHelper::count_working_days($from,$to);
                        $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($time);
                        if( in_array($personnel_id, $infoUserIdExceptionalAttendance) ){
                            $infoPersonnelExceptionalAttendance = BatvHelper::infoPersonnelExceptionalAttendance($time,$personnel_id);
                            if( BatvHelper::handlingTime( $infoPersonnelExceptionalAttendance->apply_from ) >= BatvHelper::handlingTime( $from ) && BatvHelper::handlingTime( $infoPersonnelExceptionalAttendance->apply_from ) <= BatvHelper::handlingTime( $to ) ){
                                if( BatvHelper::handlingTime( date('Y-m-d') ) <= BatvHelper::handlingTime( $to ) ){
                                    $nctt =  BatvHelper::count_working_days($infoPersonnelExceptionalAttendance->apply_from,date('Y-m-d'));
                                }else{
                                    $nctt =  BatvHelper::count_working_days($infoPersonnelExceptionalAttendance->apply_from,$to);
                                }

                            }else{
                                if( BatvHelper::handlingTime( date('Y-m-d') ) <= BatvHelper::handlingTime( $to ) ){
                                    $nctt =  BatvHelper::count_working_days($from,date('Y-m-d'));
                                }else{
                                    $nctt =  BatvHelper::count_working_days($from,$to);
                                }
                                
                            }
                        }else{
                            //Tính số ngày nghỉ lễ
                            $tmp = BatvHelper::infoHoliday( $personnel_id,$time,$from,$to );
                            $special = 0;
                            if( $result ){
                                foreach ($result as $key => $value) {
                                    if( $value->attendance_type_id == 12 ){
                                        // Nếu đi muộn > số phút setting thì coi như  bị trừ nửa ngày công
                                        if( $value->time_late > \Config::get('app.time_late') ){
                                            //$tmp = $tmp-0.5;
                                            $special = $special+0.5;
                                        }
                                    }elseif( $value->attendance_type_id == 10  ){// Nếu nghỉ không phép
                                        if( $value->unit_date == 1 ){
                                            $special++;
                                        }elseif( $value->unit_date == 0.5 ){
                                            $special = $special+0.5;
                                        }
                                    }

                                    if( $value->type == 1 ){
                                        // Nếu đi làm 1 ngày 
                                        if( $value->unit_date == 1 ){
                                            $tmp++; 
                                        }elseif( $value->unit_date == 0.5 ){
                                            $tmp = $tmp+0.5;
                                        }
                                    }
                                }
                            }

                            $nctt = $tmp- $special;
                            if( $toh_check ==true ){
                                // - Nếu số ngày làm việc thực tế >= 10 ngày thì dc 0.5 phép
                                // - Nếu số ngày làm việc thực tế >= 20 ngày thì dc 1 phép
                                if( $nctt >= 10 && $nctt < 20){
                                    // Được hưởng 1/2 ngày phép
                                    $p_setting = $p_setting/2;
                                }elseif( $nctt >= 20 ){
                                    // Được hưởng 1 ngày phép
                                    $p_setting = $p_setting;
                                }else{
                                    $p_setting = 0;
                                }

                                if( $p_setting==1 ){
                                    if( $nctc - $nctt ==0 ){
                                        // not bad
                                    }elseif( $nctc - $nctt == 0.5 ){
                                        $nctt = $nctt+$p_setting/2;
                                    }else{
                                        $nctt = $nctt+$p_setting;
                                    }

                                }else{
                                    if( $nctc - $nctt ==0 ){
                                        // not bad
                                    }else {
                                        $nctt =  $nctt+$p_setting;
                                    }

                                }
                            }
                            $nctt  = $nctt;
                        }
                    }else{
                        $nctt = 1;
                    }
                    return $nctt;
                break;


                case 'count_working_days()':  //NGÀY CÔNG TIÊU CHUẨN
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                    $from = $convert[0].'-'.$convert[1].'-01';
                    $to = $convert[0].'-'.$convert[1].'-'.$numberDay;
                    $result = BatvHelper::count_working_days($from,$to);

                    // Nếu là Parttime thì NCTC = nctc/2
                    if( $type == 4 ){
                        $result = $result/2;
                    }
                    return $result;
                    break;

            }

        }
    }
    public static function fake_data_special_2( $param_id ,$personnel_id,$time='',$type='',$time_late='',$option='') {
        $data =  \DB::table('parameters')->where('id',$param_id)->first();
        $convert = explode("-",$time);
        if( $data->is_fixed == 1 ){
            return $data->value;
            
        }else{

            $sql = $data->value;
            switch ($data->value_org) {
                case 'nctt()':  // TINH SỐ NGÀY CÔNG THỰC TẾ
                    return 1;
                break;

                case 'count_working_days()':  //NGÀY CÔNG TIÊU CHUẨN
                    return 1;
                    break;
            }

        }
    }
    public static function calculateSpecial( $formula ,$personnel_id='',$time='',$type='',$time_late='',$option='') {
        $tokens = explode(";", $formula);
        for ($i = 0; $i < count($tokens); $i++) {
            if (is_numeric($tokens[$i])) {
                $tokens[$i] = BatvHelper::fake_data_special($tokens[$i],$personnel_id,$time,$type,$time_late,$option);
            } else {
                // do nothing
            }   
        }
        $formula_processed = "";
        for ($i = 0; $i < count($tokens); $i++) {
            $formula_processed = $formula_processed.' '.$tokens[$i];
        }
        // echo $formula_processed."</br>";die;

        $result = eval('return '.$formula_processed.';');

        return $result;
    }

    public static function calculateSpecial_2( $formula ,$personnel_id='',$time='',$type='',$time_late='',$option='') {
        $tokens = explode(";", $formula);
        for ($i = 0; $i < count($tokens); $i++) {
            if (is_numeric($tokens[$i])) {
                $tokens[$i] = BatvHelper::fake_data_special_2($tokens[$i],$personnel_id,$time,$type,$time_late,$option);
            } else {
                // do nothing
            }   
        }
        $formula_processed = "";
        for ($i = 0; $i < count($tokens); $i++) {
            $formula_processed = $formula_processed.' '.$tokens[$i];
        }
        // echo $formula_processed."</br>";die;

        $result = eval('return '.$formula_processed.';');

        return $result;
    }

    public static function array_keys_multi(array $array){
        $keys = array();

        foreach ($array as $key => $value) {
            $keys[] = $key;

            if (is_array($value)) {
                $keys = array_merge($keys, BatvHelper::array_keys_multi($value));
            }
        }
        return $keys;
    }

    //Show ra danh sách ngày thứ 7 trong tuần
    public static function getSaturday($monthCurrent, $yearCurrent){
        $workdays_Saturday = array();
        $type = CAL_GREGORIAN;
        $day_count = cal_days_in_month($type, $monthCurrent, $yearCurrent);

        for ($i = 1; $i <= $day_count; $i++) {
            $date = $yearCurrent.'/'.$monthCurrent.'/'.$i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
            //if not a Saturday add day to array
            if($day_name == 'Sat'){
                $workdays_Saturday[] = $i;
            }
        }
        return $workdays_Saturday;
    }

    public static function getPagePaging(){
        return 10;
    }

    public static function PagingDataSpecial($arr){
        $request = request();
        $page = Input::get('page', 1); 
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;
        return  new LengthAwarePaginator(array_slice($arr, $offset, $perPage, true), count($arr), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);
    }

    public static function infoAttendanceSymbol( $id ){
        return \DB::table('attendance_type')
                    ->select('title','symbol')
                    ->where([
                        ['id', '=', $id],
                    ])
                    ->first();
    }

    public static function infoPersonnelSpecial( $id,$filed='' ){
        return  \DB::table('personnel')
                    ->where([
                        ['id', '=', $id],
                    ])
                    ->value($filed);
    }
    public static function infoConfigSettingOthers($type){
        return \DB::table('setting_others')->select('value')->where('type',$type)->value('value');
    }

    public static function infoPersonnelDetail($id){
        return \DB::table('personnel')->select('date_in')->where('id',$id)->value('date_in');
    }

    public static function calculateMonth($from,$to){
        $ts_from = strtotime($from);
        $ts_to = strtotime($to);

        $year_from = date('Y', $ts_from);
        $year_to = date('Y', $ts_to);

        $month_from = date('m', $ts_from);
        $month_to = date('m', $ts_to);
        $diff = (($year_to - $year_from) * 12) + ($month_to - $month_from) + 1;
        // if( $diff == 12 ){
        //     $day = BatvHelper::formatDate($from,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
        //     if( (int)$day > 1 ){
        //         $diff = (int)$day;
        //     }
        // }
        
        return $diff;
    }

    public static function calculateMonthSalary($from, $to, $personnel_id, $period)
    {
        $arr_to = explode('-', $to);
        $to = $to . '-'.cal_days_in_month(CAL_GREGORIAN,$arr_to[1],$arr_to[0]);
        $year_from =  BatvHelper::formatDate($from, "Y-m-d", $formatDate = "Y", $timeFormat = "H:i:s", false);
        $year_to =  BatvHelper::formatDate($to, "Y-m-d", $formatDate = "Y", $timeFormat = "H:i:s", false);
        $month = 0;
        $month_start =  BatvHelper::formatDate($from, "Y-m-d", $formatDate = "m", $timeFormat = "H:i:s", false);
        $flag = 1;
        $period = (int)$period;

        if ($year_from == $year_to) {
            $year = $year_from;
            $month_to =  BatvHelper::formatDate($to, "Y-m-d", $formatDate = "m", $timeFormat = "H:i:s", false);
            for ($j = (int) $month_start; $j <= $month_to; $j++) {
                $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($year . '-' . $j . '-01');

                if ($flag < $period) {
                    if (in_array($personnel_id, $infoUserIdExceptionalAttendance)) {
                        $month++;
                    } else {
                        $j = ($j < 10) ? '0' . $j : $j;
                        $nctt = 0;
                        $result  = BatvHelper::countAttendance($j, $year, $personnel_id, 1, 31);
                        $nctt = $nctt + BatvHelper::infoHoliday($personnel_id, $year . '-' . $j, $from, $to);
                        $nctc =  BatvHelper::count_working_days($year . '-' . $j. '-01', $year . '-' . $j. '-' .cal_days_in_month(CAL_GREGORIAN,$j,$year));

                        if ($result) {
                            foreach ($result as $key => $value) {
                                // Nếu đi làm 1 ngày 
                                if ($value->unit_date == 1) {
                                    $nctt++;
                                } elseif ($value->unit_date == 0.5) {
                                    $nctt = $nctt + 0.5;
                                }
                            }
                        }

                        if ($nctt/$nctc >= 0.7) {
                            $month++;
                        }
                    }
                } else {
                    $month++;
                }

                $flag++;
            }
        
        } else {
            for ($i = 0; $i <= ($year_to - $year_from); $i++) {
                $year = $year_from + $i;

                if ($i == ($year_to - $year_from)) {
                    $month_start = 1;
                    $month_to = BatvHelper::formatDate($to, "Y-m-d", $formatDate = "m", $timeFormat = "H:i:s", false);
                } elseif ($i == 0) {
                    $month_to = 12;
                } else {
                    $month_start = 1;
                    $month_to = 12;
                }


                for ($j = (int) $month_start; $j <= (int)$month_to; $j++) {
                    $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($year . '-' . $j . '-01');

                    if ($flag < $period) {
                        if (in_array($personnel_id, $infoUserIdExceptionalAttendance)) {
                            $month++;
                        } else {
                            $j = ($j < 10) ? '0' . $j: $j;
                            $nctt = 0;
                            $result  = BatvHelper::countAttendance($j, $year, $personnel_id, 1, 31);
                            $nctt = $nctt + BatvHelper::infoHoliday($personnel_id, $year . '-' . $j, $from, $to);
                            $nctc =  BatvHelper::count_working_days($year . '-' . $j. '-01', $year . '-' . $j. '-' .cal_days_in_month(CAL_GREGORIAN,$j,$year));
                            if ($result) {
                                foreach ($result as $key => $value) {
                                    // Nếu đi làm 1 ngày 
                                    if ($value->unit_date == 1) {
                                        $nctt++;
                                    } elseif ($value->unit_date == 0.5) {
                                        $nctt = $nctt + 0.5;
                                    }
                                }
                            }
        
                            if ($nctt/$nctc >= 0.7) {
                                $month++;
                            }
                        }
                    } else {
                        $month++;
                    }

                    $flag++;
                }
                
            }
        }

        return $month;

    }


    public static function infoHoliday($personnel_id,$time,$from,$to){
        $convert = explode("-",$time);
        // Lấy thông tin ngày vào của nhân viên
        $dateIn = BatvHelper::infoPersonnelDetail($personnel_id);
        // l?y danh sách holidays t? CSDL
        $data =  \DB::table('holiday_setting')
                    ->where([
                        ['month', '=', (string)$convert[1] ],
                        ['year', '=', (string)$convert[0] ],
                        ['status', '=', 1],
                    ])
                    ->get();
        $holidayDays = array();
        foreach ($data as $key => $value) {
            $filter = $value->year.'/'.$value->month.'/'.$value->day;
            $x = strtotime($filter);
            $y = strtotime($dateIn);
            if( $x >= $y ){
                $holidayDays[]  = $value->year.'-'.$value->month.'-'.$value->day;
            }
            // echo ( $x - $y );die;
        }
        $data_2 =  \DB::table('holiday_setting')
                    ->where([
                        ['month', '=', (string)$convert[1] ],
                        ['year', '=', '*' ],
                        ['status', '=', 1],
                    ])
                    ->get();
        foreach ($data_2 as $key => $value) {
            $holidayDays[]  = $value->year.'-'.$value->month.'-'.$value->day;
        }

        $checkMaternityLeave =  MaternityLeave::getLastMaternityLeave($personnel_id);

        if (count($holidayDays) > 0 && $checkMaternityLeave) {
           foreach ($holidayDays as $key => $value) {
               if (BatvHelper::handlingTime($value) >= BatvHelper::handlingTime($checkMaternityLeave->apply_from) && BatvHelper::handlingTime($value) <= BatvHelper::handlingTime($checkMaternityLeave->apply_to)) {
                   unset($holidayDays[$key]);
               }
           }
        }

        $from_toh = new \DateTime($from);
        $to_toh = new \DateTime($to);
        $to_toh->modify('+1 day');
        $interval = new \DateInterval('P1D');
        $periods = new \DatePeriod($from_toh, $interval, $to_toh);
        $tmp=0;

        foreach ($periods as $period) {
            if (in_array($period->format('Y-m-d'), $holidayDays) || in_array($period->format('*-m-d'), $holidayDays) ) {
                if( $period->format('N') == 6 ){
                    $tmp = $tmp + 0.5;
                }else{
                    $tmp++;
                }
            }
        }

        return $tmp;
    }

    //Show ra tên người quản trị
    public static function getInfoUser( $id ){
        return \DB::table('personnel')
                    ->where([
                        ['id', '=', $id],
                    ])
                    ->value('fullname');
    }


    // Show ra thông tin quỹ của nhân viên trong khoảng thời gian hiện tại
    public static function getInfoFundsbyPersonnel( $personnel_id ){
        return \DB::table('funds')
            ->leftJoin('funds_personnel', 'funds_personnel.funds_id', '=', 'funds.id')
            ->where('funds.status','=',1)
            ->where('funds_personnel.personnel_id','=',$personnel_id)
            ->where([
                    ['funds_personnel.apply_from', '<=', date('Y-m-d')],
                    ['funds_personnel.apply_to', '>=', date('Y-m-d')],
                ])
            ->value('title');
    }

    //Show ra Ratio
    public static function getInfoRatio( $time,$personnel_id ){
        $convert = explode("-",$time);
        $param = $convert[0].'-'.$convert[1];
        return \DB::table('personnel_job_ratio')
                    ->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$param)
                    ->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$param)
                    ->where([
                        ['personnel_ID', '=', $personnel_id],
                    ])
                    ->value('ratio');
    }

    //Show ra Ratio trong bảng Lương
    public static function getInfoRatioInSalary( $time, $personnel_id, $salary_1 = '', $salary_2 = '' ){
        $convert = explode("-",$time);
        $param = $convert[0].'-'.$convert[1];
        $data = \DB::table('personnel_job_ratio')
                    ->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$param)
                    ->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$param)
                    ->where([
                        ['personnel_ID', '=', $personnel_id],
                    ])
                    ->lists('ratio');
        if (count($data) >= 2) {
            if ($salary_1 > $salary_2) {
                return max($data);
            } else {
                return min($data);
            }
            
        } elseif((count($data) == 1)) {
            return $data[0];
        } else {
            return;
        }
        
    }

    //Tính lương bình quân
    public static function calLBQ( $salary_default,$salary_work,$standard_days ){
        $number = ($salary_work * $standard_days) /$salary_default;
        $x1 = floor($number);
        $x2 = round($number,2);
        if( ( $x2 - $x1 ) >= 0.5  ){
            $number_day_works = $x1 + 0.5;
        }else{
            $number_day_works = $x1;
        }
        if( $salary_default == $salary_work ){
            $lbq =  $salary_default/$standard_days;
        }else{
            $lbq = ( $salary_default - $salary_work )/( $standard_days - $number_day_works );
        }
        return $lbq;
    }
    // Hệ số lương theo thoi gian
    public static function getRatioByTime( $personnel_id,$time){
        return \DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$time)
                    ->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$time)
                    ->value('ratio');
    }

   // Hệ số lương hiện tại
    public static function getRatioCurrent( $personnel_id ){
        return \DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->whereRaw('apply_to = ( select max(apply_to) from personnel_job_ratio where personnel_id="'.$personnel_id.'")')
                    ->value('ratio');
    }

    //Ngày nâng lương gần nhất
    public static function infoDateSalaryIncreaseNearest( $personnel_id ,$time){
        if (\DB::table('personnel_job_ratio')->where('personnel_id', '=', $personnel_id)->count() == 2) {
            return \DB::table('personnel_job_ratio')->where('personnel_id', '=', $personnel_id)->orderBy('id', 'asc')->value('apply_from');       
        } else {
            $arr_ratio_first = \DB::table('personnel_job_ratio')
                        ->where([
                            ['personnel_id', '=', $personnel_id],
                        ])
                        ->orderBy('id', 'asc')
                        ->first(['apply_from', 'ratio']);

            if ($arr_ratio_first) {
                $arr_data = \DB::table('personnel_job_ratio')
                            ->where([
                                ['personnel_id', '=', $personnel_id],
                            ])
                            ->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$time)
                            ->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$time)
                            ->first(['apply_from', 'ratio']);
                if($arr_data != null){
                    if ($arr_ratio_first->ratio == $arr_data->ratio) {
                        return $arr_ratio_first->apply_from;
                    } else {
                        return $arr_data->apply_from;
                    }
                }
            }

        }
    }

   // Apply From Nearest
    public static function getApplyFromNearest( $personnel_id ){
        return \DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->whereRaw('apply_to = ( select max(apply_to) from personnel_job_ratio where personnel_id="'.$personnel_id.'")')
                    ->value('apply_from');
    }

   // Apply From Ratio Current
    public static function getApplyFromCurrentByTime( $personnel_id,$time ){
        $month = BatvHelper::formatDate($time,"Y-m", $formatDate="m",$timeFormat="H:i:s",false);
        $year = BatvHelper::formatDate($time,"Y-m", $formatDate="Y",$timeFormat="H:i:s",false);
        $time = ( $month == 12 )?date('Y').'-01' : date('Y').'-07';
        return \DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"=",$time)
                    ->value('apply_from');
    }

   // Tính số tháng truy lĩnh
    public static function getMonthTL( $personnel_id,$type,$options,$time ){
        $month = ( BatvHelper::infoPersonnelSpecial($personnel_id,'salary_frequency') )*12;
        $time_nlgn = BatvHelper::infoDateSalaryIncreaseNearest( $personnel_id,$time );
        $day_current_nlgn = BatvHelper::getApplyFromCurrentByTime( $personnel_id,$time );
        $apply_from = date('Y-m-d',strtotime(BatvHelper::formatDate(date("Y-m-d", strtotime("+".$month. "month", strtotime($time_nlgn))),"Y-m-d", $formatDate="m/d/Y",$timeFormat="H:i:s",false) . "+1 days"));
        $apply_to = date('Y-m-d',strtotime($day_current_nlgn . "-1 days"));
        $date1 = new DateTime( $time_nlgn );
        $date2 = new DateTime( $day_current_nlgn );
        $diff = $date1->diff($date2);
        $result = '';
        if( $type > 0 && $options >= 1){ // Nếu định kỳ và đã được duyệt thì tính còn ngược lại đột xuất thì số tháng truy lĩnh = 0
            if( $diff ){
                $param['month'] = $diff->m;
                $param['day'] = $diff->d;

                if( $diff->m > 0 || $diff->d > 0){
                    $date_apply_from = new DateTime( $apply_from );
                    $date_apply_to = new DateTime( $apply_to );
                    $diff_center = $date_apply_from->diff($date_apply_to);
                    $param['month'] = $diff_center->m;
                    $param['day'] = $diff_center->d;
                    if( $param['month'] > 0 ){
                        $result .= $param['month'].' tháng ';
                    }
                    if( $param['day'] > 0 ){
                        $result .= $param['day'].' ngày ';
                    }
                }
            }
        }
        return $result;
    }
   // Hệ số lương ( TH đặc biệt dành cho việc xem hệ số  lương khi được xét năng lương)
    public static function getRatioSpecial( $personnel_id,$time ){
        return \DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$time)
                    ->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$time)
                    ->value('ratio');
    }
   // Tinh ngaỳ nâng lương gần nhất
    public static function getTimeSalaryNearest( $personnel_id ){
        $id = \DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->whereRaw('apply_to = ( select max(apply_to) from personnel_job_ratio where personnel_id="'.$personnel_id.'")')
                    ->value('id');
        return \DB::table('personnel_job_ratio')
                    ->select('ratio','apply_from')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->whereRaw('apply_to = ( select max(apply_to) from personnel_job_ratio where id<>"'.$id.'" and personnel_id="'.$personnel_id.'")')
                    ->first();
    }
    //Lấy ra ID hệ số lương hiện tại
    public static function getIdbyRatioCurrent( $personnel_id ){
        return \DB::table('personnel_job_ratio')
                    ->where([
                        ['personnel_id', '=', $personnel_id],
                    ])
                    ->whereRaw('apply_to = ( select max(apply_to) from personnel_job_ratio where personnel_id="'.$personnel_id.'")')
                    ->value('id');
    }


    public static function detailTotalPointCriteria($id,$year,$type,$turns){
        $data =  \DB::table('personnel_evaluation')
              ->leftJoin('personnel_evaluation_details', 'personnel_evaluation_details.personnel_evaluation_id', '=', 'personnel_evaluation.id')
              ->leftJoin('personnel', 'personnel.id', '=', 'personnel_evaluation.personnel_id')
              ->leftJoin('evaluation_criteria', 'evaluation_criteria.id', '=', 'personnel_evaluation_details.criteria_id')
              ->select('personnel.fullname','personnel_evaluation.personnel_id','personnel_evaluation.comment', 'personnel_evaluation_details.*','evaluation_criteria.criteria_content','evaluation_criteria.criteria_weight','evaluation_criteria.criteria_group_id')
              ->where('personnel_evaluation.personnel_id', $id)
              ->where('personnel_evaluation.date', $year)
              ->where('personnel_evaluation.turns','=',$turns)
              ->where('personnel_evaluation_details.type', $type)
              ->orderBy('evaluation_criteria.id', 'ASC')
              ->get();
        $total_point = 0;
        if( $data ){
            foreach ($data as $key => $val) {
                $total_point += ( $val->point * $val->criteria_weight*BatvHelper::pointCriteriaGroup($val->criteria_group_id) ); 
            }
        }
        return $total_point;
    }
    public static function countAttendance_CP($attendance_month,$attendance_year,$attendance_day_1,$attendance_day_2,$personnel_id){
        $result =  \DB::table('personnel_attendance')
                    ->select('attendance_type_id','attendance_day','attendance_month','attendance_year','time_late','unit_date')
                    ->where([
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                        ['personnel_id', '=', $personnel_id],
                        ['attendance_day', '>=', $attendance_day_1],
                        ['attendance_day', '<=',$attendance_day_2],
                    ])
                    ->where(function($query){
                                  // $query->where('attendance_type_id',2)->orWhere('attendance_type_id', 12);  
                        $query->where('attendance_type_id',4);   
                    })
                    ->get();
        $tmp = 0;
        if( $result ){
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
    public static function countAttendance_KP($attendance_month,$attendance_year,$attendance_day_1,$attendance_day_2,$personnel_id){
        $result =  \DB::table('personnel_attendance')
                    ->select('attendance_type_id','attendance_day','attendance_month','attendance_year','time_late','unit_date')
                    ->where([
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                        ['personnel_id', '=', $personnel_id],
                        ['attendance_day', '>=', $attendance_day_1],
                        ['attendance_day', '<=',$attendance_day_2],
                    ])
                    ->where(function($query){
                                  // $query->where('attendance_type_id',2)->orWhere('attendance_type_id', 12);  
                        $query->where('attendance_type_id',10);   
                    })
                    ->get();
        $tmp = 0;
        if( $result ){
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

    public static function countTooLateAttendance( $month,$year,$day_1,$day_2,$personnel_id ){
        $result =  \DB::table('personnel_attendance')
               ->select('*')
               ->where([
                    ['attendance_month', '=', $month],
                    ['attendance_year', '=', $year],
                    ['attendance_day', '>=', $day_1],
                    ['attendance_day', '<=',$day_2],
                    ['personnel_id', '=', $personnel_id],
                    ['attendance_type_id', '=',12],
                    ['time_late', '>', \Config::get('app.time_late')],
                ])
               ->get();
        $tmp = 0;
        if( $result ){
            foreach ($result as $key => $value) {
                $tmp = $tmp+0.5;
            }
        }
        return $tmp;
    }

    // public static function getTurns(){
    //     if( date('m') == 6){
    //         $turns = 1;
    //     }elseif( date('m') == 12 ){
    //         $turns = 2;
    //     }else{
    //         $turns = 0;
    //     }  
    //     return $turns;
    // }

    public static function getTurnsDefault(){
        return ( date('m') >= 1 && date('m') <= 6 )? 1: 2;
    }

    //Danh sách nhân viên được đánh giá nâng lương
    public static function listPesonnelAssessment( $personnel_id ){
        $turns = BatvHelper::getTurnsDefault();
        $data = \DB::table('adhoc_salary_assessment')
                ->where([
                    ['year', '=', date('Y') ],
                    ['turns', '=', $turns ]
                ])
               ->where( 'time_send_mail', '>', \Carbon\Carbon::now()->subDays(4))
               ->lists('personnel_id');
        $data = json_decode(json_encode($data), true);
        if( in_array($personnel_id, $data) ){
            $result = 1;
        }else{
            $result = 0;
        }
        return $result;
    }

    // Đếm số ngày đi làm thực tế trong khoảng thời gian( Module Truy lĩnh )
    public static function countAttendanceSpecialTL( $attendance_month,$attendance_year,$personnel_id,$attendance_day_1,$attendance_day_2 ){
        $result =  \DB::table('personnel_attendance')
                    ->select('attendance_type_id','attendance_day','attendance_month','attendance_year','time_late','unit_date','type')
                    ->where([
                        ['attendance_month', '=', $attendance_month],
                        ['attendance_year', '=', $attendance_year],
                        ['personnel_id', '=', $personnel_id],
                        ['attendance_day', '>=', $attendance_day_1],
                        ['attendance_day', '<=',$attendance_day_2],
                    ])
                    ->where(function($query){
                        //$query->where('attendance_type_id',2)->orWhere('attendance_type_id', 12);  
                        $query->where('type',1);   
                    })
                    ->get();
        if( $result ){
            $from = $attendance_year.'-'.$attendance_month.'-'.$attendance_day_1;
            $to = $attendance_year.'-'.$attendance_month.'-'.$attendance_day_2;
            $time = $attendance_year.'-'.$attendance_month;
            $p_setting =  BatvHelper::infoDaysLevea($attendance_month,$attendance_year);
            // echo $from."</br>";
            $tmp = BatvHelper::infoHoliday( $personnel_id,$time,$from,$to );
            $special = 0;
            if( $result ){
                foreach ($result as $key => $value) {
                    if( $value->attendance_type_id == 12 ){
                        // Nếu đi muộn > số phút setting thì coi như  bị trừ nửa ngày công
                        if( $value->time_late > \Config::get('app.time_late') ){
                            //$tmp = $tmp-0.5;
                            $special = $special+0.5;
                        }
                    }elseif( $value->attendance_type_id == 10  ){// Nếu nghỉ không phép
                        if( $value->unit_date == 1 ){
                            $special++;
                        }elseif( $value->unit_date == 0.5 ){
                            $special = $special+0.5;
                        }
                    }

                    if( $value->type == 1 ){
                        // Nếu đi làm 1 ngày 
                        if( $value->unit_date == 1 ){
                            $tmp++; 
                        }elseif( $value->unit_date == 0.5 ){
                            $tmp = $tmp+0.5;
                        }
                    }
                }
            }
            $nctt = $tmp- $special;
        }else{
            $nctt = 0;
        }
        return $nctt;
    }


    public static function timeSalaryTL( $olds_nlgn,$personnel_id,$month='',$time){
        $time_olds_nlgn =  BatvHelper::formatDate(date("Y-m-d", strtotime("+".$month. "month", strtotime($olds_nlgn))),"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",false);
        $time_news_nlgn = BatvHelper::getApplyFromNearest( $personnel_id);
        $date_to = BatvHelper::formatDate($time_news_nlgn,"Y-m-d", $formatDate="m/d/Y",$timeFormat="H:i:s",false);

        $date_to = date('m/d/Y',strtotime($date_to . "-1 days"));
        $result = array();
        $month_time_old_nlgn = (int)BatvHelper::formatDate($time_olds_nlgn,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
        $day_time_old_nlgn = (int)BatvHelper::formatDate($time_olds_nlgn,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
        if( ( $month_time_old_nlgn == 1 && $day_time_old_nlgn == 1) || ( $month_time_old_nlgn == 7 && $day_time_old_nlgn == 1) ){
            $result['month'] = $result['days'] = $result['salary_official_default_old'] = $result['param'] = '';
        }else{

            $salary_official_default_old = BatvHelper::ltt('',$personnel_id,$time_olds_nlgn,$type=2,'',$option=1,'special');// 100% chính thức

            $m_from = BatvHelper::formatDate($time_olds_nlgn,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
            $d_from = BatvHelper::formatDate($time_olds_nlgn,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
            $m_to = BatvHelper::formatDate($date_to,"m/d/Y", $formatDate="m",$timeFormat="H:i:s",false);
            $year = BatvHelper::formatDate($time_olds_nlgn,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false);
            $nctt = $nctc = $special = $param = 0;
            for ($i=1; $i <=12 ; $i++) { 
                if( $i >= (int)$m_from  &&  $i <= (int)$m_to ){
                    $i = ($i < 10) ? '0'.$i : $i ;
                    $d_from = ((int)$d_from  < 10) ? '0'.(int)$d_from  : (int)$d_from;
                    if( (int)$d_from == 1 ){
                        $nctt = BatvHelper::countAttendanceSpecialTL( $i,$year,$personnel_id ,1,31 );
                    }else{
                        if ( $special == 0 ) {
                            $nctt = BatvHelper::countAttendanceSpecialTL( $i,$year,$personnel_id ,$d_from+1,31);
                        }else{
                            $nctt = BatvHelper::countAttendanceSpecialTL( $i,$year,$personnel_id ,1,31 );
                        }
                        $special++;
                    }
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,$i,$year);
                    $nctc =  BatvHelper::count_working_days($year.'-'.$i.'-01',$year.'-'.$i.'-'.$numberDay);

                    if ($nctt/$nctc >= 0.7) {
                        $param += $nctt/$nctc;
                    }
                }
            }
            $time_olds_nlgn = BatvHelper::formatDate($time_olds_nlgn,"Y-m-d", $formatDate="m/d/Y",$timeFormat="H:i:s",false);
            $date1 = new DateTime( $time_olds_nlgn );
            $date2 = new DateTime( $date_to );
            $diff = $date1->diff($date2);
            if( $diff ){
                $result['param'] = ( $nctt > 1 ) ?$param:'';
                $result['month'] = $diff->m;
                $result['days'] = $diff->d;
                $result['salary_official_default_old'] = $salary_official_default_old;
            }
        }

        return $result;
    }

    public static function infoPersonnelExceptionalAttendance($date_in,$personnel_id){
        return  \DB::table('exceptional_attendance')
                ->where('status', '=', 1)
                ->where('personnel_id', '=',$personnel_id)
                ->where(function ($query) use ($date_in) {
                    $query->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$date_in);
                    $query->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$date_in);
                })
                ->first();
    }

    public static function infoUserIdExceptionalAttendance($date_in){
        return  \DB::table('exceptional_attendance')
                ->select('personnel_id')
                ->where('status', '=', 1)
                ->where(function ($query) use ($date_in) {
                    $query->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"<=",$date_in);
                    $query->where(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),">=",$date_in);
                })
                ->lists('personnel_id');
    }

    public static function getContractsPersonnelbyUser($time, $personnel_id) {
        return  \DB::table('contract_personnel')
                ->where('personnel_id', $personnel_id)
                ->where(function ($query) use ($time) {
                    $query->where(\DB::raw("(DATE_FORMAT(apply_from,'%Y-%m'))"),"=",$time);
                    $query->orWhere(\DB::raw("(DATE_FORMAT(apply_to,'%Y-%m'))"),"=",$time);
                })
                ->orderBy('apply_to', 'asc')
                ->get();
    }

    public static function pointCriteriaGroup($id){
        return  \DB::table('evaluation_criteria_group')
                ->where('id', '=', $id)
                ->value('group_weight');
    }
    // Kiểm tra xem người dùng đó đã nghỉ việc vào tháng X nào không
    public static function checkPersonnelOut($personnel_id,$month,$year){
        $date_out = BatvHelper::infoPersonnelSpecial($personnel_id,'date_out');
        if( $date_out ){
            if( BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='m',$timeFormat='H:i:s',false) == $month && BatvHelper::formatDate($date_out, 'Y-m-d', $formatDate='Y',$timeFormat='H:i:s',false) == $year ){
                $check = true;
            }else{
                $check = false;
            }
        }else{
            $check = false;
        }
        return $check;
    }

    // Lấy thông tin hợp đồng của nhân viên
    public static function getContracts($personnel_id){
        return  \DB::table('contract')
              ->leftJoin('contract_personnel', 'contract_personnel.contract_id', '=', 'contract.id')
              ->select('contract_personnel.*','contract.*')
              ->where('contract_personnel.personnel_id', $personnel_id)
              ->get();
    }

    // Tính số năm thâm niên của nhân viên
    public static function getSeniority($personnel_id){
        $data = \DB::table('personnel')->select('date_in','date_out')->where('id',$personnel_id)->first();
        $date_in = $data->date_in;
        $date_to = ( $data->date_out != NULL )?$data->date_out:date('Y-m-d');

        $date1 = new DateTime( $date_in );
        $date2 = new DateTime( $date_to );
        $diff = $date1->diff($date2);
        $result = '';
        if( $diff ){
            $param['month'] = $diff->m;
            $param['year'] = $diff->y;
            if( $param['year'] > 0 ){
                $result .= $param['year'].' năm ';
            }
            if( $param['month'] > 0 ){
                $result .= $param['month'].' tháng ';
            }
        }
        return $result;
    }

    // Show ra ngày chốt thưởng Tết
    public static function getDayLatches($month,$year){
        $result =  \DB::table('personnel_income')
                    ->where([
                        ['month',$month],
                        ['year',$year],
                    ])
                    ->value('day_latches');

        $numberDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        return ($result > 0) ? BatvHelper::formatDate($result,'Y-m-d', $formatDate="d/m/Y",$timeFormat="H:i:s",false): $numberDay.'/'.$month.'/'.$year;
    }


        /*
        ====================THƯỞNG TẾT 2018=================================
        - Quy định mức tiền thưởng tết cán bộ nhân viên như sau:        
        -    Thời gian: Tính theo số tháng làm việc thực tế trong năm  dương lịch 2018
                + NCTT < 10 => n = 0
                + 10 <= NCTT <20 => n = 0.5
                + NCTT >=20 => n = 1
        -    Căn cứ tính: Mức Lương bình quân ( Lbq) và lương cơ bản( Lcb) theo quy định của công ty:
        -     Mức thưởng =1 tháng Lbq+ 1 tháng Lcb ± % theo KI (tổng)
                   KI(tổng)        =  KI nq+ KI tn+ KI hs
        Ví dụ:
        - Không đi muộn ngày nào, nghỉ đúng số ngày phép quy định, xin phép(trước khi nghỉ)=>KI nội quy: +20% mức thưởng (chỉ áp dụng với TH1)
        - Không đi muộn ngày nào, nghỉ đúng số ngày phép quy định, xin phép( trước khi nghỉ) nhưng thời gian công tác dưới 12 tháng => KI nội quy =20% mức thưởng*(Số tháng làm việc thực tế )/12
        - Đi muộn 01 tháng <= 45 phút, nghỉ đúng số ngày phép quy định, xin phép (trước khi nghỉ) => KI nội quy: +10% mức thưởng* (Số tháng làm việc thực tế )/12
        - Tham gia các phong trào của công ty=> KI nội quy: +5% mức thưởng(Tham gia đầy đủ các hoạt động văn hóa, văn nghệ, thể thao, picnic…)
        - KI thâm niên: Mỗi năm +2% mức thưởng ( Không bao gồm KI)
        - KI hiệu suất: Áp dụng cho cá nhân/team xuất sắc được biểu dương khen thưởng năm 2018:
             Cá nhân: +4%
             Team: +2%/người trong team
             CBNV có  cả 2 giải thì  KI(hs) = 4% + 2%= 6%*/
     

    // KI Nội quy
    public static function ki_rules($personnel_id,$year,$month,$day) {
        // $check_time_late = 0;
        $check_time_late_special = 0;
        $check_attendance_type = 0;
        $data = BatvHelper::infoPersonnelDetail($personnel_id); 
        $checkItem = 0;
        if( strtotime($data) <= strtotime( $year.'-01-01' ) ){
            $date_start = $year.'-01-01';
        }else{
            $date_start = $data;
            $day_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
            $checkItem = 1;
        }

        $month_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
        $date_end = date("Y-m-t", strtotime( $year.'-12-01' ));

        $month_EX = 0;
        $percentBonusTet = 0;

        // Biến kiểm tra nhân viên không vi phạm ngày nào 
        $total_time_late_special = 0;

        for ($i=(int)$month_start; $i <= 12 ; $i++) { 
            $nctt = 0;
            $day_end = 31;
            
            if( $i == (int)$month ){
                $day_end = $day - 1;
                $nctt = $nctt + BatvHelper::count_working_days(  $year.'-'.$i.'-'.$day,$year.'-'.$i.'-31' );
            }

            $i = ( $i < 10 )?'0'.$i:$i;

            if( $checkItem == 1 && $i == (int)$month_start ){ 
                $numberDay = cal_days_in_month(CAL_GREGORIAN,$i,$year);
                $from = $year.'-'.$i.'-'.$day_start;
                $to = $year.'-'.$i.'-'.$numberDay;

                $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,(int)$day_start,$day_end );
            }else{
                $from = $year.'-'.$i.'-01';
                $numberDay = cal_days_in_month(CAL_GREGORIAN,$i,$year);
                $to = $year.'-'.$i.'-'.$numberDay;

                $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$day_end );
            }

            $nctt = $nctt + BatvHelper::infoHoliday( $personnel_id,$year.'-'.$i,$from,$to );

            if( $result ){
                foreach ($result as $key => $value) {
                    // Nếu đi làm 1 ngày 
                    if( $value->unit_date == 1 ){
                        $nctt++; 
                    }elseif( $value->unit_date == 0.5 ){
                        $nctt = $nctt + 0.5;
                    }
                }
            }
            
            if( $nctt < 10 ){

            } elseif ( $nctt >= 10 && $nctt < 20 ) {
                $month_EX = $month_EX + 0.5;
            } else {
                $month_EX = $month_EX + 1;
            }
            


            // Đi muộn 01 tháng <= 45 phút
            $data_attendance = Salary::getCountLateAttendance( $i,$year,$personnel_id );
            $total_time_late = 0;

            foreach ($data_attendance as $key => $value) {
                $total_time_late += $value->time_late;
                

                //Xét xem có bữa nào nghỉ KP ko
                if ($value->attendance_type_id == 10) {
                    $check_attendance_type++;
                }
            }

            $total_time_late_special += $total_time_late;

            // if ( $total_time_late <= 45 ) {
            //     $check_time_late++;
            // }

            unset($nctt);

        }


        $arr_nn =  \DB::table('personnel_attendance')
                    ->select('personnel_id','attendance_type_id','unit_date')
                    ->where([
                        ['personnel_id', $personnel_id],
                        ['attendance_year', $year],
                    ])
                    ->where(function($query){
                        $query->whereIn('attendance_type_id',[3,4,10]);   
                    })
                    ->get();

        $check_nn = 0;

        foreach ($arr_nn as $key => $value) {
            if ($value->unit_date == 1) {
               $check_nn++;
            } else {
               $check_nn+= 0.5;
            }
        }

        //Nghỉ đúng số ngày phép quy định, xin phép (trước khi nghỉ)
        if ( $check_attendance_type == 0 && ($check_nn - 2) <= (12 - (int)$month_start + 1)) {
            // Không vi phạm ngày nào trong năm (đặc biệt): +10%
            if ($total_time_late_special == 0 && $month_EX == 12) {
                $percentBonusTet += 10;
            } 
            // else { // => Hưởng 20% rồi thì ko dc nhận dưới nữa
            //     //Đi muộn 01 tháng <= 45 phút  +10%
            //     if ($check_time_late == (12 - (int)$month_start + 1)) {
            //         $percentBonusTet += 10*($month_EX/12);
            //     }
            // }
        }

        KiNoiQuy::where('personnel_id', $personnel_id)->where('year', $year)->delete();

        if ($percentBonusTet == 10) {
            KiNoiQuy::insert([
                'personnel_id' => $personnel_id,
                'year' => $year,
            ]);
        }
        // Nếu là nhân viên tham gia các phong trào của công ty thì  +n% mức thưởng set trong DB
        $infoKiRules = KiRules::where('year', $year)->where('personnel_id', $personnel_id)->first();
        if( $infoKiRules ){
            // $percentBonusTet += $infoKiRules->ki/100;
            $percentBonusTet += $infoKiRules->ki;
        }

        return $percentBonusTet/100;
    }


    // KI Hiệu suất
    public static function ki_performance($personnel_id,$year) {
        $result =  \DB::table('ki_performance')
                    ->where([
                        ['personnel_id',$personnel_id],
                        ['year',$year],
                    ])
                    ->value('ki');
        return ($result != '') ? $result : 0;
    }

    // KI thâm niên
    public static function ki_seniority($personnel_id,$year,$month,$day) {
        // Cơ chế tính: hs=1 nếu làm đủ 12 tháng, dưới 12 tháng hệ số n/12
        $data = BatvHelper::infoPersonnelDetail($personnel_id); 

        // $checkItem = 0;
        // if( strtotime($data) <= strtotime( $year.'-01-01' ) ){
        //     $date_start = $year.'-01-01';
        // }else{
        //     $date_start = $data;
        //     $day_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
        //     // $checkItem = 1;
        // }


        $year_start = BatvHelper::formatDate($data,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false);

        $day_start = BatvHelper::formatDate($data,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);

        $month_EX = 0;

        for ($y = $year_start; $y <= $year ; $y++) { 
            for ($i = 1; $i <= 12 ; $i++) { 
                $nctt = 0;
                $day_start = 1;

                if ($y == $year_start) {
                    $month_start = BatvHelper::formatDate($data,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);

                    if ($i == $month_start) {
                        $day_start = BatvHelper::formatDate($data,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
                    }
                }

                $day_end = cal_days_in_month(CAL_GREGORIAN,$i,$y);

                $i = ( $i < 10 )?'0'.$i:$i;
                $from = $y.'-'.$i.'-'.$day_start;
                $to = $y.'-'.$i.'-'.$day_end;

                $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($y.'-'.$i.'-'.$day_start);
                
                if( in_array($personnel_id, $infoUserIdExceptionalAttendance) ){
                    $month_EX = $month_EX + 1;
                }else{
                    $result  = BatvHelper::countAttendance( $i,$y,$personnel_id,$day_start,$day_end );
                    $nctt = $nctt + BatvHelper::infoHoliday( $personnel_id,$y.'-'.$i,$from,$to );

                    if( $result ){
                        foreach ($result as $key => $value) {
                            // Nếu đi làm 1 ngày 
                            if( $value->unit_date == 1 ){
                                $nctt++; 
                            }elseif( $value->unit_date == 0.5 ){
                                $nctt = $nctt+0.5;
                            }
                        }
                    }
                    
                    if( $nctt < 10 ){

                    }elseif ( $nctt >= 10 && $nctt < 20 ) {
                        $month_EX = $month_EX + 0.5;
                    }else{
                        $month_EX = $month_EX + 1;
                    }
                }


                // echo $kaka."</br>";
                unset($nctt);
            }
        }


        // Thâm niên trước năm 2017 nếu có
        $seniority = BatvHelper::infoPersonnelSpecial($personnel_id,'seniority');

        return ( ($month_EX+$seniority)/12 )*0.02;
    }

    // Hệ số thâm niên thưởng Tết trong năm
    // public static function param_bonus_Tet($personnel_id, $year, $month, $day, $time_end_from='', $time_end_to='', $time_start_from='', $time_start_to='') {
    //     $checkItem = 0;
    //     $month_EX = 0;
        

    //     // Nếu là lần 1
    //     if (!empty($time_end_from) && !empty($time_end_to)) {
    //         $time_end_from = explode("-", $time_end_from);
    //         $time_end_to = explode("-", $time_end_to);
    //         $checkItem = 1;

    //         if ( $time_end_from[0] < $year ) {
    //             $day_start = $time_end_from[2];
    //             $month_start = 1;
    //             $month_end = $time_end_to[1];
    //         } else {
    //             $day_start = $time_end_from[2];
    //             $month_start = $time_end_from[1];
    //             $month_end = $time_end_to[1];
    //         }

    //     } elseif (!empty($time_start_from) && !empty($time_start_to)) {
    //         $checkItem = 1;
    //         $time_start_from = explode("-", $time_start_from);
    //         $day_start = $time_start_from[2];
    //         $month_start = $time_start_from[1];
    //         $month_end = 12;
    //     } else {
    //         // Cơ chế tính: hs=1 nếu làm đủ 12 tháng, dưới 12 tháng hệ số n/12
    //         $data = BatvHelper::infoPersonnelDetail($personnel_id); 
            
    //         if( strtotime($data) <= strtotime( $year.'-01-01' ) ){
    //             $date_start = $year.'-01-01';
    //         }else{
    //             $date_start = $data;
    //             $day_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
    //             $checkItem = 1;
    //         }

    //         $month_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
    //         $date_end = date("Y-m-t", strtotime( $year.'-12-01' ));
    //         $month_end = 12;
    //     }

        

    //     // Nếu là nhân viên được miễn chấm công thì mặc định sẽ dc trọn vẹn $month_EX = 12
    //     $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($year.'-'.$month.'-'.$day);
    //     if( in_array($personnel_id, $infoUserIdExceptionalAttendance) ){
    //         for ($i=(int)$month_start; $i <= $month_end ; $i++) { 
    //             $month_EX++;
    //         }
    //     }else{

    //         for ($i=(int)$month_start; $i <= $month_end ; $i++) { 
    //             $nctt = 0;
    //             $day_end = 31;
                
    //             if( $i == (int)$month ){
    //                 $day_end = $day - 1;
    //                 $nctt = $nctt + BatvHelper::count_working_days(  $year.'-'.$i.'-'.$day,$year.'-'.$i.'-31' );
    //             }

    //             if( $checkItem == 1 && $i == (int)$month_start ){ 

    //                 $i = ( $i < 10 )?'0'.$i:$i;
    //                 // $day_start = ( (int)$day_start < 10 )?'0'.$day_start:$day_start;

    //                 $numberDay = cal_days_in_month(CAL_GREGORIAN,$i,$year);
    //                 $from = $year.'-'.$i.'-'.$day_start;
    //                 $to = $year.'-'.$i.'-'.$numberDay;

    //                 $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,(int)$day_start,$day_end );
    //             }else{
    //                 $i = ( $i < 10 )?'0'.$i:$i;
    //                 $from = $year.'-'.$i.'-01';
    //                 $numberDay = cal_days_in_month(CAL_GREGORIAN,$i,$year);
    //                 $to = $year.'-'.$i.'-'.$numberDay;

    //                 $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$day_end );
    //             }

    //             $nctt = $nctt + BatvHelper::infoHoliday( $personnel_id,$year.'-'.$i,$from,$to );

    //             if( $result ){
    //                 foreach ($result as $key => $value) {
    //                     // Nếu đi làm 1 ngày 
    //                     if( $value->unit_date == 1 ){
    //                         $nctt++; 
    //                     }elseif( $value->unit_date == 0.5 ){
    //                         $nctt = $nctt+0.5;
    //                     }
    //                 }
    //             }
                
    //             if( $nctt < 10 ){

    //             }elseif ( $nctt >= 10 && $nctt < 20 ) {
    //                 $month_EX = $month_EX + 0.5;
    //             }else{
    //                 $month_EX = $month_EX + 1;
    //             }

    //             // echo $kaka."</br>";
    //             unset($nctt);
    //         }
    //     }
    //     return $month_EX;
    // }


    // Hệ số thâm niên thưởng Tết trong năm
    public static function param_bonus_Tet($personnel_id, $year, $month, $day, $time_end_from='', $time_end_to='', $time_start_from='', $time_start_to='',$dayLatches) {
        $checkItem = 0;
        $month_EX = 0;
        $day_end = 31;

        $dayLatches = explode("-",$dayLatches);
        $day_latches = (int)$dayLatches[2];
        $month_latches = (int)$dayLatches[1];
        $year_latches = (int)$dayLatches[0];

        // Nếu là lần 1
        if (!empty($time_end_from) && !empty($time_end_to)) {
            $time_end_from = explode("-", $time_end_from);
            $time_end_to = explode("-", $time_end_to);

            if ( $time_end_from[0] < $year ) {
                $month_start = 1;
                $month_end = $time_end_to[1];
            } else {
                $month_start = $time_end_from[1];
                $month_end = $time_end_to[1];
            }

        } elseif (!empty($time_start_from) && !empty($time_start_to)) {
            $time_start_from = explode("-", $time_start_from);
            $month_start = $time_start_from[1];
            $month_end = 12;
        } else {
            // Cơ chế tính: hs=1 nếu làm đủ 12 tháng, dưới 12 tháng hệ số n/12
            $date_start = BatvHelper::infoPersonnelDetail($personnel_id); 
            
            if( strtotime($date_start) <= strtotime( $year.'-01-01' ) ){
                $date_start = $year.'-01-01';
            }else{
                $day_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
            }

            $month_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
            $month_end = 12;
        }

        

        // Nếu là nhân viên được miễn chấm công thì mặc định sẽ dc trọn vẹn $month_EX = 12
        $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($year.'-'.$month.'-'.$day);
        if( in_array($personnel_id, $infoUserIdExceptionalAttendance) ){
            for ($i=(int)$month_start; $i <= $month_end ; $i++) { 
                $month_EX++;
            }
        }else{

            for ($i=(int)$month_start; $i <= $month_end ; $i++) { 
                $nctt = 0;
                $i = ( $i < 10 )?'0'.$i:$i;

                if( $i == (int)$month_latches ){
                    $day_end = $day_latches - 1;
                    $nctt = $nctt + BatvHelper::count_working_days(  $year_latches.'-'.$i.'-'.$day_latches,$year_latches.'-'.$i.'-' .cal_days_in_month(CAL_GREGORIAN,$month_latches,$year_latches) );
                }

                if (!empty($time_end_from) && !empty($time_end_to)) {
                    if ( $time_end_from[0] < $year ) {
                        if ($i == (int)$time_end_to[1]) {
                            $from = $year.'-'.$i.'-01';
                            $to = $year.'-'.$i.'-'.$time_end_to[2];
                            $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$time_end_to[2] );
                        } else {
                            $from = $year.'-'.$i.'-01';
                            $to = $year.'-'.$i.'-31';
                            // echo $from.'----'.$to;die;
                            $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$day_end );
                        }
                    } else {
                        
                        if ($i == (int)$time_end_from[1]) {   
                            $from = $year.'-'.$i.'-'.$time_end_from[2];
                            $to = $year.'-'.$i.'-31';
                            $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,$time_end_from[2],$day_end );
                        } elseif ($i == (int)$time_end_to[1]) {
                            $from = $year.'-'.$i.'-01';
                            $to = $year.'-'.$i.'-'.$time_end_to[2];
                            $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$time_end_to[2] );
                        } else {
                            $from = $year.'-'.$i.'-01';
                            $to = $year.'-'.$i.'-31';
                            $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$day_end );
                        }
                    }

                } elseif (!empty($time_start_from) && !empty($time_start_to)) {

                    if ($i == (int)$time_start_from[1]) {   
                        $from = $year.'-'.$i.'-'.$time_start_from[2];
                        $to = $year.'-'.$i.'-31';
                        $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,$time_start_from[2],$day_end );
                    }else {
                        $from = $year.'-'.$i.'-01';
                        $to = $year.'-'.$i.'-31';
                        $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$day_end );
                    }

                } else {
                    // Cơ chế tính: hs=1 nếu làm đủ 12 tháng, dưới 12 tháng hệ số n/12 
                    
                    if( strtotime($date_start) <= strtotime( $year.'-01-01' ) ){
                        $from = $year.'-'.$i.'-01';
                        $to = $year.'-'.$i.'-31';

                        if( $i == (int)$month_latches ){
                            $day_end = $day_latches - 1;
                            $nctt = $nctt + BatvHelper::count_working_days(  $year_latches.'-'.$i.'-'.$day_latches,$year_latches.'-'.$i.'-' .cal_days_in_month(CAL_GREGORIAN,$month_latches,$year_latches) );
                        }

                        $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$day_end );
                    }else{
                        $day_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="d",$timeFormat="H:i:s",false);
                        $month_start = BatvHelper::formatDate($date_start,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
                        
                        if ($i == (int)$month_start) {   
                            $from = $year.'-'.$i.'-'.$day_start;
                            $to = $year.'-'.$i.'-31';
                            $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,$day_start,$day_end );
                        }else {
                            $from = $year.'-'.$i.'-01';
                            $to = $year.'-'.$i.'-31';
                            $result  = BatvHelper::countAttendance( $i,$year,$personnel_id,1,$day_end );
                        }                       
                    }

                }

                $nctt = $nctt + BatvHelper::infoHoliday( $personnel_id,$year.'-'.$i,$from,$to );

                if( $result ){
                    foreach ($result as $key => $value) {
                        // Nếu đi làm 1 ngày 
                        if( $value->unit_date == 1 ){
                            $nctt++; 
                        }elseif( $value->unit_date == 0.5 ){
                            $nctt = $nctt+0.5;
                        }
                    }
                }
                
                if( $nctt < 10 ){

                }elseif ( $nctt >= 10 && $nctt < 20 ) {
                    $month_EX = $month_EX + 0.5;
                }else{
                    $month_EX = $month_EX + 1;
                }

                // echo $kaka."</br>";
                unset($nctt);
                $day_end = 31;
            }
        }

        return $month_EX;
    }

    // Lấy danh sách các ngày thứ 2 trong tháng
    public static function getMondays($y, $m)
    {
        return new \DatePeriod(
            new \DateTime("first monday of $y-$m"),
            \DateInterval::createFromDateString('next monday'),
            new \DateTime("last day of $y-$m")
        );
    }

    
    // Hàm cắt chuỗi thông minh ^-^
    public static function smartStr($string, $length = 150, $character = '...') {
        $limit = abs((int)$length);

        if(strlen($string) > $limit) {
            $string = preg_replace("/^(.{1,$limit})(\s.*|$)/s", '\1'.$character, $string);
        }
        
        return $string;
    }

    // Kiểm tra xem tháng đó có đủ điều kiện nhận phụ cấp ko
    public static function checkSubsidize ($personnel_id, $month, $year) {
        // $check = Personnel::where('id', '=', $personnel_id)
        //                 ->whereMonth('date_out', '=', $month)
        //                 ->whereYear('date_out', '=', $year)
        //                 ->count();

        // if ($check > 0) {
        //     return;
        // }

        return true;
    }

    public static function checkInsurrance ($personnel_id, $month, $year) {
        $check = MaternityLeave::getLastMaternityLeave($personnel_id);

        if ($check) {
            if($check->join_insurance == 1 && strtotime($year."-".$month) >= strtotime(date("Y-m", strtotime($check->apply_from))) && strtotime($year."-".$month) <= strtotime(date("Y-m", strtotime($check->apply_to))) ){
                if (date("m", strtotime($check->apply_from)) == $month && date("Y", strtotime($check->apply_from)) == $year && date("d", strtotime($check->apply_from)) >= 15) {
                    return true;
                } elseif (date("m", strtotime($check->apply_to)) == $month && date("Y", strtotime($check->apply_to)) == $year && date("d", strtotime($check->apply_to)) <= 15) {
                    return true;
                } else {
                    return;
                }
            }
        }

        return true;
        
        // $check = Personnel::where('id', '=', $personnel_id)
        //                 ->whereMonth('date_out', '=', $month)
        //                 ->whereYear('date_out', '=', $year)
        //                 ->count();

        // if ($check > 0) {
        //     return;
        // } else {
        //     $check = MaternityLeave::where('personnel_id', '=', $personnel_id)->first();

        //     if ($check) {
        //         if($check->join_insurance == 1 && strtotime($year."-".$month) >= strtotime(date("Y-m", strtotime($check->apply_from))) && strtotime($year."-".$month) <= strtotime(date("Y-m", strtotime($check->apply_to))) ){
        //             if (date("m", strtotime($check->apply_from)) == $month && date("Y", strtotime($check->apply_from)) == $year && date("d", strtotime($check->apply_from)) >= 15) {
        //                 return true;
        //             } elseif (date("m", strtotime($check->apply_to)) == $month && date("Y", strtotime($check->apply_to)) == $year && date("d", strtotime($check->apply_to)) <= 15) {
        //                 return true;
        //             } else {
        //                 return;
        //             }
        //         }
        //     }

        //     return true;
        // }
    }
}