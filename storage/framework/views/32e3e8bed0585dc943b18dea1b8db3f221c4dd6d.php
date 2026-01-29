

<?php $__env->startSection('title', 'Quá trình công tác'); ?>

<?php $__env->startSection('content'); ?>
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
			      <th style="width: 20%;">Thâm niên</th>
			      <th class="text-left">
						<?php echo e(BatvHelper::getSeniority($id)); ?>	      
			      </th>
			    </tr>
		    </tbody>
	    </table>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Quá trình công tác</th>
		      <th class="text-center">
					<?php if(in_array('quatrinh-add',$arr_route)): ?>
						<a href="<?php echo e(route('getHistoryAdd',['id'=>$id])); ?>"><img src="<?php echo e(asset('images/general/add_2.png')); ?>"></a>
					<?php endif; ?>		      
		      </th>
		    </tr>
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Chức danh - Đơn vị</td>
		    	<td></td>
		    </tr>
		    <?php if(!empty($data)): ?>
		    	<?php foreach($data as $val): ?>
		    <tr>
		    	<td> <?php echo e($val->date_start); ?> - <?php echo e($val->date_end); ?> </td>
		    	<td><?php echo e($val->job); ?> - <?php echo e($val->title); ?> </td>
		    	<td>
					<?php if(in_array('quatrinh-edit',$arr_route)): ?>
						<a href="<?php echo e(url('toh_hrm/quatrinh/edit',[$id,$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a> 
					<?php endif; ?>	
		    	
					<?php if(in_array('quatrinh-del',$arr_route)): ?>
						<a class="btn-delete" href="<?php echo e(url('toh_hrm/quatrinh/del',[$id,$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
					<?php endif; ?>	
		    		
		    	</td>
		    </tr>
		        <?php endforeach; ?>
		    <?php endif; ?>
		    </tbody>
	    </table>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Hệ số chức danh</th>
		       <th class="text-center">
					<?php if(in_array('quatrinh-addratio',$arr_route)): ?>
		       			<a href="<?php echo e(route('getHistoryAddRatio',['id'=>$id])); ?>"><img src="<?php echo e(asset('images/general/add_2.png')); ?>"></a> 
					<?php endif; ?>	
		       </th>
		    </tr>
		    
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Hệ số chức danh</td>
		    	<td></td>
		    </tr>

		    <?php if(!empty($ratio)): ?>
		    	<?php foreach($ratio as $val): ?>
		    <tr>
		    	<td><?php echo e($val->apply_from); ?> - <?php echo e($val->apply_to); ?>  </td>
		    	<td><?php echo e($val->ratio); ?></td>
		    	<td>
					<?php if(in_array('quatrinh-editratio',$arr_route)): ?>
		       			<a href="<?php echo e(url('toh_hrm/quatrinh/editratio',[$id,$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
					<?php endif; ?>	
					<?php if(in_array('quatrinh-delratio',$arr_route)): ?>
		    			<a class="btn-delete" href="<?php echo e(url('toh_hrm/quatrinh/delratio',[$id,$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
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