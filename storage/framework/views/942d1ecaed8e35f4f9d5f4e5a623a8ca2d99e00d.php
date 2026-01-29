<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
    <?php if(in_array('luongthuong-cauhinh',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getSalaryConfig')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình chung</a></p>
    <?php endif; ?>
    <?php if(in_array('luongthuong-luong',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getSalary')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Lương</a></p>
    <?php endif; ?>
    <?php if(in_array('luongthuong-phucap',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getAllowance')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Thưởng 	&amp; phụ cấp</a></p>
    <?php endif; ?>
<!--     <?php if(in_array('luongthuong-cauhinhthamso',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getSalaryOther')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Các khoản khác</a></p>
    <?php endif; ?> -->
    <?php if(in_array('luongthuong-thuebaohiem',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getTaxInsurrance')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Thuế - Bảo hiểm</a></p>
    <?php endif; ?>
    <?php if(in_array('luongthuong-tonghop',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getAllSalary')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Tổng hợp</a></p>
    <?php endif; ?>

    <p><a href="<?php echo e(route('settingKi')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình Ki</a></p>


    <?php if(in_array('luongthuong-dsnvdutieuchuantangluong',$arr_route)): ?>
    	<p><a href="<?php echo e(route('getSalaryIncreaseCriterion')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Danh sách nhân viên đủ tiêu chuẩn tăng lương</a></p>
    <?php endif; ?>
    <?php if(in_array('luongthuong-dexuatnangluongdotxuat',$arr_route)): ?>
        <p><a href="<?php echo e(route('getSalaryPropose')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Đề xuất nâng lương đột xuất</a></p>
    <?php endif; ?>
    <?php if(in_array('luongthuong-dsnvtruylinh',$arr_route)): ?>
        <p><a href="<?php echo e(route('getSalaryTL')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Danh sách nhân viên được truy lĩnh</a></p>
    <?php endif; ?>
</div>
<style>
    #parent {
        height: 500px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $('select[name="selectMonth"],select[name="selectYear"],select[name="selectDepart"]').change(function(){
            if ($("#autoClick").length > 0) {
                $('.ajax_waiting').addClass('loading');
                $("#autoClick").click();
            }
        });
        $("#fixTable").tableHeadFixer(); 
    });
</script>
