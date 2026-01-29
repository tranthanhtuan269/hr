<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class HistoryPayLoanCapital extends Model
{
    // stauts: trạng thái đã thanh toán chưa
    // type: trạng thái đã  báo thánh toán trả bây giờ chưa
    protected $table = "history_pay_loan_capital";

}
