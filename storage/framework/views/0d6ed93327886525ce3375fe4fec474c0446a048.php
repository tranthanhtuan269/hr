

<?php $__env->startSection('title', 'Đánh giá'); ?>

<?php $__env->startSection('content'); ?>

<div class="row content-support">
	<div class="col-lg-12">
		<h4 class="title-fuction">Hướng dẫn đánh giá</h4>
		<div class="content detail-page detail">
			<?php echo $data->criteria_content; ?>

		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>