

<?php $__env->startSection('title', 'Hồ sơ'); ?>

<?php $__env->startSection('content'); ?>

<div class="row content-function">
    <div class="col-lg-3"></div>
	<div class="col-lg-7">
		<h4 class="title-fuction">Thêm khoảng thời gian nghỉ thai sản</h4>
		<?php if(session('flash_message_err') != ''): ?>
			 <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
		<?php endif; ?>
		 <?php if(count($errors) > 0): ?>
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            <?php foreach($errors->all() as $error): ?>
	                <li><?php echo e($error); ?></li>
	            <?php endforeach; ?>
	        </ul>
	      </div>
	      <?php endif; ?>
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			<?php echo e(csrf_field()); ?>

			<div class="form-group">
				<label class="col-sm-4 control-label">Từ ngày</label>
				<div class="col-sm-8">
					<input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="apply_from" id="apply_from" required <?php if($errors->has('apply_from')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('apply_from',isset($data->apply_from) ? $data->apply_from : null)); ?>" <?php endif; ?> > 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Đến ngày</label>
				<div class="col-sm-8">
					<input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="apply_to" id="apply_to" required <?php if($errors->has('apply_to')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('apply_to',isset($data->apply_to) ? $data->apply_to : null)); ?>" <?php endif; ?> >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Tham gia bảo hiểm</label>
				<div class="col-sm-8">
					<label class="radio-inline"><input type="radio" name="join_insurance" checked value="1">Có</label>
					<label class="radio-inline"><input type="radio" name="join_insurance" value="0">Không</label>
				</div>
			</div>
			 <div class="form-group">
	          <div class="text-center">
	            <button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
	          </div>
	        </div>
		</form>
	</div>
	<div class="col-lg-2"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>