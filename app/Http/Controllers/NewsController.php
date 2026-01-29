<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Http\Requests;
use App\Models\News;
use App\Models\Roles;
use App\Models\EmailConfig;
use Mail; 

class NewsController extends Controller
{

    public function getNewsList(Request $request){
        $data = News::getNewsList($request);
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.tintuc.index',['data'=>$data]);
    }

    public function getNewsAdd(){
        return view('layouts.tintuc.add');
    }

    public function postNewsAdd( Request $request ){
        $rules = [
            'title' =>'required',
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
            if( $request->email_notification ==1 ){
                $infoConfigMail = EmailConfig::getInfoEmailConfig($type = 0);
                $email = explode(",",$infoConfigMail->mail_to);
                $listEmail = EmailConfig::getListEmailbyidPersonnel($email);

                $listEmailConvert = array();
                if( $listEmail ){
                    foreach ($listEmail as $key => $value) {
                        $listEmailConvert[] = $value->email;
                    }
                }
                $content_mail = array(
                                    'title'=>$request->title,
                                    'content' =>  $request->content,
                                );
                \Mail::send('emails.news', $content_mail, function($message) use ($listEmailConvert) {
                    $infoConfigMail = EmailConfig::getInfoEmailConfig($type = 0);
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->to($listEmailConvert)->subject($infoConfigMail->mail_subject);
                });
            } 

             $arr = [
                'title' =>  trim($request->title),
                'content' =>  trim($request->content),
                'is_pinned' =>  $request->is_pinned,
                'email_notification' =>  $request->email_notification,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1,
            ];
            News::insertNews($arr); 
            return back()->with(['flash_message_succ' => 'Thêm thông tin thành công']);
        }
    }

    public function getNewsEdit($id){
        $data = News::infoNews($id);
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.tintuc.edit',['data'=>$data]);
    }    

    public function  postNewsEdit(Request $request,$id){
        $rules = [
            'title' =>'required',
            'content' =>'required',
        ];
        $messages = [
            'title.required' => 'Tiêu đề tin không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            //echo 1;die;
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if( $request->email_notification ==1 ){
                $infoConfigMail = EmailConfig::getInfoEmailConfig($type=0);
                $email = explode(",",$infoConfigMail->mail_to);
                $listEmail = EmailConfig::getListEmailbyidPersonnel($email);

                $listEmailConvert = array();
                if( $listEmail ){
                    foreach ($listEmail as $key => $value) {
                        $listEmailConvert[] = $value->email;
                    }
                }
                $content_mail = array(
                                'title' =>  trim($request->title),
                                'content' =>  trim($request->content),
                                );

                \Mail::send('emails.news', $content_mail, function($message) use ($listEmailConvert) {
                    $infoConfigMail = EmailConfig::getInfoEmailConfig($type=0);
                    $message->from('nhansu@tohsoft.com', 'TOH');
                    $message->to($listEmailConvert)->subject($infoConfigMail->mail_subject);
                });
            }
             $arr = [
                'title' =>  trim($request->title),
                'content' =>  trim($request->content),
                'is_pinned' =>  $request->is_pinned,
                'email_notification' =>  $request->email_notification,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            News::updateNews($arr,$id);  
            return redirect()->route('getNewsEdit',['id'=>$id])->with(['flash_message_succ' => 'Cập nhật thông tin thành công']);
        }
    }

    public function  getNewsDel($id){
        $arr = [
            'status' =>  0,
        ];
        News::updateNews($arr,$id); 
        if($arr){
            return back()->with(['flash_message_succ' =>'Xóa thông tin thành công']);
        }
    }

    public function getNewsListHighlight(){
        $data = News::listNewsClient(1);
        // echo "<pre>";
        // print_r($data);die;
        return view('layouts.tintuc.listNewsHighlight',['data'=>$data]);
    }   

    public function getNewsListOther(){
        $data = News::listNewsClient(0);
        return view('layouts.tintuc.listNewsOther',['data'=>$data]);
    }   
}
