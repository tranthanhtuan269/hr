

<?php $__env->startSection('title', 'Phòng ban'); ?>

<?php $__env->startSection('content'); ?>
<div class="row content-function">
	<!-- Danh muc -->
	<?php echo $__env->make('layouts.hoso.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Quản trị phòng ban</h4>	
			  <?php if(session('flash_message_succ') != ''): ?>
		     	 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
		      <?php endif; ?>
			  <?php if(session('flash_message_err') != ''): ?>
		     	 <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
		      <?php endif; ?>
				<form class="form-horizontal" method="get" action="">
					<div class="form-group col-lg-6">
						<label for="hoten" class="col-sm-4 control-label">Tên phòng ban</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="title"  autocomplete="off" placeholder="Tên phòng ban" value="<?php echo e(Request::get('title')); ?>">
						</div>
					</div>
					 <div class="form-group col-lg-6">
			          <div class="text-center">
			            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
			          </div>
			        </div>
			        <?php echo e(csrf_field()); ?>

				</form>


			</div>
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách phòng ban 			                        
					<?php if(in_array('hoso-themphongban',$arr_route)): ?>
						<a href="<?php echo e(route('addDepartment')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
					<?php endif; ?>
            	</h4>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th>STT</th>
						      <th>Tên phòng ban </th>
						      <th>Thuộc phòng ban </th>
						      <th>Người quản lý</th>
						      <th>&nbsp;&nbsp;</th>
						    </tr>
						    <?php if(!empty($data)): ?>
								<?php 
									if( !isset($_GET['page']) || $_GET['page']==1 ){
										$i  = 1;
									}else{
										$i = ($_GET['page']*10 -10) +1;
									}
								?>
						     	<?php foreach($data as $val): ?>
						     <tr>
						      <td><?php echo e($i); ?></td>
						      <td> <?php echo e($val->title); ?> </td>
						      <td> <?php echo e(BatvHelper::getNameDepartmentbyId($val->parent_id)); ?> </td>
						      <td> <?php echo e(BatvHelper::getNamePersonnelbyId($val->manager_id)); ?> </td>
						      <td>
			                        <?php if(in_array('hoso-suaphongban',$arr_route)): ?>
							       		<a class="btn-edit" href="<?php echo e(route('getDepartmentEdit',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
			                        <?php endif; ?>
			                        <?php if(in_array('hoso-xoaphongban',$arr_route)): ?>
							       		<a class="btn-delete" href="<?php echo e(route('getDepartmentDel',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
							       		<img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
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
			<div class="col-lg-12 text-right">
				<?php echo e($data->appends(Request::all())->links()); ?> 
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>