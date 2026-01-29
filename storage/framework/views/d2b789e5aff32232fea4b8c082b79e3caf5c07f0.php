<div class="col-sm-2">

        <p><a href="<?php echo e(route('settingPageHome')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình trang chủ</a></p>


    <?php if(in_array('chucnangkhac-cauhinhemail',$arr_route)): ?>
		<p><a href="<?php echo e(route('settingEmail')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình Email</a></p>
    <?php endif; ?>
    <?php if(in_array('chucnangkhac-cauhinhluongcoban',$arr_route)): ?>
    	<p><a href="<?php echo e(route('settingSalaryBasic')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình mức lương cơ bản + quỹ phúc lợi</a></p>
    <?php endif; ?>
    <?php if(in_array('chucnangkhac-cauhinhmucchiuthue',$arr_route)): ?>
    	<p><a href="<?php echo e(route('settingTax')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình mức thuế</a></p>
    <?php endif; ?>
    <?php if(in_array('chucnangkhac-cauhinhkhac',$arr_route)): ?>
        <p><a href="<?php echo e(route('settingOthers')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình khác</a></p>
    <?php endif; ?>
    <?php if(in_array('chucnangkhac-cauhinhmienchamcong',$arr_route)): ?>
        <p><a href="<?php echo e(route('settingExceptionalAttendance')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình miễn chấm công</a></p>
    <?php endif; ?>
    <?php if(in_array('chucnangkhac-cauhinhchamcongnghiphep',$arr_route)): ?>
        <p><a href="<?php echo e(route('settingAbsentAttendance')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình chấm công nghỉ phép</a></p>
    <?php endif; ?>
</div>