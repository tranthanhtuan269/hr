<?php

namespace App\Http\Controllers;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Personnel;
use App\Models\Expense;
use App\Models\Attendance;
use App\Models\History;
use App\Models\Roles;
use App\Models\User;
use App\Models\ConfigWeb;
use App\Models\PeriodSalary;
use App\Models\MaternityLeave;
use Validator;
use Auth;
use App\Mylibs\Myfunction;
use App\Helpers\BatvHelper;

class PersonnelController extends Controller
{
    public function getMaternityLeave($id){
        $data = MaternityLeave::where('personnel_id', $id)->get();
        return view('layouts.hoso.thoigiannghithaisan.detail', compact('data'));
    }

    public function addMaternityLeave(Request $request, $id){
        return view('layouts.hoso.thoigiannghithaisan.add');
    }

    public function postAddMaternityLeave(Request $request,$id){  

        // Phần Validation xử lý trường hợp chờm khoảng thời gián
        Validator::extend('validator_datetime_from_to', function($attribute, $value, $parameters, $validator) {
            $personnel_id = $parameters[0];
            $data = $validator->getData();
            $apply_from = BatvHelper::formatDate($data['apply_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($data['apply_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);

            $check = Personnel::checkMaternityLeave( '',$personnel_id,$apply_from,$apply_to );
            return $check>0?FALSE:TRUE;
        }); 

        // Phần Validation xử lý trường hợp thời gian from to
        Validator::extend('validator_from_to', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $value = BatvHelper::formatDate($value, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          return ( (BatvHelper::handlingTime($min_value) - BatvHelper::handlingTime($value))>0 )?FALSE:TRUE;
        }); 

        $rules = [
            'apply_from' => 'required',
            'apply_to'=>'required|validator_from_to:apply_from|validator_datetime_from_to:'.$id,
        ];
        $messages = [
            'apply_from.required'=>'Bạn chưa nhập ngày bắt đầu',
            'apply_to.required'=>'Bạn chưa nhập ngày đến',
            'apply_to.greater_than_field'=>'Nhập thời gian không chính xác.',
            'apply_to.validator_from_to'=>'Khoảng thời gian chọn không hợp lệ',
            'apply_to.validator_datetime_from_to'=>'Khoảng thời gian chọn không hợp lệ',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $apply_from = BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
         
            $arr = [
                    'apply_from' => $apply_from,
                    'apply_to' =>   $apply_to,
                    'personnel_id' => $id,
                    'join_insurance' => $request->join_insurance,
                ];

            MaternityLeave::insert($arr);
            return redirect()->route('getMaternityLeave',['id'=>$id])->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }

    }

    public function editMaternityLeave($personal_id,$id){
        $data = MaternityLeave::find($id);
        return view('layouts.hoso.thoigiannghithaisan.edit',['data'=>$data,'id'=>$personal_id ]);

    }

    public function postEditMaternityLeave(Request $request, $personal_id,$id){
        // Phần Validation xử lý trường hợp chờm khoảng thời gián
        Validator::extend('validator_datetime_from_to', function($attribute, $value, $parameters, $validator) {
            $personnel_id = $parameters[0];
            $id = $parameters[1];
            $data = $validator->getData();
            $apply_from = BatvHelper::formatDate($data['apply_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($data['apply_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);

            $check = Personnel::checkMaternityLeave( $id,$personnel_id,$apply_from,$apply_to );
            return $check>0?FALSE:TRUE;
        }); 

        // Phần Validation xử lý trường hợp thời gian from to
        Validator::extend('validator_from_to', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $value = BatvHelper::formatDate($value, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          return ( (BatvHelper::handlingTime($min_value) - BatvHelper::handlingTime($value))>0 )?FALSE:TRUE;
        }); 
        
        $rules = [
            'apply_from' => 'required',
            'apply_to'=>'required|validator_from_to:apply_from|validator_datetime_from_to:'.$personal_id.','.$id,
        ];
        $messages = [
            'apply_from.required'=>'Bạn chưa nhập ngày bắt đầu',
            'apply_to.required'=>'Bạn chưa nhập ngày đến',
            'apply_to.greater_than_field'=>'Nhập thời gian không chính xác.',
            'apply_to.validator_from_to'=>'Khoảng thời gian chọn không hợp lệ',
            'apply_to.validator_datetime_from_to'=>'Khoảng thời gian đã nằm trong thời gian quỹ khác',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $apply_from = BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            MaternityLeave::where('id',$id)->update([ 'apply_from' => $apply_from,'apply_to' =>   $apply_to, 'join_insurance' => $request->join_insurance,]);
            return redirect()->route('getMaternityLeave',['id'=>$personal_id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }

    }

    public function delMaternityLeave($personal_id,$id){
        MaternityLeave::find($id)->delete();
        return redirect()->route('getMaternityLeave',['id'=>$personal_id])->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }







    public function getIndex(Request $request){
        dd( $request->get('route_arr'));
        // or
        //echo $request->route_arr;
        return "hoso";
    }

    public function getFundsDetail($id){
        $data = Expense::detailFundsbyPersonnel($id);
        // echo "<pre>";
        // print_r($data);die;
        $name = History::getNamePersonnel($id);
        return view('layouts.hoso.quy.detail',['data'=>$data,'id'=>$id,'name'=>$name]);
    }

    public function getFundsAddPersonnel(Request $request, $id){
        $listFunds = Expense::getFundsList($request);
        return view('layouts.hoso.quy.add',['listFunds'=>$listFunds]);
    }

    public function postFundsAddPersonnel(Request $request,$id){  

        // Phần Validation xử lý trường hợp chờm khoảng thời gián
        Validator::extend('validator_datetime_from_to', function($attribute, $value, $parameters, $validator) {
            $personnel_id = $parameters[0];
            $data = $validator->getData();
            $apply_from = BatvHelper::formatDate($data['apply_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($data['apply_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $check = Personnel::checkFundsPersonnel( '',$personnel_id,$apply_from,$apply_to );
            return $check>0?FALSE:TRUE;
        }); 

        // Phần Validation xử lý trường hợp thời gian from to
        Validator::extend('validator_from_to', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $value = BatvHelper::formatDate($value, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          return ( (BatvHelper::handlingTime($min_value) - BatvHelper::handlingTime($value))>0 )?FALSE:TRUE;
        }); 

        $rules = [
            'apply_from' => 'required',
            'apply_to'=>'required|validator_from_to:apply_from|validator_datetime_from_to:'.$id,
        ];
        $messages = [
            'apply_from.required'=>'Bạn chưa nhập ngày bắt đầu',
            'apply_to.required'=>'Bạn chưa nhập ngày đến',
            'apply_to.greater_than_field'=>'Nhập thời gian không chính xác.',
            'apply_to.validator_from_to'=>'Khoảng thời gian chọn không hợp lệ',
            'apply_to.validator_datetime_from_to'=>'Khoảng thời gian đã nằm trong thời gian quỹ khác',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $apply_from = BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);

            $arr = [
                    'apply_from' => $apply_from,
                    'apply_to' =>   $apply_to,
                    'funds_id' => $request->funds_id,
                    'personnel_id' => $id,
                    'status' => 1,
                    'type'   => 0
                ];
            Personnel::insertPersonnelFunds($arr);
            return redirect()->route('getFundsDetail',['id'=>$id])->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }

    }

    public function getFundsEditPersonnel($personal_id,$id){
        $data = Expense::infoFundsbyPersonnel($id);
        $listFunds = Expense::getFundsList();
        return view('layouts.hoso.quy.edit',['data'=>$data,'id'=>$personal_id,'listFunds'=>$listFunds ]);

    }

    public function postFundsEditPersonnel(Request $request, $personal_id,$id){
        // Phần Validation xử lý trường hợp chờm khoảng thời gián
        Validator::extend('validator_datetime_from_to', function($attribute, $value, $parameters, $validator) {
            $personnel_id = $parameters[0];
            $id = $parameters[1];
            $data = $validator->getData();
            $apply_from = BatvHelper::formatDate($data['apply_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($data['apply_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);

            $check = Personnel::checkFundsPersonnel( $id,$personnel_id,$apply_from,$apply_to );
            return $check>0?FALSE:TRUE;
        }); 

        // Phần Validation xử lý trường hợp thời gian from to
        Validator::extend('validator_from_to', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $value = BatvHelper::formatDate($value, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          return ( (BatvHelper::handlingTime($min_value) - BatvHelper::handlingTime($value))>0 )?FALSE:TRUE;
        }); 
        
        $rules = [
            'apply_from' => 'required',
            'apply_to'=>'required|validator_from_to:apply_from|validator_datetime_from_to:'.$personal_id.','.$id,
        ];
        $messages = [
            'apply_from.required'=>'Bạn chưa nhập ngày bắt đầu',
            'apply_to.required'=>'Bạn chưa nhập ngày đến',
            'apply_to.greater_than_field'=>'Nhập thời gian không chính xác.',
            'apply_to.validator_from_to'=>'Khoảng thời gian chọn không hợp lệ',
            'apply_to.validator_datetime_from_to'=>'Khoảng thời gian đã nằm trong thời gian quỹ khác',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $apply_from = BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $apply_to = BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);

            $arr = [
                    'apply_from' => $apply_from,
                    'apply_to' =>   $apply_to,
                    'funds_id' => $request->funds_id,
                    'personnel_id' => $personal_id,
                    'type'   => 0
                ];
            Personnel::updatePersonnelFunds($arr,$id);
            return redirect()->route('getFundsDetail',['id'=>$personal_id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }

    }

    public function getFundsPersonnelDel($personal_id,$id){
            $arr = [
                    'status' => 0,
                ];
            Personnel::updatePersonnelFunds($arr,$id);
            return redirect()->route('getFundsDetail',['id'=>$personal_id])->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

    public function getHosoInfo(){
        $id = Auth::user()->id;
        $data = Personnel::getCurrentInfo($id);
        if( $data ){
            foreach ($data as $value) {
                $data->jobs = Personnel::infoJobsbyPersonnel($id);
            }
        }
        return view('layouts.hoso.owned_info',['data'=>$data]);
    }

    public function getHosoEditInfo(){
        $id = Auth::user()->id;
        $data = Personnel::getCurrentInfo($id);
        return view('layouts.hoso.owned_edit',['data'=>$data]);
    }

    public function postHosoEditInfo(Request $request){
        $id = Auth::user()->id;
        $rules = [
            'hotenDem' =>'required|min:2|max:50',
            'inputName' => 'required|min:2|max:50',
            'gender' => 'required',
            'inputBirthday' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'indentity_card_date' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'indentity_card_address'=> 'required|max:500',
            'inputPhone'=> 'required|max:15',
            'indentity_card_id'=> 'required|max:15',
            'home_town'=> 'required',
            'address'=> 'required',
            'fileImage' => 'image|max:5000'
        ];
        $messages = [
            'hotenDem.required'=>'Bạn chưa nhập họ và tên đệm',
            'hotenDem.min' =>'Họ và tên đệm phải có từ 2 ký tự trở lên',
            'hotenDem.max' =>'Họ và tên đệm không được quá 50 ký tự',
            'inputName.required'=>'Bạn chưa nhập tên',
            'inputName.min' => 'Tên phải có từ 2 ký tự trở lên',
            'inputName.max' =>'Tên không được quá 50 ký tự',
            'gender.required'=>'Bạn chưa nhập giới tính',
            'inputBirthday.required'=>'Bạn chưa nhập ngày sinh',
            'inputBirthday.date_format'=>'Định dạng ngày sinh phải là dd/mm/yyyy',
            'inputBirthday.regex'=> 'Định dạng ngày sinh phải là dd/mm/yyyy',
            'inputPhone.required'=>'Bạn chưa nhập số điện thoại',
            'inputPhone.numeric'=>'Số điện thoại phải là số',
            'inputPhone.max' =>'Số điện thoại không được quá 15 số',
            'indentity_card_id.required' => 'Bạn chưa nhập số chứng minh thư nhân dân',
            'indentity_card_id.numeric'=>'Số chứng minh thư nhân dân phải là số',
            'indentity_card_id.max' =>'Số chứng minh thư nhân dân không được quá 15 số',
            'home_town.required' => 'Bạn chưa nhập địa chỉ',
            'address.required' => 'Bạn chưa nhập địa chỉ hiện tại',
            'fileImage.image'=>'File ảnh chưa đúng định dạng',
            'fileImage.max'=>'Kích thước file phải nhỏ hơn 5 mb'

        ];
        //echo $request->file('fileImage');die;
        $validator = Validator::make($request->all(),$rules,$messages);
        if($request->hasFile('fileImage')){
            if($request->file('fileImage')->isValid()){
                // echo 1;die;
                if ($validator->fails()) {
                    // Validator fail
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{
                    $filename = time().'.'.$request->file('fileImage')->getClientOriginalName();
                    $destinationPath = 'uploads/personnels/';
                     // Nếu move hay move_uploaded_file gọi trước thì hàm getimagesize sẽ báo lỗi đường dẫn do đường dẫn file đã thay đổi
                    $request->file('fileImage')->move($destinationPath,$filename);
                    //Myfunction::resizeImage($request->file('fileImage')->getPathName(), '200', '200',$destinationPath,$filename );
                    $date_field = DateTime::createFromFormat('d/m/yy', $request->inputBirthday);      
                     $arr = [
                        'first_name' => $request->hotenDem,
                        'last_name' =>  $request->inputName,
                        'fullname' => $request->hotenDem . ' ' .$request->inputName,
                        'gender'=>  $request->gender,
                        'birthday' =>   $date_field->format('Y-m-d'),
                        'phone_number'=>   $request->inputPhone,
                        'indentity_card_id'=>  $request->indentity_card_id,
                        'indentity_card_date' =>    DateTime::createFromFormat('d/m/yy', $request->indentity_card_date)->format('Y-m-d'),
                        'indentity_card_address' =>  $request->indentity_card_address, 
                        'home_town' =>  $request->home_town,
                        'address' =>  $request->address, 
                        'user_id' => Auth::user()->id,
                        'avatar' =>  $filename,
                        'updated_at'=>date('Y-m-d'),
                        'updated_by'=>Auth::user()->id,

                    ];

                    Personnel::updateOwnedInfo($arr,Auth::user()->id);
                    return redirect()->route('getHosoInfo')->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
                }
            }else{
                return redirect()->back()->with(['flash_message_err'=>'Có lỗi xảy ra trong quá trình upload vui lòng thử lại']);
            }
        }else{
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                // echo 2;die;
                $date_field = DateTime::createFromFormat('d/m/yy', $request->inputBirthday); 
                $arr = [
                        'first_name' => $request->hotenDem,
                        'last_name' =>  $request->inputName,
                        'fullname' => $request->hotenDem . ' ' .$request->inputName,
                        'gender'=>  $request->gender,
                        'birthday' =>   $date_field->format('Y-m-d'),
                        'phone_number'=>   $request->inputPhone,
                        'indentity_card_id'=>  $request->indentity_card_id,
                        'indentity_card_date' =>    DateTime::createFromFormat('d/m/yy', $request->indentity_card_date)->format('Y-m-d'),
                        'indentity_card_address' =>  $request->indentity_card_address, 
                        'home_town' =>  $request->home_town,
                        'address' =>  $request->address, 
                        'user_id' => Auth::user()->id,
                        'updated_at'=>date('Y-m-d'),
                        'updated_by'=>Auth::user()->id,
                    ];
                Personnel::updateOwnedInfo($arr,Auth::user()->id);
                return redirect()->route('getHosoInfo')->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
            }
        }
       
        
    }
    public function getHosoCongtac(){
        $data = Personnel::getCurrentWork( Auth::user()->id );
        $ratio = Personnel::listRatio( Auth::user()->id );
        return view('layouts.hoso.owned_work',['data'=>$data,'ratio'=>$ratio]);
        
        
    }
    public function getPersonnelList(Request $request){
        $myfunc =  new Myfunction();
        $depart = Personnel::listDepartment();
        $select = 0;
        if ($request->input('selectDepart') != '') {
          $select = $request->input('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        if( $request->selectDepart !=0 ){
            $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
            if( count($tmp)==0 ){
                $ids = array($request->selectDepart);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
            $data = Personnel::listPersonnel($request,$ids);
        }else{

            $data = Personnel::listPersonnel($request);
        }

        // echo "<pre>";
        // print_r($data);die;
        foreach ($data as $key => $value) {
            $data[$key]->jobs = Personnel::infoJobsbyPersonnel($value->id);
        }

        $depart = Personnel::listDepartment();
        $myfunc =  new Myfunction();
        $select = 0;
        if ($request->input('selectDepart') !='') {
            $select = $request->input('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.hoso.index',['data'=>$data,'department'=>$select_depart]);
    }

    public static function getPersonnelEdit($id){
        $data = Personnel::getInfo($id);
        $depart = Personnel::listDepartment();
        $myfunc =  new Myfunction();
        $selectDepart = 0;
        if (!empty($data->department_id)) {
            $selectDepart = $data->department_id;
        }

        $selectjobs2 = 0;
        if (!empty(old('selectJobs'))) {
            $selectjobs2 = old('selectJobs');
        }

        $listJobs = Personnel::listJobs();
        $listJobsbyPersonnel = Personnel::listJobsbyPersonnel($id);

        $listContracts = Personnel::listContracts($id);
        $listContractsbyPersonnel = array();
        foreach ($listContracts as $key => $value) {
            $listContractsbyPersonnel[$value->contract_id] = array( 'apply_from'=>$value->apply_from,'apply_to'=> $value->apply_to);
        }
        
        $tmp=array();
        foreach ($listJobsbyPersonnel as $key => $value) {
            $tmp[] = $value->id;
        }

        foreach ($listJobs as $key => $value) {
            if( in_array($value->id, $tmp) ){
                $listJobs[$key]->selected = 1;
            }
        }

        $select_depart = $myfunc->callProcessSelect($depart,0,'',$selectDepart);
        //Thông tin các chu kỳ xét tăng lương
        $period = PeriodSalary::all();
        return view('layouts.hoso.edit',['data'=>$data,'department'=>$select_depart,'listJobs'=>$listJobs,'listContractsbyPersonnel'=>$listContractsbyPersonnel,'period'=>$period]);
    }

    public static function postPersonnelEdit(Request $request, $id){
        // Phần Validation xử lý trường hợp Ngày vào công ty không được lớn hơn ngày nghỉ 
        Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $value = BatvHelper::formatDate($value, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          if( (BatvHelper::handlingTime($min_value) - BatvHelper::handlingTime($value))>0 ){
            return FALSE;
          }else{
            return TRUE;
          }
        });   

        Validator::extend('greater_than_field_special', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $dateCurrent = BatvHelper::formatDate(date('d/m/Y'), 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          if( (BatvHelper::handlingTime($min_value) - BatvHelper::handlingTime($dateCurrent))>0 ){
            return FALSE;
          }else{
            return TRUE;
          }

        }); 

        $rules = [
            'hotenDem' =>'required|min:2|max:50',
            'inputName' => 'required|min:2|max:50',
            'gender' => 'required',
            'inputBirthday' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field_special:inputBirthday',
            // 'inputBirthday' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'inputPhone'=> 'required|numeric',
            'indentity_card_id'=> 'required|numeric',
            'indentity_card_date' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'indentity_card_address'=> 'required|max:500',
            'job'=> 'required',
            'date_in'=> 'required',
            'selectContract'=> 'required',
            'home_town'=> 'required',
            'address'=> 'required',
            'fileImage' => 'image|max:5000',
            'date_out' => 'greater_than_field:date_in'
        ];
        $messages = [
            'hotenDem.required'=>'Bạn chưa nhập họ và tên đệm',
            'hotenDem.min' =>'Họ và tên đệm phải có từ 2 ký tự trở lên',
            'hotenDem.max' =>'Họ và tên đệm không được quá 50 ký tự',
            'inputName.required'=>'Bạn chưa nhập tên',
            'inputName.min' => 'Tên phải có từ 2 ký tự trở lên',
            'inputName.max' =>'Tên không được quá 50 ký tự',
            'gender.required'=>'Bạn chưa nhập giới tính',
            'inputBirthday.required'=>'Bạn chưa nhập ngày sinh',
            'inputBirthday.date_format'=>'Định dạng ngày sinh phải là dd/mm/yyyy',
            'inputBirthday.greater_than_field_special'=>'Ngày sinh không được lớn hơn thời gian hiện tại',
            'inputBirthday.regex'=> 'Định dạng ngày sinh phải là dd/mm/yyyy',
            'inputPhone.required'=>'Bạn chưa nhập số điện thoại',
            'inputPhone.numeric'=>'Số điện thoại phải là số',
            'indentity_card_id.required' => 'Bạn chưa nhập số chứng minh thư nhân dân',
            'indentity_card_id.numeric'=>'Số chứng minh thư nhân dân phải là số',
            'job.required' => 'Bạn chưa chọn chức danh',
            'selectContract.required' => 'Bạn chưa chọn loại hợp đồng',
            'date_in.required' => 'Ngày vào công ty không được để trống',
            'home_town.required' => 'Bạn chưa nhập địa chỉ',
            'address.required' => 'Bạn chưa nhập địa chỉ hiện tại',
            'fileImage.image'=>'File ảnh chưa đúng định dạng',
            'fileImage.max'=>'Kích thước file phải nhỏ hơn 5 mb',
            'date_out.greater_than_field'=>'Ngày nghỉ phải lớn hơn ngày vào công ty'

        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        if( $request->date_out !='' && $request->date_out !='0000-00-00' ){
             $date_out = DateTime::createFromFormat('d/m/yy', $request->date_out);
             $date_out = $date_out->format('Y-m-d');
             Attendance::where('personnel_id', $id)->whereDate('created_at', '>=', $date_out . ' 00:00:00')->delete();
        }else{
             $date_out = NULL;
        }
        $birthday = DateTime::createFromFormat('d/m/yy', $request->inputBirthday);    
        $date_in = DateTime::createFromFormat('d/m/yy', $request->date_in);

        $score_tn = ConfigWeb::where('key', 'score_tn')->value('value');
        $score_tsp_tld = ConfigWeb::where('key', 'score_tsp_tld')->value('value');
        $score_tp_ptt = ConfigWeb::where('key', 'score_tp_ptt')->value('value');
        $score_ttt = ConfigWeb::where('key', 'score_ttt')->value('value');

        $datetime1 = new \DateTime(BatvHelper::formatDate($request->date_in,'d/m/Y', 'Y-m-d', 'H:i:s', false));
        $datetime2 = new \DateTime(date('Y-m-d'));
        $interval = $datetime2->diff($datetime1);
        $month_work =  (($interval->format('%y') * 12) + $interval->format('%m'));
        $score_position = round(($month_work/12)*$score_tn, 2);

        $score_role = 0;
        
        if($request->hasFile('fileImage')){
            if($request->file('fileImage')->isValid()){
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    $filename = time().'.'.$request->file('fileImage')->getClientOriginalName();
                    $destinationPath = 'uploads/personnels/';
                    $request->file('fileImage')->move($destinationPath,$filename);
                    $arr = [
                        'time_attendance_machine' => $request->time_attendance_machine,
                        'first_name' => $request->hotenDem,
                        'last_name' =>  $request->inputName,
                        'fullname' => trim($request->hotenDem) . ' ' .trim($request->inputName),
                        'gender'=>  $request->gender,
                        'birthday' =>   $birthday->format('Y-m-d'),
                        'date_in' =>   $date_in->format('Y-m-d'),
                        'date_out' =>   $date_out,
                        'number_dependent_person'=>$request->number_dependent_person,
                        'phone_number'=>   $request->inputPhone,
                        'salary_frequency'=>$request->salary_frequency,
                        'insurrance'=>   $request->insurrance,
                        'department_id' => $request->selectDepart,
                        'indentity_card_id'=>  $request->indentity_card_id,
                        'indentity_card_date' =>    DateTime::createFromFormat('d/m/yy', $request->indentity_card_date)->format('Y-m-d'),
                        'indentity_card_address' =>  $request->indentity_card_address, 
                        'home_town' =>  $request->home_town,
                        'address' =>  $request->address, 
                        'avatar' =>  $filename,
                        'seniority' =>  $request->seniority, 
                    ];
                    Personnel::updateInfo($arr,$id); 
                    $data = array();
                    foreach ($request->job as $key => $value) {
                        if ($value == 34) {
                            $score_role += $score_tsp_tld;
                        } elseif ($value == 26 || $value == 31) {
                            $score_role += $score_tp_ptt;
                        } elseif ($value == 25) {
                            $score_role += $score_ttt;
                        }
                        
                        $data[] =  array('job_title_id'=>$value, 'personnel_id'=>$id);
                    }
                    Personnel::deletePersonnelTitle($id);
                    Personnel::insertPersonnelTitle($data);

                    // $funds = array();
                    // foreach ($request->fund as $key => $value) {
                    //     $funds[] =  array('funds_id'=>$value,'personnel_id'=>$id, 'percent'=>$request->percent[$value] );
                    // }
                    // Personnel::deletePersonnelFunds($id);
                    // Personnel::insertPersonnelFunds($funds);

                    $data_cp = array();
                    foreach ($request->selectContract as $key => $value) {
                        $data_cp[] =  array('contract_id'=>$request->selectContract[$value],'personnel_id'=>$id, 'apply_from'=>BatvHelper::formatDate($request->apply_from_contract[$value], 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false),'apply_to'=>BatvHelper::formatDate($request->apply_to_contract[$value], 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false) );
                    }
                    Personnel::deleteContractPersonnel($id);
                    Personnel::insertContractPersonnel($data_cp);

                    //Tự động Insert thông tin quỹ được set mặc định
                    $check_default = Expense::checkFundsListPersonnel($id,$type=0);
                    $check_edit = Expense::checkFundsListPersonnel($id,$type=1);
                    if( $check_default == 0  && $check_edit == 0){
                        $funds_id = Expense::getIdFundsDefault();
                        $data_funds = [
                                'apply_from' => $date_in->format('Y-m-d'),
                                'apply_to' =>   "2099-12-31",
                                'funds_id' => $funds_id,
                                'personnel_id' => $id,
                                'status' => 1,
                                'type'   => 1
                            ];
                        Personnel::insertPersonnelFunds($data_funds);
                    }
                }
            }else{
                return redirect()->back()->with(['flash_message_err'=>'Có lỗi xảy ra trong quá trình upload vui lòng thử lại']);
            }
            }else{
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    $arr = [
                            'time_attendance_machine' => $request->time_attendance_machine,
                            'first_name' => $request->hotenDem,
                            'last_name' =>  $request->inputName,
                            'fullname' => $request->hotenDem . ' ' .$request->inputName,
                            'gender'=>  $request->gender,
                            'birthday' =>   $birthday->format('Y-m-d'),
                            'date_in' =>   $date_in->format('Y-m-d'),
                            'date_out' =>   $date_out,
                            'number_dependent_person'=>$request->number_dependent_person,
                            'phone_number'=>   $request->inputPhone,
                            'salary_frequency'=>$request->salary_frequency,
                            'insurrance'=>   $request->insurrance,
                            'department_id' => $request->selectDepart,
                            'indentity_card_id'=>  $request->indentity_card_id,
                            'indentity_card_date' =>    DateTime::createFromFormat('d/m/yy', $request->indentity_card_date)->format('Y-m-d'),
                            'indentity_card_address' =>  $request->indentity_card_address, 
                            'home_town' =>  $request->home_town,
                            'address' =>  $request->address, 
                            'seniority' =>  $request->seniority, 
                        ];
                    Personnel::updateInfo($arr,$id); 
                    $data = array();
                    foreach ($request->job as $key => $value) {

                        if ($value == 34) {
                            $score_role += $score_tsp_tld;
                        } elseif ($value == 26 || $value == 31) {
                            $score_role += $score_tp_ptt;
                        } elseif ($value == 25) {
                            $score_role += $score_ttt;
                        }

                        $data[] =  array('job_title_id'=>$value, 'personnel_id'=>$id);
                    }
                    Personnel::deletePersonnelTitle($id);
                    Personnel::insertPersonnelTitle($data);

                    // $funds = array();
                    // foreach ($request->fund as $key => $value) {
                    //     $funds[] =  array('funds_id'=>$value,'personnel_id'=>$id, 'percent'=>$request->percent[$value] );
                    // }
                    // Personnel::deletePersonnelFunds($id);
                    // Personnel::insertPersonnelFunds($funds);

                    $data_cp = array();
                    foreach ($request->selectContract as $key => $value) {
                        $data_cp[] =  array('contract_id'=>$request->selectContract[$value],'personnel_id'=>$id, 'apply_from'=>BatvHelper::formatDate($request->apply_from_contract[$value], 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false),'apply_to'=>BatvHelper::formatDate($request->apply_to_contract[$value], 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false) );
                    }
                    Personnel::deleteContractPersonnel($id);
                    Personnel::insertContractPersonnel($data_cp);

                    //Tự động Insert thông tin quỹ được set mặc định
                    $check_default = Expense::checkFundsListPersonnel($id,$type=0);
                    $check_edit = Expense::checkFundsListPersonnel($id,$type=1);
                    if( $check_default == 0 && $check_edit == 0 ){
                        $funds_id = Expense::getIdFundsDefault();
                        $data_funds = [
                                'apply_from' => $date_in->format('Y-m-d'),
                                'apply_to' =>   "2099-12-31",
                                'funds_id' => $funds_id,
                                'personnel_id' => $id,
                                'status' => 1,
                                'type'   => 1
                            ];
                        Personnel::insertPersonnelFunds($data_funds);
                    }
                }
            }

            $detail = Personnel::find($id);

            Personnel::where('id', $id)->update([
                'score_seniority' => $score_position,
                'score_position' => $score_role,
                'score_faith' =>  $score_position + $score_role + $detail->score_faith - ($detail->score_seniority + $detail->score_position ),
            ]);

            return  redirect()->route('getPersonnelEdit',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
    }
    public function getPersonnelDel($id){
        $arr = [ 'status'=>0 ];
        $data = Personnel::updatePersonnel($arr,$id);
        User::updateUser($arr,$id);
        if($data){
            return back()->with(['flash_message_succ' =>'Xóa hồ sơ thành công']);
        }else{
            return back()->with(['flash_message_err' =>'Xảy ra lỗi trong quá trình xóa']);
        }
    }

    public function getPersonnelAssign($id){
         $name = Personnel::getPersonnelName($id);
         $data_roles = Roles::select('id','roles_name')->get();
         return view('layouts.hoso.assign',['p_name'=>$name,'data_roles'=>$data_roles]);
    }

    public function postPersonnelAssign(Request $request , $id){
        $this->validate($request,[
            'inputHoten' =>'required|min:2',
            'inputEmail'=>'required|email|unique:users,email',
            'inputPassword'=>'required|min:6|max:32|confirmed',
            'inputPassword_confirmation'=>'required|min:6|max:32',
            'selectRole'=>'required'
        ],[
            'inputHoten.required'=>'Bạn chưa nhập tên người dùng',
            'inputEmail.required'=>'Bạn chưa nhập email',
            'inputPassword.required'=>'Bạn chưa nhập password',
            'inputPassword_confirmation.required'=>'Bạn chưa nhập lại password',
            'inputPassword.min'=> 'Mật khẩu phải có từ 6 ký tự trở lên',
            'inputPassword.max'=>'Mật khẩu phải nhỏ hơn 32 ký tự',
            'inputPassword_confirmation.min'=> 'Mật khẩu phải có từ 6 ký tự trở lên',
            'inputPassword_confirmation.max'=>'Mật khẩu phải nhỏ hơn 32 ký tự',
            'inputPassword.confirmed'=>'Mật khẩu nhập lại chưa đúng',
            'inputEmail.email'=>'Bạn chưa nhập đúng định dạng email',
            'inputEmail.unique'=>'Email đã tồn tại',
            'selectRole.required'=>'Bạn chưa chọn Role',

        ]);  

        $user = new User();
        $user->name = $request->inputHoten; 
        $user->email = $request->inputEmail; 
        $user->password = bcrypt(trim($request->inputPassword)); 
        $user->role_id = $request->selectRole; 
        $user->save();
        $lastInsertedId= $user->id;
        if ($lastInsertedId) {
            Personnel::updateInfo(
                [
                    'user_id'=>$lastInsertedId,
                    'email' => $user->email,

                ],$id);
            return redirect()->route('getPersonnelList')->with(['flash_message_succ' => 'Add User và gán thành công']);
        }else{
            return redirect()->route('getPersonnelList')->with(['flash_message_succ' => 'Có lỗi xảy ra trong quá trình gán tài khoản']);
        }
        
    }
    public function getDepartment( Request $request){
        $depart = Personnel::getDepartment( $request);
        // echo "<pre>";
        // print_r($depart);
        // echo "</pre>";die;
        return view('layouts.phongban.index',['data'=>$depart]);
    }

    public function addDepartment(){
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        // echo "<pre>";
        // print_r($listPersonnel);die;
        $depart = Attendance::listDepartment();
        $myfunc =  new Myfunction();
        $select = 0;
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.phongban.add',['department'=>$select_depart,'listPersonnel'=>$listPersonnel]);
    }

    public function postDepartment( Request $request){
        $rules = [
            'title' =>'required',
        ];
        $messages = [
            'title.required' => 'Nội dung không được để trống',
            ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title' =>  $request->title,
                'parent_id' =>  $request->parent_id,
                'manager_id' =>  $request->manager_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d'),
            ];
            Personnel::insertDepartment($arr); 

            $id =  \DB::getPdo()->lastInsertId();
            if( $request->personnel_attendance ){
                foreach ($request->personnel_attendance as $k => $v) {
                    $data[] =  array('departments_id'=>$id, 'manage_id'=>$v );

                }  
                Personnel::insertDepartmentsAttendance($data); 
            }
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getDepartmentEdit($id){
        $arr = Personnel::getInfoDepartment($id);
        $listPersonnel = Personnel::getAllPersonnel();
        $depart = Attendance::listDepartment();
        $myfunc =  new Myfunction();
        $data = array();
        if( $arr ){
            foreach ($arr as $key => $value) {
                if( !isset($data[$value->id]) ){
                    $data[$value->id] = [
                                        'id'=>$value->id,
                                        'title'=>$value->title,
                                        'manager_id'=>$value->manager_id,
                                        'parent_id'=>$value->parent_id,
                                        'manage_id_attendance'=>[ 0=>$value->manage_id_attendance,]
                                    ];
                }else{
                    $data[$value->id]['manage_id_attendance'][] = $value->manage_id_attendance;
                }
            }
        }
        // echo "<pre>";
        // print_r($data);die;
        $select = $data[$id]['parent_id'];
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.phongban.edit',['data'=>$data,'department'=>$select_depart,'listPersonnel'=>$listPersonnel,'id'=>$id]);
    }

    public function postDepartmentEdit(Request $request){
        $rules = [
            'title' =>'required',
        ];
        $messages = [
            'title.required' => 'Nội dung không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title' =>  $request->title,
                'parent_id' =>  $request->parent_id,
                'manager_id' =>  $request->manager_id,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d'),
            ];
            Personnel::updateDepartment($arr,$request->id);  
            if( $request->personnel_attendance ){
                Personnel::deleteDepartmentsAttendance($request->id);
                foreach ($request->personnel_attendance as $k => $v) {
                    $data[] =  array('departments_id'=>$request->id, 'manage_id'=>$v );

                }   
                Personnel::insertDepartmentsAttendance($data); 
            }
            return redirect()->route('getDepartmentEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function getDepartmentDel($id){
        $checkPersonnelDepartment = Personnel::checkPersonnelDepartment($id);
        $checkChildrenDepartment = Personnel::checkChildrenDepartment($id);
        if( count($checkPersonnelDepartment)==0 && count($checkChildrenDepartment)==0 ){
            $data = Personnel::deleteDepartment($id);
            return back()->with(['flash_message_succ' =>'Xóa phòng ban thành công']);
        }else{
            return back()->with(['flash_message_err' =>'Phòng ban đã có nhân viên hoặc Phòng ban đó còn chứa phòng ban khác']);
        }
    }

    public function getJobTitles( Request $request){
        $depart = Personnel::getJobTitles( $request);
        // echo "<pre>";
        // print_r($depart);die;
        return view('layouts.chucdanh.index',['data'=>$depart]);
    }

    public function addJobTitles(){
        return view('layouts.chucdanh.add');
    }

    public function postJobTitles( Request $request){
        $rules = [
            'title' =>'required',
        ];
        $messages = [
            'title.required' => 'Nội dung không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title' =>  $request->title,
                'status' =>  1,
            ];
            Personnel::insertJobTitles($arr);  
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getJobTitlesEdit($id){
        $data = Personnel::getInfoJobTitles($id);
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.chucdanh.edit',['data'=>$data]);
    }

    public function postJobTitlesEdit(Request $request){
        $rules = [
            'title' =>'required',
        ];
        $messages = [
            'title.required' => 'Nội dung không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title' =>  $request->title,
            ];
            Personnel::updateJobTitles($arr,$request->id);  
            return redirect()->route('getJobTitlesEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function getJobTitlesDel($id){
         $arr = [
            'status' =>  0,
        ];
        Personnel::updateJobTitles($arr,$id); 
        if($arr){
            return back()->with(['flash_message_succ' =>'Xóa chức danh thành công']);
        }else{
            return back()->with(['flash_message_err' =>'Xảy ra lỗi trong quá trình xóa']);
        }
    }

    public function getContract( Request $request){
        $contract = Personnel::getContract( $request);
        // echo "<pre>";
        // print_r($depart);die;
        return view('layouts.hopdong.index',['data'=>$contract]);
    }

    public function addContract(){
        return view('layouts.hopdong.add');
    }

    public function postContract( Request $request){
        $rules = [
            'title' =>'required',
            'duration'=>'required|numeric',
        ];
        $messages = [
            'title.required' => 'Tên hợp đồng không được để trống',
            'duration.required' => 'Thời gian hợp đồng không được để trống',
            'duration.numeric' => 'Thời gian hợp đồng phải là số',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title' =>  $request->title,
                'description' =>  $request->description,
                'duration' =>  $request->duration,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d'),
                'status' =>  1,
            ];
            Personnel::insertContract($arr);  
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getContractEdit($id){
        $data = Personnel::getInfoContract($id);
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.hopdong.edit',['data'=>$data]);
    }

    public function postContractEdit(Request $request){
        $rules = [
            'title' =>'required',
            'duration'=>'required|numeric',
        ];
        $messages = [
            'title.required' => 'Tên hợp đồng không được để trống',
            'duration.required' => 'Thời gian hợp đồng không được để trống',
            'duration.numeric' => 'Thời gian hợp đồng phải là số',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title' =>  $request->title,
                'description' =>  $request->description,
                'duration' =>  $request->duration,
                'updated_by' => Auth::user()->id,
                'created_at' => date('Y-m-d'),
            ];
            Personnel::updateContract($arr,$request->id);  
            return redirect()->route('getContractEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function getContractDel($id){
        $arr = [
            'status' =>  0,
        ];
        Personnel::updateContract($arr,$id); 
        if($arr){
            return back()->with(['flash_message_succ' =>'Xóa hợp đồng thành công']);
        }else{
             return back()->with(['flash_message_err' =>'Xảy ra lỗi trong quá trình xóa']);
        }
    }

    public function getFilePersonnelByManger(Request $request){
        $department_id = \DB::table('departments')->where('manager_id', '=', Auth::user()->id)->value('id');
        // dd($department_id);
        $myfunc =  new Myfunction();
        $depart = Personnel::listDepartment();
        $select = 0;

        $tmp[$department_id] = $myfunc->categoryChild($department_id, 'departments');
        if( count($tmp)==0 ){
            $ids = [$department_id];
        }else{
            $ids =  BatvHelper::array_keys_multi($tmp);
        }

        if ($request->type == 'hidden') {
            $data = Personnel::listPersonnelHidden($request,$ids);
        } else {
            $data = Personnel::listPersonnel($request,$ids);
        }


        foreach ($data as $key => $value) {
            $data[$key]->jobs = Personnel::infoJobsbyPersonnel($value->id);
        }

        return view('layouts.hoso.danhsachnhanvientructhuoc', compact('data'));
    }




    public function getFilePersonnelByMangerAjax(Request $request){
        $personnel_id = $request->personnel_id;
        $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : $time = date('Y').'-12';
        $tmp = \App\Models\Salary::getPersonnelGroupDetailMuch($personnel_id,$type=[6]);

        $tmp_2 = array();
        foreach ($tmp as $key_1 => $value_1) {
            $tmp_2[] = \App\Models\Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

        }

        $arr = array();
        foreach ($tmp_2 as $k => $v) {
            foreach ($tmp_2[$k] as $k_1 => $v_2) {
                $arr[] = $v_2;
            }

        }

        $arr = array_map("unserialize", array_unique(array_map("serialize", $arr)));
        $management_allowance_old = 0;
        $dateCurrent = date('Y-m-d');

        foreach ($arr as $key_2 => $value_2) {
            $tmp_3 = \App\Models\Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[6]);
            if( count( $tmp_3 )>0 ){
                foreach ($tmp_3 as $key_3 => $value_3) {  
                    //Phụ cấp trách nhiệm
                    if( $value_3->type == 6 ){
                        $management_allowance_old = 0;
                        if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                            if( $tmp_3[0]->is_fixed==1 ){
                                $management_allowance_old = $tmp_3[0]->value;
                            }else{
                                $string = $value_3->value_id;
                                $management_allowance_old = BatvHelper::calculateSpecial_2($string,$personnel_id,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
                            }
                        }
                    }
                }
            }
            
        }

        $salary = BatvHelper::ltt('',$personnel_id,$time,$type=1,'',$option=1,$convert_ratio='');
        $data = [
            'getSeniority' => BatvHelper::getSeniority($request->personnel_id),
            'getHistoryAddRatio' => History::listRation($personnel_id),
            'salary' => BatvHelper::formatPrice($salary),
            'management_allowance_old' =>  BatvHelper::formatPrice($management_allowance_old),
            'total_salary' =>  BatvHelper::formatPrice($salary + $management_allowance_old),
        ];

        $res=array('Response'=>"Success","data"=> $data );
        

        echo json_encode($res);
    
    }
}
