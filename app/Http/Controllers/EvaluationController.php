<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Models\Evaluation;
use App\Models\Personnel;
use DateTime;
use App\Mylibs\Myfunction;
use Validator;
use App\Helpers\BatvHelper;
use Illuminate\Support\Collection;
use App\Models\EmailConfig;
use App\Models\History;
use App\Models\Salary;
use App\Models\User;
use App\Models\Roles;
use App\Models\Privilegs;
use App\Models\AdhocSalaryAssessment;
use App\Models\ConfigLoanCapital;
use App\Models\Departments;

class EvaluationController extends Controller
{
    public function getEvaluationSupport(){
        $data = Evaluation::getInfoEvaluationSupport(0);
        return view('layouts.danhgia.support',['data'=>$data]);
    }

    public function  editEvaluationSupport($id){
        $data = Evaluation::getInfoEvaluationSupport($id);

        return view('layouts.danhgia.editSupport',['data'=>$data]);
    }
    public function postEvaluationSupportDetail(Request $request,$id){
        $rules = [
            'criteria_content' =>'required',
        ];
        $messages = [
            'criteria_content.required' => 'Nội dung không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'criteria_content' =>  $request->criteria_content,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d'),
            ];
            Evaluation::updateEvaluationSupport($arr,$id);  
            return redirect()->route('editEvaluationSupport',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function getEvaluationCriteria(Request $request){
        $data = Evaluation::listCriteria($request);
        return view('layouts.danhgia.listCriteria',['data'=>$data]);
    }
    public function addEvaluationCriteria(){
        return view('layouts.danhgia.addCriteria');
    }

    public function postaddEvaluationCriteria( Request $request ){
        Validator::extend('check_title', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          $title = $data['criteria_content'];
          $check = Evaluation::checkTitleEvaluationCriteria( $title );
          return ( $check > 0 )? false:true;
        });

        $rules = [
            'criteria_content' =>'required|max:255|check_title',
        ];
        $messages = [
            'criteria_content.required' => 'Tên tiêu chí không được để trống',
            'criteria_content.max' => 'Tên tiêu chí không được quá 255 ký tự',
            'criteria_content.check_title' => 'Tên tiêu chí đã tồn tại',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'criteria_content' =>  $request->criteria_content,
                'description'      =>  $request->description,
                'created_by'    => Auth::user()->id,
                'created_at'       => date('Y-m-d'),
            ];
            Evaluation::insertEvaluationCriteria($arr);  
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getEvaluationItem($id){
        return view('layouts.danhgia.evaluationbyMonth');
    }

    public static function editEvaluationCriteria($id){
        $data = Evaluation::getInfoEvaluationCriteria($id);
        // echo "<pre>";
        // print_r($data);
        return view('layouts.danhgia.editCriteria',['data'=>$data]);
    }
    
    public static function postEditEvaluationCriteria(Request $request, $id){
        Validator::extend('check_title', function($attribute, $value, $parameters, $validator) {
          $id = $parameters[0];
          $data = $validator->getData();
          $title = $data['criteria_content'];
          $check = Evaluation::checkTitleEvaluationCriteria( $title,$id );
          return ( $check > 0 )? false:true;
        });

        $rules = [
            'criteria_content' =>'required|max:255|check_title:'.$id,
        ];
        $messages = [
            'criteria_content.required' => 'Tên tiêu chí không được để trống',
            'criteria_content.max' => 'Tên tiêu chí không được quá 255 ký tự',
            'criteria_content.check_title' => 'Tên tiêu chí đã tồn tại',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $date_field = DateTime::createFromFormat('d/m/yy', $request->inputBirthday); 
            $arr = [
                    'criteria_content' => $request->criteria_content,
                    'description' => $request->description,
                    'updated_at' =>  date('Y-m-d'), 
                    'updated_by'=>Auth::user()->id,
                ];
            Evaluation::updateInfoCriteria($arr,$id);
            return redirect()->route('editEvaluationCriteria',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);;
        }
    }
// MONTH
    public function listPersonnelbyManger_Month(){
        $id = Auth::user()->id;
        // echo $id;die;
        $check = Evaluation::checkDepartmentbyManager($id);
        $department_id = array();
        foreach ($check as $key => $value) {
            $department_id[] = $value->id;
        }
        if( count($check)>0 ){
            $listType = Evaluation::listPersonnelbyManager($department_id);
            foreach ($listType as $k => $v) {
                if( $v->id ==$id ){
                    unset($listType[$k]);
                }
            }
            return view('layouts.danhgia.danhgiathang.listPersonnelbyManger_Month',['data'=> $listType]);
        }else{
            return view('layouts.danhgia.danhgiathang.listPersonnelbyManger_Month',['error_special'=> 'Bạn là nhân viên, phấn đấu đê để trở thành quản lý (^ ^)']);
        }

        
    }
    //Nhân viên đánh giá quản lý theo tháng
    public function getEvaluationManagerbyMonth(){
        $id = Auth::user()->id;
        $data = Evaluation::checkEvaluationCriteriabyTime($type=2);
        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        //Kiểm tra xem nhân viên được quản lý bởi ai
        $check = Evaluation::checkManager($id);
        // if( count($data)>0 ){
            if( isset($check) ){
                if( $check->manager_id != $id){
                    $checkPersonnelEvaluationDetailType = Evaluation::getInfoEvaluationManagerbyPersonnel(Auth::user()->id,$check->manager_id,$beforerCurrentDate,$type=2);
                    $param = ( count($checkPersonnelEvaluationDetailType) >0 )?1:"";
                    return view('layouts.danhgia.danhgiathang.listEvaluationManagerbyMonth',['data'=> $data,'param'=>$param,'manager_id'=>$check->manager_id ]);
                }else{
                    return view('layouts.danhgia.danhgiathang.listEvaluationManagerbyMonth',['error_special'=> 'Đây là mục dành cho nhân viên đánh giá quản lý. Bạn không thể tự Vote cho mình(^ ^)']);
                }
            }else{
                return view('layouts.danhgia.danhgiathang.listEvaluationManagerbyMonth',['error_special'=> 'Bạn không thuộc ai quản lý ']);
            }
            
        // }else{
        //     return view('layouts.danhgia.danhgiathang.listEvaluationManagerbyMonth',['error_special'=> 'Chưa có tiêu chí đánh giá trong thời gian hiện tại']);
        // }
    }
    
    public static function postEvaluationManagerbyMonth(Request $request){
        // echo 1;die;
        $id = Auth::user()->id;
        //Kiêm tra xem nhân viên thuộc quản lý của User id nào
        $check = Evaluation::checkManager($id);
        $manager_id = $check->manager_id;

        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        // echo  $beforerCurrentDate;die;
        
        $check = Evaluation::checkPersonnelEvaluation($manager_id,$beforerCurrentDate,2);
        // echo $check->id;die;
        if( count($check)>0 ){
            $checkPersonnelEvaluationDetailType = Evaluation::checkPersonnelEvaluationManagerDetail(Auth::user()->id,$check->id,$beforerCurrentDate,2);
            // echo count($checkPersonnelEvaluationDetailType);die;
            if( count($checkPersonnelEvaluationDetailType) == 0 ){
                // echo 2;die;
                $i = 1; 
                foreach ($request->point as $key => $value) {
                    $data[$i]['type'] = 2;
                    $data[$i]['criteria_id'] = $key;
                    $data[$i]['point'] = $value;
                    $data[$i]['personnel_evaluation_id'] =  $check->id;
                    $data[$i]['p_id'] = Auth::user()->id;
                    $data[$i]['date'] = $beforerCurrentDate;
                    $data[$i]['created_by'] = Auth::user()->id;
                    $data[$i]['created_at'] = date('Y-m-d');
                    $data[$i]['status'] = 1;
                    $i++;
                }
                Evaluation::insertPersonnelEvaluationDetail( $data );
                $point =  Evaluation::pointPersonnelEvaluationManagerDetail(Auth::user()->id,$check->id,$beforerCurrentDate,2);
                $total_point =  (intval($point)+intval($check->total_point) )/(intval($check->count) +1 );
                // echo $total_point;
                if( $total_point >= 1.02 &&  $total_point <=1.05){
                    $kpi = 1;
                }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
                    $kpi = 2;
                }else{
                    $kpi = 3;
                }
                $arr = [
                        'kpi' => $kpi,
                        'total_point' => $total_point,
                        'count'       => $check->count +1,
                    ];
                Evaluation::updatePersonnelEvaluation( $manager_id,$beforerCurrentDate,$check->type,$arr);
                return redirect()->route('getEvaluationManagerbyMonth')->with(['flash_message_succ' => 'Bạn đã đánh giá thành công']);
               
            }else{
                return redirect()->route('getEvaluationManagerbyMonth')->with(['flash_message_err' => 'Bạn đã đánh giá rồi']);
            }
        }else{
            //echo 1;die;
            if( isset($request->comment)){
                $comment = $request->comment;
            }else{
                $comment= '';
            }
            
            $arr = [
                    'personnel_id' => $manager_id,
                    'total_point' => 0,
                    'type' =>  2, 
                    'count'=>1,
                    'status'=>1,
                    'comment' => $comment,
                    'date' => $beforerCurrentDate,
                    'created_at' =>  date('Y-m-d'), 
                    'created_by'=>Auth::user()->id,
                ];
            Evaluation::insertPersonnelEvaluation( $arr );
            $personnel_evaluation_id = \DB::getPdo()->lastInsertId();
            $i = 1; 
            foreach ($request->point as $key => $value) {
                $data[$i]['type'] = 2;
                $data[$i]['criteria_id'] = $key;
                $data[$i]['point'] = $value;
                $data[$i]['personnel_evaluation_id'] = $personnel_evaluation_id;
                $data[$i]['p_id'] = Auth::user()->id;
                $data[$i]['date'] = $beforerCurrentDate;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d');
                $data[$i]['status'] = 1;
                $i++;
            }
            Evaluation::insertPersonnelEvaluationDetail( $data );
            $point=  Evaluation::pointPersonnelEvaluationDetail($personnel_evaluation_id,$beforerCurrentDate,2);
            $param = [
                        'total_point'=> $point
                    ];
            Evaluation::updatePersonnelEvaluation($manager_id,$beforerCurrentDate,$type=2,$param);

            return redirect()->route('getEvaluationManagerbyMonth')->with(['flash_message_succ' => 'Đánh giá thành công']);
        }
        
    } 
    public function getEvaluationManagerbyMonthEdit($id){
        // echo 1;die;
        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        $data = Evaluation::getInfoEvaluationManagerbyPersonnel(Auth::user()->id,$id,$beforerCurrentDate,$type=2);
        // echo "<pre>";
        // print_r($data);
        return view('layouts.danhgia.danhgiathang.editEvaluationManagerbyMonth',['data'=>$data]);
    }

