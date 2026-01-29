<h4 class="title-fuction">Danh mục</h4>
<p><a href="<?php echo e(url('toh_hrm/lam-them-gio/index')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Làm thêm giờ</a></p>
<p><a href="<?php echo e(url('toh_hrm/lam-them-gio/quan-ly?type=1')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quản lý đăng ký</a></p>
<p><a href="<?php echo e(url('toh_hrm/lam-them-gio/quan-ly?type=2')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quản lý báo cáo</a></p>
<?php if(in_array('lam-them-gio-giam-sat',$arr_route)): ?>
<p><a href="<?php echo e(url('toh_hrm/lam-them-gio/giam-sat')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Giám sát làm thêm giờ</a></p>
<?php endif; ?>

<?php if(in_array('lam-them-gio-giam-sat',$arr_route)): ?>
<p><a href="<?php echo e(url('toh_hrm/lam-them-gio/cau-hinh')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình</a></p>
<?php endif; ?>