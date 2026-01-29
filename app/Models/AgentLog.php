<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class AgentLog extends Model
{
    
    protected $table = "agent_log";
    public $timestamps = false;

    public static function infobyTimeCurrent(){
        return  AgentLog::selectRaw("UserID,MIN(Timestamp) as minDate")->whereDate('Timestamp', '=', date('Y-m-d'))->groupBy('UserID')->get();
    }
    
    public static function infobyTime($timestamp)
    {
        // Lấy ngày từ timestamp
        $date = date('Y-m-d', $timestamp);

        $start = $date . ' 00:00:00';
        $end   = $date . ' 23:59:59';

        return AgentLog::selectRaw('UserID, MIN(Timestamp) as minDate')
            ->whereBetween('Timestamp', [$start, $end])
            ->groupBy('UserID')
            ->get();
    }

}
