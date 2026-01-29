<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Requests;
use App\Http\Requests\SaveMenuRequest;
use App\Http\Requests\UpdateSiteConfigRequest;
use App\Http\Requests\BackendLogin;
use App\Http\Requests\BackendSlideRequest;
use App\Http\Requests\BackendUpdateConfigHome;
use App\Http\Requests\BackendUpdateConfigSeo;
use App\Helpers\AschoolHelper;
use App\SiteConfig;
use App\Page;
use App\PostCategory;
use App\Post;
use App\Author;
use App\ArticleCategory;
use Auth;
use Validator;
use Cache;

class AdminController extends Controller {
    public function getLoginAdmin(){
    	if(Auth::check()){
	        return redirect('admincp');
	    }else{
        	return view('backend.login.index');
        }
    }

    public function loginAdmin( BackendLogin $request ){
        $email = trim($request->email);
        $password = trim($request->password);

        if (Auth::attempt(['email' => $email, 'password' => $password, 'type' => 0], $request->remember)) {
            session_start();
            $_SESSION['admin_upload_file'] = 1;
            return response()->json(['message' => 'Ok', 'status' => 200]);
        } else {
            return response()->json(['message' => 'The email or password is incorrect', 'status' => 404]);
        }
    }

    public function getLogoutAdmin(){
        Auth::logout();
        session_start();
        unset($_SESSION['admin_upload_file']);
        return redirect()->route('login-admin');
    }

    public function getAdminCp(){
        return view('backend.dashboard');
    }

    public function saveMenu(Request $request){
        $item = SiteConfig::where('key', '=', $request->key)->first();
        $item->value = $request->value;
        if($item->save()){
            Cache::forget('site_config');
            return \Response::json(array('status' => '200', 'message' => 'Cập nhật thông tin thành công!'));
        }else{
            return \Response::json(array('status' => '403', 'message' => 'Đã có lỗi xảy ra trong quá trình lưu dữ liệu!'));
        }
    }

    public function slide() {
        $listSlides = SiteConfig::where('key', '=', 'slide')->first();
        $listSlides = json_decode($listSlides->value, false);
        // dd($listSlides);die;
        return view('backend.config.slide', compact('listSlides'));
    }

    public function addSlide(BackendSlideRequest $request){
        Cache::forget('site_config');
        $slide = SiteConfig::where('key', '=', 'slide')->value('value');
        $image = $request->image;
        $image = explode("/filemanager/data-images/", $image);

        if ($slide != '[]') {
            $arr_list_slide = json_decode($slide, true);
            array_unshift($arr_list_slide, ['image' => $image[1], 'link' => $request->link]);
            SiteConfig::updateRecord('slide', json_encode($arr_list_slide));
        } else {
            SiteConfig::updateRecord('slide', json_encode([['image' => $image[1], 'link' => $request->link]]));
        }

        return response()->json(['message' => 'Lưu thông tin thành công!', 'status' => 200]);
    }

    public function editSlide(BackendSlideRequest $request){
        Cache::forget('site_config');
        $slide = SiteConfig::where('key', '=', 'slide')->value('value');
        $image = $request->image;
        $image = explode("/filemanager/data-images/", $image);
        $image = trim($image[1]);

        if ($slide != '') {
            $arr_list_slide = json_decode($slide, true);
            $arr_list_slide [$request->id] = ['image' => $image, 'link' => $request->link];
            SiteConfig::updateRecord('slide', 0, json_encode($arr_list_slide));
        }

        return response()->json(['message' => 'Lưu thông tin thành công!', 'status' => 200]);
    }

    public function updateSlide(UpdateSiteConfigRequest $request) {
        Cache::forget('site_config');
        SiteConfig::where('key', '=', 'logo')->update(['value' => $request->logo]);
        SiteConfig::where('key', '=', 'email')->update(['value' => $request->email]);
        SiteConfig::where('key', '=', 'address')->update(['value' => $request->address]);
        SiteConfig::where('key', '=', 'phone')->update(['value' => $request->phone]);
        SiteConfig::where('key', '=', 'phone_2')->update(['value' => $request->phone_2]);
        SiteConfig::where('key', '=', 'facebook')->update(['value' => $request->facebook]);
        SiteConfig::where('key', '=', 'youtube')->update(['value' => $request->youtube]);
        SiteConfig::where('key', '=', 'instagram')->update(['value' => $request->instagram]);
        SiteConfig::where('key', '=', 'lat')->update(['value' => $request->lat]);
        SiteConfig::where('key', '=', 'lng')->update(['value' => $request->lng]);
        return back()->with(['flash_message_succ' => 'Cập nhật thông tin thành công!']);
    }

