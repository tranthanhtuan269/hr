<html lang="en-US">
    <head>
        <meta charset="text/html">
    </head>
    <body>
        <p><?php echo $content; ?></p>
        <?php if(isset($reason) && $reason != ''): ?>
            <div><b>Lý do từ chối</b>: </div>
            <?php echo nl2br($reason); ?>

        <?php endif; ?>

        <?php if(isset($list_work) && $list_work != ''): ?>
            <div><b>Công việc quản lý giao</b>: </div>
            <?php echo nl2br($list_work); ?>

        <?php endif; ?>

        <?php if(isset($days_config) && $days_config != ''): ?>
            <p>
                LƯU Ý: Bạn sẽ phải đăng ký và chờ phê duyệt lại nếu không phát sinh báo cáo trong <?php echo e($days_config); ?> ngày.
            </p>
        <?php endif; ?>
    </body>
</html>