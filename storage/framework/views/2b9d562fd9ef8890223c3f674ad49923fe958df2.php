

<?php $__env->startSection('title', 'Quá trình công tác'); ?>

<?php $__env->startSection('content'); ?>
<div class="row content-function">
    <div class="col-lg-3"></div>
	<div class="col-lg-7">
		<h4 class="title-fuction">Thêm quá trình công tác</h4>
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
				<label for="startDate" class="col-sm-4 control-label">Từ ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="startDate" id="startDate" required <?php if($errors->has('startDate')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('startDate',isset($data->apply_from) ? $data->apply_from : null)); ?>" <?php endif; ?> > 
				</div>
			</div>
			<div class="form-group">
				<label for="endDate" class="col-sm-4 control-label">Đến ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="endDate" id="endDate" required <?php if($errors->has('endDate')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('endDate',isset($data->apply_to) ? $data->apply_to : null)); ?>" <?php endif; ?> >
				</div>
			</div>
			<div class="form-group">
				<label for="heso" class="col-sm-4 control-label">Hệ số</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="heso" id="heso" required <?php if($errors->has('heso')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('heso',isset($data->ratio) ? $data->ratio : null)); ?>" <?php endif; ?> >	
				</div>
			</div>
			 <div class="form-group">
	          <div class="text-center">
	            <button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
	            <a class="btn btn-sm btn-grey" href="<?php echo e(route('getHistoryDetail',['id'=>$id])); ?>">Nhập lại</a>
	          </div>
	        </div>
		</form>
	</div>
	<div class="col-lg-2"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>