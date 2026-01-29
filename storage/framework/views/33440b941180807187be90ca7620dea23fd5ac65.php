

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
	.setting_salary .reference{ display: none; }
</style>
<div class="row setting_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<div class="col-lg-10">
			<h4 class="title-fuction">Cấu hình bộ tham số <a href="<?php echo e(route('addParametersConfig')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a></h4> 
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
			<?php if(session('flash_message_err') != ''): ?>
				 <div class="alert alert-err" role="alert"></span> <?php echo e(session('flash_message_err')); ?></div>
			<?php endif; ?>
			<div class="row">
				<?php echo $__env->make('layouts.luongthuong.menusetting', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<div class="col-lg-offset-2 col-lg-7">
					<form class="form-horizontal" method="post" action="">
						<?php echo e(csrf_field()); ?>

						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Tên tham số <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="title" required <?php if($errors->has('title')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('title')); ?>" <?php endif; ?>>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Mô tả<span class="required">*</span></label>
							<div class="col-sm-8">
								<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control" required><?php if($errors->has('description')): ?> autofocus  <?php else: ?>  <?php echo e(old('description')); ?> <?php endif; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Kiểu <span class="required">*</span></label>
							<div class="col-sm-8">
							  	<select class="form-control" name="type" id="mySelect">
								    <option value="1" selected>Fixed</option>
								    <option value="0">Reference</option>
							  	</select>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-4 control-label">Giá trị <span class="required">*</span></label>
							<div class="col-sm-8 fixed">
								<input type="text" class="form-control" name="value_1" required <?php if($errors->has('value_1')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('value_1')); ?>" <?php endif; ?>>
							</div>
							<div class="col-sm-8 reference">
								<select class="form-control" name="value_2">
								<?php
									if( count($setting)>0 ){
										foreach ($setting as $value) {
								?>
											<option value="<?php echo $value->setting_value; ?>"><?php echo $value->setting_key; ?></option>

								<?php
										}
									}

								?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		<script type="text/javascript">
			$('#mySelect').on('change', function() {
			  	if( this.value == 0 ){
			  		$('.fixed input').remove();
			  		$('.reference').css("display", "block");
			  	}else{
			  		$('.fixed').append('<input type="text" class="form-control" name="value_1" required <?php if($errors->has("value_1")): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old("value_1")); ?>" <?php endif; ?>> ');
			  		$('.reference').css("display", "none");
			  	}
			})
		</script>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>