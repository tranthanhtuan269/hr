<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Privilegs;
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
       /*  $role_id = Auth::user()->role_id;
        $data =  Roles::where('id', $role_id)->first();
        $arr = explode(',', $data->privileges_id);
        $privileg = new Privilegs();
        $data2 = $privileg::find($arr);
        $route_arr = array();
        foreach ($data2 as $key => $value) {
           $route_arr[] = trim($value->router);
        }
        view()->share('ddd', $route_arr);*/
        /*view()->composer('dashboard::layouts.*', function ($view) {
            $view->with('user', auth()->user());
        });*/
        //view()->composer run before render view 
        view()->composer('layouts.master', function ($view) {
            $view->with('user', auth()->user());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
