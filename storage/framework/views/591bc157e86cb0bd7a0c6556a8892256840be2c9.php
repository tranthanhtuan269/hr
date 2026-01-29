

<?php $__env->startSection('title', 'Quá trình công tác'); ?>

<?php $__env->startSection('content'); ?>
<div class="row content-function">
	<div class="col-lg-12">
		<h4 class="title-fuction">Thêm quá trình công tác</h4>
		 
		 <?php if(count($errors) > 0): ?>
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            <?php foreach($errors->all() as $error): ?>
	                <li><?php echo e($error); ?></li>
	            <?php endforeach; ?>
	        </ul>
	      </div>
	      <?php endif; ?>
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			<?php echo e(csrf_field()); ?>

			<div class="form-group col-lg-6">
				<label for="startDate" class="col-sm-4 control-label">Từ ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="startDate" id="startDate" required <?php if($errors->has('startDate')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('startDate',isset($data->startDate) ? $data->startDate : null)); ?>" <?php endif; ?> >
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="endDate" class="col-sm-4 control-label">Đến ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="endDate" id="endDate" required <?php if($errors->has('endDate')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('endDate',isset($data->endDate) ? $data->endDate : null)); ?>" <?php endif; ?> >
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="inputPossition" class="col-sm-4 control-label">Chức danh</label>
				<div class="col-sm-8">
					<select name="selectJobs" class="form-control" <?php if($errors->has('selectJobs')): ?> autofocus <?php endif; ?> >
						<option value=""> -- Chức danh -- </option>
						<?php if(!empty($listJobs)): ?>
							<?php foreach($listJobs as $job): ?>
								<option value="<?php echo e($job->id); ?>" <?php if(old('selectJobs') == $job->id): ?> selected="selected" <?php endif; ?>><?php echo e($job->title); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="inputBirthday" class="col-sm-4 control-label">Đơn vị</label>
				<div class="col-sm-8">	
	              <select name="selectDepart" class="form-control" <?php if($errors->has('selectDepart')): ?> autofocus <?php endif; ?>>
		                <option value=""> -- Đơn vị -- </option>
		                <?php echo $department; ?>

		            </select>
                </div>
			</div>
			 <div class="form-group col-lg-12">
	          <div class="text-center">
	            <button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
	            <a class="btn btn-sm btn-grey" href="<?php echo e(route('getHistoryDetail',['id'=>$id])); ?>">Nhập lại</a>
	          </div>
	        </div>
		</form>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>