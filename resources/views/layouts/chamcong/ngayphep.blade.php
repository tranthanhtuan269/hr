@extends('layouts.master')

@section('title', 'Ngày phép')

@section('content')
<div class="row content-function">
	<div class="col-lg-12">
	  @if (session('flash_message_succ') != '')
     	 <div class="alert alert-success" role="alert">{{ session('flash_message_succ') }}</div>
      @endif
		<h4 class="title-fuction">Thông tin ngày phép</h4>
		<form class="form-horizontal" method="get" action="">
			<div class="form-group col-lg-6">
				<label for="enddate" class="col-sm-4 control-label">Năm</label>
				<div class="col-sm-8">
					<select name="selectYear" class="form-control">
						<?php
						for($i=date("Y")-5;$i<=date("Y");$i++) {
							 if (!empty(Request::input('selectYear'))) {
						    	$sel = ($i == Request::input('selectYear')) ? 'selected' : '';
						    }else{
						    	$sel = ($i == date('Y')) ? 'selected' : '';
						    }	   
						    echo "<option value=".$i." ".$sel.">".$i."</option>";  // here I have changed      
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="inputBirthday" class="col-sm-4 control-label">Đơn vị</label>
				<div class="col-sm-8">	
	               <select name="selectDepart" class="form-control select2 narrow wrap" >
		                <option value="0"> -- Đơn vị -- </option>
		                {!! $department !!}
		            </select>
		            <script type="text/javascript">
						var $select2 = $('.select2').select2({
						    containerCssClass: "wrap"
						})
		            </script>
                </div>
			</div>
			 <div class="form-group col-lg-12">
	          <div class="text-center">
	            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
	            <input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
	          </div>
	        </div>
	        {{ csrf_field()}}
		</form>
	</div>
	<div class="col-lg-12 thongtin-chamcong">
	<style type="text/css">
		.table td {
   text-align: center;   
}
	</style>
		<div class="table-responsive">
	        <table class="table table-bordered">
	            <tr>
	                <th class="largerFont text-center">Họ và tên</th>
	                <?php 
	                for ($i = 1; $i <= 12; $i++){
					    $month = ($i < 10) ? 'T0'.$i : 'T'.$i ;
					    echo '<td>'.$month.'</td>';
					}
					?>
					<td>Tổng ngày phép</td>
					<td>Số ngày phép đã dùng</td>
					<td>Số ngày phép còn lại</td>
	            </tr>
	            @if(!empty($data))
					@foreach($data as $val)
						<tr>
						<?php $total = 0;?>
							<th>{{ str_limit( $val['fullname'], $limit = 45, $end = '...') }}</th>
						  @for ($i = 1; $i <= 12; $i++) 
							  @if(array_key_exists($i,$val['dayWorkLeave']))
							  	<td>{{ $val['dayWorkLeave'][$i] }}</td>
							  	<?php $total = $total + $val['dayWorkLeave'][$i] ?>
							  @else
							  	<td></td>
							  @endif
						  @endfor
						  <td>12</td>
						  <td>{{ $total }}</td>
						  <td>{{ 12 - $total }}</td>
						</tr>
					@endforeach

	            @endif

	        </table>
		</div>
	</div>	
</div>

@endsection