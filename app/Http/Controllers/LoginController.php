<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use Auth;
use Illuminate\Support\MessageBag;
class LoginController extends Controller
{
    //
    public function __construct(){
    	
    }
    public function getLogin(){
	    //if(Auth::check() && Auth::user()->role_id == 1){
    	if(Auth::check()){
	        return redirect('toh_hrm');
	    }else{
            return view('layouts.login');
        }
    	
    }

    public function getDemo(){
        echo 1;die;
        // $arr = ['status'=>0];
        // \DB::table('personnel')
        //     ->where('id', 1)
        //     ->update($arr);
    }

    public function postLogin(Request $request){
    	//dd($request->all());
    	$rules = [
    		'email'=>'required|email',
    		'password'=>'required|min:6'
    	];

    	$messages = [
    		'email.required'=> 'Bạn chưa nhập email',
    		'email.email'=>'Email không đúng định dạng',
    		'password.required'=> 'Bạn chưa nhập mật khẩu',
    		'password.min'=>'Mật khẩu phải có ít nhất 6 ký tự',

    	];

    	$validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()) {
        	// Validator fail
        	//return redirect()->back()->withErrors($validator);
        	return redirect()->back()->withErrors($validator)->withInput();
        }else{
        	$email = $request->input('email');
        	$password = $request->input('password');

        	if(Auth::attempt(['email'=>$email,'password'=>$password])){
        		//return view('dashboard',['user'=>$data]);
                return redirect()->intended('toh_hrm');

        	}else{
        		$errors = new MessageBag(['errorLogin'=>'Email hoặc mật khẩu không đúng']);
        		return redirect()->back()->withErrors($errors);
       		}
        }
    }
    public function getLogout(){
        Auth::logout();
        return redirect()->route('login');
    }

}
