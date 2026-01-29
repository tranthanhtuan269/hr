

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>

<div class="row setting_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<div class="col-lg-10">
			<h4 class="title-fuction">Sửa cấu hình nhóm người</h4> 
			<?php if(count($errors) > 0): ?>
				<div class="alert alert-danger" role="alert">
				<ul>
				    <?php foreach($errors->all() as $error): ?>
				        <li><?php echo e($error); ?></li>
				    <?php endforeach; ?>
				</ul>
				</div>
			<?php endif; ?>
			<?php if(session('flash_message_succ') != ''): ?>
				 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
			<?php endif; ?>
			<div class="row">
				<?php echo $__env->make('layouts.luongthuong.menusetting', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<div class="col-lg-offset-2 col-lg-7">
					<form class="form-horizontal" method="post" action="" id="edit_group_personnel">
						<?php echo e(csrf_field()); ?>

						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Tên nhóm <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="title" value="<?php echo e(old('title',isset($data[$id]['title']) ? $data[$id]['title']: null )); ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Mô tả </label>
							<div class="col-sm-8">
							  	<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control"><?php echo isset($data[$id]['description']) ? $data[$id]['description']: null ; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Loại <span class="required">*</span></label>
							<div class="col-sm-8">
								<div class="table-responsive settingGroup">
								    <table class="table table-bordered table-striped">
								        <thead>
								            <tr>
								                <th class="text-center">Danh sách</th>
								                <th>
								                	<input type="checkbox" id="checkAllType">
								                </th>
								            </tr>
								        </thead>
								        <tbody class="detailType">
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Lương
								                </th>
								                <td>
								                	<input type="checkbox" name="type[0]" value="0" <?php if(  isset($data[$id]['type'][0]) && $data[$id]['type'][0]==0 ){ echo "checked"; } ?> >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Thưởng ngày lễ
								                </th>
								                <td>
								                	<input type="checkbox" name="type[1]" value="1" <?php if(  isset($data[$id]['type'][1]) && $data[$id]['type'][1]==1 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Thưởng dự án
								                </th>
								                <td>
								                	<input type="checkbox" name="type[2]" value="2" <?php if(  isset($data[$id]['type'][2]) && $data[$id]['type'][2]==2 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp ăn trưa
								                </th>
								                <td>
								                	<input type="checkbox" name="type[3]" value="3" <?php if(  isset($data[$id]['type'][3]) && $data[$id]['type'][3]==3 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp đi lại
								                </th>
								                <td>
								                	<input type="checkbox" name="type[4]" value="4" <?php if(  isset($data[$id]['type'][4]) && $data[$id]['type'][4]==4 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp điện thoại
								                </th>
								                <td>
								                	<input type="checkbox" name="type[5]" value="5" <?php if(  isset($data[$id]['type'][5]) && $data[$id]['type'][5]==5 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp trách nhiệm
								                </th>
								                <td>
								                	<input type="checkbox" name="type[6]" value="6" <?php if(  isset($data[$id]['type'][6]) && $data[$id]['type'][6]==6 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp tiền gửi xe
								                </th>
								                <td>
								                	<input type="checkbox" name="type[13]" value="13" <?php if(  isset($data[$id]['type'][13]) && $data[$id]['type'][13]==13 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Lương mặc đinh
								                </th>
								                <td>
								                	<input type="checkbox" name="type[7]" value="7" <?php if(  isset($data[$id]['type'][7]) && $data[$id]['type'][7]==7 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Thuế
								                </th>
								                <td>
								                	<input type="checkbox" name="type[8]" value="8" <?php if(  isset($data[$id]['type'][8]) && $data[$id]['type'][8]==8 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Bảo hiểm (nhân viên phải đóng)
								                </th>
								                <td>
								                	<input type="checkbox" name="type[9]" value="9" <?php if(  isset($data[$id]['type'][9]) && $data[$id]['type'][9]==9 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Bảo hiểm (công ty phải đóng)
								                </th>
								                <td>
								                	<input type="checkbox" name="type[14]" value="14" <?php if(  isset($data[$id]['type'][14]) && $data[$id]['type'][14]==14 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Đi làm muộn
								                </th>
								                <td>
								                	<input type="checkbox" name="type[10]" value="10" <?php if(  isset($data[$id]['type'][10]) && $data[$id]['type'][10]==10 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								            <tr>
												<th class="text-nowrap" scope="row">
													Tiền nghỉ phép
												</th>
												<td>
													<input type="checkbox" name="type[11]" value="11" <?php if(  isset($data[$id]['type'][11]) && $data[$id]['type'][11]==11 ){ echo "checked"; } ?>>
												</td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp khác( P/c nếu không đóng bảo hiểm )
								                </th>
								                <td>
								                	<input type="checkbox" name="type[15]" value="15" <?php if(  isset($data[$id]['type'][15]) && $data[$id]['type'][15]==15 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
										<tr>
							                <th class="text-nowrap" scope="row">
												Sử dụng Laptop cá nhân
											</th>
											<td>
												<input type="checkbox" name="type[16]" value="16" <?php if(  isset($data[$id]['type'][16]) && $data[$id]['type'][16]==16 ){ echo "checked"; } ?>>
											</td>
										</tr> 
										<tr>
												<th class="text-nowrap" scope="row">
														Tiền liên hoan
													</th>
								                <td>
								                	<input type="checkbox" name="type[17]" value="17" <?php if(  isset($data[$id]['type'][17]) && $data[$id]['type'][17]==17 ){ echo "checked"; } ?>>
								                </td>
								            </tr> 
										<tr>
							                <th class="text-nowrap" scope="row">
								                	Phụ cập nhà ở
								                </th>
								                <td>
								                	<input type="checkbox" name="type[18]" value="18" <?php if(  isset($data[$id]['type'][18]) && $data[$id]['type'][18]==18 ){ echo "checked"; } ?>>
								                </td>
								            </tr> 
										<tr>
							                <th class="text-nowrap" scope="row">
												Phụ cấp phong trào
											</th>
											<td>
												<input type="checkbox" name="type[19]" value="19" <?php if(  isset($data[$id]['type'][19]) && $data[$id]['type'][19]==19 ){ echo "checked"; } ?>>
											</td>
										</tr> 
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Chi phí khác
								                </th>
								                <td>
								                	<input type="checkbox" name="type[12]" value="12" <?php if(  isset($data[$id]['type'][12]) && $data[$id]['type'][12]==12 ){ echo "checked"; } ?>>
								                </td>
								            </tr>
								        </tbody>
								    </table>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-4 control-label">Chọn nhân viên<span class="required">*</span></label>
							<div class="col-sm-8">
								<div class="table-responsive settingGroup">
								    <table class="table table-bordered table-striped">
								        <thead>
								            <tr>
								                <th class="text-center">Họ và tên &nbsp;
													<input type="checkbox" checked id="working"> Đang làm việc &nbsp;
													<input type="checkbox" checked id="un-working"> Đã nghỉ việc
												</th>
								                <th>
								                	<input type="checkbox" id="checkAll">
								                </th>
								            </tr>
								        </thead>
								        <tbody class="detail">
								            <?php foreach($listPersonnel as $key=>$value): ?>
												<tr class="<?php echo e(($value['date_out'] != '') ? 'un-working' : 'working'); ?>">
													<th class="text-nowrap" scope="row">
														<?php echo e(str_limit( $value['fullname'], $limit = 35, $end = '...')); ?>

														<div class="ajax_response <?php echo e($value['id']); ?>" style="display: none;"></div>
													</th>
													<td>
														<input type="checkbox" name="personnel_id[<?php echo e($value['id']); ?>]" value="<?php echo e($value['id']); ?>" <?php if(  isset($listPersonnel[$key]['ticket']) && $listPersonnel[$key]['ticket']==1 ){ echo "checked"; } ?>>
													</td>
												</tr>
								            <?php endforeach; ?>

								        </tbody>
								    </table>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
								<input type="hidden" name="id" value="<?php echo e($data[$id]['id']); ?>">
							</div>
						</div>
						<div class="ajax_response_special" style="display: none;"></div>
					</form>
				</div>
			</div>
			<script type="text/javascript">
				$(document).ready(function() {
					$('#edit_group_personnel').submit(function(event) {
						var link = "<?php echo e(route('editGroupPersonalConfigAjax')); ?>";
						var selected = [];
						$('.detail input:checked').each(function() {
						    selected.push($(this).val());
						});

						var selected_type = [];
						$('.detailType input:checked').each(function() {
						    selected_type.push($(this).val());
						});

						var title = $('input[name="title"]').val();
						var id = $('input[name="id"]').val();
						var description = $('textarea[name="description"]').val();

						var data = {
							title : title,
							description : description,
							selected : selected,
							selected_type : selected_type,
							id : id,
						};
						$.ajax({
							url: link, 
							data: data,
					        success: function (response) {
								var obj = $.parseJSON(response);
			                    $.each(obj, function (index, value) {
			                    	if( value.Response=='Error' ){
										$(".ajax_response."+value.personnel_id).removeClass('alert-success').addClass("alert-error");
										$(".ajax_response."+value.personnel_id).html(value.Error);
										$(".ajax_response."+value.personnel_id).show('slow');
			                    	}else if( value.Response=='Error_special' ){
										$(".ajax_response_special").removeClass('alert-success').addClass("alert-error");
										$(".ajax_response_special").html(value.Error_special);
										$(".ajax_response_special").show('slow');
			                    	}else{
										$(".ajax_response_special").removeClass('alert-error').addClass("alert-success");
										$(".ajax_response_special").html(value.Message);
										$(".ajax_response_special").show('slow');
										setTimeout(function() {
											window.location.reload();
										}, 3000);
			                    	}
			                    });
					        },
					        error: function (data) {
					            console.log('Error:', data);
					        }
						});
						$(".ajax_response_special").hide();
						$(".ajax_response").hide();
						return false;
					})
				});

				$("#checkAll").click(function(){
				    $('.detail input:checkbox').not(this).prop('checked', this.checked);
				});

				$("#checkAllType").click(function(){
				    $('.detailType input:checkbox').not(this).prop('checked', this.checked);
				});

				$("#working").click(function(){
				    if ($('#working:checkbox:checked').length > 0) {
						$('.working').removeClass('hidden')
					} else {
						$('.working').addClass('hidden')
					}
				});

				$("#un-working").click(function(){
				    if ($('#un-working:checkbox:checked').length > 0) {
						$('.un-working').removeClass('hidden')
					} else {
						$('.un-working').addClass('hidden')
					}
				});
			</script>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>