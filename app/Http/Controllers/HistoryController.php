<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Models\History;
use App\Models\Personnel;
use App\Helpers\BatvHelper;
use App\Mylibs\Myfunction;
use DateTime;
use Validator;

class HistoryController extends Controller
{
    public function getHistoryList(Request $request){

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
            $data =  History::listHistory($request,$ids);
        }else{
            $data =  History::listHistory($request);
        }
    	return view('layouts.quatrinh.index',['data'=>$data,'department'=>$select_depart]);
    }
    
    public function getHistoryDetail($id){
    	$data = History::detailHistory($id);
        $name = History::getNamePersonnel($id);
        $ratio = History::listRation($id);
    	return view('layouts.quatrinh.detail',['data'=>$data,'id'=>$id,'ratio'=>$ratio,'name'=>$name]);
    }

    public function getHistoryAdd(Request $request, $id){
        $listJobs = History::listJobs();
        $depart = History::listDepartment();
        $myfunc =  new Myfunction();
        $select = 0;
        if (!empty(old('selectDepart'))) {
            $select = old('selectDepart');
        }
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$select);
    	return view('layouts.quatrinh.add',['id'=>$id,'department'=>$select_depart,'listJobs'=>$listJobs]);
    }

    public function postHistoryAdd(Request $request,$id){  

    	$rules = [
            'startDate' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'endDate'=>'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field:startDate',
            'selectJobs'=>'required',
            'selectDepart'=>'required',
        ];
        $messages = [
            'startDate.required'=>'Bạn chưa nhập ngày bắt đầu',
            'endDate.required'=>'Bạn chưa nhập ngày đến',
            'startDate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'startDate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.greater_than_field'=>'Nhập thời gian không chính xác.',
            'selectJobs.required'=>'Bạn chưa chọn chức danh',
            'selectDepart.required' => 'Bạn chưa chọn đơn vị',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $startDate = DateTime::createFromFormat('d/m/yy', $request->startDate);  
            $endDate = DateTime::createFromFormat('d/m/yy', $request->endDate);  
            $arr = [
                    'date_start' => $startDate->format('Y-m-d'),
                    'date_end' =>   $endDate->format('Y-m-d'),
                    'job_id' => $request->selectJobs,
                    'department_id' => $request->selectDepart,
                    'personnels_ID' => $id,
                    'status' => 1,
                ];
            History::insertHistory($arr);
            return redirect()->route('getHistoryDetail',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }

    }

    public function getHistoryEdit($personal_id,$id){
        $data = History::getHistoryFromId($id);
        $depart = History::listDepartment();
        $myfunc =  new Myfunction();
        $selectDepart = 0;
        if (!empty($data->department_id)) {
            $selectDepart = $data->department_id;
        }
        $selectjobs = 0;
        if (!empty($data->job_id)) {
            $selectjobs = $data->job_id;
        }
        $selectjobs2 = 0;
        if (!empty(old('selectJobs'))) {
            $selectjobs2 = old('selectJobs');
        }
        $listJobs = History::listJobs2($selectjobs,$selectjobs2);
        // echo "<pre>";
        // print_r($data);die;
        $select_depart = $myfunc->callProcessSelect($depart,0,'',$selectDepart);
        
        return view('layouts.quatrinh.edit',['data'=>$data,'department'=>$select_depart,'listJobs'=>$listJobs,'id'=>$personal_id]);

    }

    public function postHistoryEdit(Request $request, $personal_id,$id){
        $rules = [
            'startDate' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'endDate'=>'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field:startDate',
            'selectJobs'=>'required',
            'selectDepart'=>'required',
        ];
        $messages = [
            'startDate.required'=>'Bạn chưa nhập ngày bắt đầu',
            'endDate.required'=>'Bạn chưa nhập ngày đến',
            'startDate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'startDate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.greater_than_field'=>'Nhập thời gian không chính xác.',
            'selectJobs.required'=>'Bạn chưa chọn chức danh',
            'selectDepart.required' => 'Bạn chưa chọn đơn vị',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $startDate = DateTime::createFromFormat('d/m/yy', $request->startDate);  
            $endDate = DateTime::createFromFormat('d/m/yy', $request->endDate);  
            $arr = [
                    'date_start' => $startDate->format('Y-m-d'),
                    'date_end' =>   $endDate->format('Y-m-d'),
                    'job_id' => $request->selectJobs,
                    'department_id' => $request->selectDepart,
                    'personnels_ID' => $personal_id,
                    'status' => 1,
                ];
            History::updateWorkHistory($arr,$id);
            return redirect()->route('getHistoryDetail',['id'=>$personal_id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }

    }

    public function getHistoryDel($personal_id,$id){
        $data = History::deleteWorkHistory($id);
        if($data){
            return redirect()->route('getHistoryDetail',['id'=>$personal_id])->with(['flash_message_succ' =>'Xóa Thông Tin thành công']);
        }else{
             return redirect()->route('getHistoryDetail',['id'=>$personal_id])->with(['flash_message_err' =>'Xảy ra lỗi trong quá trình xóa']);
        }
    }

    public function getHistoryAddRatio($id){

        return view('layouts.quatrinh.add_job_ratio',['id'=>$id]);
    }

    public function postHistoryAddRatio(Request $request,$id){
        $rules = [
            'startDate' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'endDate'=>'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field:startDate',
            'heso'=>'required|numeric',
        ];
        $messages = [
            'startDate.required'=>'Bạn chưa nhập ngày bắt đầu',
            'endDate.required'=>'Bạn chưa nhập ngày đến',
            'startDate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'startDate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.greater_than_field'=>'Nhập thời gian không chính xác.',
            'heso.required'=>'Bạn chưa chọn hệ số',
            'heso.numeric' => 'Định dạng phải là kiếu số',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $startDate = DateTime::createFromFormat('d/m/yy', $request->startDate);  
            $endDate = DateTime::createFromFormat('d/m/yy', $request->endDate);  
            $check_lv1 = History::checkinsertRatio('',$id);
            $check_lv2 = History::checkinsertRatio($startDate->format('Y-m-d'),$id);
            if( count($check_lv1)>0 ){
                if( count($check_lv2)>0 ){
                    $arr = [
                            'apply_from' => $startDate->format('Y-m-d'),
                            'apply_to' =>   $endDate->format('Y-m-d'),
                            'ratio' => $request->heso,
                            'personnel_ID' => $id,
                            'created_by'    => Auth::user()->id,
                            'created_at'    => date('Y-m-d'),
                            'status' => 1,
                        ];
                    History::insertRatio($arr);
                    return redirect()->route('getHistoryDetail',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
                }else{
                    return redirect()->route('getHistoryAddRatio',['id'=>$id])->with(['flash_message_err' => 'Thời gian chọn nằm trong khoảng thời gian trước']);
                }
            }else{
                $arr = [
                        'apply_from' => $startDate->format('Y-m-d'),
                        'apply_to' =>   $endDate->format('Y-m-d'),
                        'ratio' => $request->heso,
                        'personnel_ID' => $id,
                        'created_by'    => Auth::user()->id,
                        'created_at'    => date('Y-m-d'),
                        'status' => 1,
                    ];
                History::insertRatio($arr);
                return redirect()->route('getHistoryDetail',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
            }

        }
    }
    public function getHistoryEditRatio($personal_id,$id){
        $data = History::getRatioFromId($id);    
        return view('layouts.quatrinh.edit_job_ratio',['data'=>$data,'id'=>$personal_id]);

    }

     public function postHistoryEditRatio(Request $request,$personal_id,$id){
        $rules = [
            'startDate' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'endDate'=>'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field:startDate',
            'heso'=>'required|numeric',
        ];
        $messages = [
            'startDate.required'=>'Bạn chưa nhập ngày bắt đầu',
            'endDate.required'=>'Bạn chưa nhập ngày đến',
            'startDate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'startDate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'endDate.greater_than_field'=>'Nhập thời gian không chính xác.',
            'heso.required'=>'Bạn chưa chọn hệ số',
            'heso.numeric' => 'Định dạng phải là kiếu số',
        ];  
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $startDate = DateTime::createFromFormat('d/m/yy', $request->startDate);  
            $endDate = DateTime::createFromFormat('d/m/yy', $request->endDate);  
            //$check = History::checkUpdateRatio($startDate->format('Y-m-d'),$endDate->format('Y-m-d'),$id,$personal_id);
            // if( count($check)>0 ){
                $arr = [
                        'apply_from' => $startDate->format('Y-m-d'),
                        'apply_to' =>   $endDate->format('Y-m-d'),
                        'ratio' => $request->heso,
                        'personnel_ID' => $personal_id,
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date('Y-m-d'),
                        'status' => 1,
                    ];
                History::updateRatio($arr,$id);
                return redirect()->route('getHistoryDetail',['id'=>$personal_id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
            // }else{
            //     return redirect()->route('getHistoryEditRatio',['personal'=>$personal_id,'id'=> $id])->with(['flash_message_err' => 'Thời gian chọn nằm trong khoảng thời gian trước']);
            // }

        }
    
    }
    public function getHistoryDelRatio($personal_id,$id){
         $data = History::deleteRatio($id);
        if($data){
            return redirect()->route('getHistoryDetail',['id'=>$personal_id])->with(['flash_message_succ' =>'Xóa Thông Tin thành công']);
        }else{
             return redirect()->route('getHistoryDetail',['id'=>$personal_id])->with(['flash_message_err' =>'Xảy ra lỗi trong quá trình xóa']);
        }
    }

}
