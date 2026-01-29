<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Salary;
use Validator;
use App\Models\Personnel;
use Auth;
use App\Helpers\BatvHelper;
use App\Models\EmailConfig;
use App\Models\AgentLog;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Parameters;
use App\Models\OverTime;
use App\Models\SettingOvertime;
use App\Mylibs\Myfunction;
use App\Models\Evaluation;
use App\Models\Department;
use App\Models\LoanCapital;
use App\Models\ConfigWeb;
use App\Models\ConfigLoanCapital;
use App\Models\HistoryPayLoanCapital;

class CronjobsController extends Controller
{
    public function remindNotificationReviewSalary()
    {
        $day_remind_salary = \DB::table('setting_others')->where('id', 2)->value('value');

        if((int)date('d') == (int)$day_remind_salary && ((int)date('m') == 6 || (int)date('m') == 12)) {
            $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 13 );
            $listEmail = EmailConfig::getListEmailbyidPersonnel( explode(",",$infoConfigMail->mail_to) );
            $email = [];
    
            if( $listEmail ){
                foreach ($listEmail as $key => $value) {
                    $email[] = $value->email;
                }
            }
    
            $subject = $infoConfigMail->mail_subject;
            $content_mail = array(
                                'content'=>$infoConfigMail->mail_content,
                            );
            \Mail::send('emails.notification_remindNotificationReviewSalary', $content_mail,  function ($message) use ($email, $subject) {
                $message->from('nhansu@tohsoft.com', 'TOH');
                $message->to($email)->subject($subject);
            });  
        }
    }

    public function remindApprovedReportAutoBatv()
    {
        $list = OverTime::join('personnel', 'overtime.personnel_id', '=', 'personnel.id')
            ->join('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
            ->selectRaw("personnel.fullname,overtime.*,personnel.id as personnel_id")
            ->whereMonth('overtime_detail.time_day', '=', date('m'))
            ->whereYear('overtime_detail.time_day', '=', date('Y'))
            ->where('overtime_detail.score', 3)
            ->pluck('personnel_id')
            ->toArray();

        if ($list) {
            $email = [];

            foreach ($list as $personnel_id) {
                // Gửi Email cho thông tin quản lý cấp gần nhất của quản lý
                $info_user = Personnel::getCurrentInfo($personnel_id);
                $info_manager = Personnel::getCurrentInfo( $info_user->manager_id );
                $email[] = $info_manager->email;

                if( $info_manager->id == $info_user->id ){
                   $myfunc =  new Myfunction();
                   $tmp=  $myfunc->categoryParent($info_user->department_id);   
                   $department_id =  BatvHelper::array_keys_multi($tmp);
                   foreach ($department_id as $value) {
                        $arr_manager_id[] = Evaluation::infoDepartment( $value );
                   }
                   foreach ($arr_manager_id as $value) {
                        if( $info_user->id != $value ){
                            $email[] = Personnel::getCurrentInfo( $value )->email;
                            break;
                        }
                   }

                }
            }



            if (count($email) > 0) {
                $email = array_unique($email);
                $email[] = 'batv@tohsoft.com';
                $subject = '[HR] Thông báo phê duyệt báo cáo làm thêm giờ.';
                $content_mail = array(
                                    'content' => 'Vui lòng truy cập vào đường dẫn <a href="' . url('toh_hrm/lam-them-gio/quan-ly?type=2') .'">tại đây</a> để phê duyệt báo cáo làm thêm giờ của nhân viên trực thuộc quản lý.',
                                );
                \Mail::send('emails.notification_remind_approved_report', $content_mail, function($message) use ($email, $subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->to($email)->subject($subject);
                });
            }
        }
             
    }
    public function remindPayLoanCapitalAutoBatv()
    {
        $arr = HistoryPayLoanCapital::select('personnel.email', 'personnel.fullname', 'history_pay_loan_capital.*')
                                    ->leftJoin('loan_capital', 'loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
                                    ->leftJoin('personnel', 'personnel.id', '=', 'loan_capital.personnel_id')
                                    ->where('history_pay_loan_capital.month', '>', 0)
                                    ->where('history_pay_loan_capital.status', 0)
                                    ->where('loan_capital.pay', 2)
                                    ->where('loan_capital.final_settlement', 0)
                                    ->get();
        // echo '<pre>';
        // print_r($arr);die;
        if (count($arr) > 0) {
            foreach ($arr as $key => $value) {
                $datetime_1 = strtotime($value->repayment_period);
                $datetime_2 = strtotime(date('Y-m-d'));
                
                $secs = $datetime_2 - $datetime_1;
                $days = $secs / 86400;
                $remind_before_x_days = ConfigWeb::where('key', 'remind_before_x_days')->value('value');
                $remind_after_x_days = ConfigWeb::where('key', 'remind_after_x_days')->value('value');
                $remind_after_x_days = (int)("-".$remind_after_x_days);
    
                if ($days == $remind_after_x_days  || $days == $remind_before_x_days) { //Cấu hình chức năng tự động nhắc trả hàng tháng: Trước và sau 3 ngày
                    $remind = '';

                    if ($days == $remind_before_x_days) {
                        $remind = 'Đã quá hạn trả nợ định kỳ hàng tháng, xin vui lòng thanh toán.';
                    }

                    // Gửi mail cho nhân viên
                    $email_cc = ['hieu@tohsoft.com', 'hoanntt@tohsoft.com', 'batv@tohsoft.com'];
                    $email = $value->email;
                    $subject = '[HR] Thông báo trả nợ định kỳ hàng tháng.';
                    $content_mail = array(
                                        'data' => [
                                                        'fullname' => $value->fullname,
                                                        'repayment_period' => $value->repayment_period,
                                                        'month' => $value->month,
                                                        'principal' => $value->principal,
                                                        'redundancy_month_prev_money' => $value->redundancy_month_prev_money,
                                                        'wanting_month_prev_money' => $value->wanting_month_prev_money,
                                                        'interest' => $value->interest,
                                                        'interest_incurred' => $value->interest_incurred,
                                                        'remind' => $remind,
                                                    ],
                                    );
                    \Mail::send('emails.notification_remind_month_loan_capital', $content_mail, function($message) use ($email,$email_cc, $subject) {
                        $message->from('nhansu@tohsoft.com', 'TOH');
                        $message->cc($email_cc);
                        $message->to($email)->subject($subject);
                    });

                    echo 'Nhắc trả nợ thành công cho '.$value->fullname.'<br>';
                }
            }
        } else {
            echo 'Không có ai để nhắc!';
        }   
    }

    public function getAttendanceAuto(){
        $data = AgentLog::infobyTimeCurrent();
        if( $data ){    
            $getAllPersonnelCurrent = Attendance::listAttendance( $ids='',date('Y-m-d') );
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

    public function emailSalaryAuto(){
        $listAllSalary = Salary::listEmailSalary();
        $listEmail = array();

        foreach ($listAllSalary as $key => $value) {
            $listEmail[] = $value->email;
        }

        $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 2 );
        $subject = $infoConfigMail->mail_subject." tháng ".date('m')."/".date('Y');
        $content_mail = array(
                            'content'=>$infoConfigMail->mail_content,
                        );

        \Mail::send('emails.notification_salary', $content_mail,  function ($message) use ($listEmail, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->to($listEmail)->subject($subject);
        });
    }

    public function getSalaryDoneAuto( Request $request ){
        //Salary::updateStatusPersonnelIncome($arr,$request->selectMonth,$request->selectYear);
    }

    public function getSalaryAuto(){ }
    public function getAllowanceAuto(){ }
    public function getTaxInsurranceAuto(){ }

    public function sendMailRegisterOvertimeAuto(){
        $department = Department::where('parent_id', 0)->first();
        if ($department) {
            // dd($department->children);
            foreach ($department->children as $key => $value) {
                $myfunc =  new Myfunction();
                $tmp=  $myfunc->categoryChild($value->id,'departments');   
                $department_id =  BatvHelper::array_keys_multi($tmp);
                $department_id[] = $value->id;

               
                $date_current = date('Y-m-d');
                $date_isset_register = (\DateTime::createFromFormat('Y-m-d', $date_current))->modify('+6 day')->format('Y-m-d');
                $data = OverTime::join('personnel', 'overtime.personnel_id', '=', 'personnel.id')
                                ->join('departments', 'personnel.department_id', '=', 'departments.id')
                                ->select('personnel.email', 'personnel.fullname', 'personnel.department_id','overtime.*', 'departments.manager_id', 'departments.title')
                                ->where('overtime.apply_from','<=', $date_isset_register)
                                ->where('overtime.apply_to','>=', $date_isset_register)
                                ->whereIn('departments.id',$department_id)
                                ->get();

                $subject = '[HR] Đăng ký làm thêm giờ';
                $email = Personnel::find($value->manager_id)->email;
                // dd($data);
                $content_mail = array(
                                    'title'  => 'Danh sách nhân viên đăng ký làm thêm giờ tuần sau: ',
                                    'content'=> $data,
                                    'subject'=> $subject,
                                );

                \Mail::send(['html' => 'emails.notification_list_register_overtime'], $content_mail,  function ($message) use ($email,$subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->to($email)->subject($subject);
                });
            }
        }
        
    }

    public function updateAllEvaluateFaith() {
        $data = Personnel::getAllPersonnelCurrent();
        
        if ($data) {
            $score_tn = ConfigWeb::where('key', 'score_tn')->value('value');
            $datetime2 = new \DateTime(date('Y-m-d'));

            foreach ($data as $value) {
                $datetime1 = new \DateTime($value->date_in);
                $interval = $datetime2->diff($datetime1);
                $month_work =  (($interval->format('%y') * 12) + $interval->format('%m'));
                $score_position = round(($month_work/12)*$score_tn, 2);

                Personnel::where('id', $value->id)->update([
                    'score_seniority' => $score_position,
                    'score_faith' => $value->score_position + $score_position + $value->score_faith - ($value->score_seniority + $value->score_position ),
                ]);
            }
            
            return response()->json(['status' => 200, 'message' => 'Cập nhật thành công!']);
        }
        
    }

    public function calculatePayLoanCapitalAutoBatv()
    {
        $data = HistoryPayLoanCapital::whereDate('repayment_period', '=', date('Y-m-d'))
                                            ->where('status', 0)
                                            ->where('month', '>', 0)
                                            ->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $paid_money = 0;
                $loan_capital_id = $value->loan_capital_id;
                $loan_capital = LoanCapital::where('id', $loan_capital_id)->where('status', 2)->first();

                if ($loan_capital->pay == 2) {
                    $history_pay_loan_capital = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', $value->month)->first();
                    $history_pay_loan_capital->received_date = $history_pay_loan_capital->received_date;
                    $history_pay_loan_capital->paid_money = $paid_money;
                    $history_pay_loan_capital->status = 1;
                    $history_pay_loan_capital->save();
                
                    $config_loan_capital = ConfigLoanCapital::find($loan_capital->config_id);
                    // Kiểm tra xem số tiền cần trả có đủ ko, nếu ko đủ sẽ tính vào khoản lãi phát sinh tháng kế tiếp.
                    $total =  $history_pay_loan_capital->principal + $history_pay_loan_capital->interest + $history_pay_loan_capital->interest_incurred + $history_pay_loan_capital->wanting_month_prev_money - $history_pay_loan_capital->redundancy_month_prev_money;
                    $total = round($total);
                    
             
                    $history_pay_loan_capital_next = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', $history_pay_loan_capital->month + 1)->first();

                    if ($history_pay_loan_capital_next) {
                        $history_pay_loan_capital_next->interest_incurred = ($total - $paid_money)*(($config_loan_capital->deferred_interest/12)/100);
                        $history_pay_loan_capital_next->wanting_month_prev_money = $total - $paid_money;
                        $history_pay_loan_capital_next->redundancy_month_prev_money = 0;
                    } else {
                        $arr = [
                            'repayment_period' => date('Y-m-d', strtotime("+1 months", strtotime($history_pay_loan_capital->repayment_period))),
                            'month' => $history_pay_loan_capital->month + 1,
                            'remaining_principal' => 0,
                            'wanting_month_prev_money' => round($total - $paid_money),
                            'loan_capital_id' => $loan_capital_id,
                            'interest_incurred' => ($total - $paid_money)*(($config_loan_capital->deferred_interest/12)/100),
                        ];

                        HistoryPayLoanCapital::insert($arr);
                    }

                    if ($history_pay_loan_capital_next) {
                        $history_pay_loan_capital_next->save();
                    }
                    # code...
                }
            }
        } 
        

        echo 'Done';
    }
}