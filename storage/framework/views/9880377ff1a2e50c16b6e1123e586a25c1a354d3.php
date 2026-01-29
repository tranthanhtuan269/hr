

<?php $__env->startSection('title', 'Phòng ban'); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
	<div class="col-lg-offset-2 col-lg-8">
		<h4 class="title-fuction">Thêm phòng ban</h4>
		<div class="row">
			<div class="col-lg-12">
				<?php if(session('flash_message_succ') != ''): ?>
					 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
				<?php endif; ?>
			</div>
			<div class="col-lg-offset-2 col-lg-8">
		        <form  class="form-horizontal" method="POST">
		          <div class="form-group">
			          <label class="col-sm-4 control-label"> Tên phòng ban</label>
			            <div class="col-sm-8">
			              <input type="text" class="form-control" name="title"  required="required">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label"> Thuộc phòng ban</label>
			            <div class="col-sm-8">
	                        <?php if(!empty($department)): ?>
	                            <select name="parent_id" class="form-control">
	                                <option value="0" selected>--Trống--</option>
									<?php echo $department; ?>

	                            </select>
	                        <?php endif; ?>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Người quản lý</label>
			            <div class="col-sm-8">
	                        <?php if(!empty($listPersonnel)): ?>
	                            <select name="manager_id" id="my-select" required>
	                                <?php foreach($listPersonnel as $val): ?>
	                                     <option value="<?php echo e($val->id); ?>"><?php echo e($val->fullname); ?></option>
	                                <?php endforeach; ?>
	                            </select>
	                        <?php endif; ?>
							<script type="text/javascript">
								$(function() {
								    $('#my-select').searchableOptionList({
								        maxHeight: '250px'
								    });
								}); 
							</script>
			            </div>
		          </div>

					<div class="form-group">
						<label class="col-sm-4 control-label">Người chấm công</label>
						<div class="col-sm-8">	
	                        <?php if(!empty($listPersonnel)): ?>
	                            <select id="my-select-2" name="personnel_attendance[]" multiple="multiple">
	                                <?php foreach($listPersonnel as $val): ?>
	                                     <option value="<?php echo e($val->id); ?>"><?php echo e($val->fullname); ?></option>
	                                <?php endforeach; ?>
	                            </select>
	                        <?php endif; ?>
							<script type="text/javascript">
								$(function() {
								    $('#my-select-2').searchableOptionList({
								        showSelectAll: true,
								        maxHeight: '250px',
								    });
								});    
							</script>
					    </div>
					</div>

		          <div class="text-center">
		            <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Thêm mới">
		          </div>
		            <?php echo e(csrf_field()); ?>

		        </form>
			</div>
		</div>
	</div>
		
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>