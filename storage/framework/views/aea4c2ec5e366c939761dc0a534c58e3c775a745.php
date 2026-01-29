

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>

<div class="row setting_salary">
		<!-- Danh muc -->
		<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<div class="col-lg-10">
			<h4 class="title-fuction">Cấu hình bộ tham số </h4> 
			<?php if(count($errors) > 0): ?>
				<div class="alert alert-danger" role="alert">
				<ul>
				    <?php foreach($errors->all() as $error): ?>
				        <li><?php echo e($error); ?></li>
				    <?php endforeach; ?>
				</ul>
				</div>
			<?php endif; ?>
			<?php if(session('flash_message_succ') != ''): ?>
				 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
			<?php endif; ?>
			<?php if(session('flash_message_err') != ''): ?>
				 <div class="alert alert-danger" role="alert"></span> <?php echo e(session('flash_message_err')); ?></div>
			<?php endif; ?>
			<div class="row">
				<?php echo $__env->make('layouts.luongthuong.menusetting', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		        <form class="form-horizontal" method="get" action="">
		          <div class="form-group col-lg-6">
		            <div class="row">

		              <div class="col-lg-offset-3 col-lg-3"><label for="inputBirthday" class="control-label">Tên tham số</label></div>
		              <div class="col-lg-6">
		                <input type="text" class="form-control" name="title" value="<?php echo e(Request::get('title')); ?>">
		              </div>
		            </div>
		          </div>
		           <div class="form-group col-lg-6">
		                <div class="text-center">
		                  <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
		                  <input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
		                </div>
		              </div>
		              <?php echo e(csrf_field()); ?>

		        </form>
			      <div class="col-lg-12">
			        <h4 class="title-fuction">Danh sách bộ tham số  
						<?php if(in_array('luongthuong-themcauhinhthamso',$arr_route)): ?>
				      	   <a href="<?php echo e(route('addParametersConfig')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
						<?php endif; ?>
			        </h4>
			        <div class="table-responsive"> 
			          <table class="table table-hover">
			            <tbody>
			              <tr>
			                <th>STT</th>
			                <th>Tên tham số </th>
			                <th>Kiểu</th>
			                <th>Giá trị</th>
			                <th></th>
			              </tr>
							<?php 
								if( !isset($_GET['page']) || $_GET['page']==1 ){
									$i  = 1;
								}else{
									$i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
								}
							?>
			              <?php foreach($data as $val): ?>
			              <tr>
			                <td><?php echo e($i); ?> </td> 
			                <td><?php echo e(str_limit( $val->title, $limit = 45, $end = '...')); ?></td>
			                <td><?php echo e(BatvHelper::getTypeParameters($val->is_fixed)); ?></td>
			                <td><?php echo e($val->value_org); ?> </td>
			                <td>
								<?php if(in_array('luongthuong-suacauhinhthamso',$arr_route)): ?>
									<a class="btn-edit" href="<?php echo e(route('editParametersConfig',['id'=>$val->id ])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
								<?php endif; ?>
								<?php if(in_array('luongthuong-xoacauhinhthamso',$arr_route)): ?>
				                  <a class="btn-delete" href="<?php echo e(route('deleteParametersConfig',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
					                    <img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
								<?php endif; ?>
			                </td>  
			              </tr>
			               	<?php $i++ ?>
			              <?php endforeach; ?>
			             
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
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>