<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\News;
use App\Models\GroupPageHome;

class HomeController extends Controller
{
    public function demo(Request $request){

    }

    public function getIndex(Request $request){
        $dataGroupPageHome = GroupPageHome::orderBy('position', 'asc')->get();
        $route_arr = $request->route_arr;
        $listHilight = News::listNewsbyCondition(1);
        $listOther = News::listNewsbyCondition(0);
        return view('layouts.dashboard',compact('dataGroupPageHome', 'listHilight', 'listOther', 'route_arr'));
    }
}
