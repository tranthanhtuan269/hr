<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<style type="text/css">
		table, td {
			border: 1px solid #ddd;
		}
		td{
			padding: 3px 5px;
			text-align: center;
		}
	</style>
	<p>Họ tên: <?php echo e($fullname); ?></p>
	<?php echo $content; ?>

	<p>
		<div style="width: 49%;float: left">
			<?php echo $info_salary; ?>

		</div>
		<div style="width: 49%;float: right">
			<?php echo $info_management_allowance; ?>

		</div>
	</p>

</body>
</html>