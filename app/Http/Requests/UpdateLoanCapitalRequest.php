<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateLoanCapitalRequest extends FormRequest
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
            'score'                               => 'required|numeric|min:0|max:1000',
            'max_money'                           => 'required|numeric|min:0|max:10000000000',
            'preferential_interest_rate'          => 'required|numeric|min:0|max:100',
            'month_time'                          => 'required|numeric|min:0|max:999',
        ];
    }

    public function messages()
    {
        return [
            'score.required'                       => 'Điểm tín nhiệm không được để trống.',
            'score.max'                       => 'Điểm tín nhiệm không được quá 1000.',
            'max_money.required'                   => 'Số tiền vay tối đa không được để trống.',
            'preferential_interest_rate.required'  => 'Lãi suất ưu đãi không được để trống.',
            'month_time.required'           => 'Thời gian ưu đãi không được để trống.',
            'max_money.numeric'                    => 'Số tiền vay tối đa không hợp lệ.',
            'preferential_interest_rate.numeric'   => 'Lãi suất ưu đãi không hợp lệ.',
        ];
    }
}
