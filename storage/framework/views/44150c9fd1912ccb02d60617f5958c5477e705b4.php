

<?php $__env->startSection('title', 'Đánh giá'); ?>

<?php $__env->startSection('content'); ?>

<div class="row content-support">
	<div class="col-lg-2">
		<h4 class="title-fuction">Danh mục</h4>
		<?php if(in_array('danhgia-viethuongdan',$arr_route)): ?>
        	<p><a href="<?php echo e(route('getEvaluationSupport')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hướng dẫn đánh giá</a></p>
		<?php endif; ?>
		<?php if(in_array('danhgia-danhsachbotieuchi',$arr_route)): ?>
        	<p><a href="<?php echo e(route('listDepartmentCriteria')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình bộ tiêu chí</a></p>
		<?php endif; ?>
		<?php if(in_array('danhgia-danhsachtieuchi',$arr_route)): ?>
        	<p><a href="<?php echo e(route('getEvaluationCriteria')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình tiêu chí chi tiết</a></p>
		<?php endif; ?>
	</div>
	<div class="col-lg-10">
		<h4 class="title-fuction"> Hướng dẫn đánh giá</h4>
		<div class="table-responsive">
			<table class="table table-hover">
			    <tbody>
			        <tr class="text-center">
			            <th>Nội dung</th>
			            <th>Thao tác</th>
			        </tr>
			        <tr>
			            <td>
			            	<?php echo $data->criteria_content; ?>

			            </td>
			            <td>
							<?php if(in_array('danhgia-edit',$arr_route)): ?>
                            	<a class="btn-edit" href="<?php echo e(route('editEvaluationSupport',['id'=>$data->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
							<?php endif; ?>
<!--                             <a class="btn-delete" href="<?php echo e(route('deleteEvaluationSupport',['id'=>$data->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                            <img src="<?php echo e(asset('images/general/remove.png')); ?>"></a> -->
			            </td>
			        </tr>

	            </tbody>
			</table>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>