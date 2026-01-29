

<?php $__env->startSection('title', 'Chức danh'); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
	<div class="col-lg-offset-2 col-lg-8">
		<h4 class="title-fuction">Sửa chức danh</h4>
		<div class="row">
			<div class="col-lg-12">
			   <?php if(count($errors) > 0): ?>
			    <div class="alert alert-danger">
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
			</div>
			<div class="col-lg-offset-2 col-lg-8">
		        <form  class="form-horizontal" method="POST">
		          <div class="form-group error">
		          <label class="col-sm-3 control-label" for="inputRolename"> Tên chức danh</label>
		            <div class="col-sm-9">
		              <input type="text" class="form-control" name="title"  value="<?php echo e(old('title',isset($data->title) ? $data->title : null )); ?>" required="required">
		            </div>
		          </div>
		          <div class="text-center">
		            <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
		          </div>
		            <?php echo e(csrf_field()); ?>

		        </form>
			</div>
		</div>
	</div>
		
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>