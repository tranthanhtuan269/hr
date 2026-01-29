<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Http\Requests;
use App\Models\Expense;
use App\Models\Personnel;
use App\Models\User;
use App\Models\EmailConfig;
use App\Models\SignFunds;
use App\Models\WelfareFunds;
use App\Models\SettingCurrency;
use DateTime;
use App\Helpers\BatvHelper;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Models\FileMetadata;
use App\Models\LoanCapital;
use App\Models\HistoryPayLoanCapital;

class ExpenseController extends Controller
{

    public function getFundsList(Request $request){
        $data = Expense::getFundsList($request);
        return view('layouts.chiphi.quy.index',['data'=>$data]);
    }

    public function getFundsAdd(){
        return view('layouts.chiphi.quy.add');
    }

    public function postFundsAdd( Request $request ){
        $rules = [
            'title' =>'required',
        ];
        $messages = [
            'title.required' => 'Tên loại quỹ không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

             $arr = [
                'title' =>  trim($request->title),
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1,
            ];
            Expense::insertFunds($arr); 
            return back()->with(['flash_message_succ' => 'Thêm loại quỹ thành công']);
        }
    }

    public function getFundsEdit($id){
        $data = Expense::infoFunds($id);
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.chiphi.quy.edit',['data'=>$data]);
    }    

