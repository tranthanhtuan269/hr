<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Auth,Validator;



class PagesController extends Controller
{

    public function getPageList(){
        $data = Page::get();
        return view('layouts.pages.index',['data'=>$data]);
    }

    public function getPageAdd(){
        return view('layouts.pages.add');
    }

    public function postPageAdd(Request $request){
        try{
            $rules = [
                'title' =>'required',
            ];
            $messages = [
                'title.required' => 'Tiêu đề không được để trống',
                'content.required' => 'Nội dung không được để trống',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $item = new Page;
                $item->title =  $request->title;
                $item->content =  $request->content;
                $item->slug =  str_slug($request->title,'-');
                $item->created_by =  Auth::user()->id;
                $item->created_at = date('Y-m-d H:i:s');
                $item->save();
                return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }


    public function getPageEdit($id){
        $data = Page::where('id',$id)->first();
        return view('layouts.pages.edit',['data'=>$data]);
    }

    public function putPageEdit(Request $request){
        try{    
            $rules = [
                'title' =>'required',
            ];
            $messages = [
                'title.required' => 'Tiêu đề không được để trống',
                'content.required' => 'Nội dung không được để trống',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $item = Page::find($request->id);
                $item->title =  $request->title;
                $item->content =  $request->content;
                $item->slug =  str_slug($request->title,'-');
                $item->updated_by =  Auth::user()->id;
                $item->updated_at = date('Y-m-d H:i:s');
                $item->save();
                return back()->with(['flash_message_succ' => 'Sửa thông tin thành công']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }

    public function deletePageDel($id){
        $item = Page::find($id);
        $item->delete($id);
        return back()->with(['flash_message_succ' =>'Xóa thông tin thành công']);
    }

    public function getCategories($cat){
        $data = Page::where('slug',$cat)->first();
        return view('layouts.pages.detail',['data'=>$data]);
    }

}
