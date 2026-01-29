

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>

<div class="row box_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.client.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<div class="col-lg-10">
			<h4 class="title-fuction">Thông tin Thuế &amp; Bảo hiểm</h4>
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
						Thuế &amp; Bảo hiểm
						<?php
							echo isset( $_GET['selectMonth'] )?$_GET['selectMonth']. "/":date('m') . "/" ;
							echo isset( $_GET['selectYear'] )?$_GET['selectYear']:date('Y');
						?>
				</h4>
				<div class="table-responsive" >
				    <table class="table table-bordered table-striped">
				        <thead>
				            <tr>
				                <th class="text-center">Họ và tên</th>
				                <th class="text-center"> <small>Thuế TNCN</small></th>
				                <th class="text-center"> <small>Bảo hiểm(nhân viên phải đóng)</small></th>
				            </tr>
				        </thead>
				        <tbody>
						    <?php if(!empty($data)): ?>
						     	<?php foreach($data as $val): ?>
								    <tr>
								      	<td class="text-nowrap" scope="row"> <?php echo e(str_limit( $val->fullname, $limit = 35, $end = '...')); ?> </td> 
										<td><?php  if( $val->tax > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->tax)); ?></td>
										<td><?php  if( $val->insurance > 0 ) echo "-"; ?><?php echo e(BatvHelper::formatPrice($val->insurance)); ?></td>
								    </tr>
							    <?php endforeach; ?>
						    <?php endif; ?>
				        </tbody>
				    </table>
				</div>
				<?php echo e(csrf_field()); ?>

			</form>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>