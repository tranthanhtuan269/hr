

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>

<?php
	$data = array( 'job_title_id' ,'department_id');
?>
<div class="row setting_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<div class="col-lg-10">
		<h4 class="title-fuction">Cấu hình chung </h4> 
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
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>