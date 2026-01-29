<?php if($status == 2): ?>
    <p>Yêu cầu đăng ký vay vốn của bạn đã được phê duyệt. Xin vui lòng truy cập danh mục <a href="<?php echo url('toh_hrm/vay-von/index'); ?>">Tín dụng</a> trên web nhân sự để quản lý thông tin</p>
<?php else: ?>
    <p>Yêu cầu đăng ký vay vốn của bạn không được phê duyệt.</p>
    <?php if($reason != null): ?>
        <div>Ghi chú: <?php echo $reason; ?></div>
    <?php endif; ?>
<?php endif; ?>