<?php

namespace App\Models;
use DB;
use DateTime;

class News 
{

  public static function insertNews($arr){
      return DB::table('news')->insert($arr);
  }         

  public static function getNewsList($request=''){
     return DB::table('news')
          ->select('id','title')
          ->where('status','=',1)
          ->where(function ($query) use ($request) {
             if (!empty($request->title)) {
                        $query->where('title','like','%'.$request->title.'%');
             }
          })
          ->paginate(10);
  }

  public static function infoNews($id){
      return DB::table('news')->where('id','=',$id)->first();
  }

  public static function updateNews($arr,$id){
      return DB::table('news')
            ->where('id', $id)
            ->update($arr);
  }

  public static function listNewsbyCondition($param){
     return DB::table('news')
          ->select('id','title','content')
          ->where('status','=',1)
          ->where('is_pinned','=',$param)
          ->orderBy('created_at', 'DESC')
          ->limit(5)
          ->get();
  }

  public static function listNewsClient($param){
     return DB::table('news')
          ->select('id','title','content')
          ->where('status','=',1)
          ->where('is_pinned','=',$param)
          ->orderBy('created_at', 'DESC')
          ->paginate(10);
  }

}
