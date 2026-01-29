<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
	<?php if(in_array('hoso-phongban',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getDepartment')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Phòng ban</a></p>
	<?php endif; ?>
	<?php if(in_array('hoso-chucdanh',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getJobTitles')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Chức danh</a></p>
	<?php endif; ?>
	<?php if(in_array('hoso-hopdong',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getContract')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hợp đồng</a></p>
	<?php endif; ?>
	<?php if(in_array('hoso-list',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getPersonnelList')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hồ sơ nhân sự</a></p>
	<?php endif; ?>
	<?php if(in_array('quatrinh-list',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getHistoryList')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quá trình công tác</a></p>
	<?php endif; ?>
</div>