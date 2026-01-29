

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>

<div class="row setting_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<div class="col-lg-10">
			<h4 class="title-fuction">Sửa cấu hình công thức </h4> 
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
			<div class="ajax_response" style="display: none;"></div>
			<div class="row">
				<?php echo $__env->make('layouts.luongthuong.menusetting', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<div class="col-lg-12">
					<form class="form-horizontal" method="post" action="" id="contactForm">
						<?php echo e(csrf_field()); ?>

						<div class="form-group">
							<label class="col-sm-4 control-label">Tên công thức <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="title" value="<?php echo e(old('title',isset($data->title) ? $data->title: null )); ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Loại <span class="required">*</span></label>
							<div class="col-sm-6">
							  	<select class="form-control" name="gender" id="gender">
								    <option value="0" <?php echo ( isset($data->type) && $data->type==0 )? 'selected':''; ?>>Lương</option>
								    <option value="1" <?php echo ( isset($data->type) && $data->type==1 )? 'selected':''; ?>>Thưởng ngày lễ</option>
								    <option value="2" <?php echo ( isset($data->type) && $data->type==2 )? 'selected':''; ?>>Thưởng dự án</option>
								    <option value="3" <?php echo ( isset($data->type) && $data->type==3 )? 'selected':''; ?>>Phụ cấp ăn trưa</option>
								    <option value="4" <?php echo ( isset($data->type) && $data->type==4 )? 'selected':''; ?>>Phụ cấp xăng xe</option>
								    <option value="5" <?php echo ( isset($data->type) && $data->type==5 )? 'selected':''; ?>>Phụ cấp điện thoại</option>
								    <option value="6" <?php echo ( isset($data->type) && $data->type==6 )? 'selected':''; ?>>Phụ cấp trách nhiệm</option>
								    <option value="13" <?php echo ( isset($data->type) && $data->type==13 )? 'selected':''; ?>>Phụ cấp tiền gửi xe</option>
								    <option value="7" <?php echo ( isset($data->type) && $data->type==7 )? 'selected':''; ?>>Lương mặc đinh</option>
								    <option value="8" <?php echo ( isset($data->type) && $data->type==8 )? 'selected':''; ?>>Thuế</option>
								    <option value="9" <?php echo ( isset($data->type) && $data->type==9 )? 'selected':''; ?>>Bảo hiểm (nhân viên phải đóng)</option>
								    <option value="14" <?php echo ( isset($data->type) && $data->type==14 )? 'selected':''; ?>>Bảo hiểm (công ty phải đóng)</option>
								    <option value="10" <?php echo ( isset($data->type) && $data->type==10 )? 'selected':''; ?>>Đi làm muộn</option>
								    <option value="11" <?php echo ( isset($data->type) && $data->type==11 )? 'selected':''; ?>>Tiền nghỉ phép</option> 
								    <option value="15" <?php echo ( isset($data->type) && $data->type==15 )? 'selected':''; ?>>Phụ cấp khác( P/c nếu không đóng bảo hiểm )</option>
								    <option value="16" <?php echo ( isset($data->type) && $data->type==16 )? 'selected':''; ?>>Sử dụng Laptop cá nhân</option> 
                                    <option value="17" <?php echo ( isset($data->type) && $data->type==17 )? 'selected':''; ?>>Tiền liên hoan</option>
                                    <option value="18" <?php echo ( isset($data->type) && $data->type==18 )? 'selected':''; ?>>Phụ cập nhà ở</option>
									<option value="19" <?php echo ( isset($data->type) && $data->type==19 )? 'selected':''; ?>>Phụ cấp phong trào</option>
								    <option value="12" <?php echo ( isset($data->type) && $data->type==12 )? 'selected':''; ?>>Chi phí khác</option>
							  	</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Nhóm <span class="required">*</span></label>
							<div class="col-sm-6">
								<select class="form-control" name="group" id="group">
								<?php
									if( count($groupPersonal)>0 ){
										foreach ($groupPersonal as $value) {
								?>
											<option value="<?php echo $value->id; ?>" <?php echo ( isset($data->personnel_group_id) && $data->personnel_group_id==$value->id )? 'selected':''; ?>><?php echo $value->title; ?></option>

								<?php
										}
									}

								?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Kiểu <span class="required">*</span></label>
								<?php
									if( $data->is_fixed==1 ){

								?>
										<script type="text/javascript">
					                        $('.repice_fixed_parent .repice_reference').remove();
										</script>
										<style type="text/css">
											.setting_salary .repice_reference{ display: none; }
										</style>
								<?php
									}else{
										$string = $data->value;
										$repice = explode(' ',$string);
										$string_id = $data->value_id;
										$recipe_id = explode(';',$string_id);
										// echo "<pre>";
										// print_r($recipe_id);
								?>
										<script type="text/javascript">
					                        $('.repice_fixed_parent .repice_fixed').remove();
										</script>
										<style type="text/css">
											.setting_salary .repice_fixed{ display: none;}
										</style>
								<?php
									}
								?>
							<div class="col-sm-6">
							  	<select class="form-control" name="type" id="mySelect">
								    <option value="1" <?php echo (isset($data->is_fixed) && $data->is_fixed==1 )?"selected":""; ?> >Fixed</option>
								    <option value="0" <?php echo (isset($data->is_fixed) && $data->is_fixed==0 )?"selected":""; ?>>Reference</option>
							  	</select>
							</div>
						</div>
						<div class="form-group">
							<label for="selectMonth" class="col-sm-4 control-label">Tháng áp dụng <span class="required">*</span></label>
							<div class="col-sm-6">
								 <select name="selectMonth" class="form-control" id="selectMonth">
									 <?php 
						                for ($i = 1; $i <= 12; $i++){
										    $month = ($i < 10) ? '0'.$i : $i ;
										    echo '<option value="'.$month.'"';
										    if (!empty(Request::input('selectMonth')) || isset($data->applied_month) ) {
										    	if ($i == Request::input('selectMonth') || $data->applied_month ==$i) echo ' selected="selected"';
										    }else{
										    	if ($i == date("n")) echo ' selected="selected"';
										    }						    
										    echo '>'.$month.'</option>';
										}
									 ?>
										<option value="0" <?php echo (isset($data->applied_month) && $data->applied_month =="0")?"selected":"";; ?> >All</option>
					             </select>
				             </div>
						</div>
						<div class="form-group">
							<label for="startDate" class="col-sm-4 control-label">Ngày hiệu lực <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="text" class="datepicker form-control" name="startDate" id="startDate" value="<?php echo BatvHelper::formatDate($data->valid_from,'Y-m-d',$formatDate="d/m/Y",$timeFormat="H:i:s",$time=false); ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="endDate" class="col-sm-4 control-label">Ngày hết hiệu lực <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="text" class="datepicker form-control" name="endDate" id="endDate" value="<?php echo BatvHelper::formatDate($data->valid_to,'Y-m-d',$formatDate="d/m/Y",$timeFormat="H:i:s",$time=false); ?>">
							</div>
						</div>
                        <div class="form-group repice_fixed_parent">
                            <div class="repice_fixed">
								<label class="col-sm-4 control-label">Giá trị <span class="required">*</span></label>
								<div class="col-sm-6 fixed_special">
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            $("#gender").change(function(){
                                                var check_gender = $("#gender").val();
                                                if( check_gender == 12 ){
                                                    $('.repice_fixed_parent .fixed_special input').remove();
                                                    $('.repice_fixed_parent .fixed_special').append('<input type="number" class="form-control" name="recipe_fixed" step="" value="<?php echo e(old('value',isset($data->value) ? $data->value: null )); ?>" max="100000000" required/>');
                                                }else{
                                                    $('.repice_fixed_parent .fixed_special input').remove();
                                                    $('.repice_fixed_parent .fixed_special').append('<input type="number" class="form-control" name="recipe_fixed" step="" value="<?php echo e(old('value',isset($data->value) ? $data->value: null )); ?>" min="1" max="100000000" required="" />');
                                                }
                                            });
                                        });
                                    </script>
                                    <?php if( $data->type == 12 ): ?>
										<input type="number" class="form-control" name="recipe_fixed" step="" value="<?php echo e(old('value',isset($data->value) ? $data->value: null )); ?>" max="100000000" required="" />
                                    <?php else: ?>
										<input type="number" class="form-control" name="recipe_fixed" step="" value="<?php echo e(old('value',isset($data->value) ? $data->value: null )); ?>" min="1" max="100000000" required="" />
                                    <?php endif; ?>
									
								</div>
                            </div>
                        </div>
						<div class="form-group repice_reference">
						    <div class="row prameters_group">
						        <div class="col-lg-5 chose_prameters">
						            <b>Chọn tham số <span class="required">*</span></b>
						            <div class="table-responsive-special">
							            <table class="table">
							                <thead>
							                    <tr>
							                        <th>Tham số</th>
							                        <th>Mô tả</th>
							                    </tr>
							                </thead>
							                <tbody>
							                    <?php $i=1;
							                    	// echo "<pre>";
							                    	// print_r($parameters);die;
							                        foreach($parameters as $index => $item){ 
						                        ?>
							                    <tr class="item_parameter item_repice">
							                        <td data-id="<?php echo $item->id; ?>" class="item"><?php echo $item->title;?></td>
							                        <td><?php echo e($item->description); ?></td>
							                        <input id="id_parameter" type="hidden" name="id_parameter" value="<?php echo $item->id; ?>">
							                    </tr>
							                    <?php $i++;} ?>
							                </tbody>
							            </table>
						            </div>
						        </div>
						        <div class="col-lg-2 chose_operator">
						            <b>Chọn toán tử <span class="required">*</span></b>
						            <table class="table">
						                <thead>
						                    <tr>
						                        <th>#</th>
						                        <th>Mô tả</th>
						                        <th class="text-center">Toán tử </th>
						                    </tr>
						                </thead>
						                <tbody>
						                    <tr class="item_operator">
						                        <th scope="row">1</th>
						                        <td>Cộng</td>
						                        <td class="text-center">+</td>
						                    </tr>
						                    <tr class="item_operator">
						                        <th scope="row">2</th>
						                        <td>Trừ</td>
						                        <td class="text-center">-</td>
						                    </tr>
						                    <tr class="item_operator">
						                        <th scope="row">3</th>
						                        <td>Nhân</td>
						                        <td class="text-center">*</td>
						                    </tr>
						                    <tr class="item_operator">
						                        <th scope="row">4</th>
						                        <td>Chia</td>
						                        <td class="text-center">/</td>
						                    </tr>
						                    <tr class="item_operator">
						                        <th scope="row">5</th>
						                        <td></td>
						                        <td class="text-center">(</td>
						                    </tr>
						                    <tr class="item_operator">
						                        <th scope="row">6</th>
						                        <td></td>
						                        <td class="text-center">)</td>
						                    </tr>
						                </tbody>
						            </table>
						        </div>
						        <div class="col-lg-5 chose_operator">
									<div class="row recipe_group">
									    <div class="col-lg-12">
									    	<?php
									    		if( isset($repice) ){
										    		foreach ($repice as $key => $value) {
										    			if( $value !='' ){
						    				?>
															<span class="item_recipe"><?php echo $value; ?></span>
						    				<?php
										    			}
										    		}
								    			}
									    	?>
									    </div>
									    <div class="recipe_id" style="display: none;">
									    	<?php
									    		if( isset($recipe_id) ){
										    		foreach ($recipe_id as $key => $value) {
										    			if( $value !='' ){
						    				?>
															<span class="item_recipe"><?php echo $value; ?></span>
						    				<?php
										    			}
										    		}
								    			}
									    	?>
									    </div>
									</div>
									<div class="form-group text-center">
										<button type="button" class="btn btn-sm btn-grey repice_back">Xóa</button>
									</div>
						        </div>
						    </div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-6">
								<button type="submit" class="btn btn-sm btn-orange repice_group_save">Cập nhật</button>
								<input type="hidden" name="id" id="id_income_config" value="<?php echo $data->id; ?>">
							</div>
						</div>
					</form>
				</div>
			</div>
			<script type="text/javascript">
				$('.item_parameter').click(function(){
					var id = $('.item').attr('data-id');
					var html = '<span class="item_recipe">'+$(this).find('.item').html()+'</span>';
					$('.recipe_group .col-lg-12').append(html);

					var recipe_id = '<span class="item_recipe">'+$(this).find('#id_parameter').val()+'</span>';
					$('.recipe_group .recipe_id').append(recipe_id);
				})

				$('.item_operator').click(function(){
					var html = '<span class="item_recipe">'+$(this).find('td').eq(1).html()+'</span>';
					$('.recipe_group .col-lg-12').append(html);

					var recipe_id = '<span class="item_recipe">'+$(this).find('td').eq(1).html()+'</span>';
					$('.recipe_group .recipe_id').append(html);
				})

				$('body').on('click','.repice_back',function(){
					var html = $('.recipe_group .col-lg-12').html();
					if(html == ''){
						return;
					}
					var html = $('.recipe_group .col-lg-12').find('.item_recipe');
					var length = html.length - 1;
					var string = '';
					for(var i = 0; i < length; i ++){
						if(string == ''){
							string = '<span class="item_recipe">'+html.eq(i).html()+'</span>';
						}else{
							string = string + ' <span class="item_recipe">'+html.eq(i).html()+'</span>';
						}
					}
					$('.recipe_group .col-lg-12').html(string);

					var recipe_id = $('.recipe_group .recipe_id').html();
					if(recipe_id == ''){
						return;
					}
					var recipe_id = $('.recipe_group .recipe_id').find('.item_recipe');
					var length_2 = recipe_id.length - 1;
					var string_id = '';
					for(var j = 0; j < length_2; j ++){
						if(string_id == ''){
							string_id = '<span class="item_recipe">'+recipe_id.eq(j).html()+'</span>';
						}else{
							string_id = string_id + ' <span class="item_recipe">'+recipe_id.eq(j).html()+'</span>';
						}
					}

					$('.recipe_group .recipe_id').html(string_id);
				});

                $(document).ready(function() {

                    $('#contactForm').submit(function(event) {
                            var id = $('input[name="id"]').val();
                            var title = $('input[name="title"]').val();
                            var link = "<?php echo route('editRecipeConfigAjax'); ?>";
                            var _token = $('input[name="_token"]').val();
                            var type = $("#mySelect").val();
                            var gender = $("#gender").val();
                            var group = $("#group").val();
                            var selectMonth = $("#selectMonth").val();
                            var startDate = $('#startDate').val();
                            var endDate = $('#endDate').val();
                            var recipe_fixed = $('input[name="recipe_fixed"]').val();
                            var html = $('.recipe_group .col-lg-12').find('.item_recipe');

                            var string = '';
                            for(var i = 0; i < html.length; i ++){
                                if(string == ''){
                                    string = html.eq(i).html();
                                }else{
                                    string = string + ' ' +html.eq(i).html();
                                }
                            }

                            var string_id= '';
                            var recipe_id = $('.recipe_group .recipe_id').find('.item_recipe');
                            for(var j = 0; j < recipe_id.length; j ++){
                                if(string_id == ''){
                                    string_id = recipe_id.eq(j).html();

                                }else{
                                    string_id = string_id + ';' +recipe_id.eq(j).html();
                                }
                            }
                            
                            var data = {
                                    id: id,
                                    title:title,
                                    recipe_reference: string,
                                    recipe_reference_id: string_id,
                                    recipe_fixed: recipe_fixed,
                                    type: type,
                                    gender: gender,
                                    group: group,
                                    selectMonth: selectMonth,
                                    startDate: startDate,
                                    endDate: endDate,
                                    _token:_token
                                };
                            $.ajax({
                                url: link, //Relative or absolute path to response.php file
                                data: data,
                                success: function (response) {
                                    var obj = $.parseJSON(response);
                                    if(obj.Response=='Error'){
                                        $(".ajax_response").removeClass('alert-success').addClass("alert-danger");
                                        $(".ajax_response").html(obj.Error);
                                        $(".ajax_response").show('slow');
                                    }
                                    else{
                                        $(".ajax_response").removeClass('alert-danger').addClass("alert-success");
                                        $(".ajax_response").html(obj.Message);
                                        $(".ajax_response").show('slow');
	                                    setTimeout(function() {
	                                        window.location.reload();
	                                    }, 3000);
                                    }
                                    $('html, body').animate({
                                        scrollTop: $("body").offset().top
                                    }, 1000);
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        return false;
                    });
				});
                $('#mySelect').on('change', function() {
                    if( this.value == 0 ){
                        $('.repice_fixed_parent .repice_fixed').remove();
                        $('.repice_reference').css("display", "block");
                    }else{
                    	$('.repice_fixed').remove();
                    	$('.repice_fixed_parent').append('<div class="repice_fixed"><label class="col-sm-4 control-label">Giá trị <span class="required">*</span></label><div class="col-sm-6"><input type="number" class="form-control" name="recipe_fixed" step="" min="1" max="100000000" required="" value="<?php echo e(isset($data->value) ? $data->value: null); ?>"/></div></div>');
					 	$('.repice_fixed').css("display", "block");
						
                        $('.repice_reference').css("display", "none");
                    }
                })
			</script>
	</div>
</div>
<?php
	if( $data->is_fixed==1 ){
?>
		<script type="text/javascript">
            $('.repice_fixed_parent .repice_reference').remove();
		</script>
<?php
	}else{
?>
		<script type="text/javascript">
            $('.repice_fixed_parent .repice_fixed').remove();
		</script>
<?php
	}
?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>