

<?php $__env->startSection('title', 'Hồ sơ'); ?>

<?php $__env->startSection('content'); ?>
<?php
	// echo "<pre>";
	// print_r($data);die;
?>
<div class="row content-function">
	<!-- Danh muc -->
	<?php echo $__env->make('layouts.hoso.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
			  <?php if(session('flash_message_succ') != ''): ?>
		     	 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
		      <?php endif; ?>
				<h4 class="title-fuction">Quản trị hồ sơ</h4>
				<form class="form-horizontal" method="get" action="" name="contact-form">
					<div class="form-group col-lg-6">
						<label for="hoten" class="col-sm-4 control-label">Họ tên</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="hoten" id="hoten" autocomplete="off" placeholder="Họ tên" value="<?php echo e(Request::get('hoten')); ?>">
						</div>
					</div>
					<div class="form-group col-lg-6">
						<label for="email" class="col-sm-4 control-label">Email</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="email" id="email" autocomplete="off" placeholder="Email" value="<?php echo e(Request::get('email')); ?>">
						</div>
					</div>
					<div class="form-group col-lg-6">
						<label for="phone" class="col-sm-4 control-label">Điện thoại</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="phone" id="phone" autocomplete="off" placeholder="Số điện thoại" value="<?php echo e(Request::get('phone')); ?>">
						</div>
					</div>
					<div class="form-group col-lg-6">
						<label for="inputBirthday" class="col-sm-4 control-label">Đơn vị</label>
						<div class="col-sm-8">	
			               <select name="selectDepart" class="form-control select2 narrow wrap" >
				                <option value="0"> -- Đơn vị -- </option>
				                <?php echo $department; ?>

				            </select>
				            <script type="text/javascript">
								var $select2 = $('.select2').select2({
								    containerCssClass: "wrap"
								})
				            </script>
		                </div>
					</div>
					<div class="form-group col-lg-12">
			          <div class="text-center">
			            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
						<input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
			          </div>
					</div>
			        <?php echo e(csrf_field()); ?>

				</form>
			</div>
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách hồ sơ </h4>
				<?php if( count($data)>0 ): ?>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th class="text-center">Id</th>
						      <th>Họ và tên</th>
						      <th>Email </th>
						      <th>Ngày sinh </th>
						      <th>Điện thoại</th>
						      <th>Giới tính</th>
						      <th>Chức danh</th>
						      <th>Đơn vị</th>
						      <th style="width: 110px;">&nbsp;&nbsp;</th>
						    </tr>
						    
						     	<?php foreach($data as $val): ?>
						     <tr>
					     	  <td class="text-center"><?php echo e($val->id); ?></td>
						      <td><?php echo e(str_limit( $val->fullname, $limit = 45, $end = '...')); ?></td>
						      <td> <?php echo e($val->email); ?> </td>
						      <td> <?php if( !empty($val->birthday) ): ?> <?php echo e(BatvHelper::formatDate($val->birthday,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?> <?php endif; ?> </td>
						      <td> <?php echo e($val->phone_number); ?></td>
						      <?php if($val->gender == 1): ?>
						      	<td> Nam </td>
							  <?php else: ?>
							  	<td> Nữ </td>
						      <?php endif; ?>
						      <td><?php echo $val->jobs; ?></td>
						      <td><?php echo e($val->title); ?></td>
						      <td>
	                              <?php if(in_array('hoso-view',$arr_route)): ?>
	                                <a href="#" data-toggle="modal" data-target="#myModal_view<?php echo e($val->id); ?>"><img src="<?php echo e(asset('images/general/eye.png')); ?>"></a>
	                                <!--  DETAIL POPUP FUNDS -->
	                                <div id="myModal_view<?php echo e($val->id); ?>" class="modal fade" role="dialog">
	                                    <div class="modal-dialog">
	                                        <div class="modal-content clearfix">
	                                            <div class="modal-header">
	                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                                                <h4 class="modal-title text-center">Xem chi tiết</h4>
	                                                <div class="ajax_response text-center" style="display: none;"></div>
	                                            </div>
	                                            <div style="padding: 20px;">
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Họ và tên : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->first_name.' '.$val->last_name); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Giới tính : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															<?php if($val->gender == 1): ?>
																<?php echo e('Nam'); ?>

															<?php else: ?>
																<?php echo e('Nữ'); ?>

															<?php endif; ?>
	                                                    </div>
	                                                </div>
	                                                
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Ngày sinh : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                    	<?php if( $val->birthday != NULL ): ?>
	                                                        <?php echo e(BatvHelper::formatDate($val->birthday,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?>

	                                                        <?php endif; ?>
	                                                    </div>
	                                                </div>
	                                                
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Điện thoại : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->phone_number); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Số CMTND : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->indentity_card_id); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Ngày cấp CMTND : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php if( $val->indentity_card_date != NULL ): ?> <?php echo e(BatvHelper::formatDate($val->indentity_card_date,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?> <?php endif; ?>
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Nơi cấp CMTND : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->indentity_card_address); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Chức danh : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															<?php echo $val->jobs; ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Quỹ : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e(BatvHelper::getInfoFundsbyPersonnel( $val->id )); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Giờ chấm công đi làm : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															<?php echo e($val->time_attendance_machine); ?>

	                                                    </div>
	                                                </div>
	                                                <?php if( $val->date_in != NULL ): ?>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Ngày vào công ty : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															<?php echo e(BatvHelper::formatDate($val->date_in,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?>

	                                                    </div>
	                                                </div>
													<?php endif; ?>

	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Chu kỳ xét tăng lương : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															<?php echo e(( $val->salary_frequency > 0 )?$val->salary_frequency.' năm':' Không được xét '); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Loại hợp đồng : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                    	<?php
	                                                    		$contracts = BatvHelper::getContracts($val->id);
	                                                    	?>
															<?php if( $contracts ): ?>
																<?php foreach( $contracts as $k_contract => $v_contract ): ?>
																	<?php echo e($v_contract->title .': '.BatvHelper::formatDate($v_contract->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false).' - '.BatvHelper::formatDate($v_contract->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?> </br>
																<?php endforeach; ?>
															<?php endif; ?>
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Đơn vị : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															<?php echo e($val->title); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Mức lương cơ bản đóng bảo hiểm : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															<?php echo e(BatvHelper::formatPriceSpecial($val->insurrance)); ?> VNĐ
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Quê quán : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															<?php echo e($val->home_town); ?>

	                                                    </div>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>

	                              <?php endif; ?>
									<?php if(in_array('hoso-edit',$arr_route)): ?>
									   <a class="btn-edit" href="<?php echo e(route('getPersonnelEdit',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
									<?php endif; ?>
									<?php if(in_array('hoso-del',$arr_route)): ?>
							       		<a class="btn-delete" href="<?php echo e(route('getPersonnelDel',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
							        	<img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
									<?php endif; ?>
						      </td>  
						    </tr>
						    <?php endforeach; ?>
						    
					    </tbody>
					</table>
				</div>
				<?php else: ?>
					<div class="alert alert-danger" role="alert"> Không tìm thấy kết quả tìm kiếm</div>
				<?php endif; ?>
			</div>
			<div class="col-lg-12 text-right">
				<?php echo e($data->appends(Request::all())->links()); ?> 
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>