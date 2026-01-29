

<?php $__env->startSection('title', 'TOH HRMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="row content-function">
	<!-- Danh muc -->
	<?php echo $__env->make('layouts.chucnangkhac.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách</h4>
				<div class="table-responsive">
					<div class="form_style">
						<div id="comment-list-box">
							<div class="form_style">
							    <div id="comment-list-box">
								    <table class="table table-bordered table-striped">
								        <thead>
								            <tr>
								                <th class="text-center">Tên mức</th>
								                <th class="text-center">Giá trị( Lương )</th>
								                <th class="text-center">Lương cơ bản</th>
								                <th class="text-center">Quỹ phúc lợi</th>
								                <th class="text-center">Thao tác</th>
								            </tr>
								        </thead>
								        <tbody class="special">
										     	<?php foreach($data as $v): ?>
												     <tr class="message-box text-center" id="message_<?php echo e($v->id); ?>">
														<td class="message-title"><?php echo e($v->title); ?></td>
														<td class="message-value"><?php echo e($v->value); ?></td>
														<td class="message-salary"><?php echo e($v->salary_basic); ?></td>
														<td class="message-welfarefund"><?php echo e($v->welfare_fund); ?></td>
														<td class="button_special">
															<div class="item_1">
																<a class="btnEditAction" name="edit" onClick="showEditBox(this,<?php echo e($v->id); ?>)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
																<a class="btnDeleteAction" name="delete" onClick="callCrudAction('delete',<?php echo e($v->id); ?>)"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
															</div>
															<div class="item_2"></div>

														</td>
												    </tr>
										    	<?php endforeach; ?>
										  
								        </tbody>
								    </table>
							    </div>
								<div id="frmAdd">
								    <table class="table table-bordered table-striped">
								        <thead>
								            <tr>
								                <th class="text-center">Tên mức</th>
								                <th class="text-center">Giá trị( Lương )</th>
								                <th class="text-center">Lương cơ bản</th>
								                <th class="text-center">Quỹ phúc lợi</th>
								                <th class="text-center">Thao tác</th>
								            </tr>
								        </thead>
								        <tbody>
										     <tr>
													<td><input name="title" id="title" class="form-control" required></td>
													<td><input name="value" id="value" class="form-control" required></td>
													<td><input name="salary_basic" id="salary_basic" class="form-control" required></td>
													<td><input name="welfarefund" id="welfarefund" class="form-control" required></td>
													<td><p><a id="btnAddAction" name="submit" onClick="callCrudAction('add','')" class="btn btn-sm btn-orange">Thêm mới</a></p></td>
										    </tr>
										  
								        </tbody>
								    </table>
									
									
								</div>
								<img src="<?php echo e(asset('images/general/bx_loader.gif')); ?>" style="display:none">
							</div>
						</div>
				</div>
			</div>
			<div class="col-lg-12 text-right">

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function showEditBox(editobj,id) {
		$('#frmAdd').hide();
		// $(editobj).prop('disabled','true');
		var messageTitle = $("#message_" + id + " .message-title").html();
		var messageValue = $("#message_" + id + " .message-value").html();
		var messageSalary = $("#message_" + id + " .message-salary").html();
		var messageWelfarefund = $("#message_" + id + " .message-welfarefund").html();
		var title = '<input type="text" value="'+messageTitle+'" id="title_'+id+'" class="form-control">';
		var value = '<input type="text" value="'+messageValue+'" id="value_'+id+'" class="form-control">';
		var salary = '<input type="text" value="'+messageSalary+'" id="salary_basic_'+id+'" class="form-control">';
		var welfarefund =  '<input type="text" value="'+messageWelfarefund+'" id="welfarefund_'+id+'" class="form-control">';
		var button = '<button name="ok" onClick="callCrudAction(\'edit\','+id+')" class="btn btn-xs btn-orange">Lưu</button> <button name="cancel" onClick="cancelEdit('+id+',\''+messageTitle+'\',\''+messageValue+'\',\''+messageSalary+'\',\''+messageWelfarefund+'\')" class="btn btn-xs btn-grey">Hủy</button>';
		$("#message_" + id + " .message-title").html(title);
		$("#message_" + id + " .message-value").html(value);
		$("#message_" + id + " .message-salary").html(salary);
		$("#message_" + id + " .message-welfarefund").html(welfarefund);
		$("#message_" + id + " .button_special .item_1").hide();
		$("#message_" + id + " .button_special .item_2").html(button);
	}
	function cancelEdit() {
		$("#message_" + arguments[0] + " .message-title").html(arguments[1]);
		$("#message_" + arguments[0] + " .message-value").html(arguments[2]);
		$("#message_" + arguments[0] + " .message-salary").html(arguments[3]);
		$("#message_" + arguments[0] + " .message-welfarefund").html(arguments[4]);
		$('#frmAdd').show();
		$("#message_" + arguments[0] + " .button_special .item_2 button").remove();
		$("#message_" + arguments[0] + " .button_special .item_1").show();
	}
	function callCrudAction(action,id) {
		$("#loaderIcon").show();
		var queryString;
		switch(action) {
			case "add":
				var value = $("#value").val();
				var salary_basic = $("#salary_basic").val();
				var welfarefund = $("#welfarefund").val();
				var title = $("#title").val();
				var queryString = {
						type:"add",
						title:title,
						value:value,
						salary_basic:salary_basic,
						welfarefund:welfarefund,
					};

				if ( (!isNaN(value) && value>0) && (!isNaN(salary_basic) && salary_basic>0)  && (!isNaN(welfarefund) && welfarefund>0)){
					$.ajax({
					url: "<?php echo e(route('settingSalaryBasicAjax')); ?>",
					data:queryString,
					type: "GET",
					success:function(data){
						$("#comment-list-box tbody.special").append(data);
						$("#title").val('');
						$("#loaderIcon").hide();
					},
					error:function (){}
					});
				}else{
					alert('Giá trị và Lương cơ bản không được để trống và phải là số nguyên dương !!!');
				}
				    
			break;
			case "edit":
				var value = $("#value_"+id).val();
				var salary_basic = $("#salary_basic_"+id).val();
				var welfarefund = $("#welfarefund_"+id).val();
				var title = $("#title_"+id).val();
				var queryString = {
						type:"edit",
						id:id,
						title:title,
						value:value,
						salary_basic:salary_basic,
						welfarefund:welfarefund,
					};
				if ( (!isNaN(value) && value>0) && (!isNaN(salary_basic) && salary_basic>0)  && (!isNaN(welfarefund) && welfarefund>0)){
					$.ajax({
					url: "<?php echo e(route('settingSalaryBasicAjax')); ?>",
					data:queryString,
					type: "GET",
					success:function(data){
						$("#message_" + id).html(data);
						$('#frmAdd').show();
						$("#title").val('');
						$("#loaderIcon").hide();
					},
					error:function (){}
					});
				}else{
					alert('Giá trị ,Lương cơ bản ,Qũy phúc lợi không được để trống và phải là số nguyên dương !!!');
				}
			break;
			case "delete":
				var queryString = {
						type:"delete",
						id:id,
					};
				var r = confirm("Bạn có thực sự muốn xóa ???");
				if (r == true) {
					$.ajax({
					url: "<?php echo e(route('settingSalaryBasicAjax')); ?>",
					data:queryString,
					type: "GET",
					success:function(data){
						$('#message_'+id).fadeOut();
						$("#title").val('');
						$("#loaderIcon").hide();
					},
					error:function (){}
					});
				} else {
				   return false;
				}
			break;
		}	 

	}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>