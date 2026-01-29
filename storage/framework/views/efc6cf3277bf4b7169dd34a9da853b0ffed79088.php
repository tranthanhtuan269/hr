

<?php $__env->startSection('title', 'Tài khoản'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<h4 class="title-fuction">Sửa thông tin tài khoản</h4>
	 <?php if(count($errors) > 0): ?>
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            <?php foreach($errors->all() as $error): ?>
	                <li><?php echo e($error); ?></li>
	            <?php endforeach; ?>
	        </ul>
	      </div>
	    <?php endif; ?>
			<form class="form-horizontal" id="formsubmit" method="post" action="" enctype="multipart/form-data">
			<?php echo e(csrf_field()); ?>

			<div class="form-group">
				<label for="inputName" class="col-sm-4 control-label">Họ và tên</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Họ tên" value="<?php echo e(old('inputName',isset($data->name) ? $data->name : null )); ?>" required <?php if($errors->has('inputName')): ?> autofocus <?php endif; ?>>
				</div>
			</div>
			<div class="form-group">
				<label for="fileImage" class="col-sm-4 control-label">Ảnh đại diện</label>
				<div class="col-sm-8">
				    <div class="avatar">
				    	<img id="blah" src="#" style="width:150px;height:150px;display: none;" />
				    <?php if(!empty($data->avatar)): ?>
				    	<img class="avatar_first" style="width:150px;height:150px" src="<?php echo e(asset('uploads/users/'.$data->avatar)); ?>" alt="avatar">
				    <?php else: ?>
						<img class="avatar_first" style="width:150px;height:150px" src="<?php echo e(asset('images/dashboard/avatar.png')); ?>">
				    <?php endif; ?>
				    </div>
					<input type="file"  name="fileImage" id="fileImage" accept="image/*">
					
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" id="btn-submit" class="btn btn-sm btn-orange">Cập nhật</button>
					<a href="<?php echo e(route('getTaikhoanInfo',['id'=>Auth::user()->id])); ?>" class="btn btn-sm btn-grey">Nhập lại</a>
				</div>
			</div>
		</form>
		<script type="text/javascript">
			function readURL(input) {
			    if (input.files && input.files[0]) {
			        var reader = new FileReader();
			        reader.onload = function (e) {
			            $('#blah').attr('src', e.target.result);
			        }

			        reader.readAsDataURL(input.files[0]);
			         $('#blah').show();
			         $('.avatar_first').hide();
			    }else{
			    	$('#blah').hide();
			         $('.avatar_first').show();
			    }
			}

			$("#fileImage").change(function(){
			    readURL(this);
			});
		</script>
	</div>
	<div class="col-lg-3"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>