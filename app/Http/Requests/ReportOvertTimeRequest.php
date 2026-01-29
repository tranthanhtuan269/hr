<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SettingOvertime;
use Illuminate\Http\Request;

class ReportOvertTimeRequest extends FormRequest
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

    public function rules(Request $request)
    {
        $setting_overtime  = SettingOvertime::find(1);
        $number_day = $request->day;

        $check_min_hour = $number_day <= 6 ? $setting_overtime->min_hour_day_normal : $setting_overtime->min_hour_day_holiday;
        $check_max_hour = $number_day <= 6 ? $setting_overtime->max_hour_day_normal : $setting_overtime->max_hour_day_holiday;
        
        
        return [
            'hour'                => 'required|numeric|min:'.$check_min_hour.'|max:'.$check_max_hour,
            'progress'                => 'required|numeric|min:0|max:100',
            'content_report'          => 'required',
        ];
    }

    public function messages()
    {
        return [
            'hour.required'      => 'Giờ làm thêm không được để trống.',
            'hour.min'                 => 'Giờ làm thêm không được nhỏ hơn số giờ quy định.',
            'hour.max'                 => 'Giờ làm thêm không được quá số giờ quy định.',
            'progress.required'            => 'Phần trăm kết quả tiến độ không được để trống.',
            'progress.numeric'             => 'Phần trăm kết quả tiến độ phải là số.',
            'progress.max'                 => 'Phần trăm kết quả không được quá 100.',
            'content_report.required'      => 'Nội dung báo cáo không được để trống.',
        ];
    }
}