    public function setupMenu() {
        $dataCate = Menu::where('id_menu', 1)->get();
        $dataCate = array($dataCate);
        $listPages = Page::getAll();
        $listPostCategories = PostCategory::getAll();
        return view('backend.config.menu', compact('listPages', 'listPostCategories', 'dataCate'));
    }

    public function setupMenuAjax(Request $request){
        Menu::where('id_menu', $request->id_menu)->delete();
        $data =  json_decode(json_encode(json_decode($request->list)), true);
        // $data = array_reverse($data);
        Menu::insert($data);
        Cache::forget('menu_main');
        Cache::forget('menu_footer_1');
        Cache::forget('menu_footer_2');
        return \Response::json(array('status' => '200', 'Message' => 'Cập nhật thông tin thành công!'));
    }

    public function showMenuAjax(Request $request){
        $dataCate = Menu::where('id_menu', $request->id)->orderBy('id', 'asc')->get();
        $myfunc =  new AschoolHelper();
        return $myfunc->listCatMenu($dataCate,0,'',0);
    }

    public function updateConfigHome(BackendUpdateConfigHome $request) {
        Cache::forget('site_config');
        $logo = $request->logo;

        if ($logo != '') {
            $logo = explode("/filemanager/data-images/", $logo);
            SiteConfig::updateRecord('logo', $logo[1]);
        }


        // echo $request->facebook;die;
        SiteConfig::updateRecord('slogan', $request->slogan);
        SiteConfig::updateRecord('phone_1', $request->phone_1);
        SiteConfig::updateRecord('phone_2', $request->phone_2);
        SiteConfig::updateRecord('facebook', $request->facebook);
        SiteConfig::updateRecord('youtube', $request->youtube);
        SiteConfig::updateRecord('instagram', $request->instagram);
        SiteConfig::updateRecord('code_google_anaylytics', $request->code_google_anaylytics);
        SiteConfig::updateRecord('code_web_master_tools', $request->code_web_master_tools);

        return response()->json(['message' => 'Lưu thông tin thành công!', 'status' => 200]);
    }

    public function updateConfigSeo(BackendUpdateConfigSeo $request) {
        SiteConfig::updateRecord('keywords', $request->keywords);
        SiteConfig::updateRecord('seo_title', $request->seo_title);
        SiteConfig::updateRecord('seo_description', $request->seo_description);

        return response()->json(['message' => 'Lưu thông tin thành công!', 'status' => 200]);
    }

    public function editInfo() {
        $data         = SiteConfig::pluck('value', 'key');
        return view('backend.config.general', compact('data'));
    }

    public function searchLink( Request $request ){
        $results  = [];
        $keyword = $request->keyword;
        $slug_keyword = \Str::slug($keyword);

        if ($request->type_search == 1) {
            $results = Post::leftJoin('authors', 'authors.id', '=', 'cmm_posts.author_id')
                            ->where(function ($query) use($slug_keyword) {
                                $query->where('cmm_posts.slug', 'like', '%' . $slug_keyword . '%')
                                      ->orWhere('authors.slug', 'like', '%' . $slug_keyword . '%');
                            })
                            ->where('cmm_posts.public_date', '<=', date('Y-m-d H:i:s'))
                            ->select('cmm_posts.id', 'cmm_posts.title', 'cmm_posts.slug')
                            ->get();

            if (count($results) == 0) {
                $results = Post::where('cmm_posts.public_date', '<=', date('Y-m-d H:i:s'))
                                ->whereRaw("MATCH (cmm_posts.title) AGAINST ('{$keyword}' IN BOOLEAN MODE)")
                                ->select('cmm_posts.id', 'cmm_posts.title', 'cmm_posts.slug')
                                ->get();
            }
        } elseif ($request->type_search == 2) {
            $results = PostCategory::where('slug', 'like', '%' . $slug_keyword . '%')->select('id', 'title', 'slug')->get();
        } else {
            $results = Author::where('slug', 'like', '%' . $slug_keyword . '%')->select('id', 'name', 'slug')->get();

            if (count($results) == 0) {
                $results = Author::whereRaw("MATCH (name) AGAINST ('{$keyword}' IN BOOLEAN MODE)")->select('id', 'name', 'slug')->get();
            }
        }

        return response()->json(['status' => 200, 'data' => $results]);
    }
}
