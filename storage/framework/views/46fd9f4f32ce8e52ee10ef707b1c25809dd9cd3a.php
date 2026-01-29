

<?php $__env->startSection('title', 'Tài khoản'); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
	.table-borderless tbody tr td, .table-borderless tbody tr th, .table-borderless thead tr th {
	    border: none;
	}
	.table-borderless {width:200px;}
    @media  screen and (min-width: 768px) {
    	.table-borderless {text-align: left;}
    }
</style>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-8">
		<h4 class="title-fuction">Thông tin tài khoản</h4>
		<?php if(session('flash_message_succ') != ''): ?>
			<div class="alert alert-success" role="alert"><?php echo e(session('flash_message_succ')); ?></div>
		<?php endif; ?>
			<div class="col-lg-4 col-md-4">
			 <?php if(!empty($data->avatar)): ?>
				<img style="width:150px;height:150px" src="<?php echo e(asset('uploads/users/'.$data->avatar)); ?>">
			 <?php else: ?>
			 	<img style="width:150px;height:150px" src="<?php echo e(asset('images/dashboard/avatar.png')); ?>">
			 <?php endif; ?>
			</div>
			<br/>
			<div class="col-lg-8 col-md-8">
			   <table class="table table-borderless">
			   	 <tr>
			   	 	<td>Họ và tên</td>
			   	 	<td><?php echo e(str_limit(Auth::user()->name, $limit = 30, $end = '...')); ?></td>
			   	 </tr>
			   	 <tr>
			   	 	<td>Email</td>
			   	 	<td><?php echo e(Auth::user()->email); ?></td>
			   	 </tr>
			   	 <tr>
			   	 	<td><a href="<?php echo e(route('getTaikhoanEditInfo',['id'=>Auth::user()->id])); ?>" class="btn btn-sm btn-orange">Cập nhật</a></td>
			
					<td><a href="<?php echo e(route('getTaikhoanEditPass',['id'=>Auth::user()->id])); ?>" class="btn btn-sm btn-orange">Đổi mật khẩu</a></td>
			   	 </tr>
			   </table>
			</div>
	</div>
	<div class="col-lg-3"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>