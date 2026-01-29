@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<?php
    if( !empty(Request::input('selectMonth')) ){
        $month =  Request::input('selectMonth');
        $year  =  Request::input('selectYear');
        
    }
    if( !isset($_GET['selectMonth']) )
    {
        $month = date('m');
        $year  = date('Y');
    }
    $dateCurrent = $year."-".$month."-"."01";
    $time = $year."-".$month;
    $numberDate = $year."-".$month."-".cal_days_in_month(CAL_GREGORIAN,$month,$year);
    $day_last_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);
	// Tính số ngày công tiêu chuẩn
    $standard_days  = BatvHelper::count_working_days($dateCurrent, $numberDate);
    $p_setting = BatvHelper::infoDaysLevea($month,$year);
?>

<div class="row box_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.client.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Tổng hợp</h4>
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
									    $months = ($i < 10) ? '0'.$i : $i ;
									    echo '<option value="'.$months.'"';
									    if (!empty(Request::input('selectMonth'))) {
									    	if ($i == Request::input('selectMonth')) echo ' selected="selected"';
									    }else{
									    	if ($i == date("n")) echo ' selected="selected"';
									    }						    
									    echo '>'.$months.'</option>';
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
						Thông tin tổng hợp
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
				                <th class="text-center">M/l chính thức</th>
			                	<th class="text-center">Hệ số tương ứng</th>
				                <th class="text-center">M/l thử việc( 85% )</th>
				                <th class="text-center">M/l bình quân ngày chính thức</th>
				                <th class="text-center">M/l bình quân ngày thử việc</th>
				                <th class="text-center">Số ngày làm việc</th>
				                <th class="text-center">Công tiêu chuẩn</th>
				                <th class="text-center">Lương chính thức theo ngày công</th>
								<th class="text-center">M/l thử việc theo ngày công</th>
				                <th class="text-center">P/c trách nhiệm</th>
				                <th class="text-center">P/c ăn trưa</th>
				                <th class="text-center">P/c Xăng xe</th>
				                <th class="text-center">Phụ cấp khác(Nếu ko tham gia BH)</th>
				                <th class="text-center">Phụ cấp laptop cá nhân</th>
				                <th class="text-center">Hỗ trợ chi phí sử dụng 3G</th>
								<th class="text-center">Phụ cấp phong trào</th>
				                <th class="text-center">Thưởng ngày lễ</th>
				                <th class="text-center">Thưởng dự án</th>
				                <th class="text-center">Người lao động phải đóng BH</th>
				                <th class="text-center">Phạt đi làm muộn</th>
				                <th class="text-center">Quỹ phúc lợi</th>
				                <th class="text-center">Thanh toán tiền gửi xe </th>
			                	<th class="text-center">Tiền liên hoan </th>
				                <th class="text-center">Các khoản khác</th>
				                <th class="text-center">Trừ lương khi vượt số ngày phép trong tháng</th>
								<th class="text-center">Lương làm thêm giờ</th>
								<th class="text-center">Trả định kỳ vay vốn</th>
				                <th class="text-center">Lương lần 1</th>
				                <th class="text-center">Lương lần 2</th>
				                <th class="text-center">Thực nhận</th>
				            </tr>
				        </thead>
	   			        <tbody>
						    @if(!empty($data))
						     	@foreach ($data as $val)
									<?php 	
										$total_item = 0; 

						     			if ($val->status_bonus == 1) {
						     				$val->holiday_bonus = 0;
						     			}

									    $countAttendance_CP = BatvHelper::countAttendance_CP($month,$year,1,$numberDate,$val->personnel_id) + BatvHelper::countTooLateAttendance( $month,$year,1,$numberDate,$val->personnel_id  );// số ngày nghỉ có phép + số buổi đi làm nhưng đi muộn > 60 phút sẽ bị trừ nửa ngày công
									    $countAttendance_KP = BatvHelper::countAttendance_KP($month,$year,1,$numberDate,$val->personnel_id);// số ngày nghỉ ko phép
									    $days_leave = $countAttendance_CP + $countAttendance_KP*2;
									?>
							     	@foreach ( $others['list'][$val->personnel_id]['income_value'] as $k=>$v )
							     		
										@if( !empty($v) )
	<!-- 													<span style="font-size: 12px;">{{ $k }}</span> : <b>{{ BatvHelper::formatPrice($v) }}</b> <br> -->
											<?php $total_item += $v; ?> 
										@endif
									@endforeach
									<?php
										$pay_month_loan_capital = $val->principal + $val->interest + $val->interest_incurred + $val->wanting_month_prev_money - $val->redundancy_month_prev_money;
		                            	$date_final_month = 31;

		                            	if (!empty($val->date_out) && date('Y', strtotime($val->date_out)) == $year && date('m', strtotime($val->date_out)) == $month) {
		                            		$date_final_month = (int)date('d', strtotime($val->date_out));
		                            	}
									?>
						     		<!-- Chính thức -->
						     		@if( $val->salary_official_work > 0 && $val->salary_trial_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0 )
									    <tr>
											<td class="text-nowrap" scope="row"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
											<td>{{ BatvHelper::formatPrice($val->salary_official_default) }}</td>
											<td>{{ BatvHelper::getInfoRatioInSalary($numberDate,$val->personnel_id) }}</td>
											<td>-</td>
											<td>{{ BatvHelper::formatPrice( ($val->salary_official_default)/$standard_days ) }}</td>
											<td>-</td>
											<td>
										<?php
											$infoUserIdExceptionalAttendance = BatvHelper::infoUserIdExceptionalAttendance($time);
				                            if( in_array($val->personnel_id, $infoUserIdExceptionalAttendance) ){
											    $nctt = $standard_days;
				                            }else{
											    $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											    $nctt += BatvHelper::nnp($nctt);
				                            }

										    echo $nctt = ($nctt >= $standard_days) ? $standard_days : $nctt;

											if( $val->insurance >0 ){
												$salary_1 = (  ( BatvHelper::infoPersonnelSpecial($val->personnel_id,'insurrance')*$nctt )/$standard_days )+$val->lunch_allowance+$val->travel_allowance+$val->phone_allowance+$val->subsidize_house - ( BatvHelper::infoPersonnelSpecial($val->personnel_id,'insurrance')*0.105 );
											}

										?>
											</td><!-- Số ngày làm việc -->
											<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
											<td>{{ BatvHelper::formatPrice($val->salary_official_work) }}</td><!-- Lương chính thức -->
											<td>-</td><!-- M/l thử việc -->
											<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
											<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
											<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
											<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td><!-- P/c nếu ko tham gia BH -->
											<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td><!-- P/c laptop cá nhân -->
											<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
											<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
											<td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td><!-- Thưởng dự án -->
											<td>
												@if( ($val->insurance)>0)
												-{{ BatvHelper::formatPrice($val->insurance) }}
												@endif
											</td><!-- Người lao động phải đóng bảo hiểm -->
											<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
											<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
											<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
											<td>
												<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
											</td><!-- Tiền liên hoan -->
											<td>
												{{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
											</td>
											<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
											<td>
												{{ BatvHelper::formatPrice($val->salary_overtime) }}
											</td>
											<td>
												{{ ($pay_month_loan_capital > 0) ? '-'.BatvHelper::formatPrice($pay_month_loan_capital) : 0 }}<!-- Trả nợ định kỳ kỳ vay vốn -->
											</td>
											<td>
												<?php
													if( $val->insurance >0 ){
														echo BatvHelper::formatPrice($salary_1);
													} else {
														echo '0';
													}
												?>
											</td><!-- Lương lần 1 -->
											<td>
												<?php
													if( $val->insurance >0 ){
														$flag_tmp = $val->salary_overtime +  $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol  - $val->party_fee - $salary_1 - $pay_month_loan_capital;
													}else{
														$flag_tmp = $val->salary_overtime + $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol  - $val->party_fee - $pay_month_loan_capital;
													}

													echo BatvHelper::formatPrice($flag_tmp);
												?>
											</td><!-- Lương lần 2 -->
											<td>
												<?php
													$flag_tmp = $val->salary_overtime +  $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus - $val->party_fee - $pay_month_loan_capital;
															
													// if ($flag_tmp < 0) {
													// 	echo '0';
													// } else {
													// 	echo BatvHelper::formatPrice($flag_tmp);
													// }

													echo BatvHelper::formatPrice($flag_tmp);
												?>
											</td><!-- Thực nhận -->
									    </tr>
								    <!-- Thử việc -->
								    @elseif( $val->salary_trial_work> 0 && $val->salary_official_work  == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0 )
									     <tr>
									      	<td class="text-nowrap" scope="row"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
											<td>{{ BatvHelper::formatPrice($val->salary_trial_default) }}</td>
											<td>{{ BatvHelper::getInfoRatioInSalary($numberDate,$val->personnel_id) }}</td>
											<td>{{ BatvHelper::formatPrice( BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default) ) }}</td>
											<td>-</td>
											<td>{{ BatvHelper::formatPrice( (BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default))/$standard_days ) }}</td>
											<td>
											<?php
												echo $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											?>
											</td>
											<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
											<td>-</td><!-- Lương chính thức -->
											<td>{{ BatvHelper::formatPrice($val->salary_trial_work) }}</td><!-- M/l thử việc -->
											<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
											<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
											<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
											<td>-</td><!-- P/c nếu ko tham gia BH -->
											<td>-</td><!-- P/c laptop cá nhân -->
											<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
											<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
											<td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td><!-- Thưởng dự án -->
											<td>
												@if( ($val->insurance)>0)
												-{{ BatvHelper::formatPrice($val->insurance) }}
												@endif
											</td><!-- Người lao động phải đóng bảo hiểm -->
											<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
											<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
											<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
											<td>
												<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
											</td><!-- Tiền liên hoan -->
											<td>
												{{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
											</td>
											<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
											<td>{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
											<td></td><!-- Trả nợ định kỳ kỳ vay vốn -->
											<td>0</td><!-- Lương lần 1 -->
											<td></td><!-- Lương lần 2 -->
											<td>{{ BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trial_work+$val->management_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance+$total_item+$val->mulct_money_awol-$val->party_fee+$val->holiday_bonus) }}</td><!-- Thực nhận -->
									    </tr>
						     		<!-- Parttime -->
						     		@elseif( $val->salary_parttime_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_trainee_work == 0 )
									    <tr>
											<td class="text-nowrap" scope="row"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
											<td>{{ BatvHelper::formatPrice($val->salary_parttime_default) }}</td>
											<td>{{ BatvHelper::getInfoRatioInSalary($numberDate,$val->personnel_id) }}</td>
											<td>-</td>
											<td>{{ BatvHelper::formatPrice( ($val->salary_parttime_default)/$standard_days ) }}</td>
											<td>-</td>
											<td>
											<?php
												echo $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											?>
											</td><!-- Số ngày làm việc -->
											<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
											<td>{{ BatvHelper::formatPrice($val->salary_parttime_work) }}</td><!-- Lương chính thức -->
											<td>-</td><!-- M/l thử việc -->
											<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
											<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
											<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
											<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td><!-- P/c nếu ko tham gia BH -->
											<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td><!-- P/c laptop cá nhân -->
											<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
											<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
											<td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td><!-- Thưởng dự án -->
											<td>
												@if( ($val->insurance)>0)
												-{{ BatvHelper::formatPrice($val->insurance) }}
												@endif
											</td><!-- Người lao động phải đóng bảo hiểm -->
											<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
											<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
											<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
											<td>
												<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
											</td><!-- Tiền liên hoan -->
											<td>
												{{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
											</td>
											<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
											<td>{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
											<td></td>
											<td></td>
											<td>{{ BatvHelper::formatPrice($val->salary_overtime + $val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol-$val->party_fee+$val->holiday_bonus) }}</td><!-- Thực nhận -->
									    </tr>
						     		<!-- Thực tập fulltime-->
						     		@elseif( $val->salary_trainee_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_parttime_work == 0 )
									    <tr>
											<td class="text-nowrap" scope="row"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
											<td>{{ BatvHelper::formatPrice($val->salary_trainee_default) }}</td>
											<td>{{ BatvHelper::getInfoRatioInSalary($numberDate,$val->personnel_id) }}</td>
											<td>-</td>
											<td>{{ BatvHelper::formatPrice( ($val->salary_trainee_default)/$standard_days ) }}</td>
											<td>-</td>
											<td>
											<?php
												echo $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											?>
											</td><!-- Số ngày làm việc -->
											<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
											<td>{{ BatvHelper::formatPrice($val->salary_trainee_work) }}</td><!-- Lương chính thức -->
											<td>-</td><!-- M/l thử việc -->
											<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
											<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
											<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
											<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td><!-- P/c nếu ko tham gia BH -->
											<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td><!-- P/c laptop cá nhân -->
											<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
											<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
											<td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td><!-- Thưởng dự án -->
											<td>
												@if( ($val->insurance)>0)
												-{{ BatvHelper::formatPrice($val->insurance) }}
												@endif
											</td><!-- Người lao động phải đóng bảo hiểm -->
											<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
											<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
											<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
											<td>
												<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
											</td><!-- Tiền liên hoan -->
											<td>
												{{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
											</td>
											<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
											<td>{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
											<td></td><!-- Trả nợ định kỳ kỳ vay vốn -->
											<td>0</td><!-- Lương lần 1 -->
											<td></td><!-- Lương lần 2 -->
											<td>{{ BatvHelper::formatPrice($val->salary_overtime + $val->salary_trainee_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol-$val->party_fee+$val->holiday_bonus) }}</td><!-- Thực nhận -->
									    </tr>
						     		<!-- Thực tập Parttime-->
						     		@elseif( $val->salary_trainee_parttime_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0)
									    <tr>
											<td class="text-nowrap" scope="row"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
											<td>{{ BatvHelper::formatPrice($val->salary_trainee_default) }}</td>
											<td>{{ BatvHelper::getInfoRatioInSalary($numberDate,$val->personnel_id) }}</td>
											<td>-</td>
											<td>{{ BatvHelper::formatPrice( ($val->salary_trainee_default)/$standard_days ) }}</td>
											<td>-</td>
											<td>
											<?php
												echo $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											?>
											</td><!-- Số ngày làm việc -->
											<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
											<td>{{ BatvHelper::formatPrice($val->salary_trainee_parttime_work) }}</td><!-- Lương chính thức -->
											<td>-</td><!-- M/l thử việc -->
											<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
											<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
											<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
											<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td><!-- P/c nếu ko tham gia BH -->
											<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td><!-- P/c laptop cá nhân -->
											<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
											<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
											<td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td><!-- Thưởng dự án -->
											<td>
												@if( ($val->insurance)>0)
												-{{ BatvHelper::formatPrice($val->insurance) }}
												@endif
											</td><!-- Người lao động phải đóng bảo hiểm -->
											<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
											<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
											<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
											<td>
												<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
											</td><!-- Tiền liên hoan -->
											<td>
												{{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
											</td>
											<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
											<td>{{ BatvHelper::formatPrice($val->salary_overtime) }}</td>
											<td></td><!-- Trả nợ định kỳ kỳ vay vốn -->
											<td>0</td><!-- Lương lần 1 -->
											<td></td><!-- Lương lần 2 -->
                                        	<td>{{ BatvHelper::formatPrice($val->salary_overtime + $val->salary_trainee_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol+$val->holiday_bonus-$val->party_fee) }}</td><!-- Thực nhận -->
									    </tr>
									    <!-- Nửa này nửa kia -->
									    @else
									    	<?php
									    		$getContractsPersonnelbyUser = BatvHelper::getContractsPersonnelbyUser($time, $val->personnel_id);
									    	?>
								    		@if( $val->salary_trial_work >0 && ( $val->salary_trainee_work >0 || $val->salary_trainee_parttime_work >0  || $val->salary_official_work >0 || $val->salary_parttime_work >0 ) )
												<?php
										    		foreach ($getContractsPersonnelbyUser as $key => $value) {
									    				if ($key == 0) {
									    					$day_last_attendance = explode("-",$value->apply_to);
									    					$day_last_attendance = $day_last_attendance[2];

											    			if ($value->contract_id == 1) { //Hợp đồng thử việc
															    $nctt_0 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$day_last_attendance);
											    			} else {
															    $nctt_1 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$day_last_attendance);

												    			if ($value->contract_id == 2) { //Hợp đồng chính thức
																    $nctt_1 = (($nctt_0 + $nctt_1) >= $standard_days) ? $nctt_1 : $nctt_1 + BatvHelper::nnp($nctt_1);
												    			}
											    			}

									    				} else {
															$day_first_attendance = explode("-",$value->apply_from);
									    					$day_first_attendance = $day_first_attendance[2];
															$day_first_attendance = BatvHelper::formatDate( $value->apply_from,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",$time=false);

											    			if ($value->contract_id == 1) { //Hợp đồng thử việc
															    $nctt_0 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,$day_first_attendance,$day_last_month);
											    			} else {
															    $nctt_1 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,$day_first_attendance,$day_last_month);
															    
												    			if ($value->contract_id == 2) { //Hợp đồng chính thức
														    		if ($nctt_0 + $nctt_1 < $standard_days) {
														    			$nnp = BatvHelper::nnp($nctt_1);
														    			$nctt_1 = $nctt_1 + $nnp;

																		if ($nctt_0 + $nctt_1 > $standard_days) {
																			$nctt_1 = $nctt_1 - $nnp/2;
																		}
														    			
												    				}	
														    				
												    			}
											    			}
									    				}
										    		}
													if( $val->salary_trainee_work >0 ){
														$default = $val->salary_trainee_default;
														$work = $val->salary_trainee_work;
													}elseif( $val->salary_trainee_parttime_work >0 ){
														$default = $val->salary_trainee_parttime_default;
														$work = $val->salary_trainee_parttime_work;
													}elseif( $val->salary_official_work >0 ){
														$default = $val->salary_official_default;
														$work = $val->salary_official_work;
													}else{
														$default = $val->salary_parttime_default;
														$work = $val->salary_parttime_work;
													}
													$ratio_1 = BatvHelper::getInfoRatioInSalary($dateCurrent, $val->personnel_id, $val->salary_trial_default, $default);
													$ratio_2 = BatvHelper::getInfoRatioInSalary($dateCurrent, $val->personnel_id, $default, $val->salary_trial_default);
												?>
											     <tr>
											      	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
													<td>{{ BatvHelper::formatPrice($val->salary_trial_default) }}</td>
													<td>{{ $ratio_1 }}</td>
													<td>{{ BatvHelper::formatPrice( BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default) ) }}</td>
													<td>-</td>
													<td>{{ BatvHelper::formatPrice( (BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default))/$standard_days ) }}</td>
													<td>
														<?php
															$x1 = round( ($val->salary_trial_work)/(BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default)/$standard_days ),1);
															echo $x1- $countAttendance_KP;
														?>
													</td>
													<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
													<td>-</td><!-- Lương chính thức -->
													<td>{{ BatvHelper::formatPrice($val->salary_trial_work) }}</td><!-- M/l thử việc -->
													<td>-</td><!-- P/c trách nhiệm -->
													<td>-</td><!-- P/c ăn trưa -->
													<td>-</td><!-- P/c xăng xe -->
													<td>-</td><!-- P/c nếu ko tham gia BH -->
													<td>-</td><!-- P/c laptop cá nhân -->
													<td>-</td><!-- P/c điện thoại 3G -->
													<td>-</td><!-- P/c phong trào -->
													<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
													<td>-</td><!-- Thưởng dự án -->
													<td>-</td><!-- Người lao động phải đóng bảo hiểm -->
													<td>-</td><!-- Phạt đi làm muộn -->
													<td>-</td><!-- Quỹ phúc lợi -->
													<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
													<td>
														<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
													</td><!-- Tiền liên hoan -->
													<td> -</td> <!-- các khoản khác -->
													<td> -</td>
													<td rowspan="2">{{ BatvHelper::formatPrice($val->salary_overtime) }}</td><!-- Lương làm thêm giờ -->
													<td rowspan="2" style="padding: 25px 0;"></td><!-- Trả nợ định kỳ kỳ vay vốn -->
													<td>0</td><!-- Lương lần 1 -->
													<td></td><!-- Lương lần 2 -->
													<td>{{ BatvHelper::formatPrice( $val->salary_trial_work+$val->parking_fee_allowance +$val->holiday_bonus-$val->party_fee) }}</td><!-- Thực nhận -->
											    </tr>
											    <tr>
													<td>{{ BatvHelper::formatPrice($default) }}</td>
													<td>{{ $ratio_2 }}</td>
													<td>-</td>
													<td>{{ BatvHelper::formatPrice( ($default)/$standard_days ) }}</td>
													<td>-</td>
													<td>
														<?php
															$x1 = round( ($work)/($default/$standard_days ),1);
															echo $x1- $countAttendance_KP;
														?>
													</td>
													<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
													<td>{{ BatvHelper::formatPrice($work) }}</td><!-- Lương chính thức -->
													<td>-</td><!-- M/l thử việc -->
													<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
													<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
													<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
													<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td><!-- P/c nếu ko tham gia BH -->
													<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td><!-- P/c laptop cá nhân -->
													<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
													<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
													<td>-</td><!-- Thưởng ngày lễ -->
													<td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td><!-- Thưởng dự án -->
													<td>
                                                	@if( ($val->insurance)>0)
                                            			-{{ BatvHelper::formatPrice($val->insurance) }}
                                                	@endif
													</td><!-- Người lao động phải đóng bảo hiểm -->
	
													<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
													<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
													<td>-</td><!-- Thanh toán tiền gửi xe -->
													<td></td>
													<td>
													    {{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
													</td>
													<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
													<td></td>
													<td></td>
													<td>{{ BatvHelper::formatPrice( $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol) }}</td><!-- Thực nhận -->

											    </tr>
									    	@endif
									    	@if( $val->salary_trainee_work >0 && ( $val->salary_official_work >0 || $val->salary_trainee_parttime_work >0  || $val->salary_parttime_work >0 ) )
												<?php
										    		foreach ($getContractsPersonnelbyUser as $key => $value) {
									    				if ($key == 0) {
									    					$day_last_attendance = BatvHelper::formatDate( $value->apply_to,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",$time=false);

											    			if ($value->contract_id == 3) { // Thực tập Full time
															    $nctt_0 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$day_last_attendance);
											    			} else {
															    $nctt_1 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$day_last_attendance);

												    			if ($value->contract_id == 2) { //Hợp đồng chính thức
																    $nctt_1 = (($nctt_0 + $nctt_1) >= $standard_days) ? $nctt_1 : $nctt_1 + BatvHelper::nnp($nctt_1);
												    			}
											    			}

									    				} else {
															$day_first_attendance = BatvHelper::formatDate( $value->apply_from,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",$time=false);

											    			if ($value->contract_id == 3) { // Thực tập Full time
															    $nctt_0 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,$day_first_attendance,$day_last_month);
											    			} else {
															    $nctt_1 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,$day_first_attendance,$day_last_month);
															    
												    			if ($value->contract_id == 2) { //Hợp đồng chính thức
														    		if ($nctt_0 + $nctt_1 < $standard_days) {
														    			$nnp = BatvHelper::nnp($nctt_1);
														    			$nctt_1 = $nctt_1 + $nnp;

																		if ($nctt_0 + $nctt_1 > $standard_days) {
																			$nctt_1 = $nctt_1 - $nnp/2;
																		}
														    			
												    				}	
														    				
												    			}
											    			}
									    				}
										    		}
													if( $val->salary_official_work >0 ){
														$default = $val->salary_official_default;
														$work = $val->salary_official_work;
													}elseif ( $val->salary_trainee_parttime_work >0 ) {
														$default = $val->salary_trainee_parttime_default;
														$work = $val->salary_trainee_parttime_work;
													}
													else{
														$default = $val->salary_parttime_default;
														$work = $val->salary_parttime_work;
													}
													$ratio_1 = BatvHelper::getInfoRatioInSalary($dateCurrent, $val->personnel_id, $val->salary_trainee_default, $default);
													$ratio_2 = BatvHelper::getInfoRatioInSalary($dateCurrent, $val->personnel_id, $default, $val->salary_trainee_default);
												?>
											     <tr>
											      	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
													<td>{{ BatvHelper::formatPrice($val->salary_trainee_default) }}</td>
													<td>{{ $ratio_1 }}</td>
													<td>-</td>
													<td>{{ BatvHelper::formatPrice( ($val->salary_trainee_default)/$standard_days ) }}</td>
													<td>-</td>
													<td>
														<?php
															$x1 = round( ($val->salary_trainee_work)/($val->salary_trainee_default/$standard_days ),1);
															echo $x1- $countAttendance_KP;
														?>
													</td>
													<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
													<td>{{ BatvHelper::formatPrice($val->salary_trainee_work) }}</td><!-- Lương chính thức -->
													<td>-</td><!-- M/l thử việc -->
													<td>-</td><!-- P/c trách nhiệm -->
													<td>-</td><!-- P/c ăn trưa -->
													<td>-</td><!-- P/c xăng xe -->
													<td>-</td><!-- P/c nếu ko tham gia BH -->
													<td>-</td><!-- P/c laptop cá nhân -->
													<td>-</td><!-- P/c điện thoại 3G -->
													<td>-</td><!-- P/c phong trào -->
													<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
													<td>-</td><!-- Thưởng dự án -->
													<td>-</td><!-- Người lao động phải đóng bảo hiểm -->
													<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
													<td>-</td><!-- Quỹ phúc lợi -->
													<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
													<td>
														<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
													</td><!-- Tiền liên hoan -->
													<td> -</td> <!-- các khoản khác -->
													<td> -</td>
													<td rowspan="2">{{ BatvHelper::formatPrice($val->salary_overtime) }}</td><!-- Lương làm thêm giờ -->
													<td rowspan="2" style="padding: 25px 0;"></td><!-- Trả nợ định kỳ kỳ vay vốn -->
													<td>0</td><!-- Lương lần 1 -->
													<td></td><!-- Lương lần 2 -->
													<td>{{ BatvHelper::formatPrice( $val->salary_trainee_work+$val->holiday_bonus-$val->party_fee) }}</td><!-- Thực nhận -->
													
											    </tr>
											    <tr>
													<td>{{ BatvHelper::formatPrice($default) }}</td>
													<td>{{ $ratio_2 }}</td>
													<td>-</td>
													<td>{{ BatvHelper::formatPrice( ($default)/$standard_days ) }}</td>
													<td>-</td>
													<td>
														<?php
															$x1 = round( ($work)/($default/$standard_days ),1);
															echo $x1- $countAttendance_KP;
														?>
													</td>
													<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
													<td>{{ BatvHelper::formatPrice($work) }}</td><!-- Lương chính thức -->
													<td>-</td><!-- M/l thử việc -->
													<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
													<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
													<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
													<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td><!-- P/c nếu ko tham gia BH -->
													<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td><!-- P/c laptop cá nhân -->
													<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
													<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
													<td>-</td><!-- Thưởng ngày lễ -->
													<td>-</td><!-- Thưởng dự án -->
													<td>
                                                	@if( ($val->insurance)>0)
                                            			-{{ BatvHelper::formatPrice($val->insurance) }}
                                                	@endif
													</td><!-- Người lao động phải đóng bảo hiểm -->
	
													<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
													<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
													<td>-</td><!-- Thanh toán tiền gửi xe -->
													<td>
													    {{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
													</td>
													<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
													<td></td>
													<td></td>
													<td>{{ BatvHelper::formatPrice( $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol) }}</td><!-- Thực nhận -->

											    </tr>
									    	@endif
									    	@if( $val->salary_parttime_work >0 && ( $val->salary_official_work >0 || $val->salary_trainee_parttime_work >0  )  )
												<?php
										    		foreach ($getContractsPersonnelbyUser as $key => $value) {
									    				if ($key == 0) {
									    					$day_last_attendance = BatvHelper::formatDate( $value->apply_to,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",$time=false);

											    			if ($value->contract_id == 4) { // Partime
															    $nctt_0 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$day_last_attendance);
											    			} else {
															    $nctt_1 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$day_last_attendance);

												    			if ($value->contract_id == 2) { //Hợp đồng chính thức
																    $nctt_1 = (($nctt_0 + $nctt_1) >= $standard_days) ? $nctt_1 : $nctt_1 + BatvHelper::nnp($nctt_1);
												    			}
											    			}

									    				} else {
															$day_first_attendance = BatvHelper::formatDate( $value->apply_from,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",$time=false);

											    			if ($value->contract_id == 4) { // Partime
															    $nctt_0 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,$day_first_attendance,$day_last_month);
											    			} else {
															    $nctt_1 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,$day_first_attendance,$day_last_month);
															    
												    			if ($value->contract_id == 2) { //Hợp đồng chính thức
														    		if ($nctt_0 + $nctt_1 < $standard_days) {
														    			$nnp = BatvHelper::nnp($nctt_1);
														    			$nctt_1 = $nctt_1 + $nnp;

																		if ($nctt_0 + $nctt_1 > $standard_days) {
																			$nctt_1 = $nctt_1 - $nnp/2;
																		}
														    			
												    				}	
														    				
												    			}
											    			}
									    				}
										    		}

													if( $val->salary_official_work >0 ){
														$default = $val->salary_official_default;
														$work = $val->salary_official_work;
													}else{
														$default = $val->salary_trainee_parttime_default;
														$work = $val->salary_trainee_parttime_work;
													}
													$ratio_1 = BatvHelper::getInfoRatioInSalary($dateCurrent, $val->personnel_id, $val->salary_parttime_default, $default);
													$ratio_2 = BatvHelper::getInfoRatioInSalary($dateCurrent, $val->personnel_id, $default, $val->salary_parttime_default);
												?>
											     <tr>
											      	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
													<td>{{ BatvHelper::formatPrice($val->salary_parttime_default) }}</td>
													<td>{{ $ratio_1 }}</td>
													<td>-</td>
													<td>{{ BatvHelper::formatPrice( ($val->salary_parttime_default)/$standard_days ) }}</td>
													<td>-</td>
													<td>
														<?php
															$x1 = round( ($val->salary_parttime_work)/($val->salary_parttime_default/$standard_days ),1);
															echo $x1- $countAttendance_KP;
														?>
													</td>
													<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
													<td>{{ BatvHelper::formatPrice($val->salary_parttime_work) }}</td><!-- Lương chính thức -->
													<td>-</td><!-- M/l thử việc -->
													<td>-</td><!-- P/c trách nhiệm -->
													<td>-</td><!-- P/c ăn trưa -->
													<td>-</td><!-- P/c xăng xe -->
													<td>-</td><!-- P/c nếu ko tham gia BH -->
													<td>-</td><!-- P/c laptop cá nhân -->
													<td>-</td><!-- P/c điện thoại 3G -->
													<td>-</td><!-- P/c phong trào -->
													<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
													<td>-</td><!-- Thưởng dự án -->
													<td>-</td><!-- Người lao động phải đóng bảo hiểm -->
													<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
													<td>-</td><!-- Quỹ phúc lợi -->
													<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
													<td>
														<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
													</td><!-- Tiền liên hoan -->
													<td> -</td> <!-- các khoản khác -->
													<td> -</td>
													<td rowspan="2">{{ BatvHelper::formatPrice($val->salary_overtime) }}</td><!-- Lương làm thêm giờ -->
													<td rowspan="2" style="padding: 25px 0;"></td><!-- Trả nợ định kỳ kỳ vay vốn -->
													<td>0</td><!-- Lương lần 1 -->
													<td></td><!-- Lương lần 2 -->
													<td>{{ BatvHelper::formatPrice( $val->salary_parttime_work+$val->holiday_bonus-$val->party_fee) }}</td><!-- Thực nhận -->

											    </tr>
											    <tr>
													<td>{{ BatvHelper::formatPrice($default) }}</td>
													<td>{{ $ratio_2 }}</td>
													<td>-</td>
													<td>{{ BatvHelper::formatPrice( ($default)/$standard_days ) }}</td>
													<td>-</td>
													<td>
														<?php
															$x1 = round( ($work)/($default/$standard_days ),1);
															echo $x1- $countAttendance_KP;
														?>
													</td>
													<td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
													<td>{{ BatvHelper::formatPrice($work) }}</td><!-- Lương chính thức -->
													<td>-</td><!-- M/l thử việc -->
													<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
													<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
													<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
													<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td><!-- P/c nếu ko tham gia BH -->
													<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td><!-- P/c laptop cá nhân -->
													<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
													<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
													<td>-</td><!-- Thưởng ngày lễ -->
													<td>-</td><!-- Thưởng dự án -->
													<td>
                                                	@if( ($val->insurance)>0)
                                            			-{{ BatvHelper::formatPrice($val->insurance) }}
                                                	@endif
													</td><!-- Người lao động phải đóng bảo hiểm -->
	
													<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
													<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
													<td>-</td><!-- Thanh toán tiền gửi xe -->
													<td>
													    {{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
													</td>
													<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
													<td></td>
													<td></td>
													<td>{{ BatvHelper::formatPrice( $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol) }}</td><!-- Thực nhận -->

											    </tr>
									    	@endif	
	                                        @if( $val->salary_trainee_parttime_work >0 && $val->salary_official_work >0 )
	                                        	<?php
										    		foreach ($getContractsPersonnelbyUser as $key => $value) {
									    				if ($key == 0) {
									    					$day_last_attendance = BatvHelper::formatDate( $value->apply_to,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",$time=false);

											    			if ($value->contract_id == 5) { // Thực tập Partime
															    $nctt_0 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$day_last_attendance);
											    			} else {
															    $nctt_1 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$day_last_attendance);

												    			if ($value->contract_id == 2) { //Hợp đồng chính thức
																    $nctt_1 = (($nctt_0 + $nctt_1) >= $standard_days) ? $nctt_1 : $nctt_1 + BatvHelper::nnp($nctt_1);
												    			}
											    			}

									    				} else {
															$day_first_attendance = BatvHelper::formatDate( $value->apply_from,'Y-m-d', $formatDate="d",$timeFormat="H:i:s",$time=false);

											    			if ($value->contract_id == 5) { // Thực tập Partime
															    $nctt_0 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,$day_first_attendance,$day_last_month);
											    			} else {
															    $nctt_1 = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,$day_first_attendance,$day_last_month);
															    
												    			if ($value->contract_id == 2) { //Hợp đồng chính thức
														    		if ($nctt_0 + $nctt_1 < $standard_days) {
														    			$nnp = BatvHelper::nnp($nctt_1);
														    			$nctt_1 = $nctt_1 + $nnp;

																		if ($nctt_0 + $nctt_1 > $standard_days) {
																			$nctt_1 = $nctt_1 - $nnp/2;
																		}
														    			
												    				}	
														    				
												    			}
											    			}
									    				}
										    		}

													$ratio_1 = BatvHelper::getInfoRatioInSalary($dateCurrent, $val->personnel_id, $val->salary_trainee_parttime_default, $salary_official_default);
													$ratio_2 = BatvHelper::getInfoRatioInSalary($dateCurrent, $val->personnel_id, $salary_official_default, $val->salary_trainee_parttime_default);
	                                        	?>
	                                             <tr style="background: #aadcaa;" >
	                                                <td class="text-nowrap" scope="row" style="border-bottom: 1px solid #fff;"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
	                                                <td>{{ BatvHelper::formatPrice($val->salary_trainee_parttime_default) }}</td>
													<td>{{ $ratio_1 }}</td>
	                                                <td>-</td>
	                                                <td>{{ BatvHelper::formatPrice( ($val->salary_trainee_parttime_default)/$standard_days ) }}</td>
	                                                <td>-</td>
	                                                <td>{{ $nctt_0 }}</td>
	                                                <td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
	                                                <td>{{ BatvHelper::formatPrice($val->salary_trainee_parttime_work) }}</td><!-- Lương chính thức -->
	                                                <td>-</td><!-- M/l thử việc -->
													<!-- <td>6</td> --><!-- P/c khi không tham gia BH-->
	                                                <td>-</td><!-- P/c trách nhiệm -->
	                                                <td>-</td><!-- P/c ăn trưa -->
	                                                <td>-</td><!-- P/c xăng xe -->
	                                                <td>-</td><!-- P/c nếu ko tham gia BH -->
	                                                <td>-</td><!-- P/c laptop cá nhân -->
	                                                <td>-</td><!-- P/c điện thoại 3G -->
													<td>-</td><!-- P/c phong trào -->
	                                                <td>-</td><!-- Thưởng dự án -->
	                                                <td>-</td><!-- Người lao động phải đóng bảo hiểm -->
	                                                <td>-</td><!-- Doanh nghiệp phải đóng bảo hiểm -->
	                                                <td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
	                                                <td>-</td><!-- Quỹ phúc lợi -->
	                                                <td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td><!-- Thanh toán tiền gửi xe -->
													<td>
														<?php  if( $val->party_fee > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->party_fee) }}
													</td><!-- Tiền liên hoan -->
	                                                <td> -</td>
													<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td><!-- Thưởng ngày lễ -->
													<td> -</td> <!-- các khoản khác -->
													<td> -</td> <!-- Trừ lương khi vượt số ngày phép trong tháng -->
													<td rowspan="2">{{ BatvHelper::formatPrice($val->salary_overtime) }}</td><!-- Lương làm thêm giờ -->
													<td rowspan="2" style="padding: 25px 0;"></td><!-- Trả nợ định kỳ kỳ vay vốn -->
													<td>0</td><!-- Lương lần 1 -->
													<td></td><!-- Lương lần 2 -->
	                                                <td>{{ BatvHelper::formatPrice( $val->salary_trainee_parttime_work +$val->holiday_bonus-$val->party_fee) }}</td><!-- Thực nhận -->
	                                            </tr>
	                                            <tr style="background: #aadcaa;">
	                                            	<td></td>
	                                                <td>{{ BatvHelper::formatPrice($val->salary_official_default) }}</td>
													<td>{{ $ratio_2 }}</td>
	                                                <td>-</td>
	                                                <td>{{ BatvHelper::formatPrice( ($val->salary_official_default)/$standard_days ) }}</td>
	                                                <td>-</td>
	                                                <td>{{ $nctt_1 }}</td>
	                                                <td>{{ $standard_days }}</td><!-- Ngày công tiêu chuẩn -->
	                                                <td>{{ BatvHelper::formatPrice($work) }}</td><!-- Lương chính thức -->
	                                                <td>-</td><!-- M/l thử việc -->
													<!-- <td>6</td> --><!-- P/c khi không tham gia BH-->
	                                                <td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td><!-- P/c trách nhiệm -->
	                                                <td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td><!-- P/c ăn trưa -->
	                                                <td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td><!-- P/c xăng xe -->
	                                                <td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td><!-- P/c nếu ko tham gia BH -->
	                                                <td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td><!-- P/c laptop cá nhân -->
	                                                <td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td><!-- P/c điện thoại 3G -->
													<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
	                                                <td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td><!-- Thưởng dự án -->
	                                                <td><?php  if( $val->insurance > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->insurance) }}</td><!-- Người lao động phải đóng bảo hiểm -->
	                                                <td>{{ BatvHelper::formatPrice($val->insurance_by_company) }}</td><!-- Doanh nghiệp phải đóng bảo hiểm -->
	                                                <td><?php  if( $val->money_work_late > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->money_work_late) }}</td><!-- Phạt đi làm muộn -->
	                                                <td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?>{{ BatvHelper::formatPrice($val->welfare_fund) }}</td><!-- Quỹ phúc lợi -->
	                                                <td>-</td><!-- Thanh toán tiền gửi xe -->
	                                                <td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
													<td>-</td><!-- Thưởng ngày lễ -->
	                                                <td>
	                                                    {{ BatvHelper::formatPrice($total_item) }} <!-- các khoản khác -->
													</td>
													<td>{{ BatvHelper::formatPrice($val->mulct_money_awol) }}</td>
													<td>0</td><!-- Lương lần 1 -->
													<td></td><!-- Lương lần 2 -->
	                                                <td>{{ BatvHelper::formatPrice( $salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol) }}</td><!-- Thực nhận -->
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