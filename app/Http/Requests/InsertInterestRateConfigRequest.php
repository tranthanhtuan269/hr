<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class InsertInterestRateConfigRequest extends FormRequest
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
            'interest_rate'                               => 'required|numeric|min:0',
            'preferential_interest_rate'          => 'required|numeric|min:0',
            'count_month_preferential'                               => 'required|numeric|min:0',
            'deferred_interest'                               => 'required|numeric|min:0',
            'score_min'                               => 'required|numeric|min:0|max:1000',
            'apply_from'                   => 'required|date_format:"d/m/Y"|validate_time',
            'apply_to'                   => 'required|date_format:"d/m/Y"|after:apply_from',
            'start_month_pay'                               => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'interest_rate.required'                       => 'Lãi suất không được để trống.',
            'preferential_interest_rate.required'                       => 'Lãi suất ưu đãi được để trống.',
            'count_month_preferential.required'                       => 'Thời gian ưu đãi không được để trống.',
            'deferred_interest.required'                       => 'Lãi suất trả chậm không được để trống.',
            'score_min.required'                       => 'Điểm tín nhiệm không được để trống.',
            'score_min.max'                       => 'Điểm tín nhiệm không được quá 1000.',
            'start_month_pay.required'                       => 'Số tháng n/v bắt đầu phải trả tiền từ ngày giải ngân không được để trống.',
            'preferential_interest_rate.required'  => 'Lãi suất ưu đãi không được để trống.',
            'preferential_interest_rate.numeric'   => 'Lãi suất ưu đãi không hợp lệ.',
            'apply_from.required'           => 'Áp dụng từ không được để trống.',
            'apply_from.date_format'        => 'Áp dụng từ không hợp lệ.',
            'apply_from.validate_time'      => 'Khoảng thời gian áp dụng đã trùng với khoảng thời gian khác.',
            'apply_to.after'           => 'Áp dụng đến phải lớn hơn Áp dụng từ.',
            'apply_to.required'           => 'Áp dụng đến không được để trống.',
            'apply_to.date_format'        => 'Áp dụng đến không hợp lệ.',
        ];
    }
}
