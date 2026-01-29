

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>

<div class="row setting_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<div class="col-lg-10">
			<h4 class="title-fuction">Sửa cấu hình công thức </h4> 
			<?php if(count($errors) > 0): ?>
				<div class="alert alert-danger" role="alert">
				<ul>
				    <?php foreach($errors->all() as $error): ?>
				        <li><?php echo e($error); ?></li>
				    <?php endforeach; ?>
				</ul>
				</div>
			<?php endif; ?>
			<?php if(session('flash_message_succ') != ''): ?>
				 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
			<?php endif; ?>
			<div class="row">
				<?php echo $__env->make('layouts.luongthuong.menusetting', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<div class="col-lg-12">
					<form class="form-horizontal" method="post" action="" >
						<?php echo e(csrf_field()); ?>

						<div class="form-group">
							<label class="col-sm-offset-1 col-sm-2 control-label">Tiêu đề <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="title" value="<?php echo e(old('title',isset($data->title) ? $data->title: null )); ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-offset-1 col-sm-2 control-label">Ngày <span class="required">*</span></label>
							<div class="col-sm-2">
								<select name="selectDay" class="form-control">
									 <?php 
						                for ($i = 1; $i <= 31; $i++){
										    $day = ($i < 10) ? '0'.$i : $i ;
										    echo '<option value="'.$day.'"';
										    if (!empty(Request::input('selectDay')) || isset($data->day) ) {
										    	if ($i == Request::input('selectDay') || $data->day ==$i) echo ' selected="selected"';
										    }else{
										    	if ($i == date("d")) echo ' selected="selected"';
										    }						    
										    echo '>'.$day.'</option>';
										}
									 ?>
								</select>
							</div>
							<label class="col-sm-1 control-label">Tháng <span class="required">*</span></label>
							<div class="col-sm-2">
								<select name="selectMonth" class="form-control">
									 <?php 
						                for ($i = 1; $i <= 12; $i++){
										    $month = ($i < 10) ? '0'.$i : $i ;
										    echo '<option value="'.$month.'"';
										    if (!empty(Request::input('selectMonth')) || isset($data->month) ) {
										    	if ($i == Request::input('selectMonth') || $data->month ==$i) echo ' selected="selected"';
										    }else{
										    	if ($i == date("n")) echo ' selected="selected"';
										    }						    
										    echo '>'.$month.'</option>';
										}
									 ?>
								</select>
							</div>
							<label class="col-sm-1 control-label">Năm <span class="required">*</span></label>
							<div class="col-sm-2">
								<select name="selectYear" class="form-control">
										<option value="*">*</option>
									<?php
										for($i=date("Y")-5;$i<=date("Y")+2;$i++) {
											 if (!empty(Request::input('selectYear')) || isset($data->year) ) {
										    	$sel = ($i == Request::input('selectYear')|| $data->year ==$i ) ? 'selected' : '';
										    }else{
										    	$sel = ($i == date('Y')) ? 'selected' : '';
										    }	   
										    echo "<option value=".$i." ".$sel.">".$i."</option>";  // here I have changed      
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-offset-1 col-sm-2 control-label">Lý do </label>
							<div class="col-sm-8">
								<textarea rows="4" onkeydown="expandtext(this);" name="reason" class="form-control"><?php echo e(old('reason',isset($data->reason) ? $data->reason: null )); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-6">
								<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
							</div>
						</div>
					</form>
				</div>
			</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>