    public function postEvaluationManagerbyMonthEdit(Request $request){
        $id = Auth::user()->id;
        //Kiêm tra xem nhân viên thuộc quản lý của User id nào
        $check = Evaluation::checkManager($id);
        $manager_id = $check->manager_id;

        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        // echo  $beforerCurrentDate;die;
        $check = Evaluation::checkPersonnelEvaluation($manager_id,$beforerCurrentDate,2);
        Evaluation::deletePersonnelEvaluationManagerDetail(Auth::user()->id,$beforerCurrentDate,$check->id,$type=2);
        $i = 1; 
        foreach ($request->point as $key => $value) {
            $data[$i]['type'] = 2;
            $data[$i]['criteria_id'] = $key;
            $data[$i]['point'] = $value;
            $data[$i]['personnel_evaluation_id'] =  $check->id;
            $data[$i]['p_id'] = Auth::user()->id;
            $data[$i]['date'] = $beforerCurrentDate;
            $data[$i]['created_by'] = Auth::user()->id;
            $data[$i]['created_at'] = date('Y-m-d');
            $data[$i]['status'] = 1;
            $i++;
        }
        Evaluation::insertPersonnelEvaluationDetail( $data );
        $point =  Evaluation::pointPersonnelEvaluationDetail($check->id,$beforerCurrentDate,2);
        $total_point = intval($point)/intval($check->count);
        if( $total_point >= 1.02 &&  $total_point <=1.05){
            $kpi = 1;
        }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
            $kpi = 2;
        }else{
            $kpi = 3;
        }
        $arr = [
                'kpi' => $kpi,
                'total_point' => $total_point,
            ];
        Evaluation::updatePersonnelEvaluation( $manager_id,$beforerCurrentDate,$check->type,$arr);
        return redirect()->route('getEvaluationManagerbyMonthEdit',['id'=>$manager_id])->with(['flash_message_succ' => 'Bạn đã cập nhật thành công']);
    }

    // Tự đánh giá theo tháng
    public function getEvaluationMonthbyUser(){
        $data = Evaluation::checkEvaluationCriteriabyTime($type=0);
        // echo "<pre>";
        // print_r($data);die;
        if( count($data)>0 ){
            //Kiểm tra xem tài khoản có quyền đánh giá nhân viên trực thuộc quản lý của mình không
            $id = Auth::user()->id;
            $currentDate=date('Y-m-d');
            $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
            $checkPersonnelEvaluationDetailType0 = Evaluation::checkPersonnelEvaluationDetail($id,$beforerCurrentDate,0);
            $checkPersonnelEvaluationDetailType1 = Evaluation::checkPersonnelEvaluationDetail($id,$beforerCurrentDate,1);

            if( count($checkPersonnelEvaluationDetailType0) == 0 ){
                $check = Evaluation::checkDepartmentbyManager($id);
                $department_id = array();
                foreach ($check as $key => $value) {
                    $department_id[] = $value->id;
                }
                if( count($check)>0 ){
                    $listPersonnelbyManager = Evaluation::listPersonnelbyManager($department_id);
                }else{
                     $listPersonnelbyManager = '';
                }
                // echo "<pre>";
                // print_r($listPersonnelbyManager);die;
                $checkPersonnelEvaluationDetailType = Evaluation::checkPersonnelEvaluationDetail($id,$beforerCurrentDate,0);
                $param = ( count($checkPersonnelEvaluationDetailType) >0 )?1:"";
                return view('layouts.danhgia.danhgiathang.listEvaluationUserbyMonth',['data'=> $data,'listPersonnel'=>$listPersonnelbyManager,'param'=>$param]);
            }else{
                return redirect()->route('getEvaluationMonthbyUserEdit',['id'=>$id]);
            }
        }else{
            return view('layouts.danhgia.danhgiathang.listEvaluationUserbyMonth',['error_special'=> 'Chưa có tiêu chí đánh giá trong tháng '.date('m', strtotime("-1 month", strtotime(date('Y-m-d'))))]);
        }
        
    }

    public static function postEvaluationMonthbyUser(Request $request){
        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        // echo  $beforerCurrentDate;die;
        $id = Auth::user()->id;

        if( isset($request->comment)){
            $comment = $request->comment;
        }else{
            $comment= '';
        }
        $arr = [
                'personnel_id' => Auth::user()->id,
                'total_point' => 0,
                'type' =>  0, 
                'status'=>1,
                'comment' => $comment,
                'date' => $beforerCurrentDate,
                'created_at' =>  date('Y-m-d'), 
                'created_by'=>Auth::user()->id,
            ];
        Evaluation::insertPersonnelEvaluation( $arr );
        $personnel_evaluation_id = \DB::getPdo()->lastInsertId();
        $i = 1; 
        foreach ($request->point as $key => $value) {
            $data[$i]['type'] = 0;
            $data[$i]['criteria_id'] = $key;
            $data[$i]['point'] = $value;
            $data[$i]['personnel_evaluation_id'] = $personnel_evaluation_id;
            $data[$i]['date'] = $beforerCurrentDate;
            $data[$i]['created_by'] = Auth::user()->id;
            $data[$i]['created_at'] = date('Y-m-d');
            $data[$i]['status'] = 1;
            $i++;
        }
        Evaluation::insertPersonnelEvaluationDetail( $data );

        return redirect()->route('getEvaluationMonthbyUserEdit',['id'=>$id])->with(['flash_message_succ' => 'Bạn đã đánh giá thành công']);
        
    } 

    public function getEvaluationMonthbyUserEdit($id){
        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        $data = Evaluation::getInfoEvaluationbyPersonnel($id,$beforerCurrentDate,$type=0);
        // echo "<pre>";
        // print_r($data);
        return view('layouts.danhgia.danhgiathang.editEvaluationUserbyMonth',['data'=>$data]);
    }

    public function postEvaluationMonthbyUserEdit(Request $request){
        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        // echo  $beforerCurrentDate;die;
        $id = Auth::user()->id;
        $check = Evaluation::checkPersonnelEvaluation($id,$beforerCurrentDate,0);
        $checkPersonnelEvaluationDetailType1 = Evaluation::checkPersonnelEvaluationDetail($id,$beforerCurrentDate,1);

        Evaluation::deletePersonnelEvaluationDetail($beforerCurrentDate,$check->id,$type=0);
        $i = 1; 
        foreach ($request->point as $key => $value) {
            $data[$i]['type'] = 0;
            $data[$i]['criteria_id'] = $key;
            $data[$i]['point'] = $value;
            $data[$i]['personnel_evaluation_id'] = $check->id;
            $data[$i]['date'] = $beforerCurrentDate;
            $data[$i]['created_by'] = Auth::user()->id;
            $data[$i]['created_at'] = date('Y-m-d');
            $data[$i]['status'] = 1;
            $i++;
        }
        Evaluation::insertPersonnelEvaluationDetail( $data );

        if( count($checkPersonnelEvaluationDetailType1)>0 ){
            $point_0 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$beforerCurrentDate,0);
            $point_1 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$beforerCurrentDate,1);
            $total_point = (0.3*$point_0) + (0.7*$point_1);
            if( $total_point >= 1.02 &&  $total_point <=1.05){
                $kpi = 1;
            }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
                $kpi = 2;
            }else{
                $kpi = 3;
            }
            $arr = [
                    'kpi' => $kpi,
                    'total_point' => $total_point,
                    'comment' => $request->comment,
                    'updated_at' =>  date('Y-m-d'), 
                    'updated_by' =>  Auth::user()->id,
                ];
            Evaluation::updatePersonnelEvaluation( $id,$beforerCurrentDate,$check->type,$arr);
        }
        return redirect()->route('getEvaluationMonthbyUserEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Bạn đã cập nhật thành công']);

    }

    public function getEvaluationMonthbyManager($id){
        $data = Evaluation::checkEvaluationCriteriabyTime($type=0);
        // echo $id;die;
        if( count($data)>0 ){

            //Kiểm tra xem tài khoản có quyền đánh giá nhân viên trực thuộc quản lý của mình không
            $infoUser = Personnel::getInfo($id);

            $currentDate=date('Y-m-d');
            $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));

            $checkPersonnelEvaluationDetailType0 = Evaluation::checkPersonnelEvaluationDetail($id,$beforerCurrentDate,0);
            $checkPersonnelEvaluationDetailType1 = Evaluation::checkPersonnelEvaluationDetail($id,$beforerCurrentDate,1);

            if( count($checkPersonnelEvaluationDetailType1) == 0 ){
                $checkPersonnelEvaluationDetailType = Evaluation::checkPersonnelEvaluationDetail($id,$beforerCurrentDate,1);
                $param = ( count($checkPersonnelEvaluationDetailType) >0 )?1:"";
                return view('layouts.danhgia.danhgiathang.listEvaluationMonthbyManager',['data'=> $data,'infoUser'=>$infoUser,'param'=>$param,'id'=>$id]);
            }else{
                return redirect()->route('getEvaluationMonthbyManagerEdit',['id'=>$id]);
            }

        }else{
            
            return view('layouts.danhgia.danhgiathang.listEvaluationMonthbyManager',['error_special'=> 'Chưa có tiêu chí đánh giá trong tháng '.date('m', strtotime("-1 month", strtotime(date('Y-m-d'))))]);
        }
        
    }

    public function postEvaluationMonthbyManager(Request $request){
        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        // echo  $beforerCurrentDate;die;$request
        // $idManger = Auth::user()->id;
        $id = $request->userId;
        if( isset($request->comment)){
            $comment = $request->comment;
        }else{
            $comment= '';
        }
        $arr = [
                'personnel_id' => $request->userId,
                'total_point' => 0,
                'type' =>  0, 
                'status'=>1,
                'comment' => $comment,
                'date' => $beforerCurrentDate,
                'created_at' =>  date('Y-m-d'), 
                'created_by'=>Auth::user()->id,
            ];
        Evaluation::insertPersonnelEvaluation( $arr );
        $personnel_evaluation_id = \DB::getPdo()->lastInsertId();
        $i = 1; 
        foreach ($request->point as $key => $value) {
            $data[$i]['type'] = 1;
            $data[$i]['criteria_id'] = $key;
            $data[$i]['point'] = $value;
            $data[$i]['personnel_evaluation_id'] = $personnel_evaluation_id;
            $data[$i]['date'] = $beforerCurrentDate;
            $data[$i]['created_by'] = Auth::user()->id;
            $data[$i]['created_at'] = date('Y-m-d');
            $data[$i]['status'] = 1;
            $i++;
        }
        Evaluation::insertPersonnelEvaluationDetail( $data );
        return redirect()->route('getEvaluationMonthbyManagerEdit',['id'=>$id])->with(['flash_message_succ' => 'Đánh giá thành công']);
        
    }

    public function getEvaluationMonthbyManagerEdit($id){
        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        $data = Evaluation::getInfoEvaluationbyPersonnel($id,$beforerCurrentDate,$type=1);
        // echo "<pre>";
        // print_r($data);
        return view('layouts.danhgia.danhgiathang.editEvaluationMonthbyManager',['data'=>$data]);
    }

    public function postEvaluationMonthbyManagerEdit(Request $request){
        // echo 1;die;
        $currentDate=date('Y-m-d');
        $beforerCurrentDate = date('Y-m', strtotime("-1 month", strtotime($currentDate)));
        // echo  $beforerCurrentDate;die;
        $id = Auth::user()->id;
        $check = Evaluation::checkPersonnelEvaluation($request->id,$beforerCurrentDate,0);
        // echo "<pre>";
        // print_r($check);die;
        $checkPersonnelEvaluationDetailType = Evaluation::checkPersonnelEvaluationDetail($check->personnel_id,$beforerCurrentDate,0);
        // echo count($checkPersonnelEvaluationDetailType);die;
        Evaluation::deletePersonnelEvaluationDetail($beforerCurrentDate,$check->id,$type=1);
        $i = 1; 
        foreach ($request->point as $key => $value) {
            $data[$i]['type'] = 1;
            $data[$i]['criteria_id'] = $key;
            $data[$i]['point'] = $value;
            $data[$i]['personnel_evaluation_id'] = $check->id;
            $data[$i]['date'] = $beforerCurrentDate;
            $data[$i]['created_by'] = Auth::user()->id;
            $data[$i]['created_at'] = date('Y-m-d');
            $data[$i]['status'] = 1;
            $i++;
        }
        Evaluation::insertPersonnelEvaluationDetail( $data );

        if( count($checkPersonnelEvaluationDetailType)>0 ){
            $point_0 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$beforerCurrentDate,0);
            $point_1 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$beforerCurrentDate,1);
            $total_point = (0.3*$point_0) + (0.7*$point_1);
            if( $total_point >= 1.02 &&  $total_point <=1.05){
                $kpi = 1;
            }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
                $kpi = 2;
            }else{
                $kpi = 3;
            }
            $arr = [
                    'kpi' => $kpi,
                    'total_point' => $total_point,
                    'comment' => $request->comment,
                    'updated_at' =>  date('Y-m-d'), 
                    'updated_by' =>  Auth::user()->id,
                ];
            Evaluation::updatePersonnelEvaluation( $request->id,$beforerCurrentDate,$check->type,$arr);
        }else{
            $arr = [
                    'comment' => $request->comment,
                ];
            Evaluation::updatePersonnelEvaluation( $request->id,$beforerCurrentDate,$check->type,$arr);
        }
        return redirect()->route('getEvaluationMonthbyManagerEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Bạn đã cập nhật thành công']);
    }

