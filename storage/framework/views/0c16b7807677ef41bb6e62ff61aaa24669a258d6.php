

<?php $__env->startSection('title', 'TOH HRMS'); ?>

<?php $__env->startSection('content'); ?>

<div class="special"></div>
<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-sm-offset-1 col-sm-10">
<!-- 				<h3 style="text-transform: uppercase;padding-bottom: 10px;"><?php echo e($data->title); ?></h3> -->
				<div class="content detail-page">
					<?php echo $data->content; ?>

				</div>
			</div>	
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>