

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>

<?php
	$data = array( 'job_title_id' ,'department_id');
?>
<div class="row setting_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<div class="col-lg-10">
		<h4 class="title-fuction">Cấu hình Ki </h4> 
		<div class="row">
            <div class="col-12">
                <ul class="list clearfix">
                    <?php if(in_array('luongthuong-xemcauhinhkihieusuatnam',$arr_route)): ?>
                        <li><a href="<?php echo e(route('addConfigKiPerformance')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình KI(hiệu suất) trong năm</a></li>
                    <?php endif; ?>
                    <?php if(in_array('luongthuong-cauhinhkinoiquynam',$arr_route)): ?>
                        <li><a href="<?php echo e(route('settingConfigKiRules')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình KI(phong trào) trong năm</a></li>
                    <?php endif; ?>
                
                    <?php if(in_array('luongthuong-danhsachkinoiquynam',$arr_route)): ?>
                        <li><a href="<?php echo e(route('getKiRules')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> KI(nội quy) trong năm</a></li>
                    <?php endif; ?>
                </ul>
            </div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>