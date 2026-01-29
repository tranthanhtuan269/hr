<style type="text/css">
	table, td {
		border: 1px solid #ddd;
	}
	td{
		padding: 3px 5px;
		text-align: center;
	}
</style>

<?php echo nl2br($content); ?>

<?php if( !empty($comment_manager) ): ?>
	<div><b>Nhận xét của quản lý</b>: </div>
	<div><?php echo nl2br($comment_manager); ?></div><br>
<?php endif; ?>
<?php if( !empty($comment_manager_final) ): ?>
	<div><b>Nhận xét của BGD</b>: </div>
	<?php echo nl2br($comment_manager_final); ?>

<?php endif; ?>

<p>
	<div style="width: 49%;float: left">
		<?php echo $info_salary; ?>

	</div>
	<div style="width: 49%;float: right">
		<?php echo $info_management_allowance; ?>

	</div>
</p>