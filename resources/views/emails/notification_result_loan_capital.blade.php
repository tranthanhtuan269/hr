@if ($status == 2)
    <p>Yêu cầu đăng ký vay vốn của bạn đã được phê duyệt. Xin vui lòng truy cập danh mục <a href="{!! url('toh_hrm/vay-von/index') !!}">Tín dụng</a> trên web nhân sự để quản lý thông tin</p>
@else
    <p>Yêu cầu đăng ký vay vốn của bạn không được phê duyệt.</p>
    @if ($reason != null)
        <div>Ghi chú: {!! $reason !!}</div>
    @endif
@endif