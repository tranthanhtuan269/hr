<div>Tên nhân viên: <a href="<?php echo $link; ?>"><?php echo $fullname; ?></a></div>
<div>
	<?php echo $content; ?> 
	<?php if( !empty( $manager ) ): ?>
		Đã được quản lý <b><?php echo $manager; ?></b> đánh giá 
	<?php endif; ?>
</div>

<?php if( !empty($comment_manager_send_personnel) ): ?>
	<div><b>Nhận xét của quản lý gửi cho nhân viên</b>: </div>
	<div><?php echo nl2br($comment_manager_send_personnel); ?></div><br>
<?php endif; ?>
<?php if( !empty($comment_manager_send_direction) ): ?>
	<div><b>Nhận xét về nhân viên gửi BGĐ</b>: </div>
	<?php echo nl2br($comment_manager_send_direction); ?>

<?php endif; ?>