<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\BatvHelper;
use App\Models\EmailConfig;
use App\Models\Personnel;
use App\Models\SettingAbsentAttendance;
use App\Models\ExceptionalAttendance;
use Validator; 
use Auth; 

class ConvertController extends Controller
{
    public function getConvert(){
    	return view('layouts.quydoiheso.congthuc');
    }
    public function postConvert(){
        return view('layouts.quydoiheso.congthuc');
    }
    public function getConvertAjax( Request $request){
        if ($request->ajax()) {
            $heso = $request->heso;
            $phucap = 0;
            $ki = 1;
            $kq = 1;
            $htn = 0;
            $lcs = 1150000;
            $Hv = 1.75;
            $Ntt = 22;
            $Ntc = 22;
            if( $request->convert==1 ){
                if( is_numeric($heso) && ($heso >=0) ){
                    $ltn = $heso*$lcs*$htn*0.01 + $heso*$lcs*$Hv*$ki*($Ntt/$Ntc)+ $heso*$lcs*$Hv*$ki*($Ntt/$Ntc)*$kq*$ki ;
                    if( $request->phucap==1 ){
                        $ltn = $ltn + $phucap;
                    }
                    $res=array('Response'=>"Success_ltn","ltn"=>$ltn );
                }else{
                    $res=array('Response'=>"Err","Err"=>"Hệ số phải là số nguyên dương !!!" );
                }

            }elseif( $request->convert==2 ){
                $result = $request->result;
                if( is_numeric($result) && ($result >=0) ){
                    if( $request->phucap==1 ){
                        $param = ( $result -$phucap  )/(( $lcs*$htn*0.01) + $lcs*$Hv*$ki*($Ntt/$Ntc) +$lcs*$Hv*$ki*($Ntt/$Ntc)*$kq*$ki );

                    }else{
                        $param = ( $result )/(( $lcs*$htn*0.01) + $lcs*$Hv*$ki*($Ntt/$Ntc) +$lcs*$Hv*$ki*($Ntt/$Ntc)*$kq*$ki );
                    }
                    $res=array('Response'=>"Success_param","param"=>$param );
                }else{
                    $res=array('Response'=>"Err","Err"=>"Dữ liệu phải là số nguyên dương !!!" );
                }

            }else{
                $res=array('Response'=>"Err","Err"=>"Bạn chưa chọn cơ chế chuyển đổi !!!" );
            }
            echo json_encode($res);
        }

    }

    public function settingSalaryBasic( Request $request ){
        $data = EmailConfig::getSettingSalaryBasic($request);
        return view('layouts.chucnangkhac.cauhinhluongcoban.index',['data'=>$data]);
    }

