

<?php $__env->startSection('title', 'Hồ sơ'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  	<div class="col-lg-3"></div>
  	<div class="col-lg-7">
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Quá trình công tác</th>
		    </tr>
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Chức danh - Đơn vị</td>
		    </tr>
		    <?php if(!empty($data)): ?>
		    	<?php foreach($data as $val): ?>
				    <tr>
				    	<td><?php echo e($val->date_start); ?> - <?php echo e($val->date_end); ?></td>
				    	<td><?php echo e($val->job); ?> - <?php echo e($val->title); ?></td>
				    </tr>
				<?php endforeach; ?>
		    <?php endif; ?>
		    </tbody>
	    </table>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Hệ số chức danh</th>
		    </tr>
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Hệ số chức danh</td>
		    </tr>
		    <?php if(!empty($ratio)): ?>
		    	<?php foreach($ratio as $val): ?>
		    <tr>
		    	<td><?php echo e($val->apply_from); ?> - <?php echo e($val->apply_to); ?></td>
		    	<td><?php echo e($val->ratio); ?></td>
		    </tr>
				<?php endforeach; ?>
		    <?php endif; ?>
		    </tbody>
	    </table>
	</div>
	<div class="col-lg-3"></div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>