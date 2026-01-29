<style type="text/css">
	table, td {
		border: 1px solid #ddd;
	}
	td{
		padding: 3px 5px;
		text-align: center;
	}
</style>

{!! nl2br($content) !!}
@if( !empty($comment_manager) )
	<div><b>Nhận xét của quản lý</b>: </div>
	<div>{!! nl2br($comment_manager) !!}</div><br>
@endif
@if( !empty($comment_manager_final) )
	<div><b>Nhận xét của BGD</b>: </div>
	{!! nl2br($comment_manager_final) !!}
@endif

<p>
	<div style="width: 49%;float: left">
		{!! $info_salary !!}
	</div>
	<div style="width: 49%;float: right">
		{!! $info_management_allowance !!}
	</div>
</p>