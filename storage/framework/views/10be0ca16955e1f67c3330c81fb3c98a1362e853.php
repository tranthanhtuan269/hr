

<?php $__env->startSection('title', 'TOH HRMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('layouts.pages.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-10">
      <h4 class="title-fuction">Danh sách page
            <?php if(in_array('page-add',$arr_route)): ?>
              <a href="<?php echo e(route('getPageAdd')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
            <?php endif; ?>
      </h4>
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
      
      <div class="table-responsive"> 
        <table class="table table-hover">
          <tbody>
            <tr>
              <th>Tiêu đề</th>
              <th>Thao tác</th>
            </tr>
            <?php foreach($data as $val): ?>
            <tr>
              <td><a href="<?php echo e(route('getCategories',['cat'=> $val->slug ])); ?>"><?php echo e($val->title); ?></a></td> 
              <td>
                <?php if(in_array('page-edit',$arr_route)): ?>
                  <a class="btn-edit" href="<?php echo e(route('getPageEdit',['id'=> $val->id ])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                <?php endif; ?>
                <?php if(in_array('page-del',$arr_route)): ?>
                  <form action="<?php echo e(url('toh_hrm/page/del/'.$val->id)); ?>" method="POST">
                    <?php echo e(csrf_field()); ?>

                    <?php echo e(method_field('DELETE')); ?>

                    <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa ?');">
                    </button>
                  </form>
                <?php endif; ?>
              </td>  
            </tr>
            <?php endforeach; ?>
           
          </tbody>
        </table>
        </div>
      </div>
      <div class="col-lg-12 text-right">
        
      </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>