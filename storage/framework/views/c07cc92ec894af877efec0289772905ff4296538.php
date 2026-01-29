

<?php $__env->startSection('title', 'TOH HRMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <!-- Danh muc -->
  <?php echo $__env->make('layouts.pages.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-10">
      <h4 class="title-fuction">Sửa Page</h4>
      <?php if(session('flash_message_succ') != ''): ?>
      <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
      <?php endif; ?>
      <?php if(count($errors) > 0): ?>
      <div class="alert alert-danger" role="alert">
        <ul>
            <?php foreach($errors->all() as $error): ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
      <form class="form-horizontal" method="POST" action="<?php echo e(url('/' )); ?>/toh_hrm/page/edit/<?php echo e($data->id); ?>">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
          <label class="col-sm-2 control-label">Tiêu đề <span class="required">*</span></label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="title"  required="required" value="<?php echo e(old('title',isset($data->title) ? $data->title : null )); ?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Nội dung <span class="required">*</span></label>
          <div class="col-sm-10">
            <textarea rows="4" onkeydown="expandtext(this);" name="content" requried><?php echo e(old('content',isset($data->content) ? $data->content : null )); ?></textarea>
            <script type="text/javascript">
              CKEDITOR.replace( 'content',
              {
                height: '500px',
                on: {
                      change: function() {
                          unsaved = true;
                      }
                }
              });

            </script>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10 text-center">
            <button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
          </div>
        </div>
      </form>
	</div>
	<div class="col-lg-2"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>