    public function  postFundsEdit(Request $request,$id){
        $rules = [
            'title' =>'required',
        ];
        $messages = [
            'title.required' => 'Tiêu đề tin không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            //echo 1;die;
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title' =>  trim($request->title),
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            Expense::updateFunds($arr,$id);  
            return redirect()->route('getFundsEdit',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function  getFundsDel($id){
        $arr = [
            'status' =>  0,
        ];
        Expense::updateFunds($arr,$id); 
        if($arr){
            return back()->with(['flash_message_succ' =>'Xóa thông tin thành công']);
        }
    }

    public function getExpenseList(Request $request){
        $expenseGeneral = $expenseMerge = $expenseMerge_v2 = $expenseMerge_v3 = $expenseMerge_v4 = array();
        if(  !isset($request->viewfast) || $request->viewfast == '' ){
            if( !empty(  $request->valid_from ) && !empty(  $request->valid_to ) ){
                $valid_from = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $valid_to = BatvHelper::formatDate($request->valid_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            }else{  
                $valid_from = date('Y')."-".date('m')."-"."01";

                $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
                $valid_to = date('Y')."-".date('m')."-".$numberDay;

            }
        }else{
            if( $request->viewfast == 0 ){
                $valid_from = date('Y')."-".date('m')."-"."01";

                $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
                $valid_to = date('Y')."-".date('m')."-".$numberDay;
            }elseif( $request->viewfast == 1 ){
                $date_from = date('Y')."-".date('m')."-"."01";
                $date_from = strtotime($date_from.'-1 month');
                $valid_from = date('Y-m-d', $date_from);

                $convert_to = explode("-",$valid_from);
                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert_to[1], $convert_to[0]);
                $valid_to = $convert_to[0]."-".$convert_to[1]."-".$numberDay;
            }elseif ( $request->viewfast == 2 ) {
                if(  (int)date('m') >=1 && (int)date('m')<=3 ){
                    $valid_from  = date('Y').'-01-01';

                    $numberDay = cal_days_in_month(CAL_GREGORIAN,3, date('Y'));
                    $valid_to  = date('Y').'-03-'.$numberDay;
                }elseif ( (int)date('m') >3 && (int)date('m')<=6 ) {
                    $valid_from  = date('Y').'-04-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,6, date('Y'));
                    $valid_to  = date('Y').'-06-'.$numberDay;
                }elseif ( (int)date('m') >6 && (int)date('m')<=9 ) {
                    $valid_from  = date('Y').'-07-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,9, date('Y'));
                    $valid_to  = date('Y').'-09-'.$numberDay;
                }else{
                    $valid_from  = date('Y').'-10-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y'));
                    $valid_to  = date('Y').'-12-'.$numberDay;
                }
            }elseif ( $request->viewfast == 3 ) {
                if(  (int)date('m') >=1 && (int)date('m')<=3 ){
                    $valid_from  = (date('Y')-1).'-09-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y')-1);
                    $valid_to  = ( date('Y')-1 ).'-12-'.$numberDay;
                }elseif ( (int)date('m') >3 && (int)date('m')<=6 ) {
                    $valid_from  = date('Y').'-01-01';

                    $numberDay = cal_days_in_month(CAL_GREGORIAN,3, date('Y'));
                    $valid_to  = date('Y').'-03-'.$numberDay;
                }elseif ( (int)date('m') >6 && (int)date('m')<=9 ) {
                    $valid_from  = date('Y').'-04-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,6, date('Y'));
                    $valid_to  = date('Y').'-06-'.$numberDay;
                }else{
                    $valid_from  = date('Y').'-07-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,9, date('Y'));
                    $valid_to  = date('Y').'-09-'.$numberDay;
                }
            }elseif ( $request->viewfast == 4 ) {
                $valid_from = date('Y')."-01-"."01";
                $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y'));
                $valid_to  = date('Y').'-12-'.$numberDay;
            }elseif ( $request->viewfast == 5 ) {
                $valid_from = (date('Y')-1)."-01-"."01";

                $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y')-1);
                $valid_to  = ( date('Y')-1 ).'-12-'.$numberDay;
            }

        }
        // echo $valid_to;die;
        $expense = Expense::getExpenseGeneral( $valid_from,$valid_to,$request );
        if( $expense ){
            foreach ($expense as $key => $value) {
                if( !isset($expenseMerge[$value->id]) ){
                    $expenseMerge[$value->id]['expense_id'] = $value->id;
                    $expenseMerge[$value->id]['title'] = $value->title;
                    $expenseMerge[$value->id]['value'] = $value->value;
                    $expenseMerge[$value->id]['value_usd'] = $value->value_usd;
                    $expenseMerge[$value->id]['percent'] = $value->percent;
                    $expenseMerge[$value->id]['description'] = $value->description;
                    $expenseMerge[$value->id]['param'] = BatvHelper::calculateMonth( $valid_from,$valid_to );
                    $expenseMerge[$value->id]['valid_from'] = $value->valid_from;
                    $expenseMerge[$value->id]['valid_to'] = $value->valid_to;
                    $expenseMerge[$value->id]['created_by'] = $value->created_by;
                    $expenseMerge[$value->id]['created_at'] = $value->created_at;
                    $expenseMerge[$value->id]['type'] = $value->type;
                    $expenseMerge[$value->id]['link_dropbox'] = $value->link_dropbox;
                    $expenseMerge[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge[$value->id]['percent_arr'][] = $value->percent;
                }else{
                    $expenseMerge[$value->id]['percent'] += $value->percent;
                    $expenseMerge[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge[$value->id]['percent_arr'][] = $value->percent;
                }
            }
        }
        $expense_v2 = Expense::getExpenseGeneral_v2( $valid_from,$valid_to,$request );
        if( $expense_v2 ){
            foreach ($expense_v2 as $key => $value) {
                $apply_to = BatvHelper::formatDate($value->valid_to,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                if( !isset($expenseMerge_v2[$value->id]) ){
                    $expenseMerge_v2[$value->id]['expense_id'] = $value->id;
                    $expenseMerge_v2[$value->id]['title'] = $value->title;
                    $expenseMerge_v2[$value->id]['value'] = $value->value;
                    $expenseMerge_v2[$value->id]['value_usd'] = $value->value_usd;
                    $expenseMerge_v2[$value->id]['percent'] = $value->percent;
                    $expenseMerge_v2[$value->id]['description'] = $value->description;
                    $expenseMerge_v2[$value->id]['param'] = BatvHelper::calculateMonth($valid_from,$apply_to);
                    $expenseMerge_v2[$value->id]['valid_from'] = $value->valid_from;
                    $expenseMerge_v2[$value->id]['valid_to'] = $value->valid_to;
                    $expenseMerge_v2[$value->id]['created_by'] = $value->created_by;
                    $expenseMerge_v2[$value->id]['created_at'] = $value->created_at;
                    $expenseMerge_v2[$value->id]['type'] = $value->type;
                    $expenseMerge_v2[$value->id]['link_dropbox'] = $value->link_dropbox;
                    $expenseMerge_v2[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v2[$value->id]['percent_arr'][] = $value->percent;
                }else{
                    $expenseMerge_v2[$value->id]['percent'] += $value->percent;
                    $expenseMerge_v2[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v2[$value->id]['percent_arr'][] = $value->percent;
                }
            }
        }

        $expense_v3 = Expense::getExpenseGeneral_v3( $valid_from,$valid_to,$request );
        if( $expense_v3 ){
            foreach ($expense_v3 as $key => $value) {
                $apply_from = BatvHelper::formatDate($value->valid_from,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                if( !isset($expenseMerge_v3[$value->id]) ){
                    $expenseMerge_v3[$value->id]['expense_id'] = $value->id;
                    $expenseMerge_v3[$value->id]['title'] = $value->title;
                    $expenseMerge_v3[$value->id]['value'] = $value->value;
                    $expenseMerge_v3[$value->id]['value_usd'] = $value->value_usd;
                    $expenseMerge_v3[$value->id]['percent'] = $value->percent;
                    $expenseMerge_v3[$value->id]['description'] = $value->description;
                    $expenseMerge_v3[$value->id]['param'] = BatvHelper::calculateMonth( $apply_from,$valid_to );
                    $expenseMerge_v3[$value->id]['valid_from'] = $value->valid_from;
                    $expenseMerge_v3[$value->id]['valid_to'] = $value->valid_to;
                    $expenseMerge_v3[$value->id]['created_by'] = $value->created_by;
                    $expenseMerge_v3[$value->id]['created_at'] = $value->created_at;
                    $expenseMerge_v3[$value->id]['type'] = $value->type;
                    $expenseMerge_v3[$value->id]['link_dropbox'] = $value->link_dropbox;
                    $expenseMerge_v3[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v3[$value->id]['percent_arr'][] = $value->percent;
                }else{
                    $expenseMerge_v3[$value->id]['percent'] += $value->percent;
                    $expenseMerge_v3[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v3[$value->id]['percent_arr'][] = $value->percent;
                }
            }
        }

        $expense_v4 = Expense::getExpenseGeneral_v4( $valid_from,$valid_to,$request );
        if( $expense_v4 ){
            foreach ($expense_v4 as $key => $value) {
                $apply_from = BatvHelper::formatDate($value->valid_from,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                $apply_to = BatvHelper::formatDate($value->valid_to,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                if( !isset($expenseMerge_v4[$value->id]) ){
                    $expenseMerge_v4[$value->id]['expense_id'] = $value->id;
                    $expenseMerge_v4[$value->id]['title'] = $value->title;
                    $expenseMerge_v4[$value->id]['value'] = $value->value;
                    $expenseMerge_v4[$value->id]['value_usd'] = $value->value_usd;
                    $expenseMerge_v4[$value->id]['percent'] = $value->percent;
                    $expenseMerge_v4[$value->id]['description'] = $value->description;
                    $expenseMerge_v4[$value->id]['param'] = BatvHelper::calculateMonth( $apply_from,$apply_to );
                    $expenseMerge_v4[$value->id]['valid_from'] = $value->valid_from;
                    $expenseMerge_v4[$value->id]['valid_to'] = $value->valid_to;
                    $expenseMerge_v4[$value->id]['created_by'] = $value->created_by;
                    $expenseMerge_v4[$value->id]['created_at'] = $value->created_at;
                    $expenseMerge_v4[$value->id]['type'] = $value->type;
                    $expenseMerge_v4[$value->id]['link_dropbox'] = $value->link_dropbox;
                    $expenseMerge_v4[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v4[$value->id]['percent_arr'][] = $value->percent;
                }else{
                    $expenseMerge_v4[$value->id]['percent'] += $value->percent;
                    $expenseMerge_v4[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v4[$value->id]['percent_arr'][] = $value->percent;
                }
            }
        }
        $expenseGeneral = array_merge( $expenseMerge,$expenseMerge_v2,$expenseMerge_v3,$expenseMerge_v4 );
        // echo "<pre>";
        // print_r($expenseGeneral);die;
        $getListManager = User::getListManager();
        // echo "<pre>";
        // print_r($getListManager);die;
        $expenseGeneral = BatvHelper::PagingDataSpecial($expenseGeneral);
        $listFunds = Personnel::listFunds();
        return view('layouts.chiphi.index',['data'=>$expenseGeneral,'listFunds'=>$listFunds,'getListManager'=>$getListManager]);
    }

    public function getExpenseAdd(){
        $time = date('Y-m-d');
        $value_usd = SettingCurrency::where([
                                    ['apply_from', '<=', $time],
                                    ['apply_to', '>=', $time],
                                ])->value('value');
        $listFunds = Personnel::listFunds();
        $funds_id_default = Expense::getIdFundsDefault();
        $getListManager = User::getListManager();
        return view('layouts.chiphi.add',['listFunds'=>$listFunds,'funds_id_default'=>$funds_id_default,'getListManager'=>$getListManager,'value_usd'=>$value_usd]);
    }

    public function postExpenseAdd( Request $request ){
        // Phần Validation xử lý trường hợp tổng quỹ được chọn phải bằng 100%
        Validator::extend('validator_expense', function($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $tmp = 0;
            foreach ($data['fund'] as $key => $value) {
                $tmp += $data['percent'][$key];
            }
            // echo round($tmp,2);die;
            return ( round($tmp,2) == 99.99 || round($tmp,2) == 100 )?TRUE:FALSE;
        });   

        // Phần Validation xử lý trường hợp thời gian from to
        Validator::extend('validator_datetime_from_to', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $value = BatvHelper::formatDate($value, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          return ( ( BatvHelper::handlingTime($min_value) - BatvHelper::handlingTime($value))>0 )?FALSE:TRUE;
        }); 

        $rules = [
            'title' => 'required|max:255',
            'value' => 'required|numeric|min:1',
            'fund' => 'required|validator_expense',
            'valid_to' => 'validator_datetime_from_to:valid_from'
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương',
            'fund.required' => 'Bạn chưa chọn quỹ',
            'fund.validator_expense' => 'Tổng phần trăm các quỹ được chọn phải bằng 100%',
            'valid_to.validator_datetime_from_to'=>'Chọn thời gian hiệu lực không hợp lệ'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            if( $request->type == 0 ){
                $convert_from = explode("/",$request->valid_from);
                $valid_from = $convert_from[2]."-".$convert_from[1]."-".$convert_from[0];

                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert_from[1],$convert_from[2]);
                $valid_to = $convert_from[2]."-".$convert_from[1]."-".$numberDay;

                $year = $convert_from[2];
                $month = $convert_from[1];
            }else{
                $valid_from = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $valid_to = BatvHelper::formatDate($request->valid_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $year = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='Y',$timeFormat='H:i:s',$time=false);
                $month = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='m',$timeFormat='H:i:s',$time=false);
            }
            $link_dropbox = '';
            if($request->hasFile('fileImage')){
                if($request->file('fileImage')->isValid()){
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }else{
                        $app = new DropboxApp( env('DROPBOX_KEY'),env('DROPBOX_SECRET'),env('DROPBOX_TOKEN'));
                        $dropbox = new Dropbox($app);
                        $filename = '/'.time().'_'.str_replace(' ', '', $request->file('fileImage')->getClientOriginalName());
                        $destinationPath = 'uploads/tmp_chiphi/';
                        $request->file('fileImage')->move($destinationPath,$filename);
                        $dropboxFile = new DropboxFile(public_path('uploads/tmp_chiphi' . $filename));
                        $file = $dropbox->upload($dropboxFile,'/'.$year.'/'.$month.$filename, ['autorename' => true]);
                        $response = $dropbox->postToAPI("/sharing/create_shared_link_with_settings", [
                            "path" => '/'.$year.'/'.$month.$filename
                        ]);
                        $data = $response->getDecodedBody();
                        $link_dropbox = $data['url'];
                        $filePathServer = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp_chiphi'.$filename;
                        unlink($filePathServer);
                    }
                }
            }

            $arr = [
                'title'      => $request->title,
                'value'      => $request->value,
                'value_usd'      => $request->value_usd,
                'type'       => $request->type,
                'description'=> $request->description,
                'valid_from' =>  $valid_from,
                'valid_to'   => $valid_to,
                'link_dropbox'=>$link_dropbox,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $request->personnel,
            ];
            Expense::insertExpense($arr); 
            $id =  \DB::getPdo()->lastInsertId(); 
            foreach ($request->fund as $key => $value) {
                $funds[] =  array('funds_id'=>$value,'expense_id'=>$id, 'percent'=>$request->percent[$value] );
            }
            Expense::insertFundsExpense($funds); 

            //Gửi Email báo cho admin khi khởi tạo chi phí
            $infoConfigMail = EmailConfig::getInfoEmailConfig($type = 5);
            $email = explode(",",$infoConfigMail->mail_to);
            $listEmail = EmailConfig::getListEmailbyidPersonnel($email);

            $listEmailConvert = array();
            if( $listEmail ){
                foreach ($listEmail as $key => $value) {
                    if( $value->id != Auth::user()->id ){
                        $listEmailConvert[] = $value->email;
                    }
                }
            }
            $content_mail = array(
                                'title'=>$request->title,
                                'content' =>  $infoConfigMail->mail_content,
                                'link'=> route('getExpenseEdit',['id'=>$id]),
                            );
            $title = $request->title;
            $month = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='m',$timeFormat='H:i:s',$time=false);
            $price = BatvHelper::formatPrice($request->value);
            $id = $request->personnel;
            \Mail::send('emails.expense', $content_mail, function($message) use ($listEmailConvert,$title,$month,$price,$id) {
                $infoConfigMail = EmailConfig::getInfoEmailConfig($type = 5);
                $message->from('nhansu@tohsoft.com', 'TOH');
                $title = $title." - T".$month." - ".$price."VNĐ vừa được tạo bởi ".BatvHelper::getInfoUser($id);
                $message->to($listEmailConvert)->subject($title);
            });
            return back()->with(['flash_message_succ' => 'Thêm chi phí thành công']);
        }
    }

    public function viewExpenseDetail($id){
        $listFunds = Personnel::listFunds();
        $listFundsExpense = Expense::listFundsExpense($id);
        // echo "<pre>";
        // print_r($listFundsExpense);die;
        $arr=array();
        foreach ($listFundsExpense as $key => $value) {
            $arr[] = $value->funds_id;
            $listFundsExpense[$value->funds_id] = $value->percent;
        }

        $data = Expense::infoExpense($id);
        foreach ($listFunds as $key => $value) {
            if( in_array($value->id, $arr) ){
                $listFunds[$key]->selected = 1;
                $listFunds[$key]->percent = $listFundsExpense[$value->id];
            }
        }
        // echo "<pre>";
        // print_r($listFunds);die;
        return view('layouts.chiphi.edit',['data'=>$data,'listFunds'=>$listFunds]);
    }

    public function getExpenseEdit($id){
        $listFunds = Personnel::listFunds();
        $listFundsExpense = Expense::listFundsExpense($id);
        $getListManager = User::getListManager();
        $arr=array();
        foreach ($listFundsExpense as $key => $value) {
            $arr[] = $value->funds_id;
            $listFundsExpense[$value->funds_id] = $value->percent;
        }

        $data = Expense::infoExpense($id);
        $time = $data->valid_from;
        $value_usd = SettingCurrency::where([
                                    ['apply_from', '<=', $time],
                                    ['apply_to', '>=', $time],
                                ])->value('value');
        foreach ($listFunds as $key => $value) {
            if( in_array($value->id, $arr) ){
                $listFunds[$key]->selected = 1;
                $listFunds[$key]->percent = $listFundsExpense[$value->id];
            }
        }
        return view('layouts.chiphi.edit',['data'=>$data,'listFunds'=>$listFunds,'getListManager'=>$getListManager,'value_usd'=>$value_usd]);
    }

    public function postExpenseEdit( Request $request , $id){
        // Phần Validation xử lý trường hợp tổng quỹ được chọn phải bằng 100%
        Validator::extend('validator_expense', function($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $tmp = 0;
            foreach ($data['fund'] as $key => $value) {
                $tmp += $data['percent'][$key];
            }
            return ( round($tmp,2) == 99.99 || round($tmp,2) == 100 )?TRUE:FALSE;
        });   

        $rules = [
            'title' => 'required|max:255',
            'value' => 'required|numeric|min:1',
            'fund' => 'required|validator_expense',
            'valid_to' => 'validator_datetime_from_to:valid_from'
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương',
            'fund.required' => 'Bạn chưa chọn quỹ',
            'fund.validator_expense' => 'Tổng phần trăm các quỹ được chọn phải bằng 100%',
            'valid_to.validator_datetime_from_to'=>'Chọn thời gian hiệu lực không hợp lệ'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->type == 0 ){
                $convert_from = explode("/",$request->valid_from);
                $valid_from = $convert_from[2]."-".$convert_from[1]."-".$convert_from[0];

                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert_from[1],$convert_from[2]);
                $valid_to = $convert_from[2]."-".$convert_from[1]."-".$numberDay;

                $year = $convert_from[2];
                $month = $convert_from[1];
            }else{
                $valid_from = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $valid_to = BatvHelper::formatDate($request->valid_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $year = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='Y',$timeFormat='H:i:s',$time=false);
                $month = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='m',$timeFormat='H:i:s',$time=false);
            }

            if($request->hasFile('fileImage')){
                if($request->file('fileImage')->isValid()){
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }else{
                        $app = new DropboxApp( env('DROPBOX_KEY'),env('DROPBOX_SECRET'),env('DROPBOX_TOKEN'));
                        $dropbox = new Dropbox($app);
                        $filename = '/'.time().'_'.str_replace(' ', '', $request->file('fileImage')->getClientOriginalName());
                        $destinationPath = 'uploads/tmp_chiphi/';
                        $request->file('fileImage')->move($destinationPath,$filename);
                        $dropboxFile = new DropboxFile(public_path('uploads/tmp_chiphi' . $filename));
                        $file = $dropbox->upload($dropboxFile,'/'.$year.'/'.$month.$filename, ['autorename' => true]);
                        $response = $dropbox->postToAPI("/sharing/create_shared_link_with_settings", [
                            "path" => '/'.$year.'/'.$month.$filename
                        ]);
                        $data = $response->getDecodedBody();
                        $link_dropbox = $data['url'];
                        $filePathServer = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp_chiphi'.$filename;
                        unlink($filePathServer);
                    }
                }
            }else{
                $item = Expense::find($id);
                $link_dropbox = $item['link_dropbox'];
            }
            $arr = [
                'title'      => $request->title,
                'value'      => $request->value,
                'value_usd'      => $request->value_usd,
                'type'       => $request->type,
                'description'=> $request->description,
                'valid_from' =>  $valid_from,
                'valid_to'   => $valid_to,
                'link_dropbox'=>$link_dropbox,
                'created_by' => $request->personnel,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ];
            Expense::updateExpense($arr,$id); 
            Expense::deleteFundsExpense($id);
            foreach ($request->fund as $key => $value) {
                $funds[] =  array('funds_id'=>$value,'expense_id'=>$id, 'percent'=>$request->percent[$value] );
            }
            Expense::insertFundsExpense($funds); 

            //Gửi Email báo cho admin khi cập nhật chi phí
            $infoConfigMail = EmailConfig::getInfoEmailConfig($type = 5);
            $email = explode(",",$infoConfigMail->mail_to);
            $listEmail = EmailConfig::getListEmailbyidPersonnel($email);

            $listEmailConvert = array();
            if( $listEmail ){
                foreach ($listEmail as $key => $value) {
                    if( $value->id != Auth::user()->id ){
                        $listEmailConvert[] = $value->email;
                    }
                }
            }
            $content_mail = array(
                                'title'=>$request->title,
                                'content' =>  $infoConfigMail->mail_content,
                                'link'=> route('getExpenseEdit',['id'=>$id]),
                            );
            $title = $request->title;
            $month = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='m',$timeFormat='H:i:s',$time=false);
            $price = BatvHelper::formatPrice($request->value);
            $personnel_id = $request->personnel;
            \Mail::send('emails.expense', $content_mail, function($message) use ($listEmailConvert,$title,$personnel_id) {
                $infoConfigMail = EmailConfig::getInfoEmailConfig($type = 5);
                $message->from('nhansu@tohsoft.com', 'TOH');
                $title = "Chi phí ".$title."  vừa đc cập nhật bởi ".BatvHelper::getInfoUser($personnel_id);
                $message->to($listEmailConvert)->subject($title);
            });
            return back()->with(['flash_message_succ' => 'Cập nhật thành công']);
        }
    }

    public function getExpenseDel($id){
        $arr = [ 'status'=>0 ];
        Expense::updateExpense($arr,$id); 
        //Gửi Email báo cho admin khi xóa chi phí
        $infoConfigMail = EmailConfig::getInfoEmailConfig($type = 5);
        $email = explode(",",$infoConfigMail->mail_to);
        $listEmail = EmailConfig::getListEmailbyidPersonnel($email);

        $listEmailConvert = array();
        if( $listEmail ){
            foreach ($listEmail as $key => $value) {
                if( $value->id != Auth::user()->id ){
                    $listEmailConvert[] = $value->email;
                }
            }
        }
        $content_mail = array();
        $title = Expense::infoExpense($id)->title;
        $personnel_id = Auth::user()->id;
        \Mail::send('emails.empty', $content_mail, function($message) use ($listEmailConvert,$title,$personnel_id) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $title = "Chi phí ".$title." vừa đc xoá bởi ".BatvHelper::getInfoUser($personnel_id);
            $message->to($listEmailConvert)->subject($title);
        });
        return back()->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

    public function getExpenseGeneral(Request $request){
        $listFunds = Expense::getFundsList($request);
        $win = $arr = $news = $news_v2 = $news_v3  = $news_v4 = $expenseGeneral = $expenseMerge = $expenseMerge_v2 = $expenseMerge_v3 = $expenseMerge_v4 = array();
        if(  !isset($request->viewfast) || $request->viewfast == '' ){
            if( !empty(  $request->valid_from ) && !empty(  $request->valid_to ) ){
                $valid_from = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $valid_to = BatvHelper::formatDate($request->valid_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            }else{  
                $valid_from = date('Y')."-".date('m')."-"."01";

                $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
                $valid_to = date('Y')."-".date('m')."-".$numberDay;

            }
        }else{
            if( $request->viewfast == 0 ){
                $valid_from = date('Y')."-".date('m')."-"."01";

                $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
                $valid_to = date('Y')."-".date('m')."-".$numberDay;
            }elseif( $request->viewfast == 1 ){
                $date_from = date('Y')."-".date('m')."-"."01";
                $date_from = strtotime($date_from.'-1 month');
                $valid_from = date('Y-m-d', $date_from);

                $convert_to = explode("-",$valid_from);
                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert_to[1], $convert_to[0]);
                $valid_to = $convert_to[0]."-".$convert_to[1]."-".$numberDay;
            }elseif ( $request->viewfast == 2 ) {
                if(  (int)date('m') >=1 && (int)date('m')<=3 ){
                    $valid_from  = date('Y').'-01-01';

                    $numberDay = cal_days_in_month(CAL_GREGORIAN,3, date('Y'));
                    $valid_to  = date('Y').'-03-'.$numberDay;
                }elseif ( (int)date('m') >3 && (int)date('m')<=6 ) {
                    $valid_from  = date('Y').'-04-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,6, date('Y'));
                    $valid_to  = date('Y').'-06-'.$numberDay;
                }elseif ( (int)date('m') >6 && (int)date('m')<=9 ) {
                    $valid_from  = date('Y').'-07-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,9, date('Y'));
                    $valid_to  = date('Y').'-09-'.$numberDay;
                }else{
                    $valid_from  = date('Y').'-10-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y'));
                    $valid_to  = date('Y').'-12-'.$numberDay;
                }
            }elseif ( $request->viewfast == 3 ) {
                if(  (int)date('m') >=1 && (int)date('m')<=3 ){
                    $valid_from  = (date('Y')-1).'-09-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y')-1);
                    $valid_to  = ( date('Y')-1 ).'-12-'.$numberDay;
                }elseif ( (int)date('m') >3 && (int)date('m')<=6 ) {
                    $valid_from  = date('Y').'-01-01';

                    $numberDay = cal_days_in_month(CAL_GREGORIAN,3, date('Y'));
                    $valid_to  = date('Y').'-03-'.$numberDay;
                }elseif ( (int)date('m') >6 && (int)date('m')<=9 ) {
                    $valid_from  = date('Y').'-04-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,6, date('Y'));
                    $valid_to  = date('Y').'-06-'.$numberDay;
                }else{
                    $valid_from  = date('Y').'-07-01';
                    $numberDay = cal_days_in_month(CAL_GREGORIAN,9, date('Y'));
                    $valid_to  = date('Y').'-09-'.$numberDay;
                }
            }elseif ( $request->viewfast == 4 ) {
                $valid_from = date('Y')."-01-"."01";
                $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y'));
                $valid_to  = date('Y').'-12-'.$numberDay;
            }elseif ( $request->viewfast == 5 ) {
                $valid_from = (date('Y')-1)."-01-"."01";

                $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y')-1);
                $valid_to  = ( date('Y')-1 ).'-12-'.$numberDay;
            }

        }
        // echo $valid_from . '---'.$valid_to;die;
        $expense = Expense::getExpenseGeneral( $valid_from,$valid_to,$request );
        if( $expense ){
            foreach ($expense as $key => $value) {
                if( !isset($expenseMerge[$value->id]) ){
                    $expenseMerge[$value->id]['expense_id'] = $value->id;
                    $expenseMerge[$value->id]['title'] = $value->title;
                    $expenseMerge[$value->id]['value'] = $value->value;
                    $expenseMerge[$value->id]['value_usd'] = $value->value_usd;
                    $expenseMerge[$value->id]['percent'] = $value->percent;
                    $expenseMerge[$value->id]['description'] = $value->description;
                    $expenseMerge[$value->id]['param'] = BatvHelper::calculateMonth( $valid_from,$valid_to );
                    $expenseMerge[$value->id]['valid_from'] = $value->valid_from;
                    $expenseMerge[$value->id]['valid_to'] = $value->valid_to;
                    $expenseMerge[$value->id]['created_by'] = $value->created_by;
                    $expenseMerge[$value->id]['created_at'] = $value->created_at;
                    $expenseMerge[$value->id]['type'] = $value->type;
                    $expenseMerge[$value->id]['link_dropbox'] = $value->link_dropbox;
                    $expenseMerge[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge[$value->id]['percent_arr'][] = $value->percent;
                }else{
                    $expenseMerge[$value->id]['percent'] += $value->percent;
                    $expenseMerge[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge[$value->id]['percent_arr'][] = $value->percent;
                }
            }
        }
        $expense_v2 = Expense::getExpenseGeneral_v2( $valid_from,$valid_to,$request );
        if( $expense_v2 ){
            foreach ($expense_v2 as $key => $value) {
                $apply_to = BatvHelper::formatDate($value->valid_to,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                if( !isset($expenseMerge_v2[$value->id]) ){
                    $expenseMerge_v2[$value->id]['expense_id'] = $value->id;
                    $expenseMerge_v2[$value->id]['title'] = $value->title;
                    $expenseMerge_v2[$value->id]['value'] = $value->value;
                    $expenseMerge_v2[$value->id]['value_usd'] = $value->value_usd;
                    $expenseMerge_v2[$value->id]['percent'] = $value->percent;
                    $expenseMerge_v2[$value->id]['description'] = $value->description;
                    $expenseMerge_v2[$value->id]['param'] = BatvHelper::calculateMonth($valid_from,$apply_to);
                    $expenseMerge_v2[$value->id]['valid_from'] = $value->valid_from;
                    $expenseMerge_v2[$value->id]['valid_to'] = $value->valid_to;
                    $expenseMerge_v2[$value->id]['created_by'] = $value->created_by;
                    $expenseMerge_v2[$value->id]['created_at'] = $value->created_at;
                    $expenseMerge_v2[$value->id]['type'] = $value->type;
                    $expenseMerge_v2[$value->id]['link_dropbox'] = $value->link_dropbox;
                    $expenseMerge_v2[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v2[$value->id]['percent_arr'][] = $value->percent;
                }else{
                    $expenseMerge_v2[$value->id]['percent'] += $value->percent;
                    $expenseMerge_v2[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v2[$value->id]['percent_arr'][] = $value->percent;
                }
            }
        }

        $expense_v3 = Expense::getExpenseGeneral_v3( $valid_from,$valid_to,$request );
        if( $expense_v3 ){
            foreach ($expense_v3 as $key => $value) {
                $apply_from = BatvHelper::formatDate($value->valid_from,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                if( !isset($expenseMerge_v3[$value->id]) ){
                    $expenseMerge_v3[$value->id]['expense_id'] = $value->id;
                    $expenseMerge_v3[$value->id]['title'] = $value->title;
                    $expenseMerge_v3[$value->id]['value'] = $value->value;
                    $expenseMerge_v3[$value->id]['value_usd'] = $value->value_usd;
                    $expenseMerge_v3[$value->id]['percent'] = $value->percent;
                    $expenseMerge_v3[$value->id]['description'] = $value->description;
                    $expenseMerge_v3[$value->id]['param'] = BatvHelper::calculateMonth( $apply_from,$valid_to );
                    $expenseMerge_v3[$value->id]['valid_from'] = $value->valid_from;
                    $expenseMerge_v3[$value->id]['valid_to'] = $value->valid_to;
                    $expenseMerge_v3[$value->id]['created_by'] = $value->created_by;
                    $expenseMerge_v3[$value->id]['created_at'] = $value->created_at;
                    $expenseMerge_v3[$value->id]['type'] = $value->type;
                    $expenseMerge_v3[$value->id]['link_dropbox'] = $value->link_dropbox;
                    $expenseMerge_v3[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v3[$value->id]['percent_arr'][] = $value->percent;
                }else{
                    $expenseMerge_v3[$value->id]['percent'] += $value->percent;
                    $expenseMerge_v3[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v3[$value->id]['percent_arr'][] = $value->percent;
                }
            }
        }

        $expense_v4 = Expense::getExpenseGeneral_v4( $valid_from,$valid_to,$request );
        if( $expense_v4 ){
            foreach ($expense_v4 as $key => $value) {
                $apply_from = BatvHelper::formatDate($value->valid_from,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                $apply_to = BatvHelper::formatDate($value->valid_to,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                if( !isset($expenseMerge_v4[$value->id]) ){
                    $expenseMerge_v4[$value->id]['expense_id'] = $value->id;
                    $expenseMerge_v4[$value->id]['title'] = $value->title;
                    $expenseMerge_v4[$value->id]['value'] = $value->value;
                    $expenseMerge_v4[$value->id]['value_usd'] = $value->value_usd;
                    $expenseMerge_v4[$value->id]['percent'] = $value->percent;
                    $expenseMerge_v4[$value->id]['description'] = $value->description;
                    $expenseMerge_v4[$value->id]['param'] = BatvHelper::calculateMonth( $apply_from,$apply_to );
                    $expenseMerge_v4[$value->id]['valid_from'] = $value->valid_from;
                    $expenseMerge_v4[$value->id]['valid_to'] = $value->valid_to;
                    $expenseMerge_v4[$value->id]['created_by'] = $value->created_by;
                    $expenseMerge_v4[$value->id]['created_at'] = $value->created_at;
                    $expenseMerge_v4[$value->id]['type'] = $value->type;
                    $expenseMerge_v4[$value->id]['link_dropbox'] = $value->link_dropbox;
                    $expenseMerge_v4[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v4[$value->id]['percent_arr'][] = $value->percent;
                }else{
                    $expenseMerge_v4[$value->id]['percent'] += $value->percent;
                    $expenseMerge_v4[$value->id]['funds_title'][] = $value->funds_title;
                    $expenseMerge_v4[$value->id]['percent_arr'][] = $value->percent;
                }
            }
        }
        $expenseGeneral = array_merge( $expenseMerge,$expenseMerge_v2,$expenseMerge_v3,$expenseMerge_v4 );


        $listAllSalary = Expense::listAllSalary('','',$valid_from,$valid_to);
        
        // dd($listAllSalary);

        if( $listAllSalary ){
            foreach ($listAllSalary as $key => $value) {
                if( !isset($arr[$value->personnel_id]) ){
                    $arr[$value->personnel_id]['personnel_id'] = $value->personnel_id;
                    $arr[$value->personnel_id]['fullname'] = $value->fullname;
                    $arr[$value->personnel_id]['salary_overtime'] = $value->salary_overtime;
                    $arr[$value->personnel_id]['salary_trial_work'] = $value->salary_trial_work;
                    $arr[$value->personnel_id]['salary_official_work'] = $value->salary_official_work;
                    $arr[$value->personnel_id]['salary_trainee_work'] = $value->salary_trainee_work;
                    $arr[$value->personnel_id]['salary_trainee_parttime_work'] = $value->salary_trainee_parttime_work;
                    $arr[$value->personnel_id]['salary_parttime_work'] = $value->salary_parttime_work;
                    $arr[$value->personnel_id]['management_allowance'] = $value->management_allowance;
                    $arr[$value->personnel_id]['work_bonus'] = $value->work_bonus;
                    $arr[$value->personnel_id]['insurance'] = $value->insurance;
                    $arr[$value->personnel_id]['money_work_late'] = $value->money_work_late;
                    $arr[$value->personnel_id]['welfare_fund'] = $value->welfare_fund;
                    $arr[$value->personnel_id]['parking_fee_allowance'] = $value->parking_fee_allowance;
                    $arr[$value->personnel_id]['other_tax_allowance'] = $value->other_tax_allowance;
                    $arr[$value->personnel_id]['laptop_allowance'] = $value->laptop_allowance;
                    $arr[$value->personnel_id]['mulct_money_awol'] = $value->mulct_money_awol;
                    $arr[$value->personnel_id]['holiday_bonus'] = $value->holiday_bonus;
                    $arr[$value->personnel_id]['party_fee'] = $value->party_fee;
                    $arr[$value->personnel_id]['lunch_allowance'] = $value->lunch_allowance;
                    $arr[$value->personnel_id]['travel_allowance'] = $value->travel_allowance;
                    $arr[$value->personnel_id]['phone_allowance'] = $value->phone_allowance;
                    $arr[$value->personnel_id]['movement_allowance'] = $value->movement_allowance;
                    $arr[$value->personnel_id]['insurance_by_company'] = $value->insurance_by_company;
                }
            }
        }
            
        $arr = json_decode(json_encode($arr), FALSE);

        $others = Expense::listSalaryOther('','',$valid_from,$valid_to);
        $data = array();
        if( $others ){
            foreach ($others as $key => $value) {
                if( !isset($data[$value->personnel_id]->fullname) ){
                    $data[$value->personnel_id]['fullname'] = $value->fullname;
                    $data[$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                }else{
                    $data[$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                }

            }
        }
        // echo "<pre>";
        // print_r($data);die;
        // foreach ( $others['list'][$val->personnel_id]['income_value'] as $k=>$v ){
        //     if( !empty($v) )
        //         $total += $v;
        // }
        $getListManager = User::getListManager();
       
        $funds_id = $request->funds;
        $loan_capital = HistoryPayLoanCapital::leftJoin('loan_capital', 'loan_capital.id', '=', 'history_pay_loan_capital.loan_capital_id')
                                    ->leftJoin('personnel', 'personnel.id', '=', 'loan_capital.personnel_id')
                                    ->selectRaw('personnel.id as personnel_id,                                            
                                                sum(principal) as principal,                            
                                                sum(interest) as interest,                             
                                                sum(interest_incurred) as interest_incurred,                            
                                                sum(wanting_month_prev_money) as wanting_month_prev_money,                               
                                                sum(redundancy_month_prev_money) as redundancy_month_prev_money
                                                ')
                                    ->whereBetween('history_pay_loan_capital.repayment_period', [$valid_from, $valid_to])
                                    ->where('history_pay_loan_capital.status', 1)
                                    ->where('loan_capital.pay', 1)
                                    ->groupBy('history_pay_loan_capital.loan_capital_id')
                                    ->get();
       
        $arr_loan_capital = [];

        if ($loan_capital) {
            foreach ($loan_capital as  $val) {
                if (!isset($arr_loan_capital[$val->personnel_id])) {
                    $arr_loan_capital[$val->personnel_id] = $val->principal + $val->interest + $val->interest_incurred + $val->wanting_month_prev_money - $val->redundancy_month_prev_money;
                }
            }
        }

        return view('layouts.chiphi.tonghop.index',['arr_loan_capital' => $arr_loan_capital, 'data'=>$arr,'listFunds'=>$listFunds,'expenseGeneral'=>$expenseGeneral,'others'=>$data,'getListManager'=>$getListManager]);
    }

    public function setDefaultFundsAjax( Request $request ){
        if ($request->ajax()) {
            $id = $request->id;
            Expense::updateFundsSpecial(['type' => 0]);  
            Expense::updateFunds(['type' => 1],$id); 

            Expense::updateFundsPersonnel( ['funds_id'=>$id] );

            // AUTO SETTINg QUY
            // $list = Personnel::getAllPersonnel();
            // foreach ($list as $key => $value) {
            //     $data_funds = [
            //         'apply_from' => $value->date_in,
            //         'apply_to' =>   "2035-12-30",
            //         'funds_id' => $id,
            //         'personnel_id' => $value->id,
            //         'status' => 1,
            //         'type'   => 1
            //     ];
            //     Personnel::insertPersonnelFunds($data_funds);
            // }
            
            $res=array('Response'=>"Success","Message"=>"Bạn đã cập nhật thành công" );
        
            echo json_encode($res);
        }

    }

    public function getSignFundsList(){
        $data = SignFunds::where('status',1)->orderby('id','DESC')->paginate(BatvHelper::getPagePaging());
        $info = SignFunds::where('status',1)->get();
        $total_price = 0;
        if( $info ){
            foreach ($info as $key => $value) {
                $total_price += $value->value;
            }
        }
        return view('layouts.chiphi.kyquy.index',['data'=>$data,'total_price'=>$total_price]);
    }

    public function getSignFundsAdd(){
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        return view('layouts.chiphi.kyquy.add',['listPersonnel'=>$listPersonnel]);
    }

    public function postSignFundsAdd(Request $request){
        $rules = [
            'value' => 'required|numeric|min:1',
            'received_date' => 'required',
        ];
        $messages = [
            'received_date.required'=>'Ngày nhận không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương'
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = new SignFunds;
            $item->personnel_id =  $request->personnel_id;
            $item->value =  $request->value;
            $item->received_date =  BatvHelper::formatDate($request->received_date,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=true);
            $item->created_by =  Auth::user()->id;
            $item->created_at =  date('Y-m-d');
            $item->status =  1;
            $item->save();
            return redirect()->route('getSignFundsAdd')->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getSignFundsEdit($id){
        $data = SignFunds::find($id);
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        return view('layouts.chiphi.kyquy.edit',['listPersonnel'=>$listPersonnel,'data'=>$data]);
    }

    public function postSignFundsEdit(Request $request){
        $rules = [
            'value' => 'required|numeric|min:1',
            'received_date' => 'required',
        ];
        $messages = [
            'received_date.required'=>'Ngày nhận không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương'
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = SignFunds::find($request->id);
            $item->personnel_id =  $request->personnel_id;
            $item->value =  $request->value;
            $item->received_date =  BatvHelper::formatDate($request->received_date,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=true);
            $item->updated_by =  Auth::user()->id;
            $item->updated_at =  date('Y-m-d');
            $item->save();
            return redirect()->route('getSignFundsEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function signFundsDel($id){
        $item = SignFunds::find($id);
        $item->status = 0;
        $item->save();
        return redirect()->route('getSignFundsList')->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

    public function getWelfareFundsList(Request $request){
        $funds_id_default =  WelfareFunds::find(0);
        if( !empty(  $request->valid_from ) && !empty(  $request->valid_to ) ){
            $valid_from = BatvHelper::formatDate($request->valid_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $valid_to = BatvHelper::formatDate($request->valid_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        }else{  
            $valid_from = date('Y')."-".date('m')."-"."01";

            $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
            $valid_to = date('Y')."-".date('m')."-".$numberDay;

        }

        $data = WelfareFunds::whereBetween('apply_from', [$valid_from, $valid_to])->where('status',1)->whereNotIn('id', [0])->orderby('id','DESC')->paginate( BatvHelper::getPagePaging() );
        $month_check = (int)BatvHelper::formatDate($funds_id_default['valid_date'],'Y-m-d',$formatDate='m',$timeFormat='H:i:s',$time=false);
        $year_check = (int)BatvHelper::formatDate($funds_id_default['valid_date'],'Y-m-d',$formatDate='Y',$timeFormat='H:i:s',$time=false);
        $infoTotalPriceWelfareFunds = WelfareFunds::infoTotalPriceWelfareFunds($month_check,$year_check);
        $infoSpendMoneyWelfareFunds = WelfareFunds::infoSpendMoneyWelfareFunds();
        $infoSpendMoneyWelfareFundsbyMonth = WelfareFunds::infoSpendMoneyWelfareFunds($valid_from, $valid_to);
        return view('layouts.chiphi.quyphucloi.index',['data'=>$data,'funds_id_default'=>$funds_id_default,'infoTotalPriceWelfareFunds'=>$infoTotalPriceWelfareFunds,'infoSpendMoneyWelfareFunds'=>$infoSpendMoneyWelfareFunds,'infoSpendMoneyWelfareFundsbyMonth'=> $infoSpendMoneyWelfareFundsbyMonth]);
    }

    public function postWelfareFundsList(Request $request){
        $rules = [
            'value' => 'required|numeric|min:1',
            'valid_date' => 'required',
        ];
        $messages = [
            'valid_date.required'=>'Thời gian hiệu lực không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương'
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = WelfareFunds::find(0);
            $item->value =  $request->value;
            $item->valid_date =  BatvHelper::formatDate($request->valid_date,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=true);
            $item->updated_by =  Auth::user()->id;
            $item->updated_at =  date('Y-m-d');
            $item->save();
            return redirect()->route('getWelfareFundsList')->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function getWelfareFundsAdd(){
        return view('layouts.chiphi.quyphucloi.add');
    }

    public function postWelfareFundsAdd(Request $request){
        $rules = [
            'title' => 'required',
            'value' => 'required|numeric|min:1',
            'apply_from' => 'required',
        ];
        $messages = [
            'title.required'=>'Tiêu đề không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương',
            'value.required' => 'Thời gian hiệu lực không được để trống',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = new WelfareFunds;
            $item->title =  $request->title;
            $item->value =  $request->value;
            $item->apply_from =  BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $item->description =  $request->description;
            $item->created_by =  Auth::user()->id;
            $item->created_at =  date('Y-m-d');
            $item->status =  1;
            $item->save();
            return redirect()->route('getWelfareFundsAdd')->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getWelfareFundsEdit($id){
        $data = WelfareFunds::find($id);
        return view('layouts.chiphi.quyphucloi.edit',['data'=>$data]);
    }

    public function postWelfareFundsEdit(Request $request){
        $rules = [
            'title' => 'required',
            'value' => 'required|numeric|min:1',
            'apply_from' => 'required',
        ];
        $messages = [
            'title.required'=>'Tiêu đề không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương',
            'apply_from.required' => 'Thời gian hiệu lực không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = WelfareFunds::find($request->id);
            $item->title =  $request->title;
            $item->value =  $request->value;    
            $item->apply_from =  BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $item->description =  $request->description;
            $item->updated_by =  Auth::user()->id;
            $item->updated_at =  date('Y-m-d');
            $item->save();
            return redirect()->route('getWelfareFundsEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }
    
    public function welfareFundsDel($id){
        $item = WelfareFunds::find($id);
        $item->status = 0;
        $item->save();
        return redirect()->route('getWelfareFundsList')->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

    public function fundbyPersonnelAjax(){
        return json_encode(['data'=>'You can not permit access here']);
    }

    public function getCurencyAjax(Request $request){
        if ($request->ajax()) {
            $time = BatvHelper::formatDate($request->time,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $data = SettingCurrency::where([
                                        ['apply_from', '<=', $time],
                                        ['apply_to', '>=', $time],
                                        ['status', '>=', 1],
                                    ])->first();
            $result = array('usd'=>$data['value'],"value"=>($request->usd)*$data['value']);

            // echo "<pre>";
            // print_r($result);
            // echo "</pre>";die;
            echo json_encode($result);
            // $result['usd'] = $data['value'];
            // $result['value'] = ($request->usd)*$data['value'];
            // return $result;
        }
    }

    public function getByPersonnelAjax(Request $request){
        if ($request->ajax()) {
            $time = BatvHelper::formatDate($request->time,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $data = Expense::percentFundsbyPersonnel($time);
            $data = json_decode(json_encode($data), true);
            $data = array_count_values(array_column($data, 'funds_id'));
            $infoFunds = Expense::infoFundsAll();
            if( $infoFunds ){
                $total = array_sum($data);
                $result = '';
                foreach ($infoFunds as $key => $value) {
                    if( isset($data[$value->id]) ){
                        $percent = round( (($data[$value->id]/$total)*100),3 );
                    }else{
                        $percent = 0;
                    }
                    $result .= '<div class="checkbox bypersonnel_select">';
                    $result .= '<div class="col-sm-3"><input type="checkbox" id="'.$value->id.'" name="fund['.$value->id.']" value="'.$value->id.'" checked>'.$value->title.'</div>' ;
                    $result .= '<div class="col-sm-6"><input type="text" name="percent['.$value->id.']" value="'.$percent.'" required> %</div>';  

                    $result .='</div>';
                }
            }

            // echo $result;die;
            // echo "<pre>";
            // print_r($infoFunds);die;
            echo $result;
        }
    }

    public function getDefaultFundsAjax(Request $request){
        if ($request->ajax()) {
            $listFunds = Personnel::listFunds();
            $funds_id_default = Expense::getIdFundsDefault();
            $result = '';
            foreach($listFunds as $value){
                $percent = ($funds_id_default == $value->id)?100:0;
                $checked = ($funds_id_default == $value->id)?"checked":"";
                $result .= '<div class="checkbox bypersonnel_notselect">';
                $result .= '<div class="col-sm-3"><input type="checkbox" id="'.$value->id.'" name="fund['.$value->id.']" value="'.$value->id.'" '.$checked.'>'.$value->title.'</div>' ;
                $result .= '<div class="col-sm-6"><input type="text" name="percent['.$value->id.']" value="'.$percent.'" required> %</div>';  

                $result .='</div>';
            }
            echo $result;
        }
    }


    public function getSettingCurrency(){
        $data = SettingCurrency::where('status',1)->paginate(BatvHelper::getPagePaging());
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.chiphi.caidatngoaite.index',['data'=>$data]);
    }

    public function getSettingCurrencyAdd(){
        return view('layouts.chiphi.caidatngoaite.add');
    }

    public function postSettingCurrencyAdd(Request $request){
        // Phần Validation xử lý trường hợp chờm khoảng thời gián
        Validator::extend('check_setting_currency', function($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $apply_from = BatvHelper::formatDate($data['apply_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($data['apply_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $check = SettingCurrency::checkSettingCurrency( '',$apply_from,$apply_to );
            return $check>0?FALSE:TRUE;
        }); 
        $rules = [
            'title' => 'required',
            'value' => 'required|numeric|min:1',
            'apply_from' => 'required',
            'apply_to' => 'required',
            'apply_to' => 'validator_datetime_from_to:apply_from|check_setting_currency',
        ];
        $messages = [
            'title.required'=>'Tiêu đề không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương',
            'apply_from.required' => 'Thời gian hiệu lực không được để trống',
            'apply_to.required' => 'Thời gian hết hiệu lực không được để trống',
            'apply_to.validator_datetime_from_to'=>'Chọn thời gian hiệu lực không hợp lệ',
            'apply_to.check_setting_currency'=>'Thời gian chọn nằm trong khoảng thời gian trước đó',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = new SettingCurrency;
            $item->title =  $request->title;
            $item->value =  $request->value;
            $item->apply_from =  BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $item->apply_to =  BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $item->created_by =  Auth::user()->id;
            $item->created_at = date('Y-m-d H:i:s');
            $item->status =  1;
            $item->save();
            return redirect()->route('getSettingCurrencyAdd')->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getSettingCurrencyEdit($id){
        $data = SettingCurrency::find($id);
        return view('layouts.chiphi.caidatngoaite.edit',['data'=>$data]);
    }
    public function postSettingCurrencyEdit(Request $request){
        // Phần Validation xử lý trường hợp chờm khoảng thời gián
        Validator::extend('check_setting_currency', function($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $id = $parameters[0];
            $apply_from = BatvHelper::formatDate($data['apply_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($data['apply_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $check = SettingCurrency::checkSettingCurrency( $id,$apply_from,$apply_to );
            return $check>0?FALSE:TRUE;
        }); 
        $rules = [
            'title' => 'required',
            'value' => 'required|numeric|min:1',
            'apply_from' => 'required',
            'apply_to' => 'required',
            'apply_to' => 'validator_datetime_from_to:apply_from|check_setting_currency:'.$request->id
        ];
        $messages = [
            'title.required'=>'Tiêu đề không được để trống',
            'value.required' => 'Giá trị không được để trống',
            'value.numeric' => 'Giá trị phải là số',
            'value.min' => 'Giá trị phải là số nguyên dương',
            'apply_from.required' => 'Thời gian hiệu lực không được để trống',
            'apply_to.required' => 'Thời gian hết hiệu lực không được để trống',
            'apply_to.validator_datetime_from_to'=>'Chọn thời gian hiệu lực không hợp lệ',
            'apply_to.check_setting_currency'=>'Thời gian chọn nằm trong khoảng thời gian trước đó'
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = SettingCurrency::find($request->id);
            $item->title =  $request->title;
            $item->value =  $request->value;
            $item->apply_from =  BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $item->apply_to =  BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $item->updated_by =  Auth::user()->id;
            $item->updated_at =  date('Y-m-d H:i:s');
            $item->save();
            return redirect()->route('getSettingCurrencyEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Sửa thông tin thành công']);
        }
    }

    public function getSettingCurrencyDel($id){
        $item = SettingCurrency::find($id);
        $item->status = 0;
        $item->save();
        return back()->with(['flash_message_succ' =>'Xóa thông tin thành công']);
    }

}
