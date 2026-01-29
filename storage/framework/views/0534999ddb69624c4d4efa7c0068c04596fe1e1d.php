

<?php $__env->startSection('title', 'Hồ sơ'); ?>

<?php $__env->startSection('content'); ?>
<?php
	// echo "<pre>";
	// print_r($data);die;
?>
<div class="row">
  	<div class="col-lg-3"></div>
  	<div class="col-lg-7">
  	    <?php if(session('flash_message_succ') != ''): ?>
	      <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
	     <?php endif; ?>
	     <h4 class="text-center">Họ tên : <span> <?php echo e($name); ?> </span></h4>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Quỹ</th>
		       <th class="text-center">
					<?php if(in_array('hoso-themquynhanvien',$arr_route)): ?>
		       			<a href="<?php echo e(route('getFundsAddPersonnel',['id'=>$id])); ?>"><img src="<?php echo e(asset('images/general/add_2.png')); ?>"></a> 
					<?php endif; ?>	
		       </th>
		    </tr>
		    
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Thuộc quỹ</td>
		    	<td></td>
		    </tr>
		    <?php if(!empty($data)): ?>
		    	<?php foreach($data as $val): ?>
		    <tr>
		    	<td>Từ <b><?php echo e(BatvHelper::formatDate($val->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></b> đến <b><?php echo e(BatvHelper::formatDate($val->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></b>  </td>
		    	<td><b><?php echo e($val->title); ?></b></td>
		    	<td>
					<?php if(in_array('hoso-suaquynhanvien',$arr_route)): ?>
						<a href="<?php echo e(url('toh_hrm/hoso/suaquynhanvien',[$id,$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a> 
					<?php endif; ?>	
		    	
					<?php if(in_array('hoso-xoaquynhanvien',$arr_route)): ?>
						<a class="btn-delete" href="<?php echo e(url('toh_hrm/hoso/xoaquynhanvien',[$id,$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
					<?php endif; ?>	
		    		
		    	</td>
		    </tr>
		        <?php endforeach; ?>
		    <?php endif; ?>

		    </tbody>
	    </table>
	</div>
	<div class="col-lg-3"></div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>