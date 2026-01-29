<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SettingOvertTimeRequest extends FormRequest
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
        // echo '<pre>';
        // print_r($request->min_hour_month);die;
        return [
            // 'min_hour_week'          => 'required|numeric|min:0',
            // 'max_hour_week'          => 'required|numeric|check_max_hour_week',
            // 'min_hour_month'          => 'required|numeric|min:0',
            // 'max_hour_month'          => 'required|numeric|check_max_hour_month',
            'max_hour_day_normal'          => 'required||check_max_hour_day_normal',
            'max_hour_day_holiday'          => 'required|check_max_hour_day_holiday',
            'timesheet_x_day'          => 'required|numeric|min:1',
            'days_short'          => 'required|numeric|min:1',
            'days_long'          => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            // 'min_hour_week.required'      => 'Giờ tối thiểu trong tuần không được để trống.',
            // 'max_hour_week.required'      => 'Giờ tối đa trong tuần không được để trống.',
            // 'min_hour_month.required'      => 'Giờ tối thiểu trong tháng không được để trống.',
            // 'max_hour_month.required'      => 'Giờ tối đa trong tháng không được để trống.',

            // 'min_hour_week.numeric'      => 'Giờ tối thiểu trong tuần phải là số.',
            // 'max_hour_week.numeric'      => 'Giờ tối đa trong tuần phải là số.',
            // 'min_hour_month.numeric'      => 'Giờ tối thiểu trong tháng phải là số.',
            // 'max_hour_month.numeric'      => 'Giờ tối đa trong tháng phải là số.',

            // 'max_hour_week.check_max_hour_week'      => 'Giờ tối đa trong tuần phải lớn hơn giờ tối thiểu trong tuần.',
            // 'min_hour_month.min'      => 'Giờ tối thiểu trong tháng phải lớn hơn 0.',
            // 'max_hour_month.check_max_hour_month'      => 'Giờ tối đa trong tháng phải lớn hơn giờ tối thiểu trong tháng.',

            'max_hour_day_normal.check_max_hour_day_normal'      => 'Giờ tối đa trong ngày thường phải lớn hơn  giờ tối thiểu trong ngày thường',
            'max_hour_day_holiday.check_max_hour_day_holiday'      => 'Giờ tối đa trong ngày nghỉ phải lớn hơn  giờ tối thiểu trong ngày nghỉ',
            'timesheet_x_day.required'      => 'Số ngày phải đăng ký lại không được để trống.',
            'timesheet_x_day.min'      => 'Số ngày phải đăng ký lại phải lớn hơn 0.',
            'days_short.required'      => 'Số ngày làm tiến độ dự án không được để trống.',
            'days_short.min'      => 'Số ngày làm tiến độ dự án phải lớn hơn 0.',
            'days_long.required'      => 'Số ngày làm thường xuyên lâu dài không được để trống.',
            'days_long.min'      => 'Số ngày làm thường xuyên lâu dài phải lớn hơn 0.',
        ];
    }
}
