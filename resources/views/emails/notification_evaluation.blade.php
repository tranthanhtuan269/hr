<div>Tên nhân viên: <a href="{!! $link !!}">{!! $fullname !!}</a></div>
<div>
	{!! $content !!} 
	@if( !empty( $manager ) )
		Đã được quản lý <b>{!!  $manager !!}</b> đánh giá 
	@endif
</div>

@if( !empty($comment_manager_send_personnel) )
	<div><b>Nhận xét của quản lý gửi cho nhân viên</b>: </div>
	<div>{!! nl2br($comment_manager_send_personnel) !!}</div><br>
@endif
@if( !empty($comment_manager_send_direction) )
	<div><b>Nhận xét về nhân viên gửi BGĐ</b>: </div>
	{!! nl2br($comment_manager_send_direction) !!}
@endif