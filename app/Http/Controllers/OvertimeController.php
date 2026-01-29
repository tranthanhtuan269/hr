<?php

namespace App\Http\Controllers;

use App\Helpers\BatvHelper;
use App\Http\Requests\ReportOvertTimeRequest;
use App\Http\Requests\SettingOvertTimeRequest;
use App\Http\Requests\RegisterOvertTimeRequest;
use App\Models\Evaluation;
use App\Models\RegisterOverTime;
use App\Models\OverTime;
use App\Models\OverTimeDetail;
use App\Models\Personnel;
use App\Models\SettingOvertime;
use App\Mylibs\Myfunction;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

// HIỆN TẠI LÀM THÊM CHỈ DÀNH CHO NHÂN VIÊN CHÍNH THỨC 100% TỪ ĐẦU THÁNG
class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $days_config = 0;
        $arr_day_overtime = [];
        $date_current = date('Y-m-d');
        $user_id = Auth::user()->id;
        // Nếu là nhân viên chính thức mới được đăng ký làm thêm giờ
        $check_contract = \DB::table('contract_personnel')->where('contract_id', 2)->where('personnel_id', $user_id)->where('apply_from', '<=', $date_current)->count();

        if ($check_contract > 0) {
            $overtime = OverTime::where('personnel_id', $user_id)->where('apply_from', '<=', $date_current)->where('apply_to', '>=', $date_current)->first();
            $overtime = OverTime::leftJoin('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
                            ->select('overtime.*')
                            ->where('overtime.personnel_id', Auth::user()->id)
                            ->whereDate('overtime_detail.time_day','=', $date_current)
                            ->first();
            if ($overtime) {
                $check_contract = 0;
            }
        }

        if ($request->selectMonth != "") {
            $month = $request->selectMonth;
            $year = $request->selectYear;
        } else {
            $month = date('m');
            $year = date('Y');
        }

        $data = \DB::table('personnel')
            ->join('overtime', 'personnel.id', '=', 'overtime.personnel_id')
            ->join('overtime_detail', 'overtime.id', '=', 'overtime_detail.over_time_id')
            ->select('personnel.fullname', 'overtime_detail.*', 'overtime.*')
            ->where('personnel.id', '=', $user_id)
            ->whereMonth('overtime_detail.time_day', '=', $month)
            ->whereYear('overtime_detail.time_day', '=', $year)
            ->orderBy('overtime_detail.over_time_id', 'desc')
            ->orderBy('overtime_detail.day_id', 'asc')
            ->get();
 
        $list = [];
        foreach ($data as $key => $value) {
            if (!isset($list[$value->personnel_id]['fullname'])) {
                $list[$value->personnel_id]['fullname'] = $value->fullname;
                $list[$value->personnel_id]['count_info'] = 1;
                $list[$value->personnel_id]['total_hour_ok'] = 0;

                if ($value->score == 1) {
                    $list[$value->personnel_id]['total_hour_ok'] += $value->hour;
                }

                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['apply_from'] = $value->apply_from;
                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['apply_to'] = $value->apply_to;
                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['info'][] = [$value->hour, $value->content_report, $value->progress, $value->score, $value->day_id, $value->comment_manager, $value->time_day];
            } else {
                $list[$value->personnel_id]['count_info'] += 1;

                if ($value->score == 1) {
                    $list[$value->personnel_id]['total_hour_ok'] += $value->hour;
                }

                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['apply_from'] = $value->apply_from;
                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['apply_to'] = $value->apply_to;
                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['info'][] = [$value->hour, $value->content_report, $value->progress, $value->score, $value->day_id, $value->comment_manager, $value->time_day];
            }
        }

        $list = BatvHelper::PagingDataSpecial($list);

        $setting_overtime  = SettingOvertime::find(1);
        $number_day = date('N') + 1;

        $min_hour = $number_day <= 6 ? $setting_overtime->min_hour_day_normal : $setting_overtime->min_hour_day_holiday;
        $max_hour = $number_day <= 6 ? $setting_overtime->max_hour_day_normal : $setting_overtime->max_hour_day_holiday;

        $alert_re_register = $check_register = 0;
        $check_edit_register = [];
        $check_report_nearest = 0;

        $arr = RegisterOvertime::where('personnel_id', $user_id)->orderBy('id', 'desc')->first();
        $register_null = 0;
        
        if (count($arr)> 0) {
            $days_config = $arr->days_config;

            $check_report_nearest = Overtime::join('overtime_detail', 'overtime.id', '=', 'overtime_detail.over_time_id')
                                                    ->join('register_overtime', 'register_overtime.id', '=', 'overtime_detail.register_overtime_id')
                                                    ->whereIn('register_overtime.status', [1,2] )
                                                    ->where('overtime.personnel_id', '=', $user_id)
                                                    ->whereDate('time_day', '>=', date('Y-m-d', strtotime("-". $days_config ." days", strtotime($date_current))))
                                                    ->count();
       
            if ($check_report_nearest == 0) {
                
                $check_register = RegisterOvertime::where('personnel_id', $user_id)
                                                    ->where('id', $arr->id)
                                                    ->where('status', 2)
                                                    ->whereDate('updated_at', '>=', date('Y-m-d', strtotime("-". $days_config ." days", strtotime($date_current))))
                                                    ->count();
 
                if ($check_register == 0) {
                    $check_edit_register = RegisterOvertime::where('personnel_id', $user_id)
                                                            ->where('id', $arr->id)
                                                            ->where('status', 1)
                                                            ->whereDate('created_at', '>=', date('Y-m-d', strtotime("-". $days_config ." days", strtotime($date_current))))
                                                            ->first();                                    
                } 
       
                if ($check_register > 0 || (count($check_edit_register) > 0 && $setting_overtime->report_permission == 1)) {
                    $check_register = 1;
                    $check_report_nearest  = 1;
                } elseif (count($check_edit_register) > 0 && $setting_overtime->report_permission == 0) {
                    $check_report_nearest  = 1;
                }
                
            } else {
                    $check_register = 1;
                    $check_report_nearest  = 1;
            }
            

        } else {
            $check_report_nearest  = 1;
        }

        return view('layouts.lam-them-gio.index', compact('days_config','check_register', 'check_report_nearest', 'setting_overtime', 'check_edit_register', 'check_contract', 'list', 'min_hour', 'max_hour', 'alert_re_register'));
    }

    public function registerOvertimeAjax(RegisterOvertTimeRequest $request) {
        $user_id = Auth::user()->id;

        $overtime = new RegisterOvertime;
        $overtime->personnel_id = $user_id;
        $overtime->content = $request->content;
        $overtime->type = $request->type;
        $overtime->created_at = date('Y-m-d');
        $overtime->save();

        // Gửi Email cho thông tin quản lý cấp gần nhất của quản lý
        $info_user = Personnel::getCurrentInfo($user_id);
        $info_manager = Personnel::getCurrentInfo( $info_user->manager_id );
        $email = $info_manager->email;

        if( $info_manager->id == $info_user->id ){
           $myfunc =  new Myfunction();
           $tmp=  $myfunc->categoryParent($info_user->department_id);   
           $department_id =  BatvHelper::array_keys_multi($tmp);
           foreach ($department_id as $value) {
                $arr_manager_id[] = Evaluation::infoDepartment( $value );
           }
           foreach ($arr_manager_id as $value) {
                if( $info_user->id != $value ){
                    $email = Personnel::getCurrentInfo( $value )->email;
                    break;
                }
           }

        }

        $subject = '[HR] Yêu cầu đăng ký làm thêm giờ của nhân viên '.$info_user->fullname;
        $content_mail = array(
                            'content' => 'Vui lòng truy cập vào đường dẫn <a href="' . url('toh_hrm/lam-them-gio/quan-ly?type=1') .'">tại đây</a> để xem chi tiết thông tin.',
                        );
        \Mail::send('emails.notification_result_register_overtime', $content_mail, function($message) use ($email, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->to($email)->subject($subject);
        });

        return \Response::json(array('status' => 200, 'message' => 'Đã gửi thông tin đăng ký làm thêm giờ thành công!'));
    }

    public function editRegisterOvertimeAjax(RegisterOvertTimeRequest $request) {
        $overtime = RegisterOvertime::where('personnel_id', Auth::user()->id)->where('status', 1)->first();
        $overtime->content = $request->content;
        $overtime->type = $request->type;
        $overtime->save();
        return \Response::json(array('status' => 200, 'message' => 'Cập nhật thông tin đăng ký làm thêm giờ thành công!'));
    }

    public function approvedRegisterOvertime(Request $request) {
        $overtime = RegisterOvertime::find($request->id);
        $overtime->status = 2;
        $overtime->days_config = $request->days_config;
        $overtime->updated_by = Auth::user()->id;
        $overtime->updated_at = date('Y-m-d');
        $overtime->save();

        // Gửi mail cho NV
        $email_cc = Auth::user()->email;
        $email = Personnel::find($overtime->personnel_id)->email;
        $subject = '[HR] Thông báo kết quả yêu cầu đăng ký làm thêm giờ từ công ty';
        $content_mail = array(
                            'content' => 'Yêu cầu đăng ký làm thêm giờ của bạn đã được phê duyệt!',
                            'list_work' => $request->list_work,
                            'days_config' => $request->days_config,
                        );
        \Mail::send(['html' => 'emails.notification_result_register_overtime'], $content_mail, function($message) use ($email, $email_cc, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->cc($email_cc);
            $message->to($email)->subject($subject);
        });

        return \Response::json(array('status' => 200, 'message' => 'Đã phê duyệt thành công!'));
    }

    public function rejectRegisterOvertime(Request $request) {
        $user_id = Auth::user()->id;

        $overtime = RegisterOvertime::find($request->id);
        $overtime->status = 3;
        $overtime->updated_by = $user_id;
        $overtime->updated_at = date('Y-m-d');
        $overtime->save();

        // Nếu quản lý chối đăng ký thì cũng tự động từ chối báo cáo luôn.
        OvertimeDetail::where('register_overtime_id', $overtime->id)->delete();

        // Gửi mail cho NV
        $email_cc = Auth::user()->email;
        $email = Personnel::find($overtime->personnel_id)->email;
        $subject = '[HR] Thông báo kết quả yêu cầu đăng ký làm thêm giờ từ công ty';
        $content_mail = array(
                            'content' => 'Yêu cầu đăng ký làm thêm giờ của bạn đã bị từ chối!',
                            'reason' => $request->reason,
                        );
        \Mail::send('emails.notification_result_register_overtime', $content_mail, function($message) use ($email, $email_cc, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->cc($email_cc);
            $message->to($email)->subject($subject);
        });

        return \Response::json(array('status' => 200, 'message' => 'Đã từ chối thành công!'));
    }

    public function checkRejectRegisterOvertime(Request $request) {
        // Ktra xem có báo cáo nào được duyêt trong lần đk này ko, nếu có thông báo cho quản lý biết
        $check = OvertimeDetail::where('register_overtime_id', $request->id)->where('score', 1)->count();

        return \Response::json(array('status' => 200, 'message' => 'Đăng ký đã có báo cáo được duyệt, bạn vẫn muốn từ chối!', 'flag' => $check));
        
    }
    public function reportOvertimeAjax(ReportOvertTimeRequest $request)
    {
        $monday = date("Y-m-d", strtotime('monday this week'));

        for ($i = 2; $i < 9; $i++) {
            $arr_time_day[$i] = (\DateTime::createFromFormat('Y-m-d', $monday ))->modify('+' . ($i - 2) . ' day')->format('Y-m-d');
        }

        $date_report = BatvHelper::formatDate($request->day_format,'d-m-Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        // echo $date_report;die;
        // $date_report = $arr_time_day[$request->day];

        $overtime = OverTime::leftJoin('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
                            ->select('overtime.*')
                            ->where('overtime.personnel_id', Auth::user()->id)
                            ->whereDate('overtime_detail.time_day','=', $date_report)
                            ->first();
                            // dd($overtime);
        // $overtime = OverTime::where('personnel_id', Auth::user()->id)->where('apply_from', '<=', $date_report)->where('apply_to', '>=', $date_report)->first();
       
        if (!$overtime) {
            $overtime = new OverTime;
            $overtime->personnel_id = Auth::user()->id;
            $overtime->apply_from = $arr_time_day[2];
            $overtime->apply_to = $arr_time_day[8];
            $overtime->save();
        }

        $overtime_report = OverTimeDetail::where('over_time_id', $overtime->id)->where('day_id', $request->day)->first();
        $send_email = false;

        if (!$overtime_report) {
            $overtime_report = new OverTimeDetail;
            $score = 3;
            $send_email = true;
        } else {
            $score = $overtime_report->score;
        }

        if($score == 3) {
            $overtime_report->over_time_id = $overtime->id;
            $overtime_report->day_id = $request->day;
            $overtime_report->time_day = $date_report;
            $overtime_report->content_report = $request->content_report;
            $overtime_report->progress = $request->progress;
            $overtime_report->score = 3;

            $register_overtime = RegisterOvertime::where('personnel_id', Auth::user()->id)->orderBy('id', 'desc')->first();
            $overtime_report->register_overtime_id = $register_overtime->id;

            $overtime_report->hour = $request->hour;
            $overtime_report->created_at = date('Y-m-d H:i:s');
            $overtime_report->save();

            if ($send_email == true) {
                // Gửi Email cho thông tin quản lý cấp gần nhất của quản lý
                $info_user = Personnel::getCurrentInfo(Auth::user()->id);
                $info_manager = Personnel::getCurrentInfo( $info_user->manager_id );
                $email = $info_manager->email;

                if( $info_manager->id == $info_user->id ){
                   $myfunc =  new Myfunction();
                   $tmp=  $myfunc->categoryParent($info_user->department_id);   
                   $department_id =  BatvHelper::array_keys_multi($tmp);
                   foreach ($department_id as $value) {
                        $arr_manager_id[] = Evaluation::infoDepartment( $value );
                   }
                   foreach ($arr_manager_id as $value) {
                        if( $info_user->id != $value ){
                            $email = Personnel::getCurrentInfo( $value )->email;
                            break;
                        }
                   }

                }


                $subject = '[HR] Báo cáo làm thêm giờ của nhân viên '.$info_user->fullname;
                $content_mail = array(
                                    'content' => 'Vui lòng truy cập vào đường dẫn <a href="' . url('toh_hrm/lam-them-gio/quan-ly?type=2') .'">tại đây</a> để xem chi tiết thông tin.',
                                );
                \Mail::send('emails.notification_result_register_overtime', $content_mail, function($message) use ($email, $subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->to($email)->subject($subject);
                });
            }

            return \Response::json(array('status' => 200, 'message' => 'Báo cáo đã gửi thành công'));
        } else {
            return \Response::json(array('status' => 404, 'message' => 'Có lỗi xảy ra'));
        }

    }

    public function managerReportOvertimeAjax(Request $request)
    {
        $overtime_report = OverTimeDetail::where('over_time_id', $request->over_time_id)->where('day_id', $request->day_id)->first();
        if ($overtime_report) {
            if ($overtime_report->content_report != '') {
                $overtime_report->comment_manager = $request->comment_manager;

                if ($request->score == 1) {
                    $overtime_report->comment_manager = '';
                }

                $overtime_report->score = $request->score;
                $overtime_report->save();
                return \Response::json(array('status' => 200, 'message' => 'Phê duyệt thành công'));
            }
        }

        return \Response::json(array('status' => 404, 'message' => 'Nhân viên chưa báo cáo kết quả công việc nên không thể phê duyệt'));
    }

    public function managerOvertime(Request $request)
    { 
        $setting_overtime  = SettingOvertime::find(1);
        $user_id = Auth::user()->id;

        if ($request->selectMonth != "") {
            $month = $request->selectMonth;
            $year = $request->selectYear;
        } else {
            $month = date('m');
            $year = date('Y');
        }

        $myfunc = new Myfunction();
        $check = Evaluation::checkDepartmentbyManager($user_id);
        $tmp = [];

        foreach ($check as $key => $value) {
            $tmp[$value->id] = $myfunc->categoryChild($value->id, 'departments');
        }

        $list = [];
        $department_id = BatvHelper::array_keys_multi($tmp);

        if ($request->type == 1) {
            $status = $request->status;

            $approved = RegisterOverTime::leftJoin('personnel', 'personnel.id', '=', 'register_overtime.personnel_id')
                                    ->selectRaw('personnel.fullname,personnel.department_id,register_overtime.*')
                                    ->whereRaw('register_overtime.id in (select max(id) from register_overtime group by (personnel_id))')
                                    ->whereDate('register_overtime.created_at', '>', Carbon::now()->subDays(30))
                                    ->whereIn('personnel.department_id', $department_id)
                                    ->whereIn('register_overtime.status', [2,3])
                                    ->orderBy('register_overtime.id', 'desc')
                                    ->get();

            $pending = RegisterOverTime::leftJoin('personnel', 'personnel.id', '=', 'register_overtime.personnel_id')
                                    ->selectRaw('personnel.fullname,personnel.department_id,register_overtime.*')
                                    ->whereRaw('register_overtime.id in (select max(id) from register_overtime group by (personnel_id))')
                                    ->whereDate('register_overtime.created_at', '>', Carbon::now()->subDays(30))
                                    ->whereIn('personnel.department_id', $department_id)
                                    ->whereIn('register_overtime.status', [1])
                                    ->orderBy('register_overtime.id', 'desc')
                                    ->get();
            return view('layouts.lam-them-gio.manager-register', compact('approved', 'pending', 'setting_overtime'));
        } else {
            $list = OverTime::join('personnel', 'overtime.personnel_id', '=', 'personnel.id')
                ->join('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
                ->selectRaw("personnel.fullname,overtime.*")
                ->where('personnel.id', '<>', $user_id)
                ->whereIn('personnel.department_id', $department_id)
                ->whereMonth('overtime_detail.time_day', '=', $month)
                ->whereYear('overtime_detail.time_day', '=', $year)
                ->orderBy('overtime.id', 'desc');


            if ($request->show_reports_pending == 1) {
                $list->where('overtime_detail.score', 3);
            }

            $list = $list->groupBy('overtime.id')->paginate(10);

            $number_report_pending = OverTime::join('personnel', 'overtime.personnel_id', '=', 'personnel.id')
                ->join('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
                // ->selectRaw("overtime_detail.id")
                ->where('personnel.id', '<>', $user_id)
                ->whereIn('personnel.department_id', $department_id)
                ->whereMonth('overtime_detail.time_day', '=', $month)
                ->whereYear('overtime_detail.time_day', '=', $year)
                ->where('overtime_detail.score', 3)
                // ->groupBy('overtime.id')
                ->count();

            return view('layouts.lam-them-gio.manager', compact('list', 'setting_overtime', 'number_report_pending'));
        }

    }

    public function managerAttendanceOvertime(Request $request)
    {
        $depart = Personnel::listDepartment();
        $myfunc = new Myfunction();
        $select = 0;
        $ids = [];
        if ($request->input('selectDepart') != '') {
            $select = $request->input('selectDepart');
            $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart, 'departments');

            if (count($tmp) == 0) {
                $ids = array($request->selectDepart);
            } else {
                $ids = BatvHelper::array_keys_multi($tmp);
            }
        }
        $department = $myfunc->callProcessSelect($depart, 0, '', $select);

        if ($request->selectMonth != "") {
            $month = $request->selectMonth;
            $year = $request->selectYear;
        } else {
            $month = date('m');
            $year = date('Y');
        }

        $data = \DB::table('personnel')
            ->join('overtime', 'personnel.id', '=', 'overtime.personnel_id')
            ->join('overtime_detail', 'overtime.id', '=', 'overtime_detail.over_time_id')
            ->select('personnel.fullname', 'overtime_detail.*', 'overtime.*')
            ->whereMonth('overtime_detail.time_day', '=', $month)
            ->whereYear('overtime_detail.time_day', '=', $year)
            ->where(function ($query) use ($ids) {
                if (!empty($ids)) {
                    $query->whereIn('personnel.department_id', $ids);
                }
            })
            ->orderBy('overtime_detail.over_time_id', 'desc')
            ->orderBy('overtime_detail.day_id', 'asc')
            
            ->get();

        $list = [];
        foreach ($data as $key => $value) {
            if (!isset($list[$value->personnel_id]['fullname'])) {
                $list[$value->personnel_id]['fullname'] = $value->fullname;
                $list[$value->personnel_id]['count_info'] = 1;
                $list[$value->personnel_id]['total_hour_ok'] = 0;

                if ($value->score == 1) {
                    $list[$value->personnel_id]['total_hour_ok'] += $value->hour;
                }

                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['apply_from'] = $value->apply_from;
                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['apply_to'] = $value->apply_to;
                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['info'][] = [$value->hour, $value->content_report, $value->progress, $value->score, $value->day_id, $value->comment_manager, $value->time_day];
            } else {
                $list[$value->personnel_id]['count_info'] += 1;

                if ($value->score == 1) {
                    $list[$value->personnel_id]['total_hour_ok'] += $value->hour;
                }

                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['apply_from'] = $value->apply_from;
                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['apply_to'] = $value->apply_to;
                $list[$value->personnel_id]['over_time_id'][$value->over_time_id]['info'][] = [$value->hour, $value->content_report, $value->progress, $value->score, $value->day_id, $value->comment_manager, $value->time_day];
            }
        }

        // echo '<pre>';
        // print_r($list);die;
        $list = BatvHelper::PagingDataSpecial($list);

        return view('layouts.lam-them-gio.manager-attendance', compact('list', 'department'));

    }

    public function settingOvertime()
    {
        $setting_overtime = SettingOvertime::find(1);
        return view('layouts.lam-them-gio.setting', compact('setting_overtime'));
    }

    public function settingOvertimeAjax(SettingOvertTimeRequest $request)
    {
        $check = OverTimeDetail::where('score', 3)->count();

        if ($check == 0) {
            $setting_overtime = SettingOvertime::find(1);
            $setting_overtime->min_hour_day_normal = $request->min_hour_day_normal;
            $setting_overtime->max_hour_day_normal = $request->max_hour_day_normal;
            $setting_overtime->min_hour_day_holiday = $request->min_hour_day_holiday;
            $setting_overtime->max_hour_day_holiday = $request->max_hour_day_holiday;
            $setting_overtime->timesheet_x_day = $request->timesheet_x_day;
            $setting_overtime->days_short = $request->days_short;
            $setting_overtime->days_long = $request->days_long;
            $setting_overtime->report_permission = $request->report_permission;
            $setting_overtime->save();
            return \Response::json(array('status' => 200, 'message' => 'Cập nhật thành công!'));
        }

        return \Response::json(array('status' => 404, 'message' => 'Xin vui lòng duyệt hết báo cáo trước khi cập nhật thông tin cấu hình!'));
    }

    public function infoOvertimeAjax(Request $request)
    {
        $monday = date("Y-m-d", strtotime('monday this week'));
        for ($i = 2; $i < 9; $i++) {
            $arr_time_day[$i] = (\DateTime::createFromFormat('Y-m-d', $monday ))->modify('+' . ($i - 2) . ' day')->format('Y-m-d');
        }
        
        $date_report = $arr_time_day[$request->day];
        $overtime = OverTime::where('personnel_id', Auth::user()->id)->where('apply_from', '<=', $date_report)->where('apply_to', '>=', $date_report)->first();
        $data = null;
        
        if ($overtime) {
            $data = OverTimeDetail::where('over_time_id', $overtime->id)->where( 'time_day', $date_report)->first();
        }

        return \Response::json(array( 'status' => 200, 'data' => $data ));
    }

    public function infoOvertimeSettingAjax(Request $request)
    {
        $setting_overtime  = SettingOvertime::find(1);
        $number_day = $request->day;
        $min_hour = $number_day <= 6 ? $setting_overtime->min_hour_day_normal : $setting_overtime->min_hour_day_holiday;
        $max_hour = $number_day <= 6 ? $setting_overtime->max_hour_day_normal : $setting_overtime->max_hour_day_holiday;

        return \Response::json(array('status' => 200, 'message' => 'oke', 'min_hour' =>  $min_hour, 'max_hour' =>  $max_hour));
    }

}
