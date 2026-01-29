<div class="col-sm-2">
	<h4 class="title-fuction">Danh mục</h4>
    <p><a href="<?php echo e(route('getEvaluationYearbyUser')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Tự đánh giá</a></p>
    <p><a href="<?php echo e(route('listPersonnelbyManger_Year')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Đánh giá nhân viên</a></p>
    <?php if( in_array('danhgia-xemtoanbodanhgia',$arr_route) ): ?>
    <p><a href="<?php echo e(route('getResultEvaluationManagerbyYear')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Tổng hợp</a></p>
    <?php endif; ?>
</div>