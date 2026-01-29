<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
    <p><a href="<?php echo e(route('getAllClient')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Tổng hợp</a></p>
    <p><a href="<?php echo e(route('getSalaryClient')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Lương</a></p>
    <p><a href="<?php echo e(route('getAllowanceClient')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Thưởng 	&amp; phụ cấp</a></p>
    <p><a href="<?php echo e(route('getTaxInsurranceClient')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Thuế - Bảo hiểm</a></p>
    <p><a href="<?php echo e(route('getSalaryOtherClient')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Các khoản khác</a></p>
    <p><a href="<?php echo e(route('getWelfareFundsListClient')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quỹ phúc lợi</a></p>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('select[name="selectMonth"],select[name="selectYear"]').change(function(){
            $("#autoClick").click();
        });
    });
</script>