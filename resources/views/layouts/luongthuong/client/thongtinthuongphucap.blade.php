@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')

<div class="row box_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.client.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Thông tin thưởng & phụ cấp</h4>
<!-- 			<h3 style="color: red;">Comming soon...</h3> -->
			<div class="box_search">
				<div class="row">
					<form action="" method="get">
						<div class="form-group col-lg-3">
							<label for="selectMonth" class="col-sm-4 control-label" style="padding-top: 7px;">Tháng</label>
							<div class="col-sm-8">
								 <select name="selectMonth" class="form-control">
								 <?php 
					                for ($i = 1; $i <= 12; $i++){
									    $month = ($i < 10) ? '0'.$i : $i ;
									    echo '<option value="'.$month.'"';
									    if (!empty(Request::input('selectMonth'))) {
									    	if ($i == Request::input('selectMonth')) echo ' selected="selected"';
									    }else{
									    	if ($i == date("n")) echo ' selected="selected"';
									    }						    
									    echo '>'.$month.'</option>';
									}
									?>
					             </select>
							</div>
						</div>
						<div class="form-group col-lg-3">
							<label for="enddate" class="col-sm-4 control-label" style="padding-top: 7px;">Năm</label>
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
				          <div class="text-center">
				            <button type="submit" class="btn btn-sm btn-orange hidden" id="autoClick">Tìm kiếm</button>
				          </div>
						</div>
					</form>
				</div>
			</div>
			<form action="" method="post">
				<h4 class="title-fuction">
						Thông tin lương tháng 
						<?php
							echo isset( $_GET['selectMonth'] )?$_GET['selectMonth']. "/":date('m') . "/" ;
							echo isset( $_GET['selectYear'] )?$_GET['selectYear']:date('Y');
							$ki_rules = ( isset($data[0]->ki_rules) ) ? $data[0]->ki_rules : 1;
							$ki_performance = (isset($data[0]->ki_performance) ) ? $data[0]->ki_performance : 1;
							$ki_seniority = (isset($data[0]->ki_seniority)) ? $data[0]->ki_seniority : 1 ;
						?>
				</h4>
				<div class="table-responsive" >
				    <table class="table table-bordered">
				        <thead>
				            <tr>
				                <th class="text-center">Họ và tên</th>
				                <th class="text-center"> <small>KI(nq)</small></th>
				                <th class="text-center"> <small>KI(tn)</small></th>
				                <th class="text-center"> <small>KI(hs)</small></th>
				                <th class="text-center"> <small>Thưởng ngày lễ</small></th>
				                <th class="text-center"> <small>Thưởng dự án</small></th>
				                <th class="text-center"> <small>Phụ cấp ăn trưa</small></th>
				                <th class="text-center"> <small>Phụ cấp xăng xe</small></th>
				                <th class="text-center"> <small>Phụ cấp tiền gửi xe</small></th>
				                <th class="text-center"> <small>Phụ cấp ĐT</small></th>
				                <th class="text-center"> <small>Phụ cấp trách nhiệm</small></th>
				                <th class="text-center"> <small>Phụ cấp khác(Nếu ko tham gia BH)</small></th>
				                <th class="text-center"> <small>Phụ cấp laptop cá nhân</small></th>
								<th class="text-center"> <small>Phụ cấp phong trào</small></th>
				                <th class="text-center"> <small>Tổng</small></th>
				            </tr>
				        </thead>
				        <tbody>
						    @if(!empty($data))
						     	@foreach ($data as $val)
						     		<?php
						     			if ($val->status_bonus == 1) {
						     				$val->holiday_bonus = 0;
						     			}
						     		?>
								    <tr>
								      	<td class="text-nowrap" scope="row"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
								      	<td>{{ $ki_rules }}</td>
								      	<td>{{ $ki_seniority }}</td>
								      	<td>{{ $ki_performance }}</td>
										<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td>
										<td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td>
										<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td>
										<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td>
										<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td>
										<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td>
										<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td>
										<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td>
										<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td>
										<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
										<td>{{ BatvHelper::formatPrice($val->holiday_bonus+$val->work_bonus+$val->lunch_allowance+$val->travel_allowance+$val->parking_fee_allowance+$val->phone_allowance+$val->management_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->movement_allowance) }}</td>
								    </tr>
						    	@endforeach
						    @endif
				        </tbody>
				    </table>
				</div>
				{{ csrf_field()}}
			</form>
	</div>
</div>
@endsection