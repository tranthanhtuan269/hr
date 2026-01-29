<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Auth;
use App\Models\Personnel;
use App\Models\PeriodSalary;
use App\Models\WelfareFunds;
use App\Models\EmailConfig;
use App\Models\User;
use App\Models\Salary;
use App\Models\Parameters;
use App\Models\Evaluation;
use App\Models\Departments;
use App\Models\AdhocSalaryAssessment;
use App\Models\KiPerformance;
use App\Models\KiRules;
use App\Models\OverTime;
use App\Models\LoanCapital;
use App\Models\ConfigWeb;
use App\Models\ConfigLoanCapital;
use App\Models\HistoryPayLoanCapital;
use App\Helpers\BatvHelper;
use App\Mylibs\Myfunction;
use App\Models\SettingOvertime;

class SalaryController extends Controller
{
    public function getSalaryConfig(){
        $data = Salary::listDay();
        return view('layouts.luongthuong.config',['data'=>$data]);
    }
    public function getParametersConfig(  Request $request){
        $data = Salary::listParametersConfig( $request );
        // echo "<pre>";
        // print_r($setting);die;
        return view('layouts.luongthuong.cauhinhthamso.index',['data'=>$data]);
    }

    public function addParametersConfig(){
        $setting = Salary::settingParameters();
        return view('layouts.luongthuong.cauhinhthamso.add',['setting'=>$setting]);
    }

