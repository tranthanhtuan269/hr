

<?php $__env->startSection('title', 'TOH HRMS'); ?>

<?php $__env->startSection('content'); ?>

<div class="row box_salary">
	<!-- Danh muc -->
	<?php echo $__env->make('layouts.chucnangkhac.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<div class="col-lg-10">

			<h4 class="title-fuction">
				Cấu hình chấm công nghỉ phép
			</h4>
			<?php if(count($errors) > 0): ?>
				<div class="alert alert-danger" role="alert">
				<ul>
				    <?php foreach($errors->all() as $error): ?>
				        <li><?php echo e($error); ?></li>
				    <?php endforeach; ?>
				</ul>
				</div>
			<?php endif; ?>
			<div class="col-lg-12">
				<?php if(session('flash_message_succ') != ''): ?>
					 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
				<?php endif; ?>
			</div>
			<form  class="form-horizontal" method="POST">
				<div class="form-group">
					<label class="col-sm-3 control-label">Chọn nhân viên <span class="required">*</span></label>
					<div class="col-sm-5">	
                        <?php if(!empty($listPersonnel)): ?>
                            <select id="my-select-2" name="personnel_id[]" multiple="multiple">
                                <?php foreach($listPersonnel as $val): ?>
                                     <option value="<?php echo e($val->id); ?>" <?php echo e(( is_array(old('personnel_id')) && in_array($val->id, old('personnel_id')) ) ? 'selected ' : ''); ?> ><?php echo e($val->fullname); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
						<script type="text/javascript">
							$(function() {
							    $('#my-select-2').searchableOptionList({
							        showSelectAll: true,
							        maxHeight: '250px',
							    });
							});    
						</script>
				    </div>
				</div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Thời gian hiệu lực <span class="required">*</span></label>
                    <div class="col-sm-5">
                        <input type="text" class="datepicker form-control" name="apply_from" pattern="\d{1,2}/\d{1,2}/\d{4}" value="<?php echo e(old('apply_from')); ?>" required >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Thời gian hết hiệu lực <span class="required">*</span></label>
                    <div class="col-sm-5">
                        <input type="text" class="datepicker form-control" name="apply_to" pattern="\d{1,2}/\d{1,2}/\d{4}" value="<?php echo e(old('apply_to')); ?>" required>
                    </div>
                </div>
				<div class="text-center">
					<input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Thêm mới">
				</div>
	            <?php echo e(csrf_field()); ?>

			</form>

			<h4 class="title-fuction">Danh sách nhân viên được cấu hình</h4>
			<div class="table-responsive" >
			    <table class="table table-bordered table-striped">
			        <thead>
			            <tr>
			                <th class="text-center">STT</th>
			                <th class="text-center">Họ và tên</th>
			                <th class="text-center">Thời gian</th>
			            </tr>
			        </thead>
			        <tbody>	
						    <?php if(!empty($data)): ?>
						    	<?php $tmp=1; ?>
						     	<?php foreach($data as $key=>$val): ?>
							     <tr>
							      	<td class="text-center"> <?php echo e($tmp); ?> </td> 
							      	<td style="text-align: left; padding-left: 30px;">
							      		<?php echo e(str_limit( BatvHelper::getInfoUser($val->personnel_id), $limit = 35, $end = '...')); ?>

								    </td>
								    <td>
								    	<?php
								    		$apply_from = explode(',', $val->apply_from);
								    		$apply_to = explode(',', $val->apply_to);
							    		?>
							    		<?php foreach($apply_from as $k => $v): ?> 
							    			<p>Từ <?php echo e(BatvHelper::formatDate( $v,'Y-m-d', $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?> đến <?php echo e(BatvHelper::formatDate( $apply_to[$k],'Y-m-d', $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?>

					                        <?php if(in_array('chucnangkhac-xoacauhinhchamcongnghiphep',$arr_route)): ?>
									       		<a class="btn-delete" href="<?php echo e(route('deletesettingAbsentAttendance',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
									       		<img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
					                        <?php endif; ?>
							    			</p>
							    		<?php endforeach; ?>
								    </td>
							    </tr>
							    <?php $tmp++; ?>
							    <?php endforeach; ?>
						    <?php endif; ?>
			        </tbody>
			    </table>
			</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>