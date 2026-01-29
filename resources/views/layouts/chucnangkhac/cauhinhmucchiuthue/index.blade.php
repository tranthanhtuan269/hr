@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.chucnangkhac.menuleft')
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
								                <th class="text-center">Tên bậc</th>
								                <th class="text-center">Thuế thu nhập tính thuế/tháng</th>
								                <th class="text-center">Thuế suất (%)</th>
								                <th class="text-center">Số tiền bị trừ thêm</th>
								                <th class="text-center">Thao tác</th>
								            </tr>
								        </thead>
								        <tbody class="special">
								        <?php
								        	// echo "<pre>";
								        	// print_r($data);die;
								        ?>
										    
										     	@foreach($data as $v)
												     <tr class="message-box text-center" id="message_{{ $v->id }}">
														<td class="message-title">{{ $v->title }}</td>
														<td class="message-money_tax">{{ BatvHelper::formatPrice($v->money_tax) }}</td>
														<td class="message-percent_tax">{{ $v->percent_tax }}</td>
														<td class="message-money_minus">{{ BatvHelper::formatPrice($v->money_minus) }}</td>
														<td class="button_special">
															<div class="item_1">
																<a class="btnEditAction" name="edit" onClick="showEditBox(this,{{ $v->id }})"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
																<a class="btnDeleteAction" name="delete" onClick="callCrudAction('delete',{{ $v->id }})"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
															</div>
															<div class="item_2"></div>

														</td>
												    </tr>
										    	@endforeach
										  
								        </tbody>
								    </table>
							    </div>
								<div id="frmAdd">
								    <table class="table table-bordered table-striped">
								        <thead>
								            <tr>
								                <th class="text-center">Tên bậc</th>
								                <th class="text-center">Thuế thu nhập tính thuế/tháng</th>
								                <th class="text-center">Thuế suất (%)</th>
								                <th class="text-center">Số tiền bị trừ thêm</th>
								                <th class="text-center">Thao tác</th>
								            </tr>
								        </thead>
								        <tbody>
										     <tr>
													<td><input name="title" id="title" class="form-control" required></td>
													<td><input name="money_tax" id="money_tax" class="form-control" required></td>
													<td><input name="percent_tax" id="percent_tax" class="form-control" required></td>
													<td><input name="money_minus" id="money_minus" class="form-control" required></td>
													<td><p><a id="btnAddAction" name="submit" onClick="callCrudAction('add','')" class="btn btn-sm btn-orange">Thêm mới</a></p></td>
										    </tr>
										  
								        </tbody>
								    </table>
									
									
								</div>
								<img src="{{ asset('images/general/bx_loader.gif') }}" style="display:none">
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
		var messageMoney_tax = $("#message_" + id + " .message-money_tax").html();
		var messagePercent_tax = $("#message_" + id + " .message-percent_tax").html();
		var messageMoney_minus = $("#message_" + id + " .message-money_minus").html();
		var title = '<input type="text" value="'+messageTitle+'" id="title_'+id+'" class="form-control">';
		var money_tax = '<input type="text" value="'+messageMoney_tax+'" id="money_tax_'+id+'" class="form-control">';
		var percent_tax = '<input type="text" value="'+messagePercent_tax+'" id="percent_tax_'+id+'" class="form-control">';
		var money_minus = '<input type="text" value="'+messageMoney_minus+'" id="money_minus_'+id+'" class="form-control">';
		var button = '<button name="ok" onClick="callCrudAction(\'edit\','+id+')" class="btn btn-xs btn-orange">Lưu</button> <button name="cancel" onClick="cancelEdit('+id+',\''+messageTitle+'\',\''+messageMoney_tax+'\',\''+messagePercent_tax+'\',\''+messageMoney_minus+'\')" class="btn btn-xs btn-grey">Hủy</button>';
		$("#message_" + id + " .message-title").html(title);
		$("#message_" + id + " .message-money_tax").html(money_tax);
		$("#message_" + id + " .message-percent_tax").html(percent_tax);
		$("#message_" + id + " .message-money_minus").html(money_minus);
		$("#message_" + id + " .button_special .item_1").hide();
		$("#message_" + id + " .button_special .item_2").html(button);
	}
	function cancelEdit() {
		$("#message_" + arguments[0] + " .message-title").html(arguments[1]);
		$("#message_" + arguments[0] + " .message-money_tax").html(arguments[2]);
		$("#message_" + arguments[0] + " .message-percent_tax").html(arguments[3]);
		$("#message_" + arguments[0] + " .message-money_minus").html(arguments[4]);
		$('#frmAdd').show();
		$("#message_" + arguments[0] + " .button_special .item_2 button").remove();
		$("#message_" + arguments[0] + " .button_special .item_1").show();
	}
	function callCrudAction(action,id) {
		$("#loaderIcon").show();
		var queryString;
		switch(action) {
			case "add":
				var money_tax = $("#money_tax").val();
				var percent_tax = $("#percent_tax").val();
				var title = $("#title").val();
				var money_minus = $("#money_minus").val();
				var queryString = {
						type:"add",
						title:title,
						money_tax:money_tax,
						percent_tax:percent_tax,
						money_minus:money_minus
					};

				if ( (!isNaN(money_tax) && money_tax>0) && (!isNaN(percent_tax) && percent_tax>0) && (!isNaN(money_minus) && money_minus>=0)){
					$.ajax({
					url: "{{ route('settingTaxAjax') }}",
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
					alert('Ngoại trừ trường Tên bậc thì các trường còn lại không được để trống và phải là số dương !!!');
				}
				    
			break;
			case "edit":
				var money_tax = $("#money_tax_"+id).val();
				var percent_tax = $("#percent_tax_"+id).val();
				var title = $("#title_"+id).val();
				var money_minus = $("#money_minus_"+id).val();
				var queryString = {
						type:"edit",
						id:id,
						title:title,
						money_tax:money_tax,
						percent_tax:percent_tax,
						money_minus:money_minus
					};
				if ( (!isNaN(money_tax) && money_tax>0) && (!isNaN(percent_tax) && percent_tax>0) && (!isNaN(money_minus) && money_minus>0)){
					$.ajax({
					url: "{{ route('settingTaxAjax') }}",
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
					alert('Ngoại trừ trường Tên bậc thì các trường còn lại không được để trống và phải là số dương !!!');
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
					url: "{{ route('settingTaxAjax') }}",
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
@endsection