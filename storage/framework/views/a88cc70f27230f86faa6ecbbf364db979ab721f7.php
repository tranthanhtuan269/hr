

<?php $__env->startSection('title', 'Chấm công'); ?>

<?php $__env->startSection('content'); ?>
<?php  //echo 1;die; ?>
<div class="row content-function">
    <div class="col-lg-3">
    	<h4 class="title-fuction">Giải thích ký hiệu</h4>
		   <table class="table">
		   		<thead> 
					<tr> 
						<th>Ký hiệu</th> 
						<th>Chú giải</th>  
						<th>Thao tác</th> 
					</tr> 
					</thead> 
				<tbody> 
					<?php if(!empty($info)): ?>
						<?php foreach($info as $val): ?>
							<tr> 
								<td><?php echo e($val->symbol); ?></td>
								<td>
									<?php echo e($val->title); ?>

								</td> 
								<td>
									<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal_edit<?php echo e($val->id); ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal_delete<?php echo e($val->id); ?>"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
								</td>
							</tr>
							<div id="myModal_edit<?php echo e($val->id); ?>" class="modal fade" role="dialog">
							  <div class="modal-dialog">
							    <div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title text-center">Sửa thông tin</h4>
							            <div class="ajax_response text-center" style="display: none;"></div>
									</div>
									<form class="form-horizontal" id="contactFormSymbolEdit<?php echo e($val->id); ?>">
										<?php echo csrf_field(); ?>

										<div class="modal-body row">
											<div class="form-group clearfix">
											    <label class="col-sm-4 control-label" style="text-align: right;">Tên ký hiệu <span class="required">*</span></label>
											    <div class="col-sm-6">
											        <input type="text" id="symbol<?php echo e($val->id); ?>" class="form-control" name="symbol" required value="<?php echo e($val->symbol); ?>">
											    </div>
											</div>
											<div class="form-group clearfix">
											    <label class="col-sm-4 control-label" style="text-align: right;">Chú giải <span class="required">*</span></label>
											    <div class="col-sm-6">
											        <input type="text" id="title<?php echo e($val->id); ?>" class="form-control" name="title" required value="<?php echo e($val->title); ?>">
											    </div>
											</div>
											<div class="form-group clearfix">
											    <label class="col-sm-4 control-label" style="text-align: right;">Kiểu <span class="required">*</span></label>
											    <div class="col-sm-6">
												    <label class="radio-inline">
												      <input type="radio" name="type<?php echo e($val->id); ?>" value="1" required <?php if(  isset($val->type) && $val->type==1 ){ echo "checked"; } ?> >Hưởng lương
												    </label>
												    <label class="radio-inline">
												      <input type="radio" name="type<?php echo e($val->id); ?>" value="0" <?php if(  isset($val->type) && $val->type==0 ){ echo "checked"; } ?>>Nghỉ không lương
												    </label>
											    </div>
											</div>
											<input type="hidden" name="id" value="<?php echo e($val->id); ?>">
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-sm btn-orange" onclick="updateData(<?php echo e($val->id); ?>)">Cập nhật</button>
										</div>
									</form>
							    </div>
							  </div>
							</div>
							<!-- FORM XÓA -->
							<div id="myModal_delete<?php echo e($val->id); ?>" class="modal fade" role="dialog">
							  <div class="modal-dialog">
    							
							    <div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title text-center">Bạn có thực sự muốn xóa ???</h4>
							            <div class="ajax_response text-center" style="display: none;"></div>
									</div>
									<form class="form-horizontal" id="contactFormSymbolEdit<?php echo e($val->id); ?>">
										<?php echo csrf_field(); ?>

										<div class="modal-footer">
											<button type="button" class="btn btn-sm btn-orange" onclick="deleteData(<?php echo e($val->id); ?>)">Có</button>
											<button type="button" class="btn btn-sm btn-grey" data-dismiss="modal">Không</button>
										</div>
									</form>
							    </div>
							  </div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>


		<!-- FORM THÊM KÝ HIỆU -->
		<p class="text-center"><button type="button" class="btn btn-sm btn-orange" data-toggle="modal" data-target="#myModal" id="add">Thêm ký hiệu</button></p>
		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Thêm ký hiệu</h4>
		            <div class="ajax_response text-center" style="display: none;"></div>
				</div>
				<form class="form-horizontal" method="post" action="" id="contactFormSymbol">
					<?php echo csrf_field(); ?>

					<div class="modal-body row">
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Tên ký hiệu <span class="required">*</span></label>
						    <div class="col-sm-6">
						        <input type="text" class="form-control" name="symbol" required>
						    </div>
						</div>
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Chú giải <span class="required">*</span></label>
						    <div class="col-sm-6">
						        <input type="text" class="form-control" name="title" required>
						    </div>
						</div>
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Kiểu<span class="required">*</span></label>
						    <div class="col-sm-6">
							    <label class="radio-inline">
							      <input type="radio" name="type" value="1" required>Hưởng lương
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="type" value="0">Nghỉ không lương
							    </label>
						    </div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
					</div>
				</form>
		    </div>
		  </div>
		</div>
    </div>
	<div class="col-lg-9">
	   <?php if(count($errors) > 0): ?>
      <div class="alert alert-danger" role="alert">
        <ul>
            <?php foreach($errors->all() as $error): ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
      <?php if(session('flash_message_err') != ''): ?>
		<div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
	  <?php endif; ?>
	  <?php if(session('flash_message_succ') != ''): ?>
     	 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
      <?php endif; ?>
		<h4 class="title-fuction">QUẢN TRỊ CHẤM CÔNG</h4>
		<form class="form-horizontal" method="get" action="" >
			<div class="form-group col-lg-6">
				<label for="date" class="col-sm-4 control-label">Ngày</label>
				<div class="col-sm-8">
					<?php if( !empty( Request::get('date') ) ): ?>
						<input type="text"  id="datepicker" class="form-control datepicker" name="date"  placeholder="Ngày" value="<?php echo e(Request::get('date')); ?>">
					<?php else: ?>
						<input type="text"  id="datepicker" class="form-control datepicker" name="date"  placeholder="Ngày" value="<?php echo e(date('d/m/Y')); ?>">
					<?php endif; ?>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="selectDepart" class="col-sm-4 control-label">Đơn vị</label>
				<div class="col-sm-8">	
	               <select name="selectDepart" id="department" class="form-control select2 narrow wrap" >
		                <?php echo $department; ?>

		            </select>
		            <script type="text/javascript">
						var $select2 = $('.select2').select2({
						    containerCssClass: "wrap"
						})

						var get_selectDepart = '<?php echo e(Request::get('selectDepart')); ?>';
						
						if (get_selectDepart != '') {
							$("#department").select2().select2('val', "<?php echo e(Request::get('selectDepart')); ?>");
						}

		            </script>
                </div>
			</div>
			<div class="form-group col-lg-12 text-center">
				<input type="submit" class="btn btn-sm btn-orange" name="search" value="Tìm kiếm">
			</div>
			<?php echo e(csrf_field()); ?>

		</form>
		<form class="form-horizontal" method="post" action="" >
			<table class="table" id="data_default"> 
				<thead> 
					<tr> 
						<th>Id</th> 
						<th>Họ và tên</th> 
						<th>Email</th>
						<th>Chấm công đi làm</th> 
						<th>Đơn vị (ngày)</th> 
						<th>Chấm công đi muộn</th> 
						<th>Thời gian đi muộn (phút)</th> 
						<th></th> 
					</tr> 
				</thead> 
				<?php
					// echo "<pre>";
					// print_r($data);die;
				?>
				<tbody class="list-personnel"> 
			<?php if(!empty($data)): ?>
				<?php foreach($data as $val): ?>
					<tr> 
						<th scope="row"><?php echo e($val['id']); ?></th> 
						<td>
							<?php echo e(str_limit( $val['fullname'], $limit = 45, $end = '...')); ?>

						</td> 
						<td>
							<?php echo e($val['email']); ?>

						</td> 
						<td style="text-align: center;">
							<select class="select_type" name='typeAttendance_1[<?php echo e($val["personnel_attendance_id"][0]); ?>]' id='typeAttendance_1_<?php echo e($val["personnel_attendance_id"][0]); ?>'>	
				            <?php foreach($listType_1 as $v): ?>
				                <option value="<?php echo e($v->id); ?>" <?php if( $val["attendance_type_id"][0] == $v->id ): ?> selected='selected' <?php endif; ?> ><?php echo e($v->symbol); ?></option>
				            <?php endforeach; ?>
							</select>
							<?php
								if( count($val['time_late'])>1 ){
							?>
									</br><select style="margin-top: 30px;" name='typeAttendance_1_add_case[<?php echo e($val["personnel_attendance_id"][1]); ?>]' id='typeAttendance_1_1<?php echo e($val["personnel_attendance_id"][1]); ?>'>	
						            <?php foreach($listType_1 as $v): ?>
						                <option value="<?php echo e($v->id); ?>" <?php if( $val["attendance_type_id"][1] == $v->id ): ?> selected='selected' <?php endif; ?> ><?php echo e($v->symbol); ?></option>
						            <?php endforeach; ?>
									</select>

							<?php
								}
							?>

						</td> 
						<td style="text-align: center;">
							<select  name='unit_date[<?php echo e($val["personnel_attendance_id"][0]); ?>]'>	
								<?php if( $check_saturday == 1): ?>
					           		<option value="1" <?php if($val["unit_date"][0]==1): ?> selected <?php endif; ?>>1</option>
					           		<option value="0.5" <?php if($val["unit_date"][0]==0.5): ?> selected <?php endif; ?>>0.5</option>
				           		<?php else: ?>
				           			<option value="0.5">0.5</option>
				           		<?php endif; ?>
							</select>
							<?php
								if( count($val['time_late'])>1 ){
							?>
								</br><select style="margin-top: 30px;" name='unit_date_add_case[<?php echo e($val["personnel_attendance_id"][1]); ?>]'>	
									<?php if( $check_saturday == 1): ?>
						           		<option value="1" <?php if($val["unit_date"][1]==1): ?> selected <?php endif; ?>>1</option>
						           		<option value="0.5" <?php if($val["unit_date"][1]==0.5): ?> selected <?php endif; ?>>0.5</option>
					           		<?php else: ?>
					           			<option value="0.5">0.5</option>
					           		<?php endif; ?>
								</select>

							<?php
								}
							?>
						</td> 
						<td style="text-align: center;">
							<select class="select_type" name='typeAttendance_2[<?php echo e($val["personnel_attendance_id"][0]); ?>]' id='typeAttendance_2_<?php echo e($val["personnel_attendance_id"][0]); ?>'>		
				            <?php foreach($listType_3 as $v): ?>
				                <option value="<?php echo e($v->id); ?>" <?php if( $val["attendance_type_id"][0] == $v->id ): ?> selected='selected' <?php endif; ?> ><?php echo e($v->symbol); ?></option>
				            <?php endforeach; ?>
							</select>
							<?php
								if( count($val['time_late'])>1 ){
							?>
								</br><select style="margin-top: 30px;" name='typeAttendance_2_add_case[<?php echo e($val["personnel_attendance_id"][1]); ?>]' id="typeAttendance_2_<?php echo e($val["personnel_attendance_id"][1]); ?>">		
					            <?php foreach($listType_3 as $v): ?>
					                <option value="<?php echo e($v->id); ?>" <?php if( $val["attendance_type_id"][1] == $v->id ): ?> selected='selected' <?php endif; ?> ><?php echo e($v->symbol); ?></option>
					            <?php endforeach; ?>
								</select>
							<?php
								}
							?>
						</td> 
						<td>
							<input type="text" class="form-control input-sm" name='time_late[<?php echo e($val["personnel_attendance_id"][0]); ?>]' value='<?php echo e($val["time_late"][0]); ?>'>
							<?php
								if( count($val['time_late'])>1 ){
							?>
								</br><input type="text" class="form-control input-sm" name='time_late_add_case[<?php echo e($val["personnel_attendance_id"][1]); ?>]' value='<?php echo e($val["time_late"][1]); ?>'>
							<?php
								}
							?>
						</td> 
						<td>

							<?php
								if( count($val['time_late'])==1 && $check_saturday == 1 &&  $paramAttendanceStatus == 1){
							?>
								<div style="margin-top: 8px;">
									<a href="javascript:void(0)" data-toggle="modal" data-target='#myModal_addAttendance<?php echo e($val["id"]); ?>'><i class="fa fa-plus-square" aria-hidden="true"></i></a>
								</div>
							<?php
								}elseif(  count($val['time_late'])==2 &&  $paramAttendanceStatus == 1){
							?>
								<div style="margin-top: 55px;">
									<a href="javascript:void(0)" class="delete_personnel_attendance_id" data-personnel_attendance_id ="<?php echo e($val["personnel_attendance_id"][1]); ?>"><i class="fa fa-minus-square" aria-hidden="true"></i></a>
								</div>
							<?php
								}
							?>
							<!-- Chấm cồng thêm -->
							<div id='myModal_addAttendance<?php echo e($val["id"]); ?>' class="modal fade" role="dialog">
							  <div class="modal-dialog attendance">
							    <div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title text-center">Chấm công thêm</h4>
							            <div class="ajax_response text-center" style="display: none;"></div>
									</div>
									<form class="form-horizontal">
										<?php echo csrf_field(); ?>

											<table class="table table-hover" id="data_default"> 
												<thead> 
													<tr> 
														<th>Họ và tên</th> 
														<th>Email</th> 
														<th>Chấm công đi làm</th> 
														<th>Đơn vị (ngày)</th> 
														<th>Chấm công đi muộn</th> 
														<th>Thời gian đi muộn (phút)</th> 
													</tr> 
												</thead> 
												<?php
													// echo "<pre>";
													// print_r($data);die;
												?>
												<tbody class="list-personnel"> 
													<tr> 
														<td>
															<?php echo e(str_limit( $val['fullname'], $limit = 45, $end = '...')); ?>

														</td> 
														<td>
															<?php echo e($val['email']); ?>

														</td> 
														<td style="text-align: center;">
															<select class="select_type" name='typeAttendance_1_1[<?php echo e($val["id"]); ?>]' id='typeAttendance_1_1<?php echo e($val["id"]); ?>'>	
												            <?php foreach($listType_1 as $v): ?>
												                <option value="<?php echo e($v->id); ?>" ><?php echo e($v->symbol); ?></option>
												            <?php endforeach; ?>
															</select>
														</td> 
														<td style="text-align: center;">
															<select  name='unit_date_2[<?php echo e($val["id"]); ?>]' id='unit_date_2<?php echo e($val["id"]); ?>'>	
												           		<option value="0.5">0.5</option>
															</select>
														</td> 
														<td style="text-align: center;">
															<select class="select_type" name='typeAttendance_2_1[<?php echo e($val["id"]); ?>]' id='typeAttendance_2_1<?php echo e($val["id"]); ?>'>		
												            <?php foreach($listType_3 as $v): ?>
												                <option value="<?php echo e($v->id); ?>"><?php echo e($v->symbol); ?></option>
												            <?php endforeach; ?>
															</select>
															
														</td> 
														<td>
															<input type="text" class="form-control input-sm" name='time_late_2[<?php echo e($val["id"]); ?>]' id='time_late_2<?php echo e($val["id"]); ?>'>
														</td> 
													</tr> 
												</tbody>
											</table>
											<div class="modal-footer">
												<button type="button" class="btn btn-sm btn-orange" onclick="updateDataAttendance(<?php echo e($val["id"]); ?>)">Cập nhật</button>
												<input type="hidden" value="<?php echo isset($_GET['date'])?$_GET['date']:date('d/m/Y') ?>" id="date">
											</div>
									</form>
							    </div>
							  </div>
							</div>
						</td>
					</tr> 

				<?php endforeach; ?>
			<?php endif; ?>
				</tbody>
			</table>
			<div class="col-lg-12 text-right">
				<?php /* <?php echo e($data->appends(Request::query())->render()); ?>  */ ?>
			</div>
			<div id="result" class="text-center"></div>
			<?php if( $paramAttendanceStatus == 1 ): ?>
				<div class="form-group col-lg-12">
		          <div class="text-center">
		          	<input type="hidden"  name="check_saturday" value="<?php echo e($check_saturday); ?>">
		            <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
		            <button type="reset" class="btn btn-sm btn-orange">Nhập lại</button>
		            <button type="submit" class="btn btn-sm btn-orange" name="sendemail" onclick="return confirm('Bạn có chắc chắn muốn gửi Email ?')">Gửi Email</button>
		            <a href="javascript:void(0)" data-toggle="modal" class="btn btn-sm btn-orange" data-target='#myModal_addAttendanceSpecial'>Thêm nhân sự</a>
		          </div>
		        </div>
    			<?php echo e(csrf_field()); ?>

			<?php endif; ?>
		</form>
		<!-- Thêm nhân sự mới -->
		<div id='myModal_addAttendanceSpecial' class="modal fade" role="dialog">
		  <div class="modal-dialog attendance">
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Chấm công cho nhân sự mới(Hoàn thành hồ sơ chậm) </h4>
		            <div class="ajax_response text-center" style="display: none;"></div>
				</div>
				<form class="form-horizontal">
					<?php echo csrf_field(); ?>

						<table class="table table-hover" id="data_default"> 
							<thead> 
								<tr> 
									<th>Họ và tên</th> 
									<th>Email</th> 
									<th>Chấm công đi làm</th> 
									<th>Đơn vị (ngày)</th> 
									<th>Chấm công đi muộn</th> 
									<th>Thời gian đi muộn (phút)</th> 
								</tr> 
							</thead> 
							<tbody class="list-personnel"> 
							<?php foreach($listPersonnelNew as $val): ?>
								<tr> 
									<td>
										<?php echo e(str_limit( $val->fullname, $limit = 45, $end = '...')); ?>

									</td> 
									<td>
										<?php echo e($val->email); ?>

									</td> 
									<td style="text-align: center;">
										<select class="select_type" name='typeAttendance_1[<?php echo e($val->id); ?>]' id='typeAttendance_1'>	
							            <?php foreach($listType_1 as $v): ?>
							                <option value="<?php echo e($v->id); ?>" <?php if( old('typeAttendance_1') && old('typeAttendance_1') == $v->id ): ?> selected='selected' <?php endif; ?> ><?php echo e($v->symbol); ?></option>
							            <?php endforeach; ?>
										</select>
									</td> 
									<td style="text-align: center;">
										<select  name='unit_date' id="unit_date">	
											<?php if( $check_saturday == 1): ?>
								           		<option value="1">1</option>
								           		<option value="0.5">0.5</option>
							           		<?php else: ?>
							           			<option value="0.5">0.5</option>
							           		<?php endif; ?>
										</select>
									</td>  
									<td style="text-align: center;">
										<select class="select_type" name='typeAttendance_2[<?php echo e($val->id); ?>]' id="typeAttendance_2">		
							            <?php foreach($listType_3 as $v): ?>
							                <option value="<?php echo e($v->id); ?>" <?php if( old('typeAttendance_2') && old('typeAttendance_2') == $v->id ): ?> selected='selected' <?php endif; ?> ><?php echo e($v->symbol); ?></option>
							            <?php endforeach; ?>
										</select>
										
									</td>  
									<td>
										<input type="text" class="form-control" name='time_late[<?php echo e($val->id); ?>]' id="time_late">
										<input type="hidden" class="form-control" id="personnel_id" value="<?php echo e($val->id); ?>">
									</td> 
									<td>
								</tr> 
							<?php endforeach; ?>
							</tbody>
						</table>
						<div class="modal-footer">
							<button type="button" class="btn btn-sm btn-orange" onclick="insertDataAttendanceSpecial()">Cập nhật</button>
							<input type="hidden" value="<?php echo isset($_GET['date'])?$_GET['date']:date('d/m/Y') ?>" id="date">
						</div>
				</form>
		    </div>
		  </div>
		</div>
		<script>
			$(document).ready(function(){
			    $('input[name="date"]').change(function(){
			        $('.list-personnel').remove();
			    });
			    $("#edit,#add,#delete").click(function(){
			        $(".ajax_response div").remove();
			        $(".ajax_response").removeClass('alert-success');
			        $(".ajax_response").removeClass('alert-error');
			    });
			});
		</script>
		<script type="text/javascript">
				$(document).on('click','.delete_personnel_attendance_id',function(){
					var personnel_attendance_id = $(this).attr('data-personnel_attendance_id');
					var date = "<?php echo e(isset($_GET['date'])?$_GET['date']:date('d/m/Y')); ?>";
					Swal.fire({
						text: "Bạn có chắc chắn muốn xóa!",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Có',
						cancelButtonText: 'Không'
						}).then((result) => {
							if (result.value) {
								var link = "<?php echo route('delAttendanceItemAjax'); ?>";
								var date = $('#date').val();
								var data = {
										id:personnel_attendance_id,
										date: date,
									};
								$.ajax({
									url: link,
									data: data,
									success: function (response) {
										var obj = $.parseJSON(response);
										if(obj.Response=='Error'){	
											Swal.fire({
												type: 'warning',
												html: obj.Message,
											})
										}
										else{
											Swal.fire({
													type: 'success',
													html: obj.Message,
											}).then((result) => {
												if (result.value) {
													window.location.reload();
												}
											})
										}
									},
									error: function (data) {
										console.log('Error:', data);
									}
								});
								$(".ajax_response div").remove();
							}
					})
				});

                function insertDataAttendanceSpecial(id){
                     var typeAttendance_1 = $("select[id='typeAttendance_1']").map(function(){return $(this).val();}).get();
                     var typeAttendance_2 = $("select[id='typeAttendance_2']").map(function(){return $(this).val();}).get();
                     var unit_date = $("select[id='unit_date']").map(function(){return $(this).val();}).get();
					 var time_late = $("input[id='time_late']").map(function(){return $(this).val();}).get();
					 var personnel_id = $("input[id='personnel_id']").map(function(){return $(this).val();}).get();
                     var date = $('#date').val();
                     // alert(date);return false;
                     var link = "<?php echo route('addAttendanceSpecialAjax'); ?>";
                     var data = {
                     		id:id,
                            typeAttendance_1:typeAttendance_1,
                            typeAttendance_2: typeAttendance_2,
                            unit_date: unit_date,
                            time_late: time_late,
                            personnel_id:personnel_id,
                            date:date,
                         };
                     $.ajax({
                        url: link,
                        data: data,
                        success: function (response) {
                            var obj = $.parseJSON(response);
                            if(obj.Response=='Error'){	
						        $.each( obj.Error, function( key, value) {
                                    $(".ajax_response").removeClass('alert-success').addClass("alert-danger");
                                    $(".ajax_response").append('<div style="padding:5px 0px;">'+value+'</div');
						        });
						        $(".ajax_response").show('slow');
                            }
                            else{
								Swal.fire({
										type: 'success',
										html: obj.Message,
								}).then((result) => {
									if (result.value) {
										window.location.reload();
									}
								})
                            }
                        },
                         error: function (data) {
                             console.log('Error:', data);
                         }
                     });
                    $(".ajax_response div").remove();
                 	return false;
                }
                function updateData(id){
                		 var param = "type"+id;
                         var title = $('#title'+id).val();
                         var symbol = $('#symbol'+id).val();
                         var type = $('input[name='+param+']:checked').val();
                         var link = "<?php echo route('editAttendanceSymbolAjax'); ?>";
                         var data = {
                         		id:id,
                                title:title,
                                symbol: symbol,
                                type: type,
                             };
                         $.ajax({
                            url: link,
                            data: data,
                            success: function (response) {
                                var obj = $.parseJSON(response);
                                if(obj.Response=='Error'){	
							        $.each( obj.Error, function( key, value) {
                                        $(".ajax_response").removeClass('alert-success').addClass("alert-danger");
                                        $(".ajax_response").append('<div style="padding:5px 0px;">'+value+'</div');
							        });
							        $(".ajax_response").show('slow');
                                }
                                else{
									Swal.fire({
											type: 'success',
											html: obj.Message,
									}).then((result) => {
										if (result.value) {
											window.location.reload();
										}
									})
                                }
                            },
                             error: function (data) {
                                 console.log('Error:', data);
                             }
                         });
                        $(".ajax_response div").remove();
                     	return false;
                }
                function deleteData(id){
                         var link = "<?php echo route('deleteAttendanceSymbolAjax'); ?>";
                         var data = {
                         		id:id,
                             };
                         $.ajax({
                             url: link,
                             data: data,
                             success: function (response) {
                                 var obj = $.parseJSON(response);
                                 if(obj.Response=='Error'){
                                     $(".ajax_response").removeClass('alert-success').addClass("alert-error");
                                     $(".ajax_response").html('<div style="padding:5px 0px;">'+obj.Error+'</div');
                                     $(".ajax_response").show('slow');
                                 }
                                 else{
									Swal.fire({
											type: 'success',
											html: obj.Message,
									}).then((result) => {
										if (result.value) {
											window.location.reload();
										}
									})
                                 }
                             },
                             error: function (data) {
                                 console.log('Error:', data);
                             }
                         });
                     	return false;
                }

                $(document).ready(function() {
                    $('#contactFormSymbol').submit(function(event) {
                            var title = $('#contactFormSymbol input[name="title"]').val();
                            var symbol = $('#contactFormSymbol input[name="symbol"]').val();
                            var type = $('#contactFormSymbol input[name="type"]:checked').val();
                            var link = "<?php echo route('addAttendanceSymbolAjax'); ?>";
                            var data = {
                                    title:title,
                                    symbol: symbol,
                                    type: type,
                                };
                            $.ajax({
                                url: link, //Relative or absolute path to response.php file
                                data: data,
                                success: function (response) {
                                    var obj = $.parseJSON(response);
                                    if(obj.Response=='Error'){	
								        $.each( obj.Error, function( key, value) {
	                                        $(".ajax_response").removeClass('alert-success').addClass("alert-danger");
	                                        $(".ajax_response").append('<div style="padding:5px 0px;">'+value+'</div');
								        });
								        $(".ajax_response").show('slow');
                                    }
                                    else{
										Swal.fire({
												type: 'success',
												html: obj.Message,
										}).then((result) => {
											if (result.value) {
												window.location.reload();
											}
										})
                                    }
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        $(".ajax_response div").remove();
                        return false;
                    });
                });
                function updateDataAttendance(id){
                         var typeAttendance_1_1 = $('#typeAttendance_1_1'+id).val();
                         var typeAttendance_2_1 = $('#typeAttendance_2_1'+id).val();
                         var unit_date_2 = $('#unit_date_2'+id).val();
                         var time_late_2 = $('#time_late_2'+id).val();
                         var date = $('#date').val();
                         var link = "<?php echo route('getAttendanceItemAjax'); ?>";
                         var data = {
                         		id:id,
                                typeAttendance_1_1:typeAttendance_1_1,
                                typeAttendance_2_1: typeAttendance_2_1,
                                unit_date_2: unit_date_2,
                                time_late_2: time_late_2,
                                date:date,
                             };
                         $.ajax({
                            url: link,
                            data: data,
                            success: function (response) {
                                var obj = $.parseJSON(response);
                                if(obj.Response=='Error'){	
							        $.each( obj.Error, function( key, value) {
                                        $(".ajax_response").removeClass('alert-success').addClass("alert-danger");
                                        $(".ajax_response").append('<div style="padding:5px 0px;">'+value+'</div');
							        });
							        $(".ajax_response").show('slow');
                                }
                                else{
									Swal.fire({
											type: 'success',
											html: obj.Message,
									}).then((result) => {
										if (result.value) {
											window.location.reload();
										}
									})
                                }
                            },
                             error: function (data) {
                                 console.log('Error:', data);
                             }
                         });
                        $(".ajax_response div").remove();
                     	return false;
                }

		</script>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>