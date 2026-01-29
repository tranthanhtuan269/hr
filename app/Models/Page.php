<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BatvHelper;
use App\Config;
class Page extends Model{
    
    protected $table = "page";
    protected $fillable = [
                            'title',
                            'content',
                            'slug',
                        ];

    public static function checkBarCode($barcode){
        return BarCode::where('barcode',$barcode)->count();
    }

}