    public function postParametersConfig( Request $request ){
        Validator::extend('check_title', function($attribute, $value, $parameters, $validator) {
          $field = $parameters[0];
          $data = $validator->getData();
          $title = $data[$field];
          $check = Salary::checkTitleConfig($table = 'parameters',$title);
          return ( count($check) > 0 )? false:true;
        });

        $rules = [
            'title' =>'required|check_title:title',
        ];
        $messages = [
            'title.required' => 'Tên tham số không được để trống',
            'title.check_title' => 'Tên tham số đã tồn tại',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->type == 1 ){
                 $arr = [
                    'title' =>  $request->title,
                    'is_fixed' =>  $request->type,
                    'value_org' =>  $request->value_1,
                    'value' =>  $request->value_1,
                    'description' =>  $request->description,
                    'created_at' => date('Y-m-d'),
                    'created_by' => Auth::user()->id,
                    'status'    =>1
                ];
                Salary::insertParameters($arr);  
                return back()->with(['flash_message_succ' => 'Thêm tham số thành công']);
            }else{  
                $string = $request->value_2;
                $value_org = explode(' ', $string);
                $value_org = $value_org[0];
                $arr = [
                    'title'     =>  $request->title,
                    'is_fixed'  =>  $request->type,
                    'value_org' =>  $value_org,
                    'value'      =>  $request->value_2,
                    'description' =>  $request->description,
                    'created_at' => date('Y-m-d'),
                    'created_by' => Auth::user()->id,
                    'status'    =>1
                ];
                Salary::insertParameters($arr);  
                return back()->with(['flash_message_succ' => 'Thêm tham số thành công']);
            }
        }
    }

    public function editParametersConfig($id){
        $data = Salary::getInfoParametersConfig($id);
        $setting = Salary::settingParameters();
        // echo "<pre>";
        // print_r($setting);
        return view('layouts.luongthuong.cauhinhthamso.edit',['data'=>$data,'setting'=>$setting]);
    }

    public function postEditParametersConfig( Request $request){
        Validator::extend('check_title_updated', function($attribute, $value, $parameters, $validator) {
          $field_title = $parameters[0];
          $field_id= $parameters[1];
          $data = $validator->getData();
          $title = $data[$field_title];
          $id = $data[$field_id];
          $check = Salary::checkTitleConfig($table='parameters',$title,$id);
          return ( count($check) > 0 )? false:true;
        });
        $rules = [
            'title' =>'required|check_title_updated:title,id',
        ];
        $messages = [
            'title.required' => 'Tên tham số không được để trống',
            'title.check_title_updated' => 'Tên tham số đã tồn tại',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->type == 1 ){
                 $arr = [
                    'title' =>  $request->title,
                    'is_fixed'  =>$request->type,
                    'value_org' =>  $request->value_1,
                    'value' =>  $request->value_1,
                    'description' =>  $request->description,
                    'updated_at' => date('Y-m-d'),
                    'updated_by' => Auth::user()->id,
                ];
                Salary::updateParameters($arr,$request->id);  
                return redirect()->route('editParametersConfig',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật tham số thành công']);
            }else{      
                $string = $request->value_2;
                $value_org = explode(' ', $string);
                $value_org =  $value_org[0];
         
                 $arr = [
                    'title'     =>  $request->title,
                    'is_fixed'  =>$request->type,
                    'value_org' =>  $value_org,
                    'value'      =>  $request->value_2,
                    'description' =>  $request->description,
                    'updated_at' => date('Y-m-d'),
                    'updated_by' => Auth::user()->id,
                ];
                Salary::updateParameters($arr,$request->id);  
                return redirect()->route('getParametersConfig',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật tham số thành công']);
            }
        }
    }

    public function deleteParametersConfig($id){
        $check =  Salary::checkParameters($id);
        if( count($check)>0 ){
            return back()->with(['flash_message_err' => 'Tham số đã tồn tại trong công thức. Bạn không thể xóa !!!']);
        }else{
            $arr = [
                'updated_at' => date('Y-m-d'),
                'updated_by' => Auth::user()->id,
                'status'     => 0
            ];
            Salary::updateParameters($arr,$id);  
            return back()->with(['flash_message_succ' => 'Bạn đã xóa thành công']);
        }

    }

    public function getGroupPersonalConfig(  Request $request){
        $data = Salary::listGroupPersonalConfig( $request );
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        return view('layouts.luongthuong.cauhinhnhomnguoi.index',['data'=>$data]);
    }

    public function checkGroupPersonalConfigAjax( Request $request ){
        if ($request->ajax()) {
            $check = Salary::checkGroupPersonalConfigAjax( $request->type,$request->personnel_id );
            if( count($check) >0 ){
                $res=array('Response'=>"Error","Error"=>"Nhân viên chỉ có thể thuộc 1 nhóm cùng loại",'personnel_id'=> $request->personnel_id);
            }  
            echo json_encode($res);
        }
    }
    public function addGroupPersonalConfigAjax( Request $request ){
        if ($request->ajax()) {
            $list_selected = $request->selected;
            $list_selected_type = $request->selected_type;
            // Trường hợp chọn kiểu là Chi phí khác ( TH đặc biệt )
            if( count( $list_selected )>0 && count( $list_selected_type )>0 ){
                if( count( $list_selected_type ) == 1 &&  $list_selected_type[0] == 12){
                    $arr = [
                        'title'     =>  $request->title,
                        'description'  =>  $request->description,
                        'created_at' => date('Y-m-d'),
                        'created_by' => Auth::user()->id,
                        'status'    =>1
                    ];
                    Salary::insertGroupPersonal($arr);  
                    $id =  \DB::getPdo()->lastInsertId();
                    foreach ($list_selected_type as $k_selected_type => $value_selected_type) {
                        foreach ($list_selected as $k => $v) {
                            $data[] =  array('personnel_group_id'=>$id, 'personnel_id'=>$v ,'type'=> $value_selected_type);

                        }  
                        Salary::insertGroupPersonalDetail($data); 
                        unset($data);
                    }

                    $res[]=array('Response'=>"Success","Message"=>"Thêm nhóm người thành công" );
                }else{
                    // $tmp = 0;
                    // foreach ($list_selected as $key => $value) {
                    //     foreach ($list_selected_type as $k_selected_type => $value_selected_type) {
                    //         $check = Salary::checkGroupPersonalConfigAjax( $value_selected_type,$value );
                    //         if( $check >0 ){
                    //             $res[]=array('Response'=>"Error","Error"=>"Nhân viên chỉ có thể thuộc 1 nhóm cùng loại",'personnel_id'=> $value);
                    //             $tmp++;
                    //         }
                    //     }
                    // }

                    // if( $tmp == 0 ){
                        $checkTitleConfig = Salary::checkTitleConfig($table = 'personnel_groups',$request->title);
                        if( count($checkTitleConfig) > 0  ){
                            $res[]=array('Response'=>"Error_special","Error_special"=>"Tên nhóm đã tồn tại" );
                        }else{
                            $arr = [
                                'title'     =>  $request->title,
                                'description'  =>  $request->description,
                                'created_at' => date('Y-m-d'),
                                'created_by' => Auth::user()->id,
                                'status'    =>1
                            ];
                            Salary::insertGroupPersonal($arr);  
                            $id =  \DB::getPdo()->lastInsertId();
                            foreach ($list_selected_type as $k_selected_type => $value_selected_type) {
                                foreach ($list_selected as $k => $v) {
                                    $data[] =  array('personnel_group_id'=>$id, 'personnel_id'=>$v ,'type'=> $value_selected_type);

                                }  
                                Salary::insertGroupPersonalDetail($data); 
                                unset($data);
                            }

                            $res[]=array('Response'=>"Success","Message"=>"Thêm nhóm người thành công" );
                        }
                    // }
                }

            }else{
                $res[]=array('Response'=>"Error_special","Error_special"=>"Bạn chưa chọn nhân viên hoặc chưa chọn loại hình!");
            }
            echo json_encode($res);
        }
    }

    public function editGroupPersonalConfig($id){
        $listPersonnel = Personnel::getAllPersonnel();
        $arr = Salary::getGroupPersonalConfig($id);
        $data = array(); 
        foreach ($arr as $key => $value) {
            if( !isset( $data[ $value->id  ] ) ){
                $data[$value->id] = array(
                        'id'=> $value->id,
                        'title'=> $value->title,
                        'description'=> $value->description,
                    );
                $data[$value->id]['personnel_id'][$value->personnel_id] = $value->personnel_id;
                $data[$value->id]['type'][$value->type] = $value->type;
               
            }else{
                $data[$value->id]['personnel_id'][$value->personnel_id] = $value->personnel_id;
                $data[$value->id]['type'][$value->type] = $value->type;
            }
        }

        $listPersonnel = json_decode(json_encode($listPersonnel), true);
        foreach ($listPersonnel as $key => $value) {
            if( in_array($value['id'], $data[$id]['personnel_id']) ){
                $listPersonnel[$key]['ticket'] ='1';
                $listPersonnel[$key]['date_out'] = $value['date_out'];
            }

        }

        $listType = array();
        foreach ($listPersonnel as $key => $value) {
            if( in_array($value['id'], $data[$id]['personnel_id']) ){
                $listType[$key]['ticket'] ='1'; 
            }

        }

        return view('layouts.luongthuong.cauhinhnhomnguoi.edit',['data'=>$data,'listPersonnel'=>$listPersonnel,'id'=>$id]);
    }

    public function editGroupPersonalConfigAjax( Request $request ){
        if ($request->ajax()) {
            $list_selected = $request->selected;
            $list_selected_type = $request->selected_type;
            if( count( $list_selected )>0 && count( $list_selected_type )>0 ){
                // Trường hợp chọn kiểu là Chi phí khác ( TH đặc biệt )
                if( count( $list_selected_type ) == 1 && $list_selected_type[0] == 12 ){
                    $arr = [
                        'title'     =>  $request->title,
                        'description'  =>  $request->description,
                        'updated_at' => date('Y-m-d'),
                        'updated_by' => Auth::user()->id,
                    ];
                    Salary::updateGroupPersonal($arr,$request->id);  

                    Salary::deleteGroupPersonalDetail($request->id);

                    foreach ($list_selected_type as $k_selected_type => $value_selected_type) {
                        foreach ($list_selected as $k => $v) {
                            $data[] =  array('personnel_group_id'=>$request->id, 'personnel_id'=>$v,'type'=> $value_selected_type);

                        }   
                        Salary::insertGroupPersonalDetail($data); 
                        unset($data);
                    }

                    $res[]=array('Response'=>"Success","Message"=>"Cập nhật thông tin thành công" );
                }else{
                    // $tmp = 0;
                    // foreach ($list_selected as $key => $value) {
                    //     foreach ($list_selected_type as $k_selected_type => $value_selected_type) {
                    //         $check = Salary::checkEditGroupPersonalConfigAjax( $value_selected_type,$value,$request->id );
                    //         if( $check >0 ){
                    //             $res[]=array('Response'=>"Error","Error"=>"Nhân viên chỉ có thể thuộc 1 nhóm cùng loại",'personnel_id'=> $value);
                    //             $tmp++;
                    //         }
                    //     }
                    // }

                    // if( $tmp == 0 ){
                        $checkTitleConfig = Salary::checkTitleConfig($table = 'personnel_groups',$request->title,$request->id);
                        if( count($checkTitleConfig) > 0  ){
                            $res[]=array('Response'=>"Error_special","Error_special"=>"Tên nhóm đã tồn tại" );
                        }else{
                            $arr = [
                                'title'     =>  $request->title,
                                'description'  =>  $request->description,
                                'updated_at' => date('Y-m-d'),
                                'updated_by' => Auth::user()->id,
                            ];
                            Salary::updateGroupPersonal($arr,$request->id);  

                            Salary::deleteGroupPersonalDetail($request->id);

                            foreach ($list_selected_type as $k_selected_type => $value_selected_type) {
                                foreach ($list_selected as $k => $v) {
                                    $data[] =  array('personnel_group_id'=>$request->id, 'personnel_id'=>$v,'type'=> $value_selected_type);

                                }   
                                // echo "<pre>";
                                // print_r($data);die;
                                Salary::insertGroupPersonalDetail($data); 
                                unset($data);
                            }

                            $res[]=array('Response'=>"Success","Message"=>"Cập nhật thông tin thành công" );
                        }
                    // }
                }
            }else{
                $res[]=array('Response'=>"Error_special","Error_special"=>"Bạn chưa chọn nhân viên hoặc chưa chọn loại hình!");
            }
            echo json_encode($res);
        }
    }

    public function addGroupPersonalConfig(){
        $listPersonnel = Personnel::getAllPersonnel();
        return view('layouts.luongthuong.cauhinhnhomnguoi.add',['listPersonnel'=>$listPersonnel]);
    }

    public function deleteGroupPersonalConfig($id){
        $check = Salary::checkIncomeConfigbyGroupPersonnel($id);
        if( count($check) > 0){
            return back()->with(['flash_message_err' => 'Không thể xóa vì nhóm đã có trong phần cấu hình']);
        }else{
             $arr = [
                'updated_at' => date('Y-m-d'),
                'updated_by' => Auth::user()->id,
                'status'     => 0
            ];
            Salary::updateGroupPersonal($arr,$id);  
            return back()->with(['flash_message_succ' => 'Bạn đã xóa thành công']);
        }

    }

    public function getRecipeConfig( Request $request ){
        $data = Salary::listRecipeConfig( $request );
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.luongthuong.cauhinhcongthuc.index',['data'=>$data]);
    }

    public function addRecipeConfig(){
        $parameters = Salary::infoParameters();  
        $groupPersonal = Salary::infoGroupPersonal();
        return view('layouts.luongthuong.cauhinhcongthuc.add',['parameters'=> $parameters,'groupPersonal'=>$groupPersonal]);
    }

    public function addRecipeConfigAjax( Request $request ){
        if ($request->ajax()) {
            $valid_from  = BatvHelper::formatDate($request->startDate,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
            $valid_to    =  BatvHelper::formatDate($request->endDate,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
            // Kiểm tra xem công thức với option được tạo có trùng với khoảng thời gian công thức cùng option  nào đó không
            // $check = Salary::checkRecipeConfig($request->gender,$valid_from,$valid_to);

            // if( $check == 0 ){
                $valid_from_convert = explode("-",$valid_from);
                $valid_to_convert = explode("-",$valid_to);
                if( $valid_from < $valid_to ){
                    if( $request->selectMonth != "0" ){
                        if( (int)$request->selectMonth == (int)$valid_from_convert[1] && (int)$request->selectMonth <= (int)$valid_to_convert[1] ){
                            if( $request->type==1 ){
                                $arr = [
                                    'title'     =>  $request->title,
                                    'type'      =>  $request->gender,
                                    'is_fixed'  => 1,
                                    'key_special'  =>  BatvHelper::CreateKey(),
                                    'value'  =>  (int)$request->recipe_fixed,
                                    'applied_month'  =>  $request->selectMonth,
                                    'valid_from'  => $valid_from ,
                                    'valid_to'  =>$valid_to ,
                                    'created_at' => date('Y-m-d'),
                                    'created_by' => Auth::user()->id,
                                    'status' => 1,
                                ];
                                $result = Salary::insertIncomeConfig($arr);

                                $id =  \DB::getPdo()->lastInsertId();
                                $data = [
                                    'personnel_group_id'     =>  $request->group,
                                    'income_config_id'      => $id,
                                ];
                                Salary::insertIncomeConfigGroup($data);
                                if($result){
                                    $res=array('Response'=>"Success","Message"=>"Cấu hình công thức thành công" );
                                }else{
                                    $res=array('Response'=>"Error","Error"=>"Dữ liệu không thay đổi" );
                                }
                            }else{
                                if( $request->recipe_reference_id != NULL){
                                    $arr = [
                                        'title'     =>  $request->title,
                                        'type'      =>  $request->gender,
                                        'is_fixed'  => 0,
                                        'key_special'  =>  BatvHelper::CreateKey(),
                                        'value'  =>  $request->recipe_reference,
                                        'value_id'     => $request->recipe_reference_id,
                                        'applied_month'  =>  $request->selectMonth,
                                        'valid_from'  => $valid_from ,
                                        'valid_to'  =>$valid_to ,
                                        'created_at' => date('Y-m-d'),
                                        'created_by' => Auth::user()->id,
                                        'status' => 1,
                                    ];
                                    $result = Salary::insertIncomeConfig($arr);

                                    $id =  \DB::getPdo()->lastInsertId();
                                    $data = [
                                        'personnel_group_id'     =>  $request->group,
                                        'income_config_id'      => $id,
                                    ];
                                    Salary::insertIncomeConfigGroup($data);
                                    $res=array('Response'=>"Success","Message"=>"Cấu hình công thức thành công" );
                                }else{
                                    $res=array('Response'=>"Error","Error"=>"Chưa nhập công thức tính" );
                                }
                            }
                        }else{
                             $res=array('Response'=>"Error","Error"=>"Chọn khoảng thời gian hiệu lực không hợp lệ" );
                        }
                    }else{
                        if( $request->type==1 ){
                            $arr = [
                                'title'     =>  $request->title,
                                'type'      =>  $request->gender,
                                'is_fixed'  => 1,
                                'key_special'  =>  BatvHelper::CreateKey(),
                                'value'  =>  (int)$request->recipe_fixed,
                                'applied_month'  =>  $request->selectMonth,
                                'valid_from'  => $valid_from ,
                                'valid_to'  =>$valid_to ,
                                'created_at' => date('Y-m-d'),
                                'created_by' => Auth::user()->id,
                                'status' => 1,
                            ];
                            $result = Salary::insertIncomeConfig($arr);

                            $id =  \DB::getPdo()->lastInsertId();
                            $data = [
                                'personnel_group_id'     =>  $request->group,
                                'income_config_id'      => $id,
                            ];
                            Salary::insertIncomeConfigGroup($data);
                            if($result){
                                $res=array('Response'=>"Success","Message"=>"Cấu hình công thức thành công" );
                            }else{
                                $res=array('Response'=>"Error","Error"=>"Dữ liệu không thay đổi" );
                            }
                        }else{
                            if( $request->recipe_reference_id != NULL){
                                $arr = [
                                    'title'     =>  $request->title,
                                    'type'      =>  $request->gender,
                                    'is_fixed'  => 0,
                                    'key_special'  =>  BatvHelper::CreateKey(),
                                    'value'  =>  $request->recipe_reference,
                                    'value_id'     => $request->recipe_reference_id,
                                    'applied_month'  =>  $request->selectMonth,
                                    'valid_from'  => $valid_from ,
                                    'valid_to'  =>$valid_to ,
                                    'created_at' => date('Y-m-d'),
                                    'created_by' => Auth::user()->id,
                                    'status' => 1,
                                ];
                                $result = Salary::insertIncomeConfig($arr);

                                $id =  \DB::getPdo()->lastInsertId();
                                $data = [
                                    'personnel_group_id'     =>  $request->group,
                                    'income_config_id'      => $id,
                                ];
                                Salary::insertIncomeConfigGroup($data);
                                $res=array('Response'=>"Success","Message"=>"Cấu hình công thức thành công" );
                            }else{
                                $res=array('Response'=>"Error","Error"=>"Chưa nhập công thức tính" );
                            }
                        }
                    }

                }else{
                    $res=array('Response'=>"Error","Error"=>"Chọn khoảng thời gian hiệu lực không hợp lệ" );
                }
            // }else{
            //     $res=array('Response'=>"Error","Error"=>"Khoảng thời gian đã nằm trong thời gian của công thức cùng loại đã được tạo trước đó" );
            // }

            echo json_encode($res);
        }
    }

    public function editRecipeConfig( $id ){
        $parameters = Salary::infoParameters();  
        $groupPersonal = Salary::infoGroupPersonal();
        $data = Salary::infoRecipeConfig($id);
        return view('layouts.luongthuong.cauhinhcongthuc.edit',['parameters'=> $parameters,'groupPersonal'=>$groupPersonal,'data'=>$data]);
    }

    public function editRecipeConfigAjax( Request $request ){
        if ($request->ajax()) {
            $valid_from  = BatvHelper::formatDate($request->startDate,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
            $valid_to    =  BatvHelper::formatDate($request->endDate,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
            // $check = Salary::checkRecipeConfig($request->gender,$valid_from,$valid_to,$request->id);

            // if( $check == 0 ){
                $valid_from_convert = explode("-",$valid_from);
                $valid_to_convert = explode("-",$valid_to);
                if( $valid_from < $valid_to ){
                    if( $request->selectMonth != "0" ){
                        if( (int)$request->selectMonth == (int)$valid_from_convert[1] && (int)$request->selectMonth <= (int)$valid_to_convert[1] ){
                            if( $request->type==1 ){
                                $arr = [
                                    'title'     =>  $request->title,
                                    'type'      =>  $request->gender,
                                    'is_fixed'  => $request->type,
                                    'value'  =>  (int)$request->recipe_fixed,
                                    'applied_month'  =>  $request->selectMonth,
                                    'valid_from'  =>  BatvHelper::formatDate($request->startDate,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false),
                                    'valid_to'  =>  BatvHelper::formatDate($request->endDate,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false),
                                    'updated_at' => date('Y-m-d'),
                                    'updated_by' => Auth::user()->id,
                                ];
                                Salary::updateIncomeConfig($arr,$request->id);
                                $data = [
                                    'personnel_group_id'     =>  $request->group,
                                    'income_config_id'      => $request->id,
                                ];
                                Salary::updateIncomeConfigGroup($data,$request->id);

                                $res=array('Response'=>"Success","Message"=>"Cập nhật thành công" );

                  
                            }else{
                                if( $request->recipe_reference_id != NULL){
                                    $arr = [
                                        'title'     =>  $request->title,
                                        'type'      =>  $request->gender,
                                        'is_fixed'  => $request->type,
                                        'value'     =>  $request->recipe_reference,
                                        'value_id'     => $request->recipe_reference_id,
                                        'applied_month'  =>  $request->selectMonth,
                                        'valid_from'  =>  BatvHelper::formatDate($request->startDate, 'd/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false),
                                        'valid_to'  =>  BatvHelper::formatDate($request->endDate, 'd/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false),
                                        'updated_at' => date('Y-m-d'),
                                        'updated_by' => Auth::user()->id,
                                    ];
                                    $result = Salary::updateIncomeConfig($arr,$request->id);
                                    $data = [
                                        'personnel_group_id'     =>  $request->group,
                                        'income_config_id'      => $request->id,
                                    ];
                                    Salary::updateIncomeConfigGroup($data,$request->id);
                                    $res=array('Response'=>"Success","Message"=>"Cập nhật thành công" );
                                }else{
                                    $res=array('Response'=>"Error","Error"=>"Chưa nhập công thức tính" );
                                }
                    
                            }
                        }else{
                            $res=array('Response'=>"Error","Error"=>"Chọn khoảng thời gian hiệu lực không hợp lệ" );
                        }
                    }else{
                        if( $request->type==1 ){
                            $arr = [
                                'title'     =>  $request->title,
                                'type'      =>  $request->gender,
                                'is_fixed'  => $request->type,
                                'value'  =>  (int)$request->recipe_fixed,
                                'applied_month'  =>  $request->selectMonth,
                                'valid_from'  =>  BatvHelper::formatDate($request->startDate,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false),
                                'valid_to'  =>  BatvHelper::formatDate($request->endDate,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false),
                                'updated_at' => date('Y-m-d'),
                                'updated_by' => Auth::user()->id,
                            ];
                            Salary::updateIncomeConfig($arr,$request->id);
                            $data = [
                                'personnel_group_id'     =>  $request->group,
                                'income_config_id'      => $request->id,
                            ];
                            Salary::updateIncomeConfigGroup($data,$request->id);

                            $res=array('Response'=>"Success","Message"=>"Cập nhật thành công" );

              
                        }else{
                            if( $request->recipe_reference_id != NULL){
                                $arr = [
                                    'title'     =>  $request->title,
                                    'type'      =>  $request->gender,
                                    'is_fixed'  => $request->type,
                                    'value'     =>  $request->recipe_reference,
                                    'value_id'     => $request->recipe_reference_id,
                                    'applied_month'  =>  $request->selectMonth,
                                    'valid_from'  =>  BatvHelper::formatDate($request->startDate, 'd/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false),
                                    'valid_to'  =>  BatvHelper::formatDate($request->endDate, 'd/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false),
                                    'updated_at' => date('Y-m-d'),
                                    'updated_by' => Auth::user()->id,
                                ];
                                $result = Salary::updateIncomeConfig($arr,$request->id);
                                $data = [
                                    'personnel_group_id'     =>  $request->group,
                                    'income_config_id'      => $request->id,
                                ];
                                Salary::updateIncomeConfigGroup($data,$request->id);
                                $res=array('Response'=>"Success","Message"=>"Cập nhật thành công" );
                            }else{
                                $res=array('Response'=>"Error","Error"=>"Chưa nhập công thức tính" );
                            }
                
                        }
                    }

                }else{
                    $res=array('Response'=>"Error","Error"=>"Chọn khoảng thời gian hiệu lực không hợp lệ" );
                }
            // }else{
            //     $res=array('Response'=>"Error","Error"=>"Khoảng thời gian đã nằm trong thời gian của công thức cùng loại đã được tạo trước đó" );
            // }

            echo json_encode($res);
        }

    }
    public function deleteRecipeConfig( $id ){
        $check = Salary::checkIncomeConfigGroup($id);
        if( count($check)>0 ){
            return back()->with(['flash_message_err' => 'Không được xóa công thức lương trong quá trình công thức đang được áp dụng']);
        }else{
            $arr = [
                'updated_at' => date('Y-m-d'),
                'updated_by' => Auth::user()->id,
                'status'     => 0
            ];
            Salary::updateRecipeConfig($arr,$id); 
            Salary::deleteIncomeConfigGroup($id);  
            return back()->with(['flash_message_succ' => 'Bạn đã xóa thành công']);
        }

    }

    public function getSalary(Request $request){
        $data = Salary::listSalary($request);
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.luongthuong.server.luong',['data'=>$data]);
    }

    public function getSalary2(Request $request){
        $data = Salary::listSalary2($request);
        return view('layouts.luongthuong.server.luong2',['data'=>$data]);
    }

    // CẬP NHẬT PHẦN TÍNH LƯƠNG THÌ PHẢI CẬP NHẬT CRONJOBS NỮA NA
    public function getSalaryAjax( Request $request ){
        if( $request->ajax() ){
            $dayLatches = '';
            if( $request->selectMonth != "" ){
                $month =  ( $request->selectMonth < 10)?'0'.$request->selectMonth:$request->selectMonth;
                $year  =  $request->selectYear;
                $dateCurrent = $year."-".$month."-"."01";
            }else{
                $dateCurrent = date('Y-m-d');
                $month = date('m');
                $year  = date('Y');
            }
            $date_value = $year.'-'.$month.'-01';
            // Tính số ngày công tiêu chuẩn
            $standard_days  = BatvHelper::count_working_days($date_value,$year."-".$month."-".cal_days_in_month(CAL_GREGORIAN,$month,$year) );

            $checkTimeApply = Salary::checkTimeApply($dateCurrent,$month,$type=0);
            if( $checkTimeApply >0 ){
                $setting_overtime = SettingOvertime::find(1);
                $check_salary = Salary::checkPersonnelIncome($month,$year,$type='check_salary');
                $infoParameters = Salary::infoParameters();
                $listPersonnel = Personnel::getPersonnelSalary($month,$year,$year."-".$month);
                // unset($listPersonnel[0]);
                // echo "<pre>";
                // print_r($listPersonnel);die;
                //Thông tin %x lương thử việc lấy từ cấu hình
                $percent_trial = BatvHelper::infoConfigSettingOthers(0);
                if( count( $check_salary ) >0 ){
                    foreach ($listPersonnel as $key => $value) {
                        $salary_overtime = 0;
                        $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                        $tmp = Salary::getPersonnelGroupDetailMuch($value,$type=[0,10]);
                        $tmp_2 = array();
                        foreach ($tmp as $key_1 => $value_1) {
                            $tmp_2[] = Salary::getIncomeConfigGroup($value_1->personnel_group_id);

                        }
                        $data = array();
                        foreach ($tmp_2 as $k => $v) {
                            foreach ($tmp_2[$k] as $k_1 => $v_2) {
                                $data[] = $v_2;
                            }

                        }
                        $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                        if( count( $checkPersonnelIncome ) >0 ){
                            $checkPersonnelSalary = Salary::checkPersonnelSalary($checkPersonnelIncome->id);
                            if( count( $checkPersonnelSalary )==0 ){
                                $data_personnel_salary = [
                                    'personnel_income_id'   => $checkPersonnelIncome->id,
                                ];
                                Salary::insertPersonnelSalary($data_personnel_salary);
                            }
                        }else{
                            $data_personnel_income = [
                                'month'         =>  $month,
                                'year'          =>  $year,
                                'date_value'    =>  $date_value,
                                'personnel_id'  =>  $value,
                                'created_at'    =>  date('Y-m-d'),
                                'created_by'    => Auth::user()->id,
                                'status'        => 1,
                            ];
                            Salary::insertPersonnelIncome($data_personnel_income);
                            $id =  \DB::getPdo()->lastInsertId(); 

                            $data_personnel_salary = [
                                'personnel_income_id'   => $id,
                            ];

                            Salary::insertPersonnelSalary($data_personnel_salary);
                        }
                        $time = $year.'-'.$month;
                        $convert = explode("-",$time);
                        foreach ($data as $key_2 => $value_2) {
                            $type = 0;// Aplly cho Lương
                            //$tmp_3 = Salary::getIncomeConfigSalary($value_2->income_config_id,$type,$dateCurrent);
                            $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[0,10]);
                            if( count( $tmp_3 )>0 ){
                                $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                                $param = $convert[0].'-'.$convert[1].'-'.$numberDay;
                                $param_1 = $convert[0].'-'.$convert[1].'-01';
                                $toh_contract = Salary::checkContract( $value,$param );
                                
                                $toh_contract_1 = Salary::checkContract( $value,$param_1 );
                                $param_contract_check = false;
                                if( $toh_contract ){
                                    $checkContract = $toh_contract->contract_id;
                                }else{
                                    // $checkContractSpecial = Salary::checkContractSpecial($value);
                                    // $checkContract = $checkContractSpecial->contract_id;
                                    // $param_contract_check = true;
                                    $checkContractSpecial = Salary::checkContract( $value,$param_1 );
                                    // dd($checkContractSpecial);
                                    if($checkContractSpecial) {
                                        $checkContract = $checkContractSpecial->contract_id;
                                        $param_contract_check = true;
                                    }
                                }
                                if( $toh_contract_1 ){
                                    $checkContract_1 = Salary::checkContract( $value,$param_1 )->contract_id;
                                }else{
                                    $checkContract_1 = '';
                                }

                                $money_leave = $mulct_money_awol = $money_awol_half_1 = $money_awol_half_2 = 0;
                                foreach ($tmp_3 as $key_3 => $value_3) {
                                    if( $value_3->type == 0){
                                        // Sử dụng công thức áp dụng cho tháng tính lương đó
                                        if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                            $string = $value_3->value_id;
                                            /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/

                                            
                                            $welfare_fund = $salary_official_default = $salary_trial_default = $salary_parttime_default=$salary_trainee_default=$salary_trainee_parttime_default=$salary_official_work = $salary_trial_work = $salary_trainee_work = $salary_trainee_parttime_work= $salary_parttime_work=0;

                                            if ($toh_contract_1 || $toh_contract) {
                                                //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){
                                                    if( $param_contract_check == true ){
                                                        $time = $checkContractSpecial->apply_to;
                                                        $convert = explode("-",$time);
                                                        $numberDay = $convert[2];
                                                    }
    
                                                    $countAttendance_CP = BatvHelper::countAttendance_CP($convert[1],$convert[0],1,$numberDay,$value) + BatvHelper::countTooLateAttendance( $month,$year,1,$numberDay,$value  );// số ngày nghỉ có phép + số buổi đi làm nhưng đi muộn > số phút setting sẽ bị trừ nửa ngày công
                                                    $countAttendance_KP = BatvHelper::countAttendance_KP($convert[1],$convert[0],1,$numberDay,$value);// số ngày nghỉ ko phép
    
                                                    if( $checkContract == 1 ){// Thử việc
                                                        $salary_trial_work = $percent_trial*BatvHelper::calculate($string,$value,$time,$type=1,'',$option=1,$convert_ratio='',$dayLatches);
                                                        $salary_trial_default =  BatvHelper::ltt('',$value,$time,$type=1,'',$option=1,$convert_ratio='',$dayLatches);
                                                        // TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                        $mulct_money_awol = -( $countAttendance_KP*$percent_trial*$salary_trial_default )/$standard_days;
    
                                                    }elseif ( $checkContract == 2 ) {// Chính thức
    
                                                        $salary_official_work = BatvHelper::calculate($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                        $salary_official_default = BatvHelper::ltt('',$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);// 100% chính thức
                                                       
                                                        // Tính lương làm thêm ngoài giờ
                                                        $hour_overtime = Overtime::leftJoin('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
                                                                        ->where('overtime.personnel_id','=', $value)
                                                                        ->where('overtime_detail.score', 1)
                                                                        ->whereMonth('overtime_detail.time_day','=', $month)
                                                                        ->whereYear('overtime_detail.time_day','=', $year)
                                                                        ->sum('overtime_detail.hour');
                          
                                                        if ($hour_overtime > 0) {
                                                            $nctc =  BatvHelper::count_working_days($year.'-'.$month.'-01',$year.'-'.$month.'-'.$numberDay);
                                                            $salary_overtime = ( $hour_overtime * $salary_official_default/($nctc*8) );
                                                        }   
    
                                                        $flag = BatvHelper::checkPersonnelOut($value,$convert[1],$convert[0]);
                                                        $lbq = $salary_official_default/$standard_days;
                                                        if($flag){
                                                            $mulct_money_awol = 0;
                                                            if( $countAttendance_KP > 0 ){
                                                                $mulct_money_awol = -$countAttendance_KP*$lbq;
                                                            }
                                                        }else{
                                                            //Tính lương bình quân
                                                            Salary::updatePersonnelSalary(['salary_official_default'   => $salary_official_default],$checkPersonnelIncome->id);
                                                            $money_leave = BatvHelper::salary_leave('',$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
    
                                                            $welfare_fund = BatvHelper::salary_basic_inline($salary_official_default)['qpl'];
    
                                                            // Xử lý TH nếu đ/c đó nghỉ 1 ngày (mất ngày nghỉ phép chứ chưa bị trừ tiên) + 1 ngày nữa (sẽ bị trừ theo lương cơ bản giống như nhận phép), nếu thêm x ngày nữa thì x ngày này bị trừ LBQ + TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                            $countNNP = BatvHelper::countNNP($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
           
                                                            if( $countNNP == 1 || $countNNP == 0.5 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $mulct_money_awol = $money_leave;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $mulct_money_awol = 0;
                                                                }
                                                            }else{
                                                                if( $countAttendance_CP == 0.5 ){
                                                                    if( $countAttendance_KP == 0 ){
                                                                        $mulct_money_awol = $money_leave;
                                                                    } elseif( $countAttendance_KP == 0.5 ){
                                                                        $mulct_money_awol = -0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    } else {
                                                                        $mulct_money_awol = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    }
                                                                } elseif( $countAttendance_CP == 1 ){
                                                                    if( $countAttendance_KP == 0 ){
                                                                        $mulct_money_awol = $money_leave;
                                                                    } elseif( $countAttendance_KP == 0.5 ){
                                                                        $mulct_money_awol = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    } else {
                                                                        $mulct_money_awol = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    }
                                                                } elseif( $countAttendance_CP == 1.5 ){
                                                                    if( $countAttendance_KP == 0 ){
                                                                        $mulct_money_awol = 0.5*$lbq - 0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    } elseif( $countAttendance_KP == 0.5 ){
                                                                        $mulct_money_awol = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    } else {
                                                                        $mulct_money_awol = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    }
                                                                } elseif( $countAttendance_CP >= 2 ){
                                                                    if( $countAttendance_KP == 0 ){
                                                                        $mulct_money_awol = $lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    } elseif( $countAttendance_KP == 0.5 ){
                                                                        $mulct_money_awol = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    } else {
                                                                        $mulct_money_awol = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                    }
                                                                }
                                                            }
                                                        }
    
                                                    }elseif ( $checkContract == 3 ) {// Thực tập fulltime
                                                        $salary_trainee_work = BatvHelper::calculate($string,$value,$time,$type=3,'',$option=1,$convert_ratio='',$dayLatches);
                                                        $salary_trainee_default = BatvHelper::ltt('',$value,$time,$type=3,'',$option=1,$convert_ratio='',$dayLatches);// 100% chính thức
    
                                                        // TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                        $mulct_money_awol = -( $countAttendance_KP*$salary_trainee_default )/$standard_days;
    
                                                    }elseif ( $checkContract == 5 ) {// Thực tập partime
                                                        $salary_trainee_parttime_work = BatvHelper::calculate($string,$value,$time,$type=5,'',$option=1,$convert_ratio='',$dayLatches);
                                                        $salary_trainee_parttime_default = BatvHelper::ltt('',$value,$time,$type=5,'',$option=1,$convert_ratio='',$dayLatches);// 100% chính thức
                                                        // TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                        $mulct_money_awol = -( $countAttendance_KP*$salary_trainee_parttime_default )/$standard_days;
                                                    }else{// Part time
                                                        $salary_parttime_work = BatvHelper::calculate($string,$value,$time,$type=4,'',$option=1,$convert_ratio='',$dayLatches);
                                                        $salary_parttime_default = BatvHelper::ltt('',$value,$time,$type=4,'',$option=1,$convert_ratio='',$dayLatches);
                                                        // TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                        $mulct_money_awol = -( $countAttendance_KP*$salary_parttime_default )/$standard_days;
                                                    }
                                                }else{  // NẾU HỢP ĐỘNG LÀ NỬA NÀY NỬA KIA
    
                                                    $convert_apply_from = explode("-",$toh_contract->apply_from);
                                                    $countAttendance_CP = BatvHelper::countAttendance_CP($convert_apply_from[1],$convert_apply_from[0],$convert_apply_from[2],$numberDay,$value)+ BatvHelper::countTooLateAttendance( $month,$year,$convert_apply_from[2],$numberDay,$value );// số ngày nghỉ có phép + số buổi đi làm nhưng đi muộn > số phút setting sẽ bị trừ nửa ngày công
                                                    $countAttendance_KP = BatvHelper::countAttendance_KP($convert_apply_from[1],$convert_apply_from[0],$convert_apply_from[2],$numberDay,$value);// số ngày nghỉ ko phép
    
                                                    if( $checkContract == 1 ){// Thử việc
                                                        $salary_trial_work =$percent_trial* BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=1,'',$option=2,$convert_ratio='',$dayLatches);
                                                        $salary_trial_default = BatvHelper::ltt('',$value,$toh_contract->apply_from,$type=1,'',$option=2,$convert_ratio='',$dayLatches); 
                                                        // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                        $money_awol_half_1 = -( $countAttendance_KP*$percent_trial*$salary_trial_default )/$standard_days;
                                                    }elseif ( $checkContract == 2 ) {// 1/2 chính thức
                                                        
                                                        $salary_official_work = BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=2,'',$option=2,$convert_ratio='',$dayLatches);
                                                        //echo $salary_official_work;
                                                        $salary_official_default = BatvHelper::ltt( $string,$value,$toh_contract->apply_from,$type=2,'',$option=2,$convert_ratio='',$dayLatches );
                                                        // Tính lương làm thêm ngoài giờ
                                                        $hour_overtime = Overtime::leftJoin('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
                                                                        ->where('overtime.personnel_id','=', $value)
                                                                        ->where('overtime_detail.score', 1)
                                                                        ->whereMonth('overtime_detail.time_day','=', $month)
                                                                        ->whereYear('overtime_detail.time_day','=', $year)
                                                                        ->sum('overtime_detail.hour');
                          
                                                        if ($hour_overtime > 0) {
                                                            $nctc =  BatvHelper::count_working_days($year.'-'.$month.'-01',$year.'-'.$month.'-'.$numberDay);
                                                            $salary_overtime = ( $hour_overtime * $salary_official_default/($nctc*8) );
                                                        }   
                                                        //Tính lương bình quân
                                                        $lbq = $salary_official_default/$standard_days;
                                                        Salary::updatePersonnelSalary(['salary_official_default'   => $salary_official_default],$checkPersonnelIncome->id);
                                                        $money_leave = BatvHelper::salary_leave('',$value,$toh_contract->apply_from,$type=2,'',$option=2,$convert_ratio='',$dayLatches);
                                                        $countNNP = BatvHelper::countNNP($string,$value,$toh_contract->apply_from,$type=2,'',$option=2,$convert_ratio='',$dayLatches);
                                                        if( $countNNP == 1 || $countNNP == 0.5 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_1 = $money_leave;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_1 = 0;
                                                            }
                                                        }else{
    
                                                            if( $countAttendance_CP == 0.5 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $money_awol_half_1 = $money_leave;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $money_awol_half_1 = -0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $money_awol_half_1 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP == 1 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $money_awol_half_1 = $money_leave;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $money_awol_half_1 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $money_awol_half_1 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP == 1.5 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $money_awol_half_1 = 0.5*$lbq - 0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $money_awol_half_1 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $money_awol_half_1 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP >= 2 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $money_awol_half_1 = $lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $money_awol_half_1 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $money_awol_half_1 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            }
                                                        }
                                                        
    
                                                    }elseif ( $checkContract == 3 ) {// Thực tập fulltime
                                                        $salary_trainee_work = BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=3,'',$option=2,$convert_ratio='',$dayLatches);
                                                        $salary_trainee_default = BatvHelper::ltt( $string,$value,$toh_contract->apply_from,$type=3,'',$option=2,$convert_ratio='',$dayLatches);
                                                        // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                        $money_awol_half_1 = -( $countAttendance_KP*$salary_trainee_default )/$standard_days;  
    
                                                    }elseif ( $checkContract == 5 ) {// Thực tập partime
                                                        $salary_trainee_parttime_work = BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=5,'',$option=2,$convert_ratio='',$dayLatches);
                                                        $salary_trainee_parttime_default = BatvHelper::ltt( $string,$value,$toh_contract->apply_from,$type=5,'',$option=2,$convert_ratio='',$dayLatches );
                                                        // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                        $money_awol_half_1 = -( $countAttendance_KP*$salary_trainee_parttime_default )/$standard_days; 
    
                                                    }else{// Part time
                                                        $salary_parttime_work = BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=4,'',$option=2,$convert_ratio='',$dayLatches);
                                                        $salary_parttime_default = BatvHelper::ltt( $string,$value,$toh_contract->apply_from,$type=4,'',$option=2,$convert_ratio='',$dayLatches );
                                                        // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                        $money_awol_half_1 = -( $countAttendance_KP*$salary_parttime_default )/$standard_days; 
    
                                                    }
    
    
    
                                                    $convert_apply_to = explode("-",$toh_contract_1->apply_to);
                                                    $countAttendance_CP = BatvHelper::countAttendance_CP($convert_apply_to[1],$convert_apply_to[0],1,$convert_apply_to[2],$value)+ BatvHelper::countTooLateAttendance( $month,$year,1,$convert_apply_to[2] ,$value);// số ngày nghỉ có phép + số buổi đi làm nhưng đi muộn > số phút setting sẽ bị trừ nửa ngày công
                                                    $countAttendance_KP = BatvHelper::countAttendance_KP($convert_apply_to[1],$convert_apply_to[0],1,$convert_apply_to[2],$value);// số ngày nghỉ ko phép
                                                    if( $checkContract_1 == 1 ){// Thử việc
                                                        $salary_trial_work =$percent_trial* BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=1,'',$option=3,$convert_ratio='',$dayLatches);
                                                        $salary_trial_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=1,'',$option=3,$convert_ratio='',$dayLatches);
                                                    // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                        $money_awol_half_2 = -( $countAttendance_KP*$percent_trial*$salary_trial_default )/$standard_days;
    
                                                    }elseif ( $checkContract_1 == 2 ) {// Chính thức
                                                        $salary_official_work = BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3,$convert_ratio='',$dayLatches);
                                                        $salary_official_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=2,'',$option=3,$convert_ratio='',$dayLatches);
                                                        $lbq = $salary_official_default/$standard_days;
                                                        Salary::updatePersonnelSalary(['salary_official_default'   => $salary_official_default],$checkPersonnelIncome->id);
                                                        $money_leave = BatvHelper::salary_leave('',$value,$toh_contract_1->apply_to,$type=2,'',$option=3,$convert_ratio='',$dayLatches);
                                                        //Tính xem nhân viên được hưởng 1 hay 1/2 ngày nghỉ phép được cấu hình trong tháng
                                                        $countNNP = BatvHelper::countNNP($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3,$convert_ratio='',$dayLatches);
                                                        if( $countNNP == 1 || $countNNP == 0.5 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_2 = $money_leave;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_2 = 0;
                                                            }
                                                        }else{
                                                            if( $countAttendance_CP == 0.5 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $money_awol_half_2 = $money_leave;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $money_awol_half_2 = -0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $money_awol_half_2 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP == 1 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $money_awol_half_2 = $money_leave;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $money_awol_half_2 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $money_awol_half_2 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP == 1.5 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $money_awol_half_2 = 0.5*$lbq - 0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $money_awol_half_2 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $money_awol_half_2 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP >= 2 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $money_awol_half_2 = $lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $money_awol_half_2 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $money_awol_half_2 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            }
                                                        }
                                                    }elseif ( $checkContract_1 == 3 ) {// Thực tập fulltime
                                                        $salary_trainee_work = BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=3,'',$option=3,$convert_ratio='',$dayLatches);
                                                        $salary_trainee_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=3,'',$option=3,$convert_ratio='',$dayLatches);
                                                        // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                        $money_awol_half_2 = -( $countAttendance_KP*$salary_trainee_default )/$standard_days;
                                                    }elseif ( $checkContract_1 == 5 ) {// Thực tập parttime
                                                        $salary_trainee_parttime_work = BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=5,'',$option=3,$convert_ratio='',$dayLatches);
                                                        $salary_trainee_parttime_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=5,'',$option=3,$convert_ratio='',$dayLatches);
                                                        // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                        $money_awol_half_2 = -( $countAttendance_KP*$salary_trainee_parttime_default )/$standard_days;
                                                    }else{// Part time
                                                        $salary_parttime_work = BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=4,'',$option=3,$convert_ratio='',$dayLatches);
                                                        $salary_parttime_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=4,'',$option=3,$convert_ratio='',$dayLatches);
                                                        // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                        $money_awol_half_2 = -( $countAttendance_KP*$salary_parttime_default )/$standard_days;
                                                    }
                                                    $mulct_money_awol = $money_awol_half_1+$money_awol_half_2;
                                                }
                                            }

                                            if ($salary_official_work == 0) {
                                                $mulct_money_awol = $welfare_fund = 0;
                                            }

                                            $data_personnel_salary = [
                                                'salary_official_work'      => $salary_official_work,
                                                'salary_trial_work'         => $salary_trial_work,
                                                'salary_trainee_work'       => $salary_trainee_work,
                                                'salary_trainee_parttime_work'       => $salary_trainee_parttime_work,
                                                'salary_parttime_work'      => $salary_parttime_work,
                                                'total'                     => $salary_official_work+$salary_trial_work + $salary_trainee_work +$salary_parttime_work+$salary_trainee_parttime_work,
                                                'comment'                   =>  $value_3->value,
                                                'salary_official_default'   => $salary_official_default,
                                                'salary_trial_default'      => $salary_trial_default,
                                                'salary_trainee_default'    => $salary_trainee_default,
                                                'salary_trainee_parttime_default'    => $salary_trainee_parttime_default,
                                                'salary_parttime_default'   => $salary_parttime_default,
                                                'mulct_money_awol'          => $mulct_money_awol,
                                                'welfare_fund'              => $welfare_fund,
                                                'salary_leave'              =>  $money_leave,
                                                'salary_overtime'  =>  $salary_overtime,
                                            ];
 
                                            Salary::updatePersonnelSalary($data_personnel_salary,$checkPersonnelIncome->id);
                                            // unset($mulct_money_awol);
                                        }else{
                                            $check_salary = 0;
                                        }
                                    }

                                    //ĐI MUỘN
                                    if( $value_3->type == 10 ){
                                        if( $toh_contract_1 || $toh_contract && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                            $string = $value_3->value_id;
                                            $getLateAttendance = Salary::getCountLateAttendance( $month,$year,$value );
                                            $default_work_late = Parameters::where('id',0)->value('value');

                                            if( $param_contract_check == true ){
                                                $time = $checkContractSpecial->apply_to;
                                                $convert = explode("-",$time);
                                                foreach ($getLateAttendance as $key => $value) {
                                                    if( $value->attendance_day > (int)$convert[2]  ){
                                                        unset($getLateAttendance[$key]);
                                                    }
                                                }
                                                $type = 100;
                                            }else{
                                                $type = '';
                                            }

                                            
                                            // Nếu số lấn đi muốn >= số lần đi muộn trong phần setting thì tính tiền phạt đi muộn
                                            if( count($getLateAttendance) >= $default_work_late ){
                                                if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){//Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    $am=0;
                                                    foreach ($getLateAttendance as $key_LateAttendance => $value_LateAttendance) {
                                                        $am += $value_LateAttendance->time_late;
                                                    }
                                                    $money_work_late = BatvHelper::calculate($string,$value,$time,$type,$am,$option='',$convert_ratio='',$dayLatches);
                                                    
                                                    if( $checkContract == 1 ){// Thử việc
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=1 )['lcb'];
                                                    }elseif ( $checkContract == 2 ) {// Chính thức
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=2 )['lcb'];
                                                    }elseif ( $checkContract == 3 ) {// Thực tập fulltime
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=3 )['ltt'];
                                                    }elseif ( $checkContract == 5 ) {// Thực tập parttime
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=5 )['ltt'];
                                                    }else{// Part time
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=4 )['ltt'];
                                                    }
                                                    $money_work_late = ($money_work_late*$salary_agreement)/100;
                                                }else{// Nếu hợp động là nửa này nửa kia
                                                    $am=0;
                                                    foreach ($getLateAttendance as $key_LateAttendance => $value_LateAttendance) {
                                                        $am += $value_LateAttendance->time_late;
                                                    }
                                                    $money_work_late = BatvHelper::calculate($string,$value,$time,$type,$am,$option='',$convert_ratio='',$dayLatches);
                                                    if( $checkContract == 1 ){// Thử việc
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=1 )['lcb'];
                                                    }elseif ( $checkContract == 2 ) {// Chính thức
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=2 )['lcb'];
                                                    }elseif ( $checkContract == 3 ) {// Thực tập fulltime
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=3 )['ltt'];
                                                    }elseif ( $checkContract == 5 ) {// Thực tập parttime
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=5 )['ltt'];
                                                    }else{// Part time
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=4 )['ltt'];
                                                    }
                                                    $money_work_late= ($money_work_late/100)*$salary_agreement;
                                                }

                                            }else{
                                                $money_work_late = 0;
                                            }
                                        }else{
                                            $money_work_late = 0;
                                        }
                                        $data_personnel_money_work_late = [
                                            'money_work_late'       =>  $money_work_late,
                                        ];
                                        Salary::updatePersonnelSalary($data_personnel_money_work_late,$checkPersonnelIncome->id);
                                    }
                                    $data_total = [
                                        'check_salary'  => 1,
                                    ];
                                    Salary::updatePersonnelIncome($data_total,$checkPersonnelIncome->id);
                                }
                            }
                        }
                    }
                    $res=array('Response'=>"Success","Message"=>"Bạn đã cập nhật thành công" );
                }else{

                    if( $listPersonnel ){
                        foreach ($listPersonnel as $key => $value) {
                            $salary_overtime = 0;
                            $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                            $tmp = Salary::getPersonnelGroupDetailMuch($value,$type=[0,10]);
                            // echo "<pre>";
                            // print_r($tmp);die;
                            $tmp_2 = array();
                            foreach ($tmp as $key_1 => $value_1) {
                                $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

                            }
                            $data = array();
                            foreach ($tmp_2 as $k => $v) {
                                foreach ($tmp_2[$k] as $k_1 => $v_2) {
                                    $data[] = $v_2;
                                }

                            }
                            $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                            if( count( $checkPersonnelIncome ) >0 ){
                                $checkPersonnelSalary = Salary::checkPersonnelSalary($checkPersonnelIncome->id);
                                if( count( $checkPersonnelSalary )==0 ){
                                    $data_personnel_salary = [
                                        'personnel_income_id'   => $checkPersonnelIncome->id,
                                    ];
                                    Salary::insertPersonnelSalary($data_personnel_salary);
                                }
                            }else{
                                $data_personnel_income = [
                                    'month'         =>  $month,
                                    'year'          =>  $year,
                                    'date_value'    =>  $date_value,
                                    'personnel_id'  =>  $value,
                                    'created_at'    =>  date('Y-m-d'),
                                    'created_by'    => Auth::user()->id,
                                    'status'        => 1,
                                ];
                                Salary::insertPersonnelIncome($data_personnel_income);
                                $id =  \DB::getPdo()->lastInsertId(); 

                                $data_personnel_salary = [
                                    'personnel_income_id'   => $id,
                                ];

                                Salary::insertPersonnelSalary($data_personnel_salary);
                            }
                        $time = $year.'-'.$month;
                        $convert = explode("-",$time);
                        foreach ($data as $key_2 => $value_2) {
                            $type = 0;// Aplly cho Lương
                            //$tmp_3 = Salary::getIncomeConfigSalary($value_2->income_config_id,$type,$dateCurrent);
                            $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[0,10]);
                            if( count( $tmp_3 )>0 ){
                                $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                                $param = $convert[0].'-'.$convert[1].'-'.$numberDay;
                                $param_1 = $convert[0].'-'.$convert[1].'-01';
                                $toh_contract = Salary::checkContract( $value,$param );
                                $toh_contract_1 = Salary::checkContract( $value,$param_1 );
                                $param_contract_check = false;
                                if( $toh_contract ){
                                    $checkContract = $toh_contract->contract_id;
                                }else{
                                    $checkContractSpecial = Salary::checkContractSpecial($value);
                                    $checkContract = $checkContractSpecial->contract_id;
                                    $param_contract_check = true;
                                }
                                
                                if( $toh_contract_1 ){
                                    $checkContract_1 = Salary::checkContract( $value,$param_1 )->contract_id;
                                }else{
                                    $checkContract_1 = '';
                                }
                                $money_leave = $mulct_money_awol = $money_awol_half_1 = $money_awol_half_2 = 0;
                                foreach ($tmp_3 as $key_3 => $value_3) {
                                    if( $value_3->type == 0 ){
                                        // Sử dụng công thức áp dụng cho tháng tính lương đó
                                        if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                            $string = $value_3->value_id;
                                            /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/

                                            
                                            $welfare_fund = $salary_official_default = $salary_trial_default = $salary_parttime_default=$salary_trainee_default=$salary_trainee_parttime_default=$salary_official_work = $salary_trial_work = $salary_trainee_work = $salary_trainee_parttime_work= $salary_parttime_work=0;
                                            //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                            if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){
                                                if( $param_contract_check == true ){
                                                    $time = $checkContractSpecial->apply_to;
                                                    $convert = explode("-",$time);
                                                    $numberDay = $convert[2];
                                                }

                                                $countAttendance_CP = BatvHelper::countAttendance_CP($convert[1],$convert[0],1,$numberDay,$value) + BatvHelper::countTooLateAttendance( $month,$year,1,$numberDay,$value  );// số ngày nghỉ có phép + số buổi đi làm nhưng đi muộn > số phút setting sẽ bị trừ nửa ngày công
                                                $countAttendance_KP = BatvHelper::countAttendance_KP($convert[1],$convert[0],1,$numberDay,$value);// số ngày nghỉ ko phép
                                                if( $checkContract == 1 ){// Thử việc
                                                    $salary_trial_work = $percent_trial*BatvHelper::calculate($string,$value,$time,$type=1,'',$option=1,$convert_ratio='',$dayLatches);
                                                    $salary_trial_default =  BatvHelper::ltt('',$value,$time,$type=1,'',$option=1,$convert_ratio='',$dayLatches);
                                                    // TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                    $mulct_money_awol = -( $countAttendance_KP*$percent_trial*$salary_trial_default )/$standard_days;

                                                }elseif ( $checkContract == 2 ) {// Chính thức
                                                    
                                                    $salary_official_work = BatvHelper::calculate($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                    $salary_official_default = BatvHelper::ltt('',$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);// 100% chính thức
                                                    // Tính lương làm thêm ngoài giờ
                                                    $hour_overtime = Overtime::leftJoin('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
                                                                    ->where('overtime.personnel_id','=', $value)
                                                                    ->where('overtime_detail.score', 1)
                                                                    ->whereMonth('overtime_detail.time_day','=', $month)
                                                                    ->whereYear('overtime_detail.time_day','=', $year)
                                                                    ->sum('overtime_detail.hour');
                      
                                                    if ($hour_overtime > 0) {
                                                        $nctc =  BatvHelper::count_working_days($year.'-'.$month.'-01',$year.'-'.$month.'-'.$numberDay);
                                                        $salary_overtime = ( $hour_overtime * $salary_official_default/($nctc*8) );
                                                    }   

                                                    $flag = BatvHelper::checkPersonnelOut($value,$convert[1],$convert[0]);
                                                    $lbq = $salary_official_default/$standard_days;
                                                    if($flag){
                                                        $mulct_money_awol = 0;
                                                        if( $countAttendance_KP > 0 ){
                                                            $mulct_money_awol = -$countAttendance_KP*$lbq;
                                                        }
                                                    }else{
                                                        //Tính lương bình quân
                                                        Salary::updatePersonnelSalary(['salary_official_default'   => $salary_official_default],$checkPersonnelIncome->id);
                                                        $money_leave = BatvHelper::salary_leave('',$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);

                                                        $welfare_fund = BatvHelper::salary_basic_inline($salary_official_default)['qpl'];

                                                        // Xử lý TH nếu đ/c đó nghỉ 1 ngày (mất ngày nghỉ phép chứ chưa bị trừ tiên) + 1 ngày nữa (sẽ bị trừ theo lương cơ bản giống như nhận phép), nếu thêm x ngày nữa thì x ngày này bị trừ LBQ + TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                        $countNNP = BatvHelper::countNNP($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
       
                                                        if( $countNNP == 1 || $countNNP == 0.5 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $mulct_money_awol = $money_leave;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $mulct_money_awol = 0;
                                                            }
                                                        }else{
                                                            if( $countAttendance_CP == 0.5 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $mulct_money_awol = $money_leave;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $mulct_money_awol = -0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $mulct_money_awol = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP == 1 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $mulct_money_awol = $money_leave;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $mulct_money_awol = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $mulct_money_awol = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP == 1.5 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $mulct_money_awol = 0.5*$lbq - 0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $mulct_money_awol = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $mulct_money_awol = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            } elseif( $countAttendance_CP >= 2 ){
                                                                if( $countAttendance_KP == 0 ){
                                                                    $mulct_money_awol = $lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } elseif( $countAttendance_KP == 0.5 ){
                                                                    $mulct_money_awol = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                } else {
                                                                    $mulct_money_awol = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                                }
                                                            }
                                                        }
                                                    }

                                                }elseif ( $checkContract == 3 ) {// Thực tập fulltime
                                                    $salary_trainee_work = BatvHelper::calculate($string,$value,$time,$type=3,'',$option=1,$convert_ratio='',$dayLatches);
                                                    $salary_trainee_default = BatvHelper::ltt('',$value,$time,$type=3,'',$option=1,$convert_ratio='',$dayLatches);// 100% chính thức

                                                    // TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                    $mulct_money_awol = -( $countAttendance_KP*$salary_trainee_default )/$standard_days;

                                                }elseif ( $checkContract == 5 ) {// Thực tập partime
                                                    $salary_trainee_parttime_work = BatvHelper::calculate($string,$value,$time,$type=5,'',$option=1,$convert_ratio='',$dayLatches);
                                                    $salary_trainee_parttime_default = BatvHelper::ltt('',$value,$time,$type=5,'',$option=1,$convert_ratio='',$dayLatches);// 100% chính thức
                                                    // TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                    $mulct_money_awol = -( $countAttendance_KP*$salary_trainee_parttime_default )/$standard_days;
                                                }else{// Part time
                                                    $salary_parttime_work = BatvHelper::calculate($string,$value,$time,$type=4,'',$option=1,$convert_ratio='',$dayLatches);
                                                    $salary_parttime_default = BatvHelper::ltt('',$value,$time,$type=4,'',$option=1,$convert_ratio='',$dayLatches);
                                                    // TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP.
                                                    $mulct_money_awol = -( $countAttendance_KP*$salary_parttime_default )/$standard_days;
                                                }
                                            }else{  // NẾU HỢP ĐỘNG LÀ NỬA NÀY NỬA KIA

                                                $convert_apply_from = explode("-",$toh_contract->apply_from);
                                                $countAttendance_CP = BatvHelper::countAttendance_CP($convert_apply_from[1],$convert_apply_from[0],$convert_apply_from[2],$numberDay,$value)+ BatvHelper::countTooLateAttendance( $month,$year,$convert_apply_from[2],$numberDay,$value );// số ngày nghỉ có phép + số buổi đi làm nhưng đi muộn > số phút setting sẽ bị trừ nửa ngày công
                                                $countAttendance_KP = BatvHelper::countAttendance_KP($convert_apply_from[1],$convert_apply_from[0],$convert_apply_from[2],$numberDay,$value);// số ngày nghỉ ko phép

                                                if( $checkContract == 1 ){// Thử việc
                                                    $salary_trial_work =$percent_trial* BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=1,'',$option=2,$convert_ratio='',$dayLatches);
                                                    $salary_trial_default = BatvHelper::ltt('',$value,$toh_contract->apply_from,$type=1,'',$option=2,$convert_ratio='',$dayLatches); 
                                                    // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                    $money_awol_half_1 = -( $countAttendance_KP*$percent_trial*$salary_trial_default )/$standard_days;
                                                }elseif ( $checkContract == 2 ) {// 1/2 chính thức
                                                    
                                                    $salary_official_work = BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=2,'',$option=2,$convert_ratio='',$dayLatches);
                                                    //echo $salary_official_work;
                                                    $salary_official_default = BatvHelper::ltt( $string,$value,$toh_contract->apply_from,$type=2,'',$option=2,$convert_ratio='',$dayLatches );
                                                    // Tính lương làm thêm ngoài giờ
                                                    $hour_overtime = Overtime::leftJoin('overtime_detail', 'overtime_detail.over_time_id', '=', 'overtime.id')
                                                                    ->where('overtime.personnel_id','=', $value)
                                                                    ->where('overtime_detail.score', 1)
                                                                    ->whereMonth('overtime_detail.time_day','=', $month)
                                                                    ->whereYear('overtime_detail.time_day','=', $year)
                                                                    ->sum('overtime_detail.hour');
                      
                                                    if ($hour_overtime > 0) {
                                                        $nctc =  BatvHelper::count_working_days($year.'-'.$month.'-01',$year.'-'.$month.'-'.$numberDay);
                                                        $salary_overtime = ( $hour_overtime * $salary_official_default/($nctc*8) );
                                                    }   
                                                    //Tính lương bình quân
                                                    $lbq = $salary_official_default/$standard_days;
                                                    Salary::updatePersonnelSalary(['salary_official_default'   => $salary_official_default],$checkPersonnelIncome->id);
                                                    $money_leave = BatvHelper::salary_leave('',$value,$toh_contract->apply_from,$type=2,'',$option=2,$convert_ratio='',$dayLatches);
                                                    $countNNP = BatvHelper::countNNP($string,$value,$toh_contract->apply_from,$type=2,'',$option=2,$convert_ratio='',$dayLatches);
                                                    if( $countNNP == 1 || $countNNP == 0.5 ){
                                                        if( $countAttendance_KP == 0 ){
                                                            $money_awol_half_1 = $money_leave;
                                                        } elseif( $countAttendance_KP == 0.5 ){
                                                            $money_awol_half_1 = 0;
                                                        }
                                                    }else{

                                                        if( $countAttendance_CP == 0.5 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_1 = $money_leave;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_1 = -0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } else {
                                                                $money_awol_half_1 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            }
                                                        } elseif( $countAttendance_CP == 1 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_1 = $money_leave;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_1 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } else {
                                                                $money_awol_half_1 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            }
                                                        } elseif( $countAttendance_CP == 1.5 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_1 = 0.5*$lbq - 0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_1 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } else {
                                                                $money_awol_half_1 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            }
                                                        } elseif( $countAttendance_CP >= 2 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_1 = $lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_1 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } else {
                                                                $money_awol_half_1 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            }
                                                        }
                                                    }
                                                    

                                                }elseif ( $checkContract == 3 ) {// Thực tập fulltime
                                                    $salary_trainee_work = BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=3,'',$option=2,$convert_ratio='',$dayLatches);
                                                    $salary_trainee_default = BatvHelper::ltt( $string,$value,$toh_contract->apply_from,$type=3,'',$option=2,$convert_ratio='',$dayLatches);
                                                    // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                    $money_awol_half_1 = -( $countAttendance_KP*$salary_trainee_default )/$standard_days;  

                                                }elseif ( $checkContract == 5 ) {// Thực tập partime
                                                    $salary_trainee_parttime_work = BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=5,'',$option=2,$convert_ratio='',$dayLatches);
                                                    $salary_trainee_parttime_default = BatvHelper::ltt( $string,$value,$toh_contract->apply_from,$type=5,'',$option=2,$convert_ratio='',$dayLatches );
                                                    // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                    $money_awol_half_1 = -( $countAttendance_KP*$salary_trainee_parttime_default )/$standard_days; 

                                                }else{// Part time
                                                    $salary_parttime_work = BatvHelper::calculate($string,$value,$toh_contract->apply_from,$type=4,'',$option=2,$convert_ratio='',$dayLatches);
                                                    $salary_parttime_default = BatvHelper::ltt( $string,$value,$toh_contract->apply_from,$type=4,'',$option=2,$convert_ratio='',$dayLatches );
                                                    // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                    $money_awol_half_1 = -( $countAttendance_KP*$salary_parttime_default )/$standard_days; 

                                                }



                                                $convert_apply_to = explode("-",$toh_contract_1->apply_to);
                                                $countAttendance_CP = BatvHelper::countAttendance_CP($convert_apply_to[1],$convert_apply_to[0],1,$convert_apply_to[2],$value)+ BatvHelper::countTooLateAttendance( $month,$year,1,$convert_apply_to[2] ,$value);// số ngày nghỉ có phép + số buổi đi làm nhưng đi muộn > số phút setting sẽ bị trừ nửa ngày công
                                                $countAttendance_KP = BatvHelper::countAttendance_KP($convert_apply_to[1],$convert_apply_to[0],1,$convert_apply_to[2],$value);// số ngày nghỉ ko phép
                                                if( $checkContract_1 == 1 ){// Thử việc
                                                    $salary_trial_work =$percent_trial* BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=1,'',$option=3,$convert_ratio='',$dayLatches);
                                                    $salary_trial_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=1,'',$option=3,$convert_ratio='',$dayLatches);
                                                // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                    $money_awol_half_2 = -( $countAttendance_KP*$percent_trial*$salary_trial_default )/$standard_days;

                                                }elseif ( $checkContract_1 == 2 ) {// Chính thức
                                                    $salary_official_work = BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3,$convert_ratio='',$dayLatches);
                                                    $salary_official_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=2,'',$option=3,$convert_ratio='',$dayLatches);
                                                    $lbq = $salary_official_default/$standard_days;
                                                    Salary::updatePersonnelSalary(['salary_official_default'   => $salary_official_default],$checkPersonnelIncome->id);
                                                    $money_leave = BatvHelper::salary_leave('',$value,$toh_contract_1->apply_to,$type=2,'',$option=3,$convert_ratio='',$dayLatches);
                                                    //Tính xem nhân viên được hưởng 1 hay 1/2 ngày nghỉ phép được cấu hình trong tháng
                                                    $countNNP = BatvHelper::countNNP($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3,$convert_ratio='',$dayLatches);
                                                    if( $countNNP == 1 || $countNNP == 0.5 ){
                                                        if( $countAttendance_KP == 0 ){
                                                            $money_awol_half_2 = $money_leave;
                                                        } elseif( $countAttendance_KP == 0.5 ){
                                                            $money_awol_half_2 = 0;
                                                        }
                                                    }else{
                                                        if( $countAttendance_CP == 0.5 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_2 = $money_leave;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_2 = -0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } else {
                                                                $money_awol_half_2 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            }
                                                        } elseif( $countAttendance_CP == 1 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_2 = $money_leave;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_2 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } else {
                                                                $money_awol_half_2 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            }
                                                        } elseif( $countAttendance_CP == 1.5 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_2 = 0.5*$lbq - 0.5*BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_2 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } else {
                                                                $money_awol_half_2 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            }
                                                        } elseif( $countAttendance_CP >= 2 ){
                                                            if( $countAttendance_KP == 0 ){
                                                                $money_awol_half_2 = $lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } elseif( $countAttendance_KP == 0.5 ){
                                                                $money_awol_half_2 = 0.5*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            } else {
                                                                $money_awol_half_2 = -( $countAttendance_KP - 1 )*$lbq - BatvHelper::salary_basic_inline($salary_official_default)['lcb']/$standard_days;
                                                            }
                                                        }
                                                    }
                                                }elseif ( $checkContract_1 == 3 ) {// Thực tập fulltime
                                                    $salary_trainee_work = BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=3,'',$option=3,$convert_ratio='',$dayLatches);
                                                    $salary_trainee_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=3,'',$option=3,$convert_ratio='',$dayLatches);
                                                    // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                    $money_awol_half_2 = -( $countAttendance_KP*$salary_trainee_default )/$standard_days;
                                                }elseif ( $checkContract_1 == 5 ) {// Thực tập parttime
                                                    $salary_trainee_parttime_work = BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=5,'',$option=3,$convert_ratio='',$dayLatches);
                                                    $salary_trainee_parttime_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=5,'',$option=3,$convert_ratio='',$dayLatches);
                                                    // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                    $money_awol_half_2 = -( $countAttendance_KP*$salary_trainee_parttime_default )/$standard_days;
                                                }else{// Part time
                                                    $salary_parttime_work = BatvHelper::calculate($string,$value,$toh_contract_1->apply_to,$type=4,'',$option=3,$convert_ratio='',$dayLatches);
                                                    $salary_parttime_default = BatvHelper::ltt('',$value,$toh_contract_1->apply_to,$type=4,'',$option=3,$convert_ratio='',$dayLatches);
                                                    // -------TÍNH SỐ TIỀN PHẠT NẾU NGHỈ KHÔNG XIN PHÉP----------
                                                    $money_awol_half_2 = -( $countAttendance_KP*$salary_parttime_default )/$standard_days;
                                                }
                                                $mulct_money_awol = $money_awol_half_1+$money_awol_half_2;
                                            }

                                            
                                            $data_personnel_salary = [
                                                'salary_official_work'      => $salary_official_work,
                                                'salary_trial_work'         => $salary_trial_work,
                                                'salary_trainee_work'       => $salary_trainee_work,
                                                'salary_trainee_parttime_work'       => $salary_trainee_parttime_work,
                                                'salary_parttime_work'      => $salary_parttime_work,
                                                'total'                     => $salary_official_work+$salary_trial_work + $salary_trainee_work +$salary_parttime_work+$salary_trainee_parttime_work,
                                                'comment'                   =>  $value_3->value,
                                                'salary_official_default'   => $salary_official_default,
                                                'salary_trial_default'      => $salary_trial_default,
                                                'salary_trainee_default'    => $salary_trainee_default,
                                                'salary_trainee_parttime_default'    => $salary_trainee_parttime_default,
                                                'salary_parttime_default'   => $salary_parttime_default,
                                                'mulct_money_awol'          => $mulct_money_awol,
                                                'welfare_fund'              => $welfare_fund,
                                                'salary_leave'              =>  $money_leave,
                                                'salary_overtime'  =>  $salary_overtime,
                                            ];
                                            Salary::updatePersonnelSalary($data_personnel_salary,$checkPersonnelIncome->id);
                                            // unset($mulct_money_awol);
                                        }else{
                                            $check_salary = 0;
                                        }
                                    }

                                    //ĐI MUỘN
                                    if( $value_3->type == 10 ){
                                        if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                            $string = $value_3->value_id;
                                            $getLateAttendance = Salary::getCountLateAttendance( $month,$year,$value );
                                            $default_work_late = Parameters::where('id',0)->value('value');

                                            if( $param_contract_check == true ){
                                                $time = $checkContractSpecial->apply_to;
                                                $convert = explode("-",$time);
                                                foreach ($getLateAttendance as $key => $value) {
                                                    if( $value->attendance_day > (int)$convert[2]  ){
                                                        unset($getLateAttendance[$key]);
                                                    }
                                                }
                                                $type = 100;
                                            }else{
                                                $type = '';
                                            }

                                            
                                            // Nếu số lấn đi muốn >= số lần đi muộn trong phần setting thì tính tiền phạt đi muộn
                                            if( count($getLateAttendance) >= $default_work_late ){
                                                if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){//Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    $am=0;
                                                    foreach ($getLateAttendance as $key_LateAttendance => $value_LateAttendance) {
                                                        $am += $value_LateAttendance->time_late;
                                                    }
                                                    $money_work_late = BatvHelper::calculate($string,$value,$time,$type,$am,$option='',$convert_ratio='',$dayLatches);
                                                    
                                                    if( $checkContract == 1 ){// Thử việc
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=1 )['lcb'];
                                                    }elseif ( $checkContract == 2 ) {// Chính thức
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=2 )['lcb'];
                                                    }elseif ( $checkContract == 3 ) {// Thực tập fulltime
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=3 )['ltt'];
                                                    }elseif ( $checkContract == 5 ) {// Thực tập parttime
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=5 )['ltt'];
                                                    }else{// Part time
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=4 )['ltt'];
                                                    }
                                                    $money_work_late = ($money_work_late*$salary_agreement)/100;
                                                }else{// Nếu hợp động là nửa này nửa kia
                                                    $am=0;
                                                    foreach ($getLateAttendance as $key_LateAttendance => $value_LateAttendance) {
                                                        $am += $value_LateAttendance->time_late;
                                                    }
                                                    $money_work_late = BatvHelper::calculate($string,$value,$time,$type,$am,$option='',$convert_ratio='',$dayLatches);
                                                    if( $checkContract == 1 ){// Thử việc
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=1 )['lcb'];
                                                    }elseif ( $checkContract == 2 ) {// Chính thức
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=2 )['lcb'];
                                                    }elseif ( $checkContract == 3 ) {// Thực tập fulltime
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=3 )['ltt'];
                                                    }elseif ( $checkContract == 5 ) {// Thực tập parttime
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=5 )['ltt'];
                                                    }else{// Part time
                                                        $salary_agreement = BatvHelper::salary_basic( $value,$time,$type=4 )['ltt'];
                                                    }
                                                    $money_work_late= ($money_work_late/100)*$salary_agreement;
                                                }

                                            }else{
                                                $money_work_late = 0;
                                            }
                                        }else{
                                            $money_work_late = 0;
                                        }
                                        $data_personnel_money_work_late = [
                                            'money_work_late'       =>  $money_work_late,
                                        ];
                                        Salary::updatePersonnelSalary($data_personnel_money_work_late,$checkPersonnelIncome->id);
                                    }
                                    $data_total = [
                                        'check_salary'  => 1,
                                    ];
                                    Salary::updatePersonnelIncome($data_total,$checkPersonnelIncome->id);
                                }
                            }
                        }
                    }
                        $res=array('Response'=>"Success","Message"=>"Bạn đã tính lương thành công " );
                        // echo 1;die;
                    }else{
                        $res=array('Response'=>"Error","Error"=>"Bạn chưa chấm công trong tháng cần tính đó" );
                    }
                    // echo 1;die;
                }
            }else{
                $res=array('Response'=>"Error","Error"=>"Chưa có công thức tính lương trong khoảng thời gian trên hoặc Công thức đã bị xóa" );
            }

            echo json_encode($res);die;
        }
    }

    public function getSalaryBonusDoneAjax( Request $request ){
        if( $request->ajax() ){
            $arr = [
                'status_bonus' => 0
            ];

            Salary::updateStatusPersonnelIncome($arr,$request->selectMonth,$request->selectYear);
            $res=array('Response'=>"Success","Message"=>"Bạn đã thực hiện thành công " );
            echo json_encode($res);
        }

    }

    public function getSalaryDoneAjax( Request $request ){
        if( $request->ajax() ){
            set_time_limit(500);
            $arr = [
                'status' => 0,
            ];
            Salary::updateStatusPersonnelIncome($arr,$request->selectMonth,$request->selectYear);

            // Update thanh toán định kỳ cho những nhân viên vay vốn chọn hình thức trả nợ = lương

            $listAllSalary = Salary::listAllSalaryPayMonthLoanCapital($request);

            $others = array();
            $data = Salary::listSalaryOther($request);

            foreach ($data as $key => $value) {
                if( !isset($others['list'][$value->personnel_id]->fullname) ){
                    $others['list'][$value->personnel_id]['fullname'] = $value->fullname;
                    $others['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                }else{
                    $others['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                }

            }

            $list_pay_month_loan_captial = [];

            foreach ($listAllSalary as $val) {
                $loan_capital = LoanCapital::where('id', $val->loan_capital_id)->where('status', 2)->first();

                if ($loan_capital) {
                    $list_pay_month_loan_captial[$val->loan_capital_id] = $val->fullname;
                    $total_item = 0;

                    foreach ( $others['list'][$val->personnel_id]['income_value'] as $v ) {
                        if( !empty($v) ) {
                            $total_item += $v;
                        }
                    }

                    $pay_month_loan_capital = $val->principal + $val->interest + $val->interest_incurred + $val->wanting_month_prev_money - $val->redundancy_month_prev_money;
                    $salary = $val->salary_overtime +  $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus - $val->party_fee;
                    // echo $val->salary_overtime.'<br>';
                    // echo $val->salary_official_work.'<br>';die;
                    // echo $val->management_allowance.'<br>';
                    // echo $val->lunch_allowance.'<br>';
                    // echo $val->travel_allowance.'<br>';
                    // echo $val->phone_allowance.'<br>';
                    // echo $val->work_bonus.'<br>';
                    // echo $val->insurance.'<br>';
                    // echo $val->money_work_late.'<br>';
                    // echo $val->welfare_fund.'<br>';
                    // echo $val->parking_fee_allowance.'<br>';
                    // echo $val->other_tax_allowance.'<br>';
                    // echo  $val->laptop_allowance.'<br>';
                    // echo $val->mulct_money_awol.'<br>';
                    // echo $val->holiday_bonus.'<br>';
                    // echo $val->party_fee;die;
                    $paid_money = ($pay_month_loan_capital <= $salary) ? $pay_month_loan_capital : $salary;
                    $paid_money = ($paid_money < 0) ? 0 : $paid_money;

                    $loan_capital_id = $loan_capital->id;
                    $history_pay_loan_capital = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', $val->history_pay_loan_capital_month)->first();
                    $history_pay_loan_capital->received_date = date('Y-m-d');
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
                            $history_pay_loan_capital_next = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', $history_pay_loan_capital->month + 1)->first();
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
                            if ($history_pay_loan_capital->month >= $loan_capital->month_time) {
                                $loan_capital->status = 4;
                                $loan_capital->save();
                            }
                        }
                    }
                }
            }
            // Gửi mail
            $email_cc = Auth::user()->email;
            $infoConfigMailSetting = EmailConfig::getInfoEmailConfig($type = 11);
            $listEmail = EmailConfig::getListEmailbyidPersonnel( explode(",",$infoConfigMailSetting->mail_to) );
            $email = [];

            if( $listEmail ){
                foreach ($listEmail as $k => $v) {
                    $email[] = $v->email;
                }
            }

            $subject = '[HR] Thông báo v/v chốt lương tháng';

            $content_mail = array(
                                'data' => $infoConfigMailSetting,
                                'mail_subject' => 'Tháng chốt lương: '. $request->selectMonth .'/'.$request->selectYear .'',
                            );
            \Mail::send('emails.notification_latch_month_loan_capital', $content_mail, function($message) use ($email, $subject) {
                $message->from('nhansu@tohsoft.com', 'TOH');
                // $message->cc($email_cc);
                $message->to($email)->subject($subject);
            });

            // Gửi email notify tới admin khi có bất kỳ thay đổi nào về hệ số, phụ cấp của một nv. note rõ mức cũ, mức mới ...
   
            // $arr_compare = [];
            // $list_change_salary = [];
            // // Tháng gần nhất
            // if( $request->selectMonth != "" ){
            //     $month =  ( $request->selectMonth < 10)?'0'.$request->selectMonth:$request->selectMonth;
            //     $year  =  $request->selectYear;
            //     $dateCurrent = $year."-".$month."-"."01";
            // }else{
            //     $dateCurrent = date('Y-m-d');
            //     $month = date('m');
            //     $year  = date('Y');
            // }

            // $data_ratio_nearest = \DB::table('personnel_job_ratio')
            //                     ->whereDate('apply_from', '<=', $dateCurrent)
            //                     ->whereDate('apply_to', '>=', $dateCurrent)
            //                     ->pluck('ratio', 'personnel_ID');

            // $date_value = $year.'-'.$month.'-01';
            // $time = $year.'-'.$month;
            // $convert = explode("-",$time);
            // $infoParameters = Salary::infoParameters();
            // $listPersonnel = Personnel::getPersonnelSalary($month,$year,$year."-".$month);

            // foreach ($listPersonnel as $key => $value) {
            //     $tmp = Salary::getPersonnelGroupDetailMuch($value,$type=[3,4,5,6,13,15,16,18,19]);
            //     $tmp_2 = array();

            //     foreach ($tmp as $key_1 => $value_1) {
            //         $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

            //     }

            //     $data = array();
            //     foreach ($tmp_2 as $k => $v) {
            //         foreach ($tmp_2[$k] as $k_1 => $v_2) {
            //             $data[] = $v_2;
            //         }

            //     }

            //     $data = array_map("unserialize", array_unique(array_map("serialize", $data)));

            //     foreach ($data as $key_2 => $value_2) {
            //         $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[3,4,5,6,13,15,16,18,19]);
            //         if( count( $tmp_3 )>0 ){
            //             $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
            //             foreach ($tmp_3 as $key_3 => $value_3) { 
            //                 //Tiền trợ cấp nhà ở
            //                 if( $value_3->type == 18 ){
            //                     $subsidize_house = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $subsidize_house = $tmp_3[0]->value;
            //                         }
            //                     }

            //                     $arr_compare[$value]['subsidize_house'] = $subsidize_house;
            //                 }

            //                 //Phụ cấp ăn trưa
            //                 if( $value_3->type == 3 ){
            //                     $lunch_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $lunch_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $lunch_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }

            //                     $arr_compare[$value]['lunch_allowance'] = $lunch_allowance;

            //                 }

            //                 //Phụ cấp đi lại
            //                 if( $value_3->type == 4 ){
            //                     $travel_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $travel_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $travel_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }
            //                     $arr_compare[$value]['travel_allowance'] = $travel_allowance;
            //                 }
            //                 // //Phụ cấp điện thoại
            //                 if( $value_3->type == 5 ){
            //                     $phone_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $phone_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $phone_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }
            //                     $arr_compare[$value]['phone_allowance'] = $phone_allowance;
            //                 }

            //                 //Phụ cấp trách nhiệm
            //                 if( $value_3->type == 6 ){
            //                     $management_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $management_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $management_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }
            //                     $arr_compare[$value]['management_allowance'] = $management_allowance;
            //                 }


            //                 // //Phụ cấp tiền gửi xe
            //                 if( $value_3->type == 13 ){
            //                     $parking_fee_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $parking_fee_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $parking_fee_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }
            //                     $arr_compare[$value]['parking_fee_allowance'] = $parking_fee_allowance;
            //                 }

            //                 //Phụ cấp nếu không tham gia bảo hiểm
            //                 if( $value_3->type == 15 ){
            //                     $other_tax_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $other_tax_allowance = $tmp_3[0]->value;
            //                         }else{
            //                         }
            //                     }
            //                     $arr_compare[$value]['other_tax_allowance'] = $other_tax_allowance;
            //                 }

            //                 //Phụ cấp nếu sử dụng Laptop cá nhân
            //                 if( $value_3->type == 16 ){
            //                     $laptop_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $laptop_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $laptop_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }
            //                     $arr_compare[$value]['laptop_allowance'] = $laptop_allowance;
            //                 }
            //             }
            //         }
            //     }
            // }

            // Tháng trước đó
            // $dateCurrent = date("Y-m-d", strtotime("-1 month", strtotime($dateCurrent)));
            // $month = explode('-', $dateCurrent);
            // $month = $month[1];
            // foreach ($listPersonnel as $key => $value) {
            //     $tmp = Salary::getPersonnelGroupDetailMuch($value,$type=[3,4,5,6,13,15,16,18,19]);
            //     $tmp_2 = array();

            //     foreach ($tmp as $key_1 => $value_1) {
            //         $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

            //     }

            //     $data = array();
            //     foreach ($tmp_2 as $k => $v) {
            //         foreach ($tmp_2[$k] as $k_1 => $v_2) {
            //             $data[] = $v_2;
            //         }

            //     }

            //     $data = array_map("unserialize", array_unique(array_map("serialize", $data)));

            //     foreach ($data as $key_2 => $value_2) {
            //         $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[3,4,5,6,13,15,16,18,19]);
            //         if( count( $tmp_3 )>0 ){
            //             $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
            //             foreach ($tmp_3 as $key_3 => $value_3) { 
            //                 //Tiền trợ cấp nhà ở
            //                 if( $value_3->type == 18 ){
            //                     $subsidize_house = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $subsidize_house = $tmp_3[0]->value;
            //                         }
            //                     }

            //                     if ($arr_compare[$value]['subsidize_house'] != $subsidize_house) {
            //                         $list_change_salary[$value]['subsidize_house']['new'] = $arr_compare[$value]['subsidize_house'];
            //                         $list_change_salary[$value]['subsidize_house']['old'] = $subsidize_house;
            //                     }
            //                 }

            //                 //Phụ cấp ăn trưa
            //                 if( $value_3->type == 3 ){
            //                     $lunch_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $lunch_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $lunch_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }

            //                     if (isset($arr_compare[$value]['lunch_allowance']) && $arr_compare[$value]['lunch_allowance'] != $lunch_allowance) {
            //                         $list_change_salary[$value]['lunch_allowance']['new'] = $arr_compare[$value]['lunch_allowance'];
            //                         $list_change_salary[$value]['lunch_allowance']['old'] = $lunch_allowance;
            //                     }

            //                 }

            //                 //Phụ cấp đi lại
            //                 if( $value_3->type == 4 ){
            //                     $travel_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $travel_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $travel_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }

            //                     if ($arr_compare[$value]['travel_allowance'] != $travel_allowance) {
            //                         $list_change_salary[$value]['travel_allowance']['new'] = $arr_compare[$value]['travel_allowance'];
            //                         $list_change_salary[$value]['travel_allowance']['old'] = $travel_allowance;
            //                     }
            //                 }
            //                 // //Phụ cấp điện thoại
            //                 if( $value_3->type == 5 ){
            //                     $phone_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $phone_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $phone_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }

            //                     if ($arr_compare[$value]['phone_allowance'] != $phone_allowance) {
            //                         $list_change_salary[$value]['phone_allowance']['new'] = $arr_compare[$value]['phone_allowance'];
            //                         $list_change_salary[$value]['phone_allowance']['old'] = $phone_allowance;
            //                     }
            //                 }

            //                 //Phụ cấp trách nhiệm
            //                 if( $value_3->type == 6 ){
            //                     $management_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $management_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $management_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }

            //                     if ($arr_compare[$value]['management_allowance'] != $management_allowance) {
            //                         $list_change_salary[$value]['management_allowance']['new'] = $arr_compare[$value]['management_allowance'];
            //                         $list_change_salary[$value]['management_allowance']['old'] = $management_allowance;
            //                     }
            //                 }


            //                 // //Phụ cấp tiền gửi xe
            //                 if( $value_3->type == 13 ){
            //                     $parking_fee_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $parking_fee_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $parking_fee_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }

            //                     if ($arr_compare[$value]['parking_fee_allowance'] != $parking_fee_allowance) {
            //                         $list_change_salary[$value]['parking_fee_allowance']['new'] = $arr_compare[$value]['parking_fee_allowance'];
            //                         $list_change_salary[$value]['parking_fee_allowance']['old'] = $parking_fee_allowance;
            //                     }
            //                 }

            //                 //Phụ cấp nếu không tham gia bảo hiểm
            //                 if( $value_3->type == 15 ){
            //                     $other_tax_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $other_tax_allowance = $tmp_3[0]->value;
            //                         }
            //                     }

            //                     if ($arr_compare[$value]['other_tax_allowance'] != $other_tax_allowance) {
            //                         $list_change_salary[$value]['other_tax_allowance']['new'] = $arr_compare[$value]['other_tax_allowance'];
            //                         $list_change_salary[$value]['other_tax_allowance']['old'] = $other_tax_allowance;
            //                     }
            //                 }

            //                 //Phụ cấp nếu sử dụng Laptop cá nhân
            //                 if( $value_3->type == 16 ){
            //                     $laptop_allowance = 0;
            //                     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
            //                         if( $tmp_3[0]->is_fixed==1 ){
            //                             $laptop_allowance = $tmp_3[0]->value;
            //                         }else{
            //                             $string = $value_3->value_id;
            //                             $laptop_allowance = BatvHelper::calculateSpecial_2($string,$value,$time='',$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
            //                         }
            //                     }
            //                     if ($arr_compare[$value]['laptop_allowance'] != $laptop_allowance) {
            //                         $list_change_salary[$value]['laptop_allowance']['new'] = $arr_compare[$value]['laptop_allowance'];
            //                         $list_change_salary[$value]['laptop_allowance']['old'] = $laptop_allowance;
            //                     }

            //                 }
            //             }
            //         }
                    
            //     }
            // }

            // // So sanh hệ số
            // $data_ratio_before = \DB::table('personnel_job_ratio')
            //                     ->whereDate('apply_from', '<=', $dateCurrent)
            //                     ->whereDate('apply_to', '>=', $dateCurrent)
            //                     ->pluck('ratio', 'personnel_ID');

            // foreach ($data_ratio_before as $key => $value) {
            //     if (isset($data_ratio_nearest[$key]) && $data_ratio_nearest[$key] != $value) {
            //         $list_change_salary[$key]['ratio']['old'] = $value;
            //         $list_change_salary[$key]['ratio']['new'] = $data_ratio_nearest[$key];
            //     }
            // }

            // $data_personnel = Personnel::pluck('fullname', 'id')->toArray();
            // foreach ($list_change_salary as $key => $value) {
            //     $list_change_salary[$key]['fullname'] = $data_personnel[$key];
            // }

            // if (count($list_change_salary) > 0) {
            //     $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 12 );
            //     $listEmail = EmailConfig::getListEmailbyidPersonnel( explode(",",$infoConfigMail->mail_to) );
            //     $email = [];
        
            //     if( $listEmail ){
            //         foreach ($listEmail as $key => $value) {
            //             $email[] = $value->email;
            //         }
            //     }

            //     $subject = $infoConfigMail->mail_subject;
            //     $content_mail = array(
            //                         'data'=>$list_change_salary,
            //                     );
            //     \Mail::send('emails.notification_change_salary', $content_mail,  function ($message) use ($email, $subject) {
            //         $message->from('nhansu@tohsoft.com', 'TOH');
            //         $message->to($email)->subject($subject);
            //     });
            // }
            $res=array('Response'=>"Success","Message"=>"Bạn đã thực hiện thành công " );
            echo json_encode($res);
        }
    }

    public function getSalaryRecalCulationAjax( Request $request ){
        if( $request->ajax() ){
            $arr = [
                'status' => 1,
            ];
            Salary::updateStatusPersonnelIncome($arr,$request->selectMonth,$request->selectYear);

            $listAllSalary = Salary::listAllSalaryPayMonthLoanCapitalRecalCulation($request);
            $list_pay_month_loan_captial = [];

            foreach ($listAllSalary as $val) {
                $loan_capital = LoanCapital::where('id', $val->loan_capital_id)->where('status', 2)->first();

                if ($loan_capital) {
                    $loan_capital_id = $loan_capital->id;
                    $history_pay_loan_capital = HistoryPayLoanCapital::where('loan_capital_id', $loan_capital_id)->where('month', $val->history_pay_loan_capital_month)->first();
                    $history_pay_loan_capital->received_date = null;
                    $history_pay_loan_capital->paid_money = 0;
                    $history_pay_loan_capital->status = 0;
                    $history_pay_loan_capital->save();
                }
            }

            $res=array('Response'=>"Success","Message"=>"Bạn đã thực hiện thành công" );
            echo json_encode($res);
        }
    }
    // Cấu hình ngày nghỉ phép
    public function getLeaveConfig(Request $request){
        $data = Salary::listLeaveSetting($request);
        return view('layouts.luongthuong.cauhinhngaynghiphep.index',['data'=>$data] );
    }

    public function addLeave(){
        return view('layouts.luongthuong.cauhinhngaynghiphep.add');
    }

    public function postLeaveAdd(Request $request){
        Validator::extend('check_leave_detail', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          $check = Salary::checkDaysLeaveSetting($data['year']);
          // echo "<pre>";
          // print_r($check);die;
          foreach ($data['month'] as $key => $value) {
              if( in_array($value, $check) ){
                return false;
              }else{
                return true;
              }
          };   
          //return ( $check > 0 )? false:true;
        });


        $rules = [
            'title' =>'required',
            'number_days' =>'required',
            'month' =>'required|check_leave_detail',
            
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'number_days.required' => 'Số ngày phép không được để trống',
            'month.required' => 'Bạn chưa chọn tháng',
            'month.check_leave_detail' => 'Có tháng đã được cài đặt',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $arr = [
                'title' =>  $request->title,
                'year' =>  $request->year,
                'number_days' =>  $request->number_days,
                'created_at' => date('Y-m-d'),
                'created_by' => Auth::user()->id,
                'status'    =>1
            ];
            Salary::insertLeaveSetting($arr);
            $id = \DB::getPdo()->lastInsertId();
            $data = array();
            foreach ($request->month as $key => $value) {
                  $data[] = [
                            'id_days_leave_setting'=>$id,
                            'month' =>  $value,
                        ];
            }  
            Salary::insertLeaveSettingDetail($data);
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }

    }

    public function editLeave($id){
        $data = Salary::getInfoLeaveSetting($id);
        $data_detail = Salary::getInfoLeaveSettingDetail($id);
        $arr = array();
        foreach ($data_detail as $key => $value) {
            $arr[$value->month] = array(
                    'month'=> $value->month,
                );

        }
        return view('layouts.luongthuong.cauhinhngaynghiphep.edit',['data'=>$data,'data_detail'=>$arr]);
    }

    public function postLeaveEdit( Request $request ){
        Validator::extend('check_leave_detail', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          $id = $parameters[0];
          $check = Salary::checkDaysLeaveSetting( $data['year'],$id );
          $tmp = 0;
          foreach ($data['month'] as $key => $value) {
              if( in_array($value, $check) ){
                $tmp++;
              }
          };   
          return ( $tmp > 0 )? false:true;
        });


        $rules = [
            'title' =>'required',
            'number_days' =>'required',
            'month' =>'required|check_leave_detail:'.$request->id,
            
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'number_days.required' => 'Số ngày phép không được để trống',
            'month.required' => 'Bạn chưa chọn tháng',
            'month.check_leave_detail' => 'Có tháng đã được cài đặt',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $arr = [
                'title' =>  $request->title,
                'year' =>  $request->year,
                'number_days' =>  $request->number_days,
                'updated_at' => date('Y-m-d'),
                'updated_by' => Auth::user()->id,
            ];

            Salary::updateLeaveSetting($arr,$request->id);  

            Salary::deleteLeaveSettingDetail($request->id);
            $data = array();
            foreach ($request->month as $key => $value) {
                  $data[] = [
                            'id_days_leave_setting'=>$request->id,
                            'month' =>  $value,
                        ];
            }  
            Salary::insertLeaveSettingDetail($data);
            return back()->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }

    }

    public function deleteLeave($id){
        $arr = [
            'status' =>  0,
        ];

        Salary::updateLeaveSetting($arr,$id); 
        return back()->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

    // Cấu hình ngày nghỉ lễ
    public function getHolidaysConfig(Request $request){
        $data = Salary::listHolidaySetting($request);
        return view('layouts.luongthuong.cauhinhngaynghile.index',['data'=>$data] );
    }

    public function addHolidays(){
        return view('layouts.luongthuong.cauhinhngaynghile.add');
    }

    public function postHolidaysAdd(Request $request){
        Validator::extend('check_date', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          if( $data['selectYear'] != "*" ){
             if( checkdate($data['selectMonth'],$data['selectDay'],$data['selectYear']) ){
                return true;
             }else{
                return false;
             }
          }else{
            return true;
          }
        });
        $rules = [
            'title' =>'required',
            'selectYear'=>'check_date'
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'selectYear.check_date' => 'Thời gian không hợp lệ',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $arr = [
                'title' =>  $request->title,
                'day' =>  $request->selectDay,
                'month' =>  $request->selectMonth,
                'year' =>  $request->selectYear,
                'reason' =>  $request->reason,
                'created_at' => date('Y-m-d'),
                'created_by' => Auth::user()->id,
                'status'    =>1
            ];
            Salary::insertHolidaySetting($arr);  
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }

    }

    public function editHolidays($id){
        $data = Salary::getInfoHolidaySetting($id);
        return view('layouts.luongthuong.cauhinhngaynghile.edit',['data'=>$data]);
    }

    public function postHolidaysEdit(Request $request,$id){
        Validator::extend('check_date', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          if( $data['selectYear'] != "*" ){
             if( checkdate($data['selectMonth'],$data['selectDay'],$data['selectYear']) ){
                return true;
             }else{
                return false;
             }
          }else{
            return true;
          }
        });
        $rules = [
            'title' =>'required',
            'selectYear'=>'check_date'
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'selectYear.check_date' => 'Thời gian không hợp lệ',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $arr = [
                'title' =>  $request->title,
                'day' =>  $request->selectDay,
                'month' =>  $request->selectMonth,
                'year' =>  $request->selectYear,
                'reason' =>  $request->reason,
                'created_at' => date('Y-m-d'),
                'created_by' => Auth::user()->id,
                'status'    =>1
            ];
            Salary::updateHolidaySetting($arr,$id);  
            return back()->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function deleteHolidays($id){
        $arr = [
            'status'     => 0
        ];
        Salary::updateHolidaySetting($arr,$id);  
        return redirect()->route('getHolidaysConfig')->with(['flash_message_succ' => 'Bạn đã xóa thành công']);
    }

    // Thưởng và phụ cấp
    public function getAllowance(Request $request){
        $data = Salary::listBonus($request);
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.luongthuong.server.phucap',['data'=>$data]);
    }

    public function getAllowanceAjax(Request $request){
        set_time_limit(5000);
        if( $request->ajax() ){
            if( $request->selectMonth != "" ){
                $month =  ( $request->selectMonth < 10)?'0'.$request->selectMonth:$request->selectMonth;
                $year  =  $request->selectYear;
                $dateCurrent = $year."-".$month."-"."01";
            }else{
                $dateCurrent = date('Y-m-d');
                $month = date('m');
                $year  = date('Y');
            }
            $date_value = $year.'-'.$month.'-01';
            $dayLatches = ( !empty($request->dayLatches) )? BatvHelper::formatDate($request->dayLatches,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",false) : '';
            // echo $dayLatches;die;
            // $checkTimeApplyMuch = Salary::checkTimeApplyMuch($dateCurrent,$type=[1,2,3,4,5,6]);
            // if( count($checkTimeApplyMuch) >=6 ){

                $time = $year.'-'.$month;
                $convert = explode("-",$time);
                $check_bonus = Salary::checkPersonnelIncome($month,$year,$type='check_bonus');
                // echo count($check_bonus);die;
                $infoParameters = Salary::infoParameters();
                $listPersonnel = Personnel::getPersonnelSalary($month,$year,$year."-".$month);
                // echo "<pre>";
                // print_r($listPersonnel);die;
                if( count( $check_bonus ) >0 ){
                    foreach ($listPersonnel as $key => $value) {
                        $movement_allowance = 0;
                        $holiday_bonus=$work_bonus=$subsidize_house=$lunch_allowance=$travel_allowance=$phone_allowance= $management_allowance=$parking_fee_allowance=$other_tax_allowance=$laptop_allowance=0;
                        $check = Salary::checkPersonnelIncome($month,$year,$type='',$value);

                        if (BatvHelper::checkSubsidize($value, $month, $year)) {
                            /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                            $checkTrialWork = Salary::checkTrialWorkDetail( $type=1,$value ); // $type=1 : Hợp đồng thử việc
                            //Nếu có hợp đồng thử việc
                            if( $checkTrialWork >0 ){
                                $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                                $param = $convert[0].'-'.$convert[1].'-'.$numberDay;
                                // echo $param;die;
                                $checkTrialWorkDetail = Salary::checkTrialWorkDetail( $type=1,$value,$param);
                                $special_check = 1;
                            }else{
                                $special_check = 0;
                            }
                            $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);

                            $tmp = Salary::getPersonnelGroupDetailMuch($value,$type=[1,2,3,4,5,6,13,15,16,17,18,19]);

                            $tmp_2 = array();
                            foreach ($tmp as $key_1 => $value_1) {
                                $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

                            }

                            $data = array();
                            foreach ($tmp_2 as $k => $v) {
                                foreach ($tmp_2[$k] as $k_1 => $v_2) {
                                    $data[] = $v_2;
                                }

                            }

                            $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                            // update tháng chốt lương thưởng Tết nếu có
                            if(!$checkPersonnelIncome) {
                                $data_personnel_income = [
                                    'month'         =>  $month,
                                    'year'          =>  $year,
                                    'date_value'    => $date_value,
                                    'personnel_id'  =>  $value,
                                    'day_latches'   => $dayLatches,
                                    'created_at'    =>  date('Y-m-d'),
                                    'created_by'    => Auth::user()->id,
                                    'status'        => 1,
                                ];
                                Salary::insertPersonnelIncome($data_personnel_income);
                                $id =  \DB::getPdo()->lastInsertId(); 

                                $data_personnel_bonus = [
                                    'personnel_income_id'   => $id,
                                ];

                                Salary::insertPersonnelBonus($data_personnel_bonus);

                                $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                            }

                            Salary::updatePersonnelIncome(['day_latches'=>$dayLatches],$checkPersonnelIncome->id);

                            Salary::deletePersonnelBonus($checkPersonnelIncome->id);
                            $data_personnel_bonus = [
                                'personnel_income_id'   => $checkPersonnelIncome->id,
                            ];
                            Salary::insertPersonnelBonus($data_personnel_bonus);
                    
                            foreach ($data as $key_2 => $value_2) {
                                $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[1,2,3,4,5,6,13,15,16,17,18,19]);

                                if( count( $tmp_3 )>0 ){
                                    $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                                    $param = $convert[0].'-'.$convert[1].'-'.$numberDay;
                                    $param_1 = $convert[0].'-'.$convert[1].'-01';
                                    $toh_contract = Salary::checkContract( $value,$param );
                                    $toh_contract_1 = Salary::checkContract( $value,$param_1 );
                                    $param_contract_check = $checkContractSpecial = false;
                                    if( $toh_contract ){
                                        $checkContract = $toh_contract->contract_id;
                                    }else{
                                        $checkContractSpecial = Salary::checkContractSpecial($value);
                                        $checkContract = $checkContractSpecial->contract_id;
                                        $param_contract_check = true;
                                    }
                                    
                                    if( $toh_contract_1 ){
                                        $checkContract_1 = Salary::checkContract( $value,$param_1 )->contract_id;
                                    }else{
                                        $checkContract_1 = '';
                                    }
                                    foreach ($tmp_3 as $key_3 => $value_3) { 



                                        // Thưởng ngày lễ
                                        if( $value_3->type == 1 ){
                                            if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $holiday_bonus = $tmp_3[0]->value;
                                                } else {
                                                    $string = $value_3->value_id;
                                                    $holiday_bonus = BatvHelper::calculate($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches); 

                                                    // if ($holiday_bonus < 1000000) {
                                                    //    $holiday_bonus = 1000000;
                                                    // } elseif($holiday_bonus > 1000000 && $holiday_bonus < 2000000) {
                                                            // $holiday_bonus = 2000000;
                                                    // } 
                                                    
                                                }

                                                $data_personnel_bonus = [
                                                    'holiday_bonus'       =>  $holiday_bonus,
                                                ];
                                                // echo $value . "---" . $holiday_bonus . "<br>";
                                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                            }
                                        }

                                         //Thưởng ngày lễ
                                        // if( $value_3->type == 1 ){
                                        //     if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                        //         if( $tmp_3[0]->is_fixed==1 ){
                                        //             $holiday_bonus = $tmp_3[0]->value;
                                        //         }else{
                                        //             $convertTet = explode("-",$dayLatches);
                                        //             $dayEnd = (int)$convertTet[2];
                                        //             $monthEnd = (int)$convertTet[1];
                                        //             $yearEnd = (int)$convertTet[0];

                                        //             $infoNumberLcbInYear = Personnel::infoNumberLcbInYear($value,$convertTet[0]);

                                        //             // Nếu có TL

                                        //             $checkStatus = true;

                                        //             if (count($infoNumberLcbInYear) >= 2 || $check->apply_from_TL > 0) {
                                        //                 if(count($infoNumberLcbInYear) >= 2){
                                        //                     if (count($infoNumberLcbInYear) > 2){
                                        //                         unset($infoNumberLcbInYear[0]);
                                        //                         $infoNumberLcbInYear = array_values($infoNumberLcbInYear);
                                        //                     }
                                        //                     $apply_from_1 = $infoNumberLcbInYear[0]->apply_from;
                                        //                     $apply_from_2 = $infoNumberLcbInYear[1]->apply_from;

                                        //                     $apply_to_1 = $infoNumberLcbInYear[0]->apply_to;
                                        //                     $apply_to_2 = $infoNumberLcbInYear[1]->apply_to;
                                        //                 }

                                        //                 if ($check->apply_from_TL > 0) {
                                        //                     $apply_from_1 = $yearEnd.'-01-01';
                                        //                     $apply_from_2 = $check->apply_from_TL;

                                        //                     $apply_to_1 = date ( 'Y-m-d' ,strtotime ('-1 day' , strtotime ( $check->apply_from_TL ) ) ) ;
                                        //                     $apply_to_2 = $check->apply_to_TL;
                                                            
                                        //                 }
                                        //                 $ltt_1 =BatvHelper::ltt('',$value,$apply_from_1,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                        //                 $lcb = $lcb_1 = BatvHelper::salary_basic_inline($ltt_1)['lcb'];

                                        //                 $ltt_2 =BatvHelper::ltt('',$value,$apply_from_2,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                        //                 if ($check->apply_from_TL > 0) { 
                                        //                     $ltt_2 =BatvHelper::ltt('',$value,date ( 'Y-m-d' ,  strtotime ( '+1 day' , strtotime ( $apply_to_2 ) )  ),$type='','',$option=1,$convert_ratio='',$dayLatches);
                                        //                 }
                                        //                 $lcb_2 = BatvHelper::salary_basic_inline($ltt_2)['lcb'];
                     
                                        //                 if ( $lcb_1 == $lcb_2 ) {
                                        //                     $checkStatus = true;
                                        //                 }else{
                                        //                     $checkStatus = false;
                                        //                 }

                                        //                 // echo $apply_from_1;die;
                                                        
                                        //             }else{
                                        //                 $checkStatus = true;

                                        //                 $ltt_3 =BatvHelper::ltt('',$value,$dayLatches,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                        //                 $lcb = BatvHelper::salary_basic_inline($ltt_3)['lcb'];
                                        //                 // echo $value;die;
                                        //             }
                                                    

                                        //             // Nếu cùng mức LCB
                                        //             if ($checkStatus == true) {
                                        //                 $month_EX = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd);
                                        //                 $holiday_bonus = ($month_EX/12)*0.5*$lcb;
                                        //             } else {
                                      
                                        //                 $month_EX_1 = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd,$apply_from_1,$apply_to_1,'','');
                                        //                 $bonus_1 = ($month_EX_1/12)*0.5*$lcb_1;

                                        //                 $month_EX_2 = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd,'','',$apply_from_2,$apply_to_2);
                                        //                 $bonus_2 = ($month_EX_2/12)*0.5*$lcb_2;

                                        //                 $holiday_bonus = $bonus_1 + $bonus_2;
                                        //             }
                                        //         }

                                        //         if ($holiday_bonus < 1000000) {
                                        //            $holiday_bonus = 1000000;
                                        //         } elseif($holiday_bonus > 1000000 && $holiday_bonus < 2000000) {
                                        //                 $holiday_bonus = 2000000;
                                        //         } 
                                        //     }else{
                                        //         $holiday_bonus = 0;
                                        //     }
                                        // }

                                        //Thưởng dự án
                                        if( $value_3->type == 2 ){
                                            if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $work_bonus = $tmp_3[0]->value;
                                                }else{
                                                }
                                            }
                                        }

                                        //Tiền liên hoan
                                        if( $value_3->type == 17 ){
                                            if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $party_fee = $tmp_3[0]->value;
                                                }else{
                                                }
                                            }
                                        }
                                        //Tiền trợ cấp nhà ở
                                        if( $value_3->type == 18 ){
                                            if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $subsidize_house = $tmp_3[0]->value;
                                                }else{
                                                }
                                            }
                                        }

                                        //Phụ cấp ăn trưa
                                        if( $value_3->type == 3 ){
                                            
                                            $lunch_allowance = 0;
                                            if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $lunch_allowance = $tmp_3[0]->value;
                                                }else{
                                                    $string = $value_3->value_id;
                                                    /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/    

                                                    //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){
                                                        if( $checkContract == 2 ){// Chính thức
                                                            if( $param_contract_check == true ){
                                                                $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }else{
                                                                $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                        } else {
                                                            if($checkContractSpecial) {
                                                                $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            } else {
                                                                $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                        }
                                                    }else{// Nếu hợp động là nửa này nửa kia

                                                        if ( $checkContract == 2 ) {// Chính thức
                                                            $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);

                                                        }
                                                        if ( $checkContract_1 == 2 ) {// Chính thức
                                                            $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                        }
                                                    }
                                                }
                                            }

                                        }

                                        //Phụ cấp đi lại
                                        if( $value_3->type == 4 ){
                                            $travel_allowance = 0;
                                            if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $travel_allowance = $tmp_3[0]->value;
                                                }else{
                                                    $string = $value_3->value_id;
                                                    /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                    $checkTrialWork = Salary::checkTrialWorkDetail( $type=1,$value ); // $type=1 : Hợp đồng thử việc
                                                    //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){
                                                        if( $checkContract == 2 ){// Chính thức
                                                            if( $param_contract_check == true ){
                                                                $travel_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }else{
                                                                $travel_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                            
                                                        } else {
                                                            if($checkContractSpecial) {
                                                                $travel_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            } else {
                                                               $travel_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                   
                                                        }
                                                    }else{// Nếu hợp động là nửa này nửa kia
                                                        if ( $checkContract == 2 ) {// Chính thức
                                                            $travel_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                        }
                                                        if ( $checkContract_1 == 2 ) {// Chính thức
                                                            $travel_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        // //Phụ cấp điện thoại
                                        if( $value_3->type == 5 ){
                                            $phone_allowance = 0;
                                            if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $phone_allowance = $tmp_3[0]->value;
                                                }else{
                                                    $string = $value_3->value_id;
                                                    /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                    //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){

                                                        if( $checkContract == 2 ){// Chính thức
                                                           
                                                            if( $param_contract_check == true ){
                                                                $phone_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }else{
                                                                $phone_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                        } else {
                                                            if($checkContractSpecial) {
                                                                 $phone_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            } else {
                                                                $phone_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }

                                                        }
                                                    }else{// Nếu hợp động là nửa này nửa kia
                                                        if ( $checkContract == 2 ) {// Chính thức
                                                            $phone_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                        }

                                                        if ( $checkContract_1 == 2 ) {// Chính thức
                                                            $phone_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        //Phụ cấp trách nhiệm
                                        if( $value_3->type == 6 ){
                                            $management_allowance = 0;
                                            if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $management_allowance = $tmp_3[0]->value;
                                                }else{
                                                    $string = $value_3->value_id;
                                                    /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                    //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){

                                                        if( $checkContract == 2 ){// Chính thức
                                                           
                                                            if( $param_contract_check == true ){
                                                                $management_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }else{
                                                                $management_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                        } else {
                                                            if($checkContractSpecial) {
                                                                $management_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            } else {
                                                                $management_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }                                                                
                                          
                                                        }
                                                    }else{// Nếu hợp động là nửa này nửa kia
                                                        if ( $checkContract == 2 ) {// Chính thức
                                                            $management_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                        }

                                                        if ( $checkContract_1 == 2 ) {// Chính thức
                                                            $management_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                        }
                                                    }
                                                }
                                            }
                                        }


                                        // //Phụ cấp tiền gửi xe
                                        if( $value_3->type == 13 ){
                                            $parking_fee_allowance = 0;
                                            if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $parking_fee_allowance = $tmp_3[0]->value;
                                                }else{
                                                    $string = $value_3->value_id;
                                                    /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                    //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){

                                                        if( $checkContract == 2 ){// Chính thức
                                                           
                                                            if( $param_contract_check == true ){
                                                                $parking_fee_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }else{
                                                                $parking_fee_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                        }else{
                                                            if($checkContractSpecial) {
                                                                  $parking_fee_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=3,'',$option=1,$convert_ratio='',$dayLatches);
                                                            } else {
                                                                 $parking_fee_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }   

                                                         
                                                        }
                                                    }else{// Nếu hợp động là nửa này nửa kia
                                                        if ( $checkContract == 2 ) {// 1/2 chính thức
                                                            $parking_fee_allowance_1 = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                        }else{
                                                            $parking_fee_allowance_1 = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=1,'',$option=2);
                                                        }

                                                        if ( $checkContract_1 == 2 ) {// Chính thức
                                                            $parking_fee_allowance_2 = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                        }else{
                                                            $parking_fee_allowance_2 = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=1,'',$option=3);
                                                        }
                                                        $parking_fee_allowance = $parking_fee_allowance_1 + $parking_fee_allowance_2;
                                                    }
                                                }
                                            }
                                        }

                                        //Phụ cấp nếu không tham gia bảo hiểm
                                        if( $value_3->type == 15 ){
                                            
                                            if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $other_tax_allowance = $tmp_3[0]->value;

                                                    if (Personnel::checkDateOutCalAllowance($value, $month, $year) > 0) {
                                                        $other_tax_allowance = 0;
                                                    }
                                                }
                                            }
                                        }

                                        //Phụ cấp nếu sử dụng Laptop cá nhân
                                        if( $value_3->type == 16 ){
                                            $laptop_allowance = 0;
                                            if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $laptop_allowance = $tmp_3[0]->value;
                                                }else{
                                                    $string = $value_3->value_id;
                                                    /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                    //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){

                                                        if( $checkContract == 2 ){// Chính thức
                                                           
                                                            if( $param_contract_check == true ){
                                                                $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }else{
                                                                $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                        } else {
                                                            if($checkContractSpecial) {
                                                                $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            } else {
                                                               $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }

                                                        }
                                                    }else{// Nếu hợp động là nửa này nửa kia
                                                        if ( $checkContract == 2 ) {// Chính thức
                                                            $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                        }

                                                        if ( $checkContract_1 == 2 ) {// Chính thức
                                                            $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        //Phụ cấp phong trào
                                        if( $value_3->type == 19 ){
                                            $movement_allowance = 0;
                                            if( $toh_contract || $toh_contract_1 && ($tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month)){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $movement_allowance = $tmp_3[0]->value;
                                                }else{
                                                    $string = $value_3->value_id;
                                                    /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                    $checkTrialWork = Salary::checkTrialWorkDetail( $type=1,$value ); // $type=1 : Hợp đồng thử việc
                                                    //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                    if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){
                                                        if( $checkContract == 2 ){// Chính thức
                                                            if( $param_contract_check == true ){
                                                                $movement_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }else{
                                                                $movement_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                            
                                                        } else {
                                                            if($checkContractSpecial) {
                                                                $movement_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            } else {
                                                                 $movement_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }

                                                           
                                                        }
                                                    }else{// Nếu hợp động là nửa này nửa kia
                                                        if ( $checkContract == 2 ) {// Chính thức
                                                            $movement_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                        }
                                                        if ( $checkContract_1 == 2 ) {// Chính thức
                                                            $movement_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }


                            //Thưởng ngày lễ (TÉT ÂM)
                            if(1==1){
                                $convertTet = explode("-",$dayLatches);
                                $dayEnd = (int)$convertTet[2];
                                $monthEnd = (int)$convertTet[1];
                                $yearEnd = (int)$convertTet[0];
                                $infoNumberLcbInYear = Personnel::infoNumberLcbInYear($value,$convertTet[0]);

                                //SPECIAL
                                // if ($value == 187 || $value == 188){ //Thêm trường hợp cho nhân viên vào từ năm 2018, 2017 chưa vào ko có thông tin
                                //     $holiday_bonus = 1000000;
                                //     $data_personnel_bonus = [
                                //         'holiday_bonus'       =>  $holiday_bonus,
                                //     ];
                                //     for ($i=187; $i <=188 ; $i++) { 
                                //         $check = Salary::checkPersonnelIncome($month,$year,$type='',$i);
                                //         Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                //     }

                                // }

                                // Nếu có TL

                                $checkStatus = true;

                                if(count($infoNumberLcbInYear) >= 2){
                                    if (count($infoNumberLcbInYear) > 2){
                                        unset($infoNumberLcbInYear[0]);
                                        $infoNumberLcbInYear = array_values($infoNumberLcbInYear);
                                    }

                                    $apply_from_1 = $infoNumberLcbInYear[0]->apply_from;
                                    $apply_from_2 = $infoNumberLcbInYear[1]->apply_from;

                                    $apply_to_1 = $infoNumberLcbInYear[0]->apply_to;
                                    $apply_to_2 = $infoNumberLcbInYear[1]->apply_to;

                                    if ($check->apply_from_TL > 0) {
                                        $apply_from_1 = $yearEnd.'-01-01';
                                        $apply_from_2 = $check->apply_from_TL;

                                        $apply_to_1 = date ( 'Y-m-d' ,strtotime ('-1 day' , strtotime ( $check->apply_from_TL ) ) ) ;
                                        $apply_to_2 = $check->apply_to_TL;
                                        
                                    }

                                    $ltt_1 =BatvHelper::lttTet('',$value,$apply_from_1,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                    $lcb = $lcb_1 = BatvHelper::salary_basic_inline($ltt_1)['lcb'];

                                    $ltt_2 =BatvHelper::lttTet('',$value,$apply_from_2,$type='','',$option=1,$convert_ratio='',$dayLatches);

                                    if ($check->apply_from_TL > 0) { 
                                        $ltt_2 =BatvHelper::lttTet('',$value,date ( 'Y-m-d' ,  strtotime ( '+1 day' , strtotime ( $apply_to_2 ) )  ),$type='','',$option=1,$convert_ratio='',$dayLatches);
                                    }

                                    $lcb_2 = BatvHelper::salary_basic_inline($ltt_2)['lcb'];
                                    $checkStatus = false;
                                } else {
                                    $ltt_3 =BatvHelper::lttTet('',$value,$dayLatches,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                    $lcb = BatvHelper::salary_basic_inline($ltt_3)['lcb'];
                                }

                                // Nếu cùng mức LCB
                                if ($checkStatus == true) {
                                    $month_EX = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd, $time_end_from='', $time_end_to='', $time_start_from='', $time_start_to='',$dayLatches);
                                    //TH nhân viên có LBQ < mức LCB thấp nhất thì sẽ  tính LCB = LBQ
                                    if ($ltt_3 >= $lcb) {
                                        // $bonus = ($month_EX/12)*($lcb + $ltt_3);
                                        $bonus = ($month_EX/12)*($ltt_3);
                                    } else {
                                        // $bonus = ($month_EX/12)*2*$ltt_3;
                                       $bonus = ($month_EX/12)*$ltt_3;
                                    }

                                } else {

                                    $month_EX_1 = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd,$apply_from_1,$apply_to_1,'','',$dayLatches);
                                    // $bonus_1 = ($month_EX_1/12)*($lcb_1 + $ltt_1);
                                    $bonus_1 = ($month_EX_1/12)*($ltt_1);

                                    $month_EX_2 = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd,'','',$apply_from_2,$apply_to_2,$dayLatches);
                                    // $bonus_2 = ($month_EX_2/12)*($lcb_2 + $ltt_2);
                                    $bonus_2 = ($month_EX_2/12)*($ltt_2);
                                    
                                    $bonus = $bonus_1 + $bonus_2;

                                }

                                // TÍnh phần trăm được thưởng
                                // $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($dayLatches);
                                // if( in_array($value, $infoUserIdExceptionalAttendance)){
                                //     $ki = 0;
                                //     $ki_rules = 0;
                                //     $ki_performance = 0;
                                //     $ki_seniority = 0;
                                // }else{
                                //     $ki_rules = BatvHelper::ki_rules($value,$yearEnd,$monthEnd,$dayEnd);
                                //     $ki_performance = BatvHelper::ki_performance($value,$yearEnd);
                                //     $ki_seniority = BatvHelper::ki_seniority($value,$yearEnd,$monthEnd,$dayEnd);

                                //     $ki = $ki_rules + $ki_performance + $ki_seniority;

                                // }

                                $ki_rules = BatvHelper::ki_rules($value,$yearEnd,$monthEnd,$dayEnd);
                                $ki_performance = BatvHelper::ki_performance($value,$yearEnd);
                                $ki_seniority = BatvHelper::ki_seniority($value,$yearEnd,$monthEnd,$dayEnd);

                                if ($ki_seniority > 0.1) {
                                    $ki_seniority = 0.1;
                                }

                                $ki = $ki_rules + $ki_performance + $ki_seniority;

                                Salary::updatePersonnelIncome([ 'ki_rules'=>1 + $ki_rules,'ki_performance'=>1 + $ki_performance,'ki_seniority'=>1 + $ki_seniority ],$check->id);
                                
                                $holiday_bonus = ( 1 + $ki )*$bonus;


                                if($value == 171) {
                                    $holiday_bonus = 27494775;
                                }

                                if($value == 342){ // Nguyễn Thu Trang
                                    $holiday_bonus = 2000000;   
                                }
                                
                                if($value == 341) { // Trần Thế Anh
                                    $holiday_bonus = 2935147;
                                }

                                //Sếp = 0
                                if (in_array($value, [1, 119, 121, 137, 152])) {
                                    $holiday_bonus = 0;
                                }

                                $data_personnel_bonus = [
                                    'holiday_bonus'       =>  $holiday_bonus,
                                ];
                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                            }


                            // Nếu tính ngày lễ tết âm hoặc dương thì a phải comment cái còn lại :3, a bấm nút tính đi

                            //Thưởng ngày lễ (TẾT DƯƠNG)
                            if(false){
                                $convertTet = explode("-",$dayLatches);
                                $dayEnd = (int)$convertTet[2];
                                $monthEnd = (int)$convertTet[1];
                                $yearEnd = (int)$convertTet[0];

                                $infoNumberLcbInYear = Personnel::infoNumberLcbInYear($value,$convertTet[0]);

                                // Nếu có TL

                                $checkStatus = true;

                                if (count($infoNumberLcbInYear) >= 2 || $check->apply_from_TL > 0) {
                                    if(count($infoNumberLcbInYear) >= 2){
                                        if (count($infoNumberLcbInYear) > 2){
                                            unset($infoNumberLcbInYear[0]);
                                            $infoNumberLcbInYear = array_values($infoNumberLcbInYear);
                                        }
                                        $apply_from_1 = $infoNumberLcbInYear[0]->apply_from;
                                        $apply_from_2 = $infoNumberLcbInYear[1]->apply_from;

                                        $apply_to_1 = $infoNumberLcbInYear[0]->apply_to;
                                        $apply_to_2 = $infoNumberLcbInYear[1]->apply_to;
                                    }

                                    if ($check->apply_from_TL > 0) {
                                        $apply_from_1 = $yearEnd.'-01-01';
                                        $apply_from_2 = $check->apply_from_TL;

                                        $apply_to_1 = date ( 'Y-m-d' ,strtotime ('-1 day' , strtotime ( $check->apply_from_TL ) ) ) ;
                                        $apply_to_2 = $check->apply_to_TL;
                                        
                                    }
                                    $ltt_1 =BatvHelper::ltt('',$value,$apply_from_1,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                    $lcb = $lcb_1 = BatvHelper::salary_basic_inline($ltt_1)['lcb'];

                                    $ltt_2 =BatvHelper::ltt('',$value,$apply_from_2,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                    if ($check->apply_from_TL > 0) { 
                                        $ltt_2 =BatvHelper::ltt('',$value,date ( 'Y-m-d' ,  strtotime ( '+1 day' , strtotime ( $apply_to_2 ) )  ),$type='','',$option=1,$convert_ratio='',$dayLatches);
                                    }
                                    $lcb_2 = BatvHelper::salary_basic_inline($ltt_2)['lcb'];

                                    if ( $lcb_1 == $lcb_2 ) {
                                        $checkStatus = true;
                                    }else{
                                        $checkStatus = false;
                                    }

                                    // echo $apply_from_1;die;
                                    
                                }else{
                                    $checkStatus = true;

                                    $ltt_3 =BatvHelper::ltt('',$value,$dayLatches,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                    $lcb = BatvHelper::salary_basic_inline($ltt_3)['lcb'];
                                    // echo $value;die;
                                }
                                
                                
                                // Nếu cùng mức LCB
                                if ($checkStatus == true) {
                                    $month_EX = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd, $time_end_from='', $time_end_to='', $time_start_from='', $time_start_to='',$dayLatches);
                                    $holiday_bonus = ($month_EX/12)*0.5*$lcb;
                                } else {
                  
                                    $month_EX_1 = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd,$apply_from_1,$apply_to_1,'','',$dayLatches);
                                    $bonus_1 = ($month_EX_1/12)*0.5*$lcb_1;

                                    $month_EX_2 = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd,'','',$apply_from_2,$apply_to_2,$dayLatches);
                                    $bonus_2 = ($month_EX_2/12)*0.5*$lcb_2;

                                    $holiday_bonus = $bonus_1 + $bonus_2;
                                }


                                $month_EX = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd, $time_end_from='', $time_end_to='', $time_start_from='', $time_start_to='',$dayLatches);
                                $holiday_bonus = ($month_EX/12)*3000000;

                                switch ($value) {
                                    case 90:
                                    case 92:
                                    case 95:
                                    case 96:
                                    case 117:
                                    case 120:
                                        $holiday_bonus = ($month_EX/12)*5000000;
                                        break;
                                    case 341:
                                        $holiday_bonus = 2000000;
                                        break;
                                    case 171:
                                        $holiday_bonus = 2875000;
                                        break; 
                                    case 272:
                                        $holiday_bonus = 2875000;
                                        break; 
                                    case 314:
                                        $holiday_bonus = 2500000;
                                        break;   
                                    default:
                                        // code...
                                        break;
                                }

                                if ($holiday_bonus < 1000000) {
                                    $holiday_bonus = 1000000;
                                } elseif($holiday_bonus >= 1000000 && $holiday_bonus < 2000000) {
                                    $holiday_bonus = 2000000;
                                } 

                                //Sếp = 0
                                if (in_array($value, [1, 119, 121, 137, 152])) {
                                    $holiday_bonus = 0;
                                }

                                $data_personnel_bonus = [
                                    'holiday_bonus'       =>  $holiday_bonus,
                                ];
                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                            }
                        }

                        $data_bonus = [
                            'holiday_bonus'       =>  $holiday_bonus,
                            'work_bonus'       =>  $work_bonus,
                            'subsidize_house'       =>  $subsidize_house,
                            'lunch_allowance'       =>  $lunch_allowance,
                            'travel_allowance'       =>  $travel_allowance,
                            'phone_allowance'       =>  $phone_allowance,
                            'management_allowance'       =>  $management_allowance,
                            'parking_fee_allowance'       =>  $parking_fee_allowance,
                            'other_tax_allowance'       =>  $other_tax_allowance,
                            'laptop_allowance'       =>  $laptop_allowance,
                            'movement_allowance'       =>  $movement_allowance,
                        ];
                        Salary::updatePersonnelBonus($data_bonus,$check->id);

                        $data_total = [
                            'check_bonus'  => 1,
                        ];
                        Salary::updatePersonnelIncome($data_total,$check->id);
                    }

                    $res=array('Response'=>"Success","Message"=>"Bạn đã cập nhật thành công" );
                }else{
                    if( $listPersonnel ){
                        foreach ($listPersonnel as $key => $value) {
                            if (BatvHelper::checkSubsidize($value, $month, $year)) {
                                $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                                $tmp = Salary::getPersonnelGroupDetailMuch($value,$type=[1,2,3,4,5,6,13,15,16,17,18,19]);
                                $tmp_2 = array();
                                foreach ($tmp as $key_1 => $value_1) {
                                    $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

                                }
                                $data = array();
                                foreach ($tmp_2 as $k => $v) {
                                    foreach ($tmp_2[$k] as $k_1 => $v_2) {
                                        $data[] = $v_2;
                                    }

                                }
                                $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                                if( count( $checkPersonnelIncome ) >0 ){
                                    // update tháng chốt lương thưởng Tết nếu có
                                    Salary::updatePersonnelIncome(['day_latches'=>$dayLatches],$checkPersonnelIncome->id);
                                    $checkPersonnelBonus = Salary::checkPersonnelBonus($checkPersonnelIncome->id);
                                    if( count( $checkPersonnelBonus )==0 ){
                                        $data_personnel_bonus = [
                                            'personnel_income_id'   => $checkPersonnelIncome->id,
                                        ];
                                        Salary::insertPersonnelBonus($data_personnel_bonus);
                                    }
                                }else{
                                    $data_personnel_income = [
                                        'month'         =>  $month,
                                        'year'          =>  $year,
                                        'date_value'    => $date_value,
                                        'personnel_id'  =>  $value,
                                        'day_latches'   => $dayLatches,
                                        'created_at'    =>  date('Y-m-d'),
                                        'created_by'    => Auth::user()->id,
                                        'status'        => 1,
                                    ];
                                    Salary::insertPersonnelIncome($data_personnel_income);
                                    $id =  \DB::getPdo()->lastInsertId(); 

                                    $data_personnel_bonus = [
                                        'personnel_income_id'   => $id,
                                    ];

                                    Salary::insertPersonnelBonus($data_personnel_bonus);
                                }
                                $check = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                                foreach ($data as $key_2 => $value_2) {
                                    $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[1,2,3,4,5,6,13,15,16,17,18,19]);
                                    if( count( $tmp_3 )>0 ){
                                        $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert[1],$convert[0]);
                                        $param = $convert[0].'-'.$convert[1].'-'.$numberDay;
                                        $param_1 = $convert[0].'-'.$convert[1].'-01';
                                        $toh_contract = Salary::checkContract( $value,$param );
                                        $toh_contract_1 = Salary::checkContract( $value,$param_1 );
                                        $param_contract_check = false;

                                        if( $toh_contract ){
                                            $checkContract = $toh_contract->contract_id;
                                        }else{
                                            $checkContractSpecial = Salary::checkContractSpecial($value);
                                            $checkContract = $checkContractSpecial->contract_id;
                                            $param_contract_check = true;

                                        }
                                        
                                        if( $toh_contract_1 ){
                                            $checkContract_1 = Salary::checkContract( $value,$param_1 )->contract_id;
                                        }else{
                                            $checkContract_1 = '';
                                        }

                                        foreach ($tmp_3 as $key_3 => $value_3) {  
                                            //Thưởng ngày lễ
                                            // if( $value_3->type == 1 ){
                                            //     if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                            //         if( $tmp_3[0]->is_fixed==1 ){
                                            //             $holiday_bonus = $tmp_3[0]->value;
                                            //         }else{
                                            //             $convertTet = explode("-",$dayLatches);
                                            //             $dayEnd = (int)$convertTet[2];
                                            //             $monthEnd = (int)$convertTet[1];
                                            //             $yearEnd = (int)$convertTet[0];

                                            //             $infoNumberLcbInYear = Personnel::infoNumberLcbInYear($value,$convertTet[0]);

                                            //             //SPECIAL
                                            //             // if ($value == 187 || $value == 188){ //Thêm trường hợp cho nhân viên vào từ năm 2018, 2017 chưa vào ko có thông tin
                                            //             //     $holiday_bonus = 1000000;
                                            //             //     $data_personnel_bonus = [
                                            //             //         'holiday_bonus'       =>  $holiday_bonus,
                                            //             //     ];
                                            //             //     for ($i=187; $i <=188 ; $i++) { 
                                            //             //         $check = Salary::checkPersonnelIncome($month,$year,$type='',$i);
                                            //             //         Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                            //             //     }

                                            //             // }

                                            //             // Nếu có TL

                                            //             $checkStatus = true;

                                            //             if(count($infoNumberLcbInYear) >= 2){
                                            //                 if (count($infoNumberLcbInYear) > 2){
                                            //                     unset($infoNumberLcbInYear[0]);
                                            //                     $infoNumberLcbInYear = array_values($infoNumberLcbInYear);
                                            //                 }

                                            //                 $apply_from_1 = $infoNumberLcbInYear[0]->apply_from;
                                            //                 $apply_from_2 = $infoNumberLcbInYear[1]->apply_from;

                                            //                 $apply_to_1 = $infoNumberLcbInYear[0]->apply_to;
                                            //                 $apply_to_2 = $infoNumberLcbInYear[1]->apply_to;

                                            //                 if ($check->apply_from_TL > 0) {
                                            //                     $apply_from_1 = $yearEnd.'-01-01';
                                            //                     $apply_from_2 = $check->apply_from_TL;

                                            //                     $apply_to_1 = date ( 'Y-m-d' ,strtotime ('-1 day' , strtotime ( $check->apply_from_TL ) ) ) ;
                                            //                     $apply_to_2 = $check->apply_to_TL;
                                                                
                                            //                 }

                                            //                 $ltt_1 =BatvHelper::ltt('',$value,$apply_from_1,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                            //                 $lcb = $lcb_1 = BatvHelper::salary_basic_inline($ltt_1)['lcb'];

                                            //                 $ltt_2 =BatvHelper::ltt('',$value,$apply_from_2,$type='','',$option=1,$convert_ratio='',$dayLatches);

                                            //                 if ($check->apply_from_TL > 0) { 
                                            //                     $ltt_2 =BatvHelper::ltt('',$value,date ( 'Y-m-d' ,  strtotime ( '+1 day' , strtotime ( $apply_to_2 ) )  ),$type='','',$option=1,$convert_ratio='',$dayLatches);
                                            //                 }

                                            //                 $lcb_2 = BatvHelper::salary_basic_inline($ltt_2)['lcb'];
                                            //                 $checkStatus = false;
                                            //             } else {
                                            //                 $ltt_3 =BatvHelper::ltt('',$value,$dayLatches,$type='','',$option=1,$convert_ratio='',$dayLatches);
                                            //                 $lcb = BatvHelper::salary_basic_inline($ltt_3)['lcb'];
                                            //             }

                                            //             // Nếu cùng mức LCB
                                            //             if ($checkStatus == true) {
                                            //                 $month_EX = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd);
                                            //                 $bonus = ($month_EX/12)*($lcb + $ltt_3);
                                            //             } else {
                                        
                                            //                 $month_EX_1 = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd,$apply_from_1,$apply_to_1,'','');
                                            //                 $bonus_1 = ($month_EX_1/12)*($lcb_1 + $ltt_1);

                                            //                 $month_EX_2 = BatvHelper::param_bonus_Tet($value,$yearEnd,$monthEnd,$dayEnd,'','',$apply_from_2,$apply_to_2);
                                            //                 $bonus_2 = ($month_EX_2/12)*($lcb_2 + $ltt_2);

                                            //                 $bonus = $bonus_1 + $bonus_2;
                                            //             }
                                            //            // echo $value.'-----'.$bonus;
                                            //             // TÍnh phần trăm được thưởng
                                            //             $infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($dayLatches);
                                            //             if( in_array($value, $infoUserIdExceptionalAttendance)){
                                            //                 $ki = 0;
                                            //                 $ki_rules = 0;
                                            //                 $ki_performance = 0;
                                            //                 $ki_seniority = 0;
                                            //             }else{
                                            //                 $ki_rules = BatvHelper::ki_rules($value,$yearEnd,$monthEnd,$dayEnd);
                                            //                 $ki_performance = BatvHelper::ki_performance($value,$yearEnd);
                                            //                 $ki_seniority = BatvHelper::ki_seniority($value,$yearEnd,$monthEnd,$dayEnd);

                                            //                 $ki = $ki_rules + $ki_performance + $ki_seniority;

                                            //             }
                                            //             // if($value == 129){
                                            //             //     $ki_rules = 0.1;
                                            //             //     $ki_performance = 0.02;
                                            //             //     $ki_seniority = 0.039166667;
                                            //             //     $ki = $ki_rules + $ki_performance + $ki_seniority;
                                            //             //     $bonus = 10833333;
                                            //             // }
                                            //             Salary::updatePersonnelIncome([ 'ki_rules'=>1 + $ki_rules,'ki_performance'=>1 + $ki_performance,'ki_seniority'=>1 + $ki_seniority ],$check->id);
                                                        
                                            //             $holiday_bonus = ( 1 + $ki )*$bonus;
                                            //         }
                                            //     }else{
                                            //         $holiday_bonus = 0;
                                            //     }
                                            //     if ($holiday_bonus <= 1000000){ //Thêm trường hợp cho nhân viên vào từ năm 2018, 2017 chưa vào ko có thông tin
                                            //         $holiday_bonus = 1000000;

                                            //     } elseif ( $holiday_bonus > 1000000 && $holiday_bonus <= 2000000) {
                                            //         $holiday_bonus = 2000000;
                                            //     }

                                            //     $data_personnel_bonus = [
                                            //         'holiday_bonus'       =>  $holiday_bonus,
                                            //     ];
                                            //     Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                            // }

                                            //Thưởng dự án
                                            if( $value_3->type == 2 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $work_bonus = $tmp_3[0]->value;
                                                    }else{
                                                    }
                                                }else{
                                                    $work_bonus = 0;
                                                }

                                                $data_personnel_bonus = [
                                                    'work_bonus'       =>  $work_bonus,
                                                ];
                                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                            }

                                            //Tiền liên hoan
                                            if( $value_3->type == 17 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $party_fee = $tmp_3[0]->value;
                                                    }else{
                                                    }
                                                }else{
                                                    $party_fee = 0;
                                                }

                                                $data_personnel_bonus = [
                                                    'party_fee'       =>  $party_fee,
                                                ];
                                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                            }
                                            //Tiền trợ cấp nhà ở
                                            if( $value_3->type == 18 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $subsidize_house = $tmp_3[0]->value;
                                                    }else{
                                                    }
                                                }else{
                                                    $subsidize_house = 0;
                                                }

                                                $data_personnel_bonus = [
                                                    'subsidize_house'       =>  $subsidize_house,
                                                ];
                                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                            }

                                            //Phụ cấp ăn trưa
                                            if( $value_3->type == 3 ){
                                                $lunch_allowance = 0;
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $lunch_allowance = $tmp_3[0]->value;
                                                    }else{
                                                        $string = $value_3->value_id;
                                                        /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/    

                                                        //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                        if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){
                                                            if( $checkContract == 2 ){// Chính thức
                                                                if( $param_contract_check == true ){
                                                                    $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }else{
                                                                    $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }
                                                            }
                                                        }else{// Nếu hợp động là nửa này nửa kia

                                                            if ( $checkContract == 2 ) {// Chính thức
                                                                $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);

                                                            }
                                                            if ( $checkContract_1 == 2 ) {// Chính thức
                                                                $lunch_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                $data_personnel_bonus = [
                                                    'lunch_allowance'       =>  $lunch_allowance,
                                                ];

                                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);

                                            }

                                            //Phụ cấp đi lại
                                            if( $value_3->type == 4 ){
                                                $travel_allowance = 0;
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $travel_allowance = $tmp_3[0]->value;
                                                    }else{
                                                        $string = $value_3->value_id;
                                                        /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                        $checkTrialWork = Salary::checkTrialWorkDetail( $type=1,$value ); // $type=1 : Hợp đồng thử việc
                                                        //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                        if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){
                                                            if( $checkContract == 2 ){// Chính thức
                                                                if( $param_contract_check == true ){
                                                                    $travel_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }else{
                                                                    $travel_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }
                                                                
                                                            }
                                                        }else{// Nếu hợp động là nửa này nửa kia
                                                            if ( $checkContract == 2 ) {// Chính thức
                                                                $travel_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                            }
                                                            if ( $checkContract_1 == 2 ) {// Chính thức
                                                                $travel_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                            }
                                                        }
                                                    }
                                                }

                                                $data_personnel_bonus = [
                                                    'travel_allowance'       =>  $travel_allowance,
                                                ];
                                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                            }
                                            // //Phụ cấp điện thoại
                                            if( $value_3->type == 5 ){
                                                $phone_allowance = 0;
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $phone_allowance = $tmp_3[0]->value;
                                                    }else{
                                                        $string = $value_3->value_id;
                                                        /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                        //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                        if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){

                                                            if( $checkContract == 2 ){// Chính thức
                                                            
                                                                if( $param_contract_check == true ){
                                                                    $phone_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }else{
                                                                    $phone_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }
                                                            }
                                                        }else{// Nếu hợp động là nửa này nửa kia
                                                            if ( $checkContract == 2 ) {// Chính thức
                                                                $phone_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                            }

                                                            if ( $checkContract_1 == 2 ) {// Chính thức
                                                                $phone_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                            }
                                                        }
                                                    }
                                                }
                                                $data_personnel_bonus = [
                                                    'phone_allowance'       =>  $phone_allowance,
                                                ];

                                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);
                                            }

                                            //Phụ cấp trách nhiệm
                                            if( $value_3->type == 6 ){
                                                $management_allowance = 0;
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $management_allowance = $tmp_3[0]->value;
                                                    }else{
                                                        $string = $value_3->value_id;
                                                        /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                        //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                        if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){

                                                            if( $checkContract == 2 ){// Chính thức
                                                            
                                                                if( $param_contract_check == true ){
                                                                    $management_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }else{
                                                                    $management_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }
                                                            }
                                                        }else{// Nếu hợp động là nửa này nửa kia
                                                            if ( $checkContract == 2 ) {// Chính thức
                                                                $management_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                            }

                                                            if ( $checkContract_1 == 2 ) {// Chính thức
                                                                $management_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                            }
                                                        }
                                                    }
                                                }
                                                $data_personnel_bonus = [
                                                    'management_allowance'       =>  $management_allowance,
                                                ];
                                                Salary::updatePersonnelBonus($data_personnel_bonus,$check->id);

                                            }


                                            // //Phụ cấp tiền gửi xe
                                            if( $value_3->type == 13 ){
                                                $parking_fee_allowance = 0;
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $parking_fee_allowance = $tmp_3[0]->value;
                                                    }else{
                                                        $string = $value_3->value_id;
                                                        /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                        //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                        if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){

                                                            if( $checkContract == 2 ){// Chính thức
                                                            
                                                                if( $param_contract_check == true ){
                                                                    $parking_fee_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }else{
                                                                    
                                                                    $parking_fee_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }
                                                            }else{
                                                                $parking_fee_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=3,'',$option=1,$convert_ratio='',$dayLatches);
                                                            }
                                                        }else{// Nếu hợp động là nửa này nửa kia
                                                            if ( $checkContract == 2 ) {// 1/2 chính thức
                                                                $parking_fee_allowance_1 = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                            }else{
                                                                $parking_fee_allowance_1 = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=1,'',$option=2);
                                                            }

                                                            if ( $checkContract_1 == 2 ) {// Chính thức
                                                                $parking_fee_allowance_2 = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                            }else{
                                                                $parking_fee_allowance_2 = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=1,'',$option=3);
                                                            }
                                                            $parking_fee_allowance = $parking_fee_allowance_1 + $parking_fee_allowance_2;
                                                        }
                                                    }
                                                }
                                                $data_parking_fee_allowance = [
                                                    'parking_fee_allowance'       =>  $parking_fee_allowance,
                                                ];

                                                Salary::updatePersonnelBonus($data_parking_fee_allowance,$check->id);
                                            }

                                            //Phụ cấp nếu không tham gia bảo hiểm
                                            if( $value_3->type == 15 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $other_tax_allowance = $tmp_3[0]->value;
                                                    }else{
                                                    }
                                                }else{
                                                    $other_tax_allowance = 0;
                                                }

                                                $data_other_tax_allowance = [
                                                    'other_tax_allowance'       =>  $other_tax_allowance,
                                                ];
                                                Salary::updatePersonnelBonus($data_other_tax_allowance,$check->id);
                                            }

                                            //Phụ cấp nếu sử dụng Laptop cá nhân
                                            if( $value_3->type == 16 ){
                                                $laptop_allowance = 0;
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $laptop_allowance = $tmp_3[0]->value;
                                                    }else{
                                                        $string = $value_3->value_id;
                                                        /*-------Xử lý các trường hợp liên quan đến các trường hợp về hợp đồng lao động ( chính thức, thử việc, nuwaar chính thức nửa thử việc )------*/
                                                        //Nếu hợp đông là 100%  chính thức hoặc partime hoặc thực tập hoặc thử việc
                                                        if( $checkContract == $checkContract_1 || $checkContract_1 == '' ||  $param_contract_check == true){

                                                            if( $checkContract == 2 ){// Chính thức
                                                            
                                                                if( $param_contract_check == true ){
                                                                    $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$checkContractSpecial->apply_to,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }else{
                                                                    $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches);
                                                                }
                                                            }
                                                        }else{// Nếu hợp động là nửa này nửa kia
                                                            if ( $checkContract == 2 ) {// Chính thức
                                                                $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract->apply_from,$type=2,'',$option=2);
                                                            }

                                                            if ( $checkContract_1 == 2 ) {// Chính thức
                                                                $laptop_allowance = BatvHelper::calculateSpecial($string,$value,$toh_contract_1->apply_to,$type=2,'',$option=3);
                                                            }
                                                        }
                                                    }
                                                }

                                                $data_laptop_allowance = [
                                                    'laptop_allowance'       =>  $laptop_allowance,
                                                ];
                                                Salary::updatePersonnelBonus($data_laptop_allowance,$check->id);
                                            }
                                            
                                        }
                                    }
                                }
                                $data_total = [
                                    'check_bonus'  => 1,
                                ];
                                Salary::updatePersonnelIncome($data_total,$check->id);
                            }
                        }
                        $res=array('Response'=>"Success","Message"=>"Bạn đã tính Thưởng và Phụ cấp thành công" );
                    }else{
                        $res=array('Response'=>"Error","Error"=>"Bạn chưa chấm công trong tháng cần tính đó" );
                    }
                }
            // }else{
            //     $res=array('Response'=>"Error","Error"=>"Kiểm tra lại thời gian áp dụng công thức tính" );
            // }
            echo json_encode($res);die;
        }
    }

    public function getTaxInsurrance(Request $request){
        $data = Salary::listTaxInsurrance($request);
        return view('layouts.luongthuong.server.thuebaohiem',['data'=>$data]);
    }

    public function getTaxInsurranceAjax(Request $request){
        if( $request->ajax() ){
            $dayLatches = '';
            if( $request->selectMonth != "" ){
                $month =  ( $request->selectMonth < 10)?'0'.$request->selectMonth:$request->selectMonth;
                $year  =  $request->selectYear;
                $dateCurrent = $year."-".$month."-"."01";
            }else{
                $dateCurrent = date('Y-m-d');
                $month = date('m');
                $year  = date('Y');
            }
            $date_value = $year.'-'.$month.'-01';
            // $checkTimeApplyMuch = Salary::checkTimeApplyMuch($dateCurrent,$type=[8,9,14]);
            // if( count($checkTimeApplyMuch) >=4 ){
                $time = $year.'-'.$month;
                $convert = explode("-",$time);
                $check_tax_insurance = Salary::checkPersonnelIncome($month,$year,$type='check_tax_insurance');
                $infoParameters = Salary::infoParameters();
                $listPersonnel = Personnel::getPersonnelSalary($month,$year,$year."-".$month);
                if( count( $check_tax_insurance ) >0 ){
                    foreach ($listPersonnel as $key => $value) {
                        $tax = $insurance = $insurance_by_company = 0;
                        $check = Salary::checkPersonnelIncome($month,$year,$type='',$value);

                        if (BatvHelper::checkInsurrance($value, $month, $year)) {
                            $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                            $tmp = Salary::getPersonnelGroupDetailMuch($value,$type=[8,9,14]);
                            $tmp_2 = array();
                            foreach ($tmp as $key_1 => $value_1) {
                                $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

                            }
                            $data = array();
                            foreach ($tmp_2 as $k => $v) {
                                foreach ($tmp_2[$k] as $k_1 => $v_2) {
                                    $data[] = $v_2;
                                }

                            }
                            $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                            Salary::deletePersonnelTaxInsurance($checkPersonnelIncome->id);
                            $data_personnel_tax_insurance = [
                                'personnel_income_id'   => $checkPersonnelIncome->id,
                            ];
                            Salary::insertPersonnelTaxInsurance($data_personnel_tax_insurance);

                            foreach ($data as $key_2 => $value_2) {
                                $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[8,9,14]);
                                // echo "<pre>";
                                // print_r($tmp_3);
                                if( count( $tmp_3 )>0 ){
                                    foreach ($tmp_3 as $key_3 => $value_3) {   
                                        //Thuế
                                        if( $value_3->type == 8 ){
                                            if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                $string = $value_3->value_id;
                                                $income_taxable = BatvHelper::calculate($string,$value,$time);

                                                $infosettingTax = Salary::infosettingTax();
                                                $i=0;
                                                foreach ($infosettingTax as $key => $value) {
                                                    if( $income_taxable <=0 ){
                                                        $tax = 0;
                                                        break;
                                                    }
                                                    if( $i==0 ){
                                                        if( $income_taxable >0 && $income_taxable <= $infosettingTax[0]->money_tax ){
                                                            $tax = ($infosettingTax[0]->percent_tax * $income_taxable)-$infosettingTax[$i]->money_minus ;
                                                            break;
                                                        }
                                                    }
                                                    if( $i>=1 && $i<count($infosettingTax) ){
                                                        if( $income_taxable > $infosettingTax[$i-1]->money_tax && $income_taxable <= $infosettingTax[$i]->money_tax){
                                                            $tax = ($infosettingTax[$i]->percent_tax * $income_taxable) - $infosettingTax[$i]->money_minus ;
                                                            break;
                                                        }
                                                    }
                                                    if( $i==count($infosettingTax)-1 ){
                                                        if( $income_taxable > $infosettingTax[$i]->money_tax ){
                                                            $tax = ($infosettingTax[$i]->percent_tax * $income_taxable) - $infosettingTax[$i]->money_minus ;
                                                            break;
                                                        }
                                                    }

                                                    $i++;
                                                }

                                            }
                                        }
                                        //Bảo hiểm nhân viên phải đóng
                                        if( $value_3->type == 9 ){
                                            if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $insurance = $tmp_3[0]->value;

                                                    if (Personnel::checkDateOutCalAllowance($value, $month, $year) > 0) {
                                                        $insurance = 0;
                                                    }
                                                }
                                            }
                                        }

                                        //Bảo hiểm công ty phải đóng
                                        if( $value_3->type == 14 ){
                                            if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                if( $tmp_3[0]->is_fixed==1 ){
                                                    $insurance_by_company = $tmp_3[0]->value;

                                                    if (Personnel::checkDateOutCalAllowance($value, $month, $year) > 0) {
                                                        $insurance_by_company = 0;
                                                    }
                                                }
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        }

                        Salary::updatePersonnelTaxInsurance(['tax'  =>  $tax,'insurance' =>  $insurance,'insurance_by_company' =>  $insurance_by_company],$check->id);

                        $data_total = [ 'check_tax_insurance'  => 1 ];
                        Salary::updatePersonnelIncome($data_total,$check->id);

                    }
                    $res=array('Response'=>"Success","Message"=>"Bạn đã cập nhật thành công" );

                }else{
                    if( $listPersonnel ){
                        foreach ($listPersonnel as $key => $value) {
                            if (BatvHelper::checkInsurrance($value, $month, $year)) {
                                $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                                $tmp = Salary::getPersonnelGroupDetailMuch($value,$type=[8,9,14]);
                                $tmp_2 = array();
                                foreach ($tmp as $key_1 => $value_1) {
                                    $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

                                }
                                $data = array();
                                foreach ($tmp_2 as $k => $v) {
                                    foreach ($tmp_2[$k] as $k_1 => $v_2) {
                                        $data[] = $v_2;
                                    }

                                }
                                $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                                if( count( $checkPersonnelIncome ) >0 ){
                                    $checkPersonnelTaxInsurance = Salary::checkPersonnelTaxInsurance($checkPersonnelIncome->id);
                                    if( count( $checkPersonnelTaxInsurance )==0 ){
                                        $data_personnel_tax_insurance = [
                                            'personnel_income_id'   => $checkPersonnelIncome->id,
                                        ];
                                        Salary::insertPersonnelTaxInsurance($data_personnel_tax_insurance);
                                    }
                                }else{
                                    $data_personnel_income = [
                                        'month'         =>  $month,
                                        'year'          =>  $year,
                                        'date_value'    =>  $date_value,
                                        'personnel_id'  =>  $value,
                                        'created_at'    =>  date('Y-m-d'),
                                        'created_by'    => Auth::user()->id,
                                        'status'        => 1,
                                    ];
                                    Salary::insertPersonnelIncome($data_personnel_income);
                                    $id =  \DB::getPdo()->lastInsertId(); 

                                    $data_personnel_tax_insurance = [
                                        'personnel_income_id'   => $id,
                                    ];

                                    Salary::insertPersonnelTaxInsurance($data_personnel_tax_insurance);
                                }
                                $check = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                                foreach ($data as $key_2 => $value_2) {
                                    $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[8,9,14]);
                                    if( count( $tmp_3 )>0 ){
                                        foreach ($tmp_3 as $key_3 => $value_3) {   
                                            //Thuế
                                            if( $value_3->type == 8 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    $string = $value_3->value_id;
                                                    $income_taxable = BatvHelper::calculate($string,$value,$time);

                                                    $infosettingTax = Salary::infosettingTax();
                                                    $i=0;
                                                    foreach ($infosettingTax as $key => $value) {
                                                        if( $income_taxable <=0 ){
                                                            $tax = 0;
                                                            break;
                                                        }
                                                        if( $i==0 ){
                                                            if( $income_taxable >0 && $income_taxable <= $infosettingTax[0]->money_tax ){
                                                                $tax = ($infosettingTax[0]->percent_tax * $income_taxable)-$infosettingTax[$i]->money_minus ;
                                                                break;
                                                            }
                                                        }
                                                        if( $i>=1 && $i<count($infosettingTax) ){
                                                            if( $income_taxable > $infosettingTax[$i-1]->money_tax && $income_taxable <= $infosettingTax[$i]->money_tax){
                                                                $tax = ($infosettingTax[$i]->percent_tax * $income_taxable) - $infosettingTax[$i]->money_minus ;
                                                                break;
                                                            }
                                                        }
                                                        if( $i==count($infosettingTax)-1 ){
                                                            if( $income_taxable > $infosettingTax[$i]->money_tax ){
                                                                $tax = ($infosettingTax[$i]->percent_tax * $income_taxable) - $infosettingTax[$i]->money_minus ;
                                                                break;
                                                            }
                                                        }

                                                        $i++;
                                                    }

                                                }else{
                                                    $tax = 0;
                                                }

                                                $data_personnel_tax_insurance = [
                                                    'tax'       =>  $tax,
                                                ];
                                                Salary::updatePersonnelTaxInsurance($data_personnel_tax_insurance,$check->id);
                                            }

                                            //Bảo hiểm nhân viên phải đóng
                                            if( $value_3->type == 9 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $insurance = $tmp_3[0]->value;
                                                    }else{
                                                    }
                                                }else{
                                                    $insurance = 0;
                                                }

                                                $data_personnel_tax_insurance = [
                                                    'insurance'       =>  $insurance,
                                                ];
                                                Salary::updatePersonnelTaxInsurance($data_personnel_tax_insurance,$check->id);
                                            }

                                            //Bảo hiểm công ty phải đóng
                                            if( $value_3->type == 14 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $insurance_by_company = $tmp_3[0]->value;
                                                    }else{
                                                    }
                                                }else{
                                                    $insurance_by_company = 0;
                                                }
                                                $data_personnel_insurance_by_company = [
                                                    'insurance_by_company'       =>  $insurance_by_company,
                                                ];
                                                Salary::updatePersonnelTaxInsurance($data_personnel_insurance_by_company,$check->id);
                                            }
                                        }
                                    }
                                }
                                $data_total = [
                                    'check_tax_insurance'  => 1,
                                ];
                                Salary::updatePersonnelIncome($data_total,$check->id);

                            }
                        }
                        $res=array('Response'=>"Success","Message"=>"Bạn đã tính Thuế và Bảo hiểm thành công" );
                    }else{
                         $res=array('Response'=>"Error","Error"=>"Bạn chưa chấm công trong tháng cần tính đó" );
                    }
                }
            // }else{
            //     $res=array('Response'=>"Error","Error"=>"Kiểm tra lại thời gian áp dụng công thức tính" );
            // }

            echo json_encode($res);die;
        }
    }

    //Các khoản khác
    public function getSalaryOther( Request $request ){
        $arr = Salary::listSalaryOther($request);
        // echo "<pre>";
        // print_r($arr);die;
        $data = array();
        if( $arr ){
            foreach ($arr as $key => $value) {
                if( !isset($data['list'][$value->personnel_id]->fullname) ){
                    $data['list'][$value->personnel_id]['fullname'] = $value->fullname;
                    $data['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                    $data['list'][$value->personnel_id]['salary_trial_default'] = $value->salary_trial_default;
                    $data['list'][$value->personnel_id]['salary_official_default'] = $value->salary_official_default;
                }else{
                    $data['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                }
                $data['status'] =  $value->status;

            }
        }
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.luongthuong.server.cackhoankhac',['data'=>$data]);
    }

    public function getSalaryOtherAjax(Request $request){
        if( $request->ajax() ){
            $dayLatches = '';
            if( $request->selectMonth != "" ){
                $month =  ( $request->selectMonth < 10)?'0'.$request->selectMonth:$request->selectMonth;
                $year  =  $request->selectYear;
                $dateCurrent = $year."-".$month."-"."01";
            }else{
                $dateCurrent = date('Y-m-d');
                $month = date('m');
                $year  = date('Y');
            }
            $date_value = $year.'-'.$month.'-01';
            // $checkTimeApply = Salary::checkTimeApply($dateCurrent,$type=12);
            // if( count($checkTimeApply) >=0 ){
                $time = $year.'-'.$month;
                $convert = explode("-",$time);
                $infoParameters = Salary::infoParameters();
                $listPersonnel = Personnel::getPersonnelSalary($month,$year,$year."-".$month);
                $check_total_other = Salary::checkPersonnelIncome($month,$year,$type='check_other');

                if( count( $check_total_other ) >0 ){

                    foreach ($listPersonnel as $key => $value) {
                        $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                        $tmp = Salary::getPersonnelGroupDetail($value,$type=12);
                        $tmp_2 = array();
                        foreach ($tmp as $key_1 => $value_1) {
                            $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

                        }
                        $data = array();
                        foreach ($tmp_2 as $k => $v) {
                            foreach ($tmp_2[$k] as $k_1 => $v_2) {
                                $data[] = $v_2;
                            }

                        }
                        $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                        Salary::deletePersonnelIncomeOther($checkPersonnelIncome->id);
                        foreach ($data as $key_2 => $value_2) {
                            $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id, $dateCurrent,$type=[12]);

                            if( count( $tmp_3 )>0 ){
                                foreach ($tmp_3 as $key_3 => $value_3) {   
                                
                                    if( $value_3->type == 12 ){
                                        if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                            // echo 1;die;
                                            if( $tmp_3[0]->is_fixed==1 ){
                                                $income_title = $tmp_3[0]->title;
                                                $income_key = $tmp_3[0]->key_special;
                                                $income_value = $tmp_3[0]->value;
                                            }else{
                                            }
                                        }else{
                                            $income_value = 0;
                                            $income_key = $tmp_3[0]->key_special;
                                            $income_title = $tmp_3[0]->title;
                                        }

                                        $data_personnel_income_key = [
                                            'income_title'       =>  $income_title,
                                            'income_key'       =>  $income_key,
                                            'income_value'       =>  $income_value,
                                            'personnel_income_id'=>$checkPersonnelIncome->id
                                        ];
                                        Salary::insertPersonnelIncomeOther($data_personnel_income_key);
                                        $data_total = [
                                            'check_other'  => 1,
                                        ];
                                        Salary::updatePersonnelIncome($data_total,$checkPersonnelIncome->id);
                                    }
                                }
                            }
                        }
                        
                        $res=array('Response'=>"Success","Message"=>"Bạn đã cập nhật thành công" );
                    }

                }else{
                    if( $listPersonnel ){
                        foreach ($listPersonnel as $key => $value) {
                            $checkPersonnelIncome = Salary::checkPersonnelIncome($month,$year,$type='',$value);
                            //echo $value;
                            $tmp = Salary::getPersonnelGroupDetail($value,$type=12);
                            $tmp_2 = array();
                            foreach ($tmp as $key_1 => $value_1) {
                                $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

                            }
                            $data = array();
                            foreach ($tmp_2 as $k => $v) {
                                foreach ($tmp_2[$k] as $k_1 => $v_2) {
                                    $data[] = $v_2;
                                }

                            }
                            $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
                            if( count( $checkPersonnelIncome ) >0 ){
                                // echo 3;die;
                                foreach ($data as $key_2 => $value_2) {
                                    $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[12]);

                                    if( count( $tmp_3 )>0 ){
                                        foreach ($tmp_3 as $key_3 => $value_3) {   
                                            if( $value_3->type == 12 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $income_title = $tmp_3[0]->title;
                                                        $income_key = $tmp_3[0]->key_special;
                                                        $income_value = $tmp_3[0]->value;
                                                    }else{
                                                    }
                                                }else{
                                                    $income_value = 0;
                                                    $income_key = $tmp_3[0]->key_special;
                                                    $income_title = $tmp_3[0]->title;
                                                }

                                                $data_personnel_income_key = [
                                                    'income_title'       =>  $income_title,
                                                    'income_key'       =>  $income_key,
                                                    'income_value'       =>  $income_value,
                                                    'personnel_income_id'=> $checkPersonnelIncome->id
                                                ];
                                                Salary::insertPersonnelIncomeOther($data_personnel_income_key);
                                                $data_total = [
                                                    'check_other'  => 1,
                                                ];
                                                Salary::updatePersonnelIncome($data_total,$checkPersonnelIncome->id);
                                            }
                                        }
                                    }
                                }
                            }else{
                                $data_personnel_income = [
                                    'month'         =>  $month,
                                    'year'          =>  $year,
                                    'date_value'    =>  $date_value,
                                    'check_other'   =>  1,
                                    'personnel_id'  =>  $value,
                                    'created_at'    =>  date('Y-m-d'),
                                    'created_by'    => Auth::user()->id,
                                    'status'        => 1,
                                ];
                                Salary::insertPersonnelIncome($data_personnel_income);
                                $id =  \DB::getPdo()->lastInsertId(); 
                                foreach ($data as $key_2 => $value_2) {
                                    $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[12]);
                                    if( count( $tmp_3 )>0 ){
                                        foreach ($tmp_3 as $key_3 => $value_3) {   
                                            //Thưởng ngày lễ
                                            if( $value_3->type == 12 ){
                                                if( $tmp_3[0]->applied_month =="0" || $tmp_3[0]->applied_month== $month){
                                                    if( $tmp_3[0]->is_fixed==1 ){
                                                        $income_title = $tmp_3[0]->title;
                                                        $income_key = $tmp_3[0]->key_special;
                                                        $income_value = $tmp_3[0]->value;
                                                    }else{
                                                    }
                                                }else{
                                                    $income_value = 0;
                                                    $income_key = $tmp_3[0]->key_special;
                                                    $income_title = $tmp_3[0]->title;
                                                }

                                                $data_personnel_income_key = [
                                                    'income_title'       =>  $income_title,
                                                    'income_key'       =>  $income_key,
                                                    'income_value'       =>  $income_value,
                                                    'personnel_income_id'=>$id
                                                ];
                                                Salary::insertPersonnelIncomeOther($data_personnel_income_key);
                                            }
                                        }
                                    }
                                }
                            }
                            $res=array('Response'=>"Success","Message"=>"Bạn đã tính thành công" );
                        }
                    }else{
                        $res=array('Response'=>"Error","Error"=>"Bạn chưa chấm công trong tháng cần tính đó" );
                    }
                }

            // }else{
            //     $res=array('Response'=>"Error","Error"=>"Kiểm tra lại thời gian áp dụng công thức tính" );
            // }

            echo json_encode($res);
        }
    }

    // Đề xuất tăng lương ĐX
    public function getSalaryPropose( Request $request ){
        $personnel_id = Auth::user()->id;
        $dateCurrent = date('Y-m-d');
        $data = array();
        $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : $time = date('Y').'-12';
        $turns = BatvHelper::getTurnsDefault();

        if( isset( $_GET['frequency'] ) ){
            if( date('m') >= 1 && date('m') <= 6 ){
                $time = (  $_GET['frequency'] == 1 ) ? date('Y', strtotime(date('Y').' -1 year')).'-12' : date('Y').'-06';
                $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
            }else{
                $time = (  $_GET['frequency'] == 1 ) ? date('Y').'-06' : date('Y').'-12';
                $turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
            }
        }

        $year = BatvHelper::formatDate($time,"Y-m", $formatDate="Y",$timeFormat="H:i:s",false);
        $department_id = Departments::getInfoDepartment($personnel_id,'id');
        $myfunc =  new Myfunction();
        $tmp[$department_id] = $myfunc->categoryChild($department_id,'departments');

        if( count($tmp)==0 ){
            $ids = array($request->selectDepart);
        }else{
            $ids =  BatvHelper::array_keys_multi($tmp);
        }

        $listPersonnel = Personnel::getAllPersonnelCurrentbyManager($ids);
        $arrPermission = Personnel::getAllPersonnelCurrentbyManager($ids,'personnel_id');
        $arr = Personnel::getAllAdhocSalaryAssessmentbyYear( $turns,$year );
            // echo "<pre>";
            // print_r($arr);
            // echo "</pre>";die;
        foreach ($arr as $key => $value) {
            $date_nlgn = BatvHelper::infoDateSalaryIncreaseNearest( $value->id,$time );
            if( $date_nlgn ){
                // $param = BatvHelper::calculateMonth( BatvHelper::formatDate($date_nlgn,"Y-m-d", $formatDate="Y-m",$timeFormat="H:i:s",false) ,date('Y-12') );
                $param = BatvHelper::calculateMonth( $date_nlgn,$time );
            }else{
                $param = 0;
            }

            $year = BatvHelper::formatDate($time,"Y-m", $formatDate="Y",$timeFormat="H:i:s",false);
            // echo $param."</br>";
            $month = ( $value->salary_frequency )*12;

            //Lấy ra thông tin nhân viên đã được gửi mail thông báo tăng lương
            $arr_AdhocSalaryAssessment = AdhocSalaryAssessment::listPersonnelSalaryAssessmentUnexpected($turns,$year);

            //Lấy ra thông tin nhân viên dc tăng lương đột xuất
            $arr_AdhocSalaryAssessment_Dx = AdhocSalaryAssessment::where('type',2)->where('turns',$turns)->where('year',$year)->lists('personnel_id')->toArray();
            // echo "<pre>";
            // print_r($arr_AdhocSalaryAssessment_Dx);
            // echo "</pre>";die;
            $status = ( in_array($value->id, $arr_AdhocSalaryAssessment ) )?1:'';

            if( in_array($value->id, $arr_AdhocSalaryAssessment_Dx) ){
            
                $type = (in_array($value->id, $arr_AdhocSalaryAssessment_Dx))?0:1;// 1 - định kỳ, 0 - đột xuất
                $date_dxnl = date("Y-m-d", strtotime("+".$month." month", strtotime($date_nlgn)));
                $number_month_nlgn = ( empty( $date_nlgn ) )?'':BatvHelper::calculateMonth( $date_nlgn,$time.'-01' );
                $year_date_dxnl = BatvHelper::formatDate($date_dxnl,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false);
                //echo $number_month_nlgn;die;
                //Chỉ lấy ra thông tin những ông được tăng trong năm hiện tại
                // if( $number_month_nlgn>0  ){
                    $data[] = array(
                                 'personnel_id'=>$value->id,
                                 'fullname'=>$value->fullname,
                                 'hsl_ht'=> BatvHelper::getRatioByTime( $value->id,$time ),
                                 'date_nlgn'=>$date_nlgn,
                                 'number_month_nlgn'=> $number_month_nlgn,
                                 'date_dxnl'=> $date_dxnl,
                                 'salary_frequency'=> $value->salary_frequency,
                                 'status'=>$status,
                                 'type'=>$type,
                               );
                // }
            }


        }

        return view('layouts.luongthuong.server.dexuatnangluongdotxuat',['data'=>$data,'listPersonnel'=>$listPersonnel,'arrPermission'=>$arrPermission]);
    }

    public function postSalaryPropose( Request $request){
        $turns = BatvHelper::getTurnsDefault();
        if( $request->personnel_id ){
            foreach ($request->personnel_id as $k => $v) {
                $data[] =  array('personnel_id'=>$v,'year'=>date('Y'),'turns'=>$turns,'type'=>2);
            }  
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";die;
            Personnel::insertAdhocSalaryAssessment($data); 
        }
        return back()->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        
    }
    public function deleteSalaryPropose($personnel_id){
        Personnel::deleteAdhocSalaryAssessmentbyYear($personnel_id); 
        return back()->with(['flash_message_succ' => 'Xóa thông tin thành công']);
        
    }

    //DSNV truy lĩnh
    public function getSalaryTL( Request $request){
        $dateCurrent = date('Y-m-d');
        $data = array();
        $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : date('Y').'-12';
        $turns = BatvHelper::getTurnsDefault();
        $apply_from = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-01-01' : date('Y').'-07-01';
        if( isset( $_GET['frequency'] ) ){
            if( date('m') >= 1 && date('m') <= 6 ){
                $time = (  $_GET['frequency'] == 1 ) ? date('Y', strtotime(date('Y').' -1 year')).'-12' : date('Y').'-06';
                $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
                $apply_from = date('Y').'-01-01';
            }else{
                $time = (  $_GET['frequency'] == 1 ) ? date('Y').'-06' : date('Y').'-12';
                $turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
                $apply_from = date('Y').'-07-01';
            }
        }

        $year = BatvHelper::formatDate($time,"Y-m", $formatDate="Y",$timeFormat="H:i:s",false);
        $myfunc =  new Myfunction();
        $depart = Personnel::listDepartment();
        $select = 0;
        if ($request->input('selectDepart') != '') {
          $select = $request->input('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);

        if( $request->selectDepart !=0 ){
            $myfunc =  new Myfunction();
            $tmp[$select] = $myfunc->categoryChild($request->selectDepart,'departments');
            if( count($tmp)==0 ){
                $ids = array($request->selectDepart);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
            $arr = Salary::listSalaryIncreaseCriterioTL($ids,$apply_from,$year,$turns);
        }else{
            $arr = Salary::listSalaryIncreaseCriterioTL($ids=array(),$apply_from,$year,$turns);
        }

        if( count($arr)>0 ){
            $type = 0;
            foreach ($arr as $key => $value) {
                $month = ( $value->salary_frequency )*12;
                $time_nlgn = BatvHelper::infoDateSalaryIncreaseNearest( $value->personnel_id,$time );

                $apply_from_old =BatvHelper::formatDate($time_nlgn,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",false);
                $day_current_nlgn = BatvHelper::getApplyFromCurrentByTime( $value->personnel_id,$time );
                // echo $day_current_nlgn.'---'.$month_current_nlgn;die;
                $number_tt = BatvHelper::timeSalaryTL($time_nlgn,$value->personnel_id,$month,$time);

                if( $number_tt['month'] >0 ||  $number_tt['days'] > 0 ){
                    $salary_official_default_new = BatvHelper::ltt('',$value->personnel_id,BatvHelper::getApplyFromNearest( $value->personnel_id ),$type=2,'',$option=1,'special');// 100% chính thức

                    $typePersonnelIncome = Salary::checkPersonnelIncome(BatvHelper::formatDate($day_current_nlgn ,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false), BatvHelper::formatDate($day_current_nlgn ,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false),'',$value->personnel_id);

                    if( $typePersonnelIncome ){
                        $tmp = Salary::checkPersonnelIncome(BatvHelper::formatDate($day_current_nlgn ,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false), BatvHelper::formatDate($day_current_nlgn ,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false),'',$value->personnel_id);

                        if( $tmp->price_tl > 0 ){
                            $param_hs = $tmp->price_tl/( $salary_official_default_new - $number_tt['salary_official_default_old'] );
                            $value_tt = $tmp->price_tl;
                        }else{
                            $value_tt =  $number_tt['param']*( $salary_official_default_new - $number_tt['salary_official_default_old'] ) ;
                            $param_hs = $number_tt['param'];
                        }
                        if ($value_tt > 0) {
                            $data[] = array(
                                        'personnel_id'=>$value->personnel_id,
                                        'fullname'=>$value->fullname,
                                        'date_hdct'=> BatvHelper::getTimeSalaryNearest($value->personnel_id)->apply_from,
                                        'hsl_ht'=> BatvHelper::getRatioCurrent($value->personnel_id),
                                        'hsl_old'=> BatvHelper::getRatioByTime($value->personnel_id,$time),
                                        'date_nlgn'=> $day_current_nlgn,
                                        'number_tt'=>  $number_tt,
                                        'param_hs'=>  $param_hs,
                                        'value_tt'=>  $value_tt,
                                        'period'=> ['from'=>date('d/m/Y',strtotime(BatvHelper::formatDate(date("Y-m-d", strtotime("+".$month. "month", strtotime($time_nlgn))),"Y-m-d", $formatDate="m/d/Y",$timeFormat="H:i:s",false) . "+0 days")),'to'=>date('d/m/Y',strtotime($day_current_nlgn . "-1 days"))],
                                        'type'     => $typePersonnelIncome->type
                                       );
                        }
                    }
                }
               
            }
        }
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";die;
        return view('layouts.luongthuong.server.dsnvtruylinh',['data'=>$data,'department'=>$select_depart]);
    }

    // public function getSalaryIncreaseCriterion( Request $request){
    //     $dateCurrent = date('Y-m-d');
    //     $data = [];
    //     $ids = [];
    //     $myfunc =  new Myfunction();
    //     $depart = Personnel::listDepartment();
    //     $select = 0;
    //     if ($request->input('selectDepart') != '') {
    //       $select = $request->input('selectDepart');
    //     }
    //     $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);

    //     if( $request->selectDepart !=0 ){
    //         $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
    //         if( count($tmp)==0 ){
    //             $ids = array($request->selectDepart);
    //         }else{
    //             $ids =  BatvHelper::array_keys_multi($tmp);
    //         }
    //         $arr = Salary::listSalaryIncreaseCriterio( $request,$ids,$dateCurrent );
    //     }else{
    //         $department_id = Departments::getInfoDepartment(Auth::user()->id, 'id');
    //         $tmp[$department_id] = $myfunc->categoryChild($department_id,'departments');
    //         $ids =  BatvHelper::array_keys_multi($tmp);
    //         $arr = Salary::listSalaryIncreaseCriterio($request, $ids, $dateCurrent);
    //     }

    //     $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : $time = date('Y').'-12';
    //     $turns = BatvHelper::getTurnsDefault();
        
    //     if( isset( $_GET['frequency'] ) ){
    //         if( date('m') >= 1 && date('m') <= 6 ){
    //             $time = (  $_GET['frequency'] == 1 ) ? date('Y', strtotime(date('Y').' -1 year')).'-12' : date('Y').'-06';
    //             $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
    //         }else{
    //             $time = (  $_GET['frequency'] == 1 ) ? date('Y').'-06' : date('Y').'-12';
    //             $turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
    //         }
    //     }

    //     $year = BatvHelper::formatDate($time,"Y-m", $formatDate="Y",$timeFormat="H:i:s",false);
    //     //Lấy ra thông tin nhân viên đã được gửi mail thông báo tăng lương
    //     $arr_AdhocSalaryAssessment = AdhocSalaryAssessment::listPersonnelSalaryAssessmentUnexpected($turns,$year);
    //     //Lấy ra thông tin nhân viên bị xóa
    //     $arr_AdhocSalaryRemove = AdhocSalaryAssessment::listPersonnelSalaryRemove($turns, $year);
    //     //Lấy ra thông tin nhân viên dc tăng lương đột xuất
    //     $arr_AdhocSalaryAssessment_Dx = AdhocSalaryAssessment::where('type',2)->where('turns',$turns)->where('year',$year)->lists('personnel_id')->toArray();

    //     if( count($arr)>0 ){
    //         $listEmail  = [];
    //         foreach ($arr as $key => $value) {
    //             $month = ( $value->salary_frequency )*12;
    //             $date_nlgn = BatvHelper::infoDateSalaryIncreaseNearest( $value->personnel_id ,$time);

    //             if( $date_nlgn ){
    //                 $param = BatvHelper::calculateMonthSalary($date_nlgn, $time, $value->personnel_id, $month);
    //             }else{
    //                 $param = 0;
    //             }

                
    //             if( $month > 0 && !in_array($value->personnel_id, $arr_AdhocSalaryRemove)){
    //                 if( $param >= $month || in_array($value->personnel_id, $arr_AdhocSalaryAssessment_Dx) ){
    //                     $type = (in_array($value->personnel_id, $arr_AdhocSalaryAssessment_Dx))?0:1;// 1 - định kỳ, 0 - đột xuất
    //                     $date_dxnl = date("Y-m-d", strtotime("+".$month." month", strtotime($date_nlgn)));
    //                     $number_month_nlgn = ( empty( $date_nlgn ) )?'':BatvHelper::calculateMonth( $date_nlgn,$time.'-01' );
    //                     $year_date_dxnl = BatvHelper::formatDate($date_dxnl,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false);
    //                     $status = ( in_array($value->personnel_id, $arr_AdhocSalaryAssessment ) )?1:'';
    //                     //Chỉ lấy ra thông tin những ông được tăng trong năm hiện tại
    //                     // if( $number_month_nlgn>0  ){
    //                         $data[] = array(
    //                                      'personnel_id'=>$value->personnel_id,
    //                                      'fullname'=>$value->fullname,
    //                                      'date_hdct'=> $value->apply_from,
    //                                      'hsl_ht'=> BatvHelper::getRatioByTime( $value->personnel_id,$time ),
    //                                      'date_nlgn'=>$date_nlgn,
    //                                      'number_month_nlgn'=> $number_month_nlgn,
    //                                      'date_dxnl'=> $date_dxnl,
    //                                      'salary_frequency'=> $value->salary_frequency,
    //                                      'status'=>$status,
    //                                      'type'=>$type,
    //                                    );
    //                         $listEmail[] = $value->personnel_id;
    //                     // }
    //                 }
    //             }
    //         }
    //             // echo "<pre>";
    //             // print_r($data);die;
    //             // echo "</pre>";die;

    //         // Gửi Email cho nhân viên + CC mail cho người gửi và list danh sách cấu hình cài đặt gửi mail
    //         if( isset($_GET['sendemail']) ){
    //             if( $data ){

    //                 foreach ($data as $key => $value) {
    //                     $month_dxnl = (int)BatvHelper::formatDate($value['date_dxnl'],"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
    //                     $turns = BatvHelper::getTurnsDefault();

    //                     $check = AdhocSalaryAssessment::where([ ['personnel_id',$value['personnel_id'] ],['turns',$turns],['year',date('Y')]])->count();

    //                     if( $check > 0 ){//Update
    //                         AdhocSalaryAssessment::where([ ['personnel_id',$value['personnel_id'] ],['turns',$turns],['year',date('Y')] ])->update( ['time_send_mail'=>date('Y-m-d') ] );
    //                     }else{
    //                         Personnel::insertAdhocSalaryAssessment([ 'personnel_id'=>$value['personnel_id'],'year'=>date('Y'),'time_send_mail'=>date('Y-m-d'),'turns'=>$turns,'type'=>1 ]); 
    //                     }
    //                     unset($check);
                        
    //                 }
    //             }

    //             $result = EmailConfig::getListEmailbyidPersonnel( $listEmail );
    //             if( $result ){
    //                 foreach ($result as $key => $value) {
    //                     $email[] = $value->email;
    //                 }
    //             }
    //             $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 3 );

    //             $subject = $infoConfigMail->mail_subject;
    //             $content_mail = array(
    //                                 'content'=>$infoConfigMail->mail_content,
    //                                 'subject'=>$subject
    //                             );
    //             $email_cc = [];
    //             if( !empty( $infoConfigMail->mail_to ) ){
    //                 $email_others = explode(",",$infoConfigMail->mail_to);
    //                 foreach ($email_others as $key => $value) {
    //                     $email_cc[] = User::where('id',$value)->value('email');
    //                 }
    //             }

    //             $email_cc[] = User::where('id',Auth::user()->id)->value('email');
    //             // Gửi cho từng người, CC cho click gửi trong quản trị  + người đc cấu hình trong phần mail
    //             foreach ($email as $key_email => $value_email) {
    //                 $email_cc_final = array_unique($email_cc);

    //                 if( $infoConfigMail->cc_email == 1 ){ // Nếu tích chọn CC cho quản lý tương ứng với từng nhận viên
    //                     $arr_manager_id = $email_cc_tmp = [];
    //                     $personnel_id = Personnel::where('email',$value_email)->value('id');
    //                     $infoUser = Personnel::getCurrentInfo($personnel_id);
    //                     $infoManager = Personnel::getCurrentInfo( $infoUser->manager_id );
    //                     $myfunc =  new Myfunction();
    //                     $tmp =  $myfunc->categoryParent($infoUser->department_id);   
    //                     $department_id =  BatvHelper::array_keys_multi($tmp);

    //                     foreach ($department_id as $value) {
    //                         $arr_manager_id[] = Evaluation::infoDepartment( $value );
    //                     }
    //                     foreach ($arr_manager_id as $value) {
    //                         $email_cc_tmp[] = Personnel::getCurrentInfo($value)->email;
    //                     }

    //                     $email_cc_final = array_merge($email_cc_tmp,$email_cc);
    //                     $email_cc_final = array_unique($email_cc_final);
    //                 }
    //                 \Mail::send('emails.notification_salaryAssessment',$content_mail, function ($message) use ($value_email,$email_cc_final, $subject)
    //                 {
    //                     $message->from('nhansu@tohsoft.com', 'TOH');
    //                     $message->cc($email_cc_final);
    //                     $message->to($value_email)->subject($subject);
    //                 });
    //                 unset($email_cc_final);
    //             }
    //             return back()->with(['flash_message_succ' => 'Gửi Email thành công']);
    //         }
    //     }

    //     return view('layouts.luongthuong.server.dsnvdutieuchuantangluong',['data'=>$data,'department'=>$select_depart,'ids'=>$ids]);
    // }
    public function getSalaryIncreaseCriterion( Request $request){
        $dateCurrent = date('Y-m-d');
        $data = [];
        $ids = [];
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
            $arr = Salary::listSalaryIncreaseCriterio( $request,$ids,$dateCurrent );
        }else{
            $department_id = Departments::getInfoDepartment(Auth::user()->id, 'id');
            $tmp[$department_id] = $myfunc->categoryChild($department_id,'departments');
            $ids =  BatvHelper::array_keys_multi($tmp);
            $arr = Salary::listSalaryIncreaseCriterio($request, $ids, $dateCurrent);
        }

        $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : $time = date('Y').'-12';
        $turns = BatvHelper::getTurnsDefault();
        
        if( isset( $_GET['frequency'] ) ){
            if( date('m') >= 1 && date('m') <= 6 ){
                $time = (  $_GET['frequency'] == 1 ) ? date('Y', strtotime(date('Y').' -1 year')).'-12' : date('Y').'-06';
                $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
            }else{
                $time = (  $_GET['frequency'] == 1 ) ? date('Y').'-06' : date('Y').'-12';
                $turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
            }
        }

        $year = BatvHelper::formatDate($time,"Y-m", $formatDate="Y",$timeFormat="H:i:s",false);
        //Lấy ra thông tin nhân viên đã được gửi mail thông báo tăng lương
        $arr_AdhocSalaryAssessment = AdhocSalaryAssessment::listPersonnelSalaryAssessmentUnexpected($turns,$year);
        //Lấy ra thông tin nhân viên bị xóa
        $arr_AdhocSalaryRemove = AdhocSalaryAssessment::listPersonnelSalaryRemove($turns, $year);
        //Lấy ra thông tin nhân viên dc tăng lương đột xuất
        $arr_AdhocSalaryAssessment_Dx = AdhocSalaryAssessment::where('type',2)->where('turns',$turns)->where('year',$year)->lists('personnel_id')->toArray();

        if( count($arr)>0 ){
            $listEmail  = [];
            foreach ($arr as $key => $value) {
                $date_nlgn = BatvHelper::infoDateSalaryIncreaseNearest( $value->personnel_id ,$time);
                if( $date_nlgn ){
                    // $param = BatvHelper::calculateMonth( BatvHelper::formatDate($date_nlgn,"Y-m-d", $formatDate="Y-m",$timeFormat="H:i:s",false) ,date('Y-12') );
                    $param = BatvHelper::calculateMonth($date_nlgn,$time );
                }else{
                    $param = 0;
                }


                $month = ( $value->salary_frequency )*12;

                
                if( $month > 0 && !in_array($value->personnel_id, $arr_AdhocSalaryRemove)){
                    if( $param >= $month || in_array($value->personnel_id, $arr_AdhocSalaryAssessment_Dx) ){
                        $type = (in_array($value->personnel_id, $arr_AdhocSalaryAssessment_Dx))?0:1;// 1 - định kỳ, 0 - đột xuất
                        $date_dxnl = date("Y-m-d", strtotime("+".$month." month", strtotime($date_nlgn)));
                        $number_month_nlgn = ( empty( $date_nlgn ) )?'':BatvHelper::calculateMonth( $date_nlgn,$time.'-01' );
                        $year_date_dxnl = BatvHelper::formatDate($date_dxnl,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false);
                        $status = ( in_array($value->personnel_id, $arr_AdhocSalaryAssessment ) )?1:'';
                        //Chỉ lấy ra thông tin những ông được tăng trong năm hiện tại
                        // if( $number_month_nlgn>0  ){
                            $data[] = array(
                                         'personnel_id'=>$value->personnel_id,
                                         'fullname'=>$value->fullname,
                                         'date_hdct'=> $value->apply_from,
                                         'hsl_ht'=> BatvHelper::getRatioByTime( $value->personnel_id,$time ),
                                         'date_nlgn'=>$date_nlgn,
                                         'number_month_nlgn'=> $number_month_nlgn,
                                         'date_dxnl'=> $date_dxnl,
                                         'salary_frequency'=> $value->salary_frequency,
                                         'status'=>$status,
                                         'type'=>$type,
                                       );
                            $listEmail[] = $value->personnel_id;
                        // }
                    }
                }
            }

            // Gửi Email cho nhân viên + CC mail cho người gửi và list danh sách cấu hình cài đặt gửi mail
            if( isset($_GET['sendemail']) ){
                if( $data ){

                    foreach ($data as $key => $value) {
                        $month_dxnl = (int)BatvHelper::formatDate($value['date_dxnl'],"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false);
                        $turns = BatvHelper::getTurnsDefault();

                        $check = AdhocSalaryAssessment::where([ ['personnel_id',$value['personnel_id'] ],['turns',$turns],['year',date('Y')]])->count();

                        if( $check > 0 ){//Update
                            AdhocSalaryAssessment::where([ ['personnel_id',$value['personnel_id'] ],['turns',$turns],['year',date('Y')] ])->update( ['time_send_mail'=>date('Y-m-d') ] );
                        }else{
                            Personnel::insertAdhocSalaryAssessment([ 'personnel_id'=>$value['personnel_id'],'year'=>date('Y'),'time_send_mail'=>date('Y-m-d'),'turns'=>$turns,'type'=>1 ]); 
                        }
                        unset($check);
                        
                    }
                }

                $result = EmailConfig::getListEmailbyidPersonnel( $listEmail );
                if( $result ){
                    foreach ($result as $key => $value) {
                        $email[] = $value->email;
                    }
                }
                $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 3 );

                $subject = $infoConfigMail->mail_subject;
                $content_mail = array(
                                    'content'=>$infoConfigMail->mail_content,
                                    'subject'=>$subject
                                );
                $email_cc = [];
                if( !empty( $infoConfigMail->mail_to ) ){
                    $email_others = explode(",",$infoConfigMail->mail_to);
                    foreach ($email_others as $key => $value) {
                        $email_cc[] = User::where('id',$value)->value('email');
                    }
                }

                $email_cc[] = User::where('id',Auth::user()->id)->value('email');
                // Gửi cho từng người, CC cho click gửi trong quản trị  + người đc cấu hình trong phần mail
                foreach ($email as $key_email => $value_email) {
                    $email_cc_final = array_unique($email_cc);

                    if( $infoConfigMail->cc_email == 1 ){ // Nếu tích chọn CC cho quản lý tương ứng với từng nhận viên
                        $arr_manager_id = $email_cc_tmp = [];
                        $personnel_id = Personnel::where('email',$value_email)->value('id');
                        $infoUser = Personnel::getCurrentInfo($personnel_id);
                        $infoManager = Personnel::getCurrentInfo( $infoUser->manager_id );
                        $myfunc =  new Myfunction();
                        $tmp =  $myfunc->categoryParent($infoUser->department_id);   
                        $department_id =  BatvHelper::array_keys_multi($tmp);

                        foreach ($department_id as $value) {
                            $arr_manager_id[] = Evaluation::infoDepartment( $value );
                        }
                        foreach ($arr_manager_id as $value) {
                            $email_cc_tmp[] = Personnel::getCurrentInfo($value)->email;
                        }

                        $email_cc_final = array_merge($email_cc_tmp,$email_cc);
                        $email_cc_final = array_unique($email_cc_final);
                    }
                    \Mail::send('emails.notification_salaryAssessment',$content_mail, function ($message) use ($value_email,$email_cc_final, $subject)
                    {
                        $message->from('nhansu@tohsoft.com', 'TOH');
                        $message->cc($email_cc_final);
                        $message->to($value_email)->subject($subject);
                    });
                    unset($email_cc_final);
                }
                return back()->with(['flash_message_succ' => 'Gửi Email thành công']);
            }
        }

        return view('layouts.luongthuong.server.dsnvdutieuchuantangluong',['data'=>$data,'department'=>$select_depart,'ids'=>$ids]);
    }

    public function getAllSalary( Request $request){
        if( isset($_GET['export_excel']) ){
            \Excel::create('tong_hop_luong_'.$request->selectMonth.'_'.$request->selectYear, function($excel) use($request){
                $excel->sheet('Sheetname', function ($sheet)use($request) {
                    $title = 'Thông tin lương tổng hợp '.$request->selectMonth.'/'.$request->selectYear;

                    $sheet->mergeCells('A1:G1');
                    $sheet->row(1, function ($row) {
                        $row->setBackground('green');
                        $row->setAlignment('center');
                        $row->setFontSize(15);
                        $row->setFontWeight('600');
                        $row->setFontColor('#fff');
                    });

                    $sheet->row(1, array($title) );

                    $sheet->row(2, function ($row) {
                        $row->setFontWeight('600');
                        $row->setAlignment('center');
                    });

                    $sheet->appendRow(2, array(
                        'STT','Họ và tên', 'Chi tiết lương','Chi tiết thưởng','Chi tiết bảo hiểm + thuế','Các khoản khác','Tổng'
                    ));

                    $sheet->row($sheet->getHighestRow(), function ($row) {
                        $row->setFontWeight('bold');
                    });

                    $data = Salary::listAllSalary($request);
                    $tmp=1;
                    foreach ($data as $key => $value) {
                        $sheet->appendRow($tmp+2, array(
                            $tmp,$value->fullname,BatvHelper::formatPrice($value->check_salary),BatvHelper::formatPrice($value->check_bonus),BatvHelper::formatPrice($value->total_tax_insurance),BatvHelper::formatPrice($value->total_other), BatvHelper::formatPrice($value->check_salary + $value->check_bonus + $value->total_tax_insurance+$value->total_other)
                        ));
                        $tmp++;
                    }
                });
            })->export('xls');
        }elseif(isset($_GET['send_email'])){
            $listAllSalary = Salary::listAllSalary($request);
            $listEmail = array();

            foreach ($listAllSalary as $key => $value) {
                $listEmail[] = $value->email;
            }

            $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 2 );
            $subject = $infoConfigMail->mail_subject." tháng ".$request->selectMonth."/".$request->selectYear;
            $content_mail = array(
                                'content'=>$infoConfigMail->mail_content,
                            );

            \Mail::send('emails.notification_salary', $content_mail,  function ($message) use ($listEmail, $subject) {
                $message->from('nhansu@tohsoft.com', 'TOH');
                $message->to($listEmail)->subject($subject);
            });
            return back()->with(['flash_message_succ' => 'Bạn đã gửi mail thành công']);
        }else{
            $myfunc =  new Myfunction();
            $depart = Personnel::listDepartment();
            $select = 0;
            if ($request->input('selectDepart') != '') {
              $select = $request->input('selectDepart');
            }
            $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);

            $ids = [];
            if( $request->selectDepart !=0 ){
                $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
                if( count($tmp)==0 ){
                    $ids = array($request->selectDepart);
                }else{
                    $ids =  BatvHelper::array_keys_multi($tmp);
                }
            }
            $listAllSalary = Salary::listAllSalary($request,'',$ids);
            $others = Salary::listSalaryOther($request);
            $data = array();

            if( $others ){
                foreach ($others as $key => $value) {
                    if( !isset($data['list'][$value->personnel_id]->fullname) ){
                        $data['list'][$value->personnel_id]['fullname'] = $value->fullname;
                        $data['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                    }else{
                        $data['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                    }

                }
            }
            // dd($listAllSalary);
            return view('layouts.luongthuong.server.tonghop',['data'=>$listAllSalary,'others'=>$data,'department'=>$select_depart]);
        }

    }

    public function getAllClient(Request $request){
        $id = Auth::user()->id;
        $listAllSalary = Salary::listAllSalary($request,$id);
        $others = Salary::listSalaryOther($request);
        $data = array();
        if( $others ){
            foreach ($others as $key => $value) {
                if( !isset($data['list'][$value->personnel_id]->fullname) ){
                    $data['list'][$value->personnel_id]['fullname'] = $value->fullname;
                    $data['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                }else{
                    $data['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                }

            }
        }
        return view('layouts.luongthuong.client.tonghopchitiet',['data'=>$listAllSalary,'others'=>$data]);
    }

    public function getSalaryClient( Request $request ){
        $id = Auth::user()->id;
        $data = Salary::listSalary($request,$id);
        return view('layouts.luongthuong.client.thongtinluong',['data'=>$data]);
    }

    public function getAllowanceClient( Request $request ){
        $id = Auth::user()->id;
        $data = Salary::listBonus($request,$id);
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";die;
        return view('layouts.luongthuong.client.thongtinthuongphucap',['data'=>$data]);
    }

    public function getTaxInsurranceClient( Request $request ){
        $id = Auth::user()->id;
        $data = Salary::listTaxInsurrance($request,$id);
        return view('layouts.luongthuong.client.thongtinthuebaohiem',['data'=>$data]);
    }
    public function getSalaryOtherClient( Request $request  ){
        $id = Auth::user()->id;
        $arr = Salary::listSalaryOther($request,$id);
        $data = array();
        if( $arr ){
            foreach ($arr as $key => $value) {
                if( !isset($data['list'][$value->personnel_id]->fullname) ){
                    $data['list'][$value->personnel_id]['fullname'] = $value->fullname;
                    $data['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                    $data['list'][$value->personnel_id]['salary_trial_default'] = $value->salary_trial_default;
                    $data['list'][$value->personnel_id]['salary_official_default'] = $value->salary_official_default;
                }else{
                    $data['list'][$value->personnel_id]['income_value'][$value->income_title] = $value->income_value;
                }

            }
        }
        return view('layouts.luongthuong.client.thongtincackhoankhac',['data'=>$data]);
    }

    //Cấu hình chu kỳ xét tăng lương
    public function getSettingPeriodSalary()
    {
        $data = PeriodSalary::all();

        return view('layouts.luongthuong.cauhinhchukyxettangluong.index',['data'=>$data]);
    }

    public function postSettingPeriodSalary( Request $request ){
        $rules = [
            'value' => 'required|unique:salary_period,value',
        ];
        $messages = [
            'value.required'=>'Giá trị không được để trống',
            'value.unique'=>'Giá trị đã tồn  tại',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = new PeriodSalary;
            $item->value =  $request->value;
            $item->description =  $request->description;
            $item->save();
            return redirect()->route('getSettingPeriodSalary')->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }
    public function deleteSettingPeriodSalary( Request $request ){
        PeriodSalary::destroy( $request->id );
        return redirect()->route('getSettingPeriodSalary')->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

    public function getSettingPeriodSalaryEdit( Request $request ){
        $data = PeriodSalary::find($request->id);
        return view('layouts.luongthuong.cauhinhchukyxettangluong.edit',['data'=>$data]);
    }

    public function postSettingPeriodSalaryEdit(Request $request ){
        $rules = [
            'value' => 'required|unique:salary_period,value,'.$request->id,
        ];
        $messages = [
            'value.required'=>'Giá trị không được để trống',
            'value.unique'=>'Giá trị đã tồn  tại',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = PeriodSalary::find($request->id);
            $item->value =  $request->value;
            $item->description =  $request->description;
            $item->save();
            return redirect()->route('getSettingPeriodSalaryEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }

    }

    public function getSalaryDefaultAjax( Request $request ){
        if( $request->ajax() ){
            $result = BatvHelper::ltt('',$request->personnel_id,date("Y-m-d"),$type=1,'',$option=1,$request->param);
            return BatvHelper::formatPrice($result);
        }
    }

    public function getWelfareFundsListClient(Request $request){
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
        return view('layouts.luongthuong.client.welfarefunds',['data'=>$data,'funds_id_default'=>$funds_id_default,'infoTotalPriceWelfareFunds'=>$infoTotalPriceWelfareFunds,'infoSpendMoneyWelfareFunds'=>$infoSpendMoneyWelfareFunds,'infoSpendMoneyWelfareFundsbyMonth'=> $infoSpendMoneyWelfareFundsbyMonth]);
    }

    public function approvalSalaryTLAjax(Request $request){
        if( $request->ajax() ){
            $infoIdPersonnelIncome = Salary::checkPersonnelIncome($request->month,$request->year,'',$request->id)->id;
            if( $infoIdPersonnelIncome ){
                $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : date('Y').'-12';

                if ($request->frequency != '') {
                    if( date('m') >= 1 && date('m') <= 6 ){
                        $time = (  $_GET['frequency'] == 1 ) ? date('Y', strtotime(date('Y').' -1 year')).'-12' : date('Y').'-06';
                    }else{
                        $time = (  $_GET['frequency'] == 1 ) ? date('Y').'-06' : date('Y').'-12';
                    }
                }

                Salary::updatePersonnelIncome(['type'=>1],$infoIdPersonnelIncome);
                $arr = [
                    'income_title'          => 'Tiền truy lĩnh',
                    'income_value'          =>  $request->income_value,
                    'personnel_income_id'   =>  $infoIdPersonnelIncome,
                ];
                Salary::insertPersonnelIncomeOther($arr);



                $month = ( BatvHelper::infoPersonnelSpecial($request->id,'salary_frequency') )*12;
                $time_nlgn = BatvHelper::infoDateSalaryIncreaseNearest( $request->id,$time );
                $apply_from_old =BatvHelper::formatDate($time_nlgn,"Y-m-d", $formatDate="Y-m-d",$timeFormat="H:i:s",false);
                $day_current_nlgn = BatvHelper::getApplyFromCurrentByTime( $request->id,$time );
                // echo $day_current_nlgn.'---'.$month_current_nlgn;die;
                $number_tt = BatvHelper::timeSalaryTL($time_nlgn,$request->id,$month,$time);
                $salary_official_default_new = BatvHelper::ltt('',$request->id,BatvHelper::getApplyFromNearest( $request->id),$type=2,'',$option=1,'special');
                $tmp = Salary::checkPersonnelIncome(BatvHelper::formatDate($day_current_nlgn ,"Y-m-d", $formatDate="m",$timeFormat="H:i:s",false), BatvHelper::formatDate($day_current_nlgn ,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false),'',$request->id);

                if( $tmp->price_tl > 0 ){
                    $param_hs = $tmp->price_tl/( $salary_official_default_new - $number_tt['salary_official_default_old'] );
                    $value_tt = $tmp->price_tl;
                }else{
                    $value_tt =  $number_tt['param']*( $salary_official_default_new - $number_tt['salary_official_default_old'] ) ;
                    $param_hs = $number_tt['param'];
                }

                // Gửi Email cho nhân viên khi được duyệt tiền truy lĩnh
                $infoUser = Personnel::getCurrentInfo(  $request->id  );
                $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 8 );
                $subject = $infoConfigMail->mail_subject;
                $content_mail = array(
                                    'content'=>$infoConfigMail->mail_content,
                                    'subject'=>$subject,
                                    'fullname'=> BatvHelper::infoPersonnelSpecial($request->id,'fullname'),
                                    'date_hdct'=> BatvHelper::getTimeSalaryNearest($request->id)->apply_from,
                                    'hsl_ht'=> BatvHelper::getRatioCurrent($request->id),
                                    'hsl_old'=> BatvHelper::getRatioByTime($request->id,$time),
                                    'date_nlgn'=> $day_current_nlgn,
                                    'number_tt'=>  $number_tt,
                                    'value_tt'=> $value_tt,
                                    'param_hs'=>$param_hs,
                                    'period'=> ['from'=>date('d/m/Y',strtotime(BatvHelper::formatDate(date("Y-m-d", strtotime("+".$month. "month", strtotime($time_nlgn))),"Y-m-d", $formatDate="m/d/Y",$timeFormat="H:i:s",false) . "+0 days")),'to'=>date('d/m/Y',strtotime($day_current_nlgn . "-1 days"))],
                                );

                $email = Personnel::getCurrentInfo($request->id)->email;

                // CC mail
                $email_cc[] = User::where('id',Auth::user()->id)->value('email');

                if( !empty( $infoConfigMail->mail_to ) ){
                    $email_others = explode(",",$infoConfigMail->mail_to);
                    foreach ($email_others as $key => $value) {
                        $email_cc[] = User::where('id',$value)->value('email');
                    }
                }

                if( $infoConfigMail->cc_email == 1 ){
                    $infoManager = Personnel::getCurrentInfo( $infoUser->manager_id );
                    $myfunc =  new Myfunction();
                    $tmp=  $myfunc->categoryParent($infoUser->department_id);   
                    $department_id =  BatvHelper::array_keys_multi($tmp);
                    foreach ($department_id as $value) {
                        $arr_manager_id[] = Evaluation::infoDepartment( $value );
                    }
                    foreach ($arr_manager_id as $value) {
                        if( $request->id != $value ){
                            $email_cc[] = Personnel::getCurrentInfo( $value )->email;
                        }
                    }
                }
                $email_cc = array_unique($email_cc);
                \Mail::send('emails.notification_salaryTL',$content_mail, function ($message) use ($email,$email_cc, $subject)
                {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->cc($email_cc);
                    $message->to($email)->subject($subject);
                });
                $res=array('Response'=>"Success","Message"=>"Phê duyệt thành công");

            }else{
                $res=array('Response'=>"Error","Error"=>"Xin vui lòng kiểm tra lại thông tin");
            }
            echo json_encode($res);
        }
    }

    public function editSalaryTLAjax(Request $request){
        if( $request->ajax() ){
            if( $request->check == 1 ){
                $infoIdPersonnelIncome = Salary::checkPersonnelIncome($request->month,$request->year,'',$request->id)->id;
                if( $infoIdPersonnelIncome ){
                    Salary::updatePersonnelIncome(['price_tl'=>$request->income_value_handmade],$infoIdPersonnelIncome);
                    $res=array('Response'=>"Success","Message"=>"Cập nhật thông tin thành công");
                }else{
                    $res=array('Response'=>"Error","Error"=>"Xin vui lòng kiểm tra lại thông tin");
                } 
            }else{
                $res=array('Response'=>"Error","Error"=>"Số tiền không được để trống và phải là số nguyên dương");
            }
 
            echo json_encode($res);
        }
    }

    public function addConfigKiPerformance(Request $request){
        $dateCurrent = date('Y-m-d');
        $data = array();
        $myfunc =  new Myfunction();
        $depart = Personnel::listDepartment();
        $select = 0;
        if ($request->input('selectDepart') != '') {
          $select = $request->input('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        $year = (!empty($request->selectYear)) ? $request->selectYear : date('Y');
        if( $request->selectDepart !=0 ){
            $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
            if( count($tmp)==0 ){
                $ids = array($request->selectDepart);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
            $data = Salary::listConfigKiPerformanceInYear($year,$ids,$dateCurrent );
        }else{
            $data = Salary::listConfigKiPerformanceInYear($year,$ids=array(),$dateCurrent);
        }

        $listPersonnel = Personnel::getAllPersonnelCurrent();

        // Lấy ra danh sách nhân viên đã tồn tại (được thêm rồi), nếu có remove khỏi danh sách Add
        $listPersonnelIsset = KiPerformance::where('year',$year)->lists('personnel_id')->toArray();

        if ($listPersonnelIsset) {
            foreach ($listPersonnel as $key => $value) {
                if (in_array($value->id, $listPersonnelIsset)){
                    unset($listPersonnel[$key]);
                }
            }
        }

        return view('layouts.luongthuong.cauhinhkihieusuatnam.add',['data'=>$data,'department'=>$select_depart,'year'=>$year,'listPersonnel'=>$listPersonnel ]);
    }

    public function postAddConfigKiPerformance(Request $request) {
        $rules = [
            'personnel_ki_performance' =>'required',
        ];
        $messages = [
            'personnel_ki_performance.required' => 'Bạn chưa chọn nhân viên',
            ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->personnel_ki_performance ){
                // $year = (!empty($request->selectYear)) ? $request->selectYear : date('Y');
                foreach ($request->personnel_ki_performance as $key => $value) {
                    $data[] =  array('personnel_id'=>$value, 'ki'=>$request->ki_performance, 'year'=> $request->year);

                }  
                KiPerformance::insert($data); 
            }
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }

    }

    public function postSettingConfigKiRules(Request $request) {
        $rules = [
            'personnel_ki_performance' =>'required',
        ];
        $messages = [
            'personnel_ki_performance.required' => 'Bạn chưa chọn nhân viên',
            ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->personnel_ki_performance ){
                // KiRules::where('year', $request->year)->delete();
                // $year = (!empty($request->selectYear)) ? $request->selectYear : date('Y');
                foreach ($request->personnel_ki_performance as $key => $value) {
                    $data[] =  array('personnel_id'=>$value, 'ki'=>$request->ki_performance, 'year'=> $request->year);

                }  
                KiRules::insert($data); 
            }
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }

    }

    public function editKIAjax(Request $request){
        if( $request->ajax() ){
            KiPerformance::where('personnel_id',$request->personnel_id)->where('year',$request->year)->update(['ki'=>$request->ki]);
            $res=array('Response'=>"Success","Message"=>"Cập nhật thông tin thành công");
            echo json_encode($res);
        }
    }

    public function delKIAjax(Request $request){
        if( $request->ajax() ){
            KiPerformance::where('personnel_id',$request->personnel_id)->where('year',$request->year)->delete();
            $res=array('Response'=>"Success","Message"=>"Xóa thông tin thành công");
            echo json_encode($res);
        }
    }

    public function editKIRulesAjax(Request $request){
        if( $request->ajax() ){
            KiRules::where('personnel_id',$request->personnel_id)->where('year',$request->year)->update(['ki'=>$request->ki]);
            $res=array('Response'=>"Success","Message"=>"Cập nhật thông tin thành công");
            echo json_encode($res);
        }
    }

    public function delKIRulesAjax(Request $request){
        if( $request->ajax() ){
            KiRules::where('personnel_id',$request->personnel_id)->where('year',$request->year)->delete();
            $res=array('Response'=>"Success","Message"=>"Xóa thông tin thành công");
            echo json_encode($res);
        }
    }

    public function getKiRules(Request $request){
        $year = (!empty($request->selectYear)) ? $request->selectYear : date('Y');
        $myfunc =  new Myfunction();
        $depart = Personnel::listDepartment();
        $select = 0;
        $ids = [];

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
        }
        // dd($data);
        $data = Personnel::leftJoin('users','personnel.user_id', '=', 'users.id')
                        ->leftJoin('departments', 'personnel.department_id', '=', 'departments.id')
                        ->leftJoin('ki_noiquy', 'personnel.id', '=', 'ki_noiquy.personnel_id')
                        ->select('personnel.*','departments.title','users.email as tk') 
                        ->where(function ($query_2) use ($ids){
                            if ( !empty($ids) ) {
                                $query_2->whereIn('personnel.department_id',$ids );
                            }            
                        })
                        ->where('ki_noiquy.year','=',$year) 
                        ->where('personnel.status','=',1) 
                        ->orderBy('id', 'asc')
                        ->paginate(10);
        return view('layouts.luongthuong.kinoiquynam.index',['data'=>$data,'department'=>$select_depart,'year'=>$year]);
    }

    public function settingConfigKiRules(Request $request){
        $dateCurrent = date('Y-m-d');
        $data = array();
        $myfunc =  new Myfunction();
        $depart = Personnel::listDepartment();
        $select = 0;
        if ($request->input('selectDepart') != '') {
          $select = $request->input('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
        $year = (!empty($request->selectYear)) ? $request->selectYear : date('Y');
        if( $request->selectDepart !=0 ){
            $tmp[$request->selectDepart] = $myfunc->categoryChild($request->selectDepart,'departments');
            if( count($tmp)==0 ){
                $ids = array($request->selectDepart);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
            $data = Salary::listConfigKiRulesInYear($year,$ids,$dateCurrent );
        }else{
            $data = Salary::listConfigKiRulesInYear($year,$ids=array(),$dateCurrent);
        }

        $listPersonnel = Personnel::getAllPersonnelCurrent();

        // Lấy ra danh sách nhân viên đã tồn tại (được thêm rồi), nếu có remove khỏi danh sách Add
        $listPersonnelIsset = KiRules::where('year',$year)->lists('personnel_id')->toArray();

        if ($listPersonnelIsset) {
            foreach ($listPersonnel as $key => $value) {
                if (in_array($value->id, $listPersonnelIsset)){
                    unset($listPersonnel[$key]);
                }
            }
        }

        return view('layouts.luongthuong.cauhinhkinoiquynam.add',['data'=>$data,'department'=>$select_depart,'year'=>$year,'listPersonnel'=>$listPersonnel ]);
    }

    public function settingKi(){
        return view('layouts.luongthuong.setting-ki');
    }

    // public function postSettingConfigKiRules(Request $request){

    //     KiRules::where(['year'=> $request->year])->delete();
    //     if( $request->personnel_ki_rules ){
    //         foreach ($request->personnel_ki_rules as $key => $value) {
    //             $data[] =  array('personnel_id'=>$value, 'year'=> $request->year);

    //         }  
    //         KiRules::insert($data); 
    //     }

    //     return back()->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
    // }

    public function sendEmailOnlySalary(Request $request)
    {
        $turns = BatvHelper::getTurnsDefault();
        dd($turns);
        Personnel::insertAdhocSalaryAssessment(['personnel_id' => $request->personnel_id, 'year' => date('Y'), 'time_send_mail' => date('Y-m-d'), 'turns' => $turns, 'type' => 1]); 
        $info_user = Personnel::find($request->personnel_id);
        $email[] = $info_user->email;

        $infoConfigMail = EmailConfig::getInfoEmailConfig($type = 3);

        $subject = $infoConfigMail->mail_subject;
        $content_mail = array(
            'content' => $infoConfigMail->mail_content,
            'subject' => $subject
        );
        $email_cc = [];
        if (!empty($infoConfigMail->mail_to)) {
            $email_others = explode(",", $infoConfigMail->mail_to);
            foreach ($email_others as $key => $value) {
                $email_cc[] = User::where('id', $value)->value('email');
            }
        }

        $email_cc[] = User::where('id', Auth::user()->id)->value('email');
        // Gửi cho từng người, CC cho click gửi trong quản trị  + người đc cấu hình trong phần mail
        foreach ($email as $key_email => $value_email) {
            $email_cc_final = array_unique($email_cc);

            if ($infoConfigMail->cc_email == 1) { // Nếu tích chọn CC cho quản lý tương ứng với từng nhận viên
                $arr_manager_id = $email_cc_tmp = [];
                $personnel_id = Personnel::where('email', $value_email)->value('id');
                $infoUser = Personnel::getCurrentInfo($personnel_id);
                $infoManager = Personnel::getCurrentInfo($infoUser->manager_id);
                $myfunc =  new Myfunction();
                $tmp =  $myfunc->categoryParent($infoUser->department_id);
                $department_id =  BatvHelper::array_keys_multi($tmp);

                foreach ($department_id as $value) {
                    $arr_manager_id[] = Evaluation::infoDepartment($value);
                }
                foreach ($arr_manager_id as $value) {
                    $email_cc_tmp[] = Personnel::getCurrentInfo($value)->email;
                }

                $email_cc_final = array_merge($email_cc_tmp, $email_cc);
                $email_cc_final = array_unique($email_cc_final);
            }
            \Mail::send('emails.notification_salaryAssessment', $content_mail, function ($message) use ($value_email, $email_cc_final, $subject) {
                $message->from('nhansu@tohsoft.com', 'TOH');
                $message->cc($email_cc_final);
                $message->to($value_email)->subject($subject);
            });
            unset($email_cc_final);
        }

        return response()->json(['message' => 'Gửi email thành công!', 'status' => 200]);
        
    }

    public function deleteOnlySalary(Request $request)
    {
        $turns = BatvHelper::getTurnsDefault();
        AdhocSalaryAssessment::insert(['personnel_id' => $request->personnel_id, 'turns' => $turns, 'year'=>date('Y'), 'disable' => 1]);
        return response()->json(['message' => 'Đã xóa thành công!', 'status' => 200]);
    }

    public function updatedIncreaseInsurrance(Request $request) {
        $data = Personnel::getAllPersonnelCurrent();

        foreach ($data as $key => $value) {
            $item = Personnel::find($value->id);
            $item->insurrance = $value->insurrance + (int)$request->money;
            $item->save();
        }

        return response()->json(['message' => 'Cập nhật thành công', 'status' => 200]);
    }

}