    public function settingSalaryBasicAjax( Request $request ){
        
        if( $request->ajax() ){
            $action = $request->type;
            if(!empty($action)) {
                switch($action) {
                    case "add":
                         $arr = [
                            'title'             =>  $request->title,
                            'value'             =>  $request->value,
                            'salary_basic'      =>  $request->salary_basic,
                            'welfare_fund'      =>  $request->welfarefund,
                            'created_by'        => Auth::user()->id,
                            'created_at'        => date('Y-m-d'),
                            'status'            =>1
                        ];
                        $result =EmailConfig::insertSettingSalaryBasic($arr);  
                        //$result = mysql_query("INSERT INTO comment(message) VALUES('".$_POST["txtmessage"]."')");
                        if($result){
                              $insert_id = \DB::getPdo()->lastInsertId();
                              echo '<tr class="message-box text-center"  id="message_' . $insert_id . '">
                                        <td class="message-title">' . $request->title . '</td>
                                        <td class="message-value">' . $request->value . '</td>
                                        <td class="message-salary">' . $request->salary_basic . '</td>
                                        <td class="message-welfarefund">' . $request->welfarefund . '</td>
                                        <td class="button_special">
                                            <div class="item_1">
                                                <a class="btnEditAction" name="edit" onClick="showEditBox(this,' . $insert_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                <a class="btnDeleteAction" name="delete" onClick="callCrudAction(\'delete\',' . $insert_id . ')"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
                                            </div>
                                            <div class="item_2"></div>

                                        </td>
                                    </tr>';
                        }
                        break;
                        
                    case "edit":
                        $arr = [
                            'title'             =>  $request->title,
                            'value'             =>  $request->value,
                            'salary_basic'      =>  $request->salary_basic,
                            'welfare_fund'      =>  $request->welfarefund,
                            'updated_by'        => Auth::user()->id,
                            'updated_at'        => date('Y-m-d'),
                        ];
                        $result =EmailConfig::updateSettingSalaryBasic($arr,$request->id); 
                              echo '<td class="message-title">' . $request->title . '</td>
                                        <td class="message-value">' . $request->value . '</td>
                                        <td class="message-salary">' . $request->salary_basic . '</td>
                                        <td class="message-welfarefund">' . $request->welfarefund . '</td>
                                        <td class="button_special">
                                            <div class="item_1">
                                                <a class="btnEditAction" name="edit" onClick="showEditBox(this,' .$request->id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                <a class="btnDeleteAction" name="delete" onClick="callCrudAction(\'delete\',' . $request->id. ')"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
                                            </div>
                                            <div class="item_2"></div>
                                        </td>';
                        break;          
                    
                    case "delete": 
                        $arr = [
                            'status'            =>0
                        ];
                        $result =EmailConfig::updateSettingSalaryBasic($arr,$request->id); 
                        break;
                }
            }
        }
    }

    public function settingTax( Request $request ){
        $data = EmailConfig::getSettingTax($request);
        return view('layouts.chucnangkhac.cauhinhmucchiuthue.index',['data'=>$data]);
    }

    public function settingTaxAjax( Request $request ){
        
        if( $request->ajax() ){
            $action = $request->type;
            if(!empty($action)) {
                switch($action) {
                    case "add":
                         $arr = [
                            'title'             =>  $request->title,
                            'money_tax'         =>  $request->money_tax,
                            'percent_tax'      =>  $request->percent_tax,
                            'money_minus'      =>  $request->money_minus,
                            'created_by'        => Auth::user()->id,
                            'created_at'        => date('Y-m-d'),
                            'status'            =>1
                        ];
                        $result =EmailConfig::insertSettingTax($arr);  
                        //$result = mysql_query("INSERT INTO comment(message) VALUES('".$_POST["txtmessage"]."')");
                        if($result){
                              $insert_id = \DB::getPdo()->lastInsertId();
                              echo '<tr class="message-box text-center"  id="message_' . $insert_id . '">
                                        <td class="message-title">' . $request->title . '</td>
                                        <td class="message-money_tax">' . BatvHelper::formatPrice($request->money_tax) . '</td>
                                        <td class="message-percent_tax">' . $request->percent_tax . '</td>
                                        <td class="message-money_minus">' . BatvHelper::formatPrice($request->money_minus) . '</td>
                                        <td class="button_special">
                                            <div class="item_1">
                                                <a class="btnEditAction" name="edit" onClick="showEditBox(this,' . $insert_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                <a class="btnDeleteAction" name="delete" onClick="callCrudAction(\'delete\',' . $insert_id . ')"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
                                            </div>
                                            <div class="item_2"></div>

                                        </td>
                                    </tr>';
                        }
                        break;
                        
                    case "edit":
                        $arr = [
                            'title'             =>  $request->title,
                            'money_tax'         =>  $request->money_tax,
                            'percent_tax'      =>  $request->percent_tax,
                            'money_minus'      =>  $request->money_minus,
                            'updated_by'        => Auth::user()->id,
                            'updated_at'        => date('Y-m-d'),
                        ];
                        $result =EmailConfig::updateSettingTax($arr,$request->id); 
                              echo '<td class="message-title">' . $request->title . '</td>
                                        <td class="message-money_tax">' . BatvHelper::formatPrice($request->money_tax) . '</td>
                                        <td class="message-percent_tax">' . $request->percent_tax . '</td>
                                        <td class="message-money_minus">' . BatvHelper::formatPrice($request->money_minus) . '</td>
                                        <td class="button_special">
                                            <div class="item_1">
                                                <a class="btnEditAction" name="edit" onClick="showEditBox(this,' . $request->id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                <a class="btnDeleteAction" name="delete" onClick="callCrudAction(\'delete\',' . $request->id . ')"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
                                            </div>
                                            <div class="item_2"></div>

                                        </td>';
                        break;          
                    
                    case "delete": 
                        $arr = [
                            'status'            =>0
                        ];
                        $result =EmailConfig::updateSettingTax($arr,$request->id); 
                        break;
                }
            }
        }
    }

    public function settingOthers( Request $request ){
        $data = EmailConfig::getOthers($request);
        return view('layouts.chucnangkhac.cauhinhkhac.index',['data'=>$data]);
    }

    public function addsettingOthers(){
        return view('layouts.chucnangkhac.cauhinhkhac.add');
    }

    public function postsettingOthersAdd( Request $request ){
        $rules = [
            'title' =>'required',
            'value' =>'required',
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'value.required' => 'Giá trị không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title'             =>  $request->title,
                'type'             =>  $request->type,
                'description'       =>  $request->description,
                'value'           =>  $request->value,
                'status'            =>1,
                'created_by'        => Auth::user()->id,
                'created_at'        => date('Y-m-d'),
            ];
            EmailConfig::insertOthersConfig($arr);  
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function postsettingOthersEdit( Request $request,$id ){
        $rules = [
            'title' =>'required',
            'value' =>'required',
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'value.required' => 'Giá trị không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'title'             =>  $request->title,
                'type'             =>  $request->type,
                'description'       =>  $request->description,
                'value'           =>  $request->value,
                'updated_by'        => Auth::user()->id,
                'updated_at'        => date('Y-m-d'),
            ];

            EmailConfig::updateOthersConfig($arr,$id);  
            return redirect()->route('editsettingOthers',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function deletesettingOthers( Request $request ){
        $arr = [
            'status'            =>0
        ];
        $result =EmailConfig::updateOthersConfig($arr,$request->id); 
        return back()->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

    public function settingEmail( Request $request ){
        $data = EmailConfig::getSettingEmail($request);
        return view('layouts.chucnangkhac.cauhinhmail.index',['data'=>$data]);
    }

    public function addsettingEmail(){
        $listPersonnel = Personnel::getAllPersonnel();
        return view('layouts.chucnangkhac.cauhinhmail.add',['listPersonnel'=>$listPersonnel]);
    }

    public function postsettingEmailAdd( Request $request ){
        $rules = [
            'title' =>'required',
            // 'mail_subject' =>'required',
            // 'mail_content' =>'required',
            // 'mail_to' =>'required',
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            // 'mail_subject.required' => 'Tiêu đề Email không được để trống',
            // 'mail_content.required' => 'Nội dung Email không được để trống',
            // 'mail_to.required' => 'Chưa chọn địa chỉ Email được gửi đến',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->mail_to != ''){
                foreach ($request->mail_to as $key => $value) {
                    $personnel_id[] =  $value;
      
                } 
                $personnel_id = implode(",",$personnel_id);
            }else{
                $personnel_id = '';
            }
            
            $cc_email = ( $request->cc_email )?$request->cc_email:0;
             $arr = [
                // 'code'              =>  BatvHelper::CreateKey(),
                'title'             =>  $request->title,
                'type'              =>  $request->type,
                'description'       =>  $request->description,
                'mail_subject'      =>  $request->mail_subject,
                'mail_content'      =>  $request->mail_content,
                'mail_to'           =>  $personnel_id,
                'cc_email'          =>  $cc_email,
                'created_by'        => Auth::user()->id,
                'created_at'        => date('Y-m-d'),
            ];

            // echo "<pre>";
            // print_r($data);die;
            EmailConfig::insertEmailConfig($arr);  
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function editsettingOthers($id){
        $data =EmailConfig::getInfoOthersConfigbyId($id);
        return view('layouts.chucnangkhac.cauhinhkhac.edit',['data'=>$data]);
    }

    public function editsettingEmail($id){

        $listPersonnel = Personnel::getAllPersonnel();
        $data =EmailConfig::getInfoEmailConfigbyId($id);
        $email = explode(",",$data->mail_to);

        $listPersonnel = json_decode(json_encode($listPersonnel), true);
        foreach ($listPersonnel as $key => $value) {
            if( in_array($value['id'], $email) ){
                $listPersonnel[$key]['ticket'] ='1'; 
            }

        }
        return view('layouts.chucnangkhac.cauhinhmail.edit',['data'=>$data,'listPersonnel'=>$listPersonnel]);
    }

    public function postsettingEmailEdit( Request $request,$id ){
        $rules = [
            'title' =>'required',
            // 'mail_subject' =>'required',
            // 'mail_content' =>'required',
            // 'mail_to' =>'required',
        ];
        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            // 'mail_subject.required' => 'Tiêu đề Email không được để trống',
            // 'mail_content.required' => 'Nội dung Email không được để trống',
            // 'mail_to.required' => 'Chưa chọn địa chỉ Email được gửi đến',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->mail_to != ''){
                foreach ($request->mail_to as $key => $value) {
                    $personnel_id[] =  $value;
      
                } 
                $personnel_id = implode(",",$personnel_id);
            }else{
                $personnel_id = '';
            }
            $cc_email = ( $request->cc_email )?$request->cc_email:0;
             $arr = [
                // 'code'              =>  BatvHelper::CreateKey(),
                'title'             =>  $request->title,
                'type'              =>  $request->type,
                'description'       =>  $request->description,
                'mail_subject'      =>  $request->mail_subject,
                'mail_content'      =>  $request->mail_content,
                'mail_to'           =>  $personnel_id,
                'cc_email'          =>  $cc_email,
                'updated_by'        => Auth::user()->id,
                'updated_at'        => date('Y-m-d'),
            ];

            EmailConfig::updateEmailConfig($arr,$id);  
            return redirect()->route('editsettingEmail',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function deletesettingEmail( $id ){
        EmailConfig::deleteSettingEmail($id);
        return back()->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

    public function settingExceptionalAttendance(){
        $listPersonnel = Personnel::getAllPersonnel();
        $data = ExceptionalAttendance::listExceptionalAttendance();
        return view('layouts.chucnangkhac.cauhinhmienchamcong.index',['data'=>$data,'listPersonnel'=>$listPersonnel]);
    }

    public function postExceptionalAttendance( Request $request ){
        $rules = [
            'personnel_id' =>'required',
            'apply_from' =>'required',
            'apply_to' =>'required|validator_datetime_from_to:apply_from',
        ];
        $messages = [
            'personnel_id.required' => 'Bạn chưa chọn nhân viên',
            'apply_from.required' => 'Ngày hiệu lực không được để trống',
            'apply_to.required' => 'Ngày hết hiệu lực không được để trống',
            'apply_to.validator_datetime_from_to' => 'Nhập thời gian không hợp lệ',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->personnel_id ){
                foreach ($request->personnel_id as $k => $v) {
                    $apply_from = BatvHelper::formatDate( $request->apply_from,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                    $apply_to = BatvHelper::formatDate( $request->apply_to,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                    $data[] =  array('personnel_id'=>$v,'apply_from'=>$apply_from,'apply_to'=>$apply_to,'status'=>1);
                }  
            }
            ExceptionalAttendance::insert($data);
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function deleteExceptionalAttendance($id){
        $item = ExceptionalAttendance::find($id);
        $item->status = 0;
        $item->save();
        return back()->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }


    public function settingAbsentAttendance(){
        $listPersonnel = Personnel::getAllPersonnel();
        $data = SettingAbsentAttendance::listAbsentAttendance();
        return view('layouts.chucnangkhac.cauhinhchamcongnghiphep.index',['data'=>$data,'listPersonnel'=>$listPersonnel]);
    }

    public function postsettingAbsentAttendance( Request $request ){
        $rules = [
            'personnel_id' =>'required',
            'apply_from' =>'required',
            'apply_to' =>'required|validator_datetime_from_to:apply_from',
        ];
        $messages = [
            'personnel_id.required' => 'Bạn chưa chọn nhân viên',
            'apply_from.required' => 'Ngày hiệu lực không được để trống',
            'apply_to.required' => 'Ngày hết hiệu lực không được để trống',
            'apply_to.validator_datetime_from_to' => 'Nhập thời gian không hợp lệ',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->personnel_id ){
                foreach ($request->personnel_id as $k => $v) {
                    $apply_from = BatvHelper::formatDate( $request->apply_from,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                    $apply_to = BatvHelper::formatDate( $request->apply_to,'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false);
                    $data[] =  array('personnel_id'=>$v,'apply_from'=>$apply_from,'apply_to'=>$apply_to);
                }  
            }
            SettingAbsentAttendance::insert($data);
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function deletesettingAbsentAttendance($id){
        $item = SettingAbsentAttendance::find($id);
        $item->delete();
        return back()->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

}
