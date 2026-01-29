

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
   <div class="col-lg-1"></div>
   <div class="col-lg-10">
   <h4 class="title-fuction">Thêm chu kỳ xét tăng lương</h4>
   <?php if(count($errors) > 0): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach($errors->all() as $error): ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
	<?php endif; ?>
   <?php if(session('flash_message_err') != ''): ?>
	<div class="alert alert-danger" role="alert"><?php echo e(session('flash_message_err')); ?></div>
	<?php endif; ?>
	<?php if(session('flash_message_succ') != ''): ?>
	<div class="alert alert-success" role="alert"><?php echo e(session('flash_message_succ')); ?></div>
	<?php endif; ?>

<?php
	// echo "<pre>";
	// print_r($data);die;
?>
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			<?php echo e(csrf_field()); ?>

			<div class="form-group">
				<label class="col-sm-3 control-label">Giá trị</label>
				<div class="col-sm-5">
					<input type="number" class="form-control" name="value" required step="0.5" value="<?php echo e(old('value')); ?>" min="0">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Mô tả</label>
				<div class="col-sm-5">
					<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control"><?php echo e(old('description')); ?></textarea>
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
				</div>
			</div>
		</form>

			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách các chu kỳ</h4>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th>STT</th>
						      <th>Giá trị</th>
						      <th> Mô tả </th>
						      <th>&nbsp;&nbsp;</th>
						    </tr>
						    <?php if(!empty($data)): ?>
								<?php 
									$i =  1;
								?>
						     	<?php foreach($data as $val): ?>
						     <tr>
						      <td><?php echo e($i); ?></td>
						      <td> <?php echo e($val->value); ?> </td>
						      <td> <?php echo e($val->description); ?> </td>
						      <td>
									<?php if(in_array('luongthuong-suachukyxettangluong',$arr_route)): ?>
							       		<a class="btn-edit" href="<?php echo e(route('getSettingPeriodSalaryEdit',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
									<?php endif; ?>
									<?php if(in_array('luongthuong-xoachukyxettangluong',$arr_route)): ?>
										<a class="btn-delete" href="<?php echo e(route('deleteSettingPeriodSalary',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
									<?php endif; ?>
						      </td>  
						    </tr>
						    	<?php $i++ ?>
						    	<?php endforeach; ?>
						    <?php endif; ?>
					    </tbody>
					</table>
				</div>
			</div>
	</div>



   <div class="col-lg-1"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>