

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>
<?php

    if( !empty(Request::input('selectMonth')) ){
        $month =  Request::input('selectMonth');
        $year  =  Request::input('selectYear');
        
    }else{
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
<style type="text/css">
	.box_salary table {
	    border-collapse: collapse;
	    overflow-x: scroll;
	    display: block;
	}

	.box_salary thead, tbody {
	    display: block;
	}
	.box_salary td, th {
	    min-width: 120px;
	    height: 25px;
	    border: dashed 1px lightblue;
	}
	.box_salary tbody {
	    overflow-y: scroll;
	    overflow-x: hidden;
	    height: 450px;
	}

	.box_salary table thead tr th:first-child,
	.box_salary table tbody tr td:first-child{
	    position:relative;
	    z-index:10;
	    background-color:#fff;
	}

	.box_salary table tbody tr td:first-child{
	    background-color:#fff;
	}

</style>
<div class="row box_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<div class="col-lg-10">
			<h4 class="title-fuction">Tổng hợp</h4>
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
	                    <div class="form-group col-lg-5">
	                        <label for="selectDepart" class="col-sm-3 control-label" style="padding-top: 7px;">Đơn vị</label>
	                        <div class="col-sm-8">  
	                           <select name="selectDepart" id="department" class="form-control select2 wrap">
	                                <option value="0"> -- Đơn vị -- </option>
	                                <?php echo $department; ?>

	                            </select>
	                            <script type="text/javascript">
	                                var $select2 = $('.select2').select2({
	                                    containerCssClass: "wrap"
	                                })
	                            </script>
	                        </div>
	                    </div>
					 	<div class="form-group col-lg-12">
				          <div>
				            <button type="submit" class="btn btn-sm btn-orange hidden" id="autoClick">Tìm kiếm</button>
				            <?php /* <input type="submit" class="btn btn-sm btn-orange" value="Gửi Email" name="send_email" onclick="return confirm('Bạn thực sự muốn gửi Email ?')"> */ ?>
							<?php if( empty($data) || $data[0]->status_bonus ==1): ?>
								<a class="btn btn-sm btn-orange done_status_bonus" style="margin-right: 10px">Chốt thưởng</a>
							<?php endif; ?>

							<?php if( empty($data) || $data[0]->status ==1): ?>
								<a class="btn btn-sm btn-orange done">Chốt bảng lương</a>
							<?php else: ?> 
								<?php if(in_array('luongthuong-tinhlailuong',$arr_route)): ?>
									<a class="btn btn-sm btn-orange r-done">Tính lại lương</a>
								<?php endif; ?>
							<?php endif; ?>
				          </div>
						</div>
					</form>
				</div>
			</div>
	        <div id="pre_ajax_loading" style="display: none;text-align: center;"><img src="<?php echo e(asset('images/general/bx_loader.gif')); ?>"></div>
	        <div class="ajax_response" style="display: none;"></div>
			<h4 class="title-fuction">
					Thông tin tổng hợp
					<?php
						echo isset( $_GET['selectMonth'] )?$_GET['selectMonth']. "/":date('m') . "/" ;
						echo isset( $_GET['selectYear'] )?$_GET['selectYear']:date('Y');
					?>
			</h4>
			<div style="margin: 20px 0px;">
				<svg width="48" height="20">
				  <rect width="300" height="100" style="fill:rgb(147, 147, 193);stroke-width:3;stroke:rgb(147, 147, 193)" />
				</svg> <span style="font-style: italic;">Part time</span>
				<svg width="48" height="20">
				  <rect width="300" height="100" style="fill:rgba(240, 173, 78, 0.34);stroke-width:3;stroke:rgba(240, 173, 78, 0.34)" />
				</svg> <span style="font-style: italic;">Thực tập parttime</span>
				<svg width="48" height="20">
				  <rect width="300" height="100" style="fill:rgba(240, 173, 78, 0.66);stroke-width:3;stroke:rgba(240, 173, 78, 0.66)" />
				</svg> <span style="font-style: italic;">Thực tập fulltime</span>
				<svg width="48" height="20">
				  <rect width="300" height="100" style="fill:rgb(170, 220, 170);stroke-width:3;stroke:rgb(170, 220, 170)" />
				</svg> <span style="font-style: italic;">Nửa hợp đồng này, nửa hợp đồng khác</span> &nbsp;&nbsp;
				<svg width="48" height="20">
				  <rect width="300" height="100" style="fill:#5bc0de;stroke-width:3;stroke:#5bc0de" />
				</svg> <span style="font-style: italic;">Thử việc</span>
				<svg width="48" height="20">
				  <rect width="300" height="100" style="fill:#f5f5f5;stroke-width:3;stroke:#f5f5f5;" />
				</svg> <span style="font-style: italic;">Chính thức</span> &nbsp; &nbsp;
			</div>
			<div class="table-responsive" >
			    <table class="table table-bordered table-striped">
			        <thead>
			            <tr>
			                <th class="text-center" style="min-width: 210px;">Họ và tên</th>
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
			                <th class="text-center">Thưởng dự án</th>
			                <th class="text-center">Người lao động phải đóng BH</th>
			                <th class="text-center">Doanh nghiệp phải đóng BH</th>
			                <th class="text-center">Phạt đi làm muộn</th>
			                <th class="text-center">Quỹ phúc lợi</th>
			                <th class="text-center">Thanh toán tiền gửi xe </th>
			                <th class="text-center">Tiền liên hoan </th>
			                <th class="text-center">Trừ lương khi vượt số ngày phép trong tháng</th>
			                <th class="text-center">Thưởng ngày lễ</th>
			                <th class="text-center">Các khoản khác</th>
							<th class="text-center">Lương làm thêm giờ</th>
							<th class="text-center">Trả định kỳ vay vốn</th>
			                <th class="text-center">Lương lần 1</th>
			                <th class="text-center">Lương lần 2</th>
			                <th class="text-center">Thực nhận</th>
			                <th class="text-center">Tổng Bảo Hiểm</th>
			                <th class="text-center">Doanh nghiệp chi trả</th>
			            </tr>
			        </thead>
			        <tbody>
					    <?php if(!empty($data)): ?>
				    		<?php
				    			$total_pay_month_loan_capital = $all_insurance = $all_personnel = $all_company = $total = $total_salary_overtime = $total_salary_official_work = $total_salary_trial_work = $total_salary_parttime_work = $total_salary_trainee_work = $total_salary_trainee_parttime_work = $total_money_work_late = $total_mulct_money_awol = $total_holiday_bonus = $total_work_bonus = $total_lunch_allowance =$total_travel_allowance = $total_parking_fee_allowance = $total_phone_allowance=$total_movement_allowance = $total_management_allowance = $total_other_tax_allowance = $total_laptop_allowance = $total_insurance = $total_insurance_by_company = $total_welfare_fund = $total_salary_trial_default = $total_salary_official_default = $total_salary_trainee_default = $total_salary_trainee_parttime_default = $total_salary_parttime_default = $total_salary_trial_default_special = $total_salary_1 = $total_salary_2 = $total_party_fee =0;
								$stt = 1; 
				    		?>
					     	<?php foreach($data as $val): ?>
								<?php
									$total_salary_overtime += $val->salary_overtime;
					    			$total_salary_official_default += $val->salary_official_default;
					    			$total_salary_trial_default += BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default);
					    			$total_salary_trial_default_special += $val->salary_trial_default;
					    			$total_salary_trainee_default += $val->salary_trainee_default;
					    			$total_salary_trainee_parttime_default += $val->salary_trainee_parttime_default;
					    			$total_salary_parttime_default += $val->salary_parttime_default;

					    			$total_salary_official_work += $val->salary_official_work;
					    			$total_salary_trial_work += $val->salary_trial_work;
					    			$total_salary_parttime_work += $val->salary_parttime_work;
					    			$total_salary_trainee_work += $val->salary_trainee_work;
					    			$total_salary_trainee_parttime_work += $val->salary_trainee_parttime_work;
					    			$total_money_work_late += $val->money_work_late;
					    			$total_mulct_money_awol += $val->mulct_money_awol;

					    			$total_holiday_bonus += $val->holiday_bonus;
					    			$total_work_bonus += $val->work_bonus;
					    			$total_lunch_allowance += $val->lunch_allowance;
					    			$total_travel_allowance += $val->travel_allowance;
					    			$total_parking_fee_allowance += $val->parking_fee_allowance;
					    			$total_phone_allowance += $val->phone_allowance;
									$total_movement_allowance += $val->movement_allowance;
					    			$total_management_allowance += $val->management_allowance;
					    			$total_other_tax_allowance += $val->other_tax_allowance;
					    			$total_laptop_allowance += $val->laptop_allowance;
					    			$total_welfare_fund += $val->welfare_fund;
									$total_party_fee += $val->party_fee;

					    			$total_insurance += $val->insurance;
					    			$total_insurance_by_company += $val->insurance_by_company;

					    			$total_item = 0;
					    		?>
				
						     	<?php foreach( $others['list'][$val->personnel_id]['income_value'] as $k=>$v ): ?>
									<?php if( !empty($v) ): ?>
										<?php $total += $v; $total_item += $v ?> 
									<?php endif; ?>
							    <?php endforeach; ?>
								<?php	
									$pay_month_loan_capital = $val->principal + $val->interest + $val->interest_incurred + $val->wanting_month_prev_money - $val->redundancy_month_prev_money;
							    	
									// $flag_tmp_1 =  $val->salary_overtime + $val->salary_official_work+$val->salary_trial_work+$val->salary_trainee_work+$val->salary_trainee_parttime_work+$val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus-$val->party_fee - $pay_month_loan_capital;	

									// if ($flag_tmp_1 > 0) {
									// 	$all_personnel += round($flag_tmp_1);
									// 	$all_company += round($val->salary_overtime + $val->salary_official_work+$val->salary_trial_work+$val->salary_trainee_work+$val->salary_trainee_parttime_work+$val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus - $val->party_fee +$val->insurance_by_company + $pay_month_loan_capital + $val->welfare_fund);
									// } else {
									// 	$all_company += round($val->insurance_by_company);
									// }
									
									$all_insurance += $val->insurance_by_company + $val->insurance; // Tổng Bảo Hiểm, giá trị là tổng BH người LĐ đóng và BH Cty đóng
									$total_pay_month_loan_capital += $pay_month_loan_capital;

	                            	$date_final_month = 31;

	                            	if (!empty($val->date_out) && date('Y', strtotime($val->date_out)) == $year && date('m', strtotime($val->date_out)) == $month) {
	                            		$date_final_month = (int)date('d', strtotime($val->date_out));
	                            	}
								?>
					     		<!-- Chính thức -->
					     		<?php if( ($val->holiday_bonus >0 || $val->salary_official_work > 0 || $total_item > 0) && $val->salary_trial_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0 && $val->salary_trainee_parttime_work == 0 ): ?>
					     			
								    <tr style="background: #f5f5f5;">
										<td style="min-width: 210px; text-align: left;">
										  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
										</td> 
										<td><?php echo e(BatvHelper::formatPrice($val->salary_official_default)); ?></td>
										<td><?php echo e(BatvHelper::getInfoRatioInSalary($dateCurrent,$val->personnel_id)); ?></td>
										<td>-</td>
										<td><?php echo e(BatvHelper::formatPrice( ($val->salary_official_default)/$standard_days )); ?></td>
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
											} else {
												if($val->join_insurance == 1 && strtotime($year."-".$month) >= strtotime(date("Y-m", strtotime($val->apply_from))) && strtotime($year."-".$month) <= strtotime(date("Y-m", strtotime($val->apply_to))) ){
													$salary_1 = ((BatvHelper::infoPersonnelSpecial($val->personnel_id,'insurrance')*$nctt)/$standard_days )+$val->lunch_allowance+$val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->subsidize_house;
												}
											}

										?>
										</td><!-- Số ngày làm việc-->
										<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
										<td><?php echo e(BatvHelper::formatPrice($val->salary_official_work)); ?></td><!-- Lương chính thức -->
										<td>-</td><!-- M/l thử việc -->
										<td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
										<td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
										<td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
										<td><?php echo e(BatvHelper::formatPrice($val->other_tax_allowance)); ?></td><!-- P/c nếu ko tham gia BH -->
										<td><?php echo e(BatvHelper::formatPrice($val->laptop_allowance)); ?></td><!-- P/c laptop cá nhân -->
										<td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
										<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
										<td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
										<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
										<td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
										<td>
											<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

										</td><!-- Tiền liên hoan -->
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td><!-- Trừ lương khi vượt số ngày phép trong tháng -->
										<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
										<td>
											<?php echo e(BatvHelper::formatPrice($total_item)); ?> <!-- các khoản khác -->
										</td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
										<td>
											<?php echo e(($pay_month_loan_capital > 0) ? '- '.BatvHelper::formatPrice($pay_month_loan_capital) : 0); ?><!-- Trả nợ định kỳ kỳ vay vốn -->
										</td>
										<td>
											<?php
												if( $val->insurance >0 || ($val->join_insurance == 1 && strtotime($year."-".$month) >= strtotime(date("Y-m", strtotime($val->apply_from))) && strtotime($year."-".$month) <= strtotime(date("Y-m", strtotime($val->apply_to))))){
													echo BatvHelper::formatPrice($salary_1);
													$total_salary_1 += round($salary_1);
 												 
												} else {
													echo '0';
												}
											?>
										</td><!-- Lương lần 1 -->
										<td>
											<?php
												if( $val->insurance >0 ){
													$flag_tmp = $val->salary_overtime +  $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol  - $val->party_fee - $salary_1 - $pay_month_loan_capital;
												} elseif($val->join_insurance == 1 && strtotime($year."-".$month) >= strtotime(date("Y-m", strtotime($val->apply_from))) && strtotime($year."-".$month) <= strtotime(date("Y-m", strtotime($val->apply_to)))) {
													$flag_tmp = $val->salary_overtime +  $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol  - $val->party_fee - $salary_1 - $pay_month_loan_capital;
												} else {
													$flag_tmp = $val->salary_overtime + $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol  - $val->party_fee - $pay_month_loan_capital;
												}

												echo BatvHelper::formatPrice($flag_tmp);

												$total_salary_2 += round($flag_tmp);
											?>
										</td><!-- Lương lần 2 -->
										<td class="item-thucnhan">
											<?php
												$flag_tmp = $val->salary_overtime +  $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus - $val->party_fee - $pay_month_loan_capital;
														

												echo BatvHelper::formatPrice($flag_tmp);
											?>
										</td><!-- Thực nhận -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company + $val->insurance)); ?></td><!--  Tổng bảo hiểm -->
										<td class="item-dn">
											<?php
												$flag_tmp = $val->salary_overtime + $val->salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus - $val->party_fee;
														
												if ($flag_tmp < 0) {
													echo BatvHelper::formatPrice($val->insurance_by_company);
												} else {
													echo BatvHelper::formatPrice($flag_tmp + $val->insurance_by_company);
												}
											?>	
										</td><!-- Doanh nghiệp phải chi trả -->
								    </tr>
								    <?php  $stt++; ?>
							    <!-- Thử việc -->
							    <?php elseif( $val->salary_trial_work> 0 && $val->salary_official_work  == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0 && $val->salary_trainee_parttime_work == 0): ?>
								     <tr style="background: #5bc0de;" >
										<td style="min-width: 210px; text-align: left;">
										  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
										</td> 
										<td><?php echo e(BatvHelper::formatPrice($val->salary_trial_default)); ?></td>
										<td><?php echo e(BatvHelper::getInfoRatioInSalary($dateCurrent,$val->personnel_id)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice( BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default) )); ?></td>
										<td>-</td>
										<td><?php echo e(BatvHelper::formatPrice( (BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default))/$standard_days )); ?></td>
										<td>
											<?php
												echo $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											?>
										</td>
										<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
										<td>-</td><!-- Lương chính thức -->
										<td><?php echo e(BatvHelper::formatPrice($val->salary_trial_work)); ?></td><!-- M/l thử việc -->
										<td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
										<td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
										<td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
										<td>-</td><!-- P/c nếu ko tham gia BH -->
										<td>-</td><!-- P/c laptop cá nhân -->
										<td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
										<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
										<td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
										<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
										<td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
										<td>
											<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

										</td><!-- Tiền liên hoan -->
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
										<td><?php echo e(BatvHelper::formatPrice($total_item)); ?></td> <!-- các khoản khác -->
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
										<td></td><!-- Trả nợ định kỳ kỳ vay vốn -->
										<td>0</td><!-- Lương lần 1 -->
										<td>
											<?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trial_work+$val->management_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance+$val->mulct_money_awol-$val->party_fee)); ?>

											<?php  $total_salary_2 +=  round($val->salary_overtime + $val->salary_trial_work+$val->management_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance+$val->mulct_money_awol-$val->party_fee); ?>
										</td><!-- Lương lần 2 -->
										<td class="item-thucnhan"><?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trial_work+$val->management_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance+$total_item+$val->mulct_money_awol+ $val->holiday_bonus-$val->party_fee)); ?></td><!-- Thực nhận -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company + $val->insurance)); ?></td><!--  Tổng bảo hiểm -->
										<td class="item-dn"><?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trial_work+$val->management_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance +$total_item+$val->mulct_money_awol+ $val->holiday_bonus-$val->party_fee)); ?></td><!-- Doanh nghiệp phải chi trả -->
								    </tr>
								    <?php  $stt++; ?>
					     		<!-- Parttime -->
					     		<?php elseif( $val->salary_parttime_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_trainee_work == 0 && $val->salary_trainee_parttime_work == 0): ?>
								    <tr style="background: rgb(147, 147, 193);">
										<td style="min-width: 210px; text-align: left;">
										  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
										</td> 
										<td><?php echo e(BatvHelper::formatPrice($val->salary_parttime_default)); ?></td>
										<td><?php echo e(BatvHelper::getInfoRatioInSalary($dateCurrent,$val->personnel_id)); ?></td>
										<td>-</td>
										<td><?php echo e(BatvHelper::formatPrice( ($val->salary_parttime_default)/$standard_days )); ?></td>
										<td>-</td>
										<td>
											<?php
												echo $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											?>
										</td> <!-- Số ngày làm việc
										<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
										<td><?php echo e(BatvHelper::formatPrice($val->salary_parttime_work)); ?></td><!-- Lương chính thức -->
										<td>-</td><!-- M/l thử việc -->
										<td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
										<td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
										<td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
										<td><?php echo e(BatvHelper::formatPrice($val->other_tax_allowance)); ?></td><!-- P/c nếu ko tham gia BH -->
										<td><?php echo e(BatvHelper::formatPrice($val->laptop_allowance)); ?></td><!-- P/c laptop cá nhân -->
										<td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
										<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
										<td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
										<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
										<td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
										<td>
											<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

										</td><!-- Tiền liên hoan -->
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
										<td>
											<?php echo e(BatvHelper::formatPrice($total_item)); ?> <!-- các khoản khác -->
										</td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
										<td></td><!-- Trả nợ định kỳ kỳ vay vốn -->
										<td>0</td><!-- Lương lần 1 -->
										<td>
											<?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol-$val->party_fee)); ?>

											<?php  $total_salary_2 +=  round($val->salary_overtime + $val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol-$val->party_fee); ?>
										</td>
										<td class="item-thucnhan"><?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Thực nhận -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company + $val->insurance)); ?></td><!--  Tổng bảo hiểm -->
										<td class="item-dn"><?php echo e(BatvHelper::formatPrice($val->salary_overtime +  $val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->parking_fee_allowance +$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Doanh nghiệp phải chi trả -->
								    </tr>
								    <?php  $stt++; ?>
					     		<!-- Thực tập fulltime -->
					     		<?php elseif( $val->salary_trainee_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_parttime_work == 0): ?>
								    <tr style="background: rgba(240, 173, 78, 0.66);">
										<td style="min-width: 210px; text-align: left;">
										  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
										</td> 
										<td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_default)); ?></td>
										<td><?php echo e(BatvHelper::getInfoRatioInSalary($dateCurrent,$val->personnel_id)); ?></td>
										<td>-</td>
										<td><?php echo e(BatvHelper::formatPrice( ($val->salary_trainee_default)/$standard_days )); ?></td>
										<td>-</td>
										<td>
											<?php
												echo $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											?>
										</td> <!-- Số ngày làm việc-->
										<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
										<td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_work)); ?></td><!-- Lương chính thức -->
										<td>-</td><!-- M/l thử việc -->
										<td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
										<td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
										<td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
										<td><?php echo e(BatvHelper::formatPrice($val->other_tax_allowance)); ?></td><!-- P/c nếu ko tham gia BH -->
										<td><?php echo e(BatvHelper::formatPrice($val->laptop_allowance)); ?></td><!-- P/c laptop cá nhân -->
										<td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
										<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
										<td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
										<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
										<td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
										<td>
											<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

										</td><!-- Tiền liên hoan -->
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
										<td>
											<?php echo e(BatvHelper::formatPrice($total_item)); ?> <!-- các khoản khác -->
										</td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
										<td></td><!-- Trả nợ định kỳ kỳ vay vốn -->
										<td>0</td><!-- Lương lần 1 -->
										<td>
											<?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trainee_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol-$val->party_fee)); ?>

											<?php  $total_salary_2 +=  round($val->salary_overtime + $val->salary_trainee_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol-$val->party_fee); ?>
										</td>
										<td class="item-thucnhan"><?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trainee_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Thực nhận -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company + $val->insurance)); ?></td><!--  Tổng bảo hiểm -->
										<td class="item-dn"><?php echo e(BatvHelper::formatPrice($val->salary_overtime +  $val->salary_trainee_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->parking_fee_allowance +$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Doanh nghiệp phải chi trả -->
								    </tr>
								    <?php  $stt++; ?>
                                <!-- Thực tập parttime -->
                                <?php elseif( $val->salary_trainee_parttime_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0): ?>
                                    <tr style="background: rgba(240, 173, 78, 0.34);">
										<td style="min-width: 210px; text-align: left;">
										  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
										</td> 
                                        <td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_parttime_default)); ?></td>
										<td><?php echo e(BatvHelper::getInfoRatioInSalary($dateCurrent,$val->personnel_id)); ?></td>
                                        <td>-</td>
                                        <td><?php echo e(BatvHelper::formatPrice( ($val->salary_trainee_parttime_default)/$standard_days )); ?></td>
                                        <td>-</td>
                                       <td>  
											<?php
												echo $nctt = BatvHelper::countAttendanceNormal($month,$year,$val->personnel_id,1,$date_final_month);
											?>   
                                        </td> <!-- Số ngày làm việc -->
                                        <td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_parttime_work)); ?></td><!-- Lương chính thức -->
                                        <td>-</td><!-- M/l thử việc -->
										<!-- <td>6</td> --><!-- P/c khi không tham gia BH-->
                                        <td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->other_tax_allowance)); ?></td><!-- P/c nếu ko tham gia BH -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->laptop_allowance)); ?></td><!-- P/c laptop cá nhân -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
										<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
                                        <td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
                                        <td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
                                        <td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
                                        <td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
										<td>
											<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

										</td><!-- Tiền liên hoan -->
                                        <td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
                                        <td>
											<?php echo e(BatvHelper::formatPrice($total_item)); ?> <!-- các khoản khác -->
                                        </td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
										<td></td><!-- Trả nợ định kỳ kỳ vay vốn -->
										<td>0</td><!-- Lương lần 1 -->
										<td>
											<?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trainee_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol-$val->party_fee)); ?>

											<?php  $total_salary_2 +=  round($val->salary_overtime + $val->salary_trainee_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol-$val->party_fee); ?>
										</td>
                                        <td class="item-thucnhan"><?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trainee_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Thực nhận -->
										<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company + $val->insurance)); ?></td><!--  Tổng bảo hiểm -->
                                        <td class="item-dn"><?php echo e(BatvHelper::formatPrice( $val->salary_overtime + $val->salary_trainee_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->parking_fee_allowance +$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Doanh nghiệp phải chi trả -->
                                    </tr>
								    <!-- Nửa này nửa kia -->
								    <?php  $stt++; ?>
							    <?php else: ?>
							    	<?php
							    		$getContractsPersonnelbyUser = BatvHelper::getContractsPersonnelbyUser($time, $val->personnel_id);

							    		$nctt_0 = $nctt_1 = 0;
							    		// salary_trial_work : Hợp đồng thử việc
							    		// salary_trainee_work : Thực tập Full time
							    		// salary_parttime_work : Partime
							    		// salary_trainee_parttime_work : Thực tập Partime
							    		// salary_official_work : Hợp đồng chính thức
							    	?>
								    	<?php if( $val->salary_trial_work >0 && ( $val->salary_trainee_work >0 || $val->salary_trainee_parttime_work >0  || $val->salary_official_work >0 || $val->salary_parttime_work >0 ) ): ?>
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
										     <tr style="background: #aadcaa;" >
												<td style="min-width: 210px; text-align: left;border-bottom: 1px solid #fff;">
												  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
												</td> 
												<td><?php echo e(BatvHelper::formatPrice($val->salary_trial_default)); ?></td>
												<td><?php echo e($ratio_1); ?></td>
												<td><?php echo e(BatvHelper::formatPrice( BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default) )); ?></td>
												<td>-</td>
												<td><?php echo e(BatvHelper::formatPrice( (BatvHelper::infoConfigSettingOthers(0)*($val->salary_trial_default))/$standard_days )); ?></td>
												<td><?php echo e($nctt_0); ?></td>
												<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
												<td>-</td><!-- Lương chính thức -->
												<td><?php echo e(BatvHelper::formatPrice($val->salary_trial_work)); ?></td><!-- M/l thử việc -->
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
												<td>-</td><!-- Phạt đi làm muộn -->
												<td>-</td><!-- Quỹ phúc lợi -->
												<td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
												<td>
													<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

												</td><!-- Tiền liên hoan -->
												<td> -</td>
												<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
												<td> -</td> <!-- các khoản khác -->
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
												<td rowspan="2" style="padding: 25px 0;"></td><!-- Trả nợ định kỳ kỳ vay vốn -->
												<td>0</td><!-- Lương lần 1 -->
												<td rowspan="2" style="padding: 25px 0;">
													<?php echo e(BatvHelper::formatPrice( $val->salary_trial_work+$val->parking_fee_allowance-$val->party_fee + $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol)); ?>

													<?php  
														$total_salary_2 +=  round($val->salary_trial_work+$val->parking_fee_allowance -$val->party_fee + $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol); 
													?>
												</td>
												<td rowspan="2" style="padding: 25px 0;" class="item-thucnhan"><?php echo e(BatvHelper::formatPrice( $val->salary_trial_work+$val->parking_fee_allowance +$val->holiday_bonus-$val->party_fee + $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol-$val->party_fee)); ?></td><!-- Thực nhận -->
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->insurance_by_company + $val->insurance)); ?></td><!-- Tổng bảo hiểm -->
												<td  rowspan="2" style="padding: 25px 0;" class="item-dn"><?php echo e(BatvHelper::formatPrice( $val->salary_trial_work+$work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$total_item+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol+$val->parking_fee_allowance+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Doanh nghiệp phải chi trả -->
										    </tr>
										    <tr style="background: #aadcaa;">
										    	<td></td>
												<td><?php echo e(BatvHelper::formatPrice($default)); ?></td>
												<td><?php echo e($ratio_2); ?></td>
												<td>-</td>
												<td><?php echo e(BatvHelper::formatPrice( ($default)/$standard_days )); ?></td>
												<td>-</td>
												<td><?php echo e($nctt_1); ?></td>
												<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
												<td><?php echo e(BatvHelper::formatPrice($work)); ?></td><!-- Lương chính thức -->
												<td>-</td><!-- M/l thử việc -->
												<td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
												<td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
												<td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
												<td><?php echo e(BatvHelper::formatPrice($val->other_tax_allowance)); ?></td><!-- P/c nếu ko tham gia BH -->
												<td><?php echo e(BatvHelper::formatPrice($val->laptop_allowance)); ?></td><!-- P/c laptop cá nhân -->
												<td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
												<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
												<td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
												<td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
												<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
												<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
												<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
												<td>-</td><!-- Thanh toán tiền gửi xe -->
												<td></td>
												<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td>
												<td>-</td><!-- Thưởng ngày lễ -->
												<td>
												    <?php echo e(BatvHelper::formatPrice($total_item)); ?><!-- các khoản khác -->
												</td>
												<td></td>
												<td></td>
												<td></td>
												<td><?php echo e(BatvHelper::formatPrice( $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol)); ?></td><!-- Thực nhận -->

										    </tr>
										    <?php  $stt++; ?>
								    	<?php endif; ?>
								    	<?php if( $val->salary_trainee_work >0 && ( $val->salary_official_work >0 || $val->salary_trainee_parttime_work >0  || $val->salary_parttime_work >0 ) ): ?>
											<?php
									    		foreach ($getContractsPersonnelbyUser as $key => $value) {
								    				if ($key == 0) {
								    					$day_last_attendance = explode("-",$value->apply_to);
								    					$day_last_attendance = $day_last_attendance[2];

										    			if ($value->contract_id == 3) { // Thực tập Full time
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
										     <tr style="background: #aadcaa;" >
												<td style="min-width: 210px; text-align: left;border-bottom: 1px solid #fff;">
												  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
												</td> 
												<td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_default)); ?></td>
												<td><?php echo e($ratio_1); ?></td>
												<td>-</td>
												<td><?php echo e(BatvHelper::formatPrice( ($val->salary_trainee_default)/$standard_days )); ?></td>
												<td>-</td>
                                                <td><?php echo e($nctt_0); ?></td>
												<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
												<td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_work)); ?></td><!-- Lương chính thức -->
												<td>-</td><!-- M/l thử việc -->
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
												<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
												<td>-</td><!-- Quỹ phúc lợi -->
												<td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
												<td>
													<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

												</td><!-- Tiền liên hoan -->
												<td> -</td>
												<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
												<td> -</td> <!-- các khoản khác -->
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
												<td rowspan="2" style="padding: 25px 0;"></td><!-- Trả nợ định kỳ kỳ vay vốn -->
												<td>0</td><!-- Lương lần 1 -->
												<td rowspan="2" style="position: relative;top: 15px;">
													<?php echo e(BatvHelper::formatPrice( $val->salary_trainee_work+$work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol +$val->parking_fee_allowance-$val->party_fee)); ?>

													<?php  
														$total_salary_2 += round( $val->salary_trainee_work+$work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol +$val->parking_fee_allowance-$val->party_fee); 
													?>
												</td>
												<td class="item-thucnhan"><?php echo e(BatvHelper::formatPrice( $val->salary_trainee_work +$val->holiday_bonus-$val->party_fee)); ?></td><!-- Thực nhận -->
												<td rowspan="2" style="position: relative;top: 15px;">0</td><!-- Tổng bảo hiểm -->
												<td  rowspan="2" style="position: relative;top: 15px;"  class="item-dn"><?php echo e(BatvHelper::formatPrice( $val->salary_trainee_work+$work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$total_item+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol +$val->parking_fee_allowance+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Doanh nghiệp phải chi trả -->

										    </tr>
										    <tr style="background: #aadcaa;">
										    	<td></td>
												<td><?php echo e(BatvHelper::formatPrice($default)); ?></td>
												<td><?php echo e($ratio_2); ?></td>
												<td>-</td>
												<td><?php echo e(BatvHelper::formatPrice( ($default)/$standard_days )); ?></td>
												<td>-</td>
                                                <td><?php echo e($nctt_1); ?></td>
												<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
												<td><?php echo e(BatvHelper::formatPrice($work)); ?></td><!-- Lương chính thức -->
												<td>-</td><!-- M/l thử việc -->
												<td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
												<td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
												<td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
												<td><?php echo e(BatvHelper::formatPrice($val->other_tax_allowance)); ?></td><!-- P/c nếu ko tham gia BH -->
												<td><?php echo e(BatvHelper::formatPrice($val->laptop_allowance)); ?></td><!-- P/c laptop cá nhân -->
												<td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
												<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
												<td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
												<td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
												<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
												<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
												<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
												<td>-</td><!-- Thanh toán tiền gửi xe -->
												<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td>
												<td>-</td><!-- Thưởng ngày lễ -->
												<td>
												    <?php echo e(BatvHelper::formatPrice($total_item)); ?> <!-- các khoản khác -->
												</td>
												<td></td>
												<td></td>
												<td></td>
												<td><?php echo e(BatvHelper::formatPrice( $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol)); ?></td><!-- Thực nhận -->

										    </tr>
										    <?php  $stt++; ?>
								    	<?php endif; ?>
								    	<?php if( $val->salary_parttime_work >0 && ( $val->salary_official_work >0 || $val->salary_trainee_parttime_work >0  )  ): ?>
											<?php
									    		foreach ($getContractsPersonnelbyUser as $key => $value) {
								    				if ($key == 0) {
								    					$day_last_attendance = explode("-",$value->apply_to);
								    					$day_last_attendance = $day_last_attendance[2];

										    			if ($value->contract_id == 4) { // Partime
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
										     <tr style="background: #aadcaa;" >
												<td style="min-width: 210px; text-align: left;border-bottom: 1px solid #fff;">
												  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
												</td> 
												<td><?php echo e(BatvHelper::formatPrice($val->salary_parttime_default)); ?></td>
												<td><?php echo e($ratio_1); ?></td>
												<td>-</td>
												<td><?php echo e(BatvHelper::formatPrice( ($val->salary_parttime_default)/$standard_days )); ?></td>
												<td>-</td>
                                                <td><?php echo e($nctt_0); ?></td>
												<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
												<td><?php echo e(BatvHelper::formatPrice($val->salary_parttime_work)); ?></td><!-- Lương chính thức -->
												<td>-</td><!-- M/l thử việc -->
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
												<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
												<td>-</td><!-- Quỹ phúc lợi -->
												<td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
												<td>
													<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

												</td><!-- Tiền liên hoan -->
												<td> -</td>
												<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
												<td> -</td> <!-- các khoản khác -->
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
												<td rowspan="2" style="padding: 25px 0;"></td><!-- Trả nợ định kỳ kỳ vay vốn -->
												<td>0</td><!-- Lương lần 1 -->
												<td rowspan="2" style="position: relative;top: 15px;">
														<?php echo e(BatvHelper::formatPrice( $val->salary_parttime_work+$work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol+$val->parking_fee_allowance-$val->party_fee)); ?>

														<?php  
														$total_salary_2 +=  round( $val->salary_parttime_work+$work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol+$val->parking_fee_allowance-$val->party_fee); 
														?>
												</td>
												<td  class="item-thucnhan"><?php echo e(BatvHelper::formatPrice( $val->salary_parttime_work +$val->holiday_bonus-$val->party_fee)); ?></td><!-- Thực nhận -->
												<td rowspan="2" style="position: relative;top: 15px;">0</td><!-- Tổng bảo hiểm -->
												<td rowspan="2" style="position: relative;top: 15px;"  class="item-dn"><?php echo e(BatvHelper::formatPrice( $val->salary_parttime_work+$work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$total_item+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol+$val->parking_fee_allowance+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Doanh nghiệp phải chi trả -->

										    </tr>
										    <tr style="background: #aadcaa;">
										    	<td></td>
												<td><?php echo e(BatvHelper::formatPrice($default)); ?></td>
												<td><?php echo e($ratio_2); ?></td>
												<td>-</td>
												<td><?php echo e(BatvHelper::formatPrice( ($default)/$standard_days )); ?></td>
												<td>-</td>
                                                <td><?php echo e($nctt_1); ?></td>
												<td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
												<td><?php echo e(BatvHelper::formatPrice($work)); ?></td><!-- Lương chính thức -->
												<td>-</td><!-- M/l thử việc -->
												<td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
												<td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
												<td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
												<td><?php echo e(BatvHelper::formatPrice($val->other_tax_allowance)); ?></td><!-- P/c nếu ko tham gia BH -->
												<td><?php echo e(BatvHelper::formatPrice($val->laptop_allowance)); ?></td><!-- P/c laptop cá nhân -->
												<td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
												<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
												<td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
												<td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
												<td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
												<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
												<td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
												<td>-</td><!-- Thanh toán tiền gửi xe -->
												<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td>
												<td>-</td><!-- Thưởng ngày lễ -->
												<td>
												    <?php echo e(BatvHelper::formatPrice($total_item)); ?> <!-- các khoản khác -->
												</td>
												<td></td>
												<td></td>
												<td></td>
												<td><?php echo e(BatvHelper::formatPrice( $work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol)); ?></td><!-- Thực nhận -->

										    </tr>
										    <?php  $stt++; ?>
								    	<?php endif; ?>	
                                        <?php if( $val->salary_trainee_parttime_work >0 && $val->salary_official_work >0 ): ?>
                                        	<?php
									    		foreach ($getContractsPersonnelbyUser as $key => $value) {
								    				if ($key == 0) {
								    					$day_last_attendance = explode("-",$value->apply_to);
								    					$day_last_attendance = $day_last_attendance[2];

										    			if ($value->contract_id == 5) { // Thực tập Partime
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
												<td style="min-width: 210px; text-align: left;border-bottom: 1px solid #fff;">
												  <span style="padding-right: 10px;"><?php echo e($stt); ?>.</span> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> 
												</td> 
                                                <td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_parttime_default)); ?></td>
												<td><?php echo e($ratio_1); ?></td>
                                                <td>-</td>
                                                <td><?php echo e(BatvHelper::formatPrice( ($val->salary_trainee_parttime_default)/$standard_days )); ?></td>
                                                <td>-</td>
                                                <td><?php echo e($nctt_0); ?></td>
                                                <td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_parttime_work)); ?></td><!-- Lương chính thức -->
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
                                                <td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
                                                <td>-</td><!-- Quỹ phúc lợi -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->parking_fee_allowance)); ?></td><!-- Thanh toán tiền gửi xe -->
												<td>
													<?php  if( $val->party_fee > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->party_fee)); ?>

												</td><!-- Tiền liên hoan -->
                                                <td> -</td>
												<td><?php echo e(BatvHelper::formatPrice($val->holiday_bonus)); ?></td><!-- Thưởng ngày lễ -->
                                                <td> -</td> <!-- các khoản khác -->
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td><!-- Lương làm thêm giờ -->
												<td rowspan="2" style="padding: 25px 0;"></td><!-- Trả nợ định kỳ kỳ vay vốn -->
												<td>0</td><!-- Lương lần 1 -->
												<td rowspan="2" style="position: relative;top: 15px;">
													<?php echo e(BatvHelper::formatPrice( $val->salary_trainee_parttime_work+$salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol+$val->parking_fee_allowance-$val->party_fee)); ?>

													<?php  
														$total_salary_2 += round($val->salary_trainee_parttime_work+$salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol+$val->parking_fee_allowance-$val->party_fee); 
													?>
												</td>
                                                <td class="item-thucnhan"><?php echo e(BatvHelper::formatPrice( $val->salary_trainee_parttime_work +$val->holiday_bonus-$val->party_fee)); ?></td><!-- Thực nhận -->
                                                <td rowspan="2" style="position: relative;top: 15px;">0</td><!-- Tổng bảo hiểm -->
                                                <td rowspan="2" style="position: relative;top: 15px;"  class="item-dn"><?php echo e(BatvHelper::formatPrice( $val->salary_trainee_parttime_work+$salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus+$val->insurance_by_company-$val->money_work_late+$total_item+$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol+$val->parking_fee_allowance+$val->holiday_bonus-$val->party_fee)); ?></td><!-- Doanh nghiệp phải chi trả -->

                                            </tr>
                                            <tr style="background: #aadcaa;">
                                            	<td></td>
                                                <td><?php echo e(BatvHelper::formatPrice($val->salary_official_default)); ?></td>
												<td><?php echo e($ratio_2); ?></td>
                                                <td>-</td>
                                                <td><?php echo e(BatvHelper::formatPrice( ($val->salary_official_default)/$standard_days )); ?></td>
                                                <td>-</td>
                                                <td><?php echo e($nctt_1); ?></td>
                                                <td><?php echo e($standard_days); ?></td><!-- Ngày công tiêu chuẩn -->
                                                <td><?php echo e(BatvHelper::formatPrice($work)); ?></td><!-- Lương chính thức -->
                                                <td>-</td><!-- M/l thử việc -->
												<!-- <td>6</td> --><!-- P/c khi không tham gia BH-->
                                                <td><?php echo e(BatvHelper::formatPrice($val->management_allowance)); ?></td><!-- P/c trách nhiệm -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->lunch_allowance)); ?></td><!-- P/c ăn trưa -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->travel_allowance)); ?></td><!-- P/c xăng xe -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->other_tax_allowance)); ?></td><!-- P/c nếu ko tham gia BH -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->laptop_allowance)); ?></td><!-- P/c laptop cá nhân -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->phone_allowance)); ?></td><!-- P/c điện thoại 3G -->
												<td><?php echo e(BatvHelper::formatPrice($val->movement_allowance)); ?></td>
                                                <td><?php echo e(BatvHelper::formatPrice($val->work_bonus)); ?></td><!-- Thưởng dự án -->
                                                <td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td><!-- Người lao động phải đóng bảo hiểm -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->insurance_by_company)); ?></td><!-- Doanh nghiệp phải đóng bảo hiểm -->
                                                <td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td><!-- Phạt đi làm muộn -->
                                                <td><?php  if( $val->welfare_fund > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->welfare_fund)); ?></td><!-- Quỹ phúc lợi -->
                                                <td>-</td><!-- Thanh toán tiền gửi xe -->
                                                <td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol)); ?></td>
												<td>-</td><!-- Thưởng ngày lễ -->
                                                <td>
                                                    <?php echo e(BatvHelper::formatPrice($total_item)); ?> <!-- các khoản khác -->
                                                </td>
												<td></td>
												<td></td>
												<td></td>
                                                <td><?php echo e(BatvHelper::formatPrice( $salary_official_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$total_item +$val->other_tax_allowance+$val->laptop_allowance +$val->mulct_money_awol)); ?></td><!-- Thực nhận -->

                                            </tr>
                                            <?php  $stt++; ?>
                                        <?php endif; ?>
						    	<?php endif; ?>

					    	<?php endforeach; ?>
					    					<tr style="background: rgba(255, 0, 0, 0.56);">
					    						<td><b>TỔNG HỢP</b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_salary_official_default+$total_salary_trial_default_special+$total_salary_trainee_parttime_default+$total_salary_trainee_default+$total_salary_parttime_default)); ?></b></td>
					    						<td></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_salary_trial_default)); ?></b></td>
					    						<td><b></b></td>
					    						<td><b></b></td>
					    						<td><b></b></td>
					    						<td><b></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_salary_official_work+$total_salary_trainee_parttime_work+$total_salary_trainee_work+$total_salary_parttime_work)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_salary_trial_work)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_management_allowance)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_lunch_allowance)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_travel_allowance)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_other_tax_allowance)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_laptop_allowance)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_phone_allowance)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_movement_allowance)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_work_bonus)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_insurance)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_insurance_by_company)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_money_work_late)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_welfare_fund)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_parking_fee_allowance)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_party_fee)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_mulct_money_awol)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_holiday_bonus)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_salary_overtime)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_pay_month_loan_capital)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_salary_1)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_salary_2)); ?></b></td>
					    						<td><b class="sum-thucnhan"></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($all_insurance)); ?></b></td>
					    						<td><b class="sum-dn"></b></td>
					    					</tr>
					    <?php endif; ?>
			        </tbody>
			    </table>
			</div>
			<div class="alert alert-info">
			  <strong>Doanh nghiệp chi trả = Tổng bảo hiểm + Thực nhận + ABS (Quỹ phúc lợi)</strong>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					var elements_thucnhan = document.getElementsByClassName('item-thucnhan');
					var total_thucnhan = 0;

					for (var i = 0; i < elements_thucnhan.length; i++) {
						var value = parseFloat(elements_thucnhan[i].innerHTML.replace(/,/g, ''));
						total_thucnhan += value;
					}

					$('.sum-thucnhan').html(formatNumber(total_thucnhan, '.', ','));


					var elements_dn = document.getElementsByClassName('item-dn');
					var total_dn = 0;

					for (var i = 0; i < elements_dn.length; i++) {
						var value = parseFloat(elements_dn[i].innerHTML.replace(/,/g, ''));
						total_dn += value;
					}

					$('.sum-dn').html(formatNumber(total_dn, '.', ','));


					$('.box_salary table').on('scroll', function () {
						
					    $(".box_salary table > *").width($("table").width() + $("table").scrollLeft());
					});
					var $stickyHeader = $('.box_salary table thead tr th:first-child');
					var $stickyCells = $('.box_salary table tbody tr td:first-child');

					$('.box_salary table').on('scroll', function () {
					    $stickyHeader.css('left', ($(this).scrollLeft()+'px'));
					    $stickyCells.css('left', ($(this).scrollLeft()+'px'));
					});
				});
				
				$('body').on('click','.done_status_bonus',function(){
					if (confirm('Bạn có chắc chắn muốn Chốt thưởng?')) {
						var link = "<?php echo e(route('getSalaryBonusDoneAjax')); ?>";
						var data = {
								selectMonth:<?php echo isset($_GET['selectMonth'])?$_GET['selectMonth']:date('m') ?>,
								selectYear:<?php echo isset($_GET['selectYear'])?$_GET['selectYear']:date('Y') ?>,
							};
						$.ajax({
							url: link, //Relative or absolute path to response.php file
							data: data,
				            beforeSend: function() {
				                $("#pre_ajax_loading").show();
				            },
				            complete: function() {
				                // $("#pre_ajax_loading").hide();
				                $(".result-alert").show();
				            },
					        success: function (response) {
								var obj = $.parseJSON(response);
								if(obj.Response=='Error')
								{
									$(".ajax_response").removeClass('alert-success').addClass("alert-danger");
									$(".ajax_response").html(obj.Error);
									$(".ajax_response").show('slow');
								}else{
									// $(".ajax_response").removeClass('alert-danger').addClass("alert-success");
									// $(".ajax_response").html(obj.Message);
									// $(".ajax_response").show('slow');
									// setTimeout(function() {
									// 	window.location.reload();
									// }, 2000);
									window.location.reload();
								}
					        },
					        error: function (data) {
					            console.log('Error:', data);
					        }
						});
					}
				})


				$('body').on('click','.done',function(){
					if (confirm('Bạn có chắc chắn muốn Chốt lương tháng này?')) {
						var link = "<?php echo e(route('getSalaryDoneAjax')); ?>";
						var data = {
								selectMonth:<?php echo isset($_GET['selectMonth'])?$_GET['selectMonth']:date('m') ?>,
								selectYear:<?php echo isset($_GET['selectYear'])?$_GET['selectYear']:date('Y') ?>,
							};
						$.ajax({
							url: link, //Relative or absolute path to response.php file
							data: data,
				            beforeSend: function() {
				                $("#pre_ajax_loading").show();
				            },
				            complete: function() {
				                // $("#pre_ajax_loading").hide();
				                $(".result-alert").show();
				            },
					        success: function (response) {
								var obj = $.parseJSON(response);
								if(obj.Response=='Error')
								{
									$(".ajax_response").removeClass('alert-success').addClass("alert-danger");
									$(".ajax_response").html(obj.Error);
									$(".ajax_response").show('slow');
								}else{
									// $(".ajax_response").removeClass('alert-danger').addClass("alert-success");
									// $(".ajax_response").html(obj.Message);
									// $(".ajax_response").show('slow');
									// setTimeout(function() {
									// 	window.location.reload();
									// }, 2000);
									window.location.reload();
								}
					        },
					        error: function (data) {
					            console.log('Error:', data);
					        }
						});
					}
				})

				$('body').on('click','.r-done',function(){
					if (confirm('Lưu ý: Nếu đã trả lương cho nhân viên rồi mà tính lại có thể sẽ có sự thay đổi. Bạn vẫn muốn tiếp tục ?')) {
						var link = "<?php echo e(route('getSalaryRecalCulationAjax')); ?>";
						var data = {
								selectMonth:<?php echo isset($_GET['selectMonth'])?$_GET['selectMonth']:date('m') ?>,
								selectYear:<?php echo isset($_GET['selectYear'])?$_GET['selectYear']:date('Y') ?>,
							};
						$.ajax({
							url: link, //Relative or absolute path to response.php file
							data: data,
				            beforeSend: function() {
				                $("#pre_ajax_loading").show();
				            },
				            complete: function() {
				                // $("#pre_ajax_loading").hide();
				                $(".result-alert").show();
				            },
					        success: function (response) {
								var obj = $.parseJSON(response);
								if(obj.Response=='Error')
								{
									$(".ajax_response").removeClass('alert-success').addClass("alert-danger");
									$(".ajax_response").html(obj.Error);
									$(".ajax_response").show('slow');
								}else{
									window.location.reload();
								}
					        },
					        error: function (data) {
					            console.log('Error:', data);
					        }
						});
					}
				})
			</script>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>