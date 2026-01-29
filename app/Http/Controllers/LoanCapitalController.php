<?php

namespace App\Http\Controllers;

use App\Helpers\BatvHelper;
use App\Http\Requests\UpdateLoanCapitalRequest;
use App\Http\Requests\InsertInterestRateConfigRequest;
use App\Http\Requests\UserRegisterLoanCapitalRequest;
use App\Http\Requests\UpdateInterestRateConfigRequest;
use App\Models\Role_User;
use App\Models\LoanCapital;
use App\Models\Personnel;
use App\Models\ConfigWeb;
use App\Models\ConfigLoanCapital;
use App\Models\Evaluation;
use App\Models\RegisterLoanCapital;
use App\Models\HistoryPayLoanCapital;
use App\Models\Departments;
use App\Models\ProposeScoreFaith;
use App\Models\EmailConfig;
use App\Mylibs\Myfunction;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoanCapitalController extends Controller
{
    // --------Table Loan_Capital------
    // 0 : xét duyệt
    // 1 : Đã đc TTT duyệt
    // 2 : TGD đã duyệt
    // 3 : TTT hủy  
    // 4 : Đã trả nợ hết, đóng đợt vay vốn này  

    // --------Table History_Pay_Loan_Capital------
    // 0 : cần duyệt
    // 1 : Đã nhận tiền
    // 2 : Chưa nhận tiền

    public function __construct()
    {
        $user_id = Auth::user()->id;
        $data = LoanCapital::leftJoin('config_loan_capital', 'config_loan_capital.id', '=', 'loan_capital.config_id')
                                        ->select('config_loan_capital.time_complete_file','loan_capital.disbursement_date', 'loan_capital.file', 'loan_capital.config_id')
                                        ->where('loan_capital.personnel_id', $user_id)
                                        ->where('loan_capital.status_file', 0)
                                        ->whereIn('loan_capital.status', [0,1,2])
                                        ->first();
                                       
        if ($data) {
            if ($data->config_id == 0) {
                \View::share('number_day_rest', '');
                \View::share('time_complete_file', true);
                \View::share('file', $data->file);
            } else {
                $time_complete_file = $data->time_complete_file;
                $disbursement_date = $data->disbursement_date;
                $datetime_1 = strtotime($disbursement_date);
                $datetime_2 = strtotime(date('Y-m-d'));
                $secs = $datetime_2 - $datetime_1;
                $days = $secs / 86400;
    
                if ($days <= $time_complete_file) {
                    \View::share('number_day_rest', $time_complete_file - $days);
                    \View::share('time_complete_file', true);
                    \View::share('file', $data->file);
                }

            }
        }

    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $loan_capital_id = LoanCapital::where('personnel_id', $user_id)->where('status', 2)->value('id');
        $config_loan_capital = $detail_loan_capital = [];
        $date_current = date('Y-m-d');

        if ($loan_capital_id) {
            $detail_loan_capital = LoanCapital::leftJoin('history_pay_loan_capital', 'loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
            ->leftJoin('personnel', 'loan_capital.personnel_id', '=', 'personnel.id')
            ->selectRaw('history_pay_loan_capital.*,DATE_FORMAT(history_pay_loan_capital.received_date , "%d/%m/%Y") as received_date,personnel.fullname,loan_capital.config_id,loan_capital.pay,loan_capital.id as loan_capital_id,loan_capital.status as loan_capital_status,loan_capital.final_settlement,loan_capital.partial_settlement')
            ->where('loan_capital.id', $loan_capital_id)
            ->whereIn('loan_capital.status', [2,4])
            ->orderBy('history_pay_loan_capital.repayment_period', 'ASC')
            ->get();
            // dd($detail_loan_capital);
            $config_loan_capital = ConfigLoanCapital::find($detail_loan_capital[0]->config_id);
        } else {
            $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->first();
        }

        $config_loan_capital_current = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->first();
        $status_pending = LoanCapital::where('personnel_id', $user_id)->whereIn('status', [0,1])->count();

        $loan_capital_pending = [];
        $updated_file = 0;
        
        if ($status_pending > 0) {
            $updated_file = LoanCapital::where('personnel_id', $user_id)->where('status', 0)->count();
            $status_pending = 'Bạn đang có yêu cầu vay chờ duyệt.';
            $loan_capital_pending = LoanCapital::where('personnel_id', $user_id)->whereIn('status', [0,1])->first();
        } else {
            $status_pending = 'Hiện tại bạn không có khoản vay mới nào.';
        }
        
        $score_faith = Personnel::find($user_id)->score_faith;

        $history_loan_capital = LoanCapital::leftJoin('history_pay_loan_capital', 'loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
        ->leftJoin('config_loan_capital', 'config_loan_capital.id', '=', 'loan_capital.config_id')
        ->selectRaw('config_loan_capital.*,history_pay_loan_capital.repayment_period,min(history_pay_loan_capital.repayment_period) as repayment_period,max(history_pay_loan_capital.received_date) as received_date,history_pay_loan_capital.id as aaaaaaaaaaaaa,SUM(history_pay_loan_capital.interest) as total_interest,SUM(history_pay_loan_capital.interest_incurred) as total_interest_incurred,SUM(history_pay_loan_capital.paid_money) as total_paid_money,loan_capital.*')
        ->where('loan_capital.personnel_id', $user_id)
        ->where('loan_capital.status', 4)
        ->where('history_pay_loan_capital.status', '>', 0)
        ->groupBy('loan_capital.id')
        ->get();
        // $history_loan_capital = LoanCapital::where('status', 4)->where('personnel_id', $user_id)->get();
        // dd($history_loan_capital);
        $personnel = Personnel::find($user_id);

        return view('layouts.vay-von.index', compact('personnel', 'history_loan_capital', 'updated_file', 'loan_capital_pending', 'score_faith', 'detail_loan_capital', 'config_loan_capital', 'status_pending', 'config_loan_capital_current'));
    }
    
    public function managerLoanCapital(Request $request)
    {
        $status = $request->input('status');
        $personnel_id = $request->input('personnel_id');
        $depart = Personnel::listDepartment();
        $myfunc = new Myfunction();
        $select = 0;
        $department_id = [];

        $arr_route = BatvHelper::listRolesByUser();

        $user_id = Auth::user()->id;

        if( in_array('quan-ly-tra-no-dinh-ky',$arr_route) ){
            $user_id = 1;
        }

        if ($user_id == 1) {
            if ($request->input('selectDepart') != '') {
                $select = $request->input('selectDepart');
                $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart, 'departments');
    
                if (count($tmp) == 0) {
                    $department_id = array($request->selectDepart);
                } else {
                    $department_id = BatvHelper::array_keys_multi($tmp);
                }
            } else {
                $check = Evaluation::checkDepartmentbyManager(Auth::user()->id);
                $tmp = [];
        
                foreach ($check as $key => $value) {
                    $tmp[$value->id] = $myfunc->categoryChild($value->id, 'departments');
                }
        
                $list = [];
                $department_id = BatvHelper::array_keys_multi($tmp);

            }
        } else {
            $check = Evaluation::checkDepartmentbyManager(Auth::user()->id);
            $tmp = [];
    
            foreach ($check as $key => $value) {
                $tmp[$value->id] = $myfunc->categoryChild($value->id, 'departments');
            }
    
            $list = [];
            $department_id = BatvHelper::array_keys_multi($tmp);
            
        }
        
        $department = $myfunc->callProcessSelect($depart, 0, '', $select);
        $query = Personnel::rightJoin('loan_capital', 'personnel.id', '=', 'loan_capital.personnel_id')
                                    ->leftJoin('config_loan_capital', 'loan_capital.config_id', '=', 'config_loan_capital.id')
                                    ->select('personnel.id as id','personnel.score_faith','personnel.fullname','loan_capital.id as loan_capital_id','loan_capital.*','config_loan_capital.*')
                                    ->where('loan_capital.status', '<>', 3)
                                    ->whereIn('personnel.department_id', $department_id);
                                    
        if ($status != -1 && $status != null) {
            $query = $query->where('loan_capital.status', $status);
        }

        if ($personnel_id != 0) {
            $query = $query->where('personnel.id', $personnel_id);
        }

        $list_personnel_loan_capital = $query->paginate(10);
        // dd($list_personnel_loan_capital);
        $date_current = date('Y-m-d');
        $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->first();

        $list_all_personnel = Personnel::getAllPersonnelCurrent();
        // Ktra xem tổng tiền quỹ còn đủ phê duyệt vay vốn của ko
        $total_fund_loan_capital = ConfigWeb::where('key', 'fund_loan_capital')->value('value');
        $total_disbursement_money = LoanCapital::whereIn('status', [2,4])->sum('max_money');
        $rest_fund_loan_capital = $total_fund_loan_capital - $total_disbursement_money;
        return view('layouts.vay-von.manager', compact('user_id', 'list_personnel_loan_capital', 'config_loan_capital', 'department', 'list_all_personnel', 'rest_fund_loan_capital'));
    }

    public function settingLoanCapital()
    {
        $list_personnel = Personnel::rightJoin('loan_capital', 'personnel.id', '=', 'loan_capital.personnel_id')
                                    ->select('personnel.id as id','personnel.fullname','loan_capital.score','loan_capital.max_money','loan_capital.month_time')
                                    ->where('personnel.status', '=', 1)
                                    ->where('personnel.date_out', '=', NULL)
                                    ->orWhere('personnel.date_out', '>', date('Y-m-d') )
                                    ->paginate(10);
        
        $config_fund_loan_capital = ConfigWeb::where('key', 'fund_loan_capital')->first();
        $config_score_tn = ConfigWeb::where('key', 'score_tn')->value('value');
        $config_score_tsp_tld = ConfigWeb::where('key', 'score_tsp_tld')->value('value');
        $config_score_tp_ptt = ConfigWeb::where('key', 'score_tp_ptt')->value('value');
        $config_score_ttt = ConfigWeb::where('key', 'score_ttt')->value('value');
        $config_score_min = ConfigWeb::where('key', 'score_min')->value('value');
        $config_remind_before_x_days = ConfigWeb::where('key', 'remind_before_x_days')->value('value');
        $config_remind_after_x_days = ConfigWeb::where('key', 'remind_after_x_days')->value('value');
        $config_email_tq = ConfigWeb::where('key', 'email_tq')->value('value');
        $config_loan_capital = ConfigLoanCapital::orderBy('id', 'ASC')->get();
        $disbursement_money = LoanCapital::whereIn('status', [2,4])->sum('max_money');
        $amount_collected = HistoryPayLoanCapital::sum('paid_money');
        $interest_and_interest_incurred = HistoryPayLoanCapital::where('status', 1)->sum('interest') + HistoryPayLoanCapital::where('status', 1)->sum('interest_incurred');
        return view('layouts.vay-von.setting', compact('config_remind_before_x_days', 'config_remind_after_x_days', 'config_email_tq', 'config_score_tn', 'config_score_tp_ptt', 'config_score_ttt', 'config_score_tsp_tld', 'config_score_min', 'list_personnel', 'config_fund_loan_capital', 'config_loan_capital', 'disbursement_money', 'amount_collected', 'interest_and_interest_incurred'));
    }

    public function updateLoanCapitalAjax(UpdateLoanCapitalRequest $request)
    {
        $loan_capital = LoanCapital::where('personnel_id', $request->personnel_id)->first();

        if (count($loan_capital) == 0) {
            $loan_capital = new LoanCapital();
            $loan_capital->personnel_id = $request->personnel_id;
        }

        $loan_capital->score = $request->score;
        $loan_capital->max_money = $request->max_money;
        $loan_capital->preferential_interest_rate = $request->preferential_interest_rate;
        $loan_capital->month_time = $request->month_time;
        $loan_capital->created_at = date('Y-m-d H:i:s');
        $loan_capital->updated_at = date('Y-m-d H:i:s');
        $loan_capital->save();

        return \Response::json(array('status' => 200, 'message' => 'Cập nhật thành công!'));
    }

    public function updateFundLoanCapitalAjax(Request $request)
    {
        $fund_loan_capital = ConfigWeb::where('key', 'fund_loan_capital')->first();
        $fund_loan_capital->value = $request->fund_money_real;
        $fund_loan_capital->created_at = date('Y-m-d H:i:s');
        $fund_loan_capital->updated_at = date('Y-m-d H:i:s');
        $fund_loan_capital->save();

        return \Response::json(array('status' => 200, 'message' => 'Cập nhật thành công!'));
    }

    public function infoInterestRateConfigAjax(Request $request)
    {
        $disbursement_date = BatvHelper::formatDate($request->disbursement_date,'d/m/Y', 'Y-m-d', 'H:i:s', false);
        $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $disbursement_date)->whereDate('apply_to', '>=', $disbursement_date)->first();

        if (!$config_loan_capital) {
            $config_loan_capital = ConfigLoanCapital::get();
            $config_loan_capital = ConfigLoanCapital::find($config_loan_capital[count($config_loan_capital) - 1]->id);
        }

        return \Response::json(array('status' => 200, 'message' => 'Ok', 'config_loan_capital' => $config_loan_capital));
    }

    public function checkInterestRateConfigAjax(Request $request)
    {
        $check = LoanCapital::where('config_id', $request->id)->count();

        if ($check == 0) {
            return \Response::json(array('status' => 200, 'message' => 'Oke'));
        } 

        return \Response::json(array('status' => 404, 'message' => 'Not oke'));
    }

    public function insertInterestRateConfigAjax(InsertInterestRateConfigRequest $request)
    {
        $config_loan_capital = new ConfigLoanCapital();
        $config_loan_capital->score_min = $request->score_min;
        $config_loan_capital->month_time_max = $request->month_time_max;
        $config_loan_capital->x_salary = $request->x_salary;
        $config_loan_capital->time_complete_file = $request->time_complete_file;
        $config_loan_capital->interest_file_late = $request->interest_file_late;
        $config_loan_capital->interest_rate = $request->interest_rate;
        $config_loan_capital->preferential_interest_rate = $request->preferential_interest_rate;
        $config_loan_capital->start_month_pay = $request->start_month_pay;
        $config_loan_capital->count_month_preferential = $request->count_month_preferential;
        $config_loan_capital->deferred_interest = $request->deferred_interest;
        $config_loan_capital->apply_from = BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        $config_loan_capital->apply_to = BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        $config_loan_capital->created_at = date('Y-m-d H:i:s');
        $config_loan_capital->updated_at = date('Y-m-d H:i:s');
        $config_loan_capital->save();

        return \Response::json(array('status' => 200, 'message' => 'Thêm mới thành công!'));
    }

    public function updateInterestRateConfigAjax(UpdateInterestRateConfigRequest $request)
    {
        $check = LoanCapital::where('config_id', $request->id)->count();
        $config_loan_capital = ConfigLoanCapital::find($request->id);

        if ($check == 0) {
            $config_loan_capital->month_time_max = $request->month_time_max;
            $config_loan_capital->x_salary = $request->x_salary;
            $config_loan_capital->time_complete_file = $request->time_complete_file;
            $config_loan_capital->interest_file_late = $request->interest_file_late;
            $config_loan_capital->score_min = $request->score_min;
            $config_loan_capital->interest_rate = $request->interest_rate;
            $config_loan_capital->preferential_interest_rate = $request->preferential_interest_rate;
            $config_loan_capital->start_month_pay = $request->start_month_pay;
            $config_loan_capital->count_month_preferential = $request->count_month_preferential;
            $config_loan_capital->deferred_interest = $request->deferred_interest;
            $config_loan_capital->apply_from = BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        }
        
        $config_loan_capital->apply_to = BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        $config_loan_capital->updated_at = date('Y-m-d H:i:s');
        $config_loan_capital->save();

        return \Response::json(array('status' => 200, 'message' => 'Cập nhật thành công!'));
    }

    public function deleteInterestRateConfigAjax(Request $request)
    {
        $check = LoanCapital::where('config_id', $request->id)->count();

        if ($check == 0) {
            $config_loan_capital = ConfigLoanCapital::find($request->id);
            $config_loan_capital->delete();
            return \Response::json(array('status' => 200, 'message' => 'Xóa thành công!'));
        } 

        return \Response::json(array('status' => 404, 'message' => 'Cấu hình đang hoạt động bạn không thể xóa được!'));
    }

    public function userRegisterLoanCapitalAjax(Request $request)
    {
        $personnel_id = Auth::user()->id;
        $check = LoanCapital::where('personnel_id', $personnel_id)->count();
        $flag = TRUE;
        
        if ($check > 0) {
            $check_detail = LoanCapital::where('personnel_id', $personnel_id)->whereIn('status', [0,1,2])->count();
            $flag = $check_detail > 0 ? FALSE : TRUE;
        }
        
        if ($flag) {
            $loan_purpose = $request->loan_purpose;
            $date_current = date('Y-m-d');
            $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->first();
            $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : $time = date('Y').'-12';
            $salary_official_default = BatvHelper::ltt('',Auth::user()->id,$time,$type=1,'',$option=1,$convert_ratio='');

            if ($loan_purpose == 3) {
                $rules = [
                    'max_money'                           => 'required|numeric|min:1|max:'.($config_loan_capital->month_time_max * $salary_official_default),
                    'month_time'                          => 'required|numeric|min:1|max:'.$config_loan_capital->month_time_max,
                    'disbursement_date_by_user'           => 'required|date_format:"d/m/Y"',
                    'loan_purpose'                        => 'required',
                    // 'info_receive_disbursement'           => 'required',
                    'another_purpose'                     => 'required',
                ];
    
                $messages = [
                    'max_money.required'                     => 'Số tiền vay không được để trống.',
                    'max_money.min'                          => 'Số tiền vay phải lớn hơn 0.',
                    'max_money.max'                          => 'Số tiền vay không được vượt quá '.number_format($config_loan_capital->month_time_max * $salary_official_default).' VND.',
                    'month_time.required'                    => 'Thời gian vay không được để trống.',
                    'month_time.min'                         => 'Thời gian vay phải lớn hơn 0.',
                    'month_time.max'                         => 'Thời gian vay không được vượt quá '.$config_loan_capital->month_time_max.' tháng.',
                    // 'info_receive_disbursement.required'     => 'Thông tin tài khoản nhận giải ngân không được để trống.',
                    'disbursement_date_by_user.required'     => 'Thời gian mong muốn giải ngân không được để trống.',
                ];
            } else {
                $rules = [
                    'max_money'                           => 'required|numeric|min:1|max:'.($config_loan_capital->month_time_max * $salary_official_default),
                    'month_time'                          => 'required|numeric|min:1|max:'.$config_loan_capital->month_time_max,
                    'disbursement_date_by_user'           => 'required|date_format:"d/m/Y"',
                    // 'info_receive_disbursement'           => 'required',
                    'loan_purpose'                        => 'required',
                ];
                
                $messages = [
                    'max_money.required'                   => 'Số tiền vay không được để trống.',
                    'max_money.min'                       => 'Số tiền vay phải lớn hơn 0.',
                    'max_money.max'                        => 'Số tiền vay không được vượt quá '.number_format($config_loan_capital->month_time_max * $salary_official_default).' VND.',
                    'month_time.required'                  => 'Thời gian vay không được để trống.',
                    'month_time.min'                      => 'Thời gian vay phải lớn hơn 0.',
                    'month_time.max'                       => 'Thời gian vay không được vượt quá '.$config_loan_capital->month_time_max.' tháng.',
                    // 'info_receive_disbursement.required'     => 'Thông tin tài khoản nhận giải ngân không được để trống.',
                    'disbursement_date_by_user.required'   => 'Thời gian mong muốn giải ngân không được để trống.',
                ];
            }

            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return \Response::json(array('status' => 404, 'message' => $validator->errors()));
            } else {
                $register_loan_capital = new LoanCapital();
                $register_loan_capital->personnel_id = $personnel_id;
                $register_loan_capital->max_money = $request->max_money;
                $register_loan_capital->month_time = $request->month_time;
                $register_loan_capital->disbursement_date_by_user = BatvHelper::formatDate($request->disbursement_date_by_user,'d/m/Y', 'Y-m-d', 'H:i:s', false);
                $register_loan_capital->loan_purpose = $loan_purpose;
                $register_loan_capital->another_purpose = $request->another_purpose;
                $register_loan_capital->pay = $request->pay;
                $register_loan_capital->disbursement_form = $request->disbursement_form;
                $register_loan_capital->info_receive_disbursement = $request->info_receive_disbursement;

                $arr_link_base64 = $request->file;

                if (count($arr_link_base64) > 0) {
                    $str_link_base64 = '';
                    foreach ($arr_link_base64 as $value) {
                        list($type, $value) = explode(';', $value);
                        list(, $value)      = explode(',', $value);
                        $value = base64_decode($value);
                        $file_name = round(microtime(true) * 1000) . '.png';
                        $str_link_base64 .= $file_name .',';
                        file_put_contents(public_path('images/') . $file_name, $value);
                    }

                    $register_loan_capital->file = rtrim($str_link_base64,",");
                }

                $register_loan_capital->save();

                // Gửi mail cho TTT
                $list_ttt = Departments::select('personnel.email', 'departments.id')->leftJoin('personnel', 'personnel.id', '=', 'departments.manager_id')->where('departments.parent_id', 17)->get();
                $myfunc = new Myfunction();
                $info_user = Personnel::find($personnel_id);
                $tmp = $myfunc->categoryParent($info_user->department_id);   
                $department_id =  BatvHelper::array_keys_multi($tmp);
                $email_ttt = '';

                foreach ($list_ttt as $value) {
                    if (in_array($value->id, $department_id)) {
                        $email_ttt = $value->email;
                        break;
                    }
                }

                // Nếu là TTT đăng ký thì sẽ gửi trưc tiếp cho TGD
                $info_user = Personnel::getCurrentInfo($personnel_id);
                $info_manager = Personnel::getCurrentInfo($info_user->manager_id);

                if ($info_manager->id == $personnel_id) {
                    $myfunc =  new Myfunction();
                    $tmp =  $myfunc->categoryParent($info_user->department_id);
                    $department_id =  BatvHelper::array_keys_multi($tmp);
                    foreach ($department_id as $value) {
                        $arr_manager_id[] = Evaluation::infoDepartment($value);
                    }
                    foreach ($arr_manager_id as $value) {
                        if ($info_user->id != $value) {
                            $email_ttt = Personnel::getCurrentInfo($value)->email;
                            break;
                        }
                    }
                }

                $subject = '[HR] Xem xét yêu cầu vay vốn của nhân viên';
                $content_mail = array(
                    'link' =>  url('toh_hrm/vay-von/quan-ly?selectDepart=0&status=-1&loan_capital_id=' . $register_loan_capital->id),
                    'fullname' => $info_user->fullname,
                );
                \Mail::send('emails.notification_approved_loan_capital', $content_mail, function ($message) use ($email_ttt, $subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->to($email_ttt)->subject($subject);
                });

                return \Response::json(array('status' => 200, 'message' => 'Gửi yêu cầu thành công!'));
            }
        } else {
            return \Response::json(array('status' => 401, 'message' => 'Bạn đã có khoản vay đang chờ duyệt hoặc khoản vay chưa được tất toán!'));
        }

    }

    public function reviewScoreLoanCapitalAjax(Request $request)
    { 
        $status = $request->status;
        $date_current = date('Y-m-d');
        $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->first();
        $score_min = $config_loan_capital->score_min;

        // if ($status == 1) {
        //     $rules = [
        //         'score'                               => 'required|numeric|min:'.$score_min.'|max:1000',
        //     ];
        //     $messages = [
        //         'score.required'                       => 'Điểm tín nhiệm không được để trống.',
        //         'score.min'                       => 'Nếu đồng ý phê duyệt thì điểm tín nhiệm phải lớn hơn hoặc bằng '.$score_min ,
        //         'score.max'                       => 'Điểm tín nhiệm không được quá 1000.',
        //     ];
        // } else {
        //     $rules = [
        //         'score'                               => 'required|numeric|min:0|max:1000',
        //     ];
        //     $messages = [
        //         'score.required'                       => 'Điểm tín nhiệm không được để trống.',
        //         'score.max'                       => 'Điểm tín nhiệm không được quá 1000.',
        //     ];
        // }
        
        // $validator = Validator::make($request->all(), $rules, $messages);

        // if ($validator->fails()) {
        //     return \Response::json(array('status' => 404, 'message' => $validator->errors()));
        // } else {
            $loan_capital_id = $request->loan_capital_id;
            $loan_capital = LoanCapital::where('id', $loan_capital_id)->where('status', 0)->first();
            
            if($loan_capital) {
                $status = $request->status;
                $reason = $request->reason;
                // $loan_capital->score = $request->score;
                $loan_capital->note = $request->note;
                $loan_capital->status = $status;
                $loan_capital->reason = $reason;
                $loan_capital->save();

                if ($status == 1) {
                    // Gửi mail cho TGĐ
                    $email = Personnel::find(1)->email;
                    $subject = '[HR] Yêu cầu đăng ký vay vốn từ nhân viên';
                    $content_mail = array(
                                        'link' =>  url('toh_hrm/vay-von/quan-ly?selectDepart=0&status=-1&loan_capital_id='. $loan_capital_id),
                                        'fullname'=> Personnel::find($loan_capital->personnel_id)->fullname,
                                    );
                    \Mail::send('emails.notification_request_loan_capital', $content_mail, function($message) use ($email, $subject) {
                        $message->from('nhansu@tohsoft.com', 'TOH');
                        $message->to($email)->subject($subject);
                    });
                } else {
                    // Gửi mail cho NV
                    $email_cc = Auth::user()->email;
                    $email = Personnel::find($loan_capital->personnel_id)->email;
                    $subject = '[HR] Thông báo kết quả yêu cầu đăng ký vay vốn từ công ty';
                    $content_mail = array(
                                        'reason' => $reason,
                                        'status' => $status,
                                    );
                    \Mail::send('emails.notification_result_loan_capital', $content_mail, function($message) use ($email, $email_cc, $subject) {
                        $message->from('nhansu@tohsoft.com', 'TOH');
                        $message->cc($email_cc);
                        $message->to($email)->subject($subject);
                    });
                }

            }

            return \Response::json(array('status' => 200, 'message' => 'Xem xét yêu cầu vay vốn cho nhân viên thành công!'));
        // }
    }
    
    public function approvedLoanCapitalAjax(Request $request)
    { 
        $reason = ($request->reason != '') ? $request->reason : '';
        $loan_capital = LoanCapital::where('id', $request->loan_capital_id)->where('status', 1)->first();
        $personnel_id = $loan_capital->personnel_id;

        if ($request->status == 2) {
            $rules = [
                'score'                               => 'numeric|min:0|max:1000',
                'max_money'                           => 'required|numeric|min:1',
                'disbursement_date'                   => 'required|date_format:d/m/Y',
                'month_time'                          => 'required|numeric|min:1|max:999',
            ];
            $messages = [
                'score.numeric'                       => 'Điểm tín nhiệm phải là số.',
                'score.max'                       => 'Điểm tín nhiệm không được quá 1000.',
                'max_money.required'                  => 'Số tiền vay không được để trống.',
                'max_money.min'                       => 'Số tiền vay phải có giá trị hợp lệ.',
                'month_time.required'                 => 'Thời gian vay không được để trống.',
                'month_time.min'                      => 'Thời gian vay phải có giá trị hợp lệ.',
                'disbursement_date.required'           => 'Ngày giải ngân không được để trống.',
                'disbursement_date.date_format'        => 'Ngày giải ngân không hợp lệ.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return \Response::json(array('status' => 404, 'message' => $validator->errors()));
            } else {
                $loan_capital_id = $loan_capital->id;

                if($loan_capital) {
                    $disbursement_date = BatvHelper::formatDate($request->disbursement_date,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                    $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $disbursement_date)->whereDate('apply_to', '>=', $disbursement_date)->first();

                    if ($config_loan_capital) {
                        $config_start_month_pay = $config_loan_capital->start_month_pay;
                        $config_start_month_pay = $config_start_month_pay + 1;
                        $start_month_pay = date('Y-m-d', strtotime("+". $config_start_month_pay ." months", strtotime($disbursement_date)));

                        $loan_capital->score = $request->score;
                        $loan_capital->config_id = $config_loan_capital->id;
                        $loan_capital->status = $request->status;
                        $loan_capital->month_time = $request->month_time;
                        $loan_capital->max_money = $request->max_money;
                        $loan_capital->disbursement_date = $disbursement_date;
                        $loan_capital->save();

                        // ===============Tính lịch trả nợ với dư nợ giảm dần===============

                        // A/d với lãi suất ưu đãi
                        $summoney = $time= $percent = $goc = $lai = $goc_lai = $sum_goc = $sum_lai = 0;
                        $summoney = $request->max_money;
                        // $time = ($request->month_time >= $config_loan_capital->count_month_preferential) ? $request->month_time : $config_loan_capital->count_month_preferential;
                        $time = $request->month_time;
                        $tempGoc =  $summoney;
                        $goc = (int)($summoney) /  $time; // tính gốc
                        $arr = [];
                        // $arr [] = [
                        //      'repayment_period' => BatvHelper::formatDate($request->disbursement_date,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',false),
                        //      'month' => 0,
                        //      'remaining_principal' => $tempGoc,
                        //      'principal' => 0,
                        //      'interest' => 0,
                        //      'loan_capital_id' => $loan_capital_id,
                        //      'status' => 0
                        // ];

                        // if ($config_start_month_pay > 1) {
                        //     $start_month_pay_free = date('Y-m-d', strtotime("+1 months", strtotime($disbursement_date)));

                        //     for ($i = 1; $i < $config_start_month_pay; $i++) { 
                        //         $arr [] = [
                        //             'repayment_period' => $start_month_pay_free,
                        //             'month' => 0,
                        //             'remaining_principal' => $tempGoc,
                        //             'principal' => 0,
                        //             'interest' => 0,
                        //             'loan_capital_id' => $loan_capital_id,
                        //             'status' => 10
                        //        ];

                        //        $start_month_pay_free = date('Y-m-d', strtotime("+1 months", strtotime($start_month_pay_free)));
                        //     }
                        // }
                        // dd($arr);
                        
                        for ( $i = 0;  $i < $time;  $i++) {
                            if ($i < $config_loan_capital->count_month_preferential) {
                                $percent = $config_loan_capital->preferential_interest_rate;
                            } else {
                                $percent = $config_loan_capital->interest_rate;
                            }
                            
                             $sum_goc =  $sum_goc +  $goc;
             
                             $lai = (int)( $tempGoc) / 12 *  $percent / 100; // tính lãi
             
                             $tempGoc =  $tempGoc -  $goc; // gốc còn lại theo từng tháng
                             $tempGoc = ($tempGoc < 0) ? 0 : $tempGoc;
                             $goc_lai =  $goc +  $lai;
                             $sum_lai =  $sum_lai +  $lai;
                             $arr [] = [
                                         'repayment_period' => $start_month_pay,
                                         'month' => $i + 1,
                                         'remaining_principal' => $tempGoc,
                                         'principal' => $goc,
                                         'interest' => $lai,
                                         'loan_capital_id' => $loan_capital_id,
                                         'status' => 0
                                     ];

                            $start_month_pay = date('Y-m-d', strtotime("+1 months", strtotime($start_month_pay)));
                        }
        
                        HistoryPayLoanCapital::insert($arr);
                    } else {
                        return \Response::json(array('status' => 400, 'message' => 'Chưa có cấu hình cho thời gian giải ngân này!'));
                    }
                    
                }
            }
        } else {
            $loan_capital->reason = $reason;
            $loan_capital->status = $request->status;
            $loan_capital->save();
        }

        // Gửi mail cho nhân viên báo kết quả
        $email_cc = [ Auth::user()->email, ConfigWeb::where('key', 'email_tq')->value('value') ];
        $email = Personnel::find($personnel_id)->email;
        $subject = '[HR] Thông báo kết quả yêu cầu đăng ký vay vốn từ công ty';
        $content_mail = array(
                            'reason' => $reason,
                            'status' => $request->status,
                        );
        \Mail::send('emails.notification_result_loan_capital', $content_mail, function($message) use ($email,$email_cc, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->cc($email_cc);
            $message->to($email)->subject($subject);
        });

        return \Response::json(array('status' => 200, 'message' => 'Phê duyệt thành công!'));
    }

    public function detailLoanCapital($loan_capital_id)
    { 
        $detail_loan_capital = LoanCapital::leftJoin('history_pay_loan_capital', 'loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
                                            ->leftJoin('personnel', 'loan_capital.personnel_id', '=', 'personnel.id')
                                            ->selectRaw('history_pay_loan_capital.*,DATE_FORMAT(history_pay_loan_capital.received_date , "%d/%m/%Y") as received_date,DATE_FORMAT(history_pay_loan_capital.received_date_user , "%d/%m/%Y") as received_date_user,personnel.fullname,loan_capital.config_id,loan_capital.pay,loan_capital.id as loan_capital_id,loan_capital.status as loan_capital_status,loan_capital.final_settlement,DATE_FORMAT(loan_capital.partial_settlement_date_user , "%d/%m/%Y") as partial_settlement_date_user,DATE_FORMAT(loan_capital.final_settlement_date_user , "%d/%m/%Y") as final_settlement_date_user,loan_capital.disbursement_form,loan_capital.info_receive_disbursement,loan_capital.pay,loan_capital.partial_settlement')
                                            ->where('loan_capital.id', $loan_capital_id)
                                            ->whereIn('loan_capital.status', [2,4])
                                            ->orderBy('history_pay_loan_capital.repayment_period', 'ASC')
                                            ->get();

        $arr_route = BatvHelper::listRolesByUser();
        $user_id = Auth::user()->id;

        if( in_array('quan-ly-tra-no-dinh-ky',$arr_route) ){
            $user_id = 1;
        }

        $config_loan_capital = ConfigLoanCapital::find($detail_loan_capital[0]->config_id);
        return view('layouts.vay-von.detail', compact('detail_loan_capital', 'config_loan_capital', 'user_id'));
    }

    public function approvedPayMonthLoanCapitalAjax(Request $request)
    { 
        $rules = [
            'paid_money'                           => 'required|numeric|min:0',
            'received_date'                           => 'required',
        ];
        $messages = [
            'paid_money.required'                       => 'Số tiền thanh toán không được để trống.',
            'received_date.required'                       => 'Ngày nhận tiền không được để trống.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return \Response::json(array('status' => 404, 'message' => $validator->errors()));
        } else {
            $paid_money = $request->paid_money;
            $paid_money = round($request->paid_money);
            $loan_capital = LoanCapital::where('id', $request->loan_capital_id)->where('status', 2)->first();
            $loan_capital_id = $loan_capital->id;
            $history_pay_loan_capital = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', $request->month)->first();
            $history_pay_loan_capital->received_date = $request->received_date;
            $history_pay_loan_capital->paid_money = $paid_money;
            $history_pay_loan_capital->status = 1;
            $history_pay_loan_capital->save();
            
            //Nếu só tiền trả gốc đủ hoặc lớn hơn tổng số tiền cần trả
            if ($paid_money >= round($history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal + $history_pay_loan_capital->interest + $history_pay_loan_capital->interest_incurred + $history_pay_loan_capital->wanting_month_prev_money - $history_pay_loan_capital->redundancy_month_prev_money) ) {
                HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', '<>', 0)->where('status', 0)->delete();
                // Hoàn tất nếu n/v thanh toán xong tất cả số tiền vay vốn + lãi
                $loan_capital->status = 4;
                $loan_capital->save();

                return \Response::json(array('status' => 200, 'message' => 'Thanh toán cho nhân viên thành công!'));
            } else {            
                $config_loan_capital = ConfigLoanCapital::find($loan_capital->config_id);
                // Kiểm tra xem số tiền cần trả có đủ ko, nếu ko đủ sẽ tính vào khoản lãi phát sinh tháng kế tiếp.
                $total =  $history_pay_loan_capital->principal + $history_pay_loan_capital->interest + $history_pay_loan_capital->interest_incurred + $history_pay_loan_capital->wanting_month_prev_money - $history_pay_loan_capital->redundancy_month_prev_money;
                $total = round($total);
                
                if ($total != $paid_money) {
                    $history_pay_loan_capital_next = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', $request->month + 1)->first();
                    //Nếu nv gửi tiến trả trong tháng lớn hơn số tiền trong tháng cần phải trả
                    if ($paid_money > $total) {
                        if ($history_pay_loan_capital_next) {
                            $history_pay_loan_capital_next->interest_incurred = 0;
                            $history_pay_loan_capital_next->wanting_month_prev_money = 0;
                            $history_pay_loan_capital_next->redundancy_month_prev_money = $paid_money - $total;
                        }
                    } else {
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
                    }

                    if ($history_pay_loan_capital_next) {
                        $history_pay_loan_capital_next->save();
                    }
                } else {
                    // Hoàn tất nếu n/v thanh toán xong tất cả số tiền vay vốn + lãi
                    if ($request->month >= $loan_capital->month_time) {
                        $loan_capital->status = 4;
                        $loan_capital->save();
                    }
                }
            }


            return \Response::json(array('status' => 200, 'message' => 'Thanh toán cho nhân viên trong kỳ trả nợ thành công!'));
        }
    }

    public function donePayMonthLoanCapitalAjax(Request $request) {
        $loan_capital = LoanCapital::where('id', $request->loan_capital_id)->where('status', 2)->first();
        $loan_capital->status = 4;
        $loan_capital->save();
        return \Response::json(array('status' => 200, 'message' => 'Hoàn tất thành công!'));
    }

    public function calculateDemoLoanCapitalAjax(Request $request) {
        $disbursement_date = BatvHelper::formatDate($request->disbursement_date,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $disbursement_date)->whereDate('apply_to', '>=', $disbursement_date)->first();

        if ($config_loan_capital) {
            $config_start_month_pay = $config_loan_capital->start_month_pay;
        } else {
            $config_start_month_pay = ConfigLoanCapital::get();
            $config_start_month_pay = $config_start_month_pay[count($config_start_month_pay) - 1]->start_month_pay;
        }
        
        $config_start_month_pay = $config_start_month_pay + 1;
        $start_month_pay = date('Y-m-d', strtotime("+1 months", strtotime($disbursement_date)));
        // A/d với lãi suất ưu đãi
        $summoney = $time= $percent = $goc = $lai = $goc_lai = $sum_goc = $sum_lai = 0;
        $summoney = $request->money_loan;
        $time = $request->month_time;
        $tempGoc =  $summoney;
        $goc = (int)($summoney) /  $time;
        $arr = [];
        // $arr [] = [
        //         'repayment_period' => $request->disbursement_date,
        //         'month' => 0,
        //         'remaining_principal' => $tempGoc,
        //         'principal' => 0,
        //         'interest' => 0,
        // ];

        // if ($config_start_month_pay > 1) {
        //     $start_month_pay_free = date('Y-m-d', strtotime("+1 months", strtotime($disbursement_date)));

        //     for ($i = 1; $i < $config_start_month_pay; $i++) { 
        //         $arr [] = [
        //             'repayment_period' => BatvHelper::formatDate($start_month_pay_free, 'Y-m-d', 'd/m/Y', 'H:i:s', false),
        //             'month' => 0,
        //             'remaining_principal' => $tempGoc,
        //             'principal' => 0,
        //             'interest' => 0,
        //        ];

        //        $start_month_pay_free = date('Y-m-d', strtotime("+1 months", strtotime($start_month_pay_free)));
        //     }
        // }

        for ( $i = 0;  $i < $time;  $i++) {
            if ($i < $request->count_month_preferential) {
                $percent = $request->preferential_interest_rate;
            } else {
                $percent = $request->interest_rate;
            }
            
            $sum_goc =  $sum_goc +  $goc;

            $lai = (int)( $tempGoc) / 12 *  $percent / 100; // tính lãi

            $tempGoc =  $tempGoc -  $goc; // gốc còn lại theo từng tháng
            $tempGoc = ($tempGoc < 0) ? 0 : $tempGoc;
            $goc_lai =  $goc +  $lai;
            $sum_lai =  $sum_lai +  $lai;
            $arr [] = [
                        'repayment_period' => BatvHelper::formatDate($start_month_pay, 'Y-m-d', 'd/m/Y', 'H:i:s', false),
                        'month' => $i + 1,
                        'remaining_principal' => $tempGoc,
                        'principal' => $goc,
                        'interest' => $lai,
                    ];

            $start_month_pay = date('Y-m-d', strtotime("+1 months", strtotime($start_month_pay)));
        }
        // dd($arr);
        return \Response::json(array('status' => 200, 'message' => 'Hoàn tất thành công!', 'data' => $arr));
    }

    public function remindMonthLoanCapitalAjax(Request $request) {
        $arr = HistoryPayLoanCapital::select('personnel.email', 'personnel.fullname', 'history_pay_loan_capital.*')
                                    ->leftJoin('loan_capital', 'loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
                                    ->leftJoin('personnel', 'personnel.id', '=', 'loan_capital.personnel_id')
                                    ->whereMonth('history_pay_loan_capital.repayment_period', '=', date('m'))
                                    ->whereYear('history_pay_loan_capital.repayment_period', '=', date('Y'))
                                    ->where('history_pay_loan_capital.month', '>', 0)
                                    ->where('history_pay_loan_capital.status', 0)
                                    ->where('history_pay_loan_capital.type', 0)
                                    ->where('loan_capital.pay', 2)
                                    ->where('loan_capital.final_settlement', 0)
                                    ->get();

        if (count($arr) > 0) {
            foreach ($arr as $key => $value) {
                // Gửi mail cho nhân viên
                $email_cc = Auth::user()->email;
                $infoConfigMailSetting = EmailConfig::getInfoEmailConfig($type = 10);
                $listEmail = EmailConfig::getListEmailbyidPersonnel( explode(",",$infoConfigMailSetting->mail_to) );
                $email = [];

                if( $listEmail ){
                    foreach ($listEmail as $k => $v) {
                        $email[] = $v->email;
                    }
                }

                $subject = '[HR] Thông báo trả nợ định kỳ hàng tháng';
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
                                                ],
                                );
                \Mail::send('emails.notification_remind_month_loan_capital', $content_mail, function($message) use ($email,$email_cc, $subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->cc($email_cc);
                    $message->to($email)->subject($subject);
                });
                
            }
            
            return \Response::json(array('status' => 200, 'message' => 'Nhắc trả nợ thành công!'));
        } else {
            return \Response::json(array('status' => 404, 'message' => 'Không có nhân viên phải trả nợ trong tháng này!'));
        }
    }

    public function updateScoreFaithConfig(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $value_score_tn = $request->score_tn;
        $score_tn = ConfigWeb::where('key', 'score_tn')->first();
        $score_tn->value = $value_score_tn;
        $score_tn->updated_at = $date;
        $score_tn->save();

        $value_score_tsp_tld = $request->score_tsp_tld;
        $score_tsp_tld = ConfigWeb::where('key', 'score_tsp_tld')->first();
        $score_tsp_tld->value = $value_score_tsp_tld;
        $score_tsp_tld->updated_at = $date;
        $score_tsp_tld->save();

        $value_score_tp_ptt = $request->score_tp_ptt;
        $score_tp_ptt = ConfigWeb::where('key', 'score_tp_ptt')->first();
        $score_tp_ptt->value = $value_score_tp_ptt;
        $score_tp_ptt->updated_at = $date;
        $score_tp_ptt->save();

        $value_score_ttt = $request->score_ttt;
        $score_ttt = ConfigWeb::where('key', 'score_ttt')->first();
        $score_ttt->value = $value_score_ttt;
        $score_ttt->updated_at = $date;
        $score_ttt->save();

        $score_min = ConfigWeb::where('key', 'score_min')->first();
        $score_min->value = $request->score_min;
        $score_min->updated_at = $date;
        $score_min->save();

        $list_personnel = Personnel::leftJoin('personnel_title', 'personnel_title.personnel_id', '=', 'personnel.id')
                                    ->select('personnel.id', 'personnel.date_in', 'personnel_title.job_title_id')
                                    ->where('personnel.status', '=', 1)
                                    ->where('personnel.date_out', '=', NULL)
                                    ->orWhere('personnel.date_out', '>', date('Y-m-d') )
                                    ->get();
        // echo '<pre>';
        // print_r($list_personnel);die;
        $data = [];
        if ($list_personnel) {
            $data = [];

            foreach ($list_personnel as $key => $value) {
                $datetime1 = new \DateTime($value->date_in);
                $datetime2 = new \DateTime(date('Y-m-d'));
                $interval = $datetime2->diff($datetime1);
                $month_work =  (($interval->format('%y') * 12) + $interval->format('%m'));

                $score_role = 0;
                $job_title_id = $value->job_title_id;

                if ($job_title_id == 34) {
                    $score_role = $score_role + $value_score_tsp_tld;
                } elseif ($job_title_id == 26 || $job_title_id == 31) {
                    $score_role = $score_role + $value_score_tp_ptt;
                } elseif ($job_title_id == 25) {
                    $score_role = $score_role + $value_score_ttt;
                }

                $score_position = round(($month_work/12)*$value_score_tn, 2);

                Personnel::where('id', $value->id)->update([
                    'score_seniority' => $score_position,
                    'score_position' => $score_role,
                    'score_faith' => $score_position + $score_role,
                ]);

            }

        }
        return \Response::json(array('status' => 200, 'message' => 'Cập nhật thành công!'));
    }

    public function updateOtherConfig(Request $request)
    {
        $date = date('Y-m-d H:i:s');

        $remind_before_x_days = ConfigWeb::where('key', 'remind_before_x_days')->first();
        $remind_before_x_days->value = $request->remind_before_x_days;
        $remind_before_x_days->updated_at = $date;
        $remind_before_x_days->save();

        $remind_after_x_days = ConfigWeb::where('key', 'remind_after_x_days')->first();
        $remind_after_x_days->value = $request->remind_after_x_days;
        $remind_after_x_days->updated_at = $date;
        $remind_after_x_days->save();

        $email_tq = ConfigWeb::where('key', 'email_tq')->first();
        $email_tq->value = $request->email_tq;
        $email_tq->updated_at = $date;
        $email_tq->save();

        return \Response::json(array('status' => 200, 'message' => 'Cập nhật thành công!'));
    }

    public function evaluateFaith(Request $request)
    {
        $data = BatvHelper::listPesonnelByManager();
        $myfunc =  new Myfunction();
        $user_id = Auth::user()->id;
        $check = Evaluation::checkDepartmentbyManager($user_id);
        foreach ($check as $key => $value) {
            $tmp[$value->id] =  $myfunc->categoryChild($value->id,'departments');
        }

        $department_id =  BatvHelper::array_keys_multi($tmp);
        $arr_personnel_by_manager = Personnel::where('status', '=', 1)->whereIn('department_id', $department_id)->where('date_out', '=', NULL)->pluck('id')->toArray();
        $personnel_id = $request->personnel_id;

        if (in_array($personnel_id, $arr_personnel_by_manager)) {
            $propose_score_faith = ProposeScoreFaith::where('personnel_id', $personnel_id)->first();

            if ($propose_score_faith) {
                $propose_score_faith->score = $request->score;
                $propose_score_faith->note = $request->note;
                $propose_score_faith->save();
            } else {
                ProposeScoreFaith::insert(['personnel_id' => $personnel_id, 'score' => $request->score, 'note' => $request->note]);
                // Gửi mail báo cho TGĐ
                $email = Personnel::find(1)->email;
                $subject = '[HR] Đề xuất điểm tín nhiệm cho nhân viên '. Personnel::find($personnel_id)->fullname. ' từ '.Departments::where('manager_id', $user_id)->value('title');
                $content_mail = array(
                                    'link' => route('danhgia.diem-tin-nhiem').'?personnel_id='.$personnel_id,    
                                );
                                
                \Mail::send('emails.notification_propose_score_faith', $content_mail, function($message) use ($email, $subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->to($email)->subject($subject);
                });
    
            }

            return \Response::json(array('status' => 200, 'message' => 'Đã gửi thành công!'));

        }

        return \Response::json(array('status' => 404, 'message' => 'Error!'));

    }

    public function evaluateFaithByCEO(Request $request)
    {
        $item = Personnel::find($request->personnel_id);

        if ($item) {
            $item->score_faith = $request->score;
            $item->save();
            return \Response::json(array('status' => 200, 'message' => 'Đã cập nhật thành công!'));
        }

        return \Response::json(array('status' => 404, 'message' => 'Error!'));

    }

    public function approvedEvaluateFaith(Request $request)
    {
        if ($request->status == 1) {
            Personnel::where('id', $request->personnel_id)->update(['score_faith' => $request->score]);
        } 
            
        ProposeScoreFaith::where('personnel_id', $request->personnel_id)->delete();
        return \Response::json(array('status' => 200, 'message' => 'Đã duyệt thành công!'));
    }

    public function remindPayPartialSettlementByUser(Request $request)
    {
        $infoConfigMailSetting = EmailConfig::getInfoEmailConfig($type = 10);
        $listEmail = EmailConfig::getListEmailbyidPersonnel( explode(",",$infoConfigMailSetting->mail_to) );
        $email = [];

        if( $listEmail ){
            foreach ($listEmail as $key => $value) {
                $email[] = $value->email;
            }
        }

        $loan_capital = LoanCapital::where('personnel_id', Auth::user()->id)->where('status', 2)->first();
        $loan_capital->partial_settlement = 1;
        $loan_capital->partial_settlement_date_user = date('Y-m-d');
        $loan_capital->save();

        $subject = '[HR] Nhân viên '. Auth::user()->name. ' tất toán một phần khoản vay';
        $content_mail = array(
                            'link' => route('detailLoanCapital',['loan_capital_id' => $loan_capital->id]),    
                        );
                        
        \Mail::send('emails.notification_remind_pay_month_now_by_user', $content_mail, function($message) use ($email, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->to($email)->subject($subject);
        });

        return \Response::json(array('status' => 200, 'message' => 'Đã gửi thông báo thành công!'));
    }

    public function remindPayMonthNowByUser(Request $request)
    {
        $infoConfigMailSetting = EmailConfig::getInfoEmailConfig($type = 9);
        $listEmail = EmailConfig::getListEmailbyidPersonnel( explode(",",$infoConfigMailSetting->mail_to) );
        $email = [];
        if( $listEmail ){
            foreach ($listEmail as $k => $v) {
                $email[] = $v->email;
            }
        }

        // dd($email);
        // Gửi mail báo cho kế toán
        // $email = Role_User::select('personnel.email')
        //                   ->leftJoin('personnel', 'personnel.id', '=', 'role_user.user_id')
        //                   ->where('role_user.role_id', 9)
        //                   ->pluck('email')
        //                   ->toArray();
        
        $history_pay_loan_capital = HistoryPayLoanCapital::find($request->id_history_pay_loan_capital);
        $history_pay_loan_capital->type = 1;
        $history_pay_loan_capital->received_date_user = date('Y-m-d');
        $history_pay_loan_capital->save();

        $subject = '[HR] Nhân viên '. Auth::user()->name. ' trả nợ kỳ '.$history_pay_loan_capital->month. ' - '.  BatvHelper::formatDate($history_pay_loan_capital->repayment_period, 'Y-m-d', 'd/m/Y', 'H:i:s', false);
        $content_mail = array(
                            'link' => route('detailLoanCapital',['loan_capital_id' => $history_pay_loan_capital->loan_capital_id]),    
                        );
        \Mail::send('emails.notification_remind_pay_month_now_by_user', $content_mail, function($message) use ($email, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->to($email)->subject($subject);
        });
        return \Response::json(array('status' => 200, 'message' => 'Thao tác thành công!'));
    }

    public function remindPayAllNowByUser(Request $request)
    {
        // Gửi mail báo cho kế toán
        // $email = Role_User::select('personnel.email')
        //                   ->leftJoin('personnel', 'personnel.id', '=', 'role_user.user_id')
        //                   ->where('role_user.role_id', 9)
        //                   ->pluck('email')
        //                   ->toArray();

        $infoConfigMailSetting = EmailConfig::getInfoEmailConfig($type = 10);
        $listEmail = EmailConfig::getListEmailbyidPersonnel( explode(",",$infoConfigMailSetting->mail_to) );
        $email = [];

        if( $listEmail ){
            foreach ($listEmail as $key => $value) {
                $email[] = $value->email;
            }
        }

        $loan_capital = LoanCapital::where('personnel_id', Auth::user()->id)->where('status', 2)->first();
        $loan_capital->final_settlement = 1;
        $loan_capital->final_settlement_date_user = date('Y-m-d');
        $loan_capital->save();

        $subject = '[HR] Nhân viên '. Auth::user()->name. ' tất toán khoản vay';
        $content_mail = array(
                            'link' => route('detailLoanCapital',['loan_capital_id' => $loan_capital->id]),    
                        );
                        
        \Mail::send('emails.notification_remind_pay_month_now_by_user', $content_mail, function($message) use ($email, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->to($email)->subject($subject);
        });

        return \Response::json(array('status' => 200, 'message' => 'Đã gửi thông báo thành công!'));
    }

    public function approvedPartialSettlement(Request $request)
    { 
        $rules = [
            'paid_money'                           => 'required|numeric|min:0',
            'received_date'                           => 'required',
        ];
        $messages = [
            'paid_money.required'                       => 'Số tiền thanh toán không được để trống.',
            'received_date.required'                       => 'Ngày nhận tiền không được để trống.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return \Response::json(array('status' => 404, 'message' => $validator->errors()));
        } else {
            $paid_money = round($request->paid_money);
            $loan_capital_id = $request->loan_capital_id;

            $loan_capital = LoanCapital::where('id', $loan_capital_id)->where('status', 2)->first();
            $loan_capital->partial_settlement = 2;
            $loan_capital->save();

            $config_loan_capital = ConfigLoanCapital::find($loan_capital->config_id);
            $summoney = $time= $percent = $goc = $lai = $goc_lai = $sum_goc = $sum_lai = 0;
            $history_pay_loan_capital = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                            ->where('status', 0)
                                                            ->where('month', '>', 0)
                                                            ->orderBy('month', 'asc')
                                                            ->first();



            $received_date = $request->received_date;
            $repayment_period = $history_pay_loan_capital->repayment_period;

            $flag = true; 
            // $flag = false; VD trả định kỳ ngày 30/09/2019 thì ông ấy trả 30/6/2019 chẳng hàn thì ko phải chịu lãi suất nào
            $days = 30; // Ngày tính lại lãi suất cũ
            $days_first = 30; // Ngày tính lại lãi suất mới
            $days_standard = 30;

            $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                                    ->where('status', 1)
                                                                    ->where('month', '>', 0)
                                                                    ->orderBy('month', 'desc')
                                                                    ->first();

            if (!$history_pay_loan_capital_before) {
                $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                        ->where('month', 0)
                                        ->first();
            }

            $flag_percent = false;

            if ($loan_capital->status_file == 2) {
                $flag_percent = true;
                $percent_interest = $config_loan_capital->interest_file_late;
            } else {
                if ($history_pay_loan_capital->month < $config_loan_capital->count_month_preferential) {
                    $percent_interest = $config_loan_capital->preferential_interest_rate;
                } else {
                    $percent_interest = $config_loan_capital->interest_rate;
                }
            }

            if( (BatvHelper::handlingTime($received_date) <= BatvHelper::handlingTime($repayment_period)) ){
                $date_partial_settlement = $history_pay_loan_capital_before->date_partial_settlement;
                if ($date_partial_settlement != '') { // TH trả sớm một phần nhiều lần
                    $received_date = $date_partial_settlement;
                    $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                    $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                    // echo $day_repayment_period ."-". $day_received_date;die;
                    if ($year_repayment_period == $year_received_date) {
                        if ($month_repayment_period - $month_received_date == 0) {
                            if ($day_repayment_period == $day_received_date) {
                                $days_first = 0;
                                $days = 30;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_standard = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_standard++;
                                }
        
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_standard = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_standard++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_standard++;
                                }
        
                            }
        
                        } elseif($month_repayment_period - $month_received_date == 1) {
                        
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_standard = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_standard++;
                                }
        
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_standard = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_standard++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_standard++;
                                }
    
                            }
                        } elseif($month_repayment_period - $month_received_date > 1)  {
                            $flag = false;
                        }
                    } elseif($year_repayment_period - $year_received_date == 1) {
                        if ($month_repayment_period - $month_received_date == -11) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days_standard = $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days_standard = $days_first;
                            }
                        } else {
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }

                    $received_date = $date_partial_settlement;
                    $repayment_period = $request->received_date;
                    // echo $received_date .'---'.$repayment_period;die;
                    $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                    $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                    // echo $day_repayment_period ."-". $day_received_date;die;
                    if ($year_repayment_period == $year_received_date) {
                        if ($month_repayment_period - $month_received_date == 0) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
    
                            }
        
                        } elseif($month_repayment_period - $month_received_date == 1) {
                        
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                            }
                        } elseif($month_repayment_period - $month_received_date > 1)  {
                            $flag = false;
                        }
                    } elseif($year_repayment_period - $year_received_date == 1) {
                        if ($month_repayment_period - $month_received_date == -11) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                            } elseif ($day_repayment_period <= $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                            }
                        } else {
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }

                    $interest = ($history_pay_loan_capital->interest * $days_first)/$days_standard;
                    $interest_incurred = ($history_pay_loan_capital->interest_incurred * $days_first)/$days_standard;
                    $days_first = $days_standard - $days_first;
                } else {
                    $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                    $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
    
                    if ($year_repayment_period == $year_received_date) {
                        if ($month_repayment_period - $month_received_date == 0) {
                            if ($day_repayment_period == $day_received_date) {
                                $days_first = 0;
                                $days = 30;
                            } elseif ($day_repayment_period > $day_received_date) {

                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            }
        
                        } elseif($month_repayment_period - $month_received_date == 1) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
    
                                $days = 30 - $days_first;
                            }
                        } elseif($month_repayment_period - $month_received_date > 1)  {
                            $flag = false;
                        }
                    } elseif($year_repayment_period - $year_received_date == 1) {
                        if ($month_repayment_period - $month_received_date == -11) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            }
                        } else {
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }
                }

                if (!isset($interest)) {
                    if ($flag) {
                        if ($days == 30) {
                            $interest = $history_pay_loan_capital->interest;
                            $interest_incurred = $history_pay_loan_capital->interest_incurred;
                        } else {
                            $interest = ($history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal) * ( ( (($percent_interest*$days)/(12*30)) )/100 );
                            $interest_incurred = ($history_pay_loan_capital->wanting_month_prev_money) * ( ( (($config_loan_capital->deferred_interest*$days)/(12*30)) )/100 );
                        }
        
                    } else {
                        $interest = $interest_incurred = 0;
                    }
                }


                $summoney = $history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal+ $interest + $interest_incurred - $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                $summoney = round($summoney);
                $month = (int)$history_pay_loan_capital->month;

                $time = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->count();
                $time = (int)$time - 1;

                if ($paid_money >= $summoney) {
                    $loan_capital->status = 4;
                    $loan_capital->save();
                    $history_pay_loan_capital->received_date = $request->received_date;
                    $history_pay_loan_capital->interest = $interest;
                    $history_pay_loan_capital->interest_incurred = $interest_incurred;
                    $history_pay_loan_capital->paid_money = $paid_money;
                    $history_pay_loan_capital->final_settlement = 1;
                    $history_pay_loan_capital->status = 1;
                    $history_pay_loan_capital->save();
                    HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                            ->where('month','>', $month)
                                            ->delete();
                } else {
                    $summoney =  $interest + $interest_incurred - $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                    $summoney = round($summoney);
                    $start_month_pay_next = $history_pay_loan_capital->repayment_period;

                    if ($history_pay_loan_capital_before) {
                        $history_pay_loan_capital_before->status = 1;
                        $history_pay_loan_capital_before->received_date = $request->received_date;
                        $history_pay_loan_capital_before->date_partial_settlement = $request->received_date;
                        $history_pay_loan_capital_before->interest = $history_pay_loan_capital_before->interest + $interest;
                        $history_pay_loan_capital_before->interest_incurred = $history_pay_loan_capital_before->interest_incurred + $interest_incurred;
                        $history_pay_loan_capital_before->paid_money = $history_pay_loan_capital_before->paid_money + $paid_money;
                        $history_pay_loan_capital_before->save();

                        if ($paid_money >= $summoney) {
                            $tempGoc  = $history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal - ($paid_money - $summoney) ;
                            $tempGoc = ($tempGoc < 0) ? 0 : $tempGoc;
                            $goc = (int)($tempGoc) /  ($time - $month + 1); // tính gốc
                            $days = 30;

                            for ($i = $month;  $i <= $time;  $i++) {
                                $goc = ($goc < 0) ? 0 : $goc;
                                $sum_goc =  $sum_goc +  $goc;
                                // $lai = (int)( $tempGoc) / 12 *  $percent_interest / 100; // tính lãi

                                if ($flag_percent == false) {
                                    if ($i < $config_loan_capital->count_month_preferential) {
                                        $percent_interest = $config_loan_capital->preferential_interest_rate;
                                    } else {
                                        $percent_interest = $config_loan_capital->interest_rate;
                                    }
                                }
                                if ($i == $month) {
                                    $lai = $tempGoc * ( ( (($percent_interest*$days_first)/(12*30)) )/100 );
                                } else {
                                    $lai = $tempGoc * ( ( (($percent_interest*$days)/(12*30)) )/100 );
                                }
                                
                                $tempGoc =  $tempGoc -  $goc; // gốc còn lại theo từng tháng
                                $tempGoc = ($tempGoc < 0) ? 0 : $tempGoc;


                                $arr [] = [
                                            'repayment_period' => $start_month_pay_next,
                                            'month' => $i,
                                            'remaining_principal' => round($tempGoc),
                                            'principal' => round($goc),
                                            'interest' => round($lai),
                                            'loan_capital_id' => $loan_capital_id,
                                        ];
                                
                                $start_month_pay_next = date('Y-m-d', strtotime("+1 months", strtotime($start_month_pay_next)));
                            }
                            // dd($arr);
                            HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                    ->where('month','>=', $month)
                                                    ->delete();
                            HistoryPayLoanCapital::insert($arr);
                        } else {
                            $wanting_month_prev_money = $summoney - $paid_money;
                            $interest = ($history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal) * ( ( (($percent_interest*$days_first)/(12*30)) )/100 );
                            $interest_incurred = $wanting_month_prev_money * ( ( (($config_loan_capital->deferred_interest*$days_first)/(12*30)) )/100 );
                            
                            $history_pay_loan_capital->interest = $interest;
                            $history_pay_loan_capital->redundancy_month_prev_money = 0;
                            $history_pay_loan_capital->wanting_month_prev_money = $wanting_month_prev_money;
                            $history_pay_loan_capital->interest_incurred = $interest_incurred;
                            $history_pay_loan_capital->save();
                        }

                    }

                }
            } else {
                if ($loan_capital->pay == 1) {
                    $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                                            ->where('status', 1)
                                                                            ->where('month', '>', 0)
                                                                            ->orderBy('month', 'desc')
                                                                            ->first();

                    if (!$history_pay_loan_capital_before) {
                        $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                ->where('month', 0)
                                                ->first();
                    }
                    $month_final = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                            ->where('month', '>', 0)
                                                            ->count();
                    $date_partial_settlement = $history_pay_loan_capital_before->date_partial_settlement;


                    if ($date_partial_settlement == date('Y-m-d')) {
                        $flag = false;
                    } else {

                    }

                    if ( ($loan_capital->pay == 1 && $month_final == $history_pay_loan_capital->month) || $date_partial_settlement == date('Y-m-d')) {
                        $interest = $history_pay_loan_capital->interest;
                        $interest_incurred = $history_pay_loan_capital->interest_incurred;
                    } else {
                        if ($date_partial_settlement != '') { // TH trả sớm một phần nhiều lần
                            $received_date = $date_partial_settlement;
                            $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                            $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                            $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                            $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                            $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                            $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                            // echo $day_repayment_period ."-". $day_received_date;die;
                            if ($year_repayment_period == $year_received_date) {
                                if ($month_repayment_period - $month_received_date == 0) {
                                    if ($day_repayment_period == $day_received_date) {
                                        $days_first = 0;
                                        $days = 30;
                                    } elseif ($day_repayment_period > $day_received_date) {
                                        $days_standard = 0;
                
                                        for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                            $days_standard++;
                                        }
                
                                    } elseif ($day_repayment_period < $day_received_date) {
                                        $days_standard = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_standard++;
                                        }
                
                                        for ($i = 1; $i < $day_repayment_period; $i++) { 
                                            $days_standard++;
                                        }
                
                                    }
                
                                } elseif($month_repayment_period - $month_received_date == 1) {
                                
                                    if ($day_repayment_period == $day_received_date) {
                                        $flag = false;
                                    } elseif ($day_repayment_period > $day_received_date) {
                                        $days_standard = 0;
                
                                        for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                            $days_standard++;
                                        }
                
                                    } elseif ($day_repayment_period < $day_received_date) {
                                        $days_standard = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_standard++;
                                        }
                
                                        for ($i = 1; $i < $day_repayment_period; $i++) { 
                                            $days_standard++;
                                        }
            
                                    }
                                } elseif($month_repayment_period - $month_received_date > 1)  {
                                    $flag = false;
                                }
                            } elseif($year_repayment_period - $year_received_date == 1) {
                                if ($month_repayment_period - $month_received_date == -11) {
                                    if ($day_repayment_period == $day_received_date) {
                                        $flag = false;
                                    } elseif ($day_repayment_period > $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                        $days_standard = $days_first;
                                    } elseif ($day_repayment_period < $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_first++;
                                        }
                
                                        for ($i = 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                        $days_standard = $days_first;
                                    }
                                } else {
                                    $flag = false;
                                }
                            } else {
                                $flag = false;
                            }

                            $received_date = $date_partial_settlement;
                            $repayment_period = $request->received_date;
                            // echo $received_date .'---'.$repayment_period;die;
                            $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                            $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                            $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                            $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                            $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                            $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                            // echo $day_repayment_period ."-". $day_received_date;die;
                            if ($year_repayment_period == $year_received_date) {
                                if ($month_repayment_period - $month_received_date == 0) {
                                    if ($day_repayment_period == $day_received_date) {
                                        $flag = false;
                                    } elseif ($day_repayment_period > $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }

                                    } elseif ($day_repayment_period < $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_first++;
                                        }
                
                                        for ($i = 1; $i <= $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
            
                                    }
                
                                } elseif($month_repayment_period - $month_received_date == 1) {
                                
                                    if ($day_repayment_period == $day_received_date) {
                                        $flag = false;
                                    } elseif ($day_repayment_period > $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                    } elseif ($day_repayment_period < $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_first++;
                                        }
                
                                        for ($i = 1; $i <= $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }

                                    }
                                } elseif($month_repayment_period - $month_received_date > 1)  {
                                    $flag = false;
                                }
                            } elseif($year_repayment_period - $year_received_date == 1) {
                                if ($month_repayment_period - $month_received_date == -11) {
                                    if ($day_repayment_period == $day_received_date) {
                                        $flag = false;
                                    } elseif ($day_repayment_period > $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }

                                    } elseif ($day_repayment_period <= $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_first++;
                                        }
                
                                        for ($i = 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                    }
                                } else {
                                    $flag = false;
                                }
                            } else {
                                $flag = false;
                            }

                            $interest = ($history_pay_loan_capital->interest * $days_first)/$days_standard;
                            $interest_incurred = ($history_pay_loan_capital->interest_incurred * $days_first)/$days_standard;
                            $days_first = $days_standard - $days_first;
                        } else {
                            $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                            $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                            $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                            $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                            $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                            $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
            
                            if ($year_repayment_period == $year_received_date) {
                                if ($month_repayment_period - $month_received_date == 0) {
                                    if ($day_repayment_period == $day_received_date) {
                                        $days_first = 0;
                                        $days = 30;
                                    } elseif ($day_repayment_period > $day_received_date) {

                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                        $days = 30 - $days_first;
                                    } elseif ($day_repayment_period < $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_first++;
                                        }
                
                                        for ($i = 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                        $days = 30 - $days_first;
                                    }
                
                                } elseif($month_repayment_period - $month_received_date == 1) {
                                    if ($day_repayment_period == $day_received_date) {
                                        $flag = false;
                                    } elseif ($day_repayment_period > $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                        $days = 30 - $days_first;
                                    } elseif ($day_repayment_period < $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_first++;
                                        }
                
                                        for ($i = 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
            
                                        $days = 30 - $days_first;
                                    }
                                } elseif($month_repayment_period - $month_received_date > 1)  {
                                    $flag = false;
                                }
                            } elseif($year_repayment_period - $year_received_date == 1) {
                                if ($month_repayment_period - $month_received_date == -11) {
                                    if ($day_repayment_period == $day_received_date) {
                                        $flag = false;
                                    } elseif ($day_repayment_period > $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                        $days = 30 - $days_first;
                                    } elseif ($day_repayment_period < $day_received_date) {
                                        $days_first = 0;
                
                                        for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                            $days_first++;
                                        }
                
                                        for ($i = 1; $i < $day_repayment_period; $i++) { 
                                            $days_first++;
                                        }
                
                                        $days = 30 - $days_first;
                                    }
                                } else {
                                    $flag = false;
                                }
                            } else {
                                $flag = false;
                            }
                        }

                        if (!isset($interest)) {
                            if ($flag) {
                                if ($days == 30) {
                                    $interest = $history_pay_loan_capital->interest;
                                    $interest_incurred = $history_pay_loan_capital->interest_incurred;
                                } else {
                                    $interest = $history_pay_loan_capital->interest + ($history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal) * ( ( (($percent_interest*$days)/(12*30)) )/100 );
                                    $interest_incurred = $history_pay_loan_capital->interest_incurred +  ($history_pay_loan_capital->wanting_month_prev_money) * ( ( (($config_loan_capital->deferred_interest*$days)/(12*30)) )/100 );
                                }
                
                            } else {
                                $interest = $interest_incurred = 0;
                            }
                        }

                    }

                    $summoney = $history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal+ $interest + $interest_incurred - $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                    $summoney = round($summoney);
                    $month = (int)$history_pay_loan_capital->month;

                    $time = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->count();
                    $time = (int)$time - 1;
                    // echo $paid_money .'--'.$summoney;die;
                    if ($paid_money >= $summoney) {
                        $loan_capital->status = 4;
                        $loan_capital->save();
                        $history_pay_loan_capital->received_date = $request->received_date;
                        $history_pay_loan_capital->interest = $interest;
                        $history_pay_loan_capital->interest_incurred = $interest_incurred;
                        $history_pay_loan_capital->paid_money = $paid_money;
                        $history_pay_loan_capital->final_settlement = 1;
                        $history_pay_loan_capital->status = 1;
                        $history_pay_loan_capital->save();
                        HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                ->where('month','>', $month)
                                                ->delete();
                    } else {
                        $summoney =  $interest + $interest_incurred - $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                        $summoney = round($summoney);
                        $start_month_pay_next = $history_pay_loan_capital->repayment_period;

                        if ($history_pay_loan_capital_before) {
                            $history_pay_loan_capital_before->status = 1;
                            $history_pay_loan_capital_before->received_date = $request->received_date;
                            $history_pay_loan_capital_before->date_partial_settlement = $request->received_date;
                            $history_pay_loan_capital_before->interest = $history_pay_loan_capital_before->interest + $interest;
                            $history_pay_loan_capital_before->interest_incurred = $history_pay_loan_capital_before->interest_incurred + $interest_incurred;
                            $history_pay_loan_capital_before->paid_money = $history_pay_loan_capital_before->paid_money + $paid_money;
                            $history_pay_loan_capital_before->save();

                            if ($paid_money >= $summoney) {
                                $tempGoc  = $history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal - ($paid_money - $summoney) ;
                                $tempGoc = ($tempGoc < 0) ? 0 : $tempGoc;
                                $goc = (int)($tempGoc) /  ($time - $month + 1); // tính gốc
                                $days = 30;

                                for ($i = $month;  $i <= $time;  $i++) {
                                    $goc = ($goc < 0) ? 0 : $goc;
                                    $sum_goc =  $sum_goc +  $goc;
                                    // $lai = (int)( $tempGoc) / 12 *  $percent_interest / 100; // tính lãi

                                    if ($flag_percent == false) {
                                        if ($i < $config_loan_capital->count_month_preferential) {
                                            $percent_interest = $config_loan_capital->preferential_interest_rate;
                                        } else {
                                            $percent_interest = $config_loan_capital->interest_rate;
                                        }
                                    }
                                    if ($i == $month) {
                                        $lai = $tempGoc * ( ( (($percent_interest*$days_first)/(12*30)) )/100 );
                                    } else {
                                        $lai = $tempGoc * ( ( (($percent_interest*$days)/(12*30)) )/100 );
                                    }
                                    
                                    $tempGoc =  $tempGoc -  $goc; // gốc còn lại theo từng tháng
                                    $tempGoc = ($tempGoc < 0) ? 0 : $tempGoc;


                                    $arr [] = [
                                                'repayment_period' => $start_month_pay_next,
                                                'month' => $i,
                                                'remaining_principal' => round($tempGoc),
                                                'principal' => round($goc),
                                                'interest' => round($lai),
                                                'loan_capital_id' => $loan_capital_id,
                                            ];
                                    
                                    $start_month_pay_next = date('Y-m-d', strtotime("+1 months", strtotime($start_month_pay_next)));
                                }
                                // dd($arr);
                                HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                        ->where('month','>=', $month)
                                                        ->delete();
                                HistoryPayLoanCapital::insert($arr);
                            } else {
                                $wanting_month_prev_money = $summoney - $paid_money;
                                $interest = ($history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal) * ( ( (($percent_interest*$days_first)/(12*30)) )/100 );
                                $interest_incurred = $wanting_month_prev_money * ( ( (($config_loan_capital->deferred_interest*$days_first)/(12*30)) )/100 );
                                
                                $history_pay_loan_capital->interest = $interest;
                                $history_pay_loan_capital->redundancy_month_prev_money = 0;
                                $history_pay_loan_capital->wanting_month_prev_money = $wanting_month_prev_money;
                                $history_pay_loan_capital->interest_incurred = $interest_incurred;
                                $history_pay_loan_capital->save();
                            }

                        }

                    }

                } else {
                    # code...
                }
                
            }

            return \Response::json(array('status' => 200, 'message' => 'Trả sớm một phần cho nhân viên thành công!'));
        }

    }

    public function approvedFinalSettlement(Request $request)
    { 
        $rules = [
            'paid_money'                           => 'required|numeric|min:0',
            'received_date'                           => 'required',
        ];
        $messages = [
            'paid_money.required'                       => 'Số tiền thanh toán không được để trống.',
            'received_date.required'                       => 'Ngày nhận tiền không được để trống.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return \Response::json(array('status' => 404, 'message' => $validator->errors()));
        } else {
            $paid_money = $request->paid_money;
            $paid_money = round($paid_money);

            $loan_capital_id = $request->loan_capital_id;
            $loan_capital = LoanCapital::where('id', $loan_capital_id)->first();
            $config_loan_capital = ConfigLoanCapital::find($loan_capital->config_id);
            $history_pay_loan_capital = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                            ->where('month', '>', 0)
                                                            ->where('status', 0)
                                                            ->orderBy('id', 'asc')
                                                            ->first();
            $received_date = $request->received_date;
            $repayment_period = $history_pay_loan_capital->repayment_period;
            $month_final = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                            ->where('month', '>', 0)
                                                            ->count();
            $flag = true;
            $days = 30; // Ngày tính lại lãi suất cũ
            $days_first = 30; // Ngày tính lại lãi suất mới
            $add_days = 0;
            $interest = $interest_incurred = 0;

            if( (BatvHelper::handlingTime($received_date) <= BatvHelper::handlingTime($history_pay_loan_capital->repayment_period)) ){
                $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                                        ->where('status', 1)
                                                                        ->where('month', '>', 0)
                                                                        ->orderBy('month', 'desc')
                                                                        ->first();

                if (!$history_pay_loan_capital_before) {
                    $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                            ->where('month', 0)
                                            ->first();
                }

                $date_partial_settlement = $history_pay_loan_capital_before->date_partial_settlement;

                if ($loan_capital->status_file == 2) {
                    $percent_interest = $config_loan_capital->interest_file_late;
                } else {
                    if ($history_pay_loan_capital->month < $config_loan_capital->count_month_preferential) {
                        $percent_interest = $config_loan_capital->preferential_interest_rate;
                    } else {
                        $percent_interest = $config_loan_capital->interest_rate;
                    }
                }
                    
                // Trả cùng vào 1 ngày
                if ($date_partial_settlement == date('Y-m-d')) {
                    $flag = false;
                } else { 
                    if ($date_partial_settlement != '') {// TH trả sớm một phần sau đó tất toán toàn bộ
                        $received_date = $date_partial_settlement;
                        $repayment_period = date('Y-m-d');
                        $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                        $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                        $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                        $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                        $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                        $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);

                        if ($year_repayment_period == $year_received_date) {
                            if ($month_repayment_period - $month_received_date == 0) {
                                if ($day_repayment_period == $day_received_date) {
                                    $flag = false;
                                } elseif ($day_repayment_period > $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }

                                } elseif ($day_repayment_period < $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                        $days_first++;
                                    }
            
                                    for ($i = 1; $i <= $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }
        
                                }
            
                            } elseif($month_repayment_period - $month_received_date == 1) {
                            
                                if ($day_repayment_period == $day_received_date) {
                                    $flag = false;
                                } elseif ($day_repayment_period > $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }
            
                                } elseif ($day_repayment_period < $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                        $days_first++;
                                    }
            
                                    for ($i = 1; $i <= $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }

                                }
                            } elseif($month_repayment_period - $month_received_date > 1)  {
                                $flag = false;
                            }
                        } elseif($year_repayment_period - $year_received_date == 1) {
                            if ($month_repayment_period - $month_received_date == -11) {
                                if ($day_repayment_period == $day_received_date) {
                                    $flag = false;
                                } elseif ($day_repayment_period > $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }

                                } elseif ($day_repayment_period <= $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                        $days_first++;
                                    }
            
                                    for ($i = 1; $i < $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }
            
                                }
                            } else {
                                $flag = false;
                            }
                        } else {
                            $flag = false;
                        }

                        $days = $days_first;
                    } else {
                        $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                        $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                        $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                        $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                        $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                        $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);

                        if ($year_repayment_period == $year_received_date) {
                            if ($month_repayment_period - $month_received_date == 0) {
                                if ($day_repayment_period == $day_received_date) {
                                    $days_first = 0;
                                    $days = 30;
                                } elseif ($day_repayment_period > $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }
            
                                    $days = 30 - $days_first;
                                } elseif ($day_repayment_period < $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                        $days_first++;
                                    }
            
                                    for ($i = 1; $i < $day_repayment_period ; $i++) { 
                                        $days_first++;
                                    }
            
                                    $days = 30 - $days_first;
                                }
            
                            } elseif($month_repayment_period - $month_received_date == 1) {
                                if ($day_repayment_period == $day_received_date) {
                                    $flag = false;
                                } elseif ($day_repayment_period > $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }
            
                                    $days = 30 - $days_first;
                                } elseif ($day_repayment_period < $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                        $days_first++;
                                    }
            
                                    for ($i = 1; $i < $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }
            
                                    $days = 30 - $days_first;
                                }
                            } elseif($month_repayment_period - $month_received_date > 1)  {
                                $flag = false;
                            }
                        } elseif($year_repayment_period - $year_received_date == 1) {
                            if ($month_repayment_period - $month_received_date == -11) {
                                if ($day_repayment_period == $day_received_date) {
                                    $flag = false;
                                } elseif ($day_repayment_period > $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }
            
                                    $days = 30 - $days_first;
                                } elseif ($day_repayment_period < $day_received_date) {
                                    $days_first = 0;
            
                                    for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                        $days_first++;
                                    }
            
                                    for ($i = 1; $i < $day_repayment_period; $i++) { 
                                        $days_first++;
                                    }
            
                                    $days = 30 - $days_first;
                                }
                            } else {
                                $flag = false;
                            }
                        } else {
                            $flag = false;
                        }

                    }

                }
    
                if ($flag) {
                    if ($days == 30) {
                        $interest = $history_pay_loan_capital->interest;
                        $interest_incurred = $history_pay_loan_capital->interest_incurred;
                    } else {
                        $interest = ($history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal) * ( ( (($percent_interest*$days)/(12*30)) )/100 );
                        $interest_incurred = ($history_pay_loan_capital->wanting_month_prev_money) * ( ( (($config_loan_capital->deferred_interest*$days)/(12*30)) )/100 );
                    }
    
                    $total_price_real = $history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal+ $interest + $interest_incurred - $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                } else {
                    $total_price_real = $history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal + $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                }
            } else {
                $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
    
                for ($i = $day_repayment_period; $i  <= $day_received_date ; $i++) { 
                    $add_days++;
                }
    
                $history_pay_loan_capital_after = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                                ->where('month', $history_pay_loan_capital->month + 1)
                                                                ->where('status', 0)
                                                                ->first();
                
                if (!$history_pay_loan_capital_after) {
                    $wanting_month_prev_money = $history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal + $history_pay_loan_capital->interest + $history_pay_loan_capital->interest_incurred + $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;

                    if ($month_final == $history_pay_loan_capital->month && $loan_capital->pay == 1) {
                        $interest_incurred = 0;
                    } else {
                        $interest_incurred = $wanting_month_prev_money * ( ( (($config_loan_capital->deferred_interest*$add_days)/(12*30)) )/100 );
                    }

                    $total_price_real = round($wanting_month_prev_money + $interest_incurred);
                } else {
                    $wanting_month_prev_money = $history_pay_loan_capital->principal + $history_pay_loan_capital->interest + $history_pay_loan_capital->interest_incurred + $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
             
                    if ($loan_capital->status_file == 2) {
                        $percent_interest = $config_loan_capital->interest_file_late;
                    } else {
                        if ($history_pay_loan_capital_after->month < $config_loan_capital->count_month_preferential) {
                            $percent_interest = $config_loan_capital->preferential_interest_rate;
                        } else {
                            $percent_interest = $config_loan_capital->interest_rate;
                        }
                    }

                    if ($month_final == $history_pay_loan_capital_after->month && $loan_capital->pay == 1) {
                        $interest = $interest_incurred = 0;
                    } else {
                        $interest = ($history_pay_loan_capital_after->remaining_principal + $history_pay_loan_capital_after->principal) * ( ( (($percent_interest*$add_days)/(12*30)) )/100 );
                        $interest_incurred = $wanting_month_prev_money * ( ( (($config_loan_capital->deferred_interest*$add_days)/(12*30)) )/100 );
                    }

                    $total_price_real = $wanting_month_prev_money + $history_pay_loan_capital_after->remaining_principal + $history_pay_loan_capital_after->principal + $interest + $interest_incurred;
                }    

                $interest = $interest + $history_pay_loan_capital->interest;
                $interest_incurred = $interest_incurred + $history_pay_loan_capital->interest_incurred;
            }

            $total_price_real = round($total_price_real);

            if ($paid_money >= $total_price_real) {
                $history_pay_loan_capital->received_date = $received_date;
                $history_pay_loan_capital->interest = $interest;
                $history_pay_loan_capital->interest_incurred = $interest_incurred;
                $history_pay_loan_capital->paid_money = $paid_money;
                $history_pay_loan_capital->status = 1;
                $history_pay_loan_capital->final_settlement = 1;
                $history_pay_loan_capital->save();

                HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', '>', 0)->where('status', 0)->delete();

                // Hoàn tất nếu n/v thanh toán xong tất cả số tiền vay vốn + lãi\
                $loan_capital->status = 4;
                $loan_capital->save();

                return \Response::json(array('status' => 200, 'message' => 'Trả sớm toàn bộ cho nhân viên thành công!'));
            }

            return \Response::json(array('status' => 404, 'message' => 'Số tiền nhân viên tất toán chưa đủ. Xin vui lòng xem xét lại số tiền nhân viên chuyển khoản!'));
        }

    }

    public function moneyFinalSettlement(Request $request)
    {
        $loan_capital_id = $request->loan_capital_id;
        if ($loan_capital_id == '') {
            $loan_capital = LoanCapital::where('personnel_id', Auth::user()->id)
                                                ->where('loan_capital.status', 2)
                                                ->first();
            $config_loan_capital = ConfigLoanCapital::find($loan_capital->config_id);
     
            $history_pay_loan_capital = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital->id)
                                                            ->where('month', '>', 0)
                                                            ->where('status', 0)
                                                            ->orderBy('id', 'asc')
                                                            ->first();
            $received_date = date('Y-m-d');
            $repayment_period = $history_pay_loan_capital->repayment_period;
            $loan_capital_id = $history_pay_loan_capital->loan_capital_id;
        } else {
            $loan_capital = LoanCapital::where('id', $loan_capital_id)->first();
            $config_loan_capital = ConfigLoanCapital::find($loan_capital->config_id);
            $history_pay_loan_capital = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                            ->where('month', '>', 0)
                                                            ->where('status', 0)
                                                            ->orderBy('id', 'asc')
                                                            ->first();
            $received_date = $loan_capital->final_settlement_date_user;
            $repayment_period = $history_pay_loan_capital->repayment_period;
        }

        $month_final = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                            ->where('month', '>', 0)
                                                            ->count();
        
        $flag = true;
        $days = 30; // Ngày tính lại lãi suất cũ
        $days_first = 30; // Ngày tính lại lãi suất mới
        $add_days = 0;

        if( (BatvHelper::handlingTime($received_date) <= BatvHelper::handlingTime($repayment_period)) ){
            $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                                    ->where('status', 1)
                                                                    ->where('month', '>', 0)
                                                                    ->orderBy('month', 'desc')
                                                                    ->first();

            if (!$history_pay_loan_capital_before) {
                $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                        ->where('month', 0)
                                        ->first();
            }

            $date_partial_settlement = $history_pay_loan_capital_before->date_partial_settlement;

            if ($loan_capital->status_file == 2) {
                $percent_interest = $config_loan_capital->interest_file_late;
            } else {
                if ($history_pay_loan_capital->month < $config_loan_capital->count_month_preferential) {
                    $percent_interest = $config_loan_capital->preferential_interest_rate;
                } else {
                    $percent_interest = $config_loan_capital->interest_rate;
                }
            }

            // Trả cùng vào 1 ngày
            if ($date_partial_settlement == date('Y-m-d')) {
                $flag = false;
            } else {            
                $date_partial_settlement = $history_pay_loan_capital_before->date_partial_settlement;

                if ($date_partial_settlement != '') {// TH trả sớm một phần sau đó tất toán toàn bộ
                    $received_date = $date_partial_settlement;
                    $repayment_period = date('Y-m-d');
                    $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                    $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);

                    if ($year_repayment_period == $year_received_date) {
                        if ($month_repayment_period - $month_received_date == 0) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
    
                            }
        
                        } elseif($month_repayment_period - $month_received_date == 1) {
                        
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                            }
                        } elseif($month_repayment_period - $month_received_date > 1)  {
                            $flag = false;
                        }
                    } elseif($year_repayment_period - $year_received_date == 1) {
                        if ($month_repayment_period - $month_received_date == -11) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                            } elseif ($day_repayment_period <= $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                            }
                        } else {
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }

                    $days = $days_first;
                } else {
                    $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                    $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);

                    if ($year_repayment_period == $year_received_date) {
                        if ($month_repayment_period - $month_received_date == 0) {
                            if ($day_repayment_period == $day_received_date) {
                                $days_first = 0;
                                $days = 30;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;

                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;

                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }

                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                                $days = 30 - $days_first;
                            }

                        } elseif($month_repayment_period - $month_received_date == 1) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;

                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;

                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }

                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
                                $days = 30 - $days_first;
                            }
                        } elseif($month_repayment_period - $month_received_date > 1)  {
                            $flag = false;
                        }
                    } elseif($year_repayment_period - $year_received_date == 1) {
                        if ($month_repayment_period - $month_received_date == -11) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;

                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;

                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }

                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }

                                $days = 30 - $days_first;
                            }
                        } else {
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }

                }
        
            }

            if ($flag) {
                if ($days == 30) {
                    $total_price_real = $history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal+ $history_pay_loan_capital->interest + $history_pay_loan_capital->interest_incurred - $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                } else {
                    $interest = ($history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal) * ( ( (($percent_interest*$days)/(12*30)) )/100 );
                    $interest_incurred = ($history_pay_loan_capital->wanting_month_prev_money) * ( ( (($config_loan_capital->deferred_interest*$days)/(12*30)) )/100 );
                    $total_price_real = $history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal+ $interest + $interest_incurred - $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                }
            } else {
                $total_price_real = $history_pay_loan_capital->remaining_principal +  $history_pay_loan_capital->principal + $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
            }

            $total_price_real = round($total_price_real);

        } else {
            $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
            $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);

            for ($i = $day_repayment_period; $i  <= $day_received_date ; $i++) { 
                $add_days++;
            }

            $history_pay_loan_capital_after = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                            ->where('month', $history_pay_loan_capital->month + 1)
                                                            ->where('status', 0)
                                                            ->first();
            
            if (!$history_pay_loan_capital_after) {
                $wanting_month_prev_money = $history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal + $history_pay_loan_capital->interest + $history_pay_loan_capital->interest_incurred + $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
                
                if ($month_final == $history_pay_loan_capital->month && $loan_capital->pay == 1) {
                    $interest_incurred = 0;
                } else {
                    $interest_incurred = $wanting_month_prev_money * ( ( (($config_loan_capital->deferred_interest*$add_days)/(12*30)) )/100 );
                }

                $total_price_real = round($wanting_month_prev_money + $interest_incurred);
            } else {
                $wanting_month_prev_money = $history_pay_loan_capital->principal + $history_pay_loan_capital->interest + $history_pay_loan_capital->interest_incurred + $history_pay_loan_capital->paid_money - $history_pay_loan_capital->redundancy_month_prev_money + $history_pay_loan_capital->wanting_month_prev_money;
         
                if ($loan_capital->status_file == 2) {
                    $percent_interest = $config_loan_capital->interest_file_late;
                } else {
                    if ($history_pay_loan_capital_after->month < $config_loan_capital->count_month_preferential) {
                        $percent_interest = $config_loan_capital->preferential_interest_rate;
                    } else {
                        $percent_interest = $config_loan_capital->interest_rate;
                    }
                }
    
                if ($month_final == $history_pay_loan_capital_after->month && $loan_capital->pay == 1) {
                    $interest_incurred = $interest_incurred = 0;
                } else {
                    $interest = ($history_pay_loan_capital_after->remaining_principal + $history_pay_loan_capital_after->principal) * ( ( (($percent_interest*$add_days)/(12*30)) )/100 );
                    $interest_incurred = $wanting_month_prev_money * ( ( (($config_loan_capital->deferred_interest*$add_days)/(12*30)) )/100 );
                }
    
                $total_price_real = round($wanting_month_prev_money + $history_pay_loan_capital_after->remaining_principal + $history_pay_loan_capital_after->principal + $interest + $interest_incurred);
            }

        }

        return \Response::json(array('status' => 200, 'total_price_real' => $total_price_real));
    }

    public function approvedFile(Request $request)
    {
        $status_file = $request->status_file;
        $loan_capital_id = $request->loan_capital_id;

        $loan_capital = LoanCapital::find($loan_capital_id);

        if ($status_file == 2) {
            $disbursement_date = $loan_capital->disbursement_date;
            $config_loan_capital = ConfigLoanCapital::find($loan_capital->config_id);
            $data = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                            ->where('month', '>', 0)
                                            ->where('status', 0)
                                            ->orderBy('month', 'asc');

            if ($config_loan_capital) {
                // ===============Tính lịch trả nợ với dư nợ giảm dần===============
                $history_pay_loan_capital = $data->first();
                // dd($config_loan_capital->interest_file_late);die;
                // A/d với lãi suất ưu đãi
                $summoney = $time= $percent_interest = $goc = $lai = $goc_lai = $sum_goc = $sum_lai = 0;
                $summoney = $history_pay_loan_capital->remaining_principal + $history_pay_loan_capital->principal;
                $time = $data->count();
                $tempGoc =  $summoney;
                $goc = (int)($summoney) /  $time; // tính gốc
                $percent_interest = $config_loan_capital->interest_file_late;

                $flag = true; 
                // $flag = false; VD trả định kỳ ngày 30/09/2019 thì ông ấy trả 30/6/2019 chẳng hàn thì ko phải chịu lãi suất nào
                $days = 30; // Ngày tính lại lãi suất cũ
                $days_first = 30; // Ngày tính lại lãi suất mới

                $history_pay_loan_capital_before = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)
                                                ->where('status', 1)
                                                ->orderBy('month', 'desc')
                                                ->first();
                $received_date = $history_pay_loan_capital_before->received_date;
                $repayment_period = $history_pay_loan_capital->repayment_period;
                    
                if( (BatvHelper::handlingTime($received_date) <= BatvHelper::handlingTime($repayment_period)) ){
                    $day_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $day_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",false);
                    $month_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $month_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="m",$timeFormat="H:i:s",false);
                    $year_repayment_period = BatvHelper::formatDate($repayment_period,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
                    $year_received_date = BatvHelper::formatDate($received_date,'Y-m-d', $formatDate="Y",$timeFormat="H:i:s",false);
    
                    if ($year_repayment_period == $year_received_date) {
                        if ($month_repayment_period - $month_received_date == 0) {
                            if ($day_repayment_period == $day_received_date) {
                                $days_first = 0;
                                $days = 30;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            }
        
                        } elseif($month_repayment_period - $month_received_date == 1) {

                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            }
                        } elseif($month_repayment_period - $month_received_date > 1)  {
                            $flag = false;
                        }
                    } elseif($year_repayment_period - $year_received_date == 1) {
                        if ($month_repayment_period - $month_received_date == -11) {
                            if ($day_repayment_period == $day_received_date) {
                                $flag = false;
                            } elseif ($day_repayment_period > $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            } elseif ($day_repayment_period < $day_received_date) {
                                $days_first = 0;
        
                                for ($i = $day_received_date + 1; $i <= 30; $i++) { 
                                    $days_first++;
                                }
        
                                for ($i = 1; $i < $day_repayment_period; $i++) { 
                                    $days_first++;
                                }
        
                                $days = 30 - $days_first;
                            }
                        } else {
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }
                }
                // echo $days_first;die;
                for ( $i = $history_pay_loan_capital->month;  $i <=  ($history_pay_loan_capital->month + $time);  $i++) {
                    $sum_goc =  $sum_goc +  $goc;

                    if ($i == $history_pay_loan_capital->month) {
                        $lai = $tempGoc * ( ( (($percent_interest*$days_first)/(12*30)) )/100 );
                        $interest_incurred = $history_pay_loan_capital->wanting_month_prev_money * ( ( (($config_loan_capital->deferred_interest*$days_first)/(12*30)) )/100 );
                    } else {
                        $interest_incurred = 0;
                        $lai = $tempGoc * ( ( (($percent_interest*$days)/(12*30)) )/100 );
                    }

                    $tempGoc =  $tempGoc -  $goc; // gốc còn lại theo từng tháng
                    $tempGoc = ($tempGoc < 0) ? 0 : $tempGoc;
                    $goc_lai =  $goc +  $lai;
                    $sum_lai =  $sum_lai +  $lai;
                    $arr = [
                                'remaining_principal' => $tempGoc,
                                'principal' => $goc,
                                'interest' => $lai,
                                'interest_incurred' => $interest_incurred,
                            ];

                    HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', $i)->update($arr);
                }

                $loan_capital->status_file = $status_file;
                $loan_capital->save();
                
            } else {
                return \Response::json(array('status' => 400, 'message' => 'Ngày giải ngân chưa được phê duyệt hoặc chưa có cấu hình cho thời gian giải ngân này!'));
            }
        } else {
            $loan_capital->status_file = $status_file;
            $loan_capital->save();
        }

        return \Response::json(array('status' => 200, 'message' => 'Đã phê duyệt thành công!'));
    }

    public function scoreFaithLoanCapital(Request $request)
    {
        $personnel_id = $request->personnel_id;
        $date_current = date('Y-m-d');
        $user_id = Auth::user()->id;
        $myfunc = new Myfunction();
        $select = 0;
        $department_id = [];
        
        if ($request->input('selectDepart') != '') {
            $select = $request->input('selectDepart');
            $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart, 'departments');

            if (count($tmp) == 0) {
                $department_id = array($request->selectDepart);
            } else {
                $department_id = BatvHelper::array_keys_multi($tmp);
            }
        } else {
            $check = Evaluation::checkDepartmentbyManager($user_id);
            $tmp = [];
    
            foreach ($check as $key => $value) {
                $tmp[$value->id] = $myfunc->categoryChild($value->id, 'departments');
            }
    
            $department_id = BatvHelper::array_keys_multi($tmp);

        }

        if ($user_id == 1) {
            $depart = Personnel::listDepartment();
            $department = $myfunc->callProcessSelect($depart, 0, '', $select);
        } else {
            $check = Evaluation::checkDepartmentbyManager($user_id);
            $tmp = [];
            
            foreach ($check as $key => $value) {
                $tmp[$value->id] = $myfunc->categoryChild($value->id, 'departments');
            }
            
            $depart = \DB::table('departments')->select('id','title','parent_id')->whereIn('id', BatvHelper::array_keys_multi($tmp))->get();
            $department = $myfunc->callProcessSelect($depart, $depart[0]->parent_id, '', $select);
        }

        $score_min = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->value('score_min');
        $score_min = $score_min ? $score_min : 0;
        $list_all_personnel = Personnel::getAllPersonnelCurrentbyManager($department_id);
        
        $query = Personnel::leftJoin('personnel_title', 'personnel_title.personnel_id', '=', 'personnel.id')
                            ->leftJoin('job_titles', 'job_titles.id', '=', 'personnel_title.job_title_id')
                            ->leftJoin('propose_score_faith', 'propose_score_faith.personnel_id', '=', 'personnel.id')
                            ->leftJoin('departments', 'departments.id', '=', 'personnel.department_id')
                            ->select('personnel.*', 'personnel_title.job_title_id', 'departments.title', 'job_titles.title as jobs', 'propose_score_faith.score', 'propose_score_faith.note')
                            ->where('personnel.status', '=', 1)
                            ->where('personnel.date_out', '=', NULL)
                            ->whereIn('personnel.department_id', $department_id)
                            ->groupBy('personnel.id');

        if ($personnel_id != 0) {
            $query = $query->where('personnel.id', $personnel_id);
        }

        $status = $request->status;

        if ($status > 0) {
            if ($status == 1) {
                $query = $query->where('personnel.score_faith', '>=', $score_min);
            } else {
                $query = $query->where('personnel.score_faith', '<', $score_min);
            }
        }

        $data = $query->paginate(10);

        return view('layouts.vay-von.score-faith', compact('data', 'list_all_personnel', 'score_min', 'department'));
    }

    public function loanCompleteFile(Request $request)
    {
        $str_image = '';

        if ($request->file_old) {
            foreach ($request->file_old as $value) {
                $str_image .= $value . ',';
            }
        }
        
        $edit_loan_conplete_file = LoanCapital::where('personnel_id', Auth::user()->id)->whereIn('loan_capital.status', [0,1,2])->first();
        $edit_loan_conplete_file->file = rtrim($str_image,",");
        $edit_loan_conplete_file->save();
        

        if ($request->file_old_delete) {
           foreach ($request->file_old_delete as $value) {
                if(file_exists(public_path('images/').$value)){
                    unlink(public_path('images/').$value);
                }
           }
        }

        return response()->json(array('status' => 200, 'message' => 'Đã cập nhật thành công!'));
    }

    public function droponeJs(Request $request)
    {
        $files = $request->file('files');
        // echo '<pre>';
        // print_r($files);die;
        if ($files) {
            $edit_loan_conplete_file = LoanCapital::where('personnel_id', Auth::user()->id)->whereIn('loan_capital.status', [0,1,2])->first();
            $destination_path = public_path('images');
            $data = [];
            $str_image = ($edit_loan_conplete_file->file != '') ? $edit_loan_conplete_file->file.',' : '';
    
            foreach ($files as $file_object ) {
                if ($file_object->getSize() > 0) {
                    $file_name = $file_object->getClientOriginalName();
                    $ext  = \File::extension($file_name);
                    $ext  = strtolower($ext);
                    $file_name = str_replace(".".$ext,"", $file_name);
                    $destination_file_name = Str::slug($file_name, '-') . round(microtime(true) * 1000) . '.' . $ext;
                    $file_object->move($destination_path, $destination_file_name);
                    $str_image .= $destination_file_name .',';
                }
            }
    
            $edit_loan_conplete_file->file = rtrim($str_image,",");
            $edit_loan_conplete_file->save();
        }
        // echo '<pre>';
        // print_r($data);
        if ( isset($request->register) && $request->register == 1) {
            return response()->json(array('status' => 200, 'message' => 'Gửi yêu cầu thành công!'));
        }

        return response()->json(array('status' => 200, 'message' => 'Đã cập nhật thành công!'));
    }

    public function userEditRegisterLoanCapitalAjax(Request $request)
    {
        $personnel_id = Auth::user()->id;
        $loan_purpose = $request->loan_purpose;
        $loan_purpose = $request->loan_purpose;
        $date_current = date('Y-m-d');
        $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->first();
        $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : $time = date('Y').'-12';
        $salary_official_default = BatvHelper::ltt('',Auth::user()->id,$time,$type=1,'',$option=1,$convert_ratio='');

        if ($loan_purpose == 3) {
            $rules = [
                'max_money'                           => 'required|numeric|min:1|max:'.($config_loan_capital->month_time_max * $salary_official_default),
                'month_time'                          => 'required|numeric|min:1|max:'.$config_loan_capital->month_time_max,
                'disbursement_date_by_user'           => 'required|date_format:"d/m/Y"',
                'loan_purpose'                        => 'required',
                // 'info_receive_disbursement'           => 'required',
                'another_purpose'                     => 'required',
            ];

            $messages = [
                'max_money.required'                     => 'Số tiền vay không được để trống.',
                'max_money.min'                          => 'Số tiền vay phải lớn hơn 0.',
                'max_money.max'                          => 'Số tiền vay không được vượt quá '.number_format($config_loan_capital->month_time_max * $salary_official_default).' VND.',
                'month_time.required'                    => 'Thời gian vay không được để trống.',
                'month_time.min'                         => 'Thời gian vay phải lớn hơn 0.',
                'month_time.max'                         => 'Thời gian vay không được vượt quá '.$config_loan_capital->month_time_max.' tháng.',
                // 'info_receive_disbursement.required'     => 'Thông tin tài khoản nhận giải ngân không được để trống.',
                'disbursement_date_by_user.required'     => 'Thời gian mong muốn giải ngân không được để trống.',
            ];
        } else {
            $rules = [
                'max_money'                           => 'required|numeric|min:1|max:'.($config_loan_capital->month_time_max * $salary_official_default),
                'month_time'                          => 'required|numeric|min:1|max:'.$config_loan_capital->month_time_max,
                'disbursement_date_by_user'           => 'required|date_format:"d/m/Y"',
                // 'info_receive_disbursement'           => 'required',
                'loan_purpose'                        => 'required',
            ];

            $messages = [
                'max_money.required'                   => 'Số tiền vay không được để trống.',
                'max_money.min'                       => 'Số tiền vay phải lớn hơn 0.',
                'max_money.max'                        => 'Số tiền vay không được vượt quá '.number_format($config_loan_capital->month_time_max * $salary_official_default).' VND.',
                'month_time.required'                  => 'Thời gian vay không được để trống.',
                'month_time.min'                      => 'Thời gian vay phải lớn hơn 0.',
                'month_time.max'                       => 'Thời gian vay không được vượt quá '.$config_loan_capital->month_time_max.' tháng.',
                // 'info_receive_disbursement.required'     => 'Thông tin tài khoản nhận giải ngân không được để trống.',
                'disbursement_date_by_user.required'   => 'Thời gian mong muốn giải ngân không được để trống.',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return \Response::json(array('status' => 404, 'message' => $validator->errors()));
        } else {
            $edit_register_loan_capital = LoanCapital::where('personnel_id', $personnel_id)->whereIn('status', [0,1,2])->first();
            $edit_register_loan_capital->max_money = $request->max_money;
            $edit_register_loan_capital->month_time = $request->month_time;
            $edit_register_loan_capital->disbursement_date_by_user = BatvHelper::formatDate($request->disbursement_date_by_user,'d/m/Y', 'Y-m-d', 'H:i:s', false);;
            $edit_register_loan_capital->loan_purpose = $loan_purpose;
            $edit_register_loan_capital->another_purpose = $request->another_purpose;
            $edit_register_loan_capital->disbursement_form = $request->disbursement_form;
            $edit_register_loan_capital->info_receive_disbursement = $request->info_receive_disbursement;
            $edit_register_loan_capital->pay = $request->pay;

            $str_image = '';

            if ($request->file_old) {
                foreach ($request->file_old as $value) {
                    $str_image .= $value . ',';
                }
            }
            
            $edit_register_loan_capital->file = rtrim($str_image,",");
    
            if ($request->file_old_delete) {
               foreach ($request->file_old_delete as $value) {
                    if(file_exists(public_path('images/').$value)){
                        unlink(public_path('images/').$value);
                    }
               }
            }

            $edit_register_loan_capital->save();

            return \Response::json(array('status' => 200, 'message' => 'Đã cập nhật thành công!'));
        }
    }

    public function listLoanCapitalHistory(Request $request)
    {
        // $data = LoanCapital::selectRaw('DATE_FORMAT(disbursement_date , "%d/%m/%Y") as disbursement_date, max_money, month_time')
        //                     ->where('personnel_id', $request->personnel_id)
        //                     ->where('status', 4)
        //                     ->get();

        $data = LoanCapital::leftJoin('history_pay_loan_capital', 'loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
        ->leftJoin('config_loan_capital', 'config_loan_capital.id', '=', 'loan_capital.config_id')
        ->selectRaw('config_loan_capital.*,history_pay_loan_capital.repayment_period,min(history_pay_loan_capital.repayment_period) as repayment_period,max(history_pay_loan_capital.received_date) as received_date,SUM(history_pay_loan_capital.interest) as total_interest,SUM(history_pay_loan_capital.interest_incurred) as total_interest_incurred,SUM(history_pay_loan_capital.paid_money) as total_paid_money,loan_capital.*,DATE_FORMAT(disbursement_date , "%d/%m/%Y") as disbursement_date,DATE_FORMAT(repayment_period , "%d/%m/%Y") as repayment_period')
        ->where('loan_capital.personnel_id', $request->personnel_id)
        ->where('loan_capital.status', 4)
        ->where('history_pay_loan_capital.status', '>', 0)
        ->groupBy('loan_capital.id')
        ->get();
        // dd($history_loan_capital);
        return response()->json(['status' => 200, 'data' => $data]);
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
}
