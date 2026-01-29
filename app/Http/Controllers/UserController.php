<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\Roles;
use App\Models\Personnel;
use App\Models\Role_User;
use App\Mylibs\Myfunction;
use App\Http\Requests\UpdateUserRequest;
class UserController extends Controller
{
    public function forgotAjax(Request $request){
        $user = User::where('email',$request->email)->first();
        $reset_code = substr(md5(microtime()),rand(0,26),10);
        if ($user) {
            $user->reset_code = $reset_code;
            $user->reset_code_time = date('Y-m-d H:i:s');
            $user->save();
            $content_mail = [
                'reset_code'    => $reset_code,
                'link'          => url('resetpass').'?email='.$request->email.'&reset_code='.$reset_code,
            ];
            $email = $request->email;

            $content_mail = array(
                                'reset_code'    => $reset_code,
                                'link'          => url('resetpass').'?email='.$request->email.'&reset_code='.$reset_code,
                            );
            \Mail::send('emails.notification_forgot_pass', $content_mail,  function ($message) use ($email) {
                $message->from('nhansu@tohsoft.com', 'TOH');
                $message->to($email)->subject('[HR] Reset code for change password');
            });

            return response()->json(['status'=>200]);
        }
        else{
            return response()->json(['message'=>'No email']);
        }
    }

    public function resetpass(){
        Auth::logout();
        return view('layouts.users.reset-pass');
    }

    public function resetpassAjax(UpdateUserRequest $request){
        $email = trim($request->email);
        $reset_code = trim($request->code);
        $password = trim($request->password);
        $confirmpassword = trim($request->confirmpassword);
        $reset_code_time = User::where([
                                ['reset_code', $reset_code],
                                ['email', $email],
                        ])->value('reset_code_time');
        if($reset_code_time){
            $timeFirst  = strtotime($reset_code_time);
            $timeSecond = strtotime(date('Y-m-d H:i:s'));
            $differenceInSeconds = $timeSecond - $timeFirst;
            if($differenceInSeconds <= 1800){
                $user = User::where([
                    ['reset_code', $reset_code],
                    ['email', $email]
                ])->first();
                $user->password = bcrypt(trim($password));
                $user->save();
                return response()->json(['message'=>'success', 'status'=>200]);
            }
            else{
                return response()->json(['message'=>'fail', 'status'=>201]);
            }
        }
        return response()->json(['message'=>'fail', 'status'=>201]);
    }

    public function getUserList(Request $request){
      
        $data = User::getUserList($request);
    	return view('layouts.users.index',['data'=>$data]);
    }

    public function getUserAdd(){
        $depart = Personnel::listDepartment();
        $myfunc =  new Myfunction();
        $select = 0;
        $department = $myfunc->callProcessSelect($depart,0,'',$select);

        $data = User::with('roles');//->toArray() ;
        $data_roles = Roles::select('id','roles_name')->get();
        /*$data = User::selectRaw('users.id,users.name,users.email, roles.roles_name')
              ->leftJoin('roles','users.role_id','=','roles.id')->get()->paginate(2);*/
        return view('layouts.users.add',['data'=>$data,'data_roles'=>$data_roles, 'department' => $department]);
    }

    public function postUserAdd(Request $request){
        // echo $request->department_id;die;
        $this->validate($request,[
            'inputHoten' =>'required|min:3',
            'inputEmail'=>'required|email|unique:users,email',
            'inputPassword'=>'required|min:6|max:32|confirmed',
            'inputPassword_confirmation'=>'required|min:6|max:32',
            'roles_id'=>'required',
            'department_id' => 'required',
        ],[
            'inputHoten.required'=>'Bạn chưa nhập tên người dùng',
            'inputHoten.min'=>'Họ tên phải có từ 3 ký tự trở lên',
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
            'roles_id.required'=>'Bạn chưa chọn Role',
            'department_id.required'=>'Vui lòng chọn đơn vị công tác',

        ]);  

        $user = new User();
        $user->name = trim(preg_replace('/\s\s+/', ' ',$request->inputHoten)); 
        $user->email = $request->inputEmail; 
        $user->password = bcrypt(trim($request->inputPassword)); 
        $user->role_id = 1;
        $user->status = 1;
        $user->created_by = Auth::user()->id;
        $user->created_at = date('Y-m-d');
        $user->status = 1; 
        $user->save();
        $id =  \DB::getPdo()->lastInsertId();  
        $arr = [
            'id'      => $id,
            'email' =>  $request->inputEmail,
            'fullname'=>  $request->inputHoten,
            'status'=>  1,
            'department_id' => $request->department_id,
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d'),
        ];
        Personnel::insertInfo($arr);
  
        foreach ($request->roles_id as $key => $value) {
            $arr_roles[] = [
                'role_id'  => $value,
                'user_id'  => $id,
            ];
        }

        Role_User::insert($arr_roles);

        return back()->with(['flash_message_succ' => 'Thêm người dùng thành công']);
    }
    public function getUserEdit($id){
        if( is_numeric($id) ){
            $check =  User::check_User($id);
            if( count( $check )>0 ){
                $data = User::detailRoleUser($id);
                $roles_user = explode(",",$data->roles_id);
                $data_roles = Roles::select('id','roles_name')->get();
                return view('layouts.users.edit', compact('data', 'roles_user', 'data_roles'));
            }else{
                return redirect()->route('getUserList')->with(['flash_message_err' => 'Không tìm thấy thông tin']);
            }

        }else{
            return abort(503);
        }
    }

    public function postUserEdit(Request $request,$id){
        $this->validate($request,[
            'inputHoten' =>'required|min:3',
            'inputEmail'=>'required|email|unique:users,email,'.$id,
            'inputPassword'=>'min:6|max:32|confirmed',
            'inputPassword_confirmation'=>'min:6|max:32',
            'roles_id'=>'required'
        ],[
            'inputHoten.required'=>'Bạn chưa nhập tên người dùng',
            'inputHoten.min'=>'Họ tên phải có từ 3 ký tự trở lên',
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
            'roles_id.required'=>'Bạn chưa chọn Role',

        ]);  

        $user = User::find($id);
        $user->name = trim(preg_replace('/\s\s+/', ' ',$request->inputHoten)); 
        $user->email = $request->inputEmail; 
        if ($request->inputPassword != '') {
           $user->password = bcrypt(trim($request->inputPassword)); 
        }
        $user->role_id = 1; 
        $user->updated_by = Auth::user()->id;
        $user->updated_at = date('Y-m-d');
        $user->save();
        $arr = [
            'email' =>  $request->inputEmail,
        ];
        Personnel::updateInfo($arr,$id);

        $user_id =  $user->id; 
        Role_User::where('user_id',$user_id)->delete();

        foreach ($request->roles_id as $key => $value) {
            $arr_roles[] = [
                'role_id'  => $value,
                'user_id'  => $user_id,
            ];
        }
        
        Role_User::insert($arr_roles);

        return back()->with(['flash_message_succ' => 'Sửa User thành công']);


    }
    public function getUserDel($id){
        if ($id != 1) {
            $arr = [ 
                    'status'=>0
                ];
            User::updateUser($arr,$id);
            Personnel::updateInfo($arr,$id);
            return back()->with(['flash_message_succ' =>'Xóa tài khoản thành công']);
        }else{
            return back()->with(['flash_message_err'=>'Bạn không thể xóa tài khoản Admin']);
        }
    }
}
