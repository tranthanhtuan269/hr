<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
	
	<?php if(in_array('chiphi-tonghopchiphi',$arr_route)): ?>
		<p><a href="<?php echo e(route('getExpenseGeneral')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Tổng hợp</a></p>
	<?php endif; ?>

	<?php if(in_array('chiphi-danhsachquy',$arr_route)): ?>
		<p><a href="<?php echo e(route('getFundsList')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quỹ</a></p>
	<?php endif; ?>

	<?php if(in_array('chiphi-danhsachchiphi',$arr_route)): ?>
		<p><a href="<?php echo e(route('getExpenseList')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Chi phí</a></p>
	<?php endif; ?>

	<?php if(in_array('chiphi-danhsachkyquy',$arr_route)): ?>
		<p><a href="<?php echo e(route('getSignFundsList')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Ký quỹ</a></p>
	<?php endif; ?>

    <?php if(in_array('chiphi-danhsachchitieuquyphucloi',$arr_route)): ?>
        <p><a href="<?php echo e(route('getWelfareFundsList')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quỹ phúc lợi</a></p>
    <?php endif; ?>

    <?php if(in_array('chiphi-danhsachcauhinhngoaite',$arr_route)): ?>
        <p><a href="<?php echo e(route('getSettingCurrency')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cài đặt ngoại tệ</a></p>
    <?php endif; ?>
</div>