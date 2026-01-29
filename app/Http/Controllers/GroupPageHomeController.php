<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Http\Requests;
use App\Models\Roles; 
use App\Models\GroupPageHome;

class GroupPageHomeController extends Controller
{

    public function settingPageHome(Request $request){
        $data = GroupPageHome::orderBy('position', 'asc')->get();
        return view('layouts.chucnangkhac.cauhinhtrangchu.index', compact('data'));
    }

    public function addPageHome(Request $request){
        return view('layouts.chucnangkhac.cauhinhtrangchu.add');
    }

    public function postPageHomeAdd( Request $request ){
        $rules = [
            'title' =>'required|unique:group_page_home,title',
            'content' =>'required',
        ];
        $messages = [
            'title.required' => 'Tiêu đề tin không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $filename = '';
            if($request->hasFile('icon')){
                if($request->file('icon')->isValid()){
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }else{
                        $filename = time().'.'.$request->file('icon')->getClientOriginalName();
                        $destinationPath = public_path('/uploads/icon-cat-home');
                        $request->file('icon')->move($destinationPath,$filename);
                    }
                }else{
                    return redirect()->back()->with(['flash_message_err'=>'An error occurred during upload process please try again']);
                }
            }

             $arr = [
                'title' =>  trim($request->title),
                'content' =>  trim($request->content),
                'icon' =>  $filename,
                'position' => GroupPageHome::count() + 1,
                'background_color' => $request->background_color,
            ];
            GroupPageHome::insert($arr); 
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function changePositionAjax(Request $request){
        if ( $request->dataSort ) {
            foreach ($request->dataSort as $key => $value) {
                GroupPageHome::where('id', $value['id'])->update(['position' => $value['position']]);
            }
            $res = array('Response'=>"Succses","Message"=> "Thay đổi vị trí thành công");  
        }
        
        echo json_encode($res);
    }

    public function editPageHome($id){
        $data = GroupPageHome::find($id);
        return view('layouts.chucnangkhac.cauhinhtrangchu.edit',['data'=>$data]);
    }    

    public function postPageHomeEdit(Request $request,$id){
        $rules = [
            'title' =>'required|unique:group_page_home,title,'.$id,
            'content' =>'required',
        ];
        $messages = [
            'title.required' => 'Tiêu đề tin không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $item = GroupPageHome::find($id);
            $filename = $item->icon;
            if($request->hasFile('icon')){
                if($request->file('icon')->isValid()){
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }else{
                        $filename = time().'.'.$request->file('icon')->getClientOriginalName();
                        $destinationPath = public_path('/uploads/icon-cat-home');

                        if (!empty($filename)) {
                            \File::delete($destinationPath.$filename);
                        }

                        $request->file('icon')->move($destinationPath,$filename);
                    }
                }else{
                    return redirect()->back()->with(['flash_message_err'=>'An error occurred during upload process please try again']);
                }
            }

             $arr = [
                'title' =>  trim($request->title),
                'content' =>  trim($request->content),
                'icon' =>  $filename,
                'background_color' => $request->background_color,
            ];
            GroupPageHome::where('id', $id)->update($arr); 
            return back()->with(['flash_message_succ' => 'Sửa thông tin thành công']);
        }
    }

    public function delPageHomeAjax(Request $request){
        $item = GroupPageHome::find($request->idPageHome);
        $item->delete();
        $res = array('Response'=>"Succses","Message"=> "Xóa thông tin thành công");  
        echo json_encode($res);
    }  
    
}
