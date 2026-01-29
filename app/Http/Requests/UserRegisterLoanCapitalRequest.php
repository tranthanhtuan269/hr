<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRegisterLoanCapitalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'money'               => 'check_registerloan_capital|required|numeric|min:0|max:10000000000',
            'month_time'          => 'required|numeric|min:0|max:999',
        ];
    }

    public function messages()
    {
        return [
            'money.check_score_register_loan_capital'                => 'Điểm tín nhiệm chưa đủ tiêu chuẩn để đăng ký!',
            'money.required'                => 'Số tiền vay không được để trống.',
            'month_time.required'           => 'Thời gian vay không được để trống.',
        ];
    }
}
