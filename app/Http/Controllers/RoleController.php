<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Roles;
use App\Models\Privilegs;
use Validator;
use App\Models\Personnel;
use App\Mylibs\Myfunction;
use App\Helpers\BatvHelper;
use Illuminate\Support\Collection;

class RoleController extends Controller
{
    //
	/*public function __construct(){
		$this->middleware('auth');
	}*/
	public function getRoleList(Request $request){
		$listRoles = Roles::all()->toArray();
        // echo "<pre>";
        // print_r($listRoles);die;
        $results = Roles::listRoles( $request );
        $data = json_decode(json_encode($results), true);
        $arr = array();
        $data2 = Privilegs::all()->toArray();
        foreach ($data2 as $key => $value) {
            $id = $value['id'];
            $arr[$id] = $value['router'];
        }
        $arr2 =array();
        foreach ($data as $key => $value) {
            $arr_id = explode(',', $value['privileges_id']);
            $arr2[] = array_only($arr,$arr_id);
            $data[$key]['privileges_id'] = $arr2[$key];
        }
        $data = BatvHelper::PagingDataSpecial($data);
        return view('layouts.roles.index',['roles'=>$data,'arr_privilegs'=>$data2,'listRoles'=>$listRoles]);
	}

    public function getRoleAdd(){
        $data = Roles::all()->toArray();
        $arr = array();
        $data2 = Privilegs::all()->toArray();
        foreach ($data2 as $key => $value) {
            $id = $value['id'];
            $arr[$id] = $value['router'];
        }
        $arr2 =array();
        foreach ($data as $key => $value) {
            $arr_id = explode(',', $value['privileges_id']);
            $arr2[] = array_only($arr,$arr_id);
            $data[$key]['privileges_id'] = $arr2[$key];
        }
        return view('layouts.roles.add',['roles'=>$data,'arr_privilegs'=>$data2]);
    }

	public function postRoleAdd(Request $request){
		$rules = [
    		'role_name'=>'required|unique:roles,roles_name|min:3',
    	];

    	$messages = [
    		'role_name.required'=> 'Role name la truong bat buoc',
            'roles_name.min'=> 'Tên phải lớn hơn 3 ký tự',
            'role_name.unique' => 'Role name đã có trong cơ sở dữ liệu'
    	];

    	$validator = Validator::make($request->all(),$rules,$messages);
    	if ($validator->fails()) {
        	// Validator fail
        	//return redirect()->back()->withErrors($validator);
        	return redirect()->back()->withErrors($validator)->withInput();
        }else{
        	$role_name = $request->input('role_name');
            $str="";
             if (isset($request->check_id)) {
                $str = implode(',', $request->check_id);
            }
            $roles = new Roles();
            $roles->roles_name = $role_name;
            $roles->privileges_id = $str;
            $roles->save();
            return back()->with(['flash_message_succ' => 'Thêm Role thành Công']);
        	
        }
	}
	public function getRoleEdit($id){
		$list_privilegs_id = Roles::select('roles_name','privileges_id')->where('id',$id)->first();
        $arr_privilegs_id = explode(',', $list_privilegs_id->privileges_id);
		$data = Privilegs::select('id','privilege_name','parent_id')->get()->toArray();
		return view('layouts.roles.edit',['data'=>$data,'list_privilegs'=> $arr_privilegs_id, 'role_name' => $list_privilegs_id->roles_name ]);
	}

	public function postRoleEdit(Request $request,$id){
        $this->validate($request,[
            'roles_name' =>'required|min:3',
        ],[
            'roles_name.required'=>'Bạn chưa nhập tên Role',
            'roles_name.min'=> 'Tên phải lớn hơn 3 ký tự',
        ]);  
        if ($id != 1) {
            $roles = Roles::findOrFail($id);
            $str="";
            if (isset($request->check_id)) {
                $str = implode(',', $request->check_id);
            }
            $roles->roles_name = $request->roles_name;
            $roles->privileges_id = $str;
            $roles->save();
            return redirect()->route('getRoleList',['id'=>$id])->with(['flash_message_succ' => 'Sửa Role thành công']);
        }else{
            return redirect()->route('getRoleList',['id'=>$id])->with(['flash_message_err' => 'Bạn ko có quyền sửa role Admin']);
        } 

	}

    public function getRoleDel($id){
        if ($id != 1) {
            $role = Roles::find($id);
            $role->delete($id);
            return back()->with(['flash_message_succ' =>'Xóa Role thành công']);
        }else{
            return back()->with(['flash_message_err'=>'Bạn ko thể xóa vai trò Admin']);
        }
       
    }

}
