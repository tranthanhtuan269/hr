

<?php $__env->startSection('title', 'Thiết bị'); ?>

<?php $__env->startSection('content'); ?>
<div class="row content-function">
	<!-- Danh muc -->
	<?php echo $__env->make('layouts.thietbi.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh mục thiết bị 			                        
					<?php if(in_array('thietbi-themdanhmucthietbi',$arr_route)): ?>
						<a href="<?php echo e(route('getCateDeviceAdd')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
					<?php endif; ?>
            	</h4>
				<?php if(session('flash_message_succ') != ''): ?>
					 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
				<?php endif; ?>
				<?php if(session('flash_message_err') != ''): ?>
					 <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
				<?php endif; ?>
				<div class="table-responsive">
                    <?php if(!empty($cateDevice)): ?>
						<?php echo $cateDevice; ?>

					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>