

<?php $__env->startSection('title', 'Tài khoản'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
	 <?php if(count($errors) > 0): ?>
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            <?php foreach($errors->all() as $error): ?>
	                <li><?php echo e($error); ?></li>
	            <?php endforeach; ?>
	        </ul>
	      </div>
	    <?php endif; ?>
			<form class="form-horizontal" method="post" action="">
			<div class="form-group">
				<label for="passwordCurrent" class="col-sm-4 control-label">Mật khẩu hiện tại</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="passwordCurrent" id="passwordCurrent" value="<?php echo e(old('passwordCurrent')); ?>" required <?php if( ($errors->has('inputPassword')==NULL) && $errors->has('inputPassword_confirmation')==NULL ): ?> autofocus <?php elseif($errors->has('field')): ?> autofocus <?php endif; ?> >
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword" class="col-sm-4 control-label">Mật khẩu mới</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword" id="inputPassword" value="<?php echo e(old('inputPassword')); ?>" required <?php if($errors->has('inputPassword')): ?> autofocus <?php endif; ?> >
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword_confirmation" class="col-sm-4 control-label">Nhập lại mật khẩu</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword_confirmation" id="inputPassword_confirmation" value="<?php echo e(old('inputPassword_confirmation')); ?>"  required <?php if($errors->has('inputPassword_confirmation')): ?> autofocus <?php endif; ?> >
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
					<a href="<?php echo e(route('getTaikhoanInfo',['id'=>Auth::user()->id])); ?>" class="btn btn-sm btn-grey">Nhập lại</a>
				</div>
			</div>
			<?php echo e(csrf_field()); ?>

		</form>
	</div>
	<div class="col-lg-3"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>