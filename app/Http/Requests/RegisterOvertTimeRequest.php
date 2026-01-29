<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SettingOvertime;
use Illuminate\Http\Request;

class RegisterOvertTimeRequest extends FormRequest
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
        return [
            'type'          => 'required',
            // 'content'          => 'required',
        ];
    }

    public function messages()
    {
        return [
            'type.required'      => 'Vui lòng chọn hình thức làm thêm.',
            // 'content.required'      => 'Công việc đề xuất không được để trống.',
        ];
    }
}
