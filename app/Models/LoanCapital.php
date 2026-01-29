<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class LoanCapital extends Model
{

    protected $table = "loan_capital";
    public $timestamps = false;   

    public function detail(){
    	return $this->hasMany('App\Models\OverTimeDetail')->orderBy('day_id','asc');
    }

    public function detail_approved(){
    	return $this->hasMany('App\Models\OverTimeDetail')->where('score', 1);
    }
}
