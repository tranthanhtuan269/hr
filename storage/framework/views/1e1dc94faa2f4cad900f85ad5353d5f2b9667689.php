

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>

<div class="row box_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

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
			  <?php if(count($errors) > 0): ?>
		      <div class="alert alert-danger" role="alert">
		        <ul>
		            <?php foreach($errors->all() as $error): ?>
		                <li><?php echo e($error); ?></li>
		            <?php endforeach; ?>
		        </ul>
		      </div>
		      <?php endif; ?>
		      <?php if(session('flash_message_err') != ''): ?>
				<div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
			  <?php endif; ?>
			  <?php if(session('flash_message_succ') != ''): ?>
		     	 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
		      <?php endif; ?>

		        <div id="pre_ajax_loading" style="display: none;text-align: center;"><img src="<?php echo e(asset('images/general/bx_loader.gif')); ?>"></div>
		        <div class="ajax_response" style="display: none;"></div>

				<h4 class="title-fuction">
						Thông tin lương tháng 
						<?php
							echo isset( $_GET['selectMonth'] )?$_GET['selectMonth']. "/":date('m') . "/" ;
							echo isset( $_GET['selectYear'] )?$_GET['selectYear']:date('Y');
						?>
					<div class="pull-right" >
						<?php if( empty($data) || $data[0]->status ==1): ?>
							<button type="button" class="btn btn-sm btn-orange special salary"><img src="<?php echo e(asset('images/general/calculator.png')); ?>"></button>
						<?php endif; ?>
					</div>
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
				<div class="table-responsive" id="parent">
				    <table id="fixTable" class="table table-bordered table-striped">
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
				    <?php if(!empty($data)): ?>
				    		<?php
				    			$total_salary_official_work = $total_salary_overtime = $total_salary_trial_work = $total_salary_parttime_work = $total_salary_trainee_work = $total_salary_trainee_parttime_work = $total_money_work_late = $total_mulct_money_awol =0 
				    		?>
					     	<?php foreach($data as $val): ?>
					    		<?php
					    			$total_salary_official_work += $val->salary_official_work;
					    			$total_salary_trial_work += $val->salary_trial_work;
					    			$total_salary_parttime_work += $val->salary_parttime_work;
					    			$total_salary_trainee_work += $val->salary_trainee_work;
									$total_salary_overtime += $val->salary_overtime;
					    			$total_salary_trainee_parttime_work += $val->salary_trainee_parttime_work;
					    			$total_money_work_late += $val->money_work_late;
									$total_mulct_money_awol += $val->mulct_money_awol;
					    		?>
					     		<!-- Chính thức -->
					     		<?php if( $val->salary_official_work > 0 && $val->salary_trial_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0 && $val->salary_trainee_parttime_work == 0 ): ?>
								    <tr style="background: #f5f5f5;">
								      	<td class="text-nowrap" scope="row"><?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?></td> 
										<td><?php echo e(BatvHelper::formatPrice($val->salary_official_work)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime + $val->salary_official_work-$val->money_work_late+$val->mulct_money_awol)); ?></td>
								    </tr>
							    <!-- Thử việc -->
							    <?php elseif( $val->salary_trial_work> 0 && $val->salary_official_work  == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0 && $val->salary_trainee_parttime_work == 0): ?>
								     <tr style="background: #5bc0de;" >
								      	<td class="text-nowrap" scope="row"><?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?></td> 
										<td><?php echo e(BatvHelper::formatPrice( $val->salary_trial_work )); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
										<td><?php echo e(BatvHelper::formatPrice( $val->salary_overtime + ( $val->salary_trial_work )-$val->money_work_late+$val->mulct_money_awol)); ?></td>
								    </tr>
					     		<!-- Parttime -->
					     		<?php elseif( $val->salary_parttime_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_trainee_work == 0 && $val->salary_trainee_parttime_work == 0): ?>
								    <tr style="background: rgb(147, 147, 193);">
								      	<td class="text-nowrap" scope="row"><?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?></td> 
										<td><?php echo e(BatvHelper::formatPrice($val->salary_parttime_work)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime + $val->salary_parttime_work-$val->money_work_late+$val->mulct_money_awol)); ?></td>
								    </tr>
					     		<!-- Thực tập fulltime -->
					     		<?php elseif( $val->salary_trainee_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_parttime_work == 0): ?>
								    <tr style="background: rgba(240, 173, 78, 0.66);">
								      	<td class="text-nowrap" scope="row"><?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?></td> 
										<td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_work)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime + $val->salary_trainee_work-$val->money_work_late+$val->mulct_money_awol)); ?></td>
								    </tr>
                                <!-- Thực tập parttime -->
                                <?php elseif( $val->salary_trainee_parttime_work > 0 && $val->salary_trial_work == 0 && $val->salary_official_work == 0 && $val->salary_parttime_work == 0 && $val->salary_trainee_work == 0): ?>
								    <tr style="background: rgba(240, 173, 78, 0.34);">
								      	<td class="text-nowrap" scope="row"><?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?></td> 
										<td><?php echo e(BatvHelper::formatPrice($val->salary_trainee_parttime_work)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
										<td><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
										<td><?php echo e(BatvHelper::formatPrice($val->salary_overtime + $val->salary_trainee_parttime_work-$val->money_work_late+$val->mulct_money_awol)); ?></td>
								    </tr>
							    <!-- Nửa này nửa kia -->
							    <?php else: ?>
								    	<?php if( $val->salary_trial_work >0 && ( $val->salary_trainee_work >0 || $val->salary_trainee_parttime_work >0  || $val->salary_official_work >0 || $val->salary_parttime_work >0 ) ): ?>
											<?php
												if( $val->salary_trainee_work >0 ){
													$work = $val->salary_trainee_work;
												}elseif( $val->salary_trainee_parttime_work >0 ){
													$work = $val->salary_trainee_parttime_work;
												}elseif( $val->salary_official_work >0 ){
													$work = $val->salary_official_work;
												}else{
													$work = $val->salary_parttime_work;
												}
											?>
										    <tr style="background: #aadcaa;" >
										     	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> </td> 
												<td><?php echo e(BatvHelper::formatPrice( ( $val->salary_trial_work ) )); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime + $work+$val->salary_trial_work-$val->money_work_late+$val->mulct_money_awol)); ?></td>

										    </tr>
										    <tr style="background: #aadcaa;">
												<td><?php echo e(BatvHelper::formatPrice($work)); ?></td>
										    </tr>
								    	<?php endif; ?>

								    	<?php if( $val->salary_trainee_work >0 && ( $val->salary_official_work >0 || $val->salary_trainee_parttime_work >0  || $val->salary_parttime_work >0 ) ): ?>
											<?php
												if( $val->salary_official_work >0 ){
													$work = $val->salary_official_work;
												}elseif ( $val->salary_trainee_parttime_work >0 ) {
													$work = $val->salary_trainee_parttime_work;
												}
												else{
													$work = $val->salary_parttime_work;
												}

											?>
										    <tr style="background: #aadcaa;" >
										     	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> </td> 
												<td><?php echo e(BatvHelper::formatPrice( ( $val->salary_trainee_work ) )); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime + $work+$val->salary_trainee_work-$val->money_work_late+$val->mulct_money_awol)); ?></td>

										    </tr>
										    <tr style="background: #aadcaa;">
												<td><?php echo e(BatvHelper::formatPrice($work)); ?></td>
										    </tr>
								    	<?php endif; ?>
								    	<?php if( $val->salary_parttime_work >0 && ( $val->salary_official_work >0 || $val->salary_trainee_parttime_work >0  )  ): ?>
											<?php
												if( $val->salary_official_work >0 ){
													$default = $val->salary_official_default;
													$work = $val->salary_official_work;
												}else{
													$default = $val->salary_trainee_parttime_default;
													$work = $val->salary_trainee_parttime_work;
												}

											?>
										    <tr style="background: #aadcaa;" >
										     	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> </td> 
												<td><?php echo e(BatvHelper::formatPrice( ( $val->salary_parttime_work ) )); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime + $val->salary_parttime_work+$work-$val->money_work_late+$val->mulct_money_awol)); ?></td>

										    </tr>
										    <tr style="background: #aadcaa;">
												<td><?php echo e(BatvHelper::formatPrice($work)); ?></td>
										    </tr>
								    	<?php endif; ?>

                                        <?php if( $val->salary_trainee_parttime_work >0 && $val->salary_official_work >0 ): ?>
										    <tr style="background: #aadcaa;" >
										     	<td class="text-nowrap" scope="row" rowspan="2" style="padding: 25px 0;"> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> </td> 
												<td><?php echo e(BatvHelper::formatPrice( ( $val->salary_trainee_parttime_work ) )); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime)); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php  if( $val->money_work_late > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->money_work_late)); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->mulct_money_awol )); ?></td>
												<td rowspan="2" style="padding: 25px 0;"><?php echo e(BatvHelper::formatPrice($val->salary_overtime + $val->salary_trainee_parttime_work+$val->salary_official_work-$val->money_work_late+$val->mulct_money_awol)); ?></td>

										    </tr>
										    <tr style="background: #aadcaa;">
												<td><?php echo e(BatvHelper::formatPrice($val->salary_official_work)); ?></td>
										    </tr>
                                        <?php endif; ?>
						    	<?php endif; ?>
					    	<?php endforeach; ?>
					    					<tr style="background: rgba(255, 0, 0, 0.56);">
					    						<td><b>TỔNG HỢP</b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice( $total_salary_official_work + $total_salary_trial_work + $total_salary_parttime_work + $total_salary_trainee_work + $total_salary_trainee_parttime_work  )); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_salary_overtime)); ?></b></td>
												<td><b><?php echo e(BatvHelper::formatPrice($total_money_work_late)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_mulct_money_awol)); ?></b></td>
					    						<td><b><?php echo e(BatvHelper::formatPrice($total_salary_overtime + $total_salary_official_work + $total_salary_trial_work + $total_salary_parttime_work + $total_salary_trainee_work + $total_salary_trainee_parttime_work -$total_money_work_late+$total_mulct_money_awol)); ?></b></td>
					    					</tr>
					    <?php endif; ?>
				        </tbody>
				    </table>
				</div>
				<?php echo e(csrf_field()); ?>

			</form>
			<script type="text/javascript">
				$('body').on('click','.special.salary',function(){
					var link = "<?php echo e(route('getSalaryAjax')); ?>";
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
			                $("#pre_ajax_loading").hide();
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
								$(".ajax_response").removeClass('alert-danger').addClass("alert-success");
								$(".ajax_response").html(obj.Message);
								$(".ajax_response").show('slow');
								setTimeout(function() {
									window.location.reload();
								}, 1500);
							}
				        },
				        error: function (data) {
				            console.log('Error:', data);
				        }
					});
				})

			</script>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>