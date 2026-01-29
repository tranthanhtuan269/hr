<style>

	table, th, td {
		border: 1px solid #ccc;
		border-collapse: collapse;
	}
	th, td {
		padding: 5px 10px;
		text-align: left;    
	}
	.table>thead:first-child>tr:first-child>th {
		border-top: 0;
	}

</style>
	@if (count($content) > 0)
		<p>{{ $title }}</p>
		<table class="table">
			<thead>
				<tr>
					<th>Họ và tên</th>
					<th>Ngày đăng ký - Thời gian đăng ký(h)</th>

				</tr>
			</thead>
			<tbody>
				@foreach ($content as $value)
				<tr>
					<td>{{ $value->fullname }}</td>
					<td>
						@foreach ($value->detail as $item)
							<div>Thứ {{ $item->day_id }} ({{ BatvHelper::formatDate($item->time_day,"Y-m-d", $formatDate="d-m-Y",$timeFormat="H:i:s",false) }}) - {{ $item->hour }}h</div>
						@endforeach
					</td>	
				</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<p>Không có nhân viên nào đăng ký làm thêm tuần tới.</p>
	@endif

