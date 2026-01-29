@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')

<div class="row box_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.client.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Thông tin lương</h4>
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
						?>
				</h4>
				<div class="table-responsive" >
				    <table class="table table-bordered">
				        <thead>
				            <tr>
				                <th class="text-center">Họ và tên</th>
								<th class="text-center">Lương theo ngày công</th>
								<th class="text-center">Lương làm thêm giờ</th>
				                <th class="text-center">Phạt đi làm muộn</th>
				                <th class="text-center">Trừ lương khi vượt số ngày phép trong tháng</th>
				                <th class="text-center">Tông tiền</th>
				            </tr>
				        </thead>
			       		<tbody>
			       		<?php
			       			// echo "<pre>";
			       			// print_r($data);die;
			       		?>
				    @if(!empty($data))
					     	@foreach ($data as $val)
					     		<!-- Chính thức -->
					     		@if( $val->salary_official_work > 0 && $val->salary_trial_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0 )
								    <tr>
								      	<td class="text-nowrap" scope="row">{{ str_limit( $val->fullname, $limit = 35, $end = '...') }}</td> 
										<td>{{ BatvHelper::formatPrice($val->salary_official_work) }}</td>
										<td>{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td>
										<td>{{ BatvHelper::formatPrice($val->mulct_money_awol ) }}</td>
										<td>{{ BatvHelper::formatPrice($val->salary_overtime + $val->salary_official_work-$val->money_work_late+$val->mulct_money_awol) }}</td>
								    </tr>
							    <!-- Thử việc -->
							    @elseif( $val->salary_trial_work> 0 && $val->salary_official_work  == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0 )
								     <tr>
								      	<td class="text-nowrap" scope="row">{{ str_limit( $val->fullname, $limit = 35, $end = '...') }}</td> 
										<td>{{ BatvHelper::formatPrice( $val->salary_trial_work ) }}</td>
										<td>{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td>
										<td>{{ BatvHelper::formatPrice($val->mulct_money_awol ) }}</td>
										<td>{{ BatvHelper::formatPrice( $val->salary_overtime + ( $val->salary_trial_work )-$val->money_work_late+$val->mulct_money_awol) }}</td>
								    </tr>
					     		<!-- Parttime -->
					     		@elseif( $val->salary_parttime_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_trainee_work == 0 )
								    <tr>
								      	<td class="text-nowrap" scope="row">{{ str_limit( $val->fullname, $limit = 35, $end = '...') }}</td> 
										<td>{{ BatvHelper::formatPrice($val->salary_parttime_work) }}</td>
										<td>{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td>
										<td>{{ BatvHelper::formatPrice($val->mulct_money_awol ) }}</td>
										<td>{{ BatvHelper::formatPrice($val->salary_overtime + $val->salary_parttime_work-$val->money_work_late+$val->mulct_money_awol) }}</td>
								    </tr>
					     		<!-- Thực tập -->
					     		@elseif( $val->salary_trainee_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_parttime_work == 0 )
								    <tr>
								      	<td class="text-nowrap" scope="row">{{ str_limit( $val->fullname, $limit = 35, $end = '...') }}</td> 
										<td>{{ BatvHelper::formatPrice($val->salary_trainee_work) }}</td>
										<td>{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td>
										<td>{{ BatvHelper::formatPrice($val->mulct_money_awol ) }}</td>
										<td>{{ BatvHelper::formatPrice($val->salary_overtime + $val->salary_trainee_work-$val->money_work_late+$val->mulct_money_awol) }}</td>
								    </tr>
								    <!-- Nửa này nửa kia -->
								    @else
								    	@if( $val->salary_trial_work >0 && ( $val->salary_trainee_work >0 || $val->salary_official_work >0 || $val->salary_parttime_work >0 ) )
											<?php
												if( $val->salary_trainee_work >0 ){
													$work = $val->salary_trainee_work;
												}elseif( $val->salary_official_work >0 ){
													$work = $val->salary_official_work;
												}else{
													$work = $val->salary_parttime_work;
												}

											?>
										    <tr>
										     	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
												<td>{{ BatvHelper::formatPrice( ( $val->salary_trial_work ) ) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
												<td rowspan="2" style="padding: 25px 0;"><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->mulct_money_awol ) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->salary_overtime + $work+$val->salary_trial_work-$val->money_work_late+$val->mulct_money_awol) }}</td>

										    </tr>
										    <tr>
												<td>{{ BatvHelper::formatPrice($work) }}</td>
										    </tr>
								    	@endif

								    	@if( $val->salary_trainee_work >0 && ( $val->salary_official_work >0 || $val->salary_parttime_work >0 ) )
											<?php
												if( $val->salary_official_work >0 ){
													$work = $val->salary_official_work;
												}else{
													$work = $val->salary_parttime_work;
												}

											?>
										    <tr>
										     	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
												<td>{{ BatvHelper::formatPrice( ( $val->salary_trainee_work ) ) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
												<td rowspan="2" style="padding: 25px 0;"><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->mulct_money_awol ) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->salary_overtime + $work+$val->salary_trainee_work-$val->money_work_late+$val->mulct_money_awol) }}</td>

										    </tr>
										    <tr>
												<td>{{ BatvHelper::formatPrice($work) }}</td>
										    </tr>
								    	@endif
								    	@if( $val->salary_parttime_work >0 && $val->salary_official_work >0 )
										    <tr>
										     	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
												<td>{{ BatvHelper::formatPrice( ( $val->salary_parttime_work ) ) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
												<td rowspan="2" style="padding: 25px 0;"><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->mulct_money_awol ) }}</td>
												<td rowspan="2" style="padding: 25px 0;">{{ BatvHelper::formatPrice($val->salary_overtime + $val->salary_parttime_work+$val->salary_official_work-$val->money_work_late+$val->mulct_money_awol) }}</td>

										    </tr>
										    <tr>
												<td>{{ BatvHelper::formatPrice($val->salary_official_work) }}</td>
										    </tr>
								    	@endif
						    	@endif
					    	@endforeach
					    @endif
				        </tbody>
				    </table>
				</div>
			</form>
	</div>
</div>
@endsection