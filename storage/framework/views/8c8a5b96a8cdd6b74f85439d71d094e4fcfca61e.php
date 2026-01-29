

<?php $__env->startSection('title', 'Quá trình công tác'); ?>

<?php $__env->startSection('content'); ?>
<div class="row content-function">
	<!-- Danh muc -->
	<?php echo $__env->make('layouts.hoso.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<div class="col-lg-9">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Quản trị quá trình công tác</h4>
				<?php if(session('flash_message_succ') != ''): ?>
					 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
				<?php endif; ?>
				<form class="form-horizontal" method="get" action="">
					<div class="form-group col-lg-6">
						<label for="hoten" class="col-sm-4 control-label">Họ tên</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="hoten" id="hoten" autocomplete="off" placeholder="Họ và tên" value="<?php echo e(Request::get('hoten')); ?>">
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
						 <div class="form-group">
				          <div class="text-center">
				            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
				          </div>
				        </div>
			        </div>
			        <?php echo e(csrf_field()); ?>

				</form>
			</div>
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách hồ sơ</h4>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th>Họ và tên</th>
						      <th>Email </th>    
						      <th>Đơn vị</th>
						      <th>Thâm niên</th>
						      <th>Quá trình công tác</th>
						    </tr>
						    <?php if(!empty($data)): ?>
						     	<?php foreach($data as $val): ?>
						     <tr>
						      <td><?php echo e($val->fullname); ?></td>
						      <td> <?php echo e($val->email); ?> </td>
						      <td><?php echo e($val->title); ?></td>
						      <td><?php echo e(BatvHelper::getSeniority($val->id)); ?></td>
						      <td>
									<?php if(in_array('quatrinh-detail',$arr_route)): ?>
									   <a href="<?php echo e(route('getHistoryDetail',['id'=>$val->id ])); ?>">Chi tiết</a>
									<?php endif; ?>
						      </td>
						    </tr>
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