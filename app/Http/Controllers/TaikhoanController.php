<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use File;
//use App\Mylibs\Myfunction;
use App\Mylibs\ResizeImage;
class TaikhoanController extends Controller
{
    //
     public function getTaikhoanInfo(){
        $user_id = Auth::user()->id;
        $data = User::findOrFail($user_id);
        return view('layouts.users.info_taikhoan',['data'=>$data]);
    }
     public function getTaikhoanEditPass(){
       // $data = User::with('roles')->where('id',$id)->first();
        $user_id = Auth::user()->id;
        $data = User::findOrFail($user_id);
        return view('layouts.users.editpass_taikhoan',['data'=>$data]);
    }

    public function postTaikhoanEditPass(Request $request){
        $user_id = Auth::user()->id;
        $rules = [
            'inputPassword'=>'min:6|max:32|regex:/^\S{1,}\z/',
            'inputPassword_confirmation'=>'required|same:inputPassword',
        ];
        $messages = [
            'inputPassword.regex'=>'Mật khẩu không được chứa dấu cách',
            'inputPassword.min'=> 'Mật khẩu phải có từ 6 ký tự trở lên',
            'inputPassword.max'=>'Mật khẩu phải nhỏ hơn 32 ký tự',
            'inputPassword_confirmation.required'=>'Mật khẩu nhập lại chưa đúng',
            'inputPassword_confirmation.same'=>'Mật khẩu nhập lại chưa đúng',

        ];
        $pass = $request->input('passwordCurrent');
        $user = User::find($user_id);
		$validator = Validator::make($request->all(),$rules,$messages);
		$validator->after(function($validator) use ($pass,$user)  {
		    if (!Hash::check($pass,$user->password)){
		        $validator->errors()->add('field', 'Mật khẩu hiện tại không chính xác !');
		    }
		});

		if ($validator->fails()) {
		    //validator false
		    return redirect()->back()->withErrors($validator)->withInput();
		}else{
             $user->password = bcrypt($request->input('inputPassword'));
             $user->save();
             return redirect()->route('getTaikhoanInfo')->with(['flash_message_succ' => 'Thay đổi mật khẩu thành công']);

		}
    }
    public function getTaikhoanEditInfo(){
        $user_id = Auth::user()->id;
    	$data = User::findOrFail($user_id);
        return view('layouts.users.editinfo_taikhoan',['data'=>$data]);
    }
    public function postTaikhoanEditInfo(Request $request){
        $user_id = Auth::user()->id;
    	$rules = [
            'inputName' => 'required|min:3|max:100',
            'fileImage' => 'image|mimes:jpeg,jpg,png|max:2048'
        ];
        $messages = [
            'inputName.required'=>'Bạn chưa nhập họ tên',
            'inputName.min' => 'Họ tên phải có từ 3 ký tự trở lên',
            'inputName.max'=>'Họ tên phải nhỏ hơn 100 ký tự',
            'fileImage.image'=>'File ảnh chưa đúng định dạng',
            'fileImage.mimes'=>'Ảnh không đúng định dạng ( Định dạng đúng:jpeg,jpg,png )',
            'fileImage.max'=>'Kích thước file phải nhỏ hơn 5 mb'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $user = User::findOrFail($user_id);
        if($request->hasFile('fileImage')){
        	if($request->file('fileImage')->isValid()){
        		if ($validator->fails()) {
		        	return redirect()->back()->withErrors($validator)->withInput();
		        }else{
                    if (!empty($user->avatar)) {
                        if (file_exists(public_path().'/uploads/users/'.$user->avatar)) {
                            File::delete(public_path().'/uploads/users/'.$user->avatar);
                        }
                    }
		        	$filename = time().'.'.$request->file('fileImage')->getClientOriginalName();
					$destinationPath = 'uploads/users/';
                    // Nếu move hay move_uploaded_file gọi trước thì hàm getimagesize sẽ báo lỗi đường dẫn do đường dẫn file đã thay đổi
                    //$request->file('fileImage')->move($destinationPath,$filename);
                   // Myfunction::resizeImage($request->file('fileImage')->getPathName(), '200', '200',$destinationPath,$filename );
                    $resize = new ResizeImage($request->file('fileImage')->getPathName());
                    $resize->resizeTo(150, 150, 'exact');
                    $resize->saveImage($destinationPath.$filename);

                    $user->name = trim(preg_replace('/\s\s+/', ' ',$request->inputName)); 
					$user->avatar = $filename;
					$user->save();
					return redirect()->route('getTaikhoanInfo')->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
		        }
        	}else{
        		return redirect()->back()->with(['flash_message_err'=>'Có lỗi xảy ra trong quá trình upload vui lòng thử lại']);
        	}
        	
        }else{
        	if ($validator->fails()) {
	        	return redirect()->back()->withErrors($validator)->withInput();
	        }else{
	        	$user->name = trim(preg_replace('/\s\s+/', ' ',$request->inputName)); 
				$user->save();
				return redirect()->route('getTaikhoanInfo')->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
	        }
        }
        
    }
}
