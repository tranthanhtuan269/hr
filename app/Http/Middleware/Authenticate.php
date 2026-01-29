<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\BatvHelper;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
          return redirect()->guest('login');
        }

        $route_arr = BatvHelper::listRolesByUser();
        $route_name = '';

        if ($request->segment(2) != '') {
            $route_name .= $request->segment(2);
        }

        if ($request->segment(3) != '') {
            $route_name .= '-'.$request->segment(3);
        }

        $user_id = \Auth::user()->id;

        if ( $user_id == 1 || in_array($route_name, $route_arr) || $request->path() == 'toh_hrm' || $request->is('insertAttendance') || $request->is('toh_hrm/taikhoan/*') || $request->is('toh_hrm/hoso/*') || $request->is('toh_hrm/api/*') || $request->is('toh_hrm/page-detail/*') || $request->is('toh_hrm/lam-them-gio/index') || $request->is('toh_hrm/lam-them-gio/quan-ly') || $request->is('toh_hrm/vay-von/index') || $request->path() == 'toh_hrm/luongthuong/cauhinhki'){
            view()->share('arr_route', $route_arr);
            return $next($request);
        }else{
           return redirect()->back()->with(['flash_message_err'=>'Bạn không có quyền truy cập chức năng này']);
        }   
      
    }
}