// YEAR
    public function listPersonnelbyManger_Year(Request $request){
        $id = Auth::user()->id;
        $check = Evaluation::checkDepartmentbyManager($id);
        $year = date('Y');
        
        if( count($check)>0 ){
            $myfunc =  new Myfunction();
            foreach ($check as $key => $value) {
                $tmp[$value->id] =  $myfunc->categoryChild($value->id,'departments');
            }

            $department_id =  BatvHelper::array_keys_multi($tmp);
            $listExpires = $listType = Evaluation::listPersonnelbyManager($department_id);
            $turns = BatvHelper::getTurnsDefault();

            if( isset( $_GET['frequency'] ) ){

                if( date('m') >= 1 && date('m') <= 6 ){
                    if ($_GET['frequency'] == 1) {
                        $year = date('Y', strtotime(date('Y').' -1 year'));
                    }

                    $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
                }else{
                    $turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
                }
            }
            $data = Evaluation::where('date', $year )->where('turns', $turns )->lists('personnel_id')->toArray();
            $arr_personnel_id_expires = \DB::table('adhoc_salary_assessment')
                                ->where([
                                    ['year', '=', $year ],
                                    ['turns', '=', $turns ]
                                ])
                                ->whereNull( 'disable')
                               ->where( 'time_send_mail', '<', \Carbon\Carbon::now()->subDays(4))
                               ->lists('personnel_id');
            foreach ($listType as $k => $v) {
                if( !in_array( $v->id, $data) ){
                    unset($listType[$k]);
                } else {
                    unset($listExpires[$k]);
                }

                if( !in_array( $v->id, $arr_personnel_id_expires) ){
                    unset($listExpires[$k]);
                }
            }

            // dd($listType);

            return view('layouts.danhgia.danhgianam.listPersonnelbyManger_Year',['data'=> $listType,'turns'=>$turns, 'listExpires' => $listExpires]);
        }else{
            return view('layouts.danhgia.danhgianam.listPersonnelbyManger_Year',['error_special'=> 'Bạn là nhân viên, phấn đấu đê để trở thành quản lý (^ ^)']);
        }

        
    }
    // Tổng hợp điểm dánh giá theo năm
    public function getResultEvaluationManagerbyYear(Request $request){
        $button_duyet = false;
        $ids = [];
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

        $role_id = Auth::user()->role_id;
        $data =  Roles::where('id', $role_id)->first();
        $arr = explode(',', $data->privileges_id);
        $privileg = new Privilegs();
        $data2 = $privileg::find($arr);
        $arr_route = array();
        foreach ($data2 as $key => $value) {
           $arr_route[] = trim($value->router);
        }
        $arr_route = BatvHelper::listRolesByUser();
        $id = Auth::user()->id;
        
        if( in_array('danhgia-xemtoanbodanhgia',$arr_route) ){
            $id = 1;
        }

        $check = Evaluation::checkDepartmentbyManager($id);
        // echo "<pre>";
        // print_r($check);die;
        $param = array();
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
        }

        foreach ($check as $key => $value) {
            $param[$value->id] = $myfunc->categoryChild( $value->id,'departments' );
        }
        $department_id = array_unique( BatvHelper::array_keys_multi($param) );

        if( count($check)>0 ){
            $list_personnel_id = [];
            $listType = Evaluation::listPersonnelbyManager( $department_id );
            foreach ($listType as $k => $v) {
                $listPersonnelbyManager[] = $v->id;
            }

            $data = Evaluation::listResultPointCriteria($listPersonnelbyManager,$turns,$year,$ids);
            $arr = [];
            if( $data ){
                $arr_AdhocSalaryAssessment_Dx = AdhocSalaryAssessment::where('type',2)->where('turns',$turns)->where('year',$year)->lists('personnel_id')->toArray();
                // dd($data);
                foreach ($data as $key => $value) {
                    $list_personnel_id[] = $value->personnel_id;
                    $date_nlgn = BatvHelper::infoDateSalaryIncreaseNearest( $value->personnel_id ,$time);
                    $type = (in_array($value->personnel_id, $arr_AdhocSalaryAssessment_Dx))?0:1;// 1 - định kỳ, 0 - đột xuất
                    // dd($date_nlgn.'============='.$time.'-01');
                    $arr[$key]['number_month_nlgn']  = ( empty( $date_nlgn ) )?'':BatvHelper::calculateMonth( $date_nlgn,$time.'-01' );
                    $arr[$key]['personnel_id']  = $value->personnel_id;
                    $arr[$key]['management_allowance']  = $value->management_allowance;
                    $arr[$key]['management_allowance_old']  = $value->management_allowance_old;
                    $arr[$key]['first_name']  = $value->first_name;
                    $arr[$key]['last_name']  = $value->last_name;
                    $arr[$key]['fullname'] =$value->fullname;
                    $arr[$key]['email']  = $value->email;
                    $arr[$key]['phone_number']  = $value->phone_number;
                    $arr[$key]['gender']  = $value->gender;
                    $arr[$key]['birthday']  = $value->birthday;
                    $arr[$key]['date_in']  = $value->date_in;
                    $arr[$key]['date_out']  = $value->date_out;
                    $arr[$key]['salary_frequency']  = $value->salary_frequency;
                    $arr[$key]['department_id']  = $value->department_id;
                    $arr[$key]['contract_id']  = $value->contract_id;
                    $arr[$key]['indentity_card_id']  = $value->indentity_card_id;
                    $arr[$key]['home_town']  = $value->home_town;
                    $arr[$key]['address']  = $value->address;
                    $arr[$key]['options'] =$value->options;

                    if ($value->options == 0) {
                        $button_duyet = true;
                    }

                    $arr[$key]['title']  = $value->title;
                    $arr[$key]['time_attendance_machine']  = $value->time_attendance_machine;
                    $arr[$key]['comment_manager_final'] =$value->comment_manager_final;
                    $arr[$key]['insurrance'] =$value->insurrance;
                    $arr[$key]['type'] = $type;
                    if($value->options >= 1)
                        $arr[$key]['ratio_before'] = BatvHelper::getRatioSpecial($value->personnel_id,$time);
                    else{
                        $arr[$key]['ratio_before'] = BatvHelper::getRatioByTime($value->personnel_id,$time);
                    }

                    $arr[$key]['ratio_propose'] =$value->ratio_propose;

                    // if($value->ratio_propose > 0){
                        // if($value->options >= 1){
                        //     $arr[$key]['increase'] = $value->ratio_propose - BatvHelper::getRatioSpecial($value->personnel_id,$time);
                        // }else{
                        //     $arr[$key]['increase'] = $value->ratio_propose - BatvHelper::getRatioByTime($value->personnel_id,$time);
                        // }

                        $arr[$key]['number_month_TL'] = BatvHelper::getMonthTL($value->personnel_id,$type,$value->options,$time);
                    // }

                    
                    $arr[$key]['detail_total_point_personnel'] = BatvHelper::detailTotalPointCriteria( $value->personnel_id,$year,0,$turns );
                    $arr[$key]['detail_total_point_manager'] = BatvHelper::detailTotalPointCriteria( $value->personnel_id,$year,1,$turns );
                    $arr[$key]['result_final'] = 0.3*BatvHelper::detailTotalPointCriteria( $value->personnel_id,$year,0,$turns ) + 0.7*BatvHelper::detailTotalPointCriteria( $value->personnel_id,$year,1,$turns );

                    $info = Evaluation::infoPersonnelEvaluation($value->personnel_id,$year,$turns);

                    $arr[$key]['comment_send_personnel'] =  $info->comment_manager;
                    $arr[$key]['comment_send_manager'] = $info->comment;
                    $arr[$key]['management_allowance'] = $info->management_allowance;
                    $arr[$key]['management_allowance_old'] = $info->management_allowance_old;

                    $tmp = Salary::getPersonnelGroupDetailMuch($value->personnel_id,$type=[6]);

                    $tmp_2 = array();
                    foreach ($tmp as $key_1 => $value_1) {
                        $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

                    }

                    $arr_tmp = array();
                    foreach ($tmp_2 as $k => $v) {
                        foreach ($tmp_2[$k] as $k_1 => $v_2) {
                            $arr_tmp[] = $v_2;
                        }

                    }

                    $arr_tmp = array_map("unserialize", array_unique(array_map("serialize", $arr_tmp)));
                    $management_allowance_old = 0;
                    $dateCurrent = date('Y-m-d');

                    foreach ($arr_tmp as $key_2 => $value_2) {
                        $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[6]);
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
                                            $management_allowance_old = BatvHelper::calculateSpecial_2($string,$id,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
                                        }
                                    }
                                }
                            }
                        }
                        
                    }

                    Evaluation::updatePersonnelEvaluation( $value->personnel_id,date('Y'),1,['management_allowance_old' => $management_allowance_old],$turns);
                                   
                }
            }

            return view('layouts.danhgia.danhgianam.result',['data'=> $arr,'department'=>$select_depart, 'list_personnel_id' => $list_personnel_id, 'button_duyet' => $button_duyet]);
        }else{
            return view('layouts.danhgia.danhgianam.listPersonnelbyManger_Year',['error_special'=> 'Bạn là nhân viên, phấn đấu đê để trở thành quản lý (^ ^)']);
        }

        
    }

    //Nhân viên đánh giá quản lý theo năm
    public function getEvaluationManagerbyYear(){
        $id = Auth::user()->id;
        $data = Evaluation::checkEvaluationCriteriabyTime($type=2);
        $currentDate=date('Y');
        $turns = BatvHelper::getTurnsDefault();
        //Kiểm tra xem nhân viên được quản lý bởi ai
        $check = Evaluation::checkManager($id);
        if( count($data)>0 ){
            if( isset($check) ){
                if( $check->manager_id != $id){
                    // $tmp = Evaluation::checkPersonnelEvaluation($check->manager_id,$beforerCurrentDate,2);
                    // echo "<pre>";
                    // print_r($data);die;
                    $checkPersonnelEvaluationDetailType = Evaluation::getInfoEvaluationManagerbyPersonnel(Auth::user()->id,$check->manager_id,$currentDate,$type=2,$turns);
                    $param = ( count($checkPersonnelEvaluationDetailType) >0 )?1:"";
                    return view('layouts.danhgia.danhgianam.listEvaluationManagerbyYear',['data'=> $data,'param'=>$param,'manager_id'=>$check->manager_id ]);
                }else{
                    return view('layouts.danhgia.danhgianam.listEvaluationManagerbyYear',['error_special'=> 'Đây là mục dành cho nhân viên đánh giá quản lý. Bạn không thể tự Vote cho mình(^ ^)']);
                }
            }else{
                return view('layouts.danhgia.danhgianam.listEvaluationManagerbyYear',['error_special'=> 'Bạn không thuộc ai quản lý ']);
            }
            
        }else{
            return view('layouts.danhgia.danhgianam.listEvaluationManagerbyYear',['error_special'=> 'Chưa có tiêu chí đánh giá trong thời gian hiện tại']);
        }
    }
    public static function postEvaluationManagerbyYear(Request $request){
        // echo 1;die;
        $id = Auth::user()->id;
        //Kiêm tra xem nhân viên thuộc quản lý của User id nào
        $check = Evaluation::checkManager($id);
        $manager_id = $check->manager_id;

        $currentDate=date('Y');
        $turns = BatvHelper::getTurnsDefault();
        $check = Evaluation::checkPersonnelEvaluation($manager_id,$currentDate,3,$turns);
        // echo $check->id;die;
        if( count($check)>0 ){
            $checkPersonnelEvaluationDetailType = Evaluation::checkPersonnelEvaluationManagerDetail(Auth::user()->id,$check->id,$currentDate,2);
            // echo count($checkPersonnelEvaluationDetailType);die;
            if( count($checkPersonnelEvaluationDetailType) == 0 ){
                // echo 2;die;
                $i = 1; 
                foreach ($request->point as $key => $value) {
                    $data[$i]['type'] = 2;
                    $data[$i]['criteria_id'] = $key;
                    $data[$i]['point'] = $value;
                    $data[$i]['personnel_evaluation_id'] =  $check->id;
                    $data[$i]['p_id'] = Auth::user()->id;
                    $data[$i]['date'] = $currentDate;
                    $data[$i]['created_by'] = Auth::user()->id;
                    $data[$i]['created_at'] = date('Y-m-d');
                    $data[$i]['status'] = 1;
                    $i++;
                }
                Evaluation::insertPersonnelEvaluationDetail( $data );
                $point =  Evaluation::pointPersonnelEvaluationManagerDetail(Auth::user()->id,$check->id,$currentDate,2);
                $total_point =  (intval($point)+intval($check->total_point) )/(intval($check->count) +1 );
                // echo $total_point;
                if( $total_point >= 1.02 &&  $total_point <=1.05){
                    $kpi = 1;
                }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
                    $kpi = 2;
                }else{
                    $kpi = 3;
                }
                $arr = [
                        'kpi' => $kpi,
                        'total_point' => $total_point,
                        'count'       => $check->count +1,
                    ];
                Evaluation::updatePersonnelEvaluation( $manager_id,$currentDate,$check->type,$arr,$turns);
                return redirect()->route('getEvaluationManagerbyYear')->with(['flash_message_succ' => 'Bạn đã đánh giá thành công']);
               
            }else{
                return redirect()->route('getEvaluationManagerbyYear')->with(['flash_message_err' => 'Bạn đã đánh giá rồi']);
            }
        }else{
            //echo 1;die;
            if( isset($request->comment)){
                $comment = $request->comment;
            }else{
                $comment= '';
            }
            
            $arr = [
                    'personnel_id' => $manager_id,
                    'total_point' => 0,
                    'type' =>  3, 
                    'options' =>  0, 
                    'turns' =>  $turns, 
                    'count'=>1,
                    'status'=>1,
                    'comment' => $comment,
                    'date' => $currentDate,
                    'created_at' =>  date('Y-m-d'), 
                    'created_by'=>Auth::user()->id,
                ];
            Evaluation::insertPersonnelEvaluation( $arr );
            $personnel_evaluation_id = \DB::getPdo()->lastInsertId();
            $i = 1; 
            foreach ($request->point as $key => $value) {
                $data[$i]['type'] = 2;
                $data[$i]['criteria_id'] = $key;
                $data[$i]['point'] = $value;
                $data[$i]['personnel_evaluation_id'] = $personnel_evaluation_id;
                $data[$i]['p_id'] = Auth::user()->id;
                $data[$i]['date'] = $currentDate;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d');
                $data[$i]['status'] = 1;
                $i++;
            }
            Evaluation::insertPersonnelEvaluationDetail( $data );
            $point=  Evaluation::pointPersonnelEvaluationDetail($personnel_evaluation_id,$currentDate,2);

            $param = [
                        'total_point'=> $point
                    ];
            Evaluation::updatePersonnelEvaluation($manager_id,$currentDate,$check->type,$param,$turns);

            return redirect()->route('getEvaluationManagerbyYear')->with(['flash_message_succ' => 'Đánh giá thành công']);
        }
        
    } 
    public function getEvaluationManagerbyYearEdit($id){
        // echo 1;die;
        $currentDate=date('Y');
        $turns = BatvHelper::getTurnsDefault();
        $data = Evaluation::getInfoEvaluationManagerbyPersonnel(Auth::user()->id,$id,$currentDate,$type=2,$turns);
        // echo "<pre>";
        // print_r($data);
        return view('layouts.danhgia.danhgianam.editEvaluationManagerbyYear',['data'=>$data]);
    }

    public function postEvaluationManagerbyYearEdit(Request $request){
        $id = Auth::user()->id;
        //Kiêm tra xem nhân viên thuộc quản lý của User id nào
        $check = Evaluation::checkManager($id);
        $manager_id = $check->manager_id;

        $currentDate=date('Y');
        $turns = BatvHelper::getTurnsDefault();
        $check = Evaluation::checkPersonnelEvaluation($manager_id,$currentDate,3,$turns);
        Evaluation::deletePersonnelEvaluationManagerDetail(Auth::user()->id,$currentDate,$check->id,$type=2);
        $i = 1; 
        foreach ($request->point as $key => $value) {
            $data[$i]['type'] = 2;
            $data[$i]['criteria_id'] = $key;
            $data[$i]['point'] = $value;
            $data[$i]['personnel_evaluation_id'] =  $check->id;
            $data[$i]['p_id'] = Auth::user()->id;
            $data[$i]['date'] = $currentDate;
            $data[$i]['created_by'] = Auth::user()->id;
            $data[$i]['created_at'] = date('Y-m-d');
            $data[$i]['status'] = 1;
            $i++;
        }
        Evaluation::insertPersonnelEvaluationDetail( $data );
        $point =  Evaluation::pointPersonnelEvaluationDetail($check->id,$currentDate,2);
        $total_point = intval($point)/intval($check->count);
        if( $total_point >= 1.02 &&  $total_point <=1.05){
            $kpi = 1;
        }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
            $kpi = 2;
        }else{
            $kpi = 3;
        }
        $arr = [
                'kpi' => $kpi,
                'total_point' => $total_point,
            ];
        Evaluation::updatePersonnelEvaluation( $manager_id,$currentDate,$check->type,$arr);
        return redirect()->route('getEvaluationManagerbyYearEdit',['id'=>$manager_id])->with(['flash_message_succ' => 'Bạn đã cập nhật thành công']);
    }

    // Tự đánh giá theo năm
    public function getEvaluationYearbyUser(){ 

        $id = Auth::user()->id; 
        // $param = BatvHelper::listPesonnelAssessment( $id );
        // if( $param == 1 ){
            $data = Evaluation::checkEvaluationCriteriabyTime($type=1);
            if( count($data)>0 ){
                //Kiểm tra xem tài khoản có quyền đánh giá nhân viên trực thuộc quản lý của mình không
                
                $currentDate=date('Y');
                // $beforerCurrentDate = date('Y', strtotime("-1 year", strtotime($currentDate)));
                $turns = BatvHelper::getTurnsDefault();
                $check = Evaluation::where('personnel_id', $id)->where('date', $currentDate)->where('turns', $turns)->count();

                

                $checkPersonnelEvaluationDetailType0 = Evaluation::checkPersonnelEvaluationDetail($id,$currentDate,0,$turns);
                $checkPersonnelEvaluationDetailType1 = Evaluation::checkPersonnelEvaluationDetail($id,$currentDate,1,$turns);
                
                if( count($checkPersonnelEvaluationDetailType0) == 0 && $check == 0 ){
                    $check = Evaluation::checkDepartmentbyManager($id);
                    $department_id = array();
                    foreach ($check as $key => $value) {
                        $department_id[] = $value->id;
                    }
                    if( count($check)>0 ){
                        $listPersonnelbyManager = Evaluation::listPersonnelbyManager($department_id);
                    }else{
                         $listPersonnelbyManager = '';
                    }

                    return view('layouts.danhgia.danhgianam.listEvaluationUserbyYear',['data'=> $data,'listPersonnel'=>$listPersonnelbyManager]);
                }else{
                    return redirect()->route('getEvaluationYearbyUserEdit',['id'=>$id]);
                }

            }else{
                return view('layouts.danhgia.danhgianam.listEvaluationUserbyYear',['error_special'=> 'Chưa có tiêu chí đánh giá trong thời gian hiện tại']);
            }
        // }else{
        //     return redirect('/');
        // }

        
    }

    public static function postEvaluationYearbyUser(Request $request){
        $currentDate=date('Y');
        $id = Auth::user()->id;
        $turns = BatvHelper::getTurnsDefault();
        
        if( isset($request->comment)){
            $comment = $request->comment;
        }else{
            $comment= '';
        }
        $arr = [
                'personnel_id' => Auth::user()->id,
                'total_point' => 0,
                'type' =>  1, 
                'options' =>  0, 
                'turns' =>  $turns, 
                'status'=>1,
                'comment' => $comment,
                'date' => $currentDate,
                'created_at' =>  date('Y-m-d'), 
                'created_by'=>Auth::user()->id,
            ];
        Evaluation::insertPersonnelEvaluation( $arr );
        $personnel_evaluation_id = \DB::getPdo()->lastInsertId();
        $i = 1; 
        foreach ($request->point as $key => $value) {
            $data[$i]['type'] = 0;
            $data[$i]['criteria_id'] = $key;
            $data[$i]['point'] = $value;
            $data[$i]['personnel_evaluation_id'] = $personnel_evaluation_id;
            $data[$i]['date'] = $currentDate;
            $data[$i]['created_by'] = Auth::user()->id;
            $data[$i]['created_at'] = date('Y-m-d');
            $data[$i]['status'] = 1;
            $i++;
        }
        Evaluation::insertPersonnelEvaluationDetail( $data );

        // Gửi Email cho thông tin quản lý cấp gần nhất của quản lý
        $infoManagerChild = Personnel::getCurrentInfo(  Auth::user()->id  );
        $infoManager = Personnel::getCurrentInfo( $infoManagerChild->manager_id );
        $email = $infoManager->email;
        if( $infoManager->id == $infoManagerChild->id ){
           $myfunc =  new Myfunction();
           $tmp=  $myfunc->categoryParent($infoManagerChild->department_id);   
           $department_id =  BatvHelper::array_keys_multi($tmp);
           foreach ($department_id as $value) {
                $arr_manager_id[] = Evaluation::infoDepartment( $value );
           }
           foreach ($arr_manager_id as $value) {
                if( $infoManagerChild->id != $value ){
                    $email = Personnel::getCurrentInfo( $value )->email;
                    break;
                }
           }

        }
        // echo $email;die;
        $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 4 );
        $subject = $infoConfigMail->mail_subject;
        $content_mail = array(
                            'content'=>$infoConfigMail->mail_content,
                            'subject'=>$subject,
                            'fullname'=> $infoManagerChild->fullname,
                            'link'=> route('getEvaluationYearbyManager',[$id,$currentDate,$turns]),
                        );
        \Mail::send('emails.notification_evaluation', $content_mail,  function ($message) use ($email, $subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->to($email)->subject($subject);
        });
        return  redirect()->route('getEvaluationYearbyUserEdit',['id'=>$id])->with(['flash_message_succ' => 'Đánh giá năm thành công']);        
    } 

    public function getEvaluationYearbyUserEdit($id){
        $currentDate=date('Y');
        $turns = BatvHelper::getTurnsDefault();
        $data = Evaluation::getInfoEvaluationbyPersonnel($id,$currentDate,$type=0,$turns);
        // echo "<pre>";
        // print_r($data);
        return view('layouts.danhgia.danhgianam.editEvaluationUserbyYear',['data'=>$data]);
    }

    public function postEvaluationYearbyUserEdit(Request $request){
        $currentDate=date('Y');
        $turns = BatvHelper::getTurnsDefault();
        $id = Auth::user()->id;
        $checkSpecial = Evaluation::where('personnel_id', $id)->where('date', $currentDate)->where('turns', $turns)->where('options', 1)->count();
        if( $checkSpecial >=1 ){
            return redirect()->route('getEvaluationYearbyUserEdit',['id'=>$request->id])->with(['flash_message_err' => 'Quản lý đã chốt, bạn không thể chỉnh sửa ']);
        }else{
            $check = Evaluation::checkPersonnelEvaluation($id,$currentDate,1,$turns);
            $checkPersonnelEvaluationDetailType1 = Evaluation::checkPersonnelEvaluationDetail($id,$currentDate,1,$turns);

            Evaluation::deletePersonnelEvaluationDetail($currentDate,$check->id,$type=0);
            $i = 1; 
            foreach ($request->point as $key => $value) {
                $data[$i]['type'] = 0;
                $data[$i]['criteria_id'] = $key;
                $data[$i]['point'] = $value;
                $data[$i]['personnel_evaluation_id'] = $check->id;
                $data[$i]['date'] = $currentDate;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d');
                $data[$i]['status'] = 1;
                $i++;
            }
            Evaluation::insertPersonnelEvaluationDetail( $data );

            if( count($checkPersonnelEvaluationDetailType1)>0 ){
                $point_0 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$currentDate,0);
                $point_1 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$currentDate,1);
                $total_point = (0.3*$point_0) + (0.7*$point_1);
                if( $total_point >= 1.02 &&  $total_point <=1.05){
                    $kpi = 1;
                }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
                    $kpi = 2;
                }else{
                    $kpi = 3;
                }
                $arr = [
                        'kpi' => $kpi,
                        'total_point' => $total_point,
                        'comment' => $request->comment,
                        'updated_at' =>  date('Y-m-d'), 
                        'updated_by' =>  Auth::user()->id,
                    ];
                Evaluation::updatePersonnelEvaluation( $id,$currentDate,$check->type,$arr,$turns);
            }

            // Gửi Email cho thông tin quản lý cấp gần nhất của quản lý
            $infoManagerChild = Personnel::getCurrentInfo(  Auth::user()->id  );
            $infoManager = Personnel::getCurrentInfo( $infoManagerChild->manager_id );
            $email = $infoManager->email;
            if( $infoManager->id == $infoManagerChild->id ){
               $myfunc =  new Myfunction();
               $tmp=  $myfunc->categoryParent($infoManagerChild->department_id);   
               $department_id =  BatvHelper::array_keys_multi($tmp);
               foreach ($department_id as $value) {
                    $arr_manager_id[] = Evaluation::infoDepartment( $value );
               }

               foreach ($arr_manager_id as $value) {
                    if( $infoManagerChild->id != $value ){
                        $email = Personnel::getCurrentInfo( $value )->email;
                        break;
                    }
               }

            }

            $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 4 );
            $subject = $infoConfigMail->mail_subject;
            $content_mail = array(
                                'content'=>$infoConfigMail->mail_content,
                                'subject'=>$subject,
                                'fullname'=> $infoManagerChild->fullname,
                                'link'=> route('getEvaluationYearbyManager',[$id,$currentDate,$turns]),
                                'status'=>1
                            );

            \Mail::send('emails.notification_evaluation', $content_mail,  function ($message) use ($email, $subject) {
                $message->from('nhansu@tohsoft.com', 'TOH');
                $message->to($email)->subject($subject);
            });

            return redirect()->route('getEvaluationYearbyUserEdit',['id'=>$request->id])->with(['flash_message_succ' => 'Bạn đã cập nhật thành công']);
        }

    }

    public function getEvaluationYearbyManager($id,$year,$turns){
        $data = Evaluation::checkEvaluationCriteriabyTime($type=1);
        $time = ( $turns == 1 ) ? $year.'-06' : $year.'-12';
        if( count($data)>0 ){
            //Kiểm tra xem tài khoản có quyền đánh giá nhân viên trực thuộc quản lý của mình không
            $infoUser = Personnel::getInfo($id);
            $tdgn = Evaluation::getInfoEvaluationbyPersonnel($id,$year,$type=0,$turns);
            $checkPersonnelEvaluationDetailType0 = Evaluation::checkPersonnelEvaluationDetail($id,$year,0,$turns);
            $checkPersonnelEvaluationDetailType1 = Evaluation::checkPersonnelEvaluationDetail($id,$year,1,$turns);
            // echo "<pre>";
            // print_r($tdgn);
            // echo "</pre>";die;
            if( count($checkPersonnelEvaluationDetailType1) == 0 ){
                $tmp = Salary::getPersonnelGroupDetailMuch($id,$type=[6]);

                $tmp_2 = array();
                foreach ($tmp as $key_1 => $value_1) {
                    $tmp_2[] = Salary::getIncomeConfigGroup($tmp[$key_1]->personnel_group_id);

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
                    $tmp_3 = Salary::getIncomeConfig($value_2->income_config_id,$dateCurrent,$type=[6]);
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
                                        $management_allowance_old = BatvHelper::calculateSpecial_2($string,$id,$time,$type=2,'',$option=1,$convert_ratio='',$dayLatches='');
                                    }
                                }
                            }
                        }
                    }
                    
                }

                return view('layouts.danhgia.danhgianam.listEvaluationYearbyManager',['data'=> $data,'infoUser'=>$infoUser,'id'=>$id,'tdgn'=>$tdgn,'year'=>$year,'turns'=>$turns,'time'=>$time, 'management_allowance_old' => $management_allowance_old]);
            }else{
                // echo 2;die;
                return redirect()->route('getEvaluationYearbyManagerEdit',[$id,$year,$turns]);
            }
        }else{
            return view('layouts.danhgia.danhgianam.listEvaluationYearbyManager',['error_special'=> 'Chưa có tiêu chí đánh giá trong thời gian hiện tại']);
        }
        
    }

    public function postEvaluationYearbyManager(Request $request){
        $personnel_id = $request->id;
        $year = $request->year;
        $turns = $request->turns;
        $check = Evaluation::checkPersonnelEvaluation($request->id,$year,1,$turns);

        if( isset($request->comment)){
            $comment = $request->comment;
        }else{
            $comment= '';
        }

        $management_allowance  = 0;

        if( $request->change_management_allowance == 1){
            $management_allowance = str_replace(",","", $request->management_allowance);
            $management_allowance = (int)$management_allowance;
        }

        if( $check->id ){
            $arr = [
                    'comment' => $comment,
                    'comment_manager' => $request->comment_manager,
                    'ratio_propose' => $request->ratio_propose,
                    'management_allowance' => $management_allowance,
                    'management_allowance_old' => $request->management_allowance_old,
                ];
            Evaluation::updatePersonnelEvaluation( $check->personnel_id,$year,$type=1,$arr,$turns );
            $i = 1; 
            foreach ($request->point as $key => $value) {
                $data[$i]['type'] = 1;
                $data[$i]['criteria_id'] = $key;
                $data[$i]['point'] = $value;
                $data[$i]['personnel_evaluation_id'] =  $check->id;
                $data[$i]['date'] = $year;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d');
                $data[$i]['status'] = 1;
                $i++;
            }
            Evaluation::insertPersonnelEvaluationDetail( $data );
        }else{
            $arr = [
                    'personnel_id' => $personnel_id,
                    'total_point' => 0,
                    'type' =>  1, 
                    'options' =>  0, 
                    'turns' =>  $turns, 
                    'status'=>1,
                    'comment' => $comment,
                    'comment_manager' => $request->comment_manager,
                    'ratio_propose' => $request->ratio_propose,
                    'management_allowance' => $management_allowance,
                    'management_allowance_old' => $request->management_allowance_old,
                    'date' => $year,
                    'created_at' =>  date('Y-m-d'), 
                    'created_by'=>Auth::user()->id,
                ];
            Evaluation::insertPersonnelEvaluation( $arr );
            $personnel_evaluation_id = \DB::getPdo()->lastInsertId();
            $i = 1; 
            foreach ($request->point as $key => $value) {
                $data[$i]['type'] = 1;
                $data[$i]['criteria_id'] = $key;
                $data[$i]['point'] = $value;
                $data[$i]['personnel_evaluation_id'] = $personnel_evaluation_id;
                $data[$i]['date'] = $year;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d');
                $data[$i]['status'] = 1;
                $i++;
            }
            Evaluation::insertPersonnelEvaluationDetail( $data );
        }
        $checkPersonnelEvaluationDetailType = Evaluation::checkPersonnelEvaluationDetail($check->personnel_id,$year,0,$turns);
        if( count($checkPersonnelEvaluationDetailType)>0 ){

            $point_0 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$year,0);
            $point_1 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$year,1);
            $total_point = (0.3*$point_0) + (0.7*$point_1);
            if( $total_point >= 1.02 &&  $total_point <=1.05){
                $kpi = 1;
            }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
                $kpi = 2;
            }else{
                $kpi = 3;
            }
            $arr = [
                    'kpi' => $kpi,
                    'total_point' => $total_point,
                ];
            Evaluation::updatePersonnelEvaluation( $personnel_id,$year,$check->type,$arr,$turns);

        }

        // Gửi Email cho thông tin quản lý cấp gần nhất của quản lý
        $infoManagerChild = Personnel::getCurrentInfo(  Auth::user()->id  );
        $infoManager = Personnel::getCurrentInfo( $infoManagerChild->manager_id );
        $email = $infoManager->email;
        if( $infoManager->id == $infoManagerChild->id ){
           $myfunc =  new Myfunction();
           $tmp=  $myfunc->categoryParent($infoManagerChild->department_id);   
           $department_id =  BatvHelper::array_keys_multi($tmp);
           foreach ($department_id as $value) {
                $arr_manager_id[] = Evaluation::infoDepartment( $value );
           }

           foreach ($arr_manager_id as $value) {
                if( $infoManagerChild->id != $value ){
                    $email = Personnel::getCurrentInfo( $value )->email;
                    break;
                }
           }

        }

        $infoUser = Personnel::getCurrentInfo(  $personnel_id  );
        $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 4 );
        $subject = $infoConfigMail->mail_subject;
        $content_mail = array(
                            'content'=>$infoConfigMail->mail_content,
                            'subject'=>$subject,
                            'fullname'=> $infoUser->fullname,
                            'link'=> route('getEvaluationYearbyManager',[$personnel_id,$year,$turns]),
                            'manager'=> $infoManagerChild->fullname,
                            'comment_manager_send_personnel' => $request->comment_manager,
                            'comment_manager_send_direction' => $request->comment,
                        );
        $email_cc[] = User::where('id',Auth::user()->id)->value('email');
        \Mail::send('emails.notification_evaluation', $content_mail,  function ($message) use ($email,$email_cc,$subject) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->cc($email_cc);
            $message->to($email)->subject($subject);
        });

        return redirect()->route('getEvaluationYearbyManagerEdit',[$personnel_id,$year,$turns])->with(['flash_message_succ' => 'Đánh giá cho nhân viên và gửi Email cho quản lý cấp trên thành công']);
        
    }

    public function getEvaluationYearbyManagerEdit($id,$year,$turns){
        $time = ( $turns == 1 ) ? $year.'-06' : $year.'-12';
        $checkSpecial = Evaluation::where('personnel_id', $id)->where('date', $year)->where('turns', $turns)->where('options', 1)->count();
        $data = Evaluation::getInfoEvaluationbyPersonnel($id,$year,$type=1,$turns);

        $tdgn = Evaluation::getInfoEvaluationbyPersonnel($id,$year,$type=0,$turns);

        return view('layouts.danhgia.danhgianam.editEvaluationYearbyManager',['data'=>$data,'tdgn'=>$tdgn,'checkSpecial'=>$checkSpecial,'personnel_id'=>$id,'turns'=>$turns,'year'=>$year,'time'=>$time]);
    }

    public function postEvaluationYearbyManagerEdit(Request $request){
        $year = $request->year;
        $turns = $request->turns;
        $personnel_id = $request->id;
        $id = Auth::user()->id;
        $checkSpecial = Evaluation::where('personnel_id', $personnel_id)->where('date', $year)->where('turns', $turns)->where('options', 1)->count();
        $check = Evaluation::checkPersonnelEvaluation($personnel_id,$year,1,$turns);
        if( isset( $_POST['cancel'] ) ){
            $arr = [
                    'options' => 0,
                ];
            Evaluation::updatePersonnelEvaluation( $personnel_id,$year,$check->type,$arr,$turns);
            return redirect()->route('getEvaluationYearbyManagerEdit',['id'=>$personnel_id])->with(['flash_message_succ' => 'Bạn đã hủy chốt thành công']);
        }
        if( $checkSpecial >=1 ){
            return redirect()->route('getEvaluationYearbyManagerEdit',['id'=>$personnel_id])->with(['flash_message_err' => 'Tổng giám đốc đã chốt, bạn không thể chỉnh sửa ']);

        }else{
            $checkPersonnelEvaluationDetailType = Evaluation::checkPersonnelEvaluationDetail($check->personnel_id,$year,0,$turns);
            // echo count($checkPersonnelEvaluationDetailType);die;
            // echo $check->id;die;
            Evaluation::deletePersonnelEvaluationDetail($year,$check->id,$type=1);
            $i = 1; 
            foreach ($request->point as $key => $value) {
                $data[$i]['type'] = 1;
                $data[$i]['criteria_id'] = $key;
                $data[$i]['point'] = $value;
                $data[$i]['personnel_evaluation_id'] = $check->id;
                $data[$i]['date'] = $year;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d');
                $data[$i]['status'] = 1;
                $i++;
            }
            Evaluation::insertPersonnelEvaluationDetail( $data );

            $management_allowance  = 0;

            if( $request->change_management_allowance == 1){
                $management_allowance = str_replace(",","", $request->management_allowance);
                $management_allowance = (int)$management_allowance;
            }

            if( count($checkPersonnelEvaluationDetailType)>0 ){

                $point_0 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$year,0);
                $point_1 =  Evaluation::pointPersonnelEvaluationDetail($check->id,$year,1);
                $total_point = (0.3*$point_0) + (0.7*$point_1);
                if( $total_point >= 1.02 &&  $total_point <=1.05){
                    $kpi = 1;
                }elseif ($total_point >= 0.95 &&  $total_point <=0.98) {
                    $kpi = 2;
                }else{
                    $kpi = 3;
                }
                $arr = [
                        'kpi' => $kpi,
                        'total_point' => $total_point,
                        'comment' => $request->comment,
                        'comment_manager' => $request->comment_manager,
                        'ratio_propose' => $request->ratio_propose,
                         'management_allowance' => $management_allowance,
                        'updated_at' =>  date('Y-m-d'), 
                        'updated_by' =>  Auth::user()->id,
                    ];
                Evaluation::updatePersonnelEvaluation( $personnel_id,$year,$check->type,$arr,$turns);

            }else{

                $arr = [
                        'comment' => $request->comment,
                        'comment_manager' => $request->comment_manager,
                        'ratio_propose' => $request->ratio_propose,
                         'management_allowance' => $management_allowance,
                    ];
                Evaluation::updatePersonnelEvaluation( $personnel_id,$year,$check->type,$arr,$turns);
            }
            if( isset($_POST['send_email']) ){
                // Gửi Email cho thông tin quản lý cấp gần nhất của quản lý
                $infoManagerChild = Personnel::getCurrentInfo(  Auth::user()->id  );
                $infoManager = Personnel::getCurrentInfo( $infoManagerChild->manager_id );
                $email = $infoManager->email;
                if( $infoManager->id == $infoManagerChild->id ){
                   $myfunc =  new Myfunction();
                   $tmp=  $myfunc->categoryParent($infoManagerChild->department_id);   
                   $department_id =  BatvHelper::array_keys_multi($tmp);
                   foreach ($department_id as $value) {
                        $arr_manager_id[] = Evaluation::infoDepartment( $value );
                   }

                   foreach ($arr_manager_id as $value) {
                        if( $infoManagerChild->id != $value ){
                            $email = Personnel::getCurrentInfo( $value )->email;
                            break;
                        }
                   }

                }
                $infoUser = Personnel::getCurrentInfo($check->personnel_id);
                $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 4 );
                $subject = $infoConfigMail->mail_subject;
                $content_mail = array(
                                    'content'=>$infoConfigMail->mail_content,
                                    'subject'=>$subject,
                                    'fullname'=> $infoUser->fullname,
                                    'link'=> route('getEvaluationYearbyManager',[$id,$year,$turns]),
                                    'manager'=> $infoManagerChild->fullname,
                                    'comment_manager_send_personnel' => $request->comment_manager,
                                    'comment_manager_send_direction' => $request->comment,
                                );
                $email_cc[] = User::where('id',Auth::user()->id)->value('email');
                \Mail::send('emails.notification_evaluation', $content_mail,  function ($message) use ($email,$email_cc, $subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->cc($email_cc);
                    $message->to($email)->subject($subject);
                });
                return redirect()->route('getEvaluationYearbyManagerEdit',[$personnel_id,$year,$turns])->with(['flash_message_succ' => 'Bạn đã gửi Email thành công']);
            }
            return redirect()->route('getEvaluationYearbyManagerEdit',[$personnel_id,$year,$turns])->with(['flash_message_succ' => 'Bạn đã cập nhật thành công']);
        }

    }
    //END YEAR


    public function postEvaluationSupport(Request $request){
        $rules = [
            'txt_support' =>'required',
        ];
        $messages = [
            'txt_support.required' => 'Nội dung không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
             $arr = [
                'content' =>  $request->txt_support,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d'),
            ];
            Evaluation::insertEvaluationSupport($arr);  
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function viewEvaluationSupport(){
        $data = Evaluation::getInfoEvaluationSupport();
        return view('layouts.danhgia.xemhuongdan',['data'=> $data]);
    }

    //Danh sách bộ tiêu chí
    public function listDepartmentCriteria( Request $request){
        $result = Evaluation::listDepartmentCriteria($request);
        $arr = array();
        $result = json_decode(json_encode($result), true);

        if( $result ){
            foreach ($result as $key => $value) {
                if( !isset($arr[$value['id']]) ){
                    $arr[$value['id']] = array(
                                                'id'=>$value['id'],
                                                'date_start'=>$value['date_start'],
                                                'date_end'=>$value['date_end'],
                                                'title'=>$value['title'],
                                                'criteria_content'=>array($value['criteria_content'],)
                                            );
                }else{
                    $arr[$value['id']]['criteria_content'][] = $value['criteria_content'];
                }
            }
        }
        $data = BatvHelper::PagingDataSpecial($arr);
        return view('layouts.danhgia.listDepartmentCriteria',['data'=> $data]);
    }

    public function settingEvaluationCriteria(){
        //echo $checkEvaluationStagebyTime = Evaluation::checkEvaluationStagebyTime();
        $data = Evaluation::listCriteria();
        return view('layouts.danhgia.setting',['data'=> $data]);
    }

    public function  postsettingEvaluationCriteria( Request $request ){
        Validator::extend('check_evaluation_stage_by_time', function($attribute, $value, $parameters, $validator) {
          $data_validator = $validator->getData();
          $data_validator = (object)$data_validator;
          $checkEvaluationStagebyTime = Evaluation::checkEvaluationStagebyTime($data_validator);
          return ( $checkEvaluationStagebyTime > 0 )?true:false;
        }); 
        $rules = [
            'title' => 'required|max:255',
            'type' => 'required',
            'startdate' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'enddate' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field:startdate|check_evaluation_stage_by_time',
            'criteria'=> 'required'
        ];
        $messages = [
            'title.required'=>'Tên bộ tiêu chí không được để trống',
            'title.max'=>'Tên bộ tiêu chí không được quá 255 ký tự',
            'type.required'=>'Bạn chưa chọn loại bộ tiêu chí',
            'startdate.required'=>'Bạn chưa nhập ngày',
            'startdate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'startdate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'enddate.required'=>'Bạn chưa nhập ngày',
            'enddate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'enddate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'enddate.greater_than_field'=>'Nhập thời gian không hợp lệ',
            'enddate.check_evaluation_stage_by_time'=>'Thời gian bạn chọn nằm trong khoảng thời gian của bộ tiêu chí ở thời gian trước',
            'criteria.required'=>'Bạn chưa chọn danh sách các tiêu chí',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $startdate = DateTime::createFromFormat('d/m/yy', $request->startdate); 
            $enddate = DateTime::createFromFormat('d/m/yy', $request->enddate); 
            $startdate_format = $startdate->format('Y-m-d');
            $enddate_format = $enddate->format('Y-m-d');
            $criteria = $request->criteria;
            $arr = [
                'title'      => $request->title,
                'type'      => $request->type,
                'date_start' =>  $startdate_format,
                'date_end' => $enddate_format,
                'created_at' => date('Y-m-d'),
                'created_by'=>Auth::user()->id
            ];
            // echo "<pre>";
            // print_r($criteria);die;
            Evaluation::insertEvaluationStage($arr);
            $id =  \DB::getPdo()->lastInsertId();  
            foreach ($criteria as $key => $value) {
                $data = [
                    'stage_id' => $id,
                    'criteria_id' => $value,
                ];
                Evaluation::insertEvaluationStageDetail($data);
            }
            return back()->with(['flash_message_succ' => 'Cài đặt thành công']);
            
        }
    }

    public static function editDepartmentCriteria($id){
        $data = Evaluation::getInfoDepartmentCriteria($id);
        $listCriteria = Evaluation::listCriteria();
        $result = $arr = array();

        foreach ($data as $key => $value) {
            $arr[] = $value->evaluation_criteria_id;
        }

        foreach ($listCriteria as $key => $value) {
            if( in_array($value->id, $arr) ){
                $result[] = array(
                            'id'=>$value->id,
                            'criteria_content'=> $value->criteria_content,
                            'type'=>1
                        );
            }else{
                $result[] = array(
                            'id'=>$value->id,
                            'criteria_content'=> $value->criteria_content,
                            'type'=>''
                        );
            }
        }
        // echo "<pre>";
        // print_r($data);die;
        return view( 'layouts.danhgia.editDepartmentCriteria',['data'=>$data,'listCriteria'=>$result] );
    }

    public static function postEditDepartmentCriteria(Request $request, $id){
        Validator::extend('check_evaluation_stage_by_time', function($attribute, $value, $parameters, $validator) {
          $data_validator = $validator->getData();
          $data_validator = (object)$data_validator;
          $checkEvaluationStagebyTimeEdit = Evaluation::checkEvaluationStagebyTimeEdit($data_validator,$data_validator->id);
          return ( $checkEvaluationStagebyTimeEdit >0 )?true:false;
        }); 
        $rules = [
            'title' => 'required|max:255',
            'type' => 'required',
            'startdate' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
            'enddate' => 'required|date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|greater_than_field:startdate|check_evaluation_stage_by_time',
            'criteria'=> 'required'
        ];
        $messages = [
            'title.required'=>'Tên bộ tiêu chí không được để trống',
            'title.max'=>'Tên bộ tiêu chí không được quá 255 ký tự',
            'type.required'=>'Bạn chưa chọn loại bộ tiêu chí',
            'startdate.required'=>'Bạn chưa nhập ngày',
            'startdate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'startdate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'enddate.required'=>'Bạn chưa nhập ngày',
            'enddate.date_format'=>'Định dạng ngày phải là dd/mm/yyyy',
            'enddate.regex'=> 'Định dạng ngày phải là dd/mm/yyyy',
            'enddate.greater_than_field'=>'Nhập thời gian không hợp lệ',
            'enddate.check_evaluation_stage_by_time'=>'Thời gian bạn chọn nằm trong khoảng thời gian của bộ tiêu chí ở thời gian trước',
            'criteria.required'=>'Bạn chưa chọn danh sách các tiêu chí',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $startdate_format = BatvHelper::formatDate($request->startdate, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false); 
            $enddate_format = BatvHelper::formatDate($request->enddate, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $criteria = $request->criteria;
            $arr = [
                'title'      => $request->title,
                'type'      => $request->type,
                'date_start' =>  $startdate_format,
                'date_end' => $enddate_format,
                'updated_at'=>date('Y-m-d'),
                'updated_by'=>Auth::user()->id,
            ];
            // echo "<pre>";
            // print_r($criteria);die;
            Evaluation::updateEvaluationStage($arr,$id);
            Evaluation::deleteEvaluationStageDetail($id);
            foreach ($criteria as $key => $value) {
                $data = [
                    'stage_id' => $id,
                    'criteria_id' => $value,
                ];
                Evaluation::insertEvaluationStageDetail($data);
            }

            return redirect()->route('editDepartmentCriteria',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function deleteEvaluationCriteriaAjax(Request $request){

        if ($request->ajax()) {
            $check = Evaluation::checkInfoDepartmentCriteria();
            $arr = array();

            foreach ($check as $key => $value) {
                $arr[] = $value->evaluation_criteria_id;
            }
            if( in_array($request->id, $arr) ){
                $res=array('Response'=>"Error","Error"=>"Tiêu chí đã có hiệu lực trong bộ tiêu chí. Bạn không thể xóa được" );
            }else{
                $data = Evaluation::deleteEvaluationCriteria($request->id);
                if($data){
                    $res=array('Response'=>"Success","Message"=>"Xóa tiêu chí thành công" );
                }
            }
            echo json_encode($res);
        }
    }

    public function deleteDepartmentCriteria($id){
        $check = Evaluation::checkInfoDepartmentCriteria($id);
        if( count($check) >0 ){
            return back()->with(['flash_message_err' =>'Bộ tiêu chí đã được áp dụng, bạn không thể xóa được !!!']);
        }else{
            $data = Evaluation::deleteDepartmentCriteria($id);
            if($data){
                return back()->with(['flash_message_succ' =>'Xóa bộ tiêu chí thành Công']);
            }else{
                 return back()->with(['flash_message_err' =>'Xảy ra lỗi trong quá trình xóa']);
            }
        }

    }

    public function approvalSalaryAjax(Request $request){
        if ($request->ajax()) {
            $yearRoot = (int)$request->year;
            $currentDate = $yearRoot;
            $turns = ( date('m') >= 3 && date('m') <= 9 ) ? 1 : 2 ;
            $check = Evaluation::checkPersonnelEvaluation($request->id,$currentDate,1,$turns);

            if ($check->options == 0) {
                $info_management_allowance = $request->info_management_allowance;
                $info_management_allowance = '';

                if ($check->management_allowance > 0) {
                    $info_management_allowance = $request->info_management_allowance;
                }
                // Sau khi Sếp phê duyệt thì tự động cập nhật hệ số chức danh
                $apply_to = ( $turns == 1 )?$yearRoot."-06-".cal_days_in_month(CAL_GREGORIAN,6,$yearRoot):$yearRoot."-12-".cal_days_in_month(CAL_GREGORIAN,12,$yearRoot);

                $year = BatvHelper::formatDate($apply_to,"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",false);

                $apply_from = ( $turns == 1 )?$year."-07-01":($year+1)."-01-01";
                $id_ratio = BatvHelper::getIdbyRatioCurrent( $request->id );
                $arr_ratio_update = [
                        'apply_to'      => $apply_to,
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date('Y-m-d'),
                    ];
                History::updateRatio($arr_ratio_update,$id_ratio);

                $arr_ratio_insert = [
                        'apply_from' => $apply_from,
                        'apply_to' =>   "2025-12-31",
                        'ratio' => $request->ratio_propose,
                        'personnel_ID' => $request->id,
                        'created_by'    => Auth::user()->id,
                        'created_at'    => date('Y-m-d'),
                        'status' => 1,
                    ];
                History::insertRatio($arr_ratio_insert);

                $arr = [
                        'options' => 1,
                        'comment_manager_final'=>$request->comment_manager_final,
                    ];
                Evaluation::updatePersonnelEvaluation( $request->id,$currentDate,$check->type,$arr,$turns);
                // SEND MAIL
                $infoUser = Personnel::getCurrentInfo(  $request->id  );
                // Gửi Email báo cho nhân viên khi được sếp phê duyệt
                $infoConfigMail = EmailConfig::getInfoEmailConfig( $type = 6 );
                $subject = $infoConfigMail->mail_subject;
                $content_mail_personnel = array(
                                    'content'=>$infoConfigMail->mail_content,
                                    'subject'=>$subject,
                                    'comment_manager_final'=>$request->comment_manager_final,
                                    'comment_manager'=>$check->comment_manager,
                                    'info_salary' => $request->info_salary,
                                    'info_management_allowance' => $info_management_allowance,
                                );
                $email[] = $infoUser->email;

                if( !empty( $infoConfigMail->mail_to ) ){
                    $email_others = explode(",",$infoConfigMail->mail_to);
                    foreach ($email_others as $key => $value) {
                        $email[] = User::where('id',$value)->value('email');
                    }
                }
                $email = array_unique($email);

                $email_cc[] = User::where('id',Auth::user()->id)->value('email');

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

                \Mail::send('emails.notification_salary_success_personnel', $content_mail_personnel,  function ($message) use ($email,$email_cc, $subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->cc($email_cc);
                    $message->to($email)->subject($subject);
                });
                // Gửi Email báo cho quản lý của nhân viên đó + kế toán khi được sếp phê duyệt
                $listEmailConvert = [];
                $infoConfigMailSetting = EmailConfig::getInfoEmailConfig($type = 7);
                $listEmail = EmailConfig::getListEmailbyidPersonnel( explode(",",$infoConfigMailSetting->mail_to) );

                if( $listEmail ){
                    foreach ($listEmail as $key => $value) {
                        if( $value->id != $request->id ){
                            $listEmailConvert[] = $value->email;
                        }
                    }
                }

                $email_cc = [];
                if( $infoConfigMailSetting->cc_email == 1 ){
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
                $email_cc[] = User::where('id',Auth::user()->id)->value('email');
                $email_cc = array_unique($email_cc);
                $subject = $infoConfigMailSetting->mail_subject;
                $content_mail = array(
                                    'content' =>  $infoConfigMailSetting->mail_content,
                                    'fullname'=> $infoUser->fullname,
                                    'info_salary' => $request->info_salary,
                                    'info_management_allowance' => $info_management_allowance,
                                );

                \Mail::send('emails.notification_salary_success_manager', $content_mail, function($message) use ($listEmailConvert,$email_cc,$subject) {
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->cc($email_cc);
                    $message->to($listEmailConvert)->subject($subject);
                });
            }

            $res=array('Response'=>"Success","Message"=>"Bạn đã phê duyệt thành công" );
            echo json_encode($res);
        }
    }

    public function getScoreFaith(Request $request){
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
        // echo '<pre>';
        // print_r($depart);die;
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

        $data = $query->paginate(10);
        // dd($data);
        // $list_all_personnel = Personnel::select('personnel.id', 'personnel.fullname')->where('personnel.date_out', '=', NULL)->where('personnel.status', '=', 1)->get();
        $score_min = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->value('score_min');
        $score_min = $score_min ? $score_min : 0;
        return view('layouts.danhgia.diem-tin-nhiem.index', compact('data', 'list_all_personnel', 'score_min', 'department'));
    }

    public function updateExtendAjax(Request $request){
        if ($request->ajax()) {
            $personnel_id = $request->personnel_id;
            \DB::table('adhoc_salary_assessment')
                                ->where([
                                    ['year', '=', date('Y') ],
                                    ['personnel_id', '=', $request->personnel_id ],
                                    ['turns', '=', $request->turns ]
                                ])
                                ->whereNull( 'disable')
                               ->update(['time_send_mail' => date('Y-m-d', strtotime("+4 day"))]);
            $res=array('Response'=>"Success","Message"=>"Gia hạn đánh giá thành công" );
            echo json_encode($res);
        }
    }
}