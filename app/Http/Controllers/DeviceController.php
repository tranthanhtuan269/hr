<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Http\Requests;
use App\Models\Device;
use App\Models\CateDevice;
use App\Models\Personnel;
use App\Mylibs\Myfunction;
use App\Helpers\BatvHelper;

class DeviceController extends Controller
{
    public function getDeviceList(Request $request){
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        $ids = [];
        if( $request->c_id !=0 ){
            $flag =  new Myfunction();
            $tmp = $flag->categoryChild($request->c_id,'category_device');
            if( count($tmp)==0 ){
                $ids = array($request->c_id);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
        }

        $data = Device::getDeviceList($request,$ids);
        $depart = Device::listCateDevice();
        $myfunc =  new Myfunction();
        $select = 0;
        if ($request->input('c_id') !='') {
            $select = $request->input('c_id');
        }
        $cateDevice = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.thietbi.index',['data'=>$data,'cateDevice'=>$cateDevice,'listPersonnel'=>$listPersonnel]);
    }

    public function getCateDeviceList(){
        $depart = CateDevice::where('status',1)->get();
        $myfunc =  new Myfunction();
        $select = 0;
        $cateDevice = $myfunc->callCateDevice($depart,0,'',$select);
        return view('layouts.thietbi.danhmucthietbi.index',['cateDevice'=>$cateDevice]);
    }

    public function getCateDeviceAdd(){
        $depart = Device::listCateDevice();
        $myfunc =  new Myfunction();
        $select = 0;
        $cateDevice = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.thietbi.danhmucthietbi.add',['cateDevice'=>$cateDevice]);
    }

    public function postCateDeviceAdd( Request $request){
        $rules = [
            'title' =>'required',
        ];
        $messages = [
            'title.required' => 'Nội dung không được để trống',
            ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $item = new CateDevice;
            $item->title =  $request->title;
            $item->parent_id =  $request->parent_id;
            $item->created_by =  Auth::user()->id;
            $item->created_at = date('Y-m-d H:i:s');
            $item->status =  1;
            $item->save();
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getCateDeviceEdit($id){
        $data = CateDevice::find($id);
        $depart = Device::listCateDevice();
        $myfunc =  new Myfunction();
        $select = $data['parent_id'];
        $cateDevice = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.thietbi.danhmucthietbi.edit',['data'=>$data,'cateDevice'=>$cateDevice]);
    }

    public function postCateDeviceEdit( Request $request){
        $rules = [
            'title' =>'required',
        ];
        $messages = [
            'title.required' => 'Nội dung không được để trống',
            ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $item = CateDevice::find($request->id);
            $item->title =  $request->title;
            $item->parent_id =  $request->parent_id;
            $item->updated_by =  Auth::user()->id;
            $item->updated_at = date('Y-m-d H:i:s');
            $item->save();
            return back()->with(['flash_message_succ' => 'Sửa thông tin thành công']);
        }
    }

    public function getCateDeviceDel($id){
        $item = CateDevice::find($id);
        $item->status = 0;
        $item->save();
        return back()->with(['flash_message_succ' =>'Xóa thông tin thành công']);
    }

    public function getDeviceAdd(){
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        $depart = Device::listCateDevice();
        $myfunc =  new Myfunction();
        $select = 0;
        $cateDevice = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.thietbi.add',['cateDevice'=>$cateDevice,'listPersonnel'=>$listPersonnel]);
    }

    public function postDeviceAdd( Request $request){
        $rules = [
            'title' =>'required',
            'parent_id' =>'required',
            'number' =>'required|numeric|min:0',
            'date_buy' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
        ];
        $messages = [
            'title.required' => 'Tên thiết bị không được để trống',
            'parent_id.required' => 'Bạn chưa chọn danh mục',
            'number.required' => 'Số lượng không được để trống',
            'number.numeric' => 'Số lượng phải là số',
            'number.min' => 'Số lượng phải là số nguyên dương ',
            'date_buy.date_format'=>'Ngày mua chưa đúng định dạng',
            'date_buy.required' => 'Ngày mua không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $item = new Device;
            $item->title =  $request->title;
            $item->c_id =  $request->parent_id;
            $item->number =  $request->number;
            $item->system =  $request->system;
            $item->maker =  $request->maker;
            $item->screen_size =  $request->screen_size;
            $item->config =  $request->config;
            $item->others =  $request->others;
            $item->description =  $request->title.$request->number.$request->system.$request->maker.$request->screen_size.$request->config.$request->note.$request->others;
            $item->date_buy =  BatvHelper::formatDate($request->date_buy,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $item->created_by =  Auth::user()->id;
            $item->created_at = date('Y-m-d H:i:s');
            $item->status = 1;
            $item->save();
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getDeviceEdit($id){
        $data = Device::getDeviceByID($id);
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        $depart = CateDevice::where('status',1)->get();
        $myfunc =  new Myfunction();
        $select = $data['c_id'];
        $cateDevice = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.thietbi.edit',['cateDevice'=>$cateDevice,'data'=>$data,'listPersonnel'=>$listPersonnel]);
    }

    public function postDeviceEdit( Request $request){
        $rules = [
            'title' =>'required',
            'parent_id' =>'required',
            'number' =>'required|numeric|min:0',
            'date_buy' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
        ];
        $messages = [
            'title.required' => 'Tên thiết bị không được để trống',
            'parent_id.required' => 'Bạn chưa chọn danh mục',
            'number.required' => 'Số lượng không được để trống',
            'number.numeric' => 'Số lượng phải là số',
            'number.min' => 'Số lượng phải là số nguyên dương ',
            'date_buy.date_format'=>'Ngày mua chưa đúng định dạng',
            'date_buy.required' => 'Ngày mua không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $item = Device::find($request->id);
            $item->title =  $request->title;
            $item->c_id =  $request->parent_id;
            $item->number =  $request->number;
            $item->system =  $request->system;
            $item->maker =  $request->maker;
            $item->screen_size =  $request->screen_size;
            $item->config =  $request->config;
            $item->others =  $request->others;
            $item->description =  $request->title.$request->number.$request->system.$request->maker.$request->screen_size.$request->config.$request->note.$request->others;
            $item->date_buy =  BatvHelper::formatDate($request->date_buy,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $item->updated_by =  Auth::user()->id;
            $item->updated_at = date('Y-m-d H:i:s');
            $item->save();
            return back()->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function getDeviceDel($id){
        $item = Device::find($id);
        $item->status = 0;
        $item->save();
        return back()->with(['flash_message_succ' =>'Xóa thông tin thành công']);
    }

    public function getDeviceClientList(Request $request){
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        $ids = [];
        if( $request->c_id !=0 ){
            $flag =  new Myfunction();
            $tmp = $flag->categoryChild($request->c_id,'category_device');
            if( count($tmp)==0 ){
                $ids = array($request->c_id);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }

        }

        $personnel_id = array(Auth::user()->id);
        if( isset($request->oke) ){
            if( $request->personnel_id !=0 ){
                $personnel_id = array($request->personnel_id);
            }elseif( $request->personnel_id ==0 ){
                $personnel_id = [];
            }
        }

        $data = Device::getTakeDevice($request,$ids,$personnel_id);
        $depart = Device::listCateDevice();
        $myfunc =  new Myfunction();
        $select = 0;
        if ($request->input('c_id') !='') {
            $select = $request->input('c_id');
        }
        $cateDevice = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.thietbi.client.index',['data'=>$data,'cateDevice'=>$cateDevice,'listPersonnel'=>$listPersonnel]);
    }

    public function getTakeDeviceList(Request $request){
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        $personnel_id = $ids = [];
        if( $request->c_id !=0 ){
            $flag =  new Myfunction();
            $tmp = $flag->categoryChild($request->c_id,'category_device');
            if( count($tmp)==0 ){
                $ids = array($request->c_id);
            }else{
                $ids =  BatvHelper::array_keys_multi($tmp);
            }
        }
        if( $request->personnel_id !=0 ){
            $personnel_id = array($request->personnel_id);
        }
        $data = Device::getTakeDevice($request,$ids,$personnel_id);
        // echo "<pre>";
        // print_r($data);die;
        $depart = Device::listCateDevice();
        $myfunc =  new Myfunction();
        $select = 0;
        if ($request->input('c_id') !='') {
            $select = $request->input('c_id');
        }
        $cateDevice = $myfunc->callProcessSelect($depart,0,'',$select);
        return view('layouts.thietbi.bangiao.index',['data'=>$data,'cateDevice'=>$cateDevice,'listPersonnel'=>$listPersonnel]);
    }

    public function getTakeDeviceAdd(){
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        $device = Device::where('status',1)->get();
        return view('layouts.thietbi.bangiao.add',['device'=>$device,'listPersonnel'=>$listPersonnel]);
    }

    public function postTakeDeviceAdd( Request $request){
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

        // Kiểm tra số lượng thiết bị bàn giao
        Validator::extend('check_number_device', function($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            if( empty($data['title']) ){
                return FALSE;
            }else{
                $numberDevicePersonnelCurrent = Device::numberDevicePersonnelCurrent($data['title']);
                $numberDeviceCurrent = Device::numberDeviceCurrent($data['title']);
                $number_device = $data['number']*count($data['personnel_id']);
                $check = $numberDeviceCurrent - $numberDevicePersonnelCurrent;
                if( $number_device <= $check ){
                    return TRUE;
                }else{
                    return FALSE;
                }
            }

        }); 
        $rules = [
            'title' =>'required',
            'personnel_id' =>'required',
            'number' =>'required|numeric|min:0|check_number_device',
            'date_in' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field_special:date_in',
        ];
        $messages = [
            'title.required' => 'Bạn chưa chọn thiết bị',
            'number.required' => 'Số lượng không được để trống',
            'number.numeric' => 'Số lượng phải là số',
            'number.min' => 'Số lượng phải là số nguyên dương ',
            'personnel_id.required' => 'Bạn chưa chọn người được bàn giao',
            'date_in.date_format'=>'Ngày giao chưa đúng định dạng',
            'date_in.required' => 'Ngày giao không được để trống',
            'date_in.greater_than_field_special' => 'Ngày giao thiết bị không được lớn hơn ngày hiện tại',
            'number.check_number_device' => 'Số lượng bàn giao lớn hơn số lượng thiết bị trong kho',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->personnel_id ){
                foreach ($request->personnel_id as $k => $v) {
                    $data[] = [
                                'device_id' =>  $request->title,
                                'personnel_id'=>$v,
                                'date_in' =>  BatvHelper::formatDate($request->date_in,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false),
                                'note' =>  $request->note,
                                'number' =>  $request->number,
                                'options' =>  $request->options,
                                'created_by' => Auth::user()->id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'status' => 1,
                            ];
                }  
                Device::insertDevicePersonnel($data); 
            }
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getTakeDeviceEdit($id){
        $data = Device::getDevicePersonnelByID($id);
        // echo "<pre>";
        // print_r($data);die;
        $listPersonnel = Personnel::getAllPersonnelCurrent();
        $device = Device::where('status',1)->get();
        return view('layouts.thietbi.bangiao.edit',['device'=>$device,'data'=>$data,'listPersonnel'=>$listPersonnel]);
    }

    public function postTakeDeviceEdit( Request $request){
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

        // Kiểm tra số lượng thiết bị bàn giao
        Validator::extend('check_number_device', function($attribute, $value, $parameters, $validator) {
            $id = $parameters[0];
            $data = $validator->getData();
            $numberDevicePersonnelCurrent = Device::numberDevicePersonnelCurrent($data['title'],array($id));
            $numberDeviceCurrent = Device::numberDeviceCurrent($data['title']);
            $number_device = $data['number']*count($data['personnel_id']);
            $check = $numberDeviceCurrent - $numberDevicePersonnelCurrent;
            if( $number_device <= $check ){
                return TRUE;
            }else{
                return FALSE;
            }
        }); 
        $rules = [
            'title' =>'required',
            'personnel_id' =>'required',
            'number' =>'required|numeric|min:0|check_number_device:'.$request->id,
            'date_in' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field_special:date_in',
        ];
        $messages = [
            'title.required' => 'Bạn chưa chọn thiết bị',
            'number.required' => 'Số lượng không được để trống',
            'number.numeric' => 'Số lượng phải là số',
            'number.min' => 'Số lượng phải là số nguyên dương ',
            'personnel_id.required' => 'Bạn chưa chọn người được bàn giao',
            'date_in.date_format'=>'Ngày giao chưa đúng định dạng',
            'date_in.required' => 'Ngày giao không được để trống',
            'date_in.greater_than_field_special' => 'Ngày giao thiết bị không được lớn hơn ngày hiện tại',
            'number.check_number_device' => 'Số lượng bàn giao lớn hơn số lượng thiết bị trong kho',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->personnel_id ){
                $data= [
                            'device_id' =>  $request->title,
                            'personnel_id'=>$request->personnel_id,
                            'date_in' =>  BatvHelper::formatDate($request->date_in,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false),
                            'note' =>  $request->note,
                            'number' =>  $request->number,
                            'options' =>  $request->options,
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                Device::updateDevicePersonnel($data,$request->id); 
            }
            return redirect()->route('getTakeDeviceEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function getTakeDeviceDel($id){
        $data= [
                  'status' => 0,
                ];
        Device::updateDevicePersonnel($data,$id);
        return redirect()->route('getTakeDeviceList')->with(['flash_message_succ' => 'Xóa thông tin thành công']);
    }

